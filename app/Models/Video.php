<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Video extends Model
{
    use HasFactory, SoftDeletes;

    const STATE_PENDING= 'pending';
    const STATE_CONVERTED= 'converted';
    const STATE_ACCEPTED= 'accepted';
    const STATE_BLOCKED= 'blocked';
    const STATE = [self::STATE_PENDING, self::STATE_CONVERTED, self::STATE_ACCEPTED, self::STATE_BLOCKED];

    protected $table = 'videos';

    protected $fillable = ['title', 'user_id', 'category_id', 'channel_category_id', 'slug', 'info', 'duration', 'banner', 'publish_at', 'enable_comments', 'state'];

    protected $with = ['playlist', 'tags'];

    protected $appends = ['likeCount', 'age'];

    public function playlist(){
        return $this->belongsToMany(playlist::class, 'playlist_videos');

    }

    public function tags(){
        return $this->belongsToMany(Tag::class, 'video_tags');

    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function isInState($state){
        return $this->state == $state;
    }

    public function isPending(){
        return $this->isInState(self::STATE_PENDING);
    }

    public function isAccepted(){
        return $this->isInState(self::STATE_ACCEPTED);
    }

    public function isConverted(){
        return $this->isInState(self::STATE_CONVERTED);
    }

    public function isBlocked(){
        return $this->isInState(self::STATE_BLOCKED);
    }

    public function isRepublished($userId = null){
        if($userId) {
            return (bool)$this->republishes()->where('user_id', $userId)->count();
        }

        return (bool)$this->republishes()->count();
    }

    public function getVideoLinkAttribute(){

        return Storage::disk('videos')
            ->url($this->user_id . '/' . $this->slug . '.mp4');
    }

    public function getBannerLinkAttribute(){

        return $this->banner 
        ? Storage::disk('videos')
            ->url($this->user_id . '/' . $this->slug . '-banner') . '?v=' . $this->updated_at->timestamp
            :asset('/img/no-video.jpg');
    }

    public function getLikeCountAttribute()
    {
        return $this->likes()->count();
    }

    public function getAgeAttribute()
    {
        return Carbon::now()->diffInDays($this->created_at);
    }

    public function toArray()
    {
        $data = parent::toArray();

        $data['link'] = $this->video_link;
        $data['banner_link'] = $this->banner_link;
        $data['views'] = VideoView::where('video_id', $this->id)->count();

        return $data;
    }

    public static function whereNotRepublished()
    {
        return static::whereRaw('id not in (select video_id from video_republishes)');
    }

    public static function whereRepublished()
    {
        return static::whereRaw('id in (select video_id from video_republishes)');
    }

    public static function channelComments($userId)
    {
        return static::where('videos.user_id', $userId)
            ->join('comments', 'videos.id', '=', 'comments.video_id');
    }

    public function viewers(){
        return $this
            ->belongsToMany(User::class, 'video_views')
            ->withTimestamps();
    }

    public static function views($userId){
        return static::where('videos.user_id', $userId)
            ->join('video_views', 'videos.id', '=', 'video_views.video_id');
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function likes(){
        return $this->hasMany(VideoFavourite::class);
    }

    public function related(){
         return static::selectRaw('COUNT(*) related_tags, videos.*')
            ->leftJoin('video_tags', 'videos.id', '=', 'video_tags.video_id')
            ->whereRaw('videos.id != ' . $this->id)
            ->whereRaw("videos.state = '" . self::STATE_ACCEPTED . "'")
            ->whereIn(DB::raw('video_tags.tag_id'), function ($query) {
                $query->selectRaw('video_tags.tag_id')
                    ->from('videos')
                    ->leftJoin('video_tags', 'videos.id', '=', 'video_tags.video_id')
                    ->whereRaw('videos.id=' . $this->id);
            })
            ->groupBy(DB::raw('videos.id'))
            ->orderBy('related_tags', 'desc');
    }

    public function republishes() {
        return $this->hasMany(VideoRepublish::class, 'video_id', 'id');
    }
}
