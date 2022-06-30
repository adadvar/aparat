<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Controllers\AccessTokenController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([], function($router){  
    Route::group(['namespace' => '\Laravel\Passport\Http\Controllers'], function($router){
        $router->post('login', [
            'middleware' => ['throttle'],
            AccessTokenController::class,'issueToken',
        ])->name('auth.login ');
    });
    $router->post('register', [
        AuthController::class, 'register'
    ])->name('auth.register');

    $router->post('register-verify', [
        AuthController::class, 'registerVerify'
    ])->name('auth.register.verify');

    $router->post('resend-verification-code', [
        AuthController::class, 'resendVerificationCode'
    ])->name('auth.register.resend.verification.code');
});

Route::group(['middleware' => ['auth:api']], function($router){

    $router->post('change-email', [
        UserController::class, 'changeEmail'
    ])->name('change.email');
    
    $router->match(['post', 'put'], 'change-password', [
        UserController::class, 'changePassword'
    ])->name('change.password');
});

Route::group(['middleware' => ['auth:api'], 'prefix' => '/channel'], function($router){

    $router->put('/{id?}' ,[
        ChannelController::class, 'update'
    ])->name('channel.update');

    $router->match(['post', 'put'],'/' ,[
        ChannelController::class, 'uploadBanner'
    ])->name('channel.upload.banner');

    $router->match(['post', 'put'],'/socials' ,[
        ChannelController::class, 'updateSocials'
    ])->name('channel.update.socials');
});

Route::group(['middleware' => ['auth:api'], 'prefix' => '/video'], function($router){
  
    $router->post('/upload', [
        VideoController::class, 'upload'
    ])->name('video.upload');

    $router->post('/upload-banner', [
        VideoController::class, 'uploadBanner'
    ])->name('video.upload.banner');

    $router->post('/', [
        VideoController::class, 'create'
    ])->name('video.create');
});




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
