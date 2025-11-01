<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

// --- PUBLIC ROUTES ---
// Accessible by anyone without a token.
Route::get('/', function () {
    return response()->json(['message' => 'E-commerce API is running!']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// --- PROTECTED ROUTES (SANCTUM AUTHENTICATION REQUIRED) ---
// Email verification is no longer enforced for these routes.
Route::middleware(['auth:sanctum'])->group(function () {

    // 1. READ-ONLY ROUTES (Accessible by all authenticated users)
    // Includes: GET /roles (index) and GET /roles/{role} (show)

    // 2. MANAGEMENT ROUTES (Accessible only by MANAGER role)
    // Includes: POST /roles (store), PUT/PATCH /roles/{role} (update), DELETE /roles/{role} (destroy)
    Route::middleware('role:MANAGER')->group(function () {
        Route::apiResource('roles', RoleController::class)->except(['index', 'show', 'create', 'edit']);
    });
});
    Route::apiResource('roles', RoleController::class)->only(['index', 'show']);