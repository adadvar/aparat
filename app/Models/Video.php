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
}
