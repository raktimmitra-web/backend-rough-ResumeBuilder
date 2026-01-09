<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//auth routes
Route::post('/login',[AuthController::class,'login']);
Route::post('/logout',[AuthController::class,'logout'])->middleware('auth:sanctum');

// Route::post('/forget-password', [AuthController::class, 'forgetPassword']);
// Route::post('/reset-password', [AuthController::class, 'resetPassword']);

//resume routes (private)

// Route::middleware('auth:sanctum')->group(function () {

//     Route::apiResource('resumes', ResumeController::class);

//     Route::put('resumes/{resume}/sections/{section}',
//         [ResumeSectionController::class, 'update']
//     );

//     Route::delete('resumes/{resume}/sections/{section}',
//         [ResumeSectionController::class, 'destroy']
//     );
// });


//templateRoutes
// Route::get('/templates', [TemplateController::class, 'index']);
// Route::get('/templates/{template}', [TemplateController::class, 'show']);


//admin routes (protected/only for admin)

Route::prefix('admin')->group(function () {

    Route::get('/dashboard', 
    function(){
      return "dashboard";
    }
    // [AdminDashboardController::class, 'index']
)->middleware(['auth:sanctum', 'admin']);

    // Route::get('/users', [AdminUserController::class, 'index']);
    // Route::put('/users/{user}/role', [AdminUserController::class, 'updateRole']);
    // Route::put('/users/{user}/status', [AdminUserController::class, 'updateStatus']);

    // Route::get('/resumes', [AdminResumeController::class, 'index']);
});


