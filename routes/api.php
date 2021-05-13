<?php

use App\Http\Controllers\ProductController;
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
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/password-reset', [ForgotPasswordController::class, 'forgot']);
Route::post('/auth/password-reset/{token}', [ForgotPasswordController::class, 'reset']);

Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{id}', [PostController::class, 'show']);


// Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/posts', [PostController::class, 'store']);
    Route::patch('/posts/{id}', [PostController::class, 'update']);
    Route::delete('/posts/{id}', [PostController::class, 'destroy']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/change_password', [AuthController::class, 'change_password']);
});



Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
