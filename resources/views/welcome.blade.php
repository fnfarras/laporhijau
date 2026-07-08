<!DOCTYPE html>
<html lang="id"
      x-data="landingApp()"
      x-init="init()"
      :class="{ 'dark': dark }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaporHijau — Platform Lingkungan Komunitas Indonesia</title>
    <meta name="description" content="Platform civic tech untuk pelaporan, pemantauan, dan penanganan masalah lingkungan hidup secara kolaboratif di Indonesia.">
    
    <!-- SEO Meta Tags -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="LaporHijau — Platform Lingkungan Komunitas Indonesia">
    <meta property="og:description" content="Platform civic tech untuk pelaporan, pemantauan, dan penanganan masalah lingkungan hidup secara kolaboratif di Indonesia.">
    <meta name="twitter:card" content="summary_large_image">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        html { scroll-behavior: smooth; }

        /* ── Glowing Floating Blobs ── */
        @keyframes float-blob {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(35px, -60px) scale(1.15); }
            66% { transform: translate(-25px, 25px) scale(0.9); }
        }
        .blob-1 {
            animation: float-blob 12s infinite ease-in-out;
        }
        .blob-2 {
            animation: float-blob 15s infinite ease-in-out 2s;
        }
        .blob-3 {
            animation: float-blob 18s infinite ease-in-out 4s;
        }

        /* ── Floating Animation ── */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
        }
        .animate-float {
            animation: float 4s ease-in-out infinite;
        }
        .animate-float-delayed {
            animation: float 4.5s ease-in-out infinite;
            animation-delay: 2s;
        }

        /* ── Pulse Animation ── */
        @keyframes pulse-subtle {
            0%, 100% { transform: scale(1); box-shadow: 0 4px 15px rgba(22, 163, 74, 0.2); }
            50% { transform: scale(1.03); box-shadow: 0 8px 25px rgba(22, 163, 74, 0.4); }
        }
        .animate-pulse-subtle {
            animation: pulse-subtle 2s infinite ease-in-out;
        }

        /* ── Glowing Shadow Animation ── */
        @keyframes glowing {
            0% { box-shadow: 0 0 5px rgba(22, 163, 74, 0.1); }
            50% { box-shadow: 0 0 25px rgba(22, 163, 74, 0.35); }
            100% { box-shadow: 0 0 5px rgba(22, 163, 74, 0.1); }
        }
        .glow-button {
            animation: glowing 3s infinite ease-in-out;
        }

        /* ── Decorative Line Hover Animation ── */
        .nav-link-anim {
            position: relative;
        }
        .nav-link-anim::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2.5px;
            bottom: -4px;
            left: 0;
            background-color: #16a34a;
            border-radius: 99px;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .nav-link-anim:hover::after {
            width: 100%;
        }

        /* ── Map ── */
        #home-map { height: 380px; border-radius: 24px; z-index: 1; }

        /* ── Custom Dot & Grid Background Subtle ── */
        .bg-grid-subtle {
            background-size: 30px 30px;
            background-image: linear-gradient(to right, rgba(0, 0, 0, 0.02) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(0, 0, 0, 0.02) 1px, transparent 1px);
        }
        .dark .bg-grid-subtle {
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.015) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255, 255, 255, 0.015) 1px, transparent 1px);
        }

        /* ── Card Lift Animation ── */
        .how-card {
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.4s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.4s;
        }
        .how-card:hover {
            transform: translateY(-8px) scale(1.01);
            border-color: rgba(22, 163, 74, 0.4);
            box-shadow: 0 20px 40px -15px rgba(22, 163, 74, 0.12);
        }

        /* ── Leaflet Custom Popup Styling ── */
        .leaflet-popup-content-wrapper {
            border-radius: 16px !important;
            box-shadow: 0 12px 30px rgba(0,0,0,0.06) !important;
            border: 1px solid rgba(226, 232, 240, 0.8) !important;
            padding: 4px !important;
        }
        .leaflet-popup-tip-container {
            display: none !important; /* Hilangkan tip segitiga agar mengapung bersih */
        }

        /* ── Custom Scroll & Transitions ── */
        body {
            transition: background-color 0.3s, color 0.3s;
        }
    </style>
