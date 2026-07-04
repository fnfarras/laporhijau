<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckSlaOverdue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sla:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Periksa tenggat waktu SLA laporan dan tandai yang terlambat';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 1. Inisialisasi deadline untuk laporan yang datanya kosong (laporan lama dari seeder)
        $allReports = \App\Models\Report::all();
        $initCount = 0;
        foreach ($allReports as $report) {
            $changed = false;
            
            if (!$report->verified_deadline) {
                $report->verified_deadline = $report->created_at->copy()->addHours(48);
                $changed = true;
            }
            
            if (in_array($report->status, ['verified', 'in_progress', 'resolved']) && !$report->handled_deadline) {
                $verifiedLog = $report->statusLogs()->where('new_status', 'verified')->first();
                $verifiedAt = $verifiedLog ? $verifiedLog->created_at : $report->created_at;
                $report->handled_deadline = $verifiedAt->copy()->addDays(7);
                $changed = true;
            }
            
            if ($changed) {
                $report->saveQuietly();
                $initCount++;
            }
        }
        if ($initCount > 0) {
            $this->info("Inisialisasi deadline untuk {$initCount} laporan lama berhasil.");
        }

        // 2. Cek status Overdue untuk laporan yang belum resolved/rejected
        $reportsToCheck = \App\Models\Report::whereNotIn('status', ['resolved', 'rejected'])->get();
        $overdueCount = 0;

        foreach ($reportsToCheck as $report) {
            $isOverdueNow = false;

            if ($report->status === 'pending' && $report->verified_deadline && now()->gt($report->verified_deadline)) {
                $isOverdueNow = true;
            } elseif (in_array($report->status, ['verified', 'in_progress']) && $report->handled_deadline && now()->gt($report->handled_deadline)) {
                $isOverdueNow = true;
            }

            // Update jika status overdue berubah
            if ($report->is_overdue !== $isOverdueNow) {
                $report->is_overdue = $isOverdueNow;
                $report->saveQuietly();
                if ($isOverdueNow) {
                    $overdueCount++;
                }
            }
        }

        $this->info("{$overdueCount} laporan ditandai overdue");
        return Command::SUCCESS;
    }
}
