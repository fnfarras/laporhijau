<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} Admin — @yield('title', 'Dashboard')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .sidebar-link { transition: all 0.15s ease; }
        .sidebar-link:hover, .sidebar-link.active {
            background: rgba(239,68,68,0.1);
            color: #ef4444;
        }
        .sidebar-link.active { font-weight: 700; color: #dc2626; }
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
                <div class="w-8 h-8 bg-gradient-to-br from-red-500 to-rose-600 rounded-lg flex items-center justify-center">
                    <span class="text-white text-base">⚙️</span>
                </div>
                <div>
                    <span class="font-bold text-gray-900 text-sm">LaporHijau</span>
                    <span class="block text-xs text-red-500 font-semibold">Administrator</span>
                </div>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-3 py-4 overflow-y-auto space-y-0.5">

            {{-- Overview --}}
            <p class="text-[10px] text-gray-400 px-3 mb-1 mt-1 uppercase tracking-widest font-bold">Overview</p>
            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-600 font-medium {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="text-lg">📊</span> Dashboard
            </a>

            {{-- Manajemen Data --}}
            <p class="text-[10px] text-gray-400 px-3 mb-1 mt-4 uppercase tracking-widest font-bold">Manajemen</p>
            <a href="{{ route('admin.users.index') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-600 font-medium {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <span class="text-lg">👥</span> Pengguna
                @php $totalUsers = \App\Models\User::count(); @endphp
                <span class="ml-auto text-xs font-bold text-gray-400">{{ $totalUsers }}</span>
            </a>
            <a href="{{ route('admin.categories.index') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-600 font-medium {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <span class="text-lg">📂</span> Kategori
            </a>
            <a href="{{ route('admin.rewards.index') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-600 font-medium {{ request()->routeIs('admin.rewards.*') ? 'active' : '' }}">
                <span class="text-lg">🎁</span> Hadiah
            </a>

            {{-- Konten --}}
            <p class="text-[10px] text-gray-400 px-3 mb-1 mt-4 uppercase tracking-widest font-bold">Konten</p>
            <a href="{{ route('artikel.index') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-600 font-medium">
                <span class="text-lg">📰</span> Artikel
            </a>
            <a href="{{ route('event.index') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-600 font-medium">
                <span class="text-lg">🗓️</span> Event Aksi
            </a>
            <a href="{{ route('open-data') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-600 font-medium">
                <span class="text-lg">📈</span> Open Data
            </a>

            {{-- Area Lain --}}
            <p class="text-[10px] text-gray-400 px-3 mb-1 mt-4 uppercase tracking-widest font-bold">Area Role</p>
            <a href="{{ route('relawan.dashboard') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-600 font-medium">
                <span class="text-lg">🌿</span> Area Relawan
            </a>
            <a href="{{ route('pemerintah.dashboard') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-600 font-medium">
                <span class="text-lg">🏛️</span> Area Pemerintah
            </a>
            <a href="{{ route('leaderboard') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-600 font-medium">
                <span class="text-lg">🏆</span> Leaderboard
            </a>
        </nav>

        {{-- User Info --}}
        <div class="px-4 py-4 border-t border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-red-100 rounded-full flex items-center justify-center text-red-600 font-bold text-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-red-500 font-medium">Admin</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit" class="w-full text-xs text-gray-500 hover:text-red-500 transition text-left">
                    → Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- ── Main Content ────────────────────────────────────────── --}}
    <div class="flex-1 lg:ml-64 flex flex-col min-h-screen">

        {{-- Topbar --}}
        <header class="bg-white border-b border-gray-100 px-4 lg:px-8 py-3.5 flex items-center justify-between sticky top-0 z-20">
            <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div class="ml-auto flex items-center gap-2">
                <span class="text-xs bg-red-100 text-red-600 font-bold px-2.5 py-1 rounded-full">⚙️ Administrator</span>
            </div>
        </header>

        <main class="flex-1 px-4 lg:px-8 py-6 max-w-7xl w-full mx-auto">
            @if(session('success'))
                <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm flex items-center gap-2">
                    ✅ {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm flex items-center gap-2">
                    ❌ {{ session('error') }}
                </div>
            @endif
            {{ $slot }}
        </main>
    </div>
</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }
</script>
@stack('scripts')
</body>
</html>
