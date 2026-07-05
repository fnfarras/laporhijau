<?php

namespace App\Listeners;

use App\Events\ReportSubmitted;

class AwardReportSubmitPoints
{
    /**
     * +5 poin diberikan saat user berhasil submit laporan.
     * Dijalankan synchronous (bukan via queue) agar poin langsung
     * terlihat tanpa perlu menjalankan queue worker.
     */
    public function handle(ReportSubmitted $event): void
    {
        $report = $event->report;
        $user   = $report->user;

        if (!$user) {
            return;
        }

        // 1. Tambah poin ke kolom users.points
        $user->increment('points', 5);

        // 2. Catat ke tabel point_logs
        $user->pointLogs()->create([
            'points'       => 5,
            'reason'       => 'Mengajukan laporan: ' . $report->title,
            'reference_id' => $report->id,
        ]);
    }
}
