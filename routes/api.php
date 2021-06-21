<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ImageUploadController;

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

Route::group([
    'prefix' => 'auth'
], function($router){
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
    Route::post('register', [AuthController::class, 'register']);
});

Route::get('tags/{tagId}', [ArticleController::class, 'getByTag']);

Route::get('users/{userId}/articles', [ArticleController::class, 'getUserArticles']);
Route::resource('users', UserController::class);


Route::get('articles/search/{query}', [ArticleController::class, 'search']);
Route::post('articles/{id}/likes', [ArticleController::class, 'toggleLike']);
Route::resource('articles', ArticleController::class);

Route::post('image-upload', [ImageUploadController::class, 'store']);
Route::delete('image-upload', [ImageUploadController::class, 'destroy']);