<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} Pemerintah — @yield('title', 'Dashboard')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .sidebar-link { transition: all 0.15s ease; }
        .sidebar-link:hover, .sidebar-link.active {
            background: rgba(14,165,233,0.1);
            color: #0ea5e9;
        }
        .sidebar-link.active { font-weight: 700; color: #0284c7; }
    </style>
</head>
<body class="bg-slate-50 antialiased">

<div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden" onclick="toggleSidebar()"></div>

<div class="flex min-h-screen">

    {{-- ── Sidebar ───────────────────────────────────────────── --}}
    <aside id="sidebar" class="fixed top-0 left-0 h-full w-64 bg-white border-r border-gray-100 shadow-sm z-40 flex flex-col transform -translate-x-full lg:translate-x-0 transition-transform duration-300">

        {{-- Logo --}}
        <div class="px-6 py-5 border-b border-gray-100">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                    <span class="text-white text-base">🌿</span>
                </div>
                <div>
                    <p class="font-bold text-gray-900 text-sm leading-tight">LaporHijau</p>
                    <p class="text-xs text-sky-600 font-medium">Area Pemerintah</p>
                </div>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">

            <p class="text-[10px] text-gray-400 px-3 mb-1 mt-1 uppercase tracking-widest font-bold">Tugas Saya</p>
            <a href="{{ route('pemerintah.dashboard') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-600 {{ request()->routeIs('pemerintah.dashboard') ? 'active' : '' }}">
                <span class="text-lg">📊</span>
                <span>Dashboard & Grafik</span>
            </a>
            <a href="{{ route('pemerintah.laporan') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-600 {{ request()->routeIs('pemerintah.laporan') ? 'active' : '' }}">
                <span class="text-lg">📋</span>
                <span>Kelola Laporan</span>
                @php
                    $actionCount = \App\Models\Report::whereIn('status',['verified','in_progress'])->count();
                @endphp
                @if($actionCount > 0)
                    <span class="ml-auto bg-sky-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $actionCount }}</span>
                @endif
            </a>

            <p class="text-[10px] text-gray-400 px-3 mb-1 mt-4 uppercase tracking-widest font-bold">Aksi Edukasi</p>
            <a href="{{ route('artikel.create') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-600 {{ request()->routeIs('artikel.create') ? 'active' : '' }}">
                <span class="text-lg">✍️</span>
                <span>Tulis Artikel</span>
            </a>
            <a href="{{ route('event.create') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-600 {{ request()->routeIs('event.create') ? 'active' : '' }}">
                <span class="text-lg">📅</span>
                <span>Buat Event</span>
            </a>

            <p class="text-[10px] text-gray-400 px-3 mb-1 mt-4 uppercase tracking-widest font-bold">Komunitas</p>
            <a href="{{ route('open-data') }}" target="_blank"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-600">
                <span class="text-lg">📈</span>
                <span>Open Data ↗</span>
            </a>
            <a href="{{ route('event.index') }}" target="_blank"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-600">
                <span class="text-lg">🗓️</span>
                <span>Event Aksi ↗</span>
            </a>
            <a href="{{ route('leaderboard') }}" target="_blank"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-600">
                <span class="text-lg">🏆</span>
                <span>Leaderboard ↗</span>
            </a>
        </nav>

        {{-- User info --}}
        <div class="px-4 py-4 border-t border-gray-100">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-sky-400 to-blue-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-sky-600 font-medium">Pejabat Pemerintah</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left text-xs text-gray-400 hover:text-red-500 px-3 py-1.5 rounded-lg hover:bg-red-50 transition-colors flex items-center gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- ── Main Content ─────────────────────────────────────── --}}
    <div class="flex-1 lg:ml-64 flex flex-col min-h-screen">

        {{-- Mobile topbar --}}
        <header class="lg:hidden sticky top-0 z-20 bg-white border-b border-gray-100 px-4 py-3 flex items-center gap-3">
            <button onclick="toggleSidebar()" class="p-2 rounded-lg hover:bg-gray-100">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <span class="font-bold text-gray-800">LaporHijau Pemerintah</span>
        </header>

        {{-- Flash messages --}}
        <div class="px-6 pt-4">
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 mb-4 flex items-start gap-2 text-sm">
                    <span class="flex-shrink-0 mt-0.5">✅</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 mb-4 flex items-start gap-2 text-sm">
                    <span class="flex-shrink-0 mt-0.5">❌</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
        </div>

        <main class="flex-1 px-4 sm:px-6 pb-8">
            {{ $slot }}
        </main>
    </div>
</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const isOpen  = !sidebar.classList.contains('-translate-x-full');
        sidebar.classList.toggle('-translate-x-full', isOpen);
        overlay.classList.toggle('hidden', isOpen);
    }
</script>

@stack('scripts')
</body>
</html>
