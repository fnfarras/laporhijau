<?php

namespace App\Http\Controllers;

use App\Events\ReportSubmitted;
use App\Http\Requests\StoreReportRequest;
use App\Models\Report;
use App\Models\ReportCategory;
use App\Models\ReportPhoto;
use App\Models\ReportStatusLog;
use App\Services\CloudinaryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(
        private readonly CloudinaryService $cloudinary
    ) {}

    /**
     * Daftar laporan milik user yang sedang login.
     */
    public function index(): View
    {
        $reports = auth()->user()
            ->reports()
            ->with(['category', 'photos'])
            ->latest()
            ->paginate(10);

        return view('laporan.index', compact('reports'));
    }

    /**
     * Form buat laporan baru.
     */
    public function create(): View
    {
        Gate::authorize('create', Report::class);

        $categories = cache()->remember('laporhijau_categories', 3600, fn() => ReportCategory::orderBy('name')->get());

        return view('laporan.create', compact('categories'));
    }

    /**
     * Simpan laporan baru ke database.
     * Validasi via StoreReportRequest (bukan validate() langsung).
     * Upload foto ke Cloudinary via CloudinaryService, fire Event untuk poin.
     */
    public function store(StoreReportRequest $request): RedirectResponse
    {
        Gate::authorize('create', Report::class);

        // 1. Simpan laporan
        $report = Report::create([
            'user_id'     => auth()->id(),
            'category_id' => $request->category_id,
            'title'       => $request->title,
            'description' => $request->description,
            'address'     => $request->address,
            'latitude'    => $request->latitude,
            'longitude'   => $request->longitude,
            'status'      => 'pending',
        ]);

        // 2. Catat status awal ke report_status_logs
        ReportStatusLog::create([
            'report_id'  => $report->id,
            'old_status' => null,
            'new_status' => 'pending',
            'changed_by' => auth()->id(),
            'notes'      => 'Laporan baru diajukan oleh masyarakat.',
        ]);

        // 3. Upload foto ke Cloudinary via service (maks 5 foto)
        if ($request->hasFile('photos')) {
            $folder = 'laporhijau/reports/' . $report->id;

            foreach ($request->file('photos') as $photo) {
                $url = $this->cloudinary->upload($photo, $folder);

                ReportPhoto::create([
                    'report_id' => $report->id,
                    'photo_url' => $url,
                ]);
            }
        }

        // 4. Fire event → Listener akan memberi +5 poin
        ReportSubmitted::dispatch($report);

        return redirect()
            ->route('laporan.show', $report)
            ->with('success', '✅ Laporan berhasil dikirim! Poin +5 akan segera diproses.');
    }

    /**
     * Detail laporan lengkap dengan foto, timeline status, dan komentar.
     */
    public function show(Report $report): View
    {
        Gate::authorize('view', $report);

        $report->load([
            'user',
            'category',
            'photos',
            'statusLogs.changedBy',
            'comments.user',
        ]);

        return view('laporan.show', compact('report'));
    }
}
