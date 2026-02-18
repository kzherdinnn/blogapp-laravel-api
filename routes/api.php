<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('test-storage', function() {
    $files = Storage::disk('public')->allFiles();
    $publicStorageExists = file_exists(public_path('storage'));
    $linkTarget = $publicStorageExists && is_link(public_path('storage')) ? readlink(public_path('storage')) : 'not a symlink';
    
    return response()->json([
        'files_count' => count($files),
        'files_list' => array_slice($files, 0, 10),
        'public_storage_exists' => $publicStorageExists,
        'link_target' => $linkTarget,
        'public_path' => public_path(),
        'storage_path' => storage_path('app/public'),
    ]);
});

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

// Auth Routes (tanpa middleware jwtAuth)
Route::post('login', 'Api\AuthController@login');
Route::post('register', 'Api\AuthController@register');

// Group Routes dengan middleware jwtAuth
Route::middleware('jwtAuth')->group(function () {

    // User Routes
    Route::get('logout', 'Api\AuthController@logout');
    Route::post('save_user_info', 'Api\AuthController@saveUserInfo');

    // Post Routes
    Route::prefix('posts')->group(function () {
        Route::post('create', 'Api\PostsController@create');
        Route::post('posts/{id}', 'Api\PostsController@update');
        Route::delete('{id}', 'Api\PostsController@delete');
        Route::get('/', 'Api\PostsController@posts');
        Route::get('my_posts', 'Api\PostsController@myPosts');
        Route::post('like', 'Api\LikesController@like');
        Route::post('comments', 'Api\CommentsController@comments');
    });

    // Comment Routes
    Route::prefix('comments')->group(function () {
        Route::post('create', 'Api\CommentsController@create');
        Route::put('update', 'Api\CommentsController@update');
        Route::delete('delete', 'Api\CommentsController@delete');
    });
});
