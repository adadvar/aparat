<?php

use App\Http\Controllers\AuthController;
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
Route::group(['namespace' => '\Laravel\Passport\Http\Controllers'], function($router){
    $router->post('login', [
        'middleware' => ['throttle'],
        AccessTokenController::class,'issueToken',
    ])->name('auth.login ');
});

Route::post('register', [
    AuthController::class, 'register'
]);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
