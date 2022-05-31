<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\RolePermissionController;
use App\Http\Controllers\ProfileController;

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

require __DIR__ . '/api/auth.php';

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('posts', PostController::class);
    Route::apiResource('categories', CategoryController::class);

    Route::prefix('role-permissions')->group(function () {
        Route::get('/', [RolePermissionController::class, 'index']);
        Route::put('/', [RolePermissionController::class, 'update']);
    });

    Route::prefix('permissions')->group(function () {
        Route::get('/', PermissionController::class);
    });

    Route::prefix('profile')->group(function () {
        Route::put('update', [ProfileController::class, 'update']);
        Route::put('change-password', [ProfileController::class, 'changePassword']);
    });
});