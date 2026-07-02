<?php

namespace App\Listeners;

use App\Models\Badge;
use App\Models\Report;
use App\Models\UserBadge;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class CheckAndAwardBadges
{
    /**
     * Cek semua badge untuk user yang baru saja mendapat poin.
     * Dipanggil dari event: ReportSubmitted, ReportVerified, ReportResolved.
     */
    public function handle(object $event): void
    {
        // Tentukan user dari event
        $user = null;

        if (isset($event->report)) {
            $report = $event->report;

            // Event ReportSubmitted / ReportResolved → cek reporter
            $user = $report->user;

            // Event ReportVerified → cek juga relawan (second argument)
            if (isset($event->relawan)) {
                $this->checkBadgesForUser($event->relawan);
            }
        }

        if ($user) {
            $this->checkBadgesForUser($user);
        }
    }

    /**
     * Evaluasi semua badge yang belum dimiliki user dan award jika kriteria terpenuhi.
     */
    private function checkBadgesForUser($user): void
    {
        $user->refresh(); // Pastikan data poin & relasi terbaru

        // Ambil badge yang belum dimiliki
        $ownedBadgeIds = $user->badges()->pluck('badges.id');
        $unownedBadges = Badge::whereNotIn('id', $ownedBadgeIds)->get();

        if ($unownedBadges->isEmpty()) {
            return;
        }

        foreach ($unownedBadges as $badge) {
            if ($this->criteriaFulfilled($user, $badge)) {
                UserBadge::create([
                    'user_id'   => $user->id,
                    'badge_id'  => $badge->id,
                    'earned_at' => now(),
                ]);
            }
        }
    }

    /**
     * Cek apakah kriteria badge terpenuhi.
     */
    private function criteriaFulfilled($user, Badge $badge): bool
    {
        return match ($badge->criteria_type) {
            // Jumlah laporan yang dibuat
            'report_count' => $user->reports()->count() >= $badge->criteria_value,

            // Laporan yang sudah terverifikasi (status >= verified)
            'verified_report_count' => $user->reports()
                ->whereIn('status', ['verified', 'in_progress', 'resolved'])
                ->count() >= $badge->criteria_value,

            // Jumlah laporan yang diverifikasi oleh relawan ini
            'verification_count' => \App\Models\ReportStatusLog::where('changed_by', $user->id)
                ->where('new_status', 'verified')
                ->count() >= $badge->criteria_value,

            // Jumlah event yang diikuti
            'event_participation' => \DB::table('event_participants')
                ->where('user_id', $user->id)
                ->count() >= $badge->criteria_value,

            // Total poin yang dimiliki
            'total_points' => $user->points >= $badge->criteria_value,

            default => false,
        };
    }
}
