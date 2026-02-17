<?php

use App\Http\Controllers\AdminResumeController;
use App\Http\Controllers\AdminStatsController;
use App\Http\Controllers\AdminUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResumeController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//auth routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgetPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Group routes with auth if needed
Route::middleware('auth:sanctum')->group(function () {
    // Resume routes
    Route::post('/resumes', [ResumeController::class, 'store']);
    Route::get('/resumes', [ResumeController::class, 'index']);
    Route::get('/resumes/{resume}', [ResumeController::class, 'show']);
    Route::put('/resumes/{resume}', [ResumeController::class, 'update']);
    Route::delete('/resumes/{resume}', [ResumeController::class, 'destroy']);
    Route::post('/resumes/{resume}/duplicate', [ResumeController::class, 'duplicate']);
});
//admin routes (protected/only for admin)
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::get('/stats', [AdminStatsController::class, 'index']);
    Route::get('/users', [AdminUserController::class, 'index']);
    Route::get('/users/{id}', [AdminUserController::class, 'show']);
    Route::put('/users/{id}', [AdminUserController::class, 'update']);
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy']);
    Route::post('/users/bulk-suspend', [AdminUserController::class, 'bulkSuspend']);
    Route::post('/users/add', [AdminUserController::class, 'addAdminUser']);
    Route::patch('/users/{id}/change-password', [AdminUserController::class, 'changePassword']);

    //resume related routes for admin

    Route::get('/resumes', [AdminResumeController::class, 'index']);
    Route::get('/resumes/{id}', [AdminResumeController::class, 'show']);
    Route::delete('/resumes/{id}', [AdminResumeController::class, 'destroy']);
});