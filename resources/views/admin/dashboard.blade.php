<x-admin-layout>
    @section('title', 'Dashboard Admin')

    @push('styles')
    <style>
        .stat-card { transition: transform 0.2s, box-shadow 0.2s; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.08); }
    </style>
    @endpush

    <div class="pt-6 pb-4">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard Admin</h1>
        <p class="text-sm text-gray-500 mt-0.5">Pantau seluruh aktivitas platform LaporHijau</p>
    </div>

    {{-- ── Statistik Pengguna ─────────────────────────────────── --}}
    <div class="mb-6">
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Pengguna</h2>
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">

            <div class="stat-card bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-xl">👥</div>
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Total</span>
                </div>
                <p class="text-3xl font-bold text-gray-900">{{ $userStats['total'] }}</p>
                <p class="text-xs text-gray-500 mt-1">Semua pengguna terdaftar</p>
            </div>

            <div class="stat-card bg-white rounded-2xl border border-green-100 shadow-sm p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center text-xl">🌱</div>
                    <span class="text-xs font-semibold text-green-500 uppercase tracking-wide">Masyarakat</span>
                </div>
                <p class="text-3xl font-bold text-green-700">{{ $userStats['masyarakat'] }}</p>
                <p class="text-xs text-gray-500 mt-1">Pelapor aktif</p>
            </div>

            <div class="stat-card bg-white rounded-2xl border border-sky-100 shadow-sm p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-sky-100 rounded-xl flex items-center justify-center text-xl">🙋</div>
                    <span class="text-xs font-semibold text-sky-500 uppercase tracking-wide">Relawan</span>
                </div>
                <p class="text-3xl font-bold text-sky-700">{{ $userStats['relawan'] }}</p>
                <p class="text-xs text-gray-500 mt-1">Verifikator laporan</p>
            </div>

            <div class="stat-card bg-white rounded-2xl border border-purple-100 shadow-sm p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center text-xl">🏛️</div>
                    <span class="text-xs font-semibold text-purple-500 uppercase tracking-wide">Pemerintah</span>
                </div>
                <p class="text-3xl font-bold text-purple-700">{{ $userStats['pemerintah'] }}</p>
                <p class="text-xs text-gray-500 mt-1">Penanganan laporan</p>
            </div>
        </div>
    </div>

    {{-- ── Statistik Laporan ──────────────────────────────────── --}}
    <div class="mb-6">
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Laporan</h2>
        <div class="grid grid-cols-3 xl:grid-cols-6 gap-3">
            @php
            $reportCards = [
                ['label' => 'Total',       'value' => $reportStats['total'],       'color' => 'gray',   'icon' => '📝'],
                ['label' => 'Pending',     'value' => $reportStats['pending'],     'color' => 'amber',  'icon' => '⏳'],
                ['label' => 'Terverifikasi','value' => $reportStats['verified'],   'color' => 'sky',    'icon' => '✅'],
                ['label' => 'Diproses',    'value' => $reportStats['in_progress'], 'color' => 'blue',   'icon' => '🔧'],
                ['label' => 'Selesai',     'value' => $reportStats['resolved'],    'color' => 'green',  'icon' => '🎉'],
                ['label' => 'Ditolak',     'value' => $reportStats['rejected'],    'color' => 'red',    'icon' => '❌'],
            ];
            @endphp
            @foreach($reportCards as $card)
            <div class="stat-card bg-white rounded-xl border border-{{ $card['color'] }}-100 shadow-sm p-4 text-center">
                <span class="text-2xl block mb-1">{{ $card['icon'] }}</span>
                <p class="text-2xl font-bold text-gray-900">{{ $card['value'] }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ $card['label'] }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ── 2 Kolom: Laporan Terbaru + Top Users ──────────────── --}}
    <div class="grid xl:grid-cols-3 gap-6">

        {{-- Laporan Terbaru --}}
        <div class="xl:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="text-base font-bold text-gray-900 mb-4">10 Laporan Terbaru</h2>
            <div class="space-y-3">
                @forelse($latestReports as $report)
                <div class="flex items-start gap-3 py-2.5 border-b border-gray-50 last:border-0">
                    <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-sm flex-shrink-0">
                        {{ $report->category?->icon ?? '📍' }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('laporan.show', $report) }}"
                           class="text-sm font-medium text-gray-800 hover:text-green-600 truncate block">
                            {{ $report->title }}
                        </a>
                        <p class="text-xs text-gray-400 mt-0.5">
                            {{ $report->user?->name ?? 'Anonim' }} · {{ $report->created_at->diffForHumans() }}
                        </p>
                    </div>
                    @php
                    $statusColor = match($report->status) {
                        'pending'     => 'amber',
                        'verified'    => 'sky',
                        'in_progress' => 'blue',
                        'resolved'    => 'green',
                        'rejected'    => 'red',
                        default       => 'gray',
                    };
                    @endphp
                    <span class="text-xs px-2 py-0.5 rounded-full bg-{{ $statusColor }}-100 text-{{ $statusColor }}-700 font-medium flex-shrink-0">
                        {{ $report->getStatusLabel() }}
                    </span>
                </div>
                @empty
                <p class="text-sm text-gray-400 text-center py-6">Belum ada laporan masuk.</p>
                @endforelse
            </div>
        </div>

        {{-- Top 5 Users --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="text-base font-bold text-gray-900 mb-4">🏆 Top Pengguna</h2>
            <div class="space-y-3">
                @foreach($topUsers as $index => $topUser)
                <div class="flex items-center gap-3">
                    <span class="text-lg font-black {{ $index === 0 ? 'text-yellow-500' : ($index === 1 ? 'text-slate-400' : ($index === 2 ? 'text-amber-600' : 'text-gray-400')) }}">
                        #{{ $index + 1 }}
                    </span>
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center text-green-700 font-bold text-sm">
                        {{ strtoupper(substr($topUser->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $topUser->name }}</p>
                    </div>
                    <span class="text-sm font-bold text-green-600">{{ number_format($topUser->points) }} <span class="text-xs font-normal text-gray-400">pts</span></span>
                </div>
                @endforeach
            </div>

            {{-- Konten Stats --}}
            <div class="mt-6 pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide mb-3">Konten Platform</p>
                <div class="flex gap-4">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-amber-600">{{ $contentStats['events'] }}</p>
                        <p class="text-xs text-gray-500">Event</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-sky-600">{{ $contentStats['articles'] }}</p>
                        <p class="text-xs text-gray-500">Artikel</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-admin-layout>
