<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaporHijau — Platform Lingkungan Komunitas Indonesia</title>
    <meta name="description" content="Platform civic tech untuk pelaporan, pemantauan, dan penanganan masalah lingkungan hidup secara kolaboratif di Indonesia.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        html { scroll-behavior: smooth; }

        /* ── Hero ── */
        .hero-bg { background: linear-gradient(170deg, #f0fdf4 0%, #ffffff 60%, #f0f9ff 100%); }

        /* ── Counter animasi ── */
        @keyframes countUp { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
        .counter-in { animation: countUp 0.5s ease forwards; }
        .counter-num { display: inline-block; transition: all 0.3s ease; }

        /* ── Cards ── */
        .how-card { transition: transform 0.25s, box-shadow 0.25s; }
        .how-card:hover { transform: translateY(-4px); box-shadow: 0 16px 40px rgba(22,163,74,0.10); }

        .article-card { transition: transform 0.2s, box-shadow 0.2s; }
        .article-card:hover { transform: translateY(-3px); box-shadow: 0 12px 28px rgba(0,0,0,0.08); }

        /* ── Map ── */
        #home-map { height: 300px; border-radius: 16px; z-index: 1; }

        /* ── Stat highlight ── */
        .stat-big { font-size: clamp(2rem, 5vw, 3.5rem); font-weight: 900; line-height: 1; }

        /* ── Gradient text ── */
        .text-gradient { background: linear-gradient(135deg, #16a34a, #0ea5e9); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    </style>
</head>
<body class="bg-white text-gray-900" x-data="landingApp()" x-init="init()">

    {{-- ════════════════ NAVBAR ════════════════ --}}
    <nav class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-green-600 rounded-xl flex items-center justify-center text-white font-black text-sm">LH</div>
                    <span class="font-extrabold text-gray-900 text-lg">LaporHijau</span>
                </a>

                {{-- Desktop Nav --}}
                <div class="hidden md:flex items-center gap-6 text-sm font-semibold text-gray-600">
                    <a href="{{ route('peta') }}" class="hover:text-green-600 transition-colors">🗺 Peta</a>
                    <a href="{{ route('event.index') }}" class="hover:text-green-600 transition-colors">🌿 Event</a>
                    <a href="{{ route('artikel.index') }}" class="hover:text-green-600 transition-colors">📚 Artikel</a>
                    <a href="{{ route('leaderboard') }}" class="hover:text-green-600 transition-colors">🏆 Leaderboard</a>
                </div>

                {{-- Auth --}}
                <div class="hidden md:flex items-center gap-3">
                    @auth
                        <a href="{{ route('profil', auth()->user()) }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-50 text-green-700 text-xs font-bold rounded-full border border-green-200 hover:bg-green-100 transition-colors">
                            ⭐ {{ number_format(auth()->user()->fresh()->points) }} poin
                        </a>
                        <a href="{{ route('dashboard') }}"
                           class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-xl transition-all shadow-md">
                            Dashboard →
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-600 hover:text-green-700 transition-colors">Masuk</a>
                        <a href="{{ route('register') }}"
                           class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-xl transition-all shadow-md">
                            Daftar Gratis →
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- ════════════════ SECTION 1: HERO ════════════════ --}}
    <section class="hero-bg py-16 sm:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center">
                {{-- Sub-label --}}
                <div class="inline-flex items-center gap-2 bg-green-100 text-green-700 text-xs font-bold px-4 py-2 rounded-full mb-6 border border-green-200">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    Platform Lingkungan Komunitas Indonesia
                </div>

                {{-- Headline --}}
                <h1 class="text-3xl sm:text-5xl font-black text-gray-900 leading-tight mb-5">
                    Laporkan masalah lingkungan,<br>
                    <span class="text-gradient">pantau penanganannya bersama</span>
                </h1>

                <p class="text-base sm:text-lg text-gray-500 mb-8 leading-relaxed max-w-2xl mx-auto">
                    LaporHijau menghubungkan masyarakat, relawan, dan pemerintah untuk menyelesaikan masalah lingkungan secara transparan dan kolaboratif.
                    Setiap laporan kamu berkontribusi nyata untuk lingkungan yang lebih baik.
                </p>

                {{-- CTA Buttons --}}
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3 mb-12">
                    <a href="{{ route('laporan.create') }}"
                       class="inline-flex items-center gap-2 px-6 py-3.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl text-sm shadow-lg hover:shadow-xl transition-all">
                        📸 Lapor Sekarang →
                    </a>
                    <a href="{{ route('peta') }}"
                       class="inline-flex items-center gap-2 px-6 py-3.5 bg-white hover:bg-gray-50 text-gray-700 font-bold rounded-xl text-sm border-2 border-gray-200 hover:border-green-300 transition-all">
                        🗺 Lihat Peta
                    </a>
                </div>

                {{-- Impact Counter ── Alpine.js live update + count-up animasi --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 max-w-2xl mx-auto" id="counter-grid">
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 text-center">
                        <p class="text-2xl font-black text-gray-900 counter-num" id="cnt-laporan" data-target="{{ $stats['total_laporan'] }}">0</p>
                        <p class="text-xs text-gray-500 font-medium mt-0.5">📋 Laporan Masuk</p>
                    </div>
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 text-center">
                        <p class="text-2xl font-black text-green-600 counter-num" id="cnt-selesai" data-target="{{ $stats['laporan_selesai'] }}">0</p>
                        <p class="text-xs text-gray-500 font-medium mt-0.5">🎉 Laporan Selesai</p>
                    </div>
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 text-center">
                        <p class="text-2xl font-black text-sky-600 counter-num" id="cnt-relawan" data-target="{{ $stats['relawan_aktif'] }}">0</p>
                        <p class="text-xs text-gray-500 font-medium mt-0.5">🌿 Relawan Aktif</p>
                    </div>
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 text-center">
                        <p class="text-2xl font-black text-amber-600 counter-num" id="cnt-artikel" data-target="{{ $stats['artikel'] }}">0</p>
                        <p class="text-xs text-gray-500 font-medium mt-0.5">📚 Artikel Edukasi</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ════════════════ SECTION 2: PETA PREVIEW ════════════════ --}}
    <section class="py-14 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-extrabold text-gray-900">📍 Laporan di Peta Real-time</h2>
                <p class="text-gray-500 text-sm mt-1">Pantau persebaran masalah lingkungan di seluruh wilayah</p>
            </div>

            <div id="home-map" class="border border-gray-200 shadow-md"></div>

            {{-- Legend --}}
            <div class="flex flex-wrap items-center justify-center gap-4 mt-4">
                @foreach([['#f59e0b','Pending'],['#0ea5e9','Verified'],['#f97316','Ditangani'],['#16a34a','Selesai'],['#ef4444','Ditolak']] as $s)
                    <div class="flex items-center gap-1.5">
                        <div class="w-3 h-3 rounded-full" style="background: {{ $s[0] }}"></div>
                        <span class="text-xs text-gray-500 font-medium">{{ $s[1] }}</span>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-5">
                <a href="{{ route('peta') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-xl transition-all shadow-md">
                    Buka Peta Penuh →
                </a>
            </div>
        </div>
    </section>

    {{-- ════════════════ SECTION 3: CARA KERJA ════════════════ --}}
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <h2 class="text-2xl font-extrabold text-gray-900">Bagaimana LaporHijau Bekerja?</h2>
                <p class="text-gray-500 text-sm mt-1">3 langkah sederhana dari laporan hingga penyelesaian</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach([
                    ['📸','Lapor & Foto','Temukan masalah lingkungan? Foto, tandai lokasi di peta, dan kirim laporan dalam 60 detik.','#f0fdf4','#16a34a','1'],
                    ['✅','Relawan Verifikasi','Relawan terlatih memverifikasi laporan di lapangan dan memastikan informasi akurat.','#eff6ff','#0ea5e9','2'],
                    ['🏛','Pemerintah Tangani','Instansi terkait menerima notifikasi dan mengambil tindakan nyata di lapangan.','#fffbeb','#f59e0b','3'],
                ] as $step)
                    <div class="how-card bg-white rounded-2xl border border-gray-100 shadow-sm p-6 text-center">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-4"
                             style="background: {{ $step[2] }}">{{ $step[0] }}</div>
                        <div class="w-6 h-6 rounded-full text-white text-xs font-black flex items-center justify-center mx-auto mb-2"
                             style="background: {{ $step[3] }}">{{ $step[4] }}</div>
                        <h3 class="font-extrabold text-gray-900 mb-2">{{ $step[1] }}</h3>
                        <p class="text-xs text-gray-500 leading-relaxed">{{ $step[2+1] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ════════════════ SECTION 4: STATISTIK HIGHLIGHT ════════════════ --}}
    <section class="py-14" style="background: linear-gradient(135deg, #16a34a 0%, #059669 50%, #0ea5e9 100%)">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-center text-white">
                <div>
                    <p class="stat-big" x-text="stats.laporan_bulan_ini + '+'">{{ $stats['laporan_bulan_ini'] }}+</p>
                    <p class="text-white/80 text-sm font-semibold mt-1">Laporan Bulan Ini</p>
                </div>
                <div>
                    <p class="stat-big" x-text="stats.relawan_aktif + '+'">{{ $stats['relawan_aktif'] }}+</p>
                    <p class="text-white/80 text-sm font-semibold mt-1">Relawan Aktif</p>
                </div>
                <div>
                    <p class="stat-big" x-text="stats.resolved_rate + '%'">{{ $stats['resolved_rate'] }}%</p>
                    <p class="text-white/80 text-sm font-semibold mt-1">Tingkat Penyelesaian</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ════════════════ SECTION 5: EVENT & ARTIKEL TERBARU ════════════════ --}}
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                {{-- Event Upcoming --}}
                <div>
                    <div class="flex items-center justify-between mb-5">
                        <h2 class="text-lg font-extrabold text-gray-900">🌿 Event Mendatang</h2>
                        <a href="{{ route('event.index') }}" class="text-xs font-bold text-green-600 hover:text-green-700">Lihat Semua →</a>
                    </div>

                    @if ($upcomingEvents->isEmpty())
                        <div class="bg-white rounded-2xl border border-gray-100 p-8 text-center">
                            <p class="text-sm text-gray-400">Belum ada event mendatang</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach ($upcomingEvents as $event)
                                @php
                                    $grads = ['linear-gradient(135deg,#16a34a,#0ea5e9)','linear-gradient(135deg,#f59e0b,#ef4444)','linear-gradient(135deg,#8b5cf6,#ec4899)'];
                                    $g = $grads[$loop->index % 3];
                                @endphp
                                <a href="{{ route('event.show', $event) }}"
                                   class="flex items-center gap-3 bg-white rounded-2xl border border-gray-100 shadow-sm p-4 hover:shadow-md transition-shadow">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl flex-shrink-0"
                                         style="background: {{ $g }}">
                                        @if ($event->category === 'Bersih-bersih') 🧹
                                        @elseif ($event->category === 'Tanam Pohon') 🌳
                                        @elseif ($event->category === 'Gotong Royong') 🤝
                                        @else 🌿
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-gray-900 truncate">{{ $event->title }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            {{ $event->event_date->translatedFormat('D, d M Y') }} · {{ $event->activeParticipants()->count() }} peserta
                                        </p>
                                    </div>
                                    <span class="text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded-lg flex-shrink-0">{{ $event->category }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Artikel Terbaru --}}
                <div>
                    <div class="flex items-center justify-between mb-5">
                        <h2 class="text-lg font-extrabold text-gray-900">📚 Artikel Terbaru</h2>
                        <a href="{{ route('artikel.index') }}" class="text-xs font-bold text-green-600 hover:text-green-700">Lihat Semua →</a>
                    </div>

                    @if ($latestArticles->isEmpty())
                        <div class="bg-white rounded-2xl border border-gray-100 p-8 text-center">
                            <p class="text-sm text-gray-400">Belum ada artikel</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach ($latestArticles as $article)
                                @php
                                    $catGrads = ['Daur Ulang'=>'linear-gradient(135deg,#3b82f6,#6366f1)','Regulasi'=>'linear-gradient(135deg,#8b5cf6,#ec4899)','Tips Lingkungan'=>'linear-gradient(135deg,#16a34a,#0ea5e9)','Edukasi'=>'linear-gradient(135deg,#f59e0b,#ef4444)','Inspirasi'=>'linear-gradient(135deg,#ec4899,#f97316)'];
                                    $catEmojis = ['Daur Ulang'=>'♻️','Regulasi'=>'⚖️','Tips Lingkungan'=>'🌿','Edukasi'=>'📖','Inspirasi'=>'✨'];
                                    $ag = $catGrads[$article->category] ?? 'linear-gradient(135deg,#16a34a,#059669)';
                                    $ae = $catEmojis[$article->category] ?? '📄';
                                @endphp
                                <a href="{{ route('artikel.show', $article->slug) }}"
                                   class="article-card flex items-center gap-3 bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl flex-shrink-0"
                                         style="background: {{ $ag }}">{{ $ae }}</div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-gray-900 line-clamp-1">{{ $article->title }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $article->category }} · {{ $article->reading_time }} mnt baca</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </section>

    {{-- ════════════════ SECTION 6: CTA BANNER ════════════════ --}}
    <section class="py-16" style="background: #14532d">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-xs font-bold text-green-300 uppercase tracking-widest mb-3">Bergabung Sekarang</p>
            <h2 class="text-3xl sm:text-4xl font-black text-white mb-4">Mulai berkontribusi hari ini</h2>
            <p class="text-green-200 text-sm mb-8 max-w-xl mx-auto leading-relaxed">
                Bersama kita bisa menciptakan lingkungan yang lebih bersih, sehat, dan lestari untuk generasi mendatang. Bergabunglah dengan ribuan warga yang sudah bergerak.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ route('register') }}"
                   class="px-8 py-3.5 bg-white hover:bg-gray-50 text-green-800 text-sm font-extrabold rounded-xl transition-all shadow-lg hover:shadow-xl">
                    Daftar Gratis →
                </a>
                <a href="{{ route('laporan.create') }}"
                   class="px-8 py-3.5 bg-green-600/30 hover:bg-green-600/50 text-white text-sm font-bold rounded-xl border border-green-500 transition-all">
                    Lapor Masalah
                </a>
            </div>
        </div>
    </section>

    {{-- ════════════════ FOOTER ════════════════ --}}
    <footer class="bg-gray-900 text-gray-400 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 pb-8 border-b border-gray-800">
                <div>
                    <div class="flex items-center gap-2.5 mb-3">
                        <div class="w-8 h-8 bg-green-600 rounded-xl flex items-center justify-center text-white font-black text-sm">LH</div>
                        <span class="font-extrabold text-white text-lg">LaporHijau</span>
                    </div>
                    <p class="text-sm text-gray-400 leading-relaxed">
                        Platform civic tech untuk pelaporan dan penanganan masalah lingkungan hidup secara kolaboratif di Indonesia.
                    </p>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-white mb-3">Navigasi</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('peta') }}" class="hover:text-green-400 transition-colors">🗺 Peta Interaktif</a></li>
                        <li><a href="{{ route('event.index') }}" class="hover:text-green-400 transition-colors">🌿 Event Komunitas</a></li>
                        <li><a href="{{ route('artikel.index') }}" class="hover:text-green-400 transition-colors">📚 Artikel Edukasi</a></li>
                        <li><a href="{{ route('leaderboard') }}" class="hover:text-green-400 transition-colors">🏆 Leaderboard</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-white mb-3">Akun</h4>
                    <ul class="space-y-2 text-sm">
                        @guest
                            <li><a href="{{ route('login') }}" class="hover:text-green-400 transition-colors">Masuk</a></li>
                            <li><a href="{{ route('register') }}" class="hover:text-green-400 transition-colors">Daftar Gratis</a></li>
                        @else
                            <li><a href="{{ route('dashboard') }}" class="hover:text-green-400 transition-colors">Dashboard</a></li>
                            <li><a href="{{ route('profil', auth()->user()) }}" class="hover:text-green-400 transition-colors">Profil Saya</a></li>
                            <li><a href="{{ route('laporan.create') }}" class="hover:text-green-400 transition-colors">Buat Laporan</a></li>
                        @endguest
                    </ul>
                </div>
            </div>
            <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-500">
                <p>© {{ date('Y') }} LaporHijau. NIFC 5.0 — Empowering Communities Through Digital Solutions for Sustainability.</p>
                <p>Dibuat dengan ❤️ untuk Indonesia yang lebih hijau</p>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // ── Count-Up Animation ──────────────────────────────────────────────
        function animateCounter(el, target, duration = 1200) {
            const start = performance.now();
            const startVal = 0;
            function update(now) {
                const elapsed = now - start;
                const progress = Math.min(elapsed / duration, 1);
                // Ease out cubic
                const eased = 1 - Math.pow(1 - progress, 3);
                el.textContent = Math.round(startVal + (target - startVal) * eased);
                if (progress < 1) requestAnimationFrame(update);
            }
            requestAnimationFrame(update);
        }

        // Trigger count-up saat counter grid masuk viewport
        const counterGrid = document.getElementById('counter-grid');
        if (counterGrid) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        document.querySelectorAll('.counter-num').forEach(el => {
                            animateCounter(el, parseInt(el.dataset.target || '0'));
                        });
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.3 });
            observer.observe(counterGrid);
        }

        function landingApp() {
            return {
                stats: {
                    total_laporan:      {{ $stats['total_laporan'] }},
                    laporan_selesai:    {{ $stats['laporan_selesai'] }},
                    relawan_aktif:      {{ $stats['relawan_aktif'] }},
                    artikel:            {{ $stats['artikel'] }},
                    resolved_rate:      {{ $stats['resolved_rate'] }},
                    laporan_bulan_ini:  {{ $stats['laporan_bulan_ini'] }},
                },

                init() {
                    this.initMap();
                    // Refresh stats tiap 30 detik
                    setInterval(() => this.fetchStats(), 30000);
                },

                async fetchStats() {
                    try {
                        const r = await fetch('/api/stats');
                        if (r.ok) {
                            this.stats = await r.json();
                            // Re-animate counters setelah data update
                            ['total_laporan','laporan_selesai','relawan_aktif','artikel'].forEach(key => {
                                const ids = {total_laporan:'cnt-laporan',laporan_selesai:'cnt-selesai',relawan_aktif:'cnt-relawan',artikel:'cnt-artikel'};
                                const el = document.getElementById(ids[key]);
                                if (el) animateCounter(el, this.stats[key], 600);
                            });
                        }
                    } catch(e) {}
                },

                initMap() {
                    const map = L.map('home-map', { zoomControl: true, scrollWheelZoom: false })
                        .setView([0.5096, 101.4506], 11);

                    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                        attribution: '&copy; OpenStreetMap &copy; CARTO',
                        subdomains: 'abcd',
                        maxZoom: 19
                    }).addTo(map);

                    const statusColors = {
                        pending:     '#f59e0b',
                        verified:    '#0ea5e9',
                        in_progress: '#f97316',
                        resolved:    '#16a34a',
                        rejected:    '#ef4444',
                    };

                    fetch('/api/map-data')
                        .then(r => r.json())
                        .then(reports => {
                            reports.forEach(rpt => {
                                if (!rpt.latitude || !rpt.longitude) return;
                                const color = statusColors[rpt.status] || '#6b7280';
                                const icon = L.divIcon({
                                    html: `<div style="background:${color};width:14px;height:14px;border-radius:50%;border:2px solid white;box-shadow:0 2px 4px rgba(0,0,0,0.3)"></div>`,
                                    className: '',
                                    iconSize: [14, 14],
                                    iconAnchor: [7, 7],
                                });
                                L.marker([rpt.latitude, rpt.longitude], { icon })
                                    .addTo(map)
                                    .bindPopup(`<strong>${rpt.title}</strong><br><small>${rpt.category} · ${rpt.status}</small>`);
                            });
                        }).catch(() => {});
                }
            }
        }
    </script>
</body>
</html>

