<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterNewUserRequest;
use App\Http\Requests\Auth\RegisterVerifyUserRequest;
use App\Http\Requests\Auth\ResendVerificationCodeRequest;
use App\Services\UserService;


class AuthController extends Controller
{
    public function register(RegisterNewUserRequest $request){
        
        // $expiration = config('auth.register_cache_expiration', 1440);
        // Cache::put('user-auth-register-' . $value, compact('code', 'field'), now()->addMinutes($expiration)) ;
        
        return UserService::registerNewUser($request);
    } 

    public function registerVerify(RegisterVerifyUserRequest $request){
        
        return UserService::registerNewUserVerify($request);
        // $registerData = Cache::get('user-auth-register-' . $field); 

        // if($registerData && $registerData['code'] == $code){
            
        // }
        
        // throw new RegisterVerificationException('register code is wrong!');
    }

    public function resendVerificationCode(ResendVerificationCodeRequest $request){
        return UserService::resendVerificationCodeUser($request);
    }
}
 