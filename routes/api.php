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

// Authorization

Route::group(['prefix'=>'auth'], function ($router) {
    Route::post('register', [\App\Http\Controllers\Auth\RegisterController::class, 'store']);
    Route::post('login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);
    Route::group(['middleware' => 'api.auth'], function ($router) {
        Route::post('logout', 'AuthController@logout');//退出登录
        Route::post('refresh', 'AuthController@refresh');// 刷新token
        Route::post('me', 'AuthController@me');
    });
});

Route::group(['middleware' => 'api.auth','prefix'=>'admin'], function ($router) {
    // 用户管理
    Route::post('user', [\App\Http\Controllers\Admin\UserController::class, 'getUser']);//获取用户列表
    Route::post('lock', [\App\Http\Controllers\Admin\UserController::class, 'lock']);//修改用户状态
    // 分类管理
    Route::post('category', [\App\Http\Controllers\Admin\CategoryController::class, 'index']);//获取列表
    Route::post('category/edit', [\App\Http\Controllers\Admin\CategoryController::class, 'edit']);//添加修改分类
    Route::post('category/show', [\App\Http\Controllers\Admin\CategoryController::class, 'show']);//分类详情
    Route::post('category/status', [\App\Http\Controllers\Admin\CategoryController::class, 'status']);//更改分类状态
    // 商品管理
    Route::post('goods/edit', [\App\Http\Controllers\Admin\GoodsController::class, 'edit']);//添加商品
    Route::post('goods', [\App\Http\Controllers\Admin\GoodsController::class, 'index']);//获取商品列表
});



// Route::post('/member',function(){ return 'member页面';};);
Route::post('logout', function(){ return 'member页面';});

// 路由分组
