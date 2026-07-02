<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Event;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        // Stats untuk counter
        $stats = $this->getStats();

        // 3 event upcoming terbaru
        $upcomingEvents = Event::with('organizer')
            ->where('event_date', '>=', now())
            ->orderBy('event_date')
            ->limit(3)
            ->get();

        // 3 artikel terbaru
        $latestArticles = Article::published()
            ->with('author')
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('welcome', compact('stats', 'upcomingEvents', 'latestArticles'));
    }

    /** API endpoint untuk live counter (publik) */
    public function apiStats(): JsonResponse
    {
        return response()->json($this->getStats());
    }

    private function getStats(): array
    {
        $totalReports   = Report::count();
        $resolvedCount  = Report::where('status', 'resolved')->count();

        return [
            'total_laporan'   => $totalReports,
            'laporan_selesai' => $resolvedCount,
            'relawan_aktif'   => User::role('relawan')->count(),
            'artikel'         => Article::published()->count(),
            'resolved_rate'   => $totalReports > 0
                ? round($resolvedCount / $totalReports * 100)
                : 0,
            'laporan_bulan_ini' => Report::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];
    }
}
