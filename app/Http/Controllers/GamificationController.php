<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class GamificationController extends Controller
{
    /**
     * Leaderboard — ranking berdasarkan poin per periode.
     */
    public function leaderboard(Request $request): View
    {
        $period = $request->get('period', 'all'); // weekly | monthly | all

        $leaders = cache()->remember("laporhijau_leaderboard_{$period}", 600, function() use ($period) {
            $query = User::query()
                ->withCount(['badges'])
                ->with(['badges'])
                ->where('points', '>', 0);

            // Filter berdasarkan periode menggunakan point_logs
            if ($period === 'weekly') {
                $query->whereHas('pointLogs', fn($q) =>
                    $q->where('created_at', '>=', now()->startOfWeek())
                )->addSelect([
                    'period_points' => DB::table('point_logs')
                        ->selectRaw('SUM(points)')
                        ->whereColumn('user_id', 'users.id')
                        ->where('created_at', '>=', now()->startOfWeek()),
                ]);
            } elseif ($period === 'monthly') {
                $query->whereHas('pointLogs', fn($q) =>
                    $q->where('created_at', '>=', now()->startOfMonth())
                )->addSelect([
                    'period_points' => DB::table('point_logs')
                        ->selectRaw('SUM(points)')
                        ->whereColumn('user_id', 'users.id')
                        ->where('created_at', '>=', now()->startOfMonth()),
                ]);
            }

            $sortColumn = in_array($period, ['weekly', 'monthly']) ? 'period_points' : 'points';
            $leadersCollection = $query->orderByDesc($sortColumn)->limit(50)->get();

            // Untuk weekly/monthly, assign sort value ke attribute uniform
            if (in_array($period, ['weekly', 'monthly'])) {
                $leadersCollection->each(fn($u) => $u->display_points = (int) $u->period_points);
            } else {
                $leadersCollection->each(fn($u) => $u->display_points = $u->points);
            }

            return $leadersCollection;
        });

        return view('komunitas.leaderboard', compact('leaders', 'period'));
    }

    /**
     * Profil publik user.
     */
    public function profile(User $user): View
    {
        $user->load(['badges', 'reports.category', 'reports.photos', 'pointLogs']);

        $allBadges = Badge::all();

        // Hitung dari koleksi yang sudah di-load untuk menghindari N+1
        $stats = [
            'total_reports'    => $user->reports->count(),
            'resolved_reports' => $user->reports->where('status', 'resolved')->count(),
            'total_points'     => $user->points,
            'badge_count'      => $user->badges->count(),
        ];

        // 5 laporan terbaru
        $recentReports = $user->reports
            ->sortByDesc('created_at')
            ->take(5)
            ->values();

        // Riwayat poin (10 terbaru)
        $pointHistory = $user->pointLogs
            ->sortByDesc('created_at')
            ->take(10)
            ->values();

        $role = $user->getRoleNames()->first() ?? 'masyarakat';

        return view('komunitas.profil', compact(
            'user', 'allBadges', 'stats', 'recentReports', 'pointHistory', 'role'
        ));
    }

    /**
     * Tandai semua notifikasi sebagai terbaca.
     */
    public function bacaSemuaNotifikasi(): RedirectResponse
    {
        auth()->user()->notifications()->whereNull('read_at')->update(['read_at' => now()]);
        return back()->with('success', 'Semua notifikasi telah ditandai sebagai terbaca.');
    }
}
