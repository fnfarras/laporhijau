<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportCategory;
use App\Models\ReportStatusLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class OpenDataController extends Controller
{
    /**
     * Tampilkan Halaman Open Data Dashboard.
     */
    public function index(Request $request)
    {
        $stats = $this->getStatsData();

        // Query tabel laporan resolved
        $query = Report::where('status', 'resolved')
            ->with(['category', 'statusLogs' => fn($q) => $q->where('new_status', 'resolved')]);

        // Filter kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Search lokasi/alamat
        if ($request->filled('search')) {
            $query->where(fn($q) => $q->where('address', 'like', '%' . $request->search . '%')
                ->orWhere('title', 'like', '%' . $request->search . '%'));
        }

        $reports = $query->latest()->paginate(20)->withQueryString();
        $categories = ReportCategory::orderBy('name')->get();

        return view('open-data.index', compact('stats', 'reports', 'categories'));
    }

    /**
     * API: Statistik Open Data (JSON).
     */
    public function apiStats()
    {
        return response()->json($this->getStatsData());
    }

    /**
     * API: Laporan Resolved Publik (JSON).
     */
    public function apiReports()
    {
        $reports = Report::where('status', 'resolved')
            ->with(['category', 'statusLogs' => fn($q) => $q->where('new_status', 'resolved')])
            ->get()
            ->map(fn($rpt) => [
                'id' => $rpt->id,
                'kategori' => $rpt->category->name ?? '-',
                'alamat' => $rpt->address,
                'lat' => (float)$rpt->latitude,
                'lng' => (float)$rpt->longitude,
                'created_at' => $rpt->created_at->format('Y-m-d H:i:s'),
                'resolved_at' => ($rpt->statusLogs->first() ? $rpt->statusLogs->first()->created_at : $rpt->updated_at)->format('Y-m-d H:i:s'),
            ]);

        return response()->json($reports);
    }

    /**
     * API: GeoJSON Laporan.
     */
    public function apiGeoJson()
    {
        return response($this->generateGeoJsonData(), 200, [
            'Content-Type' => 'application/geo+json'
        ]);
    }

    /**
     * Download: CSV Laporan Resolved — rapi & siap buka di Excel.
     */
    public function downloadCsv()
    {
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="laporan_laporhijau_' . now()->format('Ymd') . '.csv"',
            'Cache-Control'       => 'no-cache, no-store, must-revalidate',
            'Pragma'              => 'no-cache',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // UTF-8 BOM agar Excel baca karakter Indonesia

            // ── Header baris info aplikasi ────────────────────────────
            fputcsv($file, ['LaporHijau — Data Open Laporan Lingkungan']);
            fputcsv($file, ['Diunduh pada:', now()->translatedFormat('d F Y, H:i') . ' WIB']);
            fputcsv($file, ['Sumber:', config('app.url')]);
            fputcsv($file, []); // baris kosong

            // ── Baris kolom ───────────────────────────────────────────
            fputcsv($file, [
                'No',
                'ID Laporan',
                'Judul Laporan',
                'Kategori',
                'Alamat Lokasi',
                'Latitude',
                'Longitude',
                'Tanggal Lapor',
                'Tanggal Selesai',
                'Durasi Penanganan (Hari)',
            ]);

            $counter = 1;
            Report::where('status', 'resolved')
                ->with(['category', 'statusLogs' => fn($q) => $q->where('new_status', 'resolved')])
                ->orderBy('created_at')
                ->chunk(200, function ($reports) use ($file, &$counter) {
                    foreach ($reports as $rpt) {
                        $resolvedLog = $rpt->statusLogs->first();
                        $resolvedAt  = $resolvedLog ? $resolvedLog->created_at : $rpt->updated_at;
                        $durationDay = round($rpt->created_at->diffInHours($resolvedAt) / 24, 1);

                        fputcsv($file, [
                            $counter++,
                            $rpt->id,
                            $rpt->title,
                            $rpt->category->name ?? '-',
                            $rpt->address,
                            number_format((float) $rpt->latitude,  7, '.', ''),
                            number_format((float) $rpt->longitude, 7, '.', ''),
                            $rpt->created_at->format('d/m/Y H:i'),
                            $resolvedAt->format('d/m/Y H:i'),
                            $durationDay,
                        ]);
                    }
                });

            fclose($file);
        };

        return response()->streamDownload($callback, 'laporan_laporhijau_' . now()->format('Ymd') . '.csv', $headers);
    }

    /**
     * Download: Excel (.xls) Laporan Resolved — dengan header info & styling.
     */
    public function downloadExcel()
    {
        $filename = 'laporan_laporhijau_' . now()->format('Ymd') . '.xls';

        $headers = [
            'Content-Type'        => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control'       => 'no-cache, no-store, must-revalidate',
            'Pragma'              => 'no-cache',
        ];

        $callback = function () {
            echo "\xEF\xBB\xBF"; // UTF-8 BOM
            echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" ';
            echo 'xmlns:x="urn:schemas-microsoft-com:office:excel" ';
            echo 'xmlns="http://www.w3.org/TR/REC-html40">';
            echo '<head><meta charset="utf-8"></head>';
            echo '<body>';

            // ── Info header ──────────────────────────────────────────
            echo '<table>';
            echo '<tr><td colspan="10" style="font-size:16pt;font-weight:bold;color:#16a34a;">';
            echo '🌿 LaporHijau — Data Open Laporan Lingkungan</td></tr>';
            echo '<tr><td colspan="10" style="font-size:10pt;color:#6b7280;">';
            echo 'Diunduh pada: ' . htmlspecialchars(now()->translatedFormat('d F Y, H:i') . ' WIB');
            echo ' &nbsp;|&nbsp; Sumber: ' . htmlspecialchars(config('app.url')) . '</td></tr>';
            echo '<tr><td colspan="10"></td></tr>'; // baris kosong
            echo '</table>';

            // ── Tabel data ───────────────────────────────────────────
            echo '<table border="1" cellspacing="0" cellpadding="4" ';
            echo 'style="border-collapse:collapse;font-family:Arial,sans-serif;font-size:10pt;">';

            // Header kolom dengan background hijau
            $thStyle = 'style="background-color:#16a34a;color:#ffffff;font-weight:bold;';
            $thStyle .= 'text-align:center;white-space:nowrap;padding:6px 10px;"';
            echo '<tr>';
            foreach ([
                'No', 'ID', 'Judul Laporan', 'Kategori',
                'Alamat Lokasi', 'Latitude', 'Longitude',
                'Tanggal Lapor', 'Tanggal Selesai', 'Durasi (Hari)',
            ] as $col) {
                echo "<th {$thStyle}>" . htmlspecialchars($col) . '</th>';
            }
            echo '</tr>';

            $counter = 1;
            $rowEven = 'style="background-color:#f0fdf4;"';
            $rowOdd  = 'style="background-color:#ffffff;"';

            Report::where('status', 'resolved')
                ->with(['category', 'statusLogs' => fn($q) => $q->where('new_status', 'resolved')])
                ->orderBy('created_at')
                ->chunk(200, function ($reports) use (&$counter, $rowEven, $rowOdd) {
                    foreach ($reports as $rpt) {
                        $resolvedLog = $rpt->statusLogs->first();
                        $resolvedAt  = $resolvedLog ? $resolvedLog->created_at : $rpt->updated_at;
                        $durationDay = round($rpt->created_at->diffInHours($resolvedAt) / 24, 1);
                        $rowStyle    = ($counter % 2 === 0) ? $rowEven : $rowOdd;

                        echo "<tr {$rowStyle}>";
                        echo '<td style="text-align:center;">' . $counter++ . '</td>';
                        echo '<td style="text-align:center;">' . $rpt->id . '</td>';
                        echo '<td>' . htmlspecialchars($rpt->title) . '</td>';
                        echo '<td style="text-align:center;">' . htmlspecialchars($rpt->category->name ?? '-') . '</td>';
                        echo '<td>' . htmlspecialchars($rpt->address) . '</td>';
                        echo '<td style="text-align:right;mso-number-format:\"0\\.0000000\";">' . number_format((float)$rpt->latitude,  7, '.', '') . '</td>';
                        echo '<td style="text-align:right;mso-number-format:\"0\\.0000000\";">' . number_format((float)$rpt->longitude, 7, '.', '') . '</td>';
                        echo '<td style="text-align:center;white-space:nowrap;">' . $rpt->created_at->format('d/m/Y H:i') . '</td>';
                        echo '<td style="text-align:center;white-space:nowrap;">' . $resolvedAt->format('d/m/Y H:i') . '</td>';
                        echo '<td style="text-align:center;">' . $durationDay . '</td>';
                        echo '</tr>';
                    }
                });

            echo '</table>';
            echo '</body></html>';
        };

        return response()->streamDownload($callback, $filename, $headers);
    }

    /**
     * Download: GeoJSON Laporan.
     */
    public function downloadGeoJson()
    {
        return response($this->generateGeoJsonData(), 200, [
            'Content-Type' => 'application/geo+json',
            'Content-Disposition' => 'attachment; filename="laporan_geospasial_laporhijau.geojson"',
        ]);
    }

    /**
     * Helper: Menghimpun & meng-cache data statistik selama 30 menit.
     */
    private function getStatsData()
    {
        return Cache::remember('open_data_stats', 1800, function () {
            // 1. Total laporan per kategori
            $categoriesCount = ReportCategory::withCount('reports')
                ->orderByDesc('reports_count')
                ->get()
                ->map(fn($cat) => [
                    'name' => $cat->name,
                    'icon' => $cat->icon,
                    'count' => $cat->reports_count,
                ]);

            // 2. Total laporan per status
            $statusCount = Report::selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status')
                ->toArray();

            $statuses = ['pending', 'verified', 'rejected', 'in_progress', 'resolved'];
            foreach ($statuses as $st) {
                if (!isset($statusCount[$st])) {
                    $statusCount[$st] = 0;
                }
            }

            // 3. Tren laporan per bulan (12 bulan terakhir)
            $monthlyTrend = [];
            for ($i = 11; $i >= 0; $i--) {
                $monthDate = now()->subMonths($i);
                $monthLabel = $monthDate->translatedFormat('F Y');
                $monthKey = $monthDate->format('Y-m');
                $start = $monthDate->copy()->startOfMonth();
                $end = $monthDate->copy()->endOfMonth();

                $laporanMasuk = Report::whereBetween('created_at', [$start, $end])->count();
                $laporanSelesai = ReportStatusLog::where('new_status', 'resolved')
                    ->whereBetween('created_at', [$start, $end])
                    ->count();

                $monthlyTrend[] = [
                    'label' => $monthLabel,
                    'key' => $monthKey,
                    'masuk' => $laporanMasuk,
                    'selesai' => $laporanSelesai,
                ];
            }

            // 4. Top 5 lokasi dengan laporan terbanyak
            $topLocations = Report::selectRaw('address, count(*) as count')
                ->groupBy('address')
                ->orderByDesc('count')
                ->limit(5)
                ->get()
                ->map(fn($rpt) => [
                    'address' => $rpt->address,
                    'count' => $rpt->count,
                ]);

            // 5. Rata-rata waktu penyelesaian & SLA Handled
            $resolvedReports = Report::where('status', 'resolved')
                ->with(['statusLogs' => fn($q) => $q->where('new_status', 'resolved')])
                ->get();

            $totalDays = 0;
            $resolvedCount = $resolvedReports->count();
            $onTimeCount = 0;

            foreach ($resolvedReports as $report) {
                $resolvedLog = $report->statusLogs->first();
                $resolvedAt = $resolvedLog ? $resolvedLog->created_at : $report->updated_at;

                $diffInHours = $report->created_at->diffInHours($resolvedAt);
                $days = $diffInHours / 24;
                $totalDays += $days;

                if (!$report->handled_deadline || $resolvedAt->lte($report->handled_deadline)) {
                    $onTimeCount++;
                }
            }

            $avgResolutionTime = $resolvedCount > 0 ? round($totalDays / $resolvedCount, 1) : 0;
            $slaPercentage = $resolvedCount > 0 ? round(($onTimeCount / $resolvedCount) * 100, 1) : 100;

            // 6. Total relawan aktif
            $totalVolunteers = User::whereHas('roles', fn($q) => $q->where('name', 'relawan'))->count();

            // 7. Total poin komunitas
            $totalPoints = User::sum('points');

            // 8. Laporan per hari dalam 30 hari terakhir — 2 query GROUP BY pengganti 60 query loop
            $thirtyDaysAgo = now()->subDays(29)->startOfDay();

            $resolvedByDay = \Illuminate\Support\Facades\DB::table('reports')
                ->selectRaw("DATE(created_at) as day, COUNT(*) as cnt")
                ->where('status', 'resolved')
                ->where('created_at', '>=', $thirtyDaysAgo)
                ->groupByRaw('DATE(created_at)')
                ->pluck('cnt', 'day');

            $pendingByDay = \Illuminate\Support\Facades\DB::table('reports')
                ->selectRaw("DATE(created_at) as day, COUNT(*) as cnt")
                ->where('status', 'pending')
                ->where('created_at', '>=', $thirtyDaysAgo)
                ->groupByRaw('DATE(created_at)')
                ->pluck('cnt', 'day');

            $dailyStats = [];
            for ($i = 29; $i >= 0; $i--) {
                $dayDate  = now()->subDays($i);
                $dayKey   = $dayDate->format('Y-m-d');
                $dailyStats[] = [
                    'label'    => $dayDate->format('d M'),
                    'resolved' => (int) ($resolvedByDay[$dayKey] ?? 0),
                    'pending'  => (int) ($pendingByDay[$dayKey] ?? 0),
                ];
            }

            return [
                'total_reports' => Report::count(),
                'resolved_count' => $resolvedCount,
                'sla_percentage' => $slaPercentage,
                'avg_resolution_time' => $avgResolutionTime,
                'categories_count' => $categoriesCount,
                'status_count' => $statusCount,
                'monthly_trend' => $monthlyTrend,
                'top_locations' => $topLocations,
                'total_volunteers' => $totalVolunteers,
                'total_points' => $totalPoints,
                'daily_stats' => $dailyStats,
                'last_updated' => now()->format('d F Y H:i'),
            ];
        });
    }

    /**
     * Helper: Menghasilkan format GeoJSON untuk seluruh laporan.
     */
    private function generateGeoJsonData()
    {
        $reports = Report::with(['category'])->get();
        
        $features = [];
        foreach ($reports as $rpt) {
            $features[] = [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [(float)$rpt->longitude, (float)$rpt->latitude],
                ],
                'properties' => [
                    'id' => $rpt->id,
                    'kategori' => $rpt->category->name ?? '-',
                    'status' => $rpt->status,
                    'tanggal' => $rpt->created_at->format('Y-m-d'),
                ]
            ];
        }

        return json_encode([
            'type' => 'FeatureCollection',
            'features' => $features,
        ], JSON_PRETTY_PRINT);
    }
}
