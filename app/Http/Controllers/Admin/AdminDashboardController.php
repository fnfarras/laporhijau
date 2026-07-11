<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;
use App\Models\Event;
use App\Models\Article;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Overview statistik seluruh platform untuk admin.
     */
    public function index(): View
    {
        // ── Statistik Pengguna ─────────────────────────────────────
        $userStats = [
            'total'       => User::count(),
            'masyarakat'  => User::role('masyarakat')->count(),
            'relawan'     => User::role('relawan')->count(),
            'pemerintah'  => User::role('pemerintah')->count(),
        ];

        // ── Statistik Laporan ──────────────────────────────────────
        $reportStats = [
            'total'       => Report::count(),
            'pending'     => Report::where('status', 'pending')->count(),
            'verified'    => Report::where('status', 'verified')->count(),
            'in_progress' => Report::where('status', 'in_progress')->count(),
            'resolved'    => Report::where('status', 'resolved')->count(),
            'rejected'    => Report::where('status', 'rejected')->count(),
        ];

        // ── Statistik Konten ───────────────────────────────────────
        $contentStats = [
            'events'   => Event::count(),
            'articles' => Article::count(),
        ];

        // ── 10 Laporan terbaru ─────────────────────────────────────
        $latestReports = Report::with(['category', 'user'])
            ->latest()
            ->limit(10)
            ->get();

        // ── Top 5 User berdasarkan poin ────────────────────────────
        $topUsers = User::orderByDesc('points')
            ->limit(5)
            ->get(['id', 'name', 'email', 'points']);

        return view('admin.dashboard', compact(
            'userStats', 'reportStats', 'contentStats',
            'latestReports', 'topUsers'
        ));
    }
}
