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

Route::post('login', 'Auth\AuthController@login');
Route::post('signup', 'Auth\AuthController@signup');


Route::middleware('auth:api')->group(function () {
    Route::group([
        'prefix' => 'auth'
    ], function () {

        Route::group([
            'middleware' => 'auth:api'
        ], function () {
            Route::get('logout', 'Auth\AuthController@logout');
            Route::post('user', 'Auth\AuthController@user');
            Route::resource('posts', 'Admin\PostController')->only([
                'store', 'create', 'update', 'destroy', 'index', 'show'

            ]);
        });
    });
});
