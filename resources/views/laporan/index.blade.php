<x-app-layout>
    @section('title', 'Laporan Saya')

    <x-slot name="header">
        <div class="flex items-center justify-between" style="font-family: 'Plus Jakarta Sans', sans-serif;">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Laporan Saya</h2>
                <p class="text-sm text-gray-500 mt-0.5">Semua laporan masalah lingkungan yang kamu ajukan</p>
            </div>
            <a
                href="{{ route('laporan.create') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Buat Laporan Baru
            </a>
        </div>
    </x-slot>

    <div class="py-8" style="font-family: 'Plus Jakarta Sans', sans-serif;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- ── Stats Bar ─────────────────────────────────────────── --}}
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-8">
                @php
                    $statuses = [
                        ['label' => 'Total',       'value' => $reports->total(),   'color' => 'bg-gray-100 text-gray-700'],
                        ['label' => 'Menunggu',    'value' => auth()->user()->reports()->where('status','pending')->count(),     'color' => 'bg-amber-50 text-amber-700'],
                        ['label' => 'Terverifikasi', 'value' => auth()->user()->reports()->where('status','verified')->count(), 'color' => 'bg-sky-50 text-sky-700'],
                        ['label' => 'Diproses',    'value' => auth()->user()->reports()->where('status','in_progress')->count(),'color' => 'bg-orange-50 text-orange-700'],
                        ['label' => 'Selesai',     'value' => auth()->user()->reports()->where('status','resolved')->count(),   'color' => 'bg-green-50 text-green-700'],
                    ];
                @endphp
                @foreach ($statuses as $stat)
                    <div class="rounded-xl p-3.5 {{ $stat['color'] }} text-center">
                        <div class="text-2xl font-bold">{{ $stat['value'] }}</div>
                        <div class="text-xs font-medium mt-0.5">{{ $stat['label'] }}</div>
                    </div>
                @endforeach
            </div>

            {{-- ── Laporan List ──────────────────────────────────────── --}}
            @if ($reports->isEmpty())
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700/80 shadow-sm p-12 text-center">
                    {{-- Clean City SVG Illustration --}}
                    <div class="w-64 h-40 mx-auto mb-4 relative">
                        <svg viewBox="0 0 200 150" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full mx-auto">
                            <circle cx="160" cy="40" r="12" fill="#facc15" />
                            <path d="M30 40 C35 30, 50 30, 55 40 C60 35, 75 35, 80 45 C80 50, 30 50, 30 40 Z" fill="#e2e8f0" class="dark:fill-slate-700"/>
                            
                            <rect x="50" y="60" width="25" height="70" fill="#94a3b8" rx="2" class="dark:fill-slate-600"/>
                            <rect x="80" y="40" width="30" height="90" fill="#cbd5e1" rx="2" class="dark:fill-slate-500"/>
                            <rect x="115" y="70" width="20" height="60" fill="#94a3b8" rx="2" class="dark:fill-slate-600"/>
                            
                            <rect x="86" y="50" width="6" height="8" fill="#fef08a"/>
                            <rect x="98" y="50" width="6" height="8" fill="#fef08a"/>
                            
                            <circle cx="45" cy="115" r="16" fill="#16a34a"/>
                            <rect x="43" y="125" width="4" height="15" fill="#78350f"/>
                            
                            <circle cx="140" cy="110" r="18" fill="#15803d"/>
                            <rect x="138" y="120" width="4" height="20" fill="#78350f"/>
                            
                            <path d="M10 130 C60 125, 140 125, 190 130" stroke="#16a34a" stroke-width="6" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Belum ada laporan</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-xs mb-6 max-w-xs mx-auto">Kamu belum mengajukan laporan masalah lingkungan apapun. Mulai jaga lingkungan kita dengan melaporkan masalah di sekitarmu.</p>
                    <a href="{{ route('laporan.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Buat Laporan Pertamamu
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach ($reports as $report)
                        <a href="{{ route('laporan.show', $report) }}" class="group block bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 overflow-hidden">

                            {{-- Foto --}}
                            <div class="relative h-44 bg-gradient-to-br from-green-50 to-emerald-100 overflow-hidden">
                                @if ($report->photos->isNotEmpty())
                                    <img
                                        src="{{ $report->photos->first()->photo_url }}"
                                        alt="{{ $report->title }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                        loading="lazy"
                                    >
                                @else
                                    <div class="absolute inset-0 flex flex-col items-center justify-center text-green-300">
                                        <svg class="w-12 h-12 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-xs">Tanpa foto</span>
                                    </div>
                                @endif

                                {{-- Status Pill --}}
                                @php
                                    $pillClasses = match($report->status) {
                                        'pending'     => 'bg-amber-100 text-amber-700 border-amber-200',
                                        'verified'    => 'bg-sky-100 text-sky-700 border-sky-200',
                                        'in_progress' => 'bg-orange-100 text-orange-700 border-orange-200',
                                        'resolved'    => 'bg-green-100 text-green-700 border-green-200',
                                        'rejected'    => 'bg-red-100 text-red-700 border-red-200',
                                        default       => 'bg-gray-100 text-gray-700 border-gray-200',
                                    };
                                @endphp
                                <span class="absolute top-3 right-3 px-2.5 py-1 text-xs font-semibold rounded-full border {{ $pillClasses }}">
                                    {{ $report->getStatusLabel() }}
                                </span>
                            </div>

                            {{-- Info --}}
                            <div class="p-4">
                                <div class="flex items-center gap-1.5 mb-2">
                                    <span class="text-sm">{{ $report->category->icon ?? '📋' }}</span>
                                    <span class="text-xs text-gray-500 font-medium">{{ $report->category->name ?? '-' }}</span>
                                </div>
                                <h4 class="font-bold text-gray-800 text-sm leading-tight mb-2 line-clamp-2 group-hover:text-green-700 transition-colors">
                                    {{ $report->title }}
                                </h4>
                                <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-50">
                                    <div class="flex items-center gap-1 text-xs text-gray-400">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        </svg>
                                        <span class="truncate max-w-[120px]">{{ $report->address }}</span>
                                    </div>
                                    <span class="text-xs text-gray-400">{{ $report->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if ($reports->hasPages())
                    <div class="mt-8">
                        {{ $reports->links() }}
                    </div>
                @endif
            @endif

        </div>
    </div>
</x-app-layout>
