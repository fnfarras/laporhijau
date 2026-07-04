<x-relawan-layout>
    @section('title', 'Riwayat Verifikasi')

    <div class="pt-6 pb-4">
        <h1 class="text-2xl font-bold text-gray-900">Riwayat Saya</h1>
        <p class="text-sm text-gray-500 mt-0.5">Laporan yang sudah kamu verifikasi atau tolak</p>
    </div>

    @if ($logs->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 p-16 text-center">
            <div class="text-6xl mb-3">📜</div>
            <h3 class="text-base font-bold text-gray-700 mb-1">Belum ada riwayat</h3>
            <p class="text-sm text-gray-400 mb-6">Kamu belum pernah memverifikasi atau menolak laporan apapun.</p>
            <a href="{{ route('relawan.antrian') }}"
               class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold text-sm rounded-xl transition-colors">
                Lihat Antrian Laporan
            </a>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="divide-y divide-gray-50">
                @foreach ($logs as $log)
                    @php
                        $isVerified = $log->new_status === 'verified';
                        $report     = $log->report;
                    @endphp
                    <div class="flex items-center gap-4 px-5 py-4 hover:bg-gray-50 transition-colors">

                        {{-- Thumbnail --}}
                        <div class="w-14 h-14 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100">
                            @if ($report && $report->photos->isNotEmpty())
                                <img src="{{ $report->photos->first()->photo_url }}"
                                     alt="{{ $report->title }}"
                                     class="w-full h-full object-cover"
                                     loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300 text-xl">
                                    {{ $isVerified ? '✅' : '❌' }}
                                </div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            @if ($report)
                                <div class="flex items-center gap-1.5 mb-0.5">
                                    <span class="text-xs">{{ $report->category->icon ?? '📋' }}</span>
                                    <span class="text-xs text-gray-400">{{ $report->category->name ?? '-' }}</span>
                                </div>
                                <a href="{{ route('laporan.show', $report) }}" target="_blank"
                                   class="text-sm font-semibold text-gray-800 hover:text-green-700 transition-colors line-clamp-1">
                                    {{ $report->title }}
                                </a>
                            @else
                                <p class="text-sm font-semibold text-gray-400 italic">Laporan telah dihapus</p>
                            @endif

                            <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                                <span class="text-xs text-gray-400">
                                    {{ $log->created_at->format('d M Y, H:i') }}
                                </span>
                                @if ($log->notes)
                                    <span class="text-xs text-gray-300">·</span>
                                    <span class="text-xs text-gray-400 italic truncate max-w-xs">
                                        "{{ Str::limit($log->notes, 60) }}"
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Status + Poin --}}
                        <div class="flex flex-col items-end gap-1 flex-shrink-0">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                                {{ $isVerified
                                    ? 'bg-green-100 text-green-700 border border-green-200'
                                    : 'bg-red-100 text-red-700 border border-red-200' }}">
                                {{ $isVerified ? '✅ Verified' : '❌ Rejected' }}
                            </span>
                            @if ($isVerified)
                                <span class="text-xs text-green-600 font-semibold">+20 poin</span>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>
        </div>

        {{-- Pagination --}}
        @if ($logs->hasPages())
            <div class="mt-6">
                {{ $logs->links() }}
            </div>
        @endif
    @endif
</x-relawan-layout>
