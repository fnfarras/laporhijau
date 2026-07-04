<x-relawan-layout>
    @section('title', 'Dashboard Relawan')

    {{-- ── Page Header ─────────────────────────────────────────── --}}
    <div class="pt-6 pb-4">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard Relawan</h1>
        <p class="text-sm text-gray-500 mt-0.5">Verifikasi laporan masalah lingkungan dari masyarakat</p>
    </div>

    {{-- ── Stats Bar ─────────────────────────────────────────────── --}}
    <div class="grid grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-2xl">✅</div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['verified'] }}</p>
                <p class="text-xs text-gray-500 font-medium">Diverifikasi</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center text-2xl">❌</div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['rejected'] }}</p>
                <p class="text-xs text-gray-500 font-medium">Ditolak</p>
            </div>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center text-2xl">⭐</div>
            <div>
                <p class="text-2xl font-bold text-white">{{ $stats['points'] }}</p>
                <p class="text-xs text-green-100 font-medium">Poin Saya</p>
            </div>
        </div>
    </div>

    {{-- ── Antrian Laporan Pending ───────────────────────────────── --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-base font-bold text-gray-800">Antrian Verifikasi</h2>
            <p class="text-xs text-gray-400">{{ $pending->total() }} laporan menunggu</p>
        </div>
        @if ($pending->total() > 9)
            <a href="{{ route('relawan.antrian') }}" class="text-xs text-green-600 font-semibold hover:underline">
                Lihat semua →
            </a>
        @endif
    </div>

    @if ($pending->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 p-16 text-center">
            <div class="text-6xl mb-3">🎉</div>
            <h3 class="text-base font-bold text-gray-700 mb-1">Antrian kosong!</h3>
            <p class="text-sm text-gray-400">Semua laporan sudah ditangani. Kerja bagus!</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5 mb-8">
            @foreach ($pending as $report)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow">

                    {{-- Foto --}}
                    <div class="relative h-40 bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden">
                        @if ($report->photos->isNotEmpty())
                            <img src="{{ $report->photos->first()->photo_url }}"
                                 alt="{{ $report->title }}"
                                 class="w-full h-full object-cover"
                                 loading="lazy">
                        @else
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-gray-300">
                                <svg class="w-10 h-10 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-xs">Tanpa foto</span>
                            </div>
                        @endif
                        <span class="absolute top-2 left-2 bg-amber-100 text-amber-700 border border-amber-200 text-xs font-semibold px-2.5 py-1 rounded-full">
                            ⏳ Pending
                        </span>
                    </div>

                    {{-- Info --}}
                    <div class="p-4">
                        <div class="flex items-center gap-1 mb-1">
                            <span class="text-sm">{{ $report->category->icon ?? '📋' }}</span>
                            <span class="text-xs text-gray-400 font-medium">{{ $report->category->name ?? '-' }}</span>
                        </div>
                        <h4 class="font-bold text-gray-800 text-sm leading-snug mb-1 line-clamp-2">{{ $report->title }}</h4>
                        <p class="text-xs text-gray-400 mb-1 truncate">📍 {{ $report->address }}</p>
                        <p class="text-xs text-gray-400 mb-1">👤 {{ $report->user->name }}</p>
                        <p class="text-xs text-gray-300 mb-4">{{ $report->created_at->diffForHumans() }}</p>

                        {{-- Actions --}}
                        <div class="flex gap-2">
                            {{-- Verifikasi --}}
                            <button
                                type="button"
                                onclick="openVerifyModal({{ $report->id }}, '{{ addslashes($report->title) }}', '{{ route('relawan.verify', $report) }}')"
                                class="flex-1 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-lg transition-colors flex items-center justify-center gap-1"
                            >
                                ✅ Verifikasi
                            </button>

                            {{-- Tolak --}}
                            <button
                                type="button"
                                onclick="openRejectModal({{ $report->id }}, '{{ addslashes($report->title) }}', '{{ route('relawan.reject', $report) }}')"
                                class="flex-1 py-2 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold rounded-lg border border-red-200 transition-colors flex items-center justify-center gap-1"
                            >
                                ❌ Tolak
                            </button>

                            {{-- Detail --}}
                            <a href="{{ route('laporan.show', $report) }}"
                               target="_blank"
                               class="py-2 px-3 bg-gray-50 hover:bg-gray-100 text-gray-500 text-xs font-semibold rounded-lg border border-gray-200 transition-colors flex items-center"
                               title="Lihat detail">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if ($pending->hasPages())
            {{ $pending->links() }}
        @endif
    @endif

    {{-- ── Riwayat Singkat ───────────────────────────────────────── --}}
    @if ($recentHistory->isNotEmpty())
        <div class="mt-4">
            <h2 class="text-base font-bold text-gray-800 mb-4">Riwayat Terakhirku</h2>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="divide-y divide-gray-50">
                    @foreach ($recentHistory as $log)
                        @php
                            $isVerified = $log->new_status === 'verified';
                        @endphp
                        <div class="flex items-center gap-3 px-5 py-3.5">
                            <span class="text-lg">{{ $isVerified ? '✅' : '❌' }}</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-800 truncate">
                                    {{ $log->report->title ?? 'Laporan dihapus' }}
                                </p>
                                <p class="text-xs text-gray-400">
                                    {{ $log->report->category->name ?? '-' }} ·
                                    {{ $log->created_at->format('d M Y H:i') }}
                                </p>
                            </div>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $isVerified ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $isVerified ? 'Verified' : 'Rejected' }}
                            </span>
                        </div>
                    @endforeach
                </div>
                <div class="px-5 py-3 bg-gray-50 border-t border-gray-100">
                    <a href="{{ route('relawan.riwayat') }}" class="text-xs text-green-600 font-semibold hover:underline">Lihat semua riwayat →</a>
                </div>
            </div>
        </div>
    @endif
</x-relawan-layout>
