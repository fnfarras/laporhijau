<?php

namespace App\Http\Controllers\Pemerintah;

use App\Events\ReportResolved;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateReportStatusRequest;
use App\Models\Notification;
use App\Models\Report;
use App\Models\ReportCategory;
use App\Models\ReportStatusLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

class PemerintahDashboardController extends Controller
{
    /**
     * Dashboard utama dengan statistik & data grafik.
     */
    public function index(): View
    {
        // ── Kartu statistik ────────────────────────────────────
        $stats = [
            'total'       => Report::count(),
            'waiting'     => Report::whereIn('status', ['pending', 'verified'])->count(),
            'in_progress' => Report::where('status', 'in_progress')->count(),
            'resolved'    => Report::where('status', 'resolved')->count(),
        ];

        // ── 5 laporan verified terbaru (butuh tindakan) ────────
        $actionNeeded = Report::with(['category', 'user'])
            ->where('status', 'verified')
            ->latest()
            ->limit(5)
            ->get();

        // ── 5 laporan overdue (terlambat) ────────
        $overdueReports = Report::with(['category', 'user'])
            ->where('is_overdue', true)
            ->whereNotIn('status', ['resolved', 'rejected'])
            ->latest()
            ->get();

        // ── Data tren 6 bulan (untuk Chart.js) ────────────────
        $trendData = $this->getTrendData();

        // ── Data distribusi per kategori ───────────────────────
        $categoryData = $this->getCategoryData();

        return view('pemerintah.dashboard', compact(
            'stats', 'actionNeeded', 'trendData', 'categoryData', 'overdueReports'
        ));
    }

    /**
     * Daftar laporan verified & in_progress dengan filter.
     */
    public function reports(): View
    {
        $statusFilter   = request('status');
        $categoryFilter = request('category');
        $dateFrom       = request('date_from');
        $dateTo         = request('date_to');

        $query = Report::with(['category', 'user'])
            ->whereIn('status', ['verified', 'in_progress', 'resolved'])
            ->latest();

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }
        if ($categoryFilter) {
            $query->where('category_id', $categoryFilter);
        }
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $reports    = $query->paginate(15)->withQueryString();
        $categories = cache()->remember('laporhijau_categories', 3600, fn() => ReportCategory::orderBy('name')->get());

        return view('pemerintah.laporan', compact(
            'reports', 'categories', 'statusFilter', 'categoryFilter', 'dateFrom', 'dateTo'
        ));
    }

    /**
     * Update status laporan: verified → in_progress → resolved
     */
    public function updateStatus(Report $report, UpdateReportStatusRequest $request): RedirectResponse
    {
        $action = $request->validated()['action'];
        $pemerintah = auth()->user();

        // Tentukan transisi status
        $transitions = [
            'in_progress' => ['verified'],
            'resolved'    => ['in_progress', 'verified'],
        ];

        $targetStatus = match($action) {
            'in_progress' => 'in_progress',
            'resolved'    => 'resolved',
            default       => null,
        };

        if (! $targetStatus || ! in_array($report->status, $transitions[$targetStatus])) {
            return back()->with('error', 'Transisi status tidak valid untuk laporan ini.');
        }

        $oldStatus = $report->status;

        // Upload after_photo jika ada dan action is resolved
        $afterPhotoUrl = null;
        if ($targetStatus === 'resolved' && $request->hasFile('after_photo')) {
            $cloudinaryConfig = config('filesystems.disks.cloudinary');
            Configuration::instance([
                'cloud' => [
                    'cloud_name' => $cloudinaryConfig['cloud'],
                    'api_key'    => $cloudinaryConfig['key'],
                    'api_secret' => $cloudinaryConfig['secret'],
                ],
                'url' => ['secure' => true],
            ]);

            $uploadApi = new UploadApi();
            $uploaded = $uploadApi->upload($request->file('after_photo')->getRealPath(), [
                'folder'       => 'laporhijau/reports/' . $report->id,
                'quality'      => 'auto',
                'fetch_format' => 'auto',
            ]);
            $afterPhotoUrl = $uploaded['secure_url'];
        }

        // 1. Update status laporan
        $updateData = ['status' => $targetStatus];
        if ($afterPhotoUrl) {
            $updateData['after_photo_url'] = $afterPhotoUrl;
        }
        $report->update($updateData);

        // 2. Catat di report_status_logs
        $notes = match($targetStatus) {
            'in_progress' => 'Laporan sedang ditangani oleh ' . $pemerintah->name,
            'resolved'    => 'Laporan telah diselesaikan oleh ' . $pemerintah->name,
        };

        ReportStatusLog::create([
            'report_id'  => $report->id,
            'old_status' => $oldStatus,
            'new_status' => $targetStatus,
            'changed_by' => $pemerintah->id,
            'notes'      => $notes,
        ]);

        // 3. Insert notifikasi ke reporter
        $notifType = match($targetStatus) {
            'in_progress' => 'laporan_diproses',
            'resolved'    => 'laporan_diselesaikan',
        };

        $notifMessage = match($targetStatus) {
            'in_progress' => "Laporan \"{$report->title}\" Anda sedang ditangani oleh pemerintah.",
            'resolved'    => "Laporan \"{$report->title}\" Anda telah selesai ditangani! +50 poin untuk Anda.",
        };

        Notification::create([
            'user_id' => $report->user_id,
            'type'    => $notifType,
            'data'    => [
                'report_id'    => $report->id,
                'report_title' => $report->title,
                'message'      => $notifMessage,
                'officer_name' => $pemerintah->name,
            ],
        ]);

        // 4. Jika resolved → fire event (+50 poin ke reporter)
        if ($targetStatus === 'resolved') {
            ReportResolved::dispatch($report);
        }

        $flashMsg = match($targetStatus) {
            'in_progress' => "🔧 Laporan \"{$report->title}\" sekarang dalam proses penanganan.",
            'resolved'    => "🎉 Laporan \"{$report->title}\" berhasil diselesaikan! Reporter mendapat +50 poin.",
        };

        return back()->with('success', $flashMsg);
    }

    // ── API Endpoints untuk Chart.js ────────────────────────────

    public function apiStats(): JsonResponse
    {
        return response()->json([
            'total'       => Report::count(),
            'pending'     => Report::where('status', 'pending')->count(),
            'verified'    => Report::where('status', 'verified')->count(),
            'in_progress' => Report::where('status', 'in_progress')->count(),
            'resolved'    => Report::where('status', 'resolved')->count(),
            'rejected'    => Report::where('status', 'rejected')->count(),
        ]);
    }

    public function apiChartTrend(): JsonResponse
    {
        return response()->json($this->getTrendData());
    }

    public function apiChartKategori(): JsonResponse
    {
        return response()->json($this->getCategoryData());
    }

    // ── Private helpers ─────────────────────────────────────────

    private function getTrendData(): array
    {
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $months->push(now()->subMonths($i)->format('Y-m'));
        }

        $raw = Report::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        return [
            'labels' => $months->map(fn($m) => \Carbon\Carbon::createFromFormat('Y-m', $m)->translatedFormat('M Y'))->values()->toArray(),
            'data'   => $months->map(fn($m) => $raw->get($m, 0))->values()->toArray(),
        ];
    }

    private function getCategoryData(): array
    {
        $data = ReportCategory::withCount('reports')
            ->having('reports_count', '>', 0)
            ->orderByDesc('reports_count')
            ->get();

        return [
            'labels' => $data->pluck('name')->toArray(),
            'data'   => $data->pluck('reports_count')->toArray(),
        ];
    }
}
