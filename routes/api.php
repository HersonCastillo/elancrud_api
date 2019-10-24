<?php

use Illuminate\Http\Request;

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

Route::prefix('auth')->group(function(){
    Route::post('signin', 'AuthController@signin');
    Route::post('signup', 'AuthController@signup');
    Route::get('user', 'AuthController@user');
});
Route::group(['middleware' => 'jwt.auth'], function(){
    Route::prefix('app')->group(function(){
        Route::resource('categories', 'CategoryController');
        Route::resource('products', 'ProductController');
        Route::resource('likes', 'LikeController');
    });
});
Route::prefix('pub')->group(function(){
    Route::get('products', 'ProductController@index');
    Route::get('categories', 'CategoryController@index');
});