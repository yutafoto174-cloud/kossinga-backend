<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\KosController;
use Illuminate\Support\Facades\Route;

// ===== PUBLIK =====
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// ===== AUTHENTICATION: wajib login (token Bearer) =====
Route::name('api.')->middleware('auth:sanctum')->group(function () {

    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // semua pengguna yang login boleh MEMBACA
    Route::apiResource('kos', KosController::class)
        ->only(['index', 'show'])
        ->parameters(['kos' => 'id']);

    // ===== AUTHORIZATION: hanya ADMIN yang boleh MENULIS =====
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('kos', KosController::class)
            ->only(['store', 'update', 'destroy'])
            ->parameters(['kos' => 'id']);
    });
});