<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Relawan\RelawanVerificationController;
use App\Http\Controllers\Pemerintah\PemerintahDashboardController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\GamificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ── Public Routes ──────────────────────────────────────────────────────────
Route::get('/peta',         [MapController::class, 'index'])->name('peta');
Route::get('/api/map-data', [MapController::class, 'getData'])->name('api.map-data');

// Gamification (public)
Route::get('/komunitas/leaderboard', [GamificationController::class, 'leaderboard'])->name('leaderboard');
Route::get('/profil/{user}',         [GamificationController::class, 'profile'])->name('profil');

// Event (public read)
Route::get('/komunitas/event',        [EventController::class, 'index'])->name('event.index');
Route::get('/komunitas/event/{event}', [EventController::class, 'show'])->name('event.show');


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

    // ── Event: create (relawan & pemerintah) + rsvp (semua auth) ──
    Route::post('/komunitas/event/{event}/rsvp', [EventController::class, 'rsvp'])->name('event.rsvp');
    Route::middleware('role:relawan|pemerintah|admin')->group(function () {
        Route::get('/komunitas/event/create',  [EventController::class, 'create'])->name('event.create');
        Route::post('/komunitas/event',        [EventController::class, 'store'])->name('event.store');
    });

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

