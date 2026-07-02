<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class MapController extends Controller
{
    /**
     * Tampilkan halaman peta interaktif (publik).
     */
    public function index(): View
    {
        $categories = ReportCategory::orderBy('name')->get();
        return view('peta', compact('categories'));
    }

    /**
     * Ambil data JSON semua laporan untuk Leaflet.
     */
    public function getData(): JsonResponse
    {
        $reports = Report::with(['category', 'photos', 'user'])
            ->get()
            ->map(function ($report) {
                return [
                    'id'            => $report->id,
                    'title'         => $report->title,
                    'category'      => [
                        'id'   => $report->category?->id,
                        'name' => $report->category?->name,
                        'icon' => $report->category?->icon ?? '📋',
                    ],
                    'status'        => $report->status,
                    'latitude'      => (float) $report->latitude,
                    'longitude'     => (float) $report->longitude,
                    'address'       => $report->address,
                    'photo_url'     => $report->photos->first()?->photo_url,
                    'created_at'    => $report->created_at->format('d M Y H:i'),
                    'reporter_name' => $report->user?->name ?? 'Masyarakat',
                ];
            });

        return response()->json($reports);
    }
}