</head>
<body class="bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen">

    {{-- ════════════════ NAVBAR ════════════════ --}}
    <nav class="sticky top-0 z-50 transition-all duration-300 border-b border-gray-100 dark:border-gray-800"
         :class="scrolled ? 'bg-white/80 dark:bg-gray-900/80 backdrop-blur-md shadow-sm' : 'bg-white dark:bg-gray-900'">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-17">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center text-sm shadow-sm">
                        🌿
                    </div>
                    <span class="font-black text-gray-900 dark:text-white text-xl tracking-tight" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                        Lapor<span class="text-green-600">Hijau</span>
                    </span>
                </a>
 
                {{-- Desktop Nav Links --}}
                <div class="hidden md:flex items-center gap-4 text-xs font-bold text-gray-600 dark:text-gray-300">
                    <a href="{{ route('peta') }}" class="nav-link-anim hover:text-green-600 dark:hover:text-green-400 transition-colors whitespace-nowrap">🗺 Peta</a>
                    <a href="{{ route('event.index') }}" class="nav-link-anim hover:text-green-600 dark:hover:text-green-400 transition-colors whitespace-nowrap">🌿 Event</a>
                    <a href="{{ route('artikel.index') }}" class="nav-link-anim hover:text-green-600 dark:hover:text-green-400 transition-colors whitespace-nowrap">📚 Artikel</a>
                    <a href="{{ route('leaderboard') }}" class="nav-link-anim hover:text-green-600 dark:hover:text-green-400 transition-colors whitespace-nowrap">🏆 Leaderboard</a>
                    <a href="{{ route('hadiah') }}" class="nav-link-anim hover:text-green-600 dark:hover:text-green-400 transition-colors whitespace-nowrap">🎁 Hadiah</a>
                    <a href="{{ route('laporan-anonim.create') }}" class="nav-link-anim hover:text-green-600 dark:hover:text-green-400 transition-colors whitespace-nowrap">🔒 Lapor Anonim</a>
                </div>

                {{-- Action / Auth --}}
                <div class="flex items-center gap-3">
                    {{-- Dark Mode Toggle --}}
                    <button @click="toggleDark()"
                            class="p-2 rounded-xl bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-amber-300 border border-gray-200 dark:border-gray-700 transition-all">
                        <span x-show="dark">☀️</span>
                        <span x-show="!dark">🌙</span>
                    </button>

                    @auth
                        <a href="{{ route('profil', auth()->user()) }}"
                           class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-50 dark:bg-green-950/30 text-green-700 dark:text-green-400 text-xs font-bold rounded-full border border-green-200 dark:border-green-800 transition-colors">
                            ⭐ {{ number_format(auth()->user()->fresh()->points) }} poin
                        </a>
                        <a href="{{ route('dashboard') }}"
                           class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-xl transition-all shadow-md">
                            Dashboard →
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-bold text-gray-600 dark:text-gray-300 hover:text-green-600 transition-colors">Masuk</a>
                        <a href="{{ route('register') }}"
                           class="px-3.5 py-2.5 bg-green-600 hover:bg-green-700 text-white text-xs sm:text-sm font-extrabold rounded-xl transition-all shadow-md animate-pulse-subtle">
                            <span class="sm:hidden">Daftar</span>
                            <span class="hidden sm:inline">Daftar Gratis</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- ════════════════ SECTION 1: HERO ════════════════ --}}
    <section class="relative py-20 sm:py-28 overflow-hidden bg-grid-subtle">
        {{-- Subtle radial gradient backgrounds with floating blobs (Fitur Wow Premium) --}}
        <div class="absolute top-0 right-0 w-[500px] h-[500px] rounded-full bg-green-100/30 dark:bg-green-900/10 blur-[120px] -z-10 blob-1"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] rounded-full bg-sky-100/30 dark:bg-sky-900/10 blur-[100px] -z-10 blob-2"></div>
        <div class="absolute top-1/2 left-1/3 w-[350px] h-[350px] rounded-full bg-emerald-100/20 dark:bg-emerald-900/10 blur-[100px] -z-10 blob-3"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            
            {{-- Floating Badges (Desktop Only) --}}
            <div class="hidden lg:block">
                <div class="absolute left-10 top-12 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-xl rounded-2xl p-3 flex items-center gap-2.5 animate-float z-10">
                    <span class="text-xl">✅</span>
                    <div>
                        <p class="text-xs font-bold text-gray-900 dark:text-white">89% Ditangani</p>
                        <p class="text-[10px] text-gray-400">Respon cepat instansi</p>
                    </div>
                </div>
                
                <div class="absolute right-12 top-20 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-xl rounded-2xl p-3 flex items-center gap-2.5 animate-float-delayed z-10">
                    <span class="text-xl">🌿</span>
                    <div>
                        <p class="text-xs font-bold text-gray-900 dark:text-white">1,247 Laporan</p>
                        <p class="text-[10px] text-gray-400">Telah terkirim & diproses</p>
                    </div>
                </div>
            </div>

            <div class="max-w-3xl mx-auto text-center space-y-6">
                {{-- Sub-label --}}
                <div class="inline-flex items-center gap-2 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-bold px-4.5 py-2.5 rounded-full border border-green-200 dark:border-green-800">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    Platform Civic Tech Lingkungan Hidup Nasional
                </div>

                {{-- Headline --}}
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-gray-900 dark:text-white leading-tight">
                    Laporkan masalah lingkungan,<br>
                    <span class="bg-gradient-to-r from-green-600 to-teal-500 bg-clip-text text-transparent">pantau penanganannya bersama</span>
                </h1>

                <p class="text-base sm:text-lg text-gray-500 dark:text-gray-400 max-w-2xl mx-auto leading-relaxed">
                    LaporHijau mempermudah masyarakat, relawan, dan instansi pemerintah berkolaborasi menyelesaikan tumpukan sampah, pencemaran air, dan isu lingkungan lainnya demi kelestarian Indonesia.
                </p>

                {{-- CTA Buttons --}}
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4 flex-wrap">
                    <a href="{{ route('laporan.create') }}"
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-8 py-4 bg-gradient-to-r from-green-600 to-emerald-500 hover:from-green-700 hover:to-emerald-600 text-white font-extrabold rounded-2xl text-sm shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-0.5 glow-button">
                        📸 Lapor Sekarang
                    </a>
                    <a href="{{ route('peta') }}"
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-8 py-4 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 font-extrabold rounded-2xl text-sm border border-gray-200 dark:border-gray-700 transition-all duration-300 hover:-translate-y-0.5 shadow-sm">
                        🗺 Lihat Peta Interaktif
                    </a>
                    <div class="w-full sm:w-auto flex flex-col items-center">
                        <button onclick="laporWhatsApp()"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-8 py-4 border-2 border-green-600 hover:bg-green-50 dark:hover:bg-green-950/20 text-green-600 dark:text-green-400 font-extrabold rounded-2xl text-sm transition-all duration-300 hover:-translate-y-0.5 shadow-sm bg-transparent cursor-pointer">
                            💬 Lapor via WhatsApp
                        </button>
                        <span class="text-[10px] text-gray-400 mt-1 font-semibold">Lebih mudah lewat WhatsApp</span>
                    </div>
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    Atau lapor tanpa akun → <a href="{{ route('laporan-anonim.create') }}" class="text-green-600 dark:text-green-400 hover:underline font-bold">Laporan Anonim</a>
                </div>

                {{-- Impact Counter --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 max-w-2xl mx-auto pt-12" id="counter-grid">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-4 text-center relative overflow-hidden transition-all duration-300 hover:shadow-md">
                        <p class="text-3xl font-black text-gray-900 dark:text-white counter-num" id="cnt-laporan" data-target="{{ $stats['total_laporan'] }}">0</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold mt-1">Laporan Masuk</p>
                        <p class="text-[10px] font-bold text-green-600 dark:text-green-400 mt-2" id="cnt-laporan-today">
                            @if($stats['laporan_hari_ini'] > 0) +{{ $stats['laporan_hari_ini'] }} baru hari ini @else &nbsp; @endif
                        </p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-4 text-center relative overflow-hidden transition-all duration-300 hover:shadow-md">
                        <p class="text-3xl font-black text-green-600 dark:text-green-400 counter-num" id="cnt-selesai" data-target="{{ $stats['laporan_selesai'] }}">0</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold mt-1">Laporan Selesai</p>
                        <p class="text-[10px] font-bold text-green-600 dark:text-green-400 mt-2" id="cnt-selesai-today">
                            @if($stats['selesai_hari_ini'] > 0) +{{ $stats['selesai_hari_ini'] }} baru hari ini @else &nbsp; @endif
                        </p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-4 text-center relative overflow-hidden transition-all duration-300 hover:shadow-md">
                        <p class="text-3xl font-black text-sky-600 dark:text-sky-400 counter-num" id="cnt-relawan" data-target="{{ $stats['relawan_aktif'] }}">0</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold mt-1">Relawan Aktif</p>
                        <p class="text-[10px] font-bold text-green-600 dark:text-green-400 mt-2" id="cnt-relawan-today">
                            @if($stats['relawan_hari_ini'] > 0) +{{ $stats['relawan_hari_ini'] }} baru hari ini @else &nbsp; @endif
                        </p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-4 text-center relative overflow-hidden transition-all duration-300 hover:shadow-md">
                        <p class="text-3xl font-black text-amber-600 dark:text-amber-400 counter-num" id="cnt-artikel" data-target="{{ $stats['artikel'] }}">0</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold mt-1">Artikel Edukasi</p>
                        <p class="text-[10px] font-bold text-green-600 dark:text-green-400 mt-2" id="cnt-artikel-today">
                            @if($stats['artikel_hari_ini'] > 0) +{{ $stats['artikel_hari_ini'] }} baru hari ini @else &nbsp; @endif
                        </p>
                    </div>
                </div>

                {{-- SLA Penanganan Tepat Waktu Bar --}}
                @php
                    $rate = $stats['resolved_rate'];
                    $colorClass = $rate > 70 
                        ? 'text-green-600 dark:text-green-400 bg-green-50/50 dark:bg-green-950/20 border-green-200 dark:border-green-800/40' 
                        : ($rate >= 40 
                            ? 'text-amber-600 dark:text-amber-400 bg-amber-50/50 dark:bg-amber-950/20 border-amber-200 dark:border-amber-800/40' 
                            : 'text-red-650 dark:text-red-400 bg-red-50/50 dark:bg-red-950/20 border-red-200 dark:border-red-800/40');
                @endphp
                <div class="max-w-2xl mx-auto mt-6 p-4 rounded-2xl border text-xs sm:text-sm font-black flex items-center justify-center gap-2 {{ $colorClass }} transition-colors shadow-sm">
                    <span>📊</span> Tingkat Penanganan Tepat Waktu: {{ $rate }}%
                </div>

            </div>
        </div>
    </section>

    {{-- ════════════════ SECTION 2: PETA PREVIEW ════════════════ --}}
    <section class="py-20 bg-gray-50 dark:bg-gray-900/50 border-y border-gray-100 dark:border-gray-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">📍 Sebaran Laporan Terkini</h2>
                <div class="w-16 h-1 bg-green-500 mx-auto mt-3 rounded-full"></div>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-3">Pantau dan verifikasi kondisi lapangan secara waktu nyata</p>
            </div>

            <div id="home-map" class="border border-gray-200 dark:border-gray-700 shadow-md"></div>

            {{-- Legend --}}
            <div class="flex flex-wrap items-center justify-center gap-4 mt-6">
                @foreach([['#f59e0b','Pending'],['#0ea5e9','Verified'],['#f97316','Ditangani'],['#16a34a','Selesai'],['#ef4444','Ditolak']] as $s)
                    <div class="flex items-center gap-2 bg-white dark:bg-gray-800 px-3.5 py-1.5 rounded-full border border-gray-100 dark:border-gray-700 shadow-xs">
                        <div class="w-3 h-3 rounded-full" style="background: {{ $s[0] }}"></div>
                        <span class="text-xs text-gray-600 dark:text-gray-300 font-semibold">{{ $s[1] }}</span>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-8">
                <a href="{{ route('peta') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-xl transition-all shadow-md hover:shadow-lg">
                    Buka Peta Interaktif Penuh →
                </a>
            </div>
        </div>
    </section>

    {{-- ════════════════ SECTION 3: CARA KERJA ════════════════ --}}
    <section class="py-20 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 relative">
                <h2 class="text-3xl font-black text-gray-900 dark:text-white">Bagaimana LaporHijau Bekerja?</h2>
                <div class="w-16 h-1 bg-green-500 mx-auto mt-3 rounded-full"></div>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-3">3 langkah mudah mewujudkan kolaborasi aksi lingkungan</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                
                {{-- Card 1: Lapor & Foto --}}
                <div class="how-card relative bg-white dark:bg-gray-800 rounded-3xl border border-gray-200 dark:border-gray-700 p-8 text-center space-y-4 shadow-[0_4px_20px_rgba(0,0,0,0.06)] dark:shadow-none">
                    <span class="absolute top-4 right-6 text-xs font-black text-green-600 bg-green-50 dark:bg-green-950/40 border border-green-200 dark:border-green-900 px-3 py-1 rounded-full">01</span>
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center text-3xl mx-auto shadow-md">
                        📸
                    </div>
                    <h3 class="font-extrabold text-gray-900 dark:text-white text-lg">Lapor & Foto</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                        Upload foto masalah lingkungan, tandai lokasi di peta, dan kirim laporan dalam waktu kurang dari 2 menit
                    </p>
                </div>

                {{-- Card 2: Relawan Verifikasi --}}
                <div class="how-card relative bg-white dark:bg-gray-800 rounded-3xl border border-gray-200 dark:border-gray-700 p-8 text-center space-y-4 shadow-[0_4px_20px_rgba(0,0,0,0.06)] dark:shadow-none">
                    <span class="absolute top-4 right-6 text-xs font-black text-sky-600 bg-sky-50 dark:bg-sky-950/40 border border-sky-200 dark:border-sky-900 px-3 py-1 rounded-full">02</span>
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-sky-500 to-blue-600 flex items-center justify-center text-3xl mx-auto shadow-md">
                        ✅
                    </div>
                    <h3 class="font-extrabold text-gray-900 dark:text-white text-lg">Relawan Verifikasi</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                        Relawan terdekat turun ke lapangan, memverifikasi kondisi nyata dan mengkonfirmasi laporan valid
                    </p>
                </div>

                {{-- Card 3: Pemerintah Tangani --}}
                <div class="how-card relative bg-white dark:bg-gray-800 rounded-3xl border border-gray-200 dark:border-gray-700 p-8 text-center space-y-4 shadow-[0_4px_20px_rgba(0,0,0,0.06)] dark:shadow-none">
                    <span class="absolute top-4 right-6 text-xs font-black text-amber-600 bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-900 px-3 py-1 rounded-full">03</span>
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-amber-500 to-yellow-600 flex items-center justify-center text-3xl mx-auto shadow-md">
                        🏛️
                    </div>
                    <h3 class="font-extrabold text-gray-900 dark:text-white text-lg">Pemerintah Tangani</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                        Instansi terkait merespons laporan, menangani masalah, dan memperbarui status hingga selesai
                    </p>
                </div>

            </div>
        </div>
    </section>

    {{-- ════════════════ SECTION 4: STATISTIK HIGHLIGHT ════════════════ --}}
    <section class="py-20 relative overflow-hidden bg-gradient-to-r from-green-600 to-emerald-700 text-white" id="stats-section">
        {{-- Custom pattern --}}
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:20px_20px]"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-10 text-center">
                <div class="space-y-2">
                    <div class="text-4xl">🗓️</div>
                    <p class="text-5xl font-black counter-num" id="stat-bulan" data-target="{{ $stats['laporan_bulan_ini'] }}">0</p>
                    <p class="text-green-100/90 text-sm font-semibold tracking-wider uppercase">Laporan Bulan Ini</p>
                </div>
                <div class="space-y-2">
                    <div class="text-4xl">🌿</div>
                    <p class="text-5xl font-black counter-num" id="stat-relawan" data-target="{{ $stats['relawan_aktif'] }}">0</p>
                    <p class="text-green-100/90 text-sm font-semibold tracking-wider uppercase">Relawan Aktif</p>
                </div>
                <div class="space-y-2">
                    <div class="text-4xl">⚡</div>
                    <p class="text-5xl font-black counter-num" id="stat-rate" data-target="{{ $stats['resolved_rate'] }}">0</p>
                    <p class="text-green-100/90 text-sm font-semibold tracking-wider uppercase">Tingkat Penyelesaian %</p>
                </div>
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('open-data') }}" class="inline-flex items-center gap-2 px-8 py-3.5 bg-white text-green-700 hover:bg-gray-50 font-extrabold text-sm rounded-2xl transition-all shadow-md">
                    Lihat Data Lengkap →
                </a>
            </div>
        </div>
    </section>

    {{-- ════════════════ SECTION 5: EVENT & ARTIKEL TERBARU ════════════════ --}}
    <section class="py-20 bg-gray-50 dark:bg-gray-900/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

                {{-- Event Upcoming --}}
                <div class="space-y-6">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-200 dark:border-gray-800">
                        <h2 class="text-xl font-extrabold text-gray-900 dark:text-white flex items-center gap-2">
                            <span>🌿</span> Event Mendatang
                        </h2>
                        <a href="{{ route('event.index') }}" class="text-xs font-bold text-green-600 hover:text-green-700">Lihat Semua →</a>
                    </div>

                    @if ($upcomingEvents->isEmpty())
                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-8 text-center">
                            <p class="text-sm text-gray-400">Belum ada event mendatang</p>
                        </div>
                    @else
                        <div class="space-y-3.5">
                            @foreach ($upcomingEvents as $event)
                                @php
                                    $grads = ['linear-gradient(135deg,#16a34a,#0ea5e9)','linear-gradient(135deg,#f59e0b,#ef4444)','linear-gradient(135deg,#8b5cf6,#ec4899)'];
                                    $g = $grads[$loop->index % 3];
                                @endphp
                                <a href="{{ route('event.show', $event) }}"
                                   class="flex items-center gap-4 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/80 shadow-xs hover:shadow-md transition-shadow p-4.5">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl flex-shrink-0"
                                         style="background: {{ $g }}">
                                        @if ($event->category === 'Bersih-bersih') 🧹
                                        @elseif ($event->category === 'Tanam Pohon') 🌳
                                        @elseif ($event->category === 'Gotong Royong') 🤝
                                        @else 🌿
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $event->title }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            📅 {{ $event->event_date->translatedFormat('D, d M Y') }} · 👥 {{ $event->activeParticipants()->count() }} peserta
                                        </p>
                                    </div>
                                    <span class="text-[10px] font-bold text-green-600 bg-green-50 dark:bg-green-950/40 dark:text-green-400 px-2.5 py-1 rounded-full flex-shrink-0">{{ $event->category }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Artikel Terbaru --}}
                <div class="space-y-6">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-200 dark:border-gray-800">
                        <h2 class="text-xl font-extrabold text-gray-900 dark:text-white flex items-center gap-2">
                            <span>📚</span> Artikel Edukasi
                        </h2>
                        <a href="{{ route('artikel.index') }}" class="text-xs font-bold text-green-600 hover:text-green-700">Lihat Semua →</a>
                    </div>

                    @if ($latestArticles->isEmpty())
                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-8 text-center">
                            <p class="text-sm text-gray-400">Belum ada artikel</p>
                        </div>
                    @else
                        <div class="space-y-3.5">
                            @foreach ($latestArticles as $article)
                                @php
                                    $catGrads = ['Daur Ulang'=>'linear-gradient(135deg,#3b82f6,#6366f1)','Regulasi'=>'linear-gradient(135deg,#8b5cf6,#ec4899)','Tips Lingkungan'=>'linear-gradient(135deg,#16a34a,#0ea5e9)','Edukasi'=>'linear-gradient(135deg,#f59e0b,#ef4444)','Inspirasi'=>'linear-gradient(135deg,#ec4899,#f97316)'];
                                    $catEmojis = ['Daur Ulang'=>'♻️','Regulasi'=>'⚖️','Tips Lingkungan'=>'🌿','Edukasi'=>'📖','Inspirasi'=>'✨'];
                                    $ag = $catGrads[$article->category] ?? 'linear-gradient(135deg,#16a34a,#059669)';
                                    $ae = $catEmojis[$article->category] ?? '📄';
                                @endphp
                                <a href="{{ route('artikel.show', $article->slug) }}"
                                   class="article-card flex items-center gap-4 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/80 shadow-xs hover:shadow-md transition-shadow p-4.5">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl flex-shrink-0"
                                         style="background: {{ $ag }}">{{ $ae }}</div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-gray-900 dark:text-white line-clamp-1 leading-snug">{{ $article->title }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">📚 {{ $article->category }} · ⏳ {{ $article->reading_time }} mnt baca</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </section>

    {{-- ── ══════════════ SECTION: 3 CARA MUDAH MELAPOR ════════════════ --}}
    <section class="py-20 bg-white dark:bg-gray-900 transition-colors border-t border-gray-100 dark:border-gray-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-black text-gray-900 dark:text-white">📣 3 Cara Mudah Melapor</h2>
                <div class="w-16 h-1 bg-green-500 mx-auto mt-3 rounded-full"></div>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-3">Pilih cara yang paling cocok untuk berpartisipasi menjaga kelestarian sekitar kita</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                {{-- Card 1: Via Website --}}
                <div class="how-card bg-white dark:bg-gray-800 rounded-3xl border border-gray-150 dark:border-gray-700 p-8 text-center space-y-5 flex flex-col justify-between shadow-[0_4px_20px_rgba(0,0,0,0.03)]">
                    <div class="space-y-4">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center text-3xl mx-auto shadow-md">
                            🌐
                        </div>
                        <h3 class="font-extrabold text-gray-900 dark:text-white text-lg">Via Website</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                            Metode paling lengkap. Dapatkan poin kontribusi, naikkan peringkat di leaderboard, dan raih badge kehormatan.
                        </p>
                    </div>
                    <a href="{{ route('laporan.create') }}" class="block text-center w-full py-3.5 bg-green-600 hover:bg-green-700 text-white font-bold text-xs rounded-xl transition-all shadow-sm">
                        Lapor Sekarang
                    </a>
                </div>

                {{-- Card 2: Via WhatsApp --}}
                <div class="how-card bg-white dark:bg-gray-800 rounded-3xl border border-gray-150 dark:border-gray-700 p-8 text-center space-y-5 flex flex-col justify-between shadow-[0_4px_20px_rgba(0,0,0,0.03)]">
                    <div class="space-y-4">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-3xl mx-auto shadow-md">
                            💬
                        </div>
                        <h3 class="font-extrabold text-gray-900 dark:text-white text-lg">Via WhatsApp</h3>
                        <p class="text-xs text-gray-550 dark:text-gray-400 leading-relaxed">
                            Mudah, cepat, dan familiar untuk semua rentang usia. Cukup kirim pesan, koordinat GPS, serta foto bukti masalah.
                        </p>
                    </div>
                    <button onclick="laporWhatsApp()" class="block text-center w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl transition-all shadow-sm border-0 cursor-pointer">
                        Buka WhatsApp
                    </button>
                </div>

                {{-- Card 3: Via Komunitas --}}
                <div class="how-card bg-white dark:bg-gray-800 rounded-3xl border border-gray-150 dark:border-gray-700 p-8 text-center space-y-5 flex flex-col justify-between shadow-[0_4px_20px_rgba(0,0,0,0.03)]">
                    <div class="space-y-4">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-sky-500 to-blue-600 flex items-center justify-center text-3xl mx-auto shadow-md">
                            👥
                        </div>
                        <h3 class="font-extrabold text-gray-900 dark:text-white text-lg">Via Komunitas</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                            Ikuti event aksi bersih-bersih penanggulangan lingkungan bersama warga dan komunitas relawan LaporHijau terdekat.
                        </p>
                    </div>
                    <a href="{{ route('event.index') }}" class="block text-center w-full py-3.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-xl transition-all shadow-sm">
                        Lihat Event
                    </a>
                </div>

            </div>
        </div>
    </section>

    {{-- ════════════════ SECTION 6: CTA BANNER ════════════════ --}}
    <section class="py-20 relative overflow-hidden bg-gradient-to-br from-green-900 to-emerald-950 text-white text-center">
        <div class="absolute inset-0 opacity-5 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:20px_20px]"></div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-6">
            <p class="text-xs font-bold text-green-300 uppercase tracking-widest">Kolaborasi Untuk Kelestarian</p>
            <h2 class="text-3xl sm:text-4xl font-black">Mulai Berkontribusi Hari Ini</h2>
            <p class="text-green-200/80 text-sm max-w-xl mx-auto leading-relaxed">
                Bergabunglah dengan ribuan masyarakat, relawan, dan dinas pemerintah dalam mewujudkan lingkungan Indonesia yang bersih, sehat, dan bebas pencemaran.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3.5 pt-4">
                <a href="{{ route('register') }}"
                   class="w-full sm:w-auto px-8 py-4 bg-white text-green-900 font-extrabold rounded-2xl shadow-lg hover:shadow-xl hover:bg-gray-50 transition-all text-sm">
                    Daftar Akun Gratis
                </a>
                <a href="{{ route('laporan.create') }}"
                   class="w-full sm:w-auto px-8 py-4 bg-white/10 text-white border border-white/20 font-bold rounded-2xl hover:bg-white/25 transition-all text-sm">
                    Buat Laporan Baru
                </a>
            </div>
        </div>
    </section>

    {{-- ════════════════ FOOTER ════════════════ --}}
    <footer class="bg-[#0f172a] text-gray-400 py-16 border-t-4 border-gradient-to-r from-green-500 to-emerald-400">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 pb-12 border-b border-gray-800">
                <div class="md:col-span-2 space-y-4">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 text-white">
                        <span class="text-2xl">🍃</span>
                        <span class="font-extrabold text-xl tracking-tight">Lapor<span class="text-green-500">Hijau</span></span>
                    </a>
                    <p class="text-xs text-gray-400 leading-relaxed max-w-sm">
                        Platform civic tech untuk pelaporan, pemantauan, dan penanganan isu lingkungan hidup di Indonesia. Bersama menjaga kelestarian bumi nusantara.
                    </p>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider mb-4">Navigasi</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('peta') }}" class="hover:text-green-400 transition-colors">🗺 Peta Interaktif</a></li>
                        <li><a href="{{ route('event.index') }}" class="hover:text-green-400 transition-colors">🌿 Event Komunitas</a></li>
                        <li><a href="{{ route('artikel.index') }}" class="hover:text-green-400 transition-colors">📚 Artikel Edukasi</a></li>
                        <li><a href="{{ route('leaderboard') }}" class="hover:text-green-400 transition-colors">🏆 Leaderboard</a></li>
                        <li><a href="{{ route('hadiah') }}" class="hover:text-green-400 transition-colors">🎁 Toko Hadiah</a></li>
                        <li><a href="{{ route('laporan-anonim.create') }}" class="hover:text-green-400 transition-colors">🔒 Laporan Anonim</a></li>
                        <li><a href="{{ route('open-data') }}" class="hover:text-green-400 transition-colors">🔓 Open Data</a></li>
                        <li><a href="{{ route('tentang') }}" class="hover:text-green-400 transition-colors">ℹ️ Tentang Kami</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider mb-4">Hubungi Kami</h4>
                    <div class="space-y-3 text-xs">
                        <a href="https://wa.me/{{ env('WHATSAPP_NUMBER', '6281234567890') }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 hover:text-green-400 transition-colors">
                            <span>💬</span> WhatsApp: +62 812-3456-7890
                        </a>
                        <div class="flex gap-3 text-white pt-2 border-t border-gray-800/80">
                            <a href="https://github.com" target="_blank" class="w-8 h-8 rounded-xl bg-gray-800 hover:bg-gray-700 flex items-center justify-center transition-colors text-sm">GH</a>
                            <a href="https://instagram.com" target="_blank" class="w-8 h-8 rounded-xl bg-gray-800 hover:bg-gray-700 flex items-center justify-center transition-colors text-sm">IG</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row items-center justify-between text-xs text-gray-500 pt-8">
                <p>© 2026 LaporHijau. Bersama menjaga lingkungan Indonesia.</p>
                <div class="flex gap-4 mt-4 sm:mt-0">
                    <a href="#" class="hover:underline">Kebijakan Privasi</a>
                    <a href="#" class="hover:underline">Ketentuan Layanan</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // ── Count-Up Animation (Fitur 2) ───────────────────────────────────
        function animateCounter(el, target, duration = 1200) {
            const start = performance.now();
            const startVal = 0;
            function update(now) {
                const elapsed = now - start;
                const progress = Math.min(elapsed / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3); // Ease out cubic
                const currentVal = Math.round(startVal + (target - startVal) * eased);
                el.textContent = currentVal.toLocaleString('id-ID');
                if (progress < 1) requestAnimationFrame(update);
            }
            requestAnimationFrame(update);
        }

        // Trigger count-up saat element masuk viewport
        const counterGrid = document.getElementById('counter-grid');
        const statsSection = document.getElementById('stats-section');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.querySelectorAll('.counter-num').forEach(el => {
                        animateCounter(el, parseInt(el.dataset.target || '0'));
                    });
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.3 });

        if (counterGrid) observer.observe(counterGrid);
        if (statsSection) observer.observe(statsSection);

        function landingApp() {
            return {
                dark: false,
                scrolled: false,
                stats: {
                    total_laporan:      {{ $stats['total_laporan'] }},
                    laporan_selesai:    {{ $stats['laporan_selesai'] }},
                    relawan_aktif:      {{ $stats['relawan_aktif'] }},
                    artikel:            {{ $stats['artikel'] }},
                    resolved_rate:      {{ $stats['resolved_rate'] }},
                    laporan_bulan_ini:  {{ $stats['laporan_bulan_ini'] }},
                },

                init() {
                    this.initDark();
                    this.initMap();
                    window.addEventListener('scroll', () => {
                        this.scrolled = window.scrollY > 20;
                    });
                    // Refresh stats tiap 30 detik
                    setInterval(() => this.fetchStats(), 30000);
                },

                initDark() {
                    const saved = localStorage.getItem('laporhijau-dark');
                    if (saved !== null) {
                        this.dark = saved === 'true';
                    } else {
                        this.dark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    }
                },

                toggleDark() {
                    this.dark = !this.dark;
                    localStorage.setItem('laporhijau-dark', this.dark);
                },

                async fetchStats() {
                    try {
                        const r = await fetch('/api/stats');
                        if (r.ok) {
                            this.stats = await r.json();
                            // Re-animate counters setelah data update
                            ['total_laporan','laporan_selesai','relawan_aktif','artikel','laporan_bulan_ini','resolved_rate'].forEach(key => {
                                const ids = {
                                    total_laporan:'cnt-laporan',
                                    laporan_selesai:'cnt-selesai',
                                    relawan_aktif:'cnt-relawan',
                                    artikel:'cnt-artikel',
                                    laporan_bulan_ini:'stat-bulan',
                                    resolved_rate:'stat-rate'
                                };
                                const el = document.getElementById(ids[key]);
                                if (el) {
                                    animateCounter(el, this.stats[key], 600);
                                    // Efek denyut (pulse) saat terupdate
                                    el.classList.add('scale-110', 'text-green-500');
                                    setTimeout(() => el.classList.remove('scale-110', 'text-green-500'), 1000);
                                }
                            });

                            // Perbarui hari ini labels
                            ['laporan_hari_ini','selesai_hari_ini','relawan_aktif_hari_ini','artikel_hari_ini'].forEach(key => {
                                const mapKey = key === 'relawan_aktif_hari_ini' ? 'relawan_hari_ini' : key;
                                const idsToday = {
                                    laporan_hari_ini:'cnt-laporan-today',
                                    selesai_hari_ini:'cnt-selesai-today',
                                    relawan_aktif_hari_ini:'cnt-relawan-today',
                                    artikel_hari_ini:'cnt-artikel-today'
                                };
                                const el = document.getElementById(idsToday[key]);
                                if (el) {
                                    const val = this.stats[mapKey] || 0;
                                    if (val > 0) {
                                        el.innerHTML = `+${val} baru hari ini`;
                                        el.classList.add('animate-pulse');
                                    } else {
                                        el.innerHTML = '&nbsp;';
                                        el.classList.remove('animate-pulse');
                                    }
                                }
                            });
                        }
                    } catch(e) {}
                },

                initMap() {
                    const map = L.map('home-map', { zoomControl: true, scrollWheelZoom: false })
                        .setView([-0.9471, 100.4172], 11);

                    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="https://carto.com/">CARTO</a>',
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

                    const statusLabels = {
                        pending:     '⏳ Pending',
                        verified:    '✅ Verified',
                        in_progress: '🔧 Ditangani',
                        resolved:    '🎉 Selesai',
                        rejected:    '❌ Ditolak',
                    };

                    const statusBadgeClasses = {
                        pending:     'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/40 dark:text-amber-400 dark:border-amber-900',
                        verified:    'bg-sky-50 text-sky-700 border-sky-200 dark:bg-sky-950/40 dark:text-sky-400 dark:border-sky-900',
                        in_progress: 'bg-orange-50 text-orange-700 border-orange-200 dark:bg-orange-950/40 dark:text-orange-400 dark:border-orange-900',
                        resolved:    'bg-green-50 text-green-700 border-green-200 dark:bg-green-950/40 dark:text-green-400 dark:border-green-900',
                        rejected:    'bg-red-50 text-red-700 border-red-200 dark:bg-red-950/40 dark:text-red-400 dark:border-red-900',
                    };

                    fetch('/api/map-data')
                        .then(r => r.json())
                        .then(reports => {
                            reports.forEach(rpt => {
                                if (!rpt.latitude || !rpt.longitude) return;
                                const color = statusColors[rpt.status] || '#6b7280';
                                const badgeClass = statusBadgeClasses[rpt.status] || 'bg-gray-50 text-gray-700 border-gray-200';
                                const badgeLabel = statusLabels[rpt.status] || rpt.status;

                                const icon = L.divIcon({
                                    html: `<div style="background:${color};width:14px;height:14px;border-radius:50%;border:2px solid white;box-shadow:0 2px 4px rgba(0,0,0,0.3)"></div>`,
                                    className: '',
                                    iconSize: [14, 14],
                                    iconAnchor: [7, 7],
                                });

                                const popupHtml = `
                                    <div class="p-2.5 max-w-[210px] font-sans">
                                        <div class="flex items-center gap-1.5 mb-1.5 flex-wrap">
                                            <span class="text-[9px] bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 px-2 py-0.5 rounded-full font-bold">
                                                ${rpt.category}
                                            </span>
                                            <span class="text-[9px] font-black px-2 py-0.5 rounded-full border ${badgeClass}">
                                                ${badgeLabel}
                                            </span>
                                        </div>
                                        <h4 class="font-extrabold text-gray-900 dark:text-white text-xs leading-snug mb-1">${rpt.title}</h4>
                                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mb-2 truncate">📍 ${rpt.address || '-'}</p>
                                        <a href="/laporan/${rpt.id}" class="block text-center w-full py-1 bg-green-600 hover:bg-green-700 active:bg-green-800 text-white font-extrabold text-[10px] rounded-md transition-colors" style="text-decoration:none;">
                                            Lihat Detail →
                                        </a>
                                    </div>
                                `;

                                L.marker([rpt.latitude, rpt.longitude], { icon })
                                    .addTo(map)
                                    .bindPopup(popupHtml);
                            });
                        }).catch(() => {});
                }
            }
        }

        function laporWhatsApp() {
            const whatsappNumber = "{{ env('WHATSAPP_NUMBER', '6281234567890') }}";
            
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        
                        const text = `Halo Admin LaporHijau! 🌿

Saya ingin melaporkan masalah lingkungan:

📍 *Lokasi:* ${lat}, ${lng}
🗺️ *Google Maps:* https://maps.google.com/?q=${lat},${lng}

📝 *Deskripsi masalah:*
[Mohon isi deskripsi masalah di sini]

📸 *Foto:* [Lampirkan foto masalah]

Terima kasih!`;
                        
                        const encodedText = encodeURIComponent(text);
                        window.open(`https://wa.me/${whatsappNumber}?text=${encodedText}`, '_blank');
                    },
                    function(error) {
                        // Lokasi ditolak atau error
                        const text = `Halo Admin LaporHijau! 🌿

Saya ingin melaporkan masalah lingkungan:

📍 *Lokasi:* [Mohon isi alamat lokasi]
📝 *Deskripsi:* [Mohon isi deskripsi]
📸 *Foto:* [Lampirkan foto]

Terima kasih!`;
                        const encodedText = encodeURIComponent(text);
                        window.open(`https://wa.me/${whatsappNumber}?text=${encodedText}`, '_blank');
                    }
                );
            } else {
                // Browser tidak mendukung geolocation
                const text = `Halo Admin LaporHijau! 🌿

Saya ingin melaporkan masalah lingkungan:

📍 *Lokasi:* [Mohon isi alamat lokasi]
📝 *Deskripsi:* [Mohon isi deskripsi]
📸 *Foto:* [Lampirkan foto]

Terima kasih!`;
                const encodedText = encodeURIComponent(text);
                window.open(`https://wa.me/${whatsappNumber}?text=${encodedText}`, '_blank');
            }
        }
    </script>
</body>
</html>
