<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Relawan\RelawanVerificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile (Breeze default)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ── Laporan (masyarakat) ──────────────────────────────────────
    Route::get('/laporan/create', [ReportController::class, 'create'])->name('laporan.create');
    Route::post('/laporan', [ReportController::class, 'store'])->name('laporan.store');
    Route::get('/laporan/{report}', [ReportController::class, 'show'])->name('laporan.show');

    // ── Masyarakat Area ───────────────────────────────────────────
    Route::get('/masyarakat/laporan', [ReportController::class, 'index'])->name('masyarakat.laporan');

    // ── Relawan Area ──────────────────────────────────────────────
    Route::middleware('role:relawan')->prefix('relawan')->name('relawan.')->group(function () {
        Route::get('/dashboard', [RelawanVerificationController::class, 'index'])->name('dashboard');
        Route::get('/antrian',   [RelawanVerificationController::class, 'antrian'])->name('antrian');
        Route::get('/riwayat',   [RelawanVerificationController::class, 'riwayat'])->name('riwayat');
        Route::post('/laporan/{report}/verify', [RelawanVerificationController::class, 'verify'])->name('verify');
        Route::post('/laporan/{report}/reject', [RelawanVerificationController::class, 'reject'])->name('reject');
    });
});

require __DIR__.'/auth.php';
