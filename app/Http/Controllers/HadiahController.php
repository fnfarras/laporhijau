<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use App\Models\RewardRedemption;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class HadiahController extends Controller
{
    /**
     * Tampilkan katalog hadiah.
     */
    public function index(): View
    {
        $rewards = Reward::where('is_active', true)->orderBy('points_required')->get();
        $user = auth()->user();

        $myRedemptionIds = [];
        if ($user) {
            $myRedemptionIds = $user->rewardRedemptions->pluck('reward_id')->toArray();
        }

        return view('hadiah.index', compact('rewards', 'myRedemptionIds', 'user'));
    }

    /**
     * Redeem hadiah — operasi atomic via DB::transaction().
     */
    public function redeem(Reward $reward): RedirectResponse
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan masuk terlebih dahulu.');
        }

        // Cek ketersediaan hadiah
        if (!$reward->is_active) {
            return redirect()->back()->with('error', 'Hadiah ini tidak lagi tersedia.');
        }

        // Cek kecukupan poin
        if ($user->points < $reward->points_required) {
            return redirect()->back()->with('error', 'Poin Anda tidak cukup untuk menukarkan hadiah ini.');
        }

        // Cek jika sudah pernah diredeem (untuk tipe non-sertifikat, batasi 1x)
        if (in_array($reward->type, ['badge_spesial', 'title'])) {
            $exists = RewardRedemption::where('user_id', $user->id)
                ->where('reward_id', $reward->id)
                ->exists();
            if ($exists) {
                return redirect()->back()->with('error', 'Anda sudah pernah menukarkan hadiah ini.');
            }
        }

        // Eksekusi atomic: poin dikurangi + log + redemption harus berhasil semua
        $code = DB::transaction(function () use ($user, $reward) {
            // 1. Kurangi poin
            $user->decrement('points', $reward->points_required);

            // 2. Catat ke point_logs
            $user->pointLogs()->create([
                'points'       => -$reward->points_required,
                'reason'       => 'Tukar hadiah: ' . $reward->name,
                'reference_id' => $reward->id,
            ]);

            // 3. Generate kode sertifikat unik
            $code = 'LH-' . strtoupper(Str::random(8));

            // 4. Create redemption record
            RewardRedemption::create([
                'user_id'          => $user->id,
                'reward_id'        => $reward->id,
                'redeemed_at'      => now(),
                'certificate_code' => $code,
            ]);

            return $code;
        });

        return redirect()->route('hadiah.sertifikat', ['code' => $code])
            ->with('success', '🎉 Hadiah berhasil ditukarkan!');
    }

    /**
     * Tampilkan sertifikat digital (publik).
     */
    public function sertifikat(string $code): View
    {
        $redemption = RewardRedemption::where('certificate_code', $code)
            ->with(['user', 'reward'])
            ->firstOrFail();

        return view('hadiah.sertifikat', compact('redemption'));
    }
}
