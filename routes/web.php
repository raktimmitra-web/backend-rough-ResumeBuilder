<?php

use App\Http\Controllers\admin\AdminAuthController;
use App\Http\Controllers\admin\AdminDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Default login route
Route::get('/login', function () {
    return redirect()->route('blade.admin.login');
})->name('login');

Route::prefix('blade-admin')->group(function () {

    // Only accessible when NOT logged in
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])
            ->name('blade.admin.login');

        Route::post('/login', [AdminAuthController::class, 'login'])
            ->name('blade.admin.login.submit');
    });

    // Protected routes
     Route::middleware(['auth', 'admin.redirect'])->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('blade.admin.dashboard');
        
        Route::get('/users', [AdminDashboardController::class, 'users'])
            ->name('blade.admin.users');

        Route::get('/logout', [AdminAuthController::class, 'logout'])
            ->name('blade.admin.logout');
    });
});