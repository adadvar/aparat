<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    const TYPE_ADMIN = 'admin';
    const TYPE_USER = 'user';
    const TYPES = [self::TYPE_ADMIN, self::TYPE_USER];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'users';

    protected $fillable = [
        'type',
        'mobile',
        'email',
        'name',
        'password',
        'avatar',
        'website',
        'verify_code',
        'verify_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'verify_code',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'verified_at' => 'datetime',
    ];
 
    public function findForPassport($usrename){
        $user = static::withTrashed()->where('mobile', $usrename)->orWhere('email', $usrename)->first();
        return $user;
    }

    public function setMobileAttribute($value){
        $this->attributes['mobile'] = to_valid_mobile_number($value);
    }

    public function getAvatarAttribute(){
        return $this->attributes['avatar']
        ? $this-> attributes['avatar']
        : asset('img/avatar.png') ;
    }

    public function channel(){
        return $this->hasOne(Channel::class);
    }

    public function categories(){
        return $this->hasMany(Category::class);
    }

    public function playlists(){
        return $this->hasMany(Playlist::class);
    }

    public function isAdmin(){
        return $this->type === User::TYPE_ADMIN;
    }

    public function isBaseUser(){
        return $this->type === User::TYPE_USER;
    }

    public function favouriteVideos(){
        return $this->hasManyThrough(Video::class,
            VideoFavourite::class,
            'user_id', //republishes_video.user_id
            'id', //video.id
            'id', //user.id
            'video_id', //republishes_video.video_id
            )->selectRaw('videos.*, true as republished');
    }

    public function channelVideos()
    {
        return $this->hasMany(Video::class)
            ->selectRaw('*, 0 as republished');
    }

    public function republishedVideos()
    {
        return $this->hasManyThrough(
            Video::class,
            VideoRepublish::class,
            'user_id', // republishes_video.user_id
            'id', // video.id
            'id', // user.id
            'video_id' // republishes_video.video_id
        )
            ->selectRaw('videos.*, 1 as republished');
    }

    public function videos()
    {
        return $this->channelVideos()
            ->union($this->republishedVideos());
    }

    public function follow(User $user){
        return UserFollowing::create([
            'user_id1' => $this->id,
            'user_id2' => $user->id,
        ]);
    }

    public function unfollow(User $user){
        return UserFollowing::where([
            'user_id1' => $this->id,
            'user_id2' => $user->id,
        ])->delete();
    }

    public function followings(){
        return $this->hasManyThrough(User::class, UserFollowing::class,
            'user_id1',
            'id',
            'id',
            'user_id2');
    }

    public function followers(){
        return $this->hasManyThrough(User::class, UserFollowing::class,
            'user_id2',
            'id',
            'id',
            'user_id1');
    }

    public function views(){
        return $this
            ->belongsToMany(Video::class, 'video_views')
            ->withTimestamps();
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public static function boot(){
        parent::boot();

        static::deleting(function($comment){
            $comment->channelVideos()->delete();
            $comment->playlists()->delete();
        });
        static::restoring(function ($user) {
            $user->channelVideos()->restore();
            $user->playlists()->restore();
        });
    }
}
