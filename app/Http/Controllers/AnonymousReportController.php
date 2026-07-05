<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAnonymousReportRequest;
use App\Models\Report;
use App\Models\ReportCategory;
use App\Models\ReportPhoto;
use App\Models\ReportStatusLog;
use App\Events\ReportSubmitted;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;
use Illuminate\Http\Request;

class AnonymousReportController extends Controller
{
    /**
     * Tampilkan form laporan anonim.
     */
    public function create()
    {
        $categories = ReportCategory::orderBy('name')->get();
        return view('laporan-anonim.create', compact('categories'));
    }

    /**
     * Simpan laporan anonim.
     */
    public function store(StoreAnonymousReportRequest $request)
    {
        // Generate kode laporan unik: LA-XXXXXX (6 digit angka)
        $code = 'LA-' . rand(100000, 999999);
        while (Report::where('anonymous_code', $code)->exists()) {
            $code = 'LA-' . rand(100000, 999999);
        }

        // Simpan data laporan ke database
        $report = Report::create([
            'user_id' => null,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => 'pending',
            'is_anonymous' => true,
            'anonymous_name' => $request->anonymous_name,
            'anonymous_contact' => $request->anonymous_contact,
            'anonymous_code' => $code,
        ]);

        // Simpan status log awal
        ReportStatusLog::create([
            'report_id' => $report->id,
            'old_status' => null,
            'new_status' => 'pending',
            'changed_by' => null,
            'notes' => 'Laporan baru diajukan secara anonim.',
        ]);

        // Upload foto ke Cloudinary (max 3 foto)
        if ($request->hasFile('photos')) {
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

            foreach ($request->file('photos') as $photo) {
                $uploaded = $uploadApi->upload($photo->getRealPath(), [
                    'folder'    => 'laporhijau/reports/' . $report->id,
                    'quality'   => 'auto',
                    'fetch_format' => 'auto',
                ]);

                ReportPhoto::create([
                    'report_id' => $report->id,
                    'photo_url' => $uploaded['secure_url'],
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
    public function konfirmasi()
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
    public function cekForm()
    {
        return view('laporan-anonim.cek');
    }

    /**
     * Proses pengecekan kode laporan anonim.
     */
    public function cek(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $code = trim($request->code);
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
