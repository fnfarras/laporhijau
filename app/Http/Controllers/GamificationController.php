<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\Report;
use App\Models\User;
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

        $stats = [
            'total_reports'    => $user->reports()->count(),
            'resolved_reports' => $user->reports()->where('status', 'resolved')->count(),
            'total_points'     => $user->points,
            'badge_count'      => $user->badges()->count(),
        ];

        // 5 laporan terbaru
        $recentReports = $user->reports()
            ->with(['category', 'photos'])
            ->latest()
            ->limit(5)
            ->get();

        // Riwayat poin (10 terbaru)
        $pointHistory = $user->pointLogs()
            ->latest()
            ->limit(10)
            ->get();

        $role = $user->getRoleNames()->first() ?? 'masyarakat';

        return view('komunitas.profil', compact(
            'user', 'allBadges', 'stats', 'recentReports', 'pointHistory', 'role'
        ));
    }

    /**
     * Halaman penukaran hadiah dengan poin (Fitur Wow Fungsional)
     */
    public function hadiah(): View
    {
        $rewards = [
            [
                'id' => 'pbb_5',
                'title' => 'Diskon PBB 5%',
                'description' => 'Voucher pemotongan Pajak Bumi dan Bangunan (PBB) sebesar 5% dari Pemda Kota Pekanbaru.',
                'points' => 300,
                'icon' => '🏠',
                'category' => 'Pemerintah'
            ],
            [
                'id' => 'retribusi_sampah',
                'title' => 'Bebas Retribusi Sampah 1 Bulan',
                'description' => 'Gratis biaya retribusi pelayanan kebersihan bulanan tingkat kelurahan.',
                'points' => 100,
                'icon' => '🗑️',
                'category' => 'Layanan'
            ],
            [
                'id' => 'voucher_ewallet_10',
                'title' => 'Voucher GoPay / OVO Rp 10.000',
                'description' => 'Saldo dompet digital Rp 10.000 hasil kerja sama dana CSR Bank Riau Kepri.',
                'points' => 150,
                'icon' => '📱',
                'category' => 'Swasta'
            ],
            [
                'id' => 'tumbler_lh',
                'title' => 'Tumbler Premium LaporHijau',
                'description' => 'Tumbler stainless ramah lingkungan berlogo eksklusif LaporHijau.',
                'points' => 500,
                'icon' => '🥤',
                'category' => 'Merchandise'
            ],
            [
                'id' => 'voucher_bus_trans',
                'title' => 'Tiket Gratis Bus Trans Metro',
                'description' => '3x perjalanan gratis naik bus Trans Metro Pekanbaru.',
                'points' => 80,
                'icon' => '🚌',
                'category' => 'Transportasi'
            ],
        ];

        $user = auth()->user();
        $myRedemptions = [];
        if ($user) {
            $myRedemptions = $user->pointLogs()
                ->where('points', '<', 0)
                ->where('reason', 'like', 'Penukaran Hadiah%')
                ->latest()
                ->get();
        }

        return view('komunitas.hadiah', compact('rewards', 'myRedemptions'));
    }

    /**
     * Proses klaim penukaran hadiah
     */
    public function redeemHadiah(Request $request)
    {
        $request->validate([
            'reward_id' => 'required|string'
        ]);

        $rewards = [
            'pbb_5' => ['title' => 'Diskon PBB 5%', 'points' => 300],
            'retribusi_sampah' => ['title' => 'Bebas Retribusi Sampah 1 Bulan', 'points' => 100],
            'voucher_ewallet_10' => ['title' => 'Voucher GoPay / OVO Rp 10.000', 'points' => 150],
            'tumbler_lh' => ['title' => 'Tumbler Premium LaporHijau', 'points' => 500],
            'voucher_bus_trans' => ['title' => 'Tiket Gratis Bus Trans Metro', 'points' => 80],
        ];

        $rewardId = $request->reward_id;
        if (!array_key_exists($rewardId, $rewards)) {
            return back()->with('error', 'Hadiah tidak valid.');
        }

        $reward = $rewards[$rewardId];
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan masuk terlebih dahulu.');
        }

        if ($user->points < $reward['points']) {
            return back()->with('error', 'Poin Anda tidak mencukupi untuk menukar hadiah ini.');
        }

        DB::transaction(function () use ($user, $reward) {
            $user->decrement('points', $reward['points']);

            $voucherCode = 'LH-' . strtoupper(substr(md5(uniqid()), 0, 8));

            \App\Models\PointLog::create([
                'user_id' => $user->id,
                'points' => -$reward['points'],
                'reason' => 'Penukaran Hadiah: ' . $reward['title'] . ' (Kode Voucher: ' . $voucherCode . ')',
            ]);
        });

        return back()->with('success', '🎉 Berhasil menukarkan hadiah! Silakan gunakan kode voucher di bawah.');
    }

    /**
     * Tandai semua notifikasi sebagai terbaca
     */
    public function bacaSemuaNotifikasi()
    {
        auth()->user()->notifications()->whereNull('read_at')->update(['read_at' => now()]);
        return back()->with('success', 'Semua notifikasi telah ditandai sebagai terbaca.');
    }
}
