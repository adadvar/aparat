<?php

namespace App\Http\Controllers;


use App\Http\Requests\User\ChangeEmailRequest;
use App\Http\Requests\User\ChangeEmailSubmitRequest;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Http\Requests\User\FollowingUserRequest;
use App\Http\Requests\User\FollowUserRequest;
use App\Http\Requests\User\UnFollowUserRequest;
use App\Http\Requests\User\UnregisterUserRequest;
use App\Http\Requests\User\UserDeleteRequest;
use App\Http\Requests\User\UserListRequest;
use App\Http\Requests\User\UserLogoutRequest;
use App\Http\Requests\User\UserMeRequest;
use App\Http\Requests\User\UserResetPasswordRequest;
use App\Http\Requests\User\UserUpdateRequest;
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

    public function followings(FollowingUserRequest $request){
         return UserService::followings($request);
    }

    public function followers(FollowingUserRequest $request){
        return UserService::followers($request);
   }

   public function unregister(UnregisterUserRequest $request){
    return UserService::unregister($request);
  }

  public function me(UserMeRequest $request){
    return UserService::me($request);
  }

  public function list(UserListRequest $request){
    return UserService::list($request);
  }

  public function update(UserUpdateRequest $request){
    return UserService::update($request);
  }

  public function resetPassword(UserResetPasswordRequest $request){
    return UserService::resetPassword($request);
  }

  public function delete(UserDeleteRequest $request){
    return UserService::delete($request);
  }

  public function logout(UserLogoutRequest $request){
    return UserService::logout($request);
  }

}
