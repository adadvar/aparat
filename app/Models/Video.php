<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    const STATE_PENDING= 'pending';
    const STATE_CONVERTED= 'converted';
    const STATE_ACCEPTED= 'accepted';
    const STATE_BLOCKED= 'blocked';
    const STATE = [self::STATE_PENDING, self::STATE_CONVERTED, self::STATE_ACCEPTED, self::STATE_BLOCKED];

    protected $table = 'videos';

    protected $fillable = ['title', 'user_id', 'category_id', 'channel_category_id', 'slug', 'info', 'duration', 'banner', 'publish_at', 'enable_comments', 'state'];


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

    public function toArray()
    {
        $data = parent::toArray();

        $conditions = [
            'video_id' => $this->id,
            'user_id' => auth('api')->check() ? auth('api')->id() : null,
        ];

        if (!auth('api')->check()){
            $conditions['user_ip'] = client_ip();
        }

        $data['liked'] = VideoFavourite::where($conditions)->count();

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
}