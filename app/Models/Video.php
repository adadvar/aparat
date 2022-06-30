<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $table = 'videos';

    protected $fillable = ['title', 'user_id', 'category_id', 'channel_category_id', 'slug', 'info', 'duration', 'banner', 'publish_at'];


    public function playlist(){
        return $this->belongsToMany(playlist::class, 'playlist_videos');

    }

    public function tags(){
        return $this->belongsToMany(Tag::class, 'video_tags');

    }
}
