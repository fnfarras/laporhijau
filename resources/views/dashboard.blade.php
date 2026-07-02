<x-app-layout>
    @section('title', 'Dashboard Saya')
    @section('meta_description', 'Dashboard pribadi LaporHijau — pantau laporan, poin, badge, dan event komunitas.')

    @push('styles')
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        @keyframes slideInUp { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }
        .card-anim { animation: slideInUp 0.4s ease forwards; }
        .card-anim:nth-child(1) { animation-delay:.05s; }
        .card-anim:nth-child(2) { animation-delay:.10s; }
        .card-anim:nth-child(3) { animation-delay:.15s; }
        .card-anim:nth-child(4) { animation-delay:.20s; }

        .stat-card { transition: transform .2s, box-shadow .2s; }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 12px 28px rgba(0,0,0,.08); }

        .progress-bar { transition: width 1s ease; }
        .point-positive { color: #16a34a; }
        .point-negative { color: #ef4444; }
    </style>
    @endpush

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- ── Welcome Header ──────────────────────────────────────── --}}
            <div class="bg-gradient-to-r from-green-600 to-emerald-500 rounded-2xl p-6 text-white shadow-md">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center text-2xl font-black">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-white/80 text-xs font-medium">Selamat datang kembali! 👋</p>
                            <h1 class="text-xl font-extrabold">{{ auth()->user()->name }}</h1>
                            <p class="text-white/70 text-xs mt-0.5">
                                Bergabung sejak {{ auth()->user()->created_at->translatedFormat('F Y') }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="bg-white/20 backdrop-blur rounded-xl px-5 py-3 text-center">
                            <p class="text-2xl font-black">{{ number_format(auth()->user()->fresh()->points) }}</p>
                            <p class="text-xs text-white/80 font-medium">⭐ Total Poin</p>
                        </div>
                        <div class="bg-white/20 backdrop-blur rounded-xl px-5 py-3 text-center">
                            <p class="text-2xl font-black">{{ $myBadges->count() }}</p>
                            <p class="text-xs text-white/80 font-medium">🏅 Badge</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Statistik Laporan ────────────────────────────────────── --}}
            <div>
                <h2 class="text-base font-bold text-gray-800 mb-3">📊 Laporan Saya</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                    @foreach([
                        ['Total',       $reportStats['total'],       '#6b7280', '#f9fafb', '📋'],
                        ['Pending',     $reportStats['pending'],      '#f59e0b', '#fffbeb', '⏳'],
                        ['Verified',    $reportStats['verified'],     '#0ea5e9', '#eff6ff', '✅'],
                        ['Ditangani',   $reportStats['in_progress'],  '#f97316', '#fff7ed', '🔧'],
                        ['Selesai',     $reportStats['resolved'],     '#16a34a', '#f0fdf4', '🎉'],
                        ['Ditolak',     $reportStats['rejected'],     '#ef4444', '#fef2f2', '❌'],
                    ] as $s)
                        <div class="stat-card card-anim rounded-2xl border border-gray-100 shadow-sm p-4 text-center"
                             style="background: {{ $s[3] }}">
                            <p class="text-lg mb-0.5">{{ $s[4] }}</p>
                            <p class="text-2xl font-black" style="color: {{ $s[2] }}">{{ $s[1] }}</p>
                            <p class="text-[10px] font-semibold text-gray-500 mt-0.5">{{ $s[0] }}</p>
                        </div>
                    @endforeach
                </div>

                {{-- Progress bar resolved --}}
                @if ($reportStats['total'] > 0)
                    @php $pct = round($reportStats['resolved'] / $reportStats['total'] * 100); @endphp
                    <div class="mt-3 bg-white rounded-xl border border-gray-100 shadow-sm p-3">
                        <div class="flex justify-between text-xs text-gray-500 mb-1.5">
                            <span class="font-semibold">Tingkat penyelesaian laporan</span>
                            <span class="font-bold text-green-600">{{ $pct }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="progress-bar bg-green-500 h-2 rounded-full" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- ── Laporan Terbaru ──────────────────────────────────── --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-sm font-bold text-gray-800">📋 Laporan Terbaru</h2>
                            <a href="{{ route('masyarakat.laporan') }}" class="text-xs font-bold text-green-600 hover:text-green-700">Lihat Semua →</a>
                        </div>

                        @if ($myReports->isEmpty())
                            <div class="text-center py-10">
                                <p class="text-3xl mb-2">📭</p>
                                <p class="text-sm text-gray-500">Belum ada laporan.</p>
                                <a href="{{ route('laporan.create') }}"
                                   class="mt-3 inline-flex items-center gap-1.5 px-4 py-2 bg-green-600 text-white text-xs font-bold rounded-xl hover:bg-green-700 transition-all">
                                    + Buat Laporan Pertama
                                </a>
                            </div>
                        @else
                            <div class="space-y-2.5">
                                @foreach ($myReports->take(5) as $report)
                                    @php
                                        $statusConfig = [
                                            'pending'     => ['bg-amber-100 text-amber-700',  '⏳ Pending'],
                                            'verified'    => ['bg-sky-100 text-sky-700',       '✅ Verified'],
                                            'in_progress' => ['bg-orange-100 text-orange-700', '🔧 Ditangani'],
                                            'resolved'    => ['bg-green-100 text-green-700',   '🎉 Selesai'],
                                            'rejected'    => ['bg-red-100 text-red-700',       '❌ Ditolak'],
                                        ];
                                        [$sc, $sl] = $statusConfig[$report->status] ?? ['bg-gray-100 text-gray-600', $report->status];
                                    @endphp
                                    <a href="{{ route('laporan.show', $report) }}"
                                       class="flex items-center gap-3 p-3 hover:bg-gray-50 rounded-xl transition-colors">
                                        <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center text-lg flex-shrink-0">
                                            {{ $report->category->icon ?? '📋' }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $report->title }}</p>
                                            <p class="text-[10px] text-gray-400 mt-0.5">{{ $report->created_at->format('d M Y') }}</p>
                                        </div>
                                        <span class="text-[10px] font-bold px-2 py-1 rounded-full {{ $sc }} flex-shrink-0">{{ $sl }}</span>
                                    </a>
                                @endforeach
                            </div>

                            <div class="mt-4 pt-3 border-t border-gray-50">
                                <a href="{{ route('laporan.create') }}"
                                   class="flex items-center justify-center gap-2 w-full py-2.5 bg-green-50 hover:bg-green-100 text-green-700 text-xs font-bold rounded-xl transition-all border border-green-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                    Buat Laporan Baru
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ── Sidebar ──────────────────────────────────────────── --}}
                <div class="space-y-5">

                    {{-- Riwayat Poin --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="text-sm font-bold text-gray-800">⭐ Riwayat Poin</h2>
                            <a href="{{ route('profil', auth()->user()) }}" class="text-xs text-green-600 font-bold">Profil →</a>
                        </div>
                        @if ($pointHistory->isEmpty())
                            <p class="text-xs text-gray-400 text-center py-4">Belum ada riwayat poin</p>
                        @else
                            <div class="space-y-2">
                                @foreach ($pointHistory as $log)
                                    <div class="flex items-center justify-between">
                                        <p class="text-xs text-gray-600 flex-1 truncate">{{ $log->reason }}</p>
                                        <span class="text-xs font-bold ml-2 flex-shrink-0 {{ $log->points > 0 ? 'point-positive' : 'point-negative' }}">
                                            {{ $log->points > 0 ? '+' : '' }}{{ $log->points }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Badge --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <h2 class="text-sm font-bold text-gray-800 mb-3">🏅 Badge Saya</h2>
                        @if ($myBadges->isEmpty())
                            <div class="text-center py-4">
                                <p class="text-2xl mb-1">🎯</p>
                                <p class="text-xs text-gray-400">Mulai lapor untuk dapatkan badge!</p>
                            </div>
                        @else
                            <div class="flex flex-wrap gap-2">
                                @foreach ($myBadges as $ub)
                                    <div class="flex items-center gap-1.5 bg-amber-50 border border-amber-200 rounded-xl px-2.5 py-1.5"
                                         title="Diperoleh {{ $ub->earned_at->format('d M Y') }}">
                                        <span class="text-base">{{ $ub->badge->icon ?? '🏅' }}</span>
                                        <span class="text-[10px] font-bold text-amber-700">{{ $ub->badge->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Event RSVP --}}
                    @if ($myEvents->isNotEmpty())
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                            <div class="flex items-center justify-between mb-3">
                                <h2 class="text-sm font-bold text-gray-800">🌿 Event Saya</h2>
                                <a href="{{ route('event.index') }}" class="text-xs text-green-600 font-bold">Semua →</a>
                            </div>
                            <div class="space-y-2.5">
                                @foreach ($myEvents as $p)
                                    <a href="{{ route('event.show', $p->event) }}"
                                       class="block hover:bg-gray-50 p-2 rounded-xl transition-colors">
                                        <p class="text-xs font-semibold text-gray-800 truncate">{{ $p->event->title }}</p>
                                        <p class="text-[10px] text-gray-400 mt-0.5">📅 {{ $p->event->event_date->translatedFormat('d M Y, H:i') }}</p>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Quick Actions --}}
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-2xl p-5">
                        <h2 class="text-sm font-bold text-green-800 mb-3">⚡ Aksi Cepat</h2>
                        <div class="space-y-2">
                            <a href="{{ route('laporan.create') }}"
                               class="flex items-center gap-2 text-xs font-semibold text-green-700 hover:text-green-900 transition-colors">
                                <span class="w-6 h-6 bg-green-600 text-white rounded-lg flex items-center justify-center text-[10px] font-bold">+</span>
                                Buat Laporan Baru
                            </a>
                            <a href="{{ route('event.index') }}"
                               class="flex items-center gap-2 text-xs font-semibold text-green-700 hover:text-green-900 transition-colors">
                                <span class="w-6 h-6 bg-emerald-500 text-white rounded-lg flex items-center justify-center text-[10px]">🌿</span>
                                Cari Event Komunitas
                            </a>
                            <a href="{{ route('leaderboard') }}"
                               class="flex items-center gap-2 text-xs font-semibold text-green-700 hover:text-green-900 transition-colors">
                                <span class="w-6 h-6 bg-amber-500 text-white rounded-lg flex items-center justify-center text-[10px]">🏆</span>
                                Lihat Leaderboard
                            </a>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ── Artikel Terbaru ──────────────────────────────────────── --}}
            @if ($latestArticles->isNotEmpty())
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-sm font-bold text-gray-800">📚 Artikel Lingkungan Terbaru</h2>
                        <a href="{{ route('artikel.index') }}" class="text-xs font-bold text-green-600">Semua Artikel →</a>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        @foreach ($latestArticles as $article)
                            <a href="{{ route('artikel.show', $article->slug) }}"
                               class="flex items-start gap-3 hover:bg-gray-50 rounded-xl p-2 -m-2 transition-colors">
                                <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center text-lg flex-shrink-0">📄</div>
                                <div>
                                    <p class="text-xs font-bold text-gray-800 line-clamp-2 leading-snug">{{ $article->title }}</p>
                                    <p class="text-[10px] text-gray-400 mt-1">{{ $article->category }} · {{ $article->reading_time }} mnt</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
