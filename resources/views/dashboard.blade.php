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

        .progress-bar { transition: width 1.2s ease-in-out; }
        .point-positive { color: #16a34a; }
        .point-negative { color: #ef4444; }

        /* ── Badge Shine Animation ── */
        .shine-badge {
            position: relative;
            overflow: hidden;
        }
        .shine-badge::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -60%;
            width: 30%;
            height: 200%;
            background: linear-gradient(
                to right,
                rgba(255, 255, 255, 0) 0%,
                rgba(255, 255, 255, 0.4) 50%,
                rgba(255, 255, 255, 0) 100%
            );
            transform: rotate(30deg);
            transition: all 0.6s ease;
        }
        .shine-badge:hover::after {
            left: 120%;
            top: 120%;
        }
    </style>
    @endpush

    @php
        // ── Level Kontributor Calculation ──
        $points = auth()->user()->points;
        if ($points <= 50) {
            $levelName = 'Pendatang Baru 🌱';
            $levelClass = 'from-green-400 to-green-500';
            $nextLevelPoints = 51;
            $prevPoints = 0;
            $maxPoints = 50;
        } elseif ($points <= 150) {
            $levelName = 'Pelapor Aktif 🌿';
            $levelClass = 'from-emerald-400 to-emerald-500';
            $nextLevelPoints = 151;
            $prevPoints = 51;
            $maxPoints = 150;
        } elseif ($points <= 300) {
            $levelName = 'Penjaga Lingkungan 🌳';
            $levelClass = 'from-teal-400 to-teal-500';
            $nextLevelPoints = 301;
            $prevPoints = 151;
            $maxPoints = 300;
        } elseif ($points <= 500) {
            $levelName = 'Pahlawan Hijau 🏆';
            $levelClass = 'from-amber-400 to-amber-500';
            $nextLevelPoints = 501;
            $prevPoints = 301;
            $maxPoints = 500;
        } else {
            $levelName = 'Legenda Hijau 👑';
            $levelClass = 'from-purple-500 to-indigo-600';
            $nextLevelPoints = null;
            $prevPoints = 501;
            $maxPoints = $points;
        }
        
        if ($nextLevelPoints) {
            $progressPercent = (($points - $prevPoints) / ($maxPoints - $prevPoints)) * 100;
            $pointsNeeded = $nextLevelPoints - $points;
        } else {
            $progressPercent = 100;
            $pointsNeeded = 0;
        }
    @endphp

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- ── Welcome Header ── --}}
            <div class="bg-gradient-to-r from-green-600 to-emerald-500 rounded-2xl p-6 text-white shadow-md">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center text-2xl font-black shadow-inner">
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

            {{-- ── Level Kontributor Progress Bar (Fitur 5) ── --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 shadow-sm space-y-3">
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <div>
                        <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wide">Level Kontributor</p>
                        <h3 class="text-base font-extrabold text-gray-800 dark:text-white mt-0.5">{{ $levelName }}</h3>
                    </div>
                    <span class="text-xs font-bold text-green-600 bg-green-50 dark:bg-green-950/40 dark:text-green-400 px-3 py-1 rounded-full">
                        {{ $points }} Poin
                    </span>
                </div>
                <div class="relative w-full h-3.5 bg-gray-100 dark:bg-gray-900 rounded-full overflow-hidden">
                    <div class="progress-bar absolute left-0 top-0 h-full rounded-full bg-gradient-to-r {{ $levelClass }}"
                         style="width: {{ $progressPercent }}%"></div>
                </div>
                <div class="flex justify-between items-center text-[10px] text-gray-400 dark:text-gray-500">
                    <span>Awal Level</span>
                    @if($nextLevelPoints)
                        <span class="font-semibold">{{ $pointsNeeded }} poin lagi untuk naik ke level berikutnya</span>
                        <span>Level Berikutnya</span>
                    @else
                        <span class="font-semibold">Level Maksimum Tercapai! 🎉</span>
                    @endif
                </div>
            </div>

            {{-- ── Statistik Laporan ── --}}
            <div>
                <h2 class="text-sm font-bold text-gray-800 dark:text-white mb-3">📊 Statistik Laporan Saya</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                    @foreach([
                        ['Total',       $reportStats['total'],       '#6b7280', 'bg-gray-50 dark:bg-gray-800', '📋'],
                        ['Pending',     $reportStats['pending'],      '#f59e0b', 'bg-amber-50 dark:bg-amber-950/20', '⏳'],
                        ['Verified',    $reportStats['verified'],     '#0ea5e9', 'bg-sky-50 dark:bg-sky-950/20', '✅'],
                        ['Ditangani',   $reportStats['in_progress'],  '#f97316', 'bg-orange-50 dark:bg-orange-950/20', '🔧'],
                        ['Selesai',     $reportStats['resolved'],     '#16a34a', 'bg-green-50 dark:bg-green-950/20', '🎉'],
                        ['Ditolak',     $reportStats['rejected'],     '#ef4444', 'bg-red-50 dark:bg-red-950/20', '❌'],
                    ] as $s)
                        <div class="stat-card card-anim rounded-2xl border border-gray-100 dark:border-gray-700/80 p-4 text-center {{ $s[3] }}">
                            <p class="text-lg mb-0.5">{{ $s[4] }}</p>
                            <p class="text-2xl font-black" style="color: {{ $s[2] }}">{{ $s[1] }}</p>
                            <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 mt-1 uppercase tracking-wider">{{ $s[0] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- ── Laporan Terbaru (Maks 3 Laporan) ── --}}
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 shadow-sm space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700">
                            <h2 class="text-sm font-bold text-gray-800 dark:text-white flex items-center gap-1.5">
                                <span>📋</span> Laporan Terbaru
                            </h2>
                            <a href="{{ route('masyarakat.laporan') }}" class="text-xs font-bold text-green-600 hover:text-green-700">Lihat Semua →</a>
                        </div>

                        @if ($myReports->isEmpty())
                            <div class="py-6">
                                <x-empty-state icon="📭" title="Belum ada laporan" message="Kamu belum membuat laporan apapun." />
                                <div class="mt-4 text-center">
                                    <a href="{{ route('laporan.create') }}"
                                       class="inline-flex items-center gap-1.5 px-4 py-2 bg-green-600 text-white text-xs font-bold rounded-xl hover:bg-green-700 transition-all">
                                        + Buat Laporan Pertama
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="space-y-3">
                                @foreach ($myReports->take(3) as $report)
                                    @php
                                        $statusConfig = [
                                            'pending'     => ['bg-amber-100 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400',  '⏳ Pending'],
                                            'verified'    => ['bg-sky-100 text-sky-700 dark:bg-sky-950/40 dark:text-sky-400',       '✅ Verified'],
                                            'in_progress' => ['bg-orange-100 text-orange-700 dark:bg-orange-950/40 dark:text-orange-400', '🔧 Ditangani'],
                                            'resolved'    => ['bg-green-100 text-green-700 dark:bg-green-950/40 dark:text-green-400',   '🎉 Selesai'],
                                            'rejected'    => ['bg-red-100 text-red-700 dark:bg-red-950/40 dark:text-red-400',       '❌ Ditolak'],
                                        ];
                                        [$sc, $sl] = $statusConfig[$report->status] ?? ['bg-gray-100 text-gray-600', $report->status];
                                    @endphp
                                    <a href="{{ route('laporan.show', $report) }}"
                                       class="flex items-center gap-3 p-3 hover:bg-gray-50 dark:hover:bg-gray-900/50 rounded-xl transition-colors border border-gray-100/50 dark:border-gray-700/50">
                                        <div class="w-10 h-10 rounded-xl bg-green-50 dark:bg-green-950/40 flex items-center justify-center text-lg flex-shrink-0">
                                            {{ $report->category->icon ?? '📋' }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $report->title }}</p>
                                            <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1">📅 {{ $report->created_at->format('d M Y') }} · 📍 {{ Str::limit($report->address, 35) }}</p>
                                        </div>
                                        <span class="text-[10px] font-bold px-2.5 py-1 rounded-full {{ $sc }} flex-shrink-0">{{ $sl }}</span>
                                    </a>
                                @endforeach
                            </div>

                            <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700">
                                <a href="{{ route('laporan.create') }}"
                                   class="flex items-center justify-center gap-2 w-full py-3 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                    Buat Laporan Baru
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ── Sidebar (Kanan) ── --}}
                <div class="space-y-5">

                    {{-- Badge Saya (Grid + Shine Effect) --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 shadow-sm space-y-4">
                        <h2 class="text-sm font-bold text-gray-800 dark:text-white pb-2 border-b border-gray-100 dark:border-gray-700">🏅 Badge Saya</h2>
                        @if ($myBadges->isEmpty())
                            <div class="text-center py-4">
                                <p class="text-2xl mb-1">🎯</p>
                                <p class="text-xs text-gray-400">Laporkan isu lingkungan untuk raih badge!</p>
                            </div>
                        @else
                            <div class="grid grid-cols-2 gap-2">
                                @foreach ($myBadges as $ub)
                                    <div class="shine-badge flex flex-col items-center justify-center gap-1.5 bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900 rounded-2xl p-3 text-center"
                                         title="Diperoleh {{ $ub->earned_at->format('d M Y') }}">
                                        <span class="text-3xl">{{ $ub->badge->icon ?? '🏅' }}</span>
                                        <span class="text-[9px] font-black text-amber-800 dark:text-amber-400 leading-tight uppercase">{{ $ub->badge->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Riwayat Poin --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 shadow-sm space-y-4">
                        <div class="flex items-center justify-between pb-2 border-b border-gray-100 dark:border-gray-700">
                            <h2 class="text-sm font-bold text-gray-800 dark:text-white">⭐ Riwayat Poin</h2>
                            <a href="{{ route('profil', auth()->user()) }}" class="text-xs text-green-600 font-bold">Semua →</a>
                        </div>
                        @if ($pointHistory->isEmpty())
                            <p class="text-xs text-gray-400 text-center py-4">Belum ada riwayat poin</p>
                        @else
                            <div class="space-y-3">
                                @foreach ($pointHistory as $log)
                                    <div class="flex items-center justify-between">
                                        <div class="min-w-0 flex-1">
                                            <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 truncate">{{ $log->reason }}</p>
                                            <p class="text-[8px] text-gray-400 mt-0.5">{{ $log->created_at->format('d M Y H:i') }}</p>
                                        </div>
                                        <span class="text-xs font-black ml-3 flex-shrink-0 {{ $log->points > 0 ? 'point-positive' : 'point-negative' }}">
                                            {{ $log->points > 0 ? '+' : '' }}{{ $log->points }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Event RSVP --}}
                    @if ($myEvents->isNotEmpty())
                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 shadow-sm space-y-4">
                            <div class="flex items-center justify-between pb-2 border-b border-gray-100 dark:border-gray-700">
                                <h2 class="text-sm font-bold text-gray-800 dark:text-white">🌿 Event Saya</h2>
                                <a href="{{ route('event.index') }}" class="text-xs text-green-600 font-bold">Semua →</a>
                            </div>
                            <div class="space-y-3">
                                @foreach ($myEvents as $p)
                                    <a href="{{ route('event.show', $p->event) }}"
                                       class="block hover:bg-gray-50 dark:hover:bg-gray-900 p-2.5 rounded-xl border border-gray-100 dark:border-gray-700/60 transition-all">
                                        <p class="text-xs font-bold text-gray-800 dark:text-white truncate">{{ $p->event->title }}</p>
                                        <p class="text-[9px] text-gray-400 dark:text-gray-500 mt-1">📅 {{ $p->event->event_date->translatedFormat('d M Y, H:i') }}</p>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
            </div>

            {{-- ── Artikel Lingkungan Terbaru ── --}}
            @if ($latestArticles->isNotEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-2 border-b border-gray-100 dark:border-gray-700">
                        <h2 class="text-sm font-bold text-gray-800 dark:text-white flex items-center gap-1.5">
                            <span>📚</span> Artikel Lingkungan Terbaru
                        </h2>
                        <a href="{{ route('artikel.index') }}" class="text-xs font-bold text-green-600">Semua Artikel →</a>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        @foreach ($latestArticles as $article)
                            <a href="{{ route('artikel.show', $article->slug) }}"
                               class="flex items-start gap-3 hover:bg-gray-50 dark:hover:bg-gray-900 rounded-xl p-3 border border-gray-100/50 dark:border-gray-700 transition-colors">
                                <div class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-950/40 flex items-center justify-center text-lg flex-shrink-0">📄</div>
                                <div>
                                    <p class="text-xs font-bold text-gray-800 dark:text-white line-clamp-2 leading-snug">{{ $article->title }}</p>
                                    <p class="text-[10px] text-gray-400 mt-1.5">{{ $article->category }} · {{ $article->reading_time }} mnt</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
