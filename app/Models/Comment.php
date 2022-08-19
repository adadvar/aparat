<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    const STATE_PENDING = 'pending';
    const STATE_READ = 'read';
    const STATE_ACCEPTED = 'accepted';
    const STATES = [
        self::STATE_PENDING,
        self::STATE_READ,
        self::STATE_ACCEPTED,
    ];

    protected $table = 'comments';

    protected $fillable = ['user_id', 'video_id', 'parent_id', 'body', 'state'];

    protected $appends=['age'];

    public function video(){
        return $this->belongsTo(Video::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function parent(){
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function children(){
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public static function channelComments($userId){
      $path = asset('videos/' . $userId) . '/';
        return  Comment::join('videos', 'comments.video_id', '=', 'videos.id')
          ->where('videos.user_id', $userId)
          ->selectRaw('comments.*, videos.banner as video_banner, "' . $path . '" as banner_path');
    }

    public function getAgeAttribute()
    {
        return Carbon::now()->diffInDays($this->created_at);
    }

    public static function boot(){
        parent::boot();

        static::deleting(function($comment){
            $comment->children()->delete();
        });
    }

}
