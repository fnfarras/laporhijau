<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\PointLog;
use App\Models\Report;
use App\Models\UserBadge;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        // Redirect ke dashboard role spesifik jika relawan/pemerintah/admin
        if ($user->hasRole('relawan')) {
            return redirect()->route('relawan.dashboard');
        }
        if ($user->hasRole('pemerintah')) {
            return redirect()->route('pemerintah.dashboard');
        }
        if ($user->hasRole('admin')) {
            // Admin: tampilkan overview singkat
        }

        // ── Dashboard Masyarakat ───────────────────────────────────────────

        // Laporan user
        $myReports = Report::where('user_id', $user->id)
            ->with(['category', 'photos'])
            ->latest()
            ->get();

        $reportStats = [
            'total'       => $myReports->count(),
            'pending'     => $myReports->where('status', 'pending')->count(),
            'verified'    => $myReports->where('status', 'verified')->count(),
            'in_progress' => $myReports->where('status', 'in_progress')->count(),
            'resolved'    => $myReports->where('status', 'resolved')->count(),
            'rejected'    => $myReports->where('status', 'rejected')->count(),
        ];

        // Event yang di-RSVP
        $myEvents = EventParticipant::where('user_id', $user->id)
            ->where('status', 'registered')
            ->with('event.organizer')
            ->get()
            ->filter(fn($p) => $p->event && $p->event->event_date->isFuture())
            ->take(3);

        // Riwayat poin terbaru
        $pointHistory = PointLog::where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        // Badge yang dimiliki
        $myBadges = UserBadge::where('user_id', $user->id)
            ->with('badge')
            ->latest('earned_at')
            ->get();

        // Artikel terbaru
        $latestArticles = Article::published()
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('dashboard', compact(
            'user', 'myReports', 'reportStats',
            'myEvents', 'pointHistory', 'myBadges', 'latestArticles'
        ));
    }
}
