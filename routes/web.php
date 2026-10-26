<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\KosController;
use Illuminate\Support\Facades\Route;
use App\Models\Kos;
use App\Models\Pemilik;

// ===================== FRONT PAGE =====================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/kos', [HomeController::class, 'daftar'])->name('kos.index');
Route::get('/kos/{slug}', [HomeController::class, 'detail'])->name('kos.detail');
Route::get('/favorit', [HomeController::class, 'favorit'])->name('favorit');

// ===================== BACK PAGE ======================
Route::get('/dashboard', function () {
    return view('dashboard', [
        'totalKos'     => Kos::count(),
        'tersedia'     => Kos::where('status', 'Y')->count(),
        'penuh'        => Kos::where('status', 'N')->count(),
        'totalPemilik' => Pemilik::count(),
    ]);
})->middleware(['auth'])->name('dashboard');
// Semua route di dalam group ini WAJIB login (middleware auth)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
Route::resource('kos', KosController::class)->parameters(['kos' => 'id']);
});
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
Route::resource('kos', KosController::class)->parameters(['kos' => 'id']);
Route::get('pemilik/{id}', [KosController::class, 'pemilik'])->name('kos.pemilik');
});
require __DIR__.'/auth.php';