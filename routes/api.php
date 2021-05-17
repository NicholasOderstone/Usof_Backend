<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;


use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;


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


// Public routes
Route::group(['prefix' => 'auth'], function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/password-reset', [ForgotPasswordController::class, 'forgot']);
    Route::post('/password-reset/{token}', [ForgotPasswordController::class, 'reset']);
});


Route::group(['prefix' => 'posts'], function () {
    Route::get('', [PostController::class, 'index']);
    Route::get('/{post_id}', [PostController::class, 'show']);
    Route::get('/{post_id}/comments', [CommentController::class, 'index']);
});

Route::group(['prefix' => 'users'], function() {
    Route::get('', [UserController::class, 'index']);
    Route::get('/{id}', [UserController::class, 'show']);
});



// Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::group(['prefix' => 'auth'], function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/change_password', [AuthController::class, 'change_password']);
    });


    Route::group(['prefix' => 'posts'], function () {
        Route::post('', [PostController::class, 'store']);
        Route::patch('/{post_id}', [PostController::class, 'update']);
        Route::delete('/{post_id}', [PostController::class, 'destroy']);

        Route::post('/{post_id}/comments', [CommentController::class, 'store']);

        Route::get('/{post_id}/like', [LikeController::class, 'IndexLikePost']);
        Route::post('/{post_id}/like', [LikeController::class, 'StoreLikePost']);
        Route::delete('/{post_id}/like', [LikeController::class, 'DestroyLikePost']);
        
    }); 
    Route::group(['prefix' => 'users'], function() {
        Route::post('', [UserController::class, 'store']);
        Route::patch('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']); 
    }); 

    Route::group(['prefix' => 'comments'], function() {
        Route::get('/{comment_id}', [CommentController::class, 'show']);
        Route::patch('/{comment_id}', [CommentController::class, 'update']);
        Route::delete('/{comment_id}', [CommentController::class, 'destroy']); 

        Route::get('/{comment_id}/like', [LikeController::class, 'IndexLikeComment']);
        Route::post('/{comment_id}/like', [LikeController::class, 'StoreLikeComment']);
        Route::delete('/{comment_id}/like', [LikeController::class, 'DestroyLikeComment']);
    });
});



Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
