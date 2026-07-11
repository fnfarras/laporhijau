<?php

namespace App\Http\Controllers;

use App\Events\ReportSubmitted;
use App\Http\Requests\CekAnonymousRequest;
use App\Http\Requests\StoreAnonymousReportRequest;
use App\Models\Report;
use App\Models\ReportCategory;
use App\Models\ReportPhoto;
use App\Models\ReportStatusLog;
use App\Services\CloudinaryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AnonymousReportController extends Controller
{
    public function __construct(
        private readonly CloudinaryService $cloudinary
    ) {}

    /**
     * Tampilkan form laporan anonim.
     */
    public function create(): View
    {
        $categories = ReportCategory::orderBy('name')->get();
        return view('laporan-anonim.create', compact('categories'));
    }

    /**
     * Simpan laporan anonim.
     */
    public function store(StoreAnonymousReportRequest $request): RedirectResponse
    {
        // Generate kode laporan unik: LA-XXXXXXXX (8 karakter alphanumeric)
        do {
            $code = 'LA-' . strtoupper(Str::random(6));
        } while (Report::where('anonymous_code', $code)->exists());

        // Simpan data laporan ke database
        $report = Report::create([
            'user_id'           => null,
            'category_id'       => $request->category_id,
            'title'             => $request->title,
            'description'       => $request->description,
            'address'           => $request->address,
            'latitude'          => $request->latitude,
            'longitude'         => $request->longitude,
            'status'            => 'pending',
            'is_anonymous'      => true,
            'anonymous_name'    => $request->anonymous_name,
            'anonymous_contact' => $request->anonymous_contact,
            'anonymous_code'    => $code,
        ]);

        // Simpan status log awal
        ReportStatusLog::create([
            'report_id'  => $report->id,
            'old_status' => null,
            'new_status' => 'pending',
            'changed_by' => null,
            'notes'      => 'Laporan baru diajukan secara anonim.',
        ]);

        // Upload foto ke Cloudinary via CloudinaryService (max 3 foto)
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

        // Trigger Laravel Event
        ReportSubmitted::dispatch($report);

        // Flash kode ke session dan redirect ke halaman sukses
        session()->flash('anonymous_code', $code);
        return redirect()->route('laporan-anonim.konfirmasi');
    }

    /**
     * Tampilkan konfirmasi sukses pengiriman.
     */
    public function konfirmasi(): View|RedirectResponse
    {
        $code = session('anonymous_code');
        if (!$code) {
            return redirect()->route('laporan-anonim.create');
        }

        return view('laporan-anonim.konfirmasi', compact('code'));
    }

    /**
     * Tampilkan form pencarian status laporan.
     */
    public function cekForm(): View
    {
        return view('laporan-anonim.cek');
    }

    /**
     * Proses pengecekan kode laporan anonim via Form Request.
     */
    public function cek(CekAnonymousRequest $request): View|RedirectResponse
    {
        $code      = trim($request->validated()['code']);
        $cleanCode = str_replace('#', '', $code);

        $report = Report::where('anonymous_code', $cleanCode)
            ->with(['category', 'statusLogs' => fn($q) => $q->orderBy('created_at')])
            ->first();

        if (!$report) {
            return redirect()->back()
                ->withErrors(['code' => 'Kode laporan tidak ditemukan atau tidak valid.'])
                ->withInput();
        }

        return view('laporan-anonim.cek-status', compact('report', 'code'));
    }
}
