<?php

namespace App\Http\Controllers\Relawan;

use App\Events\ReportVerified;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateReportStatusRequest;
use App\Http\Requests\VerifyReportRequest;
use App\Models\Notification;
use App\Models\Report;
use App\Models\ReportCategory;
use App\Models\ReportStatusLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RelawanVerificationController extends Controller
{
    /**
     * Dashboard utama — statistik + laporan pending.
     */
    public function index(): View
    {
        $relawan = auth()->user();

        // Antrian: laporan pending (terbaru dulu), eager load
        $pending = Report::with(['category', 'photos', 'user'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(9, ['*'], 'pending_page');

        // Riwayat singkat: laporan yang pernah diverifikasi/ditolak oleh relawan ini
        $recentHistory = ReportStatusLog::with(['report.category'])
            ->where('changed_by', $relawan->id)
            ->whereIn('new_status', ['verified', 'rejected'])
            ->latest()
            ->limit(5)
            ->get();

        // Statistik relawan ini
        $stats = [
            'verified' => ReportStatusLog::where('changed_by', $relawan->id)
                ->where('new_status', 'verified')->count(),
            'rejected' => ReportStatusLog::where('changed_by', $relawan->id)
                ->where('new_status', 'rejected')->count(),
            'points'   => $relawan->fresh()->points,
        ];

        return view('relawan.dashboard', compact('pending', 'recentHistory', 'stats'));
    }

    /**
     * Halaman antrian — semua laporan pending dengan filter kategori.
     */
    public function antrian(Request $request): View
    {
        $categoryId = $request->integer('category') ?: null;

        $query = Report::with(['category', 'photos', 'user'])
            ->where('status', 'pending')
            ->latest();

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $pending    = $query->paginate(15);
        $categories = cache()->remember('laporhijau_categories', 3600, fn() => ReportCategory::orderBy('name')->get());

        return view('relawan.antrian', compact('pending', 'categories', 'categoryId'));
    }

    /**
     * Riwayat laporan yang sudah ditangani relawan ini.
     */
    public function riwayat(): View
    {
        $relawan = auth()->user();

        $logs = ReportStatusLog::with(['report.category', 'report.photos'])
            ->where('changed_by', $relawan->id)
            ->whereIn('new_status', ['verified', 'rejected'])
            ->latest()
            ->paginate(15);

        return view('relawan.riwayat', compact('logs'));
    }

    /**
     * Verifikasi laporan: pending → verified
     */
    public function verify(Report $report, VerifyReportRequest $request): RedirectResponse
    {
        // Guard: hanya laporan yang masih pending
        if ($report->status !== 'pending') {
            return back()->with('error', 'Laporan ini sudah tidak berstatus pending.');
        }

        $relawan = auth()->user();

        // 1. Ubah status laporan
        $report->update([
            'status'       => 'verified',
            'volunteer_id' => $relawan->id,
        ]);

        // 2. Catat ke report_status_logs
        ReportStatusLog::create([
            'report_id'  => $report->id,
            'old_status' => 'pending',
            'new_status' => 'verified',
            'changed_by' => $relawan->id,
            'notes'      => 'Laporan diverifikasi oleh relawan: ' . $relawan->name,
        ]);

        // 3. Insert notifikasi ke reporter
        Notification::create([
            'user_id' => $report->user_id,
            'type'    => 'laporan_diverifikasi',
            'data'    => [
                'report_id'    => $report->id,
                'report_title' => $report->title,
                'message'      => "Laporan \"{$report->title}\" Anda telah diverifikasi oleh {$relawan->name}.",
                'relawan_name' => $relawan->name,
            ],
        ]);

        // 4. Fire event → AwardVerificationPoints (+20 relawan, +10 reporter)
        ReportVerified::dispatch($report, $relawan);

        return redirect()
            ->route('relawan.dashboard')
            ->with('success', "✅ Laporan \"{$report->title}\" berhasil diverifikasi! +20 poin untuk kamu.");
    }

    /**
     * Tolak laporan: pending → rejected (wajib isi alasan)
     */
    public function reject(Report $report, UpdateReportStatusRequest $request): RedirectResponse
    {
        // Guard: hanya laporan yang masih pending
        if ($report->status !== 'pending') {
            return back()->with('error', 'Laporan ini sudah tidak berstatus pending.');
        }

        $relawan = auth()->user();
        $reason  = $request->validated()['reason'];

        // 1. Ubah status laporan
        $report->update([
            'status'       => 'rejected',
            'volunteer_id' => $relawan->id,
        ]);

        // 2. Catat ke report_status_logs dengan alasan penolakan
        ReportStatusLog::create([
            'report_id'  => $report->id,
            'old_status' => 'pending',
            'new_status' => 'rejected',
            'changed_by' => $relawan->id,
            'notes'      => $reason,
        ]);

        // 3. Insert notifikasi ke reporter
        Notification::create([
            'user_id' => $report->user_id,
            'type'    => 'laporan_ditolak',
            'data'    => [
                'report_id'    => $report->id,
                'report_title' => $report->title,
                'message'      => "Laporan \"{$report->title}\" Anda ditolak. Alasan: {$reason}",
                'reason'       => $reason,
                'relawan_name' => $relawan->name,
            ],
        ]);

        return redirect()
            ->route('relawan.dashboard')
            ->with('info', "❌ Laporan \"{$report->title}\" ditolak dan reporter telah dinotifikasi.");
    }
}
