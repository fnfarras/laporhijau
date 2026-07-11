<x-app-layout>
    @section('title', 'Data Terbuka Laporan Lingkungan — LaporHijau')
    @section('meta_description', 'Akses data terbuka laporan lingkungan. Download CSV, Excel, dan GeoJSON. Pantau statistik SLA dan tren penanganan masalah lingkungan di Indonesia.')

    @push('styles')
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            .chart-card {
                background: #ffffff;
                border-radius: 16px;
                border: 1px solid #f1f5f9;
                padding: 24px;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            }
            .dark .chart-card {
                background: #1e293b;
                border-color: #334155;
            }
            .table-striped-custom tbody tr:nth-of-type(odd) {
                background-color: #f8fafc;
            }
            .dark .table-striped-custom tbody tr:nth-of-type(odd) {
                background-color: #1e293b;
            }
            .table-striped-custom tbody tr:hover {
                background-color: #f0fdf4;
            }
            .dark .table-striped-custom tbody tr:hover {
                background-color: #0f172a;
            }
        </style>
    @endpush

    <div class="bg-gradient-to-b from-green-50/70 to-white dark:from-slate-900/30 dark:to-slate-900 min-h-screen py-8 pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

            {{-- ── 1. HEADER SECTION ───────────────────────────────────── --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700/80 p-6 md:p-8 shadow-sm">
                <div class="space-y-3.5 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-green-600 text-white shadow-sm">
                            🔓 Open Data
                        </span>
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-gray-100 dark:bg-slate-700 text-gray-500 dark:text-gray-400">
                            📅 Update: {{ $stats['last_updated'] }}
                        </span>
                    </div>
                    
                    <h1 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white leading-tight">
                        Data Terbuka Lingkungan Padang
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed max-w-2xl">
                        Data laporan masalah lingkungan warga yang transparan, dapat diakses publik secara bebas, dan digunakan untuk mendorong penanggulangan tata kota yang berkelanjutan.
                    </p>
                </div>

                {{-- Quick Downloads & API link --}}
                <div class="flex flex-wrap md:flex-col lg:flex-row items-stretch md:items-end lg:items-center gap-3">
                    <a href="{{ route('open-data.download.csv') }}" class="flex-1 lg:flex-initial inline-flex items-center justify-center gap-2 px-4 py-2.5 border border-green-600 dark:border-green-500 hover:bg-green-50 dark:hover:bg-green-950/20 text-green-700 dark:text-green-400 text-xs font-bold rounded-xl transition-all">
                        📥 Unduh CSV
                    </a>
                    <a href="{{ route('open-data.download.excel') }}" class="flex-1 lg:flex-initial inline-flex items-center justify-center gap-2 px-4 py-2.5 border border-green-600 dark:border-green-500 hover:bg-green-50 dark:hover:bg-green-950/20 text-green-700 dark:text-green-400 text-xs font-bold rounded-xl transition-all">
                        📊 Unduh Excel
                    </a>
                    <a href="#api-publik" class="flex-1 lg:flex-initial inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-xl transition-all shadow-md">
                        🔗 API Endpoint
                    </a>
                </div>
            </div>

            {{-- ── 2. HIGHLIGHT STATS (4 Kartu Besar) ───────────────────── --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                {{-- Card 1: Total Laporan --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl border-l-4 border-l-green-600 border border-gray-100 dark:border-slate-700/80 p-5 shadow-sm space-y-1">
                    <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Total Laporan</span>
                    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white">{{ number_format($stats['total_reports']) }}</h2>
                    <span class="text-[10px] text-gray-400 block">Akumulasi laporan masuk</span>
                </div>

                {{-- Card 2: Selesai Ditangani --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl border-l-4 border-l-sky-600 border border-gray-100 dark:border-slate-700/80 p-5 shadow-sm space-y-1">
                    <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Selesai Ditangani</span>
                    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white">{{ number_format($stats['resolved_count']) }}</h2>
                    <span class="text-[10px] text-gray-400 block">Laporan berstatus resolved</span>
                </div>

                {{-- Card 3: SLA Rate --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl border-l-4 border-l-amber-500 border border-gray-100 dark:border-slate-700/80 p-5 shadow-sm space-y-1">
                    <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider block">SLA Rate</span>
                    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white">{{ $stats['sla_percentage'] }}%</h2>
                    <span class="text-[10px] text-gray-400 block">Tuntas tepat waktu (≤ 7 hari)</span>
                </div>

                {{-- Card 4: Avg. Respons --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl border-l-4 border-l-purple-600 border border-gray-100 dark:border-slate-700/80 p-5 shadow-sm space-y-1">
                    <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Avg. Respons</span>
                    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white">{{ $stats['avg_resolution_time'] }} Hari</h2>
                    <span class="text-[10px] text-gray-400 block">Rata-rata waktu penyelesaian</span>
                </div>
            </div>

            {{-- ── 3. GRAFIK SECTION (Chart.js) ────────────────────────── --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Chart 1: Tren Laporan --}}
                <div class="chart-card flex flex-col justify-between min-h-[350px]">
                    <h3 class="text-sm font-extrabold text-gray-850 dark:text-white mb-4 border-b border-gray-50 dark:border-slate-700 pb-2">
                        📈 Tren Laporan Lingkungan 12 Bulan Terakhir
                    </h3>
                    <div class="flex-1 relative">
                        <canvas id="chartTrend"></canvas>
                    </div>
                </div>

                {{-- Chart 2: Distribusi Kategori --}}
                <div class="chart-card flex flex-col justify-between min-h-[350px]">
                    <h3 class="text-sm font-extrabold text-gray-850 dark:text-white mb-4 border-b border-gray-50 dark:border-slate-700 pb-2">
                        🌳 Distribusi Laporan per Kategori Masalah
                    </h3>
                    <div class="flex-1 relative">
                        <canvas id="chartCategory"></canvas>
                    </div>
                </div>

                {{-- Chart 3: Status Laporan --}}
                <div class="chart-card flex flex-col justify-between min-h-[350px]">
                    <h3 class="text-sm font-extrabold text-gray-850 dark:text-white mb-4 border-b border-gray-50 dark:border-slate-700 pb-2">
                        📊 Status Penanganan Laporan
                    </h3>
                    <div class="flex-1 relative">
                        <canvas id="chartStatus"></canvas>
                    </div>
                </div>

                {{-- Chart 4: Aktivitas Harian 30 Hari --}}
                <div class="chart-card flex flex-col justify-between min-h-[350px]">
                    <h3 class="text-sm font-extrabold text-gray-850 dark:text-white mb-4 border-b border-gray-50 dark:border-slate-700 pb-2">
                        📅 Aktivitas Laporan 30 Hari Terakhir
                    </h3>
                    <div class="flex-1 relative">
                        <canvas id="chartDaily"></canvas>
                    </div>
                </div>
            </div>

            {{-- ── 4. TABEL DATA PUBLIK ────────────────────────────────── --}}
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700/80 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-slate-700">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">📋 Arsip Laporan Selesai (Resolved)</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Menampilkan data laporan historis yang telah diselesaikan oleh dinas terkait.</p>

                    {{-- Filter Form --}}
                    <form method="GET" action="{{ route('open-data') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mt-4">
                        {{-- Kategori --}}
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1">Kategori</label>
                            <select name="category_id" onchange="this.form.submit()" class="w-full text-xs py-2 px-3 border border-gray-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300">
                                <option value="">Semua Kategori</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->icon }} {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tanggal Mulai --}}
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1">Mulai Tanggal</label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}" onchange="this.form.submit()" class="w-full text-xs py-2 px-3 border border-gray-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300">
                        </div>

                        {{-- Tanggal Selesai --}}
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1">Sampai Tanggal</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}" onchange="this.form.submit()" class="w-full text-xs py-2 px-3 border border-gray-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300">
                        </div>

                        {{-- Search --}}
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1">Cari Lokasi / Judul</label>
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik lalu tekan enter..." class="w-full text-xs py-2 pl-3 pr-8 border border-gray-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300">
                                <span class="absolute right-3 top-2 text-xs text-gray-400">🔍</span>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Table Element --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse table-striped-custom">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-slate-700 border-b border-gray-100 dark:border-slate-600 text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-300">
                                <th class="p-4">ID</th>
                                <th class="p-4">Kategori</th>
                                <th class="p-4">Alamat</th>
                                <th class="p-4">Tanggal Lapor</th>
                                <th class="p-4">Tanggal Selesai</th>
                                <th class="p-4 text-center">Durasi (Hari)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reports as $rpt)
                                @php
                                    $resolvedLog = $rpt->statusLogs->first();
                                    $resolvedAt = $resolvedLog ? $resolvedLog->created_at : $rpt->updated_at;
                                    $duration = round($rpt->created_at->diffInHours($resolvedAt) / 24, 1);
                                @endphp
                                <tr class="border-b border-gray-100 dark:border-slate-700 text-gray-600 dark:text-gray-350">
                                    <td class="p-4 font-bold text-green-700 dark:text-green-400">#{{ $rpt->id }}</td>
                                    <td class="p-4 font-semibold">{{ $rpt->category->icon ?? '📋' }} {{ $rpt->category->name ?? '-' }}</td>
                                    <td class="p-4 max-w-xs truncate" title="{{ $rpt->address }}">{{ $rpt->address }}</td>
                                    <td class="p-4">{{ $rpt->created_at->format('d M Y H:i') }}</td>
                                    <td class="p-4">{{ $resolvedAt->format('d M Y H:i') }}</td>
                                    <td class="p-4 text-center font-bold text-gray-700 dark:text-white">{{ $duration }} hari</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-8 text-center text-gray-400">Tidak ada data laporan yang cocok.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Links --}}
                @if($reports->hasPages())
                    <div class="p-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800">
                        {{ $reports->links() }}
                    </div>
                @endif
            </div>

            {{-- ── 5. SECTION "UNDUH DATA" (3 Kartu Download) ──────────── --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Card 1: Data Laporan --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700/80 p-6 flex flex-col justify-between shadow-sm space-y-4">
                    <div class="space-y-2">
                        <span class="text-2xl block">📥</span>
                        <h4 class="font-bold text-gray-900 dark:text-white text-sm">Data Laporan</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                            Kumpulan seluruh data laporan berstatus **resolved** yang telah diproses secara anonim untuk riset dan publikasi.
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('open-data.download.csv') }}" class="flex-1 text-center py-2.5 bg-green-50 hover:bg-green-100 dark:bg-green-950/20 text-green-700 dark:text-green-400 text-xs font-bold rounded-xl transition-all border border-green-200 dark:border-green-800">
                            Unduh CSV
                        </a>
                        <a href="{{ route('open-data.download.excel') }}" class="flex-1 text-center py-2.5 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm">
                            Unduh Excel
                        </a>
                    </div>
                </div>

                {{-- Card 2: Statistik Bulanan --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700/80 p-6 flex flex-col justify-between shadow-sm space-y-4">
                    <div class="space-y-2">
                        <span class="text-2xl block">📊</span>
                        <h4 class="font-bold text-gray-900 dark:text-white text-sm">Statistik Bulanan</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                            Ringkasan performa bulanan selama 12 bulan terakhir termasuk jumlah laporan masuk dan penyelesaian.
                        </p>
                    </div>
                    <a href="{{ route('api.open-data.stats') }}" target="_blank" class="block text-center py-2.5 bg-green-50 hover:bg-green-100 dark:bg-green-950/20 text-green-700 dark:text-green-400 text-xs font-bold rounded-xl transition-all border border-green-200 dark:border-green-800">
                        Unduh JSON Rekap
                    </a>
                </div>

                {{-- Card 3: Data Geospasial --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700/80 p-6 flex flex-col justify-between shadow-sm space-y-4">
                    <div class="space-y-2">
                        <span class="text-2xl block">🗺️</span>
                        <h4 class="font-bold text-gray-900 dark:text-white text-sm">Data Geospasial</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                            Data koordinat peta sebaran seluruh laporan lingkungan untuk dianalisis di aplikasi GIS (QGIS, ArcGIS, dll).
                        </p>
                    </div>
                    <a href="{{ route('open-data.download.geojson') }}" class="block text-center py-2.5 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm">
                        Unduh GeoJSON
                    </a>
                </div>
            </div>

            {{-- ── 6. SECTION "API PUBLIK" ─────────────────────────────── --}}
            <div id="api-publik" class="bg-slate-900 text-slate-100 rounded-3xl p-6 sm:p-8 shadow-xl space-y-6">
                <div class="space-y-2">
                    <h3 class="text-base font-extrabold text-white flex items-center gap-2">
                        ⚡ API Publik LaporHijau
                    </h3>
                    <p class="text-xs text-slate-400 leading-relaxed max-w-xl">
                        Akses data secara programatik melalui API publik kami. API ini gratis, terbuka untuk umum (peneliti, jurnalis, mahasiswa), dan **tidak memerlukan API Key** untuk proses otorisasi.
                    </p>
                </div>

                <div class="space-y-4">
                    <div>
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1.5">Mendapatkan statistik ringkas</span>
                        <div class="flex items-center gap-2 bg-slate-950 px-4 py-2.5 rounded-xl font-mono text-[11px] overflow-x-auto text-emerald-400 whitespace-nowrap">
                            <span class="text-sky-400 font-bold">GET</span> {{ url('/api/open-data/stats') }}
                        </div>
                    </div>

                    <div>
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1.5">Mendapatkan arsip laporan resolved</span>
                        <div class="flex items-center gap-2 bg-slate-950 px-4 py-2.5 rounded-xl font-mono text-[11px] overflow-x-auto text-emerald-400 whitespace-nowrap">
                            <span class="text-sky-400 font-bold">GET</span> {{ url('/api/open-data/reports') }}
                        </div>
                    </div>

                    <div>
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1.5">Mendapatkan data peta (GeoJSON)</span>
                        <div class="flex items-center gap-2 bg-slate-950 px-4 py-2.5 rounded-xl font-mono text-[11px] overflow-x-auto text-emerald-400 whitespace-nowrap">
                            <span class="text-sky-400 font-bold">GET</span> {{ url('/api/open-data/geojson') }}
                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-800 pt-4 text-[10px] text-slate-500 flex items-center gap-2">
                    <span>💡</span>
                    <span>Format respons adalah application/json dan application/geo+json. Silakan hubungi pengelola sistem jika membutuhkan batasan limit kuota yang lebih besar untuk riset skala besar.</span>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Data untuk Chart dari Controller PHP
            const monthlyTrend = @json($stats['monthly_trend']);
            const categoriesCount = @json($stats['categories_count']);
            const statusCount = @json($stats['status_count']);
            const dailyStats = @json($stats['daily_stats']);
            
            const isDark = document.documentElement.classList.contains('dark');
            const gridColor = isDark ? '#334155' : '#f1f5f9';
            const textColor = isDark ? '#94a3b8' : '#64748b';

            // Chart 1: Tren Laporan 12 Bulan (Line Chart)
            const ctxTrend = document.getElementById('chartTrend').getContext('2d');
            new Chart(ctxTrend, {
                type: 'line',
                data: {
                    labels: monthlyTrend.map(item => item.label),
                    datasets: [
                        {
                            label: 'Laporan Masuk',
                            data: monthlyTrend.map(item => item.masuk),
                            borderColor: '#16a34a',
                            backgroundColor: 'rgba(22, 163, 74, 0.1)',
                            fill: true,
                            tension: 0.3,
                            borderWidth: 3
                        },
                        {
                            label: 'Laporan Selesai',
                            data: monthlyTrend.map(item => item.selesai),
                            borderColor: '#0ea5e9',
                            backgroundColor: 'rgba(14, 165, 233, 0.1)',
                            fill: true,
                            tension: 0.3,
                            borderWidth: 3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { labels: { color: textColor } }
                    },
                    scales: {
                        x: { grid: { color: gridColor }, ticks: { color: textColor } },
                        y: { grid: { color: gridColor }, ticks: { color: textColor, stepSize: 1 } }
                    }
                }
            });

            // Chart 2: Distribusi Kategori (Doughnut Chart)
            const ctxCategory = document.getElementById('chartCategory').getContext('2d');
            new Chart(ctxCategory, {
                type: 'doughnut',
                data: {
                    labels: categoriesCount.map(item => item.name),
                    datasets: [{
                        data: categoriesCount.map(item => item.count),
                        backgroundColor: [
                            '#16a34a', '#0ea5e9', '#f59e0b', '#ef4444', 
                            '#8b5cf6', '#ec4899', '#64748b'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right', labels: { color: textColor, boxWidth: 12 } }
                    }
                }
            });

            // Chart 3: Status Laporan (Horizontal Bar Chart)
            const ctxStatus = document.getElementById('chartStatus').getContext('2d');
            new Chart(ctxStatus, {
                type: 'bar',
                data: {
                    labels: ['Menunggu', 'Terverifikasi', 'Diproses', 'Selesai', 'Ditolak'],
                    datasets: [{
                        label: 'Jumlah Laporan',
                        data: [
                            statusCount['pending'] || 0,
                            statusCount['verified'] || 0,
                            statusCount['in_progress'] || 0,
                            statusCount['resolved'] || 0,
                            statusCount['rejected'] || 0
                        ],
                        backgroundColor: ['#f59e0b', '#0ea5e9', '#3b82f6', '#16a34a', '#ef4444'],
                        borderRadius: 8
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: { grid: { color: gridColor }, ticks: { color: textColor, stepSize: 1 } },
                        y: { grid: { color: 'transparent' }, ticks: { color: textColor } }
                    }
                }
            });

            // Chart 4: Aktivitas Harian 30 Hari (Bar Chart)
            const ctxDaily = document.getElementById('chartDaily').getContext('2d');
            new Chart(ctxDaily, {
                type: 'bar',
                data: {
                    labels: dailyStats.map(item => item.label),
                    datasets: [
                        {
                            label: 'Laporan Selesai',
                            data: dailyStats.map(item => item.resolved),
                            backgroundColor: '#16a34a',
                            borderRadius: 4
                        },
                        {
                            label: 'Laporan Baru (Menunggu)',
                            data: dailyStats.map(item => item.pending),
                            backgroundColor: '#94a3b8',
                            borderRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { labels: { color: textColor } }
                    },
                    scales: {
                        x: { stacked: true, grid: { color: 'transparent' }, ticks: { color: textColor, maxRotation: 45 } },
                        y: { stacked: true, grid: { color: gridColor }, ticks: { color: textColor, stepSize: 1 } }
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>
