<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 transition-colors duration-300">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="/" class="flex items-center gap-2">
                      <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center text-white font-bold text-sm">
                        LH
                      </div>
                      <span class="font-bold text-green-600 text-lg">
                        LaporHijau
                      </span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-6 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        Dashboard
                    </x-nav-link>
                    <x-nav-link :href="route('peta')" :active="request()->routeIs('peta')">
                        🗺 Peta
                    </x-nav-link>
                    <x-nav-link :href="route('event.index')" :active="request()->routeIs('event.*')">
                        🌿 Event
                    </x-nav-link>
                    <x-nav-link :href="route('cara-lapor')" :active="request()->routeIs('cara-lapor')">
                        📖 Cara Lapor
                    </x-nav-link>
                    <x-nav-link :href="route('artikel.index')" :active="request()->routeIs('artikel.*')">
                        📚 Artikel
                    </x-nav-link>
                    <x-nav-link :href="route('leaderboard')" :active="request()->routeIs('leaderboard')">
                        🏆 Leaderboard
                    </x-nav-link>
                    <x-nav-link :href="route('hadiah')" :active="request()->routeIs('hadiah')">
                        🎁 Hadiah
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown / Guest Auth Links -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">

                {{-- ── Dark Mode Toggle ──────────────────────────── --}}
                <button @click="toggleDark()"
                        title="Toggle Dark Mode"
                        class="relative w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-200
                               bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-600 dark:text-amber-300
                               border border-gray-200 dark:border-gray-600">
                    <!-- Sun icon (shown in dark mode) -->
                    <svg x-show="dark" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                    </svg>
                    <!-- Moon icon (shown in light mode) -->
                    <svg x-show="!dark" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>

                @auth
                    {{-- ── Pusat Notifikasi (Fitur Wow Notifikasi Pintar) ── --}}
                    @php
                        $unreadCount = auth()->user()->notifications()->whereNull('read_at')->count();
                        $recentNotifs = auth()->user()->notifications()->latest()->limit(5)->get();
                    @endphp
                    <div class="relative" x-data="{ openNotif: false }" @click.outside="openNotif = false">
                        <button @click="openNotif = !openNotif" 
                                class="relative w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-200
                                       bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300
                                       border border-gray-200 dark:border-gray-600">
                            <!-- Bell Icon -->
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            @if ($unreadCount > 0)
                                <span class="absolute top-1.5 right-1.5 flex h-2.5 w-2.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                                </span>
                            @endif
                        </button>

                        {{-- Dropdown Container --}}
                        <div x-show="openNotif"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-80 bg-white/95 dark:bg-gray-800/95 backdrop-blur-md rounded-2xl border border-gray-100 dark:border-gray-700 shadow-2xl z-50 p-4 font-sans text-left"
                             style="display: none;">
                            
                            <div class="flex items-center justify-between pb-2.5 mb-3 border-b border-gray-100 dark:border-gray-700">
                                <h4 class="font-extrabold text-sm text-gray-900 dark:text-white">🔔 Notifikasi</h4>
                                @if ($unreadCount > 0)
                                    <form action="{{ route('notifications.read-all') }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-[10px] text-green-600 dark:text-green-400 font-bold hover:underline">Tandai semua terbaca</button>
                                    </form>
                                @endif
                            </div>

                            <div class="space-y-3 max-h-[280px] overflow-y-auto pr-1">
                                @if ($recentNotifs->isEmpty())
                                    <p class="text-xs text-gray-400 text-center py-6 italic">Belum ada notifikasi baru.</p>
                                @else
                                    @foreach ($recentNotifs as $notif)
                                        @php
                                            $emoji = match($notif->type) {
                                                'laporan_diverifikasi' => '✅',
                                                'laporan_ditolak'      => '❌',
                                                'laporan_diproses'     => '🔧',
                                                'laporan_diselesaikan' => '🎉',
                                                default                => '🔔',
                                            };
                                            $targetUrl = isset($notif->data['report_id']) ? route('laporan.show', $notif->data['report_id']) : '#';
                                        @endphp
                                        <a href="{{ $targetUrl }}" class="flex gap-2.5 p-2 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors {{ !$notif->isRead() ? 'bg-green-50/30 dark:bg-green-950/10' : '' }} block" style="text-decoration: none;">
                                            <span class="text-base flex-shrink-0 mt-0.5">{{ $emoji }}</span>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs text-gray-700 dark:text-gray-300 leading-snug {{ !$notif->isRead() ? 'font-bold' : '' }}">
                                                    {{ $notif->data['message'] ?? 'Notifikasi baru' }}
                                                </p>
                                                <p class="text-[9px] text-gray-400 mt-1">
                                                    {{ $notif->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Poin Widget --}}
                    <a href="{{ route('profil', auth()->user()) }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-50 dark:bg-green-900/40 hover:bg-green-100 dark:hover:bg-green-900/60 text-green-700 dark:text-green-400 text-xs font-bold rounded-full border border-green-200 dark:border-green-700 transition-colors">
                        ⭐ {{ number_format(auth()->user()->fresh()->points) }} poin
                    </a>
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-300 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-100 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profil', auth()->user())">
                                👤 Profil Saya
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('dashboard')">
                                📊 Dashboard
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('profile.edit')">
                                ⚙️ Pengaturan
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    🚪 Keluar
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="space-x-4">
                        <a href="{{ route('login') }}" class="text-sm text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white font-semibold">{{ __('Log in') }}</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-sm text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white font-semibold">{{ __('Register') }}</a>
                        @endif
                    </div>
                @endauth
            </div>

            <!-- Hamburger + Dark Toggle Mobile -->
            <div class="-me-2 flex items-center gap-1 sm:hidden">
                {{-- Mobile Dark Toggle --}}
                <button @click="toggleDark()"
                        class="p-2 rounded-lg text-gray-400 dark:text-amber-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <svg x-show="dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                    </svg>
                    <svg x-show="!dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>

                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-300 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Full Screen Overlay Menu with Slide Animation) -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="fixed inset-0 top-16 bg-white/95 dark:bg-slate-900/95 backdrop-blur-lg z-30 sm:hidden overflow-y-auto flex flex-col justify-between pb-24"
         style="display: none;">
         
         <div class="pt-4 pb-3 space-y-2 px-4">
             @auth
                 <!-- User Points in Mobile Menu -->
                 <div class="mb-4 p-4 bg-green-50/50 dark:bg-green-950/20 border border-green-150 dark:border-green-800/40 rounded-2xl flex items-center justify-between">
                     <span class="text-xs font-bold text-gray-500 dark:text-gray-400">Total Poin Kontribusi</span>
                     <span class="text-sm font-black text-green-700 dark:text-green-400">⭐ {{ number_format(auth()->user()->fresh()->points) }} Poin</span>
                 </div>
             @endauth
             
             <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                 📊 Dashboard
             </x-responsive-nav-link>
             <x-responsive-nav-link :href="route('peta')" :active="request()->routeIs('peta')">
                 🗺 Peta Interaktif
             </x-responsive-nav-link>
             <x-responsive-nav-link :href="route('event.index')" :active="request()->routeIs('event.*')">
                 🌿 Event Komunitas
             </x-responsive-nav-link>
             <x-responsive-nav-link :href="route('cara-lapor')" :active="request()->routeIs('cara-lapor')">
                 📖 Cara Lapor
             </x-responsive-nav-link>
             <x-responsive-nav-link :href="route('artikel.index')" :active="request()->routeIs('artikel.*')">
                 📚 Artikel Edukasi
             </x-responsive-nav-link>
             <x-responsive-nav-link :href="route('leaderboard')" :active="request()->routeIs('leaderboard')">
                 🏆 Leaderboard
             </x-responsive-nav-link>
             <x-responsive-nav-link :href="route('hadiah')" :active="request()->routeIs('hadiah')">
                 🎁 Tukar Hadiah
             </x-responsive-nav-link>
             <x-responsive-nav-link :href="route('tentang')" :active="request()->routeIs('tentang')">
                 ℹ️ Tentang LaporHijau
             </x-responsive-nav-link>
         </div>

         <!-- Responsive Settings Options -->
         <div class="pt-4 pb-4 border-t border-gray-150 dark:border-slate-800 bg-white/50 dark:bg-slate-900/50 px-6">
             @auth
                 <div>
                     <div class="font-extrabold text-base text-gray-800 dark:text-gray-100">{{ Auth::user()->name }}</div>
                     <div class="font-medium text-xs text-gray-450 dark:text-gray-400 mt-0.5">{{ Auth::user()->email }}</div>
                 </div>

                 <div class="mt-4 space-y-2">
                     <a href="{{ route('profile.edit') }}" class="block text-sm font-bold text-gray-600 dark:text-gray-300 hover:text-green-600">⚙️ Pengaturan Profil</a>

                     <form method="POST" action="{{ route('logout') }}" class="mt-2">
                         @csrf
                         <button type="submit" class="text-sm font-bold text-red-600 dark:text-red-400 hover:underline text-left">
                             🚪 Keluar Akun
                         </button>
                     </form>
                 </div>
             @else
                 <div class="space-y-3">
                     <a href="{{ route('login') }}" class="block text-center py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl text-xs transition-colors shadow-sm">{{ __('Log in') }}</a>
                     @if (Route::has('register'))
                         <a href="{{ route('register') }}" class="block text-center py-3 border border-gray-250 dark:border-slate-700 font-bold text-gray-700 dark:text-gray-300 rounded-xl text-xs hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors">{{ __('Register') }}</a>
                     @endif
                 </div>
             @endauth
         </div>
    </div>
</nav>
