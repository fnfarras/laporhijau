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
                <h2 class="text-xl font-bold text-gray-900 leading-tight">{{ $report->title }}</h2>
                <p class="text-sm text-gray-500">Laporan #{{ $report->id }} · {{ $report->created_at->format('d M Y') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8" style="font-family: 'Plus Jakarta Sans', sans-serif;">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- ── Kolom Kiri (2/3) ─────────────────────────────── --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Slideshow Foto --}}
                    @if ($report->photos->isNotEmpty())
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
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
                                <p class="text-sm text-gray-700">{{ $report->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Tanggal</p>
                                <p class="text-sm text-gray-700">{{ $report->created_at->format('d F Y, H:i') }} WIB</p>
                            </div>
                        </div>
                    </div>

                    {{-- Peta Mini Leaflet --}}
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

                    {{-- Timeline Status --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
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

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
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
        </script>
    @endpush
</x-app-layout>
