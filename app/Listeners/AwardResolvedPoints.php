<?php

namespace App\Listeners;

use App\Events\ReportResolved;

class AwardResolvedPoints
{
    /**
     * +50 poin ke reporter saat laporan resolved oleh pemerintah.
     * Synchronous agar poin langsung terlihat.
     */
    public function handle(ReportResolved $event): void
    {
        $report   = $event->report;
        $reporter = $report->user;

        if (! $reporter) {
            return;
        }

        // Tambah +50 poin ke reporter
        $reporter->increment('points', 50);

        // Catat ke point_logs
        $reporter->pointLogs()->create([
            'points'       => 50,
            'reason'       => 'Laporan diselesaikan: ' . $report->title,
            'reference_id' => $report->id,
        ]);
    }
}
