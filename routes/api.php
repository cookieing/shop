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
});


// 路由分组
Route::group(['middleware' => 'api','prefix'=>'auth'], function ($router) {
    
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});