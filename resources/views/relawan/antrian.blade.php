<x-relawan-layout>
    @section('title', 'Antrian Laporan')

    <div class="pt-6 pb-4 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Antrian Laporan</h1>
            <p class="text-sm text-gray-500 mt-0.5">{{ $pending->total() }} laporan pending menunggu verifikasi</p>
        </div>
    </div>

    {{-- ── Filter Kategori ─────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
        <form method="GET" action="{{ route('relawan.antrian') }}" class="flex flex-wrap items-center gap-2">
            <span class="text-sm font-semibold text-gray-600">Filter:</span>
            <a href="{{ route('relawan.antrian') }}"
               class="px-3 py-1.5 rounded-full text-xs font-semibold transition-colors {{ !$categoryId ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Semua
            </a>
            @foreach ($categories as $cat)
                <a href="{{ route('relawan.antrian', ['category' => $cat->id]) }}"
                   class="px-3 py-1.5 rounded-full text-xs font-semibold transition-colors {{ $categoryId == $cat->id ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    {{ $cat->icon }} {{ $cat->name }}
                </a>
            @endforeach
        </form>
    </div>

    {{-- ── List Laporan ─────────────────────────────────────────── --}}
    @if ($pending->isEmpty())
        <div class="p-6">
            <x-empty-state icon="🎉" title="Tidak ada laporan pending" message="Semua laporan sudah ditangani! Kerja bagus." />
        </div>
    @else
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">
            <div class="divide-y divide-gray-50">
                @foreach ($pending as $report)
                    <div class="flex items-center gap-4 px-5 py-4 hover:bg-gray-50 transition-colors">

                        {{-- Thumbnail --}}
                        <div class="w-14 h-14 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100">
                            @if ($report->photos->isNotEmpty())
                                <img src="{{ $report->photos->first()->photo_url }}"
                                     alt="{{ $report->title }}"
                                     class="w-full h-full object-cover"
                                     loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300 text-xl">📷</div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-0.5">
                                <span class="text-xs">{{ $report->category->icon ?? '📋' }}</span>
                                <span class="text-xs text-gray-400">{{ $report->category->name ?? '-' }}</span>
                            </div>
                            <a href="{{ route('laporan.show', $report) }}" target="_blank"
                               class="text-sm font-semibold text-gray-800 hover:text-green-700 transition-colors line-clamp-1">
                                {{ $report->title }}
                            </a>
                            <p class="text-xs text-gray-400 mt-0.5">
                                📍 {{ Str::limit($report->address, 50) }} ·
                                👤 {{ $report->is_anonymous ? '🔒 ' . $report->reporter_name . ' (Anonim)' : $report->reporter_name }} ·
                                {{ $report->created_at->diffForHumans() }}
                            </p>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <button
                                type="button"
                                onclick="openVerifyModal({{ $report->id }}, '{{ addslashes($report->title) }}', '{{ route('relawan.verify', $report) }}')"
                                class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-lg transition-colors"
                            >
                                ✅ Verifikasi
                            </button>
                            <button
                                type="button"
                                onclick="openRejectModal({{ $report->id }}, '{{ addslashes($report->title) }}', '{{ route('relawan.reject', $report) }}')"
                                class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold rounded-lg border border-red-200 transition-colors"
                            >
                                ❌ Tolak
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Pagination --}}
        @if ($pending->hasPages())
            {{ $pending->links() }}
        @endif
    @endif
</x-relawan-layout>
