<x-app-layout>
    @section('title', 'Status Laporan Anonim ' . $report->anonymous_code . ' — LaporHijau')

    <div class="bg-gradient-to-b from-green-50/70 to-white dark:from-slate-900/30 dark:to-slate-900 min-h-screen py-10 pb-20">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Breadcrumb & Back --}}
            <div class="flex items-center justify-between no-print bg-white dark:bg-slate-800 p-4 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm">
                <a href="{{ route('laporan-anonim.cek-form') }}" class="text-xs font-bold text-gray-500 hover:text-green-600 transition-colors flex items-center gap-1.5">
                    ← Kembali ke Pelacakan
                </a>

                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-900 text-emerald-400 font-mono shadow-sm">
                    🔒 Anonim
                </span>
            </div>

            {{-- Main Content Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Left Side: Report Details --}}
                <div class="md:col-span-2 space-y-6">
                    {{-- Detail Card --}}
                    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700/80 p-6 sm:p-8 shadow-sm space-y-6">
                        <div class="space-y-2">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-green-50 dark:bg-green-950/20 text-green-700 dark:text-green-400">
                                {{ $report->category->icon ?? '📋' }} {{ $report->category->name ?? '-' }}
                            </span>
                            <h1 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white leading-tight">
                                {{ $report->title }}
                            </h1>
                            <p class="text-[10px] text-gray-400 font-mono">Kode Laporan: #{{ $report->anonymous_code }}</p>
                        </div>

                        {{-- Photos Grid if any --}}
                        @if ($report->photos->isNotEmpty())
                            <div class="grid grid-cols-3 gap-2.5">
                                @foreach ($report->photos as $photo)
                                    <a href="{{ $photo->photo_url }}" target="_blank" class="block aspect-square rounded-xl overflow-hidden border border-gray-150 dark:border-slate-700 hover:opacity-90 transition-opacity">
                                        <img src="{{ $photo->photo_url }}" alt="Foto Bukti" class="w-full h-full object-cover">
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        {{-- Deskripsi --}}
                        <div class="space-y-2">
                            <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Deskripsi Kejadian</span>
                            <p class="text-sm text-gray-600 dark:text-gray-350 leading-relaxed whitespace-pre-wrap">
                                {{ $report->description }}
                            </p>
                        </div>

                        {{-- Lokasi / Alamat --}}
                        <div class="space-y-2 pt-4 border-t border-gray-50 dark:border-slate-700/50">
                            <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Alamat Kejadian</span>
                            <p class="text-sm text-gray-700 dark:text-white font-semibold">
                                📍 {{ $report->address }}
                            </p>
                            <p class="text-[11px] text-gray-400 font-mono">
                                GPS: {{ $report->latitude }}, {{ $report->longitude }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Right Side: Status Timeline --}}
                <div class="space-y-6">
                    {{-- Status Badge Card --}}
                    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700/80 p-5 shadow-sm text-center space-y-2">
                        <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Status Saat Ini</span>
                        
                        @php
                            $statusBg = match($report->status) {
                                'pending'     => 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/20 dark:text-amber-400 dark:border-amber-900',
                                'verified'    => 'bg-sky-50 text-sky-700 border-sky-200 dark:bg-sky-950/20 dark:text-sky-400 dark:border-sky-900',
                                'in_progress' => 'bg-orange-50 text-orange-700 border-orange-200 dark:bg-orange-950/20 dark:text-orange-400 dark:border-orange-900',
                                'resolved'    => 'bg-green-50 text-green-700 border-green-200 dark:bg-green-950/20 dark:text-green-400 dark:border-green-900',
                                'rejected'    => 'bg-red-50 text-red-700 border-red-200 dark:bg-red-950/20 dark:text-red-400 dark:border-red-900',
                                default       => 'bg-gray-50 text-gray-700 border-gray-200',
                            };
                        @endphp
                        
                        <div class="inline-block px-4 py-2 rounded-full font-black text-xs border {{ $statusBg }} tracking-wide uppercase">
                            {{ $report->getStatusLabel() }}
                        </div>
                    </div>

                    {{-- Timeline --}}
                    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700/80 p-5 shadow-sm space-y-4">
                        <h3 class="text-xs font-extrabold text-gray-450 uppercase tracking-wider mb-2">📅 Riwayat Status</h3>
                        
                        @if ($report->statusLogs->isEmpty())
                            <p class="text-xs text-gray-400 text-center py-4">Belum ada riwayat status.</p>
                        @else
                            <div class="relative pl-4 border-l border-gray-100 dark:border-slate-700 space-y-6">
                                @foreach ($report->statusLogs as $log)
                                    @php
                                        $dotColor = match($log->new_status) {
                                            'pending'     => 'bg-amber-400',
                                            'verified'    => 'bg-sky-500',
                                            'in_progress' => 'bg-orange-500',
                                            'resolved'    => 'bg-green-600',
                                            'rejected'    => 'bg-red-500',
                                            default       => 'bg-gray-400',
                                        };
                                        $statusLabel = match($log->new_status) {
                                            'pending'     => 'Diajukan',
                                            'verified'    => 'Terverifikasi',
                                            'in_progress' => 'Sedang Ditangani',
                                            'resolved'    => 'Selesai Ditangani',
                                            'rejected'    => 'Laporan Ditolak',
                                            default       => $log->new_status,
                                        };
                                    @endphp
                                    <div class="relative space-y-1">
                                        {{-- Dot indicator --}}
                                        <div class="absolute -left-[21px] top-1 w-2.5 h-2.5 rounded-full {{ $dotColor }} border-2 border-white dark:border-slate-800"></div>

                                        <p class="text-xs font-extrabold text-gray-800 dark:text-white leading-none">
                                            {{ $statusLabel }}
                                        </p>
                                        <p class="text-[9px] text-gray-400">
                                            {{ $log->created_at->format('d M Y H:i') }}
                                        </p>
                                        @if ($log->notes)
                                            <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-1 leading-relaxed bg-gray-50 dark:bg-slate-750 p-2 rounded-lg italic">
                                                "{{ $log->notes }}"
                                            </p>
                                        @endif
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
