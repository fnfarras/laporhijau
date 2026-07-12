<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} Relawan — @yield('title', 'Dashboard')</title>

    <!-- Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .sidebar-link { transition: all 0.15s ease; }
        .sidebar-link:hover, .sidebar-link.active {
            background: rgba(22,163,74,0.12);
            color: #16a34a;
        }
        .sidebar-link.active { font-weight: 700; }
    </style>
</head>
<body class="bg-gray-50 antialiased">

{{-- Mobile overlay --}}
<div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden" onclick="toggleSidebar()"></div>

<div class="flex min-h-screen">

    {{-- ── Sidebar ──────────────────────────────────────────────── --}}
    <aside id="sidebar" class="fixed top-0 left-0 h-full w-64 bg-white border-r border-gray-100 shadow-sm z-40 flex flex-col transform -translate-x-full lg:translate-x-0 transition-transform duration-300">

        {{-- Logo --}}
        <div class="px-5 py-4 border-b border-gray-100">
            <a href="{{ route('relawan.dashboard') }}" class="flex items-center gap-2">
                <x-app-logo size="md" />
            </a>
            <span class="mt-1 block text-[10px] font-bold text-green-600 uppercase tracking-widest px-0.5">Area Relawan</span>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">

            <p class="text-[10px] text-gray-400 px-3 mb-1 mt-1 uppercase tracking-widest font-bold">Tugas Saya</p>
            <a href="{{ route('relawan.dashboard') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-600 {{ request()->routeIs('relawan.dashboard') ? 'active' : '' }}">
                <span class="text-lg">🏠</span>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('relawan.antrian') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-600 {{ request()->routeIs('relawan.antrian') ? 'active' : '' }}">
                <span class="text-lg">📋</span>
                <span>Antrian Laporan</span>
                @php $pendingCount = \App\Models\Report::where('status','pending')->count(); @endphp
                @if($pendingCount > 0)
                    <span class="ml-auto bg-amber-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $pendingCount }}</span>
                @endif
            </a>
            <a href="{{ route('relawan.riwayat') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-600 {{ request()->routeIs('relawan.riwayat') ? 'active' : '' }}">
                <span class="text-lg">📜</span>
                <span>Riwayat Saya</span>
            </a>

            <p class="text-[10px] text-gray-400 px-3 mb-1 mt-4 uppercase tracking-widest font-bold">Komunitas</p>
            <a href="{{ route('artikel.index') }}" target="_blank"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-600">
                <span class="text-lg">📰</span>
                <span>Artikel ↗</span>
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
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-green-400 to-emerald-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-green-600 font-medium">⭐ {{ auth()->user()->fresh()->points }} poin</p>
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

    {{-- ── Main Content ──────────────────────────────────────────── --}}
    <div class="flex-1 lg:ml-64 flex flex-col min-h-screen">

        {{-- Topbar Mobile --}}
        <header class="lg:hidden sticky top-0 z-20 bg-white border-b border-gray-100 px-4 py-3 flex items-center gap-3">
            <button onclick="toggleSidebar()" class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <span class="font-bold text-gray-800">LaporHijau Relawan</span>
        </header>

        {{-- Flash messages --}}
        <div class="px-6 pt-4">
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 mb-4 flex items-start gap-2 text-sm">
                    <span class="mt-0.5 flex-shrink-0">✅</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if (session('info'))
                <div class="bg-blue-50 border border-blue-200 text-blue-800 rounded-xl px-4 py-3 mb-4 flex items-start gap-2 text-sm">
                    <span class="mt-0.5 flex-shrink-0">ℹ️</span>
                    <span>{{ session('info') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 mb-4 flex items-start gap-2 text-sm">
                    <span class="mt-0.5 flex-shrink-0">❌</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
        </div>

        {{-- Page Content --}}
        <main class="flex-1 px-4 sm:px-6 pb-8">
            {{ $slot }}
        </main>
    </div>
</div>

{{-- ── Modal: Tolak Laporan ─────────────────────────────────── --}}
<div id="reject-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/50">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <h3 class="text-base font-bold text-gray-900 mb-1">❌ Tolak Laporan</h3>
        <p id="modal-report-title" class="text-sm text-gray-500 mb-4"></p>
        <form id="reject-form" method="POST">
            @csrf
            <input type="hidden" name="action" value="reject">
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                Alasan Penolakan <span class="text-red-500">*</span>
            </label>
            <textarea
                name="reason"
                id="reject-reason"
                rows="4"
                placeholder="Jelaskan alasan penolakan laporan ini (min. 10 karakter)..."
                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-400 resize-none"
            ></textarea>
            <p id="reason-error" class="text-red-500 text-xs mt-1 hidden">Alasan minimal 10 karakter.</p>
            <div class="flex gap-3 mt-5">
                <button type="button" onclick="closeRejectModal()"
                    class="flex-1 py-2.5 border border-gray-200 text-gray-600 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="submit" id="reject-submit-btn"
                    class="flex-1 py-2.5 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-xl transition-colors">
                    Tolak Laporan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ── Modal: Konfirmasi Verifikasi ─────────────────────────── --}}
<div id="verify-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/50">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 text-center">
        <div class="text-5xl mb-3">✅</div>
        <h3 class="text-base font-bold text-gray-900 mb-1">Verifikasi Laporan?</h3>
        <p id="verify-modal-title" class="text-sm text-gray-500 mb-5"></p>
        <p class="text-xs text-green-600 bg-green-50 rounded-lg px-3 py-2 mb-5">
            Kamu mendapat <strong>+20 poin</strong> · Reporter mendapat <strong>+10 poin</strong>
        </p>
        <div class="flex gap-3">
            <button onclick="closeVerifyModal()"
                class="flex-1 py-2.5 border border-gray-200 text-gray-600 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <form id="verify-form" method="POST" class="flex-1">
                @csrf
                <button type="submit"
                    class="w-full py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-xl transition-colors">
                    Ya, Verifikasi
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // ── Sidebar toggle (mobile) ────────────────────────────────
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const isOpen  = !sidebar.classList.contains('-translate-x-full');
        sidebar.classList.toggle('-translate-x-full', isOpen);
        overlay.classList.toggle('hidden', isOpen);
    }

    // ── Reject Modal ──────────────────────────────────────────
    function openRejectModal(reportId, reportTitle, rejectUrl) {
        document.getElementById('modal-report-title').textContent = '"' + reportTitle + '"';
        document.getElementById('reject-form').action = rejectUrl;
        document.getElementById('reject-reason').value = '';
        document.getElementById('reason-error').classList.add('hidden');
        const modal = document.getElementById('reject-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeRejectModal() {
        const modal = document.getElementById('reject-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.getElementById('reject-form').addEventListener('submit', function(e) {
        const reason = document.getElementById('reject-reason').value.trim();
        if (reason.length < 10) {
            e.preventDefault();
            document.getElementById('reason-error').classList.remove('hidden');
        }
    });

    // ── Verify Modal ──────────────────────────────────────────
    function openVerifyModal(reportId, reportTitle, verifyUrl) {
        document.getElementById('verify-modal-title').textContent = '"' + reportTitle + '"';
        document.getElementById('verify-form').action = verifyUrl;
        const modal = document.getElementById('verify-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeVerifyModal() {
        const modal = document.getElementById('verify-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Tutup modal saat klik backdrop
    document.getElementById('reject-modal').addEventListener('click', function(e) {
        if (e.target === this) closeRejectModal();
    });
    document.getElementById('verify-modal').addEventListener('click', function(e) {
        if (e.target === this) closeVerifyModal();
    });
</script>

@stack('scripts')
</body>
</html>