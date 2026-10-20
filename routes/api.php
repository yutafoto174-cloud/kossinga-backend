<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\KosController;
use Illuminate\Support\Facades\Route;

// cek cepat bahwa API hidup: GET /api/ping
Route::get('/ping', function () {
    return response()->json([
        'status' => 'ok',
        'app'    => config('app.name'),
    ]);
});

// ===== PUBLIK: tidak butuh token =====
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// ===== TERPROTEKSI: wajib header  Authorization: Bearer <token> =====
Route::name('api.')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('kos', KosController::class)->parameters(['kos' => 'id']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
});