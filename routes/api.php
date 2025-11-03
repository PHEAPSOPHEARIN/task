<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// --- PUBLIC ROUTES ---
// Accessible by anyone without authentication
Route::get('/', function () {
    return response()->json([
        'message' => 'E-commerce API is running!',
        'version' => '1.0.0',
        'timestamp' => now()->toISOString()
    ]);
});
 Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
// Authentication routes
Route::prefix('auth')->group(function () {
   
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:sanctum');
    Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');
});

// --- PROTECTED ROUTES (SANCTUM AUTHENTICATION REQUIRED) ---
Route::middleware(['auth:sanctum'])->group(function () {
    
    // Accessible by all authenticated users
    Route::get('/roles', [RoleController::class, 'index']);
    Route::get('/roles/{role}', [RoleController::class, 'show']);
    
    // Requires 'role:create' ability
    Route::middleware(['ability:role:create'])->group(function () {
        Route::post('/roles', [RoleController::class, 'store']);
    });
    
    // Requires 'role:update' ability
    Route::middleware(['ability:role:update'])->group(function () {
        Route::put('/roles/{role}', [RoleController::class, 'update']);
        Route::patch('/roles/{role}', [RoleController::class, 'update']);
    });
    
    // Requires 'role:delete' ability
    Route::middleware(['ability:role:delete'])->group(function () {
        Route::delete('/roles/{role}', [RoleController::class, 'destroy']);
    });
});