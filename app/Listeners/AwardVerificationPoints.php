<?php

namespace App\Listeners;

use App\Events\ReportVerified;

class AwardVerificationPoints
{
    /**
     * +20 poin ke relawan yang memverifikasi
     * +10 poin ke reporter pemilik laporan
     * Synchronous agar langsung terlihat.
     */
    public function handle(ReportVerified $event): void
    {
        $report  = $event->report;
        $relawan = $event->relawan;
        $reporter = $report->user;

        // ── Poin untuk relawan (+20) ────────────────────────────
        $relawan->increment('points', 20);
        $relawan->pointLogs()->create([
            'points'       => 20,
            'reason'       => 'Memverifikasi laporan: ' . $report->title,
            'reference_id' => $report->id,
        ]);

        // ── Poin untuk reporter (+10) ────────────────────────────
        if ($reporter && $reporter->id !== $relawan->id) {
            $reporter->increment('points', 10);
            $reporter->pointLogs()->create([
                'points'       => 10,
                'reason'       => 'Laporan diverifikasi: ' . $report->title,
                'reference_id' => $report->id,
            ]);
        }
    }
}
