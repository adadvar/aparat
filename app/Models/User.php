<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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
        $user = static::where('mobile', $usrename)->orWhere('email', $usrename)->first();
        return $user;
    }

    public function setMobileAttribute($value){
        $this->attributes['mobile'] = to_valid_mobile_number($value);
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

    public function videos(){
        return $this->hasMany(Video::class);
    }

    public function republishedVideos(){
        return $this->hasManyThrough(Video::class,
            VideoRepublish::class,
            'user_id', //republishes_video.user_id
            'id', //video.id
            'id', //user.id
            'video_id', //republishes_video.video_id
            );
    }
}
