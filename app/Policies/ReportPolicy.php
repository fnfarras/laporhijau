<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;

class ReportPolicy
{
    /**
     * Semua user yang sudah login bisa membuat laporan.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Pemilik laporan, relawan, pemerintah, dan admin bisa melihat detail.
     */
    public function view(User $user, Report $report): bool
    {
        return $user->id === $report->user_id
            || $user->hasAnyRole(['relawan', 'pemerintah', 'admin']);
    }

    /**
     * Hanya pemilik laporan yang bisa mengedit (saat masih pending).
     */
    public function update(User $user, Report $report): bool
    {
        return $user->id === $report->user_id && $report->status === 'pending';
    }

    /**
     * Hanya admin yang bisa menghapus.
     */
    public function delete(User $user, Report $report): bool
    {
        return $user->hasRole('admin');
    }
}
