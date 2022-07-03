<?php

namespace App\Http\Controllers;

use App\Http\Requests\Channel\FollowChannelRequest;
use App\Http\Requests\Channel\UnFollowChannelRequest;
use App\Http\Requests\User\ChangeEmailRequest;
use App\Http\Requests\User\ChangeEmailSubmitRequest;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Http\Requests\User\FollowUserRequest;
use App\Http\Requests\User\UnFollowUserRequest;
use App\Services\UserService;


class UserController extends Controller
{
    const CHANGE_EMAIL_CACHE_KEY = 'change.email.for.user.';

    public function changeEmail(ChangeEmailRequest $request){
        return UserService::changeEmail($request);
    }

    public function changeEmailSubmit(ChangeEmailSubmitRequest $request){
        return UserService::changeEmailSubmit($request);
    }

    public function changePassword(ChangePasswordRequest $request){
        return UserService::changePassword($request);
    }

    public function follow(FollowUserRequest $request){
        return UserService::follow($request);
    } 

    public function unfollow(UnFollowUserRequest $request){
        return UserService::unfollow($request);
    } 
}
