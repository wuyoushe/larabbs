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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace('api')->prefix('v1')->middleware('cors')->group(function() {
    //用户注册
    Route::post('/users', 'UserController@store')->name('users.store');
    //用户登录
    Route::post('/login', 'UserController@login')->name('users.login');

    Route::middleware('api.refresh')->group(function(){
        //当前用户信息
        Route::get('/users/info','UserController@info')->name('users.info');
        //用户列表
        Route::get('/users','UserController@index')->name('users.index');
        //用户信息
        Route::get('/users/{user}','UserController@show')->name('users.show');
        //用户退出
        Route::get('/logout', 'UserController@logout')->name('users.logout');
    });
});

// Route::prefix('v1')->name('api.v1.')->group(function() {
//     Route::get('version', function() {
//         abort(403, 'test');
//         return 'this is version v1';
//     })->name('version');
// });

Route::prefix('v1')->name('api.v1.')->group(function() {

});

