<x-app-layout>
    @section('title', 'Profil ' . $user->name)

    @push('styles')
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .badge-card         { transition: transform 0.2s, box-shadow 0.2s; }
        .badge-card:hover   { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,0.08); }
        .badge-locked       { filter: grayscale(1); opacity: 0.35; }
        .badge-unlocked     { filter: none; }
    </style>
    @endpush

    <div class="py-8" style="font-family:'Plus Jakarta Sans',sans-serif;">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- ── Profile Card ──────────────────────────────────────── --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6 flex items-center gap-5">
                {{-- Avatar --}}
                <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-green-400 to-emerald-600 flex items-center justify-center text-white font-extrabold text-3xl flex-shrink-0 shadow-md">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>

                <div class="flex-1 min-w-0">
                    <h1 class="text-xl font-bold text-gray-900">{{ $user->name }}</h1>
                    <p class="text-xs text-gray-500 mt-0.5">
                        <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 font-semibold px-2.5 py-0.5 rounded-full text-[10px] uppercase tracking-wide">
                            {{ $role }}
                        </span>
                        <span class="ml-2">Bergabung {{ $user->created_at->translatedFormat('F Y') }}</span>
                    </p>

                    {{-- Badges earned --}}
                    @if ($user->badges->isNotEmpty())
                        <div class="flex flex-wrap gap-1.5 mt-2">
                            @foreach ($user->badges as $badge)
                                <span title="{{ $badge->name }}" class="inline-flex items-center gap-1 text-xs bg-amber-50 text-amber-700 border border-amber-200 px-2 py-0.5 rounded-full font-semibold">
                                    {{ $badge->icon }} {{ $badge->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Poin besar --}}
                <div class="text-right flex-shrink-0 hidden sm:block">
                    <p class="text-4xl font-black text-green-600">{{ number_format($stats['total_points']) }}</p>
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Total Poin</p>
                </div>
            </div>

            {{-- ── Stats Grid ────────────────────────────────────────── --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 text-center">
                    <p class="text-2xl font-black text-gray-800">{{ $stats['total_reports'] }}</p>
                    <p class="text-xs text-gray-500 font-medium mt-0.5">Laporan Dibuat</p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 text-center">
                    <p class="text-2xl font-black text-green-600">{{ $stats['resolved_reports'] }}</p>
                    <p class="text-xs text-gray-500 font-medium mt-0.5">Laporan Selesai</p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 text-center">
                    <p class="text-2xl font-black text-amber-600">{{ number_format($stats['total_points']) }}</p>
                    <p class="text-xs text-gray-500 font-medium mt-0.5">Total Poin</p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 text-center">
                    <p class="text-2xl font-black text-purple-600">{{ $stats['badge_count'] }}</p>
                    <p class="text-xs text-gray-500 font-medium mt-0.5">Badge Diraih</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- ── Badge Grid ──────────────────────────────────── --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <h2 class="font-bold text-gray-800 mb-4 text-base">🏅 Koleksi Badge</h2>
                        <div class="space-y-3">
                            @foreach ($allBadges as $badge)
                                @php $owned = $user->badges->contains('id', $badge->id); @endphp
                                <div class="badge-card flex items-center gap-3 p-3 rounded-xl border {{ $owned ? 'border-amber-200 bg-amber-50/60' : 'border-gray-100 bg-gray-50/60' }}">
                                    <span class="text-2xl {{ $owned ? 'badge-unlocked' : 'badge-locked' }}">{{ $badge->icon }}</span>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold {{ $owned ? 'text-amber-800' : 'text-gray-400' }} leading-tight">{{ $badge->name }}</p>
                                        <p class="text-[10px] {{ $owned ? 'text-amber-600' : 'text-gray-300' }} leading-tight mt-0.5 line-clamp-2">{{ $badge->description }}</p>
                                    </div>
                                    @if ($owned)
                                        <span class="text-green-500 flex-shrink-0 text-sm">✓</span>
                                    @else
                                        <span class="text-gray-200 flex-shrink-0 text-sm">🔒</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2 space-y-6">

                    {{-- ── Riwayat Poin ─────────────────────────────── --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <h2 class="font-bold text-gray-800 mb-4 text-base">⭐ Riwayat Poin</h2>

                        @if ($pointHistory->isEmpty())
                            <p class="text-xs text-gray-400 text-center py-4">Belum ada riwayat poin.</p>
                        @else
                            <div class="space-y-2">
                                @foreach ($pointHistory as $log)
                                    <div class="flex items-center justify-between py-2.5 border-b border-gray-50 last:border-0">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-semibold text-gray-700 truncate">{{ $log->reason }}</p>
                                            <p class="text-[10px] text-gray-400">{{ $log->created_at->format('d M Y H:i') }}</p>
                                        </div>
                                        <span class="text-sm font-extrabold text-green-600 ml-3 flex-shrink-0">+{{ $log->points }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- ── Laporan Terbaru ──────────────────────────── --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <h2 class="font-bold text-gray-800 mb-4 text-base">📋 Laporan Terbaru</h2>

                        @if ($recentReports->isEmpty())
                            <p class="text-xs text-gray-400 text-center py-4">Belum ada laporan.</p>
                        @else
                            <div class="space-y-3">
                                @foreach ($recentReports as $report)
                                    @php
                                        $statusColors = [
                                            'pending'     => 'bg-amber-100 text-amber-700',
                                            'verified'    => 'bg-sky-100 text-sky-700',
                                            'in_progress' => 'bg-orange-100 text-orange-700',
                                            'resolved'    => 'bg-green-100 text-green-700',
                                            'rejected'    => 'bg-red-100 text-red-700',
                                        ];
                                        $sc = $statusColors[$report->status] ?? 'bg-gray-100 text-gray-600';
                                    @endphp
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100">
                                            @if ($report->photos->isNotEmpty())
                                                <img src="{{ $report->photos->first()->photo_url }}" alt="{{ $report->title }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-300 text-sm">📷</div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <a href="{{ route('laporan.show', $report) }}" class="text-xs font-semibold text-gray-800 hover:text-green-700 transition-colors line-clamp-1">
                                                {{ $report->title }}
                                            </a>
                                            <p class="text-[10px] text-gray-400 mt-0.5">
                                                {{ $report->category->name ?? '-' }} · {{ $report->created_at->format('d M Y') }}
                                            </p>
                                        </div>
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full flex-shrink-0 {{ $sc }}">
                                            {{ $report->status }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
