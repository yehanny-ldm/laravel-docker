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
            Route::post('/post/uploadImage', 'Admin\PostController@uploadImage');
            Route::get('/category/getPosts/{id}/{limit}', 'Admin\CategoryController@getPosts');
            Route::get('logout', 'Auth\AuthController@logout');
            Route::post('user', 'Auth\AuthController@user');
            Route::resource('posts', 'Admin\PostController')->only([
                'store', 'create', 'update', 'destroy', 'index', 'show'
            ]);
            Route::resource('comments', 'Admin\CommentsController')->only([
                'store', 'create', 'update', 'destroy', 'index', 'show'
            ]);
            Route::resource('users', 'Admin\UserController')->only([
                'store', 'create', 'update', 'destroy', 'index', 'show'
            ]);
            Route::resource('categories', 'Admin\CategoryController')->only([
                'store', 'create', 'update', 'destroy', 'index', 'show'
            ]);
        });
    });
});
