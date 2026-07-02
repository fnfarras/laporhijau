<x-pemerintah-layout>
    @section('title', 'Dashboard Pemerintah')

    @push('styles')
    <style>
        .stat-card { transition: transform 0.2s, box-shadow 0.2s; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.08); }
    </style>
    @endpush

    <div class="pt-6 pb-4">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard Pemerintah</h1>
        <p class="text-sm text-gray-500 mt-0.5">Pantau dan kelola laporan lingkungan dari masyarakat</p>
    </div>

    {{-- ── 4 Kartu Statistik ─────────────────────────────────── --}}
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-8">

        {{-- Total --}}
        <div class="stat-card bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-xl">📝</div>
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Total</span>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Semua laporan masuk</p>
        </div>

        {{-- Menunggu --}}
        <div class="stat-card bg-white rounded-2xl border border-amber-100 shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center text-xl">⏳</div>
                <span class="text-xs font-semibold text-amber-500 uppercase tracking-wide">Menunggu</span>
            </div>
            <p class="text-3xl font-bold text-amber-600">{{ $stats['waiting'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Pending & verified</p>
        </div>

        {{-- In Progress --}}
        <div class="stat-card bg-white rounded-2xl border border-blue-100 shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-xl">🔧</div>
                <span class="text-xs font-semibold text-blue-500 uppercase tracking-wide">Diproses</span>
            </div>
            <p class="text-3xl font-bold text-blue-600">{{ $stats['in_progress'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Sedang ditangani</p>
        </div>

        {{-- Resolved --}}
        <div class="stat-card bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-xl">✅</div>
                <span class="text-xs font-semibold text-green-100 uppercase tracking-wide">Selesai</span>
            </div>
            <p class="text-3xl font-bold text-white">{{ $stats['resolved'] }}</p>
            <p class="text-xs text-green-100 mt-1">Berhasil diselesaikan</p>
        </div>
    </div>

    {{-- ── Grafik ────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">

        {{-- Line Chart: Tren 6 bulan --}}
        <div class="xl:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-sm font-bold text-gray-800">Tren Laporan</h2>
                    <p class="text-xs text-gray-400">6 bulan terakhir</p>
                </div>
                <span class="text-2xl">📈</span>
            </div>
            <div class="relative" style="height: 220px;">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        {{-- Doughnut Chart: Per kategori --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-sm font-bold text-gray-800">Per Kategori</h2>
                    <p class="text-xs text-gray-400">Distribusi laporan</p>
                </div>
                <span class="text-2xl">🥧</span>
            </div>
            <div class="relative" style="height: 180px;">
                <canvas id="categoryChart"></canvas>
            </div>
            <div id="category-legend" class="mt-4 space-y-1.5 text-xs"></div>
        </div>
    </div>

    {{-- ── Tabel Laporan Butuh Tindakan ─────────────────────── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-50">
            <div>
                <h2 class="text-sm font-bold text-gray-800">Butuh Tindakan Segera</h2>
                <p class="text-xs text-gray-400">Laporan verified yang belum ditangani</p>
            </div>
            <a href="{{ route('pemerintah.laporan', ['status' => 'verified']) }}"
               class="text-xs text-sky-600 font-semibold hover:underline">Lihat semua →</a>
        </div>

        @if ($actionNeeded->isEmpty())
            <div class="p-12 text-center">
                <div class="text-5xl mb-2">🎉</div>
                <p class="text-sm font-bold text-gray-600">Tidak ada laporan menunggu tindakan</p>
                <p class="text-xs text-gray-400 mt-1">Semua laporan verified sudah ditangani!</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-xs text-gray-400 uppercase tracking-wide border-b border-gray-50">
                            <th class="text-left px-6 py-3 font-semibold">Laporan</th>
                            <th class="text-left px-4 py-3 font-semibold">Kategori</th>
                            <th class="text-left px-4 py-3 font-semibold">Pelapor</th>
                            <th class="text-left px-4 py-3 font-semibold">Tanggal</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($actionNeeded as $report)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-3.5">
                                    <a href="{{ route('laporan.show', $report) }}" target="_blank"
                                       class="font-semibold text-gray-800 hover:text-sky-600 transition-colors line-clamp-1">
                                        {{ $report->title }}
                                    </a>
                                    <p class="text-xs text-gray-400 mt-0.5 truncate">📍 {{ Str::limit($report->address, 40) }}</p>
                                </td>
                                <td class="px-4 py-3.5">
                                    <span class="inline-flex items-center gap-1 text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">
                                        {{ $report->category->icon ?? '' }} {{ $report->category->name ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-xs text-gray-500">{{ $report->user->name ?? '-' }}</td>
                                <td class="px-4 py-3.5 text-xs text-gray-400">{{ $report->created_at->format('d M Y') }}</td>
                                <td class="px-4 py-3.5">
                                    <form method="POST" action="{{ route('pemerintah.update-status', $report) }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="action" value="in_progress">
                                        <button type="submit"
                                            class="px-3 py-1.5 bg-sky-600 hover:bg-sky-700 text-white text-xs font-semibold rounded-lg transition-colors whitespace-nowrap">
                                            🔧 Tangani
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script>
        // ── Data dari server ──────────────────────────────────
        const trendLabels = @json($trendData['labels']);
        const trendData   = @json($trendData['data']);
        const catLabels   = @json($categoryData['labels']);
        const catData     = @json($categoryData['data']);

        // ── Palet warna ───────────────────────────────────────
        const palette = [
            '#16a34a', '#0ea5e9', '#f59e0b', '#ef4444',
            '#8b5cf6', '#06b6d4', '#ec4899', '#14b8a6'
        ];

        // ── Line Chart: Tren ──────────────────────────────────
        const trendCtx = document.getElementById('trendChart').getContext('2d');
        const gradient = trendCtx.createLinearGradient(0, 0, 0, 220);
        gradient.addColorStop(0, 'rgba(22,163,74,0.25)');
        gradient.addColorStop(1, 'rgba(22,163,74,0)');

        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: trendLabels,
                datasets: [{
                    label: 'Laporan',
                    data: trendData,
                    borderColor: '#16a34a',
                    backgroundColor: gradient,
                    borderWidth: 2.5,
                    pointRadius: 4,
                    pointBackgroundColor: '#16a34a',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    fill: true,
                    tension: 0.4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1f2937',
                        titleColor: '#f9fafb',
                        bodyColor: '#d1fae5',
                        cornerRadius: 8,
                        padding: 10,
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11, family: 'Plus Jakarta Sans' }, color: '#9ca3af' }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: { size: 11, family: 'Plus Jakarta Sans' },
                            color: '#9ca3af'
                        },
                        grid: { color: '#f3f4f6' }
                    }
                }
            }
        });

        // ── Doughnut Chart: Kategori ──────────────────────────
        const catCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(catCtx, {
            type: 'doughnut',
            data: {
                labels: catLabels,
                datasets: [{
                    data: catData,
                    backgroundColor: palette.slice(0, catLabels.length),
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1f2937',
                        titleColor: '#f9fafb',
                        bodyColor: '#d1fae5',
                        cornerRadius: 8,
                        padding: 10,
                    }
                },
                cutout: '65%',
            }
        });

        // Custom legend
        const legendEl = document.getElementById('category-legend');
        const total = catData.reduce((a, b) => a + b, 0);
        catLabels.forEach((label, i) => {
            const pct = total > 0 ? Math.round(catData[i] / total * 100) : 0;
            legendEl.innerHTML += `
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background:${palette[i]}"></span>
                        <span class="text-gray-600 truncate">${label}</span>
                    </div>
                    <span class="text-gray-400 font-semibold ml-2">${pct}%</span>
                </div>`;
        });
    </script>
    @endpush
</x-pemerintah-layout>
