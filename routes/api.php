<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::post('login','AuthController@login');

Route::group(['prefix'=>'auth'], function ($router) {
    Route::post('register', [\App\Http\Controllers\Auth\RegisterController::class, 'store']);
    Route::post('login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);
    Route::group(['middleware' => 'api.auth'], function ($router) {
        Route::post('logout', 'AuthController@logout');//退出登录
        Route::post('refresh', 'AuthController@refresh');// 刷新token
        Route::post('me', 'AuthController@me');
    
    });
});

// Route::post('/member',function(){ return 'member页面';};);
Route::post('logout', function(){ return 'member页面';});

// 路由分组
