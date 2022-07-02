<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\tagController;
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

    $router->post('/{video}/republish', [
        VideoController::class, 'republish'
    ])->name('video.republish');

    $router->put('/{video}/state', [
        VideoController::class, 'changeState'
    ])->name('change.state');

    $router->get('/', [
        VideoController::class, 'list'
    ])->name('video.list');
});

Route::group(['middleware' => ['auth:api'], 'prefix' => '/category'], function($router){
    $router->get('/', [
        CategoryController::class, 'index'
    ])->name('category.all');

    $router->get('/my', [
        CategoryController::class, 'my'
    ])->name('category.my');

    $router->post('/upload-banner', [
        CategoryController::class, 'uploadBanner'
    ])->name('category.upload.banner');

    $router->post('/', [
        CategoryController::class, 'create'
    ])->name('category.create');
});


Route::group(['middleware' => ['auth:api'], 'prefix' => '/playlist'], function($router){
    $router->get('/', [
        PlaylistController::class, 'index'
    ])->name('playlist.all');

    $router->get('/my', [
        PlaylistController::class, 'my'
    ])->name('playlist.my');

    $router->post('/', [
        PlaylistController::class, 'create'
    ])->name('playlist.create');
});

Route::group(['middleware' => ['auth:api'], 'prefix' => '/tag'], function($router){
    $router->get('/', [
        tagController::class, 'index'
    ])->name('tag.all');

    $router->post('/', [
        tagController::class, 'create'
    ])->name('tag.create');

});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
