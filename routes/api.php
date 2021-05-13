<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
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

// Route::resource('products', ProductController::class);

// Public routes
Route::group(['prefix' => 'auth'], function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/password-reset', [ForgotPasswordController::class, 'forgot']);
    Route::post('/password-reset/{token}', [ForgotPasswordController::class, 'reset']);
});


Route::group(['prefix' => 'posts'], function () {
    Route::get('', [PostController::class, 'index']);
    Route::get('/{id}', [PostController::class, 'show']);
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
        Route::patch('/{id}', [PostController::class, 'update']);
        Route::delete('/{id}', [PostController::class, 'destroy']);
    }); 
    Route::group(['prefix' => 'users'], function() {
        Route::post('', [UserController::class, 'store']);
        Route::patch('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']); 
    }); 
});



Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
