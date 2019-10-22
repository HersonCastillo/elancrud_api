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
});
Route::group(['middleware' => 'jwt.auth'], function(){
    Route::prefix('app')->group(function(){
        Route::resource('categories', 'CategoryController');
        Route::resource('products', 'ProductController');
    });
});