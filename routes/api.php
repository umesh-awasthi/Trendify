<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\AgentController;

Route::prefix('auth')->group(function () {
    // Public routes
    Route::post('/register', [AuthController::class, 'apiRegister']);
    Route::post('/login', [AuthController::class, 'apiLogin']);

    // Password reset routes
    Route::post('/password/reset', [AuthController::class, 'sendResetLinkEmail']);
    Route::post('/password/reset/confirm', [AuthController::class, 'resetPassword']);

    // Protected routes (require authentication)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'apiLogout']);
        // Add other protected routes here, e.g.:
        // Route::get('/profile', [AuthController::class, 'apiProfile']);
    });
});

// Agent specific authentication routes
// Route::prefix('agent')->group(function () {
//     Route::post('/login', [AuthController::class, 'apiLogin']);
//     // Agent password reset routes
//     Route::post('/password/reset', [AuthController::class, 'sendResetLinkEmail']);
//     Route::post('/password/reset/confirm', [AuthController::class, 'resetPassword']);
    
//     Route::middleware('auth:sanctum')->group(function () {
//         Route::post('/logout', [AuthController::class, 'apiLogout']);
//     });
// });
