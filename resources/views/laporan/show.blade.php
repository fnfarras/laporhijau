<x-app-layout>
    @section('title', $report->title)

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            #detail-map { height: 220px; border-radius: 12px; z-index: 1; }

            .slideshow-container { position: relative; overflow: hidden; border-radius: 16px; }
            .slide { display: none; }
            .slide.active { display: block; }
            .slide img { width: 100%; height: 380px; object-fit: cover; }

            .dot { width: 8px; height: 8px; border-radius: 50%; background: #d1d5db; cursor: pointer; transition: background 0.2s; }
            .dot.active { background: #16a34a; }

            .timeline-line::before {
                content: '';
                position: absolute;
                left: 11px; top: 24px; bottom: 0;
                width: 2px;
                background: #e5e7eb;
            }
            .timeline-dot {
                width: 24px; height: 24px;
                border-radius: 50%;
                flex-shrink: 0;
                display: flex; align-items: center; justify-content: center;
                font-size: 11px;
            }

            /* ── Print Layout CSS Overrides ── */
            @media print {
                /* Sembunyikan navigasi, header web, tombol cetak, komentar, dan map */
                nav, 
                header, 
                .no-print, 
                #detail-map, 
                .leaflet-control-container, 
                button, 
                form, 
                #comments-section,
                .fixed,
                footer {
                    display: none !important;
                }

                body {
                    background: white !important;
                    color: black !important;
                    font-size: 11pt !important;
                    line-height: 1.5 !important;
                }

                /* Sempurnakan kontainer cetak */
                .print-container {
                    width: 100% !important;
                    max-width: 100% !important;
                    margin: 0 !important;
                    padding: 0 !important;
                }

                /* Ubah layout grid menjadi satu kolom penuh */
                .grid {
                    display: block !important;
                }
                .lg\:col-span-2, .space-y-6, .lg\:col-span-1 {
                    width: 100% !important;
                    display: block !important;
                    margin-bottom: 25px !important;
                }

                /* Kop Surat Resmi LaporHijau */
                .print-header-letter {
                    display: block !important;
                    text-align: center;
                    border-bottom: 4px double #000;
                    margin-bottom: 30px;
                    padding-bottom: 12px;
                }
                .print-header-letter h1 {
                    font-size: 26pt !important;
                    font-weight: 900 !important;
                    letter-spacing: -1px;
                    margin: 0 !important;
                    color: #000 !important;
                }
                .print-header-letter p {
                    font-size: 10pt !important;
                    color: #444 !important;
                    margin: 4px 0 0 0 !important;
                    text-transform: uppercase;
                    font-weight: bold;
                }
                
                /* Reset latar belakang & bayangan card saat cetak */
                .bg-white {
                    background: transparent !important;
                    border: none !important;
                    box-shadow: none !important;
                    padding: 0 !important;
                }
                
                .border {
                    border: 1px solid #ddd !important;
                    border-radius: 8px !important;
                }
                
                /* Tampilan foto samping menyamping saat dicetak */
                .print-images {
                    display: flex !important;
                    gap: 15px !important;
                    margin: 20px 0 !important;
                }
                .print-images img {
                    width: 48% !important;
                    height: 250px !important;
                    object-fit: cover !important;
                    border-radius: 8px !important;
                    border: 1px solid #ccc !important;
                }
            }
        </style>
    @endpush

    <x-slot name="header">
        <div class="flex items-center gap-3" style="font-family: 'Plus Jakarta Sans', sans-serif;">
            <a href="{{ route('masyarakat.laporan') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <nav class="flex items-center text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-slate-500 mb-1.5 no-print">
                    <a href="{{ route('home') }}" class="hover:text-green-600 transition-colors">Beranda</a>
                    <span class="mx-1.5">/</span>
                    <a href="{{ route('masyarakat.laporan') }}" class="hover:text-green-600 transition-colors">Laporan</a>
                    <span class="mx-1.5">/</span>
                    <span class="text-gray-500">Detail Laporan #{{ $report->id }}</span>
                </nav>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white leading-tight">{{ $report->title }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Laporan #{{ $report->id }} · {{ $report->created_at->format('d M Y') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8" style="font-family: 'Plus Jakarta Sans', sans-serif;">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 print-container">

            {{-- Kop Surat Cetak (Hanya tampil saat print) --}}
            <div class="hidden print-header-letter text-center">
                <h1>LAPORHIJAU</h1>
                <p>Platform Civic Tech Kolaborasi Aksi Lingkungan Nasional</p>
                <div class="text-[9px] text-gray-400 mt-1 uppercase tracking-wider font-semibold">DOKUMEN RESMI PENANGANAN MASALAH LINGKUNGAN · TANGGAL CETAK: {{ now()->translatedFormat('d F Y') }}</div>
            </div>

            {{-- Bukti Foto Cetak Samping Menyamping (Hanya tampil saat print) --}}
            <div class="hidden print-images">
                @if ($report->photos->isNotEmpty())
                    <div style="flex: 1;">
                        <p class="text-xs font-bold text-gray-500 mb-1 text-center">FOTO SEBELUM PENANGANAN</p>
                        <img src="{{ $report->photos->first()->photo_url }}" class="rounded-xl border w-full h-[250px] object-cover" alt="Sebelum">
                    </div>
                @endif
                @if ($report->status === 'resolved' && $report->after_photo_url)
                    <div style="flex: 1;">
                        <p class="text-xs font-bold text-gray-500 mb-1 text-center">FOTO SESUDAH PENANGANAN</p>
                        <img src="{{ $report->after_photo_url }}" class="rounded-xl border w-full h-[250px] object-cover" alt="Sesudah">
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- ── Kolom Kiri (2/3) ─────────────────────────────── --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Slideshow / Before-After Slider (Fitur 1) --}}
                    @if ($report->status === 'resolved' && $report->after_photo_url)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden no-print">
                            <div class="p-6 space-y-4">
                                <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                                    <span>✨</span> Bukti Penanganan (Sebelum vs Sesudah)
                                </h3>
                                <div x-data="{ width: 50, containerWidth: 0 }" 
                                     x-init="containerWidth = $el.clientWidth; window.addEventListener('resize', () => containerWidth = $el.clientWidth)"
                                     class="relative w-full h-[350px] overflow-hidden rounded-2xl select-none cursor-ew-resize">
                                     
                                    <!-- Foto Sesudah (Background) -->
                                    <div class="absolute inset-0 w-full h-full">
                                        <img src="{{ $report->after_photo_url }}" class="w-full h-full object-cover pointer-events-none" alt="Setelah Penanganan">
                                        <div class="absolute right-4 top-4 bg-green-600 text-white text-[10px] font-bold px-2.5 py-1.5 rounded-xl z-10 shadow-sm">SESUDAH</div>
                                    </div>
                                    
                                    <!-- Foto Sebelum (Overlay) -->
                                    <div class="absolute inset-y-0 left-0 h-full overflow-hidden z-10" :style="'width: ' + width + '%'">
                                        <img src="{{ $report->photos->first()?->photo_url }}" class="absolute top-0 left-0 h-full object-cover pointer-events-none" :style="'width: ' + containerWidth + 'px; max-width: none;'" alt="Sebelum Penanganan">
                                        <div class="absolute left-4 top-4 bg-red-600 text-white text-[10px] font-bold px-2.5 py-1.5 rounded-xl z-10 shadow-sm">SEBELUM</div>
                                    </div>
                                    
                                    <!-- Range Slider overlapping -->
                                    <input type="range" min="0" max="100" x-model="width" 
                                           class="absolute inset-0 w-full h-full opacity-0 cursor-ew-resize z-30 m-0 p-0">
                                    
                                    <!-- Divider Bar / Slider Handle -->
                                    <div class="absolute inset-y-0 w-0.5 bg-white pointer-events-none z-20 shadow-md" :style="'left: ' + width + '%'">
                                        <div class="absolute top-1/2 -translate-y-1/2 -translate-x-1/2 w-8 h-8 bg-white text-gray-700 rounded-full flex items-center justify-center shadow-lg border border-gray-200 text-[10px]">
                                            ↔️
                                        </div>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-400 text-center">Geser slider di atas untuk membandingkan kondisi sebelum dan sesudah penanganan.</p>
                            </div>
                        </div>
                    @elseif ($report->photos->isNotEmpty())
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden no-print">
                            <div class="slideshow-container">
                                @foreach ($report->photos as $i => $photo)
                                    <div class="slide {{ $i === 0 ? 'active' : '' }}">
                                        <img src="{{ $photo->photo_url }}" alt="Foto {{ $i+1 }}" loading="{{ $i === 0 ? 'eager' : 'lazy' }}">
                                    </div>
                                @endforeach

                                @if ($report->photos->count() > 1)
                                    {{-- Prev/Next --}}
                                    <button onclick="changeSlide(-1)" class="absolute left-3 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/60 text-white rounded-full w-9 h-9 flex items-center justify-center transition-colors z-10">‹</button>
                                    <button onclick="changeSlide(1)" class="absolute right-3 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/60 text-white rounded-full w-9 h-9 flex items-center justify-center transition-colors z-10">›</button>

                                    {{-- Dots --}}
                                    <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5 z-10">
                                        @foreach ($report->photos as $i => $photo)
                                            <div class="dot {{ $i === 0 ? 'active' : '' }}" onclick="goToSlide({{ $i }})"></div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="px-4 py-2 bg-gray-50 text-xs text-gray-400 text-center">
                                {{ $report->photos->count() }} foto bukti
                            </div>
                        </div>
                    @else
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10 text-center text-gray-400">
                            <div class="text-5xl mb-2">🖼️</div>
                            <p class="text-sm">Tidak ada foto untuk laporan ini</p>
                        </div>
                    @endif

                    {{-- Detail Info --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-base font-bold text-gray-800 mb-4 pb-3 border-b border-gray-100">📋 Detail Laporan</h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Kategori</p>
                                <p class="text-sm text-gray-700 font-medium">{{ $report->category->icon ?? '' }} {{ $report->category->name ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Deskripsi</p>
                                <p class="text-sm text-gray-700 leading-relaxed">{{ $report->description }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Lokasi</p>
                                <p class="text-sm text-gray-700">📍 {{ $report->address }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $report->latitude }}, {{ $report->longitude }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Dilaporkan oleh</p>
                                <p class="text-sm text-gray-700">{{ $report->is_anonymous ? '🔒 ' . $report->reporter_name . ' (Anonim)' : $report->reporter_name }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Tanggal</p>
                                <p class="text-sm text-gray-700">{{ $report->created_at->format('d F Y, H:i') }} WIB</p>
                            </div>
                        </div>
                    </div>

                    <!-- ── Progress Tracker Laporan (Fitur 4) ── -->
                    @php
                        // Get logs indexed by status
                        $logs = $report->statusLogs->keyBy('new_status');
                        
                        $stages = [
                            [
                                'key' => 'pending',
                                'label' => 'Dilaporkan',
                                'icon' => '📋',
                                'active' => true,
                                'log' => $logs->get('pending')
                            ],
                            [
                                'key' => 'verified',
                                'label' => 'Terverifikasi',
                                'icon' => '✅',
                                'active' => in_array($report->status, ['verified', 'in_progress', 'resolved']),
                                'log' => $logs->get('verified')
                            ],
                            [
                                'key' => 'in_progress',
                                'label' => 'Ditangani',
                                'icon' => '🔧',
                                'active' => in_array($report->status, ['in_progress', 'resolved']),
                                'log' => $logs->get('in_progress')
                            ],
                            [
                                'key' => 'resolved',
                                'label' => 'Selesai',
                                'icon' => '🎉',
                                'active' => $report->status === 'resolved',
                                'log' => $logs->get('resolved')
                            ],
                        ];
                    @endphp

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Status Penanganan Laporan</h4>
                        
                        @if ($report->status === 'rejected')
                            @php $rejectLog = $logs->get('rejected'); @endphp
                            <div class="flex items-center gap-3 bg-red-50 border border-red-200 rounded-xl p-4">
                                <span class="text-2xl">❌</span>
                                <div>
                                    <p class="text-sm font-bold text-red-700">Laporan Ditolak</p>
                                    @if ($rejectLog?->notes)
                                        <p class="text-xs text-red-500 mt-1">Alasan: "{{ $rejectLog->notes }}"</p>
                                    @endif
                                    <p class="text-[10px] text-red-400 mt-0.5">
                                        Oleh {{ $rejectLog->changedBy->name ?? 'Relawan' }} · {{ $rejectLog->created_at->format('d M Y H:i') }}
                                    </p>
                                </div>
                            </div>
                        @else
                            <div class="relative flex flex-col md:flex-row justify-between items-start md:items-center gap-6 md:gap-0">
                                
                                {{-- Connector Lines (Desktop) --}}
                                <div class="hidden md:block absolute top-5.5 left-10 right-10 h-0.5 bg-gray-100 -z-10"></div>
                                
                                @foreach ($stages as $index => $stage)
                                    @php
                                        $isActive = $stage['active'];
                                        $isCurrent = $isActive && (($index === count($stages) - 1) || !$stages[$index + 1]['active']);
                                        $log = $stage['log'];
                                    @endphp
                                    
                                    <div class="flex md:flex-col items-center gap-4 md:gap-2 flex-1 w-full relative">
                                        
                                        {{-- Connector line for Mobile (vertical) --}}
                                        @if (!$loop->last)
                                            <div class="md:hidden absolute left-5.5 top-11 bottom-[-24px] w-0.5 {{ $stages[$index + 1]['active'] ? 'bg-green-500' : 'bg-gray-100' }}"></div>
                                        @endif
                                        
                                        {{-- Circle Icon --}}
                                        <div class="relative">
                                            <div class="w-11 h-11 rounded-full flex items-center justify-center text-lg border-2 transition-all duration-300 z-10 relative
                                                {{ $isActive 
                                                    ? 'bg-green-500 border-transparent text-white shadow-[0_4px_12px_rgba(22,163,74,0.3)]' 
                                                    : 'bg-white border-gray-200 text-gray-300 dark:bg-gray-800 dark:border-gray-700' }}"
                                            >
                                                {{ $stage['icon'] }}
                                            </div>
                                            @if ($isCurrent)
                                                <span class="absolute -inset-1 rounded-full bg-green-400/30 animate-ping"></span>
                                            @endif
                                        </div>

                                        {{-- Stage Details --}}
                                        <div class="text-left md:text-center space-y-1">
                                            <p class="text-xs font-bold leading-tight {{ $isActive ? 'text-gray-800 dark:text-white' : 'text-gray-300 dark:text-gray-600' }}">
                                                {{ $stage['label'] }}
                                            </p>
                                            @if ($isActive && $log)
                                                <p class="text-[9px] font-semibold text-gray-500 dark:text-gray-400">
                                                    {{ $log->changedBy->name ?? 'Sistem' }}
                                                </p>
                                                <p class="text-[8px] text-gray-400 font-medium leading-none">
                                                    {{ $log->created_at->format('d M Y H:i') }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- ── Peta Mini Leaflet ── -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-base font-bold text-gray-800 mb-4 pb-3 border-b border-gray-100">🗺️ Lokasi di Peta</h3>
                        <div id="detail-map"></div>
                    </div>

                    {{-- Komentar --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-base font-bold text-gray-800 mb-4 pb-3 border-b border-gray-100">
                            💬 Komentar ({{ $report->comments->count() }})
                        </h3>

                        @if ($report->comments->isEmpty())
                            <div class="text-center py-8 text-gray-400">
                                <div class="text-3xl mb-2">💬</div>
                                <p class="text-sm">Belum ada komentar.</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach ($report->comments->whereNull('parent_id') as $comment)
                                    <div class="flex gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-green-400 to-emerald-600 flex-shrink-0 flex items-center justify-center text-white font-bold text-xs">
                                            {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                        </div>
                                        <div class="flex-1">
                                            <div class="bg-gray-50 rounded-xl px-4 py-3">
                                                <p class="text-xs font-semibold text-gray-700 mb-1">{{ $comment->user->name }}</p>
                                                <p class="text-sm text-gray-600">{{ $comment->content }}</p>
                                            </div>
                                            <p class="text-xs text-gray-400 mt-1 ml-1">{{ $comment->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                </div>

                {{-- ── Kolom Kanan (1/3) — Sidebar ─────────────────── --}}
                <div class="space-y-6">

                    {{-- Status Card --}}
                    @php
                        $statusConfig = match($report->status) {
                            'pending'     => ['label' => 'Menunggu Verifikasi', 'icon' => '⏳', 'bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200'],
                            'verified'    => ['label' => 'Terverifikasi',       'icon' => '✅', 'bg' => 'bg-sky-50',    'text' => 'text-sky-700',   'border' => 'border-sky-200'],
                            'in_progress' => ['label' => 'Sedang Diproses',     'icon' => '🔧', 'bg' => 'bg-orange-50', 'text' => 'text-orange-700','border' => 'border-orange-200'],
                            'resolved'    => ['label' => 'Selesai Ditangani',   'icon' => '🎉', 'bg' => 'bg-green-50',  'text' => 'text-green-700', 'border' => 'border-green-200'],
                            'rejected'    => ['label' => 'Ditolak',             'icon' => '❌', 'bg' => 'bg-red-50',    'text' => 'text-red-700',   'border' => 'border-red-200'],
                            default       => ['label' => 'Menunggu',            'icon' => '⏳', 'bg' => 'bg-gray-50',   'text' => 'text-gray-700',  'border' => 'border-gray-200'],
                        };
                    @endphp
                    <div class="rounded-2xl border p-5 {{ $statusConfig['bg'] }} {{ $statusConfig['border'] }}">
                        <p class="text-xs font-semibold text-gray-500 mb-2">STATUS LAPORAN</p>
                        <div class="flex items-center gap-2">
                            <span class="text-2xl">{{ $statusConfig['icon'] }}</span>
                            <span class="text-base font-bold {{ $statusConfig['text'] }}">{{ $statusConfig['label'] }}</span>
                        </div>
                    </div>

                    {{-- SLA Transparency Card --}}
                    @php
                        $slaVerif = $report->sla_verification;
                        $slaHand = $report->sla_handling;
                    @endphp
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700/80 p-5 space-y-4 transition-colors no-print">
                        <h3 class="text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider">⏱️ Transparansi SLA</h3>
                        
                        {{-- SLA Verifikasi --}}
                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-xs font-bold">
                                <span class="text-gray-500 dark:text-gray-400">SLA Verifikasi (48 Jam)</span>
                                @if ($slaVerif['status'] === 'completed')
                                    <span class="text-green-600 dark:text-green-400 flex items-center gap-1">✅ Terverifikasi</span>
                                @elseif ($slaVerif['status'] === 'overdue')
                                    <span class="text-red-500 flex items-center gap-1">⚠️ Terlambat</span>
                                @else
                                    <span class="text-blue-500 flex items-center gap-1">⏳ Tepat Waktu</span>
                                @endif
                            </div>

                            <div class="p-3 rounded-xl border {{ $slaVerif['status'] === 'overdue' ? 'bg-red-50/50 dark:bg-red-950/10 border-red-150 dark:border-red-900/40 text-red-700 dark:text-red-400' : ($slaVerif['status'] === 'completed' ? 'bg-green-50/30 dark:bg-green-950/10 border-green-150 dark:border-green-900/30 text-green-700 dark:text-green-400' : 'bg-gray-50 dark:bg-slate-900/50 border-gray-150 dark:border-slate-800 text-gray-600 dark:text-gray-300') }} transition-all">
                                <p class="text-xs font-extrabold leading-tight">
                                    {{ $slaVerif['status'] === 'completed' ? 'Terverifikasi Tepat Waktu' : ($slaVerif['status'] === 'overdue' ? 'Melewati Batas Waktu Verifikasi' : 'Dalam Batas Waktu Verifikasi') }}
                                </p>
                                <p class="text-[10px] text-gray-400 mt-0.5">
                                    Tenggat: {{ $report->verified_deadline ? $report->verified_deadline->format('d M Y H:i') : '-' }}
                                </p>
                                @if ($slaVerif['status'] !== 'completed')
                                    <p class="text-xs font-extrabold mt-1.5">{{ $slaVerif['label'] }}</p>
                                    <div class="w-full bg-gray-200 dark:bg-slate-700 h-2 rounded-full overflow-hidden mt-1.5">
                                        <div class="h-full rounded-full {{ $slaVerif['status'] === 'overdue' ? 'bg-red-500' : 'bg-green-600' }}" style="width: {{ $slaVerif['percent'] }}%"></div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- SLA Penanganan (Hanya muncul jika status sudah minimal verified) --}}
                        @if ($report->status !== 'pending' && $report->status !== 'rejected')
                            <div class="space-y-2 pt-2 border-t border-gray-50 dark:border-slate-750">
                                <div class="flex items-center justify-between text-xs font-bold">
                                    <span class="text-gray-500 dark:text-gray-400">SLA Penanganan (7 Hari)</span>
                                    @if ($slaHand['status'] === 'completed')
                                        <span class="text-green-600 dark:text-green-400 flex items-center gap-1">🎉 Selesai</span>
                                    @elseif ($slaHand['status'] === 'overdue')
                                        <span class="text-red-500 flex items-center gap-1">⚠️ Terlambat</span>
                                    @else
                                        <span class="text-orange-500 flex items-center gap-1">🔧 Diproses</span>
                                    @endif
                                </div>

                                <div class="p-3 rounded-xl border {{ $slaHand['status'] === 'overdue' ? 'bg-red-50/50 dark:bg-red-950/10 border-red-150 dark:border-red-900/40 text-red-700 dark:text-red-400' : ($slaHand['status'] === 'completed' ? 'bg-green-50/30 dark:bg-green-950/10 border-green-150 dark:border-green-900/30 text-green-700 dark:text-green-400' : 'bg-gray-50 dark:bg-slate-900/50 border-gray-150 dark:border-slate-800 text-gray-600 dark:text-gray-300') }} transition-all">
                                    <p class="text-xs font-extrabold leading-tight">
                                        {{ $slaHand['status'] === 'completed' ? 'Ditangani Tepat Waktu' : ($slaHand['status'] === 'overdue' ? 'Melewati Batas Waktu Penanganan' : 'Dalam Batas Waktu Penanganan') }}
                                    </p>
                                    <p class="text-[10px] text-gray-400 mt-0.5">
                                        Tenggat: {{ $report->handled_deadline ? $report->handled_deadline->format('d M Y H:i') : '-' }}
                                    </p>
                                    @if ($slaHand['status'] !== 'completed')
                                        <p class="text-xs font-extrabold mt-1.5">{{ $slaHand['label'] }}</p>
                                        <div class="w-full bg-gray-200 dark:bg-slate-700 h-2 rounded-full overflow-hidden mt-1.5">
                                            <div class="h-full rounded-full {{ $slaHand['status'] === 'overdue' ? 'bg-red-500' : 'bg-green-600' }}" style="width: {{ $slaHand['percent'] }}%"></div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Timeline Status --}}
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700/80 p-5 transition-colors">
                        <h3 class="text-sm font-bold text-gray-800 mb-4">📅 Riwayat Status</h3>
                        @if ($report->statusLogs->isEmpty())
                            <p class="text-xs text-gray-400 text-center py-4">Belum ada riwayat status.</p>
                        @else
                            <div class="space-y-4">
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
                                            'pending'     => 'Menunggu',
                                            'verified'    => 'Terverifikasi',
                                            'in_progress' => 'Diproses',
                                            'resolved'    => 'Selesai',
                                            'rejected'    => 'Ditolak',
                                            default       => $log->new_status,
                                        };
                                    @endphp
                                    <div class="flex gap-3 items-start">
                                        <div class="timeline-dot {{ $dotColor }} text-white mt-0.5">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <circle cx="10" cy="10" r="4"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-semibold text-gray-800">{{ $statusLabel }}</p>
                                            @if ($log->notes)
                                                <p class="text-xs text-gray-500 mt-0.5">{{ $log->notes }}</p>
                                            @endif
                                            <p class="text-xs text-gray-400 mt-0.5">
                                                oleh {{ $log->changedBy->name ?? 'Sistem' }} ·
                                                {{ $log->created_at->format('d M Y H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    </div>

                    {{-- ── Share Laporan ── --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 no-print">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Tindakan & Bagikan</p>
                        <div class="space-y-2" x-data="{ openShareModal: false, shareCardUrl: '' }" @open-share-modal.window="openShareModal = true; shareCardUrl = $event.detail.url">
                            
                            {{-- Print PDF Button --}}
                            <button onclick="window.print()" 
                                    class="flex items-center justify-center gap-2.5 w-full px-3 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold rounded-xl transition-all shadow-md">
                                <span>📄</span> Cetak Laporan Resmi
                            </button>

                            @if ($report->status === 'resolved')
                                <button @click="generateShareCard()" 
                                        class="flex items-center justify-center gap-2.5 w-full px-3 py-2.5 bg-gradient-to-r from-green-600 to-teal-500 hover:from-green-700 hover:to-teal-600 text-white text-xs font-bold rounded-xl transition-all shadow-md">
                                    <span>🎉</span> Bagikan Pencapaian
                                </button>
                            @endif
                            @php
                                $shareText = "Lihat laporan lingkungan ini di LaporHijau! 🌿\n\n" .
                                             "📋 *" . $report->title . "*\n" .
                                             "📍 " . $report->address . "\n" .
                                             "🔴 Status: " . $report->getStatusLabel() . "\n\n" .
                                             "Pantau penanganannya di:\n" .
                                             url()->current() . "\n\n" .
                                             "#LaporHijau #LingkunganIndonesia";
                            @endphp
                            <a href="https://wa.me/?text={{ urlencode($shareText) }}"
                               target="_blank" rel="noopener noreferrer"
                               class="flex items-center justify-center gap-2.5 w-full px-3 py-2.5 bg-green-500 hover:bg-green-600 text-white text-xs font-bold rounded-xl transition-all shadow-sm">
                                💬 Share ke WhatsApp
                            </a>
                            <button onclick="copyLaporanLink()" id="btn-copy-link"
                                    class="flex items-center gap-2.5 w-full px-3 py-2.5 bg-gray-50 hover:bg-gray-100 text-gray-700 text-xs font-bold rounded-xl border border-gray-200 transition-all">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                Salin Link
                            </button>

                            <!-- Modal Share Card -->
                            <div x-show="openShareModal" 
                                 class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                                 
                                <!-- Overlay -->
                                <div class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity" @click="openShareModal = false"></div>
                                
                                <div class="flex min-h-full items-center justify-center p-4 text-center">
                                    <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md p-6 border border-gray-100 dark:border-gray-700">
                                        <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700 mb-4">
                                            <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-1.5">
                                                <span>🎉</span> Bagikan Pencapaian LaporHijau
                                            </h3>
                                            <button type="button" @click="openShareModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">✕</button>
                                        </div>
                                        
                                        <div class="flex flex-col items-center gap-4">
                                            <!-- Preview Image -->
                                            <div class="w-full border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden shadow-sm">
                                                <img :src="shareCardUrl" alt="Share Card Preview" class="w-full h-auto">
                                            </div>
                                            
                                            <div class="grid grid-cols-2 gap-3 w-full">
                                                <!-- Download PNG -->
                                                <a :href="shareCardUrl" download="laporhijau-pencapaian.png"
                                                   class="flex items-center justify-center gap-2 px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm text-center">
                                                    💾 Unduh PNG
                                                </a>
                                                
                                                <!-- WA Share -->
                                                <a :href="'https://wa.me/?text=' + encodeURIComponent('Alhamdulillah, masalah lingkungan ini berhasil diselesaikan! Lihat buktinya di LaporHijau: ' + window.location.href)"
                                                   target="_blank"
                                                   class="flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-xl transition-all shadow-sm text-center">
                                                    💬 Share WA
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>{{-- /sidebar --}}

            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>

            // ── Slideshow ──────────────────────────────────────────────────
            let currentSlide = 0;
            const slides = document.querySelectorAll('.slide');
            const dots   = document.querySelectorAll('.dot');

            function goToSlide(n) {
                slides.forEach(s => s.classList.remove('active'));
                dots.forEach(d => d.classList.remove('active'));
                currentSlide = (n + slides.length) % slides.length;
                slides[currentSlide].classList.add('active');
                if (dots[currentSlide]) dots[currentSlide].classList.add('active');
            }

            function changeSlide(dir) { goToSlide(currentSlide + dir); }

            // Auto-slide setiap 4 detik jika ada lebih dari 1 foto
            @if ($report->photos->count() > 1)
                setInterval(() => changeSlide(1), 4000);
            @endif

            // ── Leaflet Mini Map ───────────────────────────────────────────
            const lat = {{ $report->latitude }};
            const lng = {{ $report->longitude }};

            const detailMap = L.map('detail-map', { zoomControl: true, scrollWheelZoom: false })
                .setView([lat, lng], 15);

            L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                attribution: '© OpenStreetMap © CARTO'
            }).addTo(detailMap);

            L.marker([lat, lng], {
                icon: L.divIcon({
                    html: '<div style="background:#16a34a;width:20px;height:20px;border-radius:50%;border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.4)"></div>',
                    iconSize: [20, 20],
                    iconAnchor: [10, 10],
                    className: ''
                })
            }).addTo(detailMap)
              .bindPopup('<b>{{ addslashes($report->title) }}</b><br>{{ addslashes($report->address) }}')
              .openPopup();

            // ── Canvas Share Card Generator (Fitur 3) ──────────────────────
            function generateShareCard() {
                const canvas = document.createElement('canvas');
                canvas.width = 600;
                canvas.height = 400;
                const ctx = canvas.getContext('2d');

                // Background Gradient
                const grad = ctx.createLinearGradient(0, 0, 600, 400);
                grad.addColorStop(0, '#16a34a');
                grad.addColorStop(1, '#0d9488');
                ctx.fillStyle = grad;
                ctx.fillRect(0, 0, 600, 400);

                // Decorative Shapes
                ctx.fillStyle = 'rgba(255, 255, 255, 0.05)';
                ctx.beginPath(); ctx.arc(550, 50, 150, 0, Math.PI * 2); ctx.fill();
                ctx.beginPath(); ctx.arc(50, 350, 100, 0, Math.PI * 2); ctx.fill();

                // Logo Box
                ctx.fillStyle = '#ffffff';
                ctx.beginPath(); ctx.roundRect(40, 40, 42, 42, 12); ctx.fill();
                
                // Logo Icon
                ctx.fillStyle = '#16a34a';
                ctx.font = 'black 20px "Plus Jakarta Sans", sans-serif';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText('LH', 61, 61);

                // Brand Name
                ctx.fillStyle = '#ffffff';
                ctx.font = 'extrabold 22px "Plus Jakarta Sans", sans-serif';
                ctx.textAlign = 'left';
                ctx.fillText('LaporHijau', 94, 61);

                // Subtitle
                ctx.fillStyle = 'rgba(255, 255, 255, 0.8)';
                ctx.font = 'bold 11px "Plus Jakarta Sans", sans-serif';
                ctx.fillText('PENCAPAIAN AKSI LINGKUNGAN NYATA', 40, 125);

                // Main Heading
                ctx.fillStyle = '#ffffff';
                ctx.font = 'extrabold 24px "Plus Jakarta Sans", sans-serif';
                ctx.fillText('Masalah Lingkungan Berhasil Ditangani! ✅', 40, 155);

                // Wrap Title Text
                ctx.font = 'bold 18px "Plus Jakarta Sans", sans-serif';
                const title = "{{ addslashes($report->title) }}";
                const words = title.split(' ');
                let line = '';
                let y = 205;
                const maxWidth = 520;
                const lineHeight = 26;

                for (let n = 0; n < words.length; n++) {
                    let testLine = line + words[n] + ' ';
                    let metrics = ctx.measureText(testLine);
                    if (metrics.width > maxWidth && n > 0) {
                        ctx.fillText(line, 40, y);
                        line = words[n] + ' ';
                        y += lineHeight;
                    } else {
                        line = testLine;
                    }
                }
                ctx.fillText(line, 40, y);

                // Metadata Details
                const resolvedDate = "{{ $report->statusLogs->where('new_status','resolved')->last()?->created_at?->format('d M Y') ?? $report->updated_at->format('d M Y') }}";
                const reporterName = "{{ addslashes($report->is_anonymous ? 'Anonim' : $report->reporter_name) }}";
                const address = "{{ addslashes(Str::limit($report->address, 55)) }}";

                ctx.fillStyle = 'rgba(255, 255, 255, 0.9)';
                ctx.font = 'bold 12px "Plus Jakarta Sans", sans-serif';
                ctx.fillText(`📍 Lokasi: ${address}`, 40, 310);
                ctx.fillText(`📅 Selesai: ${resolvedDate}  ·  Dilaporkan oleh: ${reporterName}`, 40, 332);

                // Web Info Footer
                ctx.fillStyle = 'rgba(255, 255, 255, 0.65)';
                ctx.font = '800 10px "Plus Jakarta Sans", sans-serif';
                ctx.fillText('laporhijau.app — Bersama Jaga Lingkungan Indonesia', 40, 370);

                const dataUrl = canvas.toDataURL('image/png');
                window.dispatchEvent(new CustomEvent('open-share-modal', { detail: { url: dataUrl } }));
            }

            // ── Copy Link ──────────────────────────────────────────────────
            function copyLaporanLink() {
                navigator.clipboard.writeText(window.location.href).then(() => {
                    const btn = document.getElementById('btn-copy-link');
                    btn.innerHTML = `<svg class="w-4 h-4 flex-shrink-0 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg><span class="text-green-700">Link disalin!</span>`;
                    setTimeout(() => {
                        btn.innerHTML = `<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>Salin Link`;
                    }, 2000);
                });
            }
        </script>
    @endpush
</x-app-layout>

