<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Relawan\RelawanVerificationController;
use App\Http\Controllers\Pemerintah\PemerintahDashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminRewardController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\GamificationController;
use App\Http\Controllers\HadiahController;
use App\Http\Controllers\AnonymousReportController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

// ── Landing Page ───────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/api/stats', [HomeController::class, 'apiStats'])->name('api.home-stats');

// ── SEO Routes ────────────────────────────────────────────────────────────
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// ── Public Routes ──────────────────────────────────────────────────────────
Route::get('/peta',         [MapController::class, 'index'])->name('peta');
Route::get('/api/map-data', [MapController::class, 'getData'])->name('api.map-data');
Route::get('/tentang',    [PageController::class, 'tentang'])->name('tentang');
Route::get('/cara-lapor', [PageController::class, 'caraLapor'])->name('cara-lapor');

// Gamification (public)
Route::get('/komunitas/leaderboard', [GamificationController::class, 'leaderboard'])->name('leaderboard');
Route::get('/profil/{user}',         [GamificationController::class, 'profile'])->name('profil');

// Event (public read)
Route::get('/komunitas/event',         [EventController::class, 'index'])->name('event.index');
Route::get('/komunitas/event/{event}', [EventController::class, 'show'])->name('event.show');

// Artikel (public read)
Route::get('/artikel',         [ArticleController::class, 'index'])->name('artikel.index');
Route::get('/artikel/{slug}',  [ArticleController::class, 'show'])->name('artikel.show');

// ── Open Data Dashboard (public read & downloads) ───────────────────
use App\Http\Controllers\OpenDataController;
Route::get('/open-data', [OpenDataController::class, 'index'])->name('open-data');
Route::get('/open-data/download/csv', [OpenDataController::class, 'downloadCsv'])->name('open-data.download.csv');
Route::get('/open-data/download/excel', [OpenDataController::class, 'downloadExcel'])->name('open-data.download.excel');
Route::get('/open-data/download/geojson', [OpenDataController::class, 'downloadGeoJson'])->name('open-data.download.geojson');

// ── Open Data API Endpoints (public JSON) ───────────────────────────
Route::prefix('api/open-data')->group(function () {
    Route::get('/stats', [OpenDataController::class, 'apiStats'])->name('api.open-data.stats');
    Route::get('/reports', [OpenDataController::class, 'apiReports'])->name('api.open-data.reports');
    Route::get('/geojson', [OpenDataController::class, 'apiGeoJson'])->name('api.open-data.geojson');
});

// ── Hadiah / Reward Store Routes (public) ───────────────────────────
Route::get('/hadiah', [HadiahController::class, 'index'])->name('hadiah');
Route::get('/hadiah/sertifikat/{code}', [HadiahController::class, 'sertifikat'])->name('hadiah.sertifikat');

// ── Laporan Anonim Routes (public, dengan rate limiting) ────────────
Route::get('/laporan-anonim/create',    [AnonymousReportController::class, 'create'])->name('laporan-anonim.create');
Route::get('/laporan-anonim/konfirmasi',[AnonymousReportController::class, 'konfirmasi'])->name('laporan-anonim.konfirmasi');
Route::get('/laporan-anonim/cek',       [AnonymousReportController::class, 'cekForm'])->name('laporan-anonim.cek-form');

// POST routes dengan rate limiting: max 5 request per IP per menit
Route::middleware('throttle:5,1')->group(function () {
    Route::post('/laporan-anonim',      [AnonymousReportController::class, 'store'])->name('laporan-anonim.store');
    Route::post('/laporan-anonim/cek',  [AnonymousReportController::class, 'cek'])->name('laporan-anonim.cek');
});


Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    // Profile (Breeze default)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ── Laporan (masyarakat) ──────────────────────────────────────
    Route::get('/laporan/create', [ReportController::class, 'create'])->name('laporan.create');
    Route::post('/laporan', [ReportController::class, 'store'])->name('laporan.store');
    Route::get('/laporan/{report}', [ReportController::class, 'show'])->name('laporan.show');

    // ── Masyarakat Area & Gamification Hadiah ─────────────────────
    Route::post('/hadiah/{reward}/redeem', [HadiahController::class, 'redeem'])->name('hadiah.redeem');
    Route::post('/notifikasi/baca-semua', [GamificationController::class, 'bacaSemuaNotifikasi'])->name('notifications.read-all');
    Route::get('/masyarakat/laporan', [ReportController::class, 'index'])->name('masyarakat.laporan');

    // ── Event: create (relawan & pemerintah) + rsvp (semua auth) ──
    Route::post('/komunitas/event/{event}/rsvp', [EventController::class, 'rsvp'])->name('event.rsvp');
    Route::middleware('role:relawan|pemerintah|admin')->group(function () {
        Route::get('/komunitas/event/create',  [EventController::class, 'create'])->name('event.create');
        Route::post('/komunitas/event',        [EventController::class, 'store'])->name('event.store');
    });

    // ── Artikel: create & store (admin & pemerintah) ──────────────
    Route::middleware('role:admin|pemerintah')->group(function () {
        Route::get('/artikel/create', [ArticleController::class, 'create'])->name('artikel.create');
        Route::post('/artikel',       [ArticleController::class, 'store'])->name('artikel.store');
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

    // ── Admin Area ────────────────────────────────────────────
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Manajemen Pengguna
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.update-role');

        // Manajemen Hadiah
        Route::get('/rewards', [AdminRewardController::class, 'index'])->name('rewards.index');
        Route::get('/rewards/create', [AdminRewardController::class, 'create'])->name('rewards.create');
        Route::post('/rewards', [AdminRewardController::class, 'store'])->name('rewards.store');
        Route::get('/rewards/{reward}/edit', [AdminRewardController::class, 'edit'])->name('rewards.edit');
        Route::put('/rewards/{reward}', [AdminRewardController::class, 'update'])->name('rewards.update');
        Route::delete('/rewards/{reward}', [AdminRewardController::class, 'destroy'])->name('rewards.destroy');

        // Manajemen Kategori
        Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');
    });
});

require __DIR__.'/auth.php';

