<?php
namespace App\Services;

use App\Exceptions\UserAlreadyRegisteredException;
use App\Http\Requests\Auth\RegisterNewUserRequest;
use App\Http\Requests\Auth\RegisterVerifyUserRequest;
use App\Http\Requests\Auth\ResendVerificationCodeRequest;
use App\Http\Requests\User\ChangeEmailRequest;
use App\Http\Requests\User\ChangeEmailSubmitRequest;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Http\Requests\User\FollowingUserRequest;
use App\Http\Requests\User\FollowUserRequest;
use App\Http\Requests\User\UnFollowUserRequest;
use App\Http\Requests\User\UnregisterUserRequest;
use App\Http\Requests\User\UserLogoutRequest;
use App\Http\Requests\User\UserMeRequest;
use App\Mail\VerificationCodeMail;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService extends BaseService {
    const CHANGE_EMAIL_CACHE_KEY = 'change.email.for.user.';

    public static function registerNewUser(RegisterNewUserRequest $request){

        try {
            DB::beginTransaction();
            $field = $request->getFieldName();
            $value = $request->getFieldValue();

            $user = User::where($field, $value)->first();
            if($user){
                if($user->verified_at){
                    throw new UserAlreadyRegisteredException('you are registered befor!');
                }
                return response(['message' => 'user is exist and code sended befor!']);
            }
            $code = random_verification_code();
            $user = User::create([
                $field => $value,
                'verify_code' => $code,
            ]);


            Log::info('SEND-REGISTER-CODE-MESSAGE-TO-USER', ['code' => $code]);
            if($request->getFieldName() === 'email') {
                Mail::to($request->user())->send(new VerificationCodeMail($code));
            }
            else {
                \Kavenegar::Send(config('kavenegar.sender'),$value, 'کد فعال سازی' . $code);
            }

            DB::commit();
            return response(['message' => 'user registered'], 200);

        } catch (Exception $e) {
            DB::rollBack();
            
            if($e instanceof UserAlreadyRegisteredException) {
                throw $e;
            }

            Log::error($e);
            return response(['message' => 'An error has occurred !']);
        }
    }

    public static function registerNewUserVerify(RegisterVerifyUserRequest $request){
        $field = $request->has('email') ? 'email' : 'mobile';
        $value = $request->input($field);
        $code = $request->code;

        $user = User::where(['verify_code' => $code , $field => $value])->first();
        if(empty($user)){
            throw new ModelNotFoundException('user not found!'); 
        }
        
        $user->verify_code = null;
        $user->verified_at = now();
        $user->save();

        response($user, 200);
    }

    public static function resendVerificationCodeUser(ResendVerificationCodeRequest $request){
        $field = $request->getFieldName();
        $value = $request->getFieldValue();

        $user = User::where($field, $value)->whereNull('verified_at')->first();

        if(!empty($user)){
            $dateDiff = now()->diffInMinutes($user->updated_at);
            if($dateDiff > config('auth.resend_verification_code_time_diff', 60)){
                $user->verify_code = random_verification_code();
                $user->save(); 
            }
            

            Log::info('RESEND-REGISTER-CODE-MESSAGE-TO-USER', ['code' => $user->verify_code]);

            return response([
                'message' => 'code resended for you'
            ], 200);

        }

        throw new ModelNotFoundException('user not found or befor registered');
    }

    public static function changeEmail(ChangeEmailRequest $request){
        try {
            $email = $request->email;
            $userId = auth()->id();
            $code = random_verification_code();
            $expireDate = now()->addMinutes(config('auth.change_email_cache_expiration', 1440));
             Cache::put(self::CHANGE_EMAIL_CACHE_KEY . $userId, compact('email', 'code'), $expireDate);

            Log::info('SEND_CHANGE_EMAIL_CODE', compact('code'));
            return response([
                'message' => 'an email sended for you plz check it'
            ], 200);

        } catch (Exception $e) {
            Log::error($e);
            return response([
                'message' => 'an error has occurred and the server was unable to send the activation code'
            ], 500);
        }
    }

    public static function changeEmailSubmit(ChangeEmailSubmitRequest $request){
        $userId = auth()->id();
        $cacheKey = self::CHANGE_EMAIL_CACHE_KEY. $userId;
        $cache = Cache::get($cacheKey);

        if(empty($cache) || $cache['code'] != $request->code ){
            return response([
                'message' => 'invalid request'
            ], 400);
        }

        $user = auth()->user();
        $user->email = $cache['email'];
        $user->save();
        Cache::forget($cacheKey);
        return response([
            'message' => 'email has changed successfully'
        ], 200);
    }

    public static function changePassword(ChangePasswordRequest $request) {
        try {
            $user = auth()->user();

            if(!Hash::check($request->old_password, $user->password)) {
                return response(['message' => 'The password entered does not match'], 400);
            }

            $user->password = bcrypt(($request->new_password));
            $user->save();

            return response([
                'message' => 'password changed successfully!'
            ], 200);

        }catch(Exception $e){
            Log::error($e);
            return response(['message' => 'An error has occurred !'], 500);
        }
    }

    public static function follow(FollowUserRequest $request){
        $user = $request->user();
        $user->follow($request->channel->user);
        return response(['message' => 'followed successfully!'], 200);
    }

    public static function unfollow(UnFollowUserRequest $request){
        $user = $request->user();
        $user->unfollow($request->channel->user);
        return response(['message' => 'unfollowed successfully!'], 200);
    }

    public static function followings(FollowingUserRequest $request) {
        return $request->user()
            ->followings()
            ->leftJoin('channels', 'users.id', 'channels.user_id')
            ->leftJoin('followers as followers2', 'users.id', 'followers2.user_id2')
            ->leftJoin('videos', 'users.id', 'videos.user_id')
            ->groupBy('users.id')
            ->get([
              'users.id', 'channels.name', 'channels.banner', 'avatar', 'website', 
              DB::raw('count(followers2.user_id2) as followers_count'),
              DB::raw('count(videos.user_id) as videos_count'),
              'followers.created_at']);
    }

    public static function followers(FollowingUserRequest $request) {
        return $request->user()
            ->followers()
            ->leftJoin('channels', 'users.id', 'channels.user_id')
            ->leftJoin('followers as followers2', 'users.id', 'followers2.user_id2')
            ->leftJoin('videos', 'users.id', 'videos.user_id')
            ->groupBy('users.id')
            ->get([
              'users.id', 'channels.name', 'channels.banner', 'avatar', 'website', 
              DB::raw('count(followers2.user_id2) as followers_count'),
              DB::raw('count(videos.user_id) as videos_count'),
              'followers.created_at']);
    }

    public static function unregister(UnregisterUserRequest $request){
        try{
            DB::beginTransaction();
            $request->user()->delete();
            DB::table('oauth_access_tokens')
                ->where('user_id', $request->user()->id)
                ->delete();
            DB::commit();
            return response(['message' => 'unregistered successfully!'], 200);
        }catch(Exception $e){
            DB::rollBack();
            Log::error($e);
            return response(['message' => 'An error has occurred !'], 500); 
        }
    }

    public static function me(UserMeRequest $request){
      $result = User::where('id', $request->user()->id)
        ->with(['channel'])
        ->first();

      return $result;
    }

    public static function logout(UserLogoutRequest $request){
        try {
            $request->user()->currentAccessToken()->revoke();

            return response(['message' => 'loguted successfully'], Response::HTTP_OK);
        }catch(Exception $e) {
            Log::error($e);
        }

        return response(['message' => 'logout failed'], Response::HTTP_BAD_REQUEST);
    }
}