<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\CommentController;
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

    Route::group(['prefix' => 'user'], function($router){

        $router->match(['post', 'get'],'/{channel}/follow' ,[
            UserController::class, 'follow'
        ])->name('user.follow');

        $router->match(['post', 'get'],'/{channel}/unfollow' ,[
            UserController::class, 'unfollow'
        ])->name('user.unfollow');

        $router->get('/followings' ,[
            UserController::class, 'followings'
        ])->name('user.followings');

        $router->get('/followers' ,[
            UserController::class, 'followers'
        ])->name('user.followers');

        $router->delete('/me' ,[
            UserController::class, 'unregister'
        ])->name('user.unregister');

        $router->get('/me' ,[
          UserController::class, 'me'
      ])->name('user.me');
    });

});

Route::group(['prefix' => '/channel'], function($router){

    Route::group(['middleware' => ['auth:api']], function($router){

    $router->put('/{id?}' ,[
        ChannelController::class, 'update'
    ])->name('channel.update');

    $router->match(['post', 'put'],'/' ,[
        ChannelController::class, 'uploadBanner'
    ])->name('channel.upload.banner');

    $router->match(['post', 'put'],'/socials' ,[
        ChannelController::class, 'updateSocials'
    ])->name('channel.update.socials');

    $router->get('/statistics' ,[
        ChannelController::class, 'statistics'
    ])->name('channel.statistics');
  });
  
  $router->get('/{channel}', [
    ChannelController::class, 'info'
    ])->name('channel.info');
});

Route::group(['middleware' => [], 'prefix' => '/video'], function($router){
    $router->match(['get', 'post'],'/{video}/like', [
        VideoController::class, 'like'
    ])->name('video.like');

    $router->match(['get', 'post'],'/{video}/unlike', [
        VideoController::class, 'unlike'
    ])->name('video.unlike');

    $router->get('/', [
        VideoController::class, 'list'
    ])->name('video.list');

    $router->get('/{video}/show', [
        VideoController::class, 'show'
    ])->name('video.show');
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
        ])->name('video.change.state');

        $router->put('/{video}', [
            VideoController::class, 'update'
        ])->name('video.update');

        $router->get('/liked', [
            VideoController::class, 'likedByCurrentUser'
        ])->name('video.liked');

        $router->get('/{video}/statistics', [
            VideoController::class, 'statistics'
        ])->name('video.listatisticsked');

        $router->get('/favourites', [
            VideoController::class, 'favourites'
        ])->name('video.favourites');

        $router->delete('/{video}', [
            VideoController::class, 'delete'
        ])->name('video.delete');
   
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

    $router->get('/{playlist}', [
        PlaylistController::class, 'show'
    ])->name('playlist.show');

    $router->post('/', [
        PlaylistController::class, 'create'
    ])->name('playlist.create');
    
    $router->match(['post', 'put'],'/{playlist}/sort', [
        PlaylistController::class, 'sortVideos'
    ])->name('playlist.sort');
    
    $router->match(['post', 'put'],'/{playlist}/{video}', [
        PlaylistController::class, 'addVideo'
    ])->name('playlist.add-video');

});

Route::group(['middleware' => ['auth:api'], 'prefix' => '/tag'], function($router){
    $router->get('/', [
        tagController::class, 'index'
    ])->name('tag.all');

    $router->post('/', [
        tagController::class, 'create'
    ])->name('tag.create');

});


Route::group(['middleware' => ['auth:api'], 'prefix' => '/comment'], function($router){
    $router->get('/', [
        CommentController::class, 'index'
    ])->name('comment.all');

    $router->post('/', [
        CommentController::class, 'create'
    ])->name('comment.create');

    $router->match(['post', 'put'],'/{comment}/state', [
        CommentController::class, 'changeState'
    ])->name('comment.change.state');

    $router->delete('/{comment}', [
        CommentController::class, 'delete'
    ])->name('comment.delete');
}); 

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
