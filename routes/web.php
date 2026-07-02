<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Relawan\RelawanVerificationController;
use App\Http\Controllers\Pemerintah\PemerintahDashboardController;
use App\Http\Controllers\MapController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Interactive map routes (public)
Route::get('/peta', [MapController::class, 'index'])->name('peta');
Route::get('/api/map-data', [MapController::class, 'getData'])->name('api.map-data');


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

    // ── Pemerintah Area ───────────────────────────────────────────
    Route::middleware('role:pemerintah')->prefix('pemerintah')->name('pemerintah.')->group(function () {
        Route::get('/dashboard',                       [PemerintahDashboardController::class, 'index'])->name('dashboard');
        Route::get('/laporan',                         [PemerintahDashboardController::class, 'reports'])->name('laporan');
        Route::post('/laporan/{report}/update-status', [PemerintahDashboardController::class, 'updateStatus'])->name('update-status');

        // API untuk Chart.js (masih dalam middleware auth)
        Route::get('/api/stats',          [PemerintahDashboardController::class, 'apiStats'])->name('api.stats');
        Route::get('/api/chart/trend',    [PemerintahDashboardController::class, 'apiChartTrend'])->name('api.chart.trend');
        Route::get('/api/chart/kategori', [PemerintahDashboardController::class, 'apiChartKategori'])->name('api.chart.kategori');
    });
});

require __DIR__.'/auth.php';

