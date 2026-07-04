<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="darkModeApp()"
      x-init="initDark()"
      :class="{ 'dark': dark }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'LaporHijau — Platform Pelaporan Lingkungan Komunitas')</title>

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        <!-- SEO Meta Tags -->
        <meta name="description" content="@yield('meta_description', 'LaporHijau — Platform civic tech untuk pelaporan, pemantauan, dan penanganan masalah lingkungan hidup secara kolaboratif di Indonesia.')">
        <meta name="keywords" content="laporan lingkungan, civic tech, lingkungan hidup, sampah, banjir, komunitas, relawan">
        <meta name="author" content="LaporHijau">
        <meta name="robots" content="index, follow">
        <link rel="canonical" href="{{ url()->current() }}">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="@yield('title', 'LaporHijau — Platform Pelaporan Lingkungan Komunitas')">
        <meta property="og:description" content="@yield('meta_description', 'Platform civic tech untuk pelaporan masalah lingkungan di Indonesia.')">
        <meta property="og:image" content="{{ asset('og-image.png') }}">
        <meta property="og:site_name" content="LaporHijau">
        <meta property="og:locale" content="id_ID">

        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="@yield('title', 'LaporHijau')">
        <meta name="twitter:description" content="@yield('meta_description', 'Platform pelaporan masalah lingkungan Indonesia.')">

        <!-- Plus Jakarta Sans — design system LaporHijau -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- App CSS & JS (Vite) -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Extra styles (Leaflet, dll) -->
        @stack('styles')

    </head>
    <body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 transition-colors">
                    <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Flash messages are now handled by Custom Toast Component (Fitur 6) -->

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- Extra scripts (Leaflet, dll) -->
        @stack('scripts')

        <!-- Toast Notification Stack Component (Fitur 6) -->
        <div x-data="toastComponent()" 
             @add-toast.window="add($event.detail.message, $event.detail.type)"
             class="fixed bottom-5 right-5 z-[9999] flex flex-col gap-3 w-full max-w-sm pointer-events-none">
             
            <template x-for="toast in toasts" :key="toast.id">
                <div class="pointer-events-auto relative overflow-hidden bg-white dark:bg-gray-800 border rounded-2xl shadow-xl p-4.5 flex items-start gap-3 w-full transition-all duration-300 transform translate-y-0"
                     :class="{
                         'border-green-200 dark:border-green-800/60': toast.type === 'success',
                         'border-red-200 dark:border-red-800/60': toast.type === 'error',
                         'border-amber-200 dark:border-amber-800/60': toast.type === 'warning',
                         'border-blue-200 dark:border-blue-800/60': toast.type === 'info'
                     }"
                     x-show="toast.show"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-x-12"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-x-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-x-12 scale-90">
                     
                    <!-- Icon -->
                    <div class="text-xl flex-shrink-0">
                        <span x-show="toast.type === 'success'">✅</span>
                        <span x-show="toast.type === 'error'">❌</span>
                        <span x-show="toast.type === 'warning'">⚠️</span>
                        <span x-show="toast.type === 'info'">ℹ️</span>
                    </div>
                    
                    <!-- Message Body -->
                    <div class="flex-1 min-w-0">
                        <p class="text-[10px] font-bold leading-tight uppercase tracking-wider"
                           :class="{
                               'text-green-600': toast.type === 'success',
                               'text-red-600': toast.type === 'error',
                               'text-amber-600': toast.type === 'warning',
                               'text-blue-600': toast.type === 'info'
                           }"
                           x-text="toast.type === 'success' ? 'Sukses' : (toast.type === 'error' ? 'Gagal' : (toast.type === 'warning' ? 'Peringatan' : 'Info'))"></p>
                        <p class="text-xs text-gray-600 dark:text-gray-300 mt-1 font-medium leading-relaxed" x-text="toast.message"></p>
                    </div>
                    
                    <!-- Dismiss Button -->
                    <button @click="remove(toast.id)" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-xs ml-2 flex-shrink-0">✕</button>
                    
                    <!-- Progress Bar (countdown indicator) -->
                    <div class="absolute bottom-0 left-0 h-1 bg-gray-100 dark:bg-gray-700/80 w-full">
                        <div class="h-full transition-all duration-[4000ms] ease-linear"
                             :class="{
                                 'bg-green-500': toast.type === 'success',
                                 'bg-red-500': toast.type === 'error',
                                 'bg-amber-500': toast.type === 'warning',
                                 'bg-blue-500': toast.type === 'info'
                             }"
                             :style="toast.progressStyle"></div>
                    </div>
                </div>
            </template>
        </div>

        {{-- Scroll to Top Button (Fitur Wow Premium) --}}
        <button x-data="{ show: false }"
                x-on:scroll.window="show = window.pageYOffset > 300"
                x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-10"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-10"
                @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
                class="fixed bottom-6 right-6 z-40 w-11 h-11 bg-green-600 hover:bg-green-700 active:bg-green-800 text-white rounded-full flex items-center justify-center shadow-lg transition-all no-print hover:scale-110 transform"
                title="Scroll ke Atas"
                style="display: none;">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
            </svg>
        </button>

        <!-- Dark Mode, Toast & Ripple Scripts -->
        <script>
            // Ripple Effect Global Handler
            document.addEventListener('click', function(e) {
                const target = e.target.closest('button, .btn, a.bg-green-600, a.bg-sky-600, .btn-ripple');
                if (!target) return;
                
                // Exclude print mode click
                if (window.matchMedia('(print)').matches) return;
                
                target.classList.add('ripple');
                
                const rect = target.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                
                const ripple = document.createElement('span');
                ripple.style.width = ripple.style.height = `${size}px`;
                ripple.style.left = `${e.clientX - rect.left - size/2}px`;
                ripple.style.top = `${e.clientY - rect.top - size/2}px`;
                ripple.classList.add('ripple-effect');
                
                const oldRipple = target.querySelector('.ripple-effect');
                if (oldRipple) oldRipple.remove();
                
                target.appendChild(ripple);
                
                setTimeout(() => ripple.remove(), 600);
            });

            function darkModeApp() {
                return {
                    dark: false,
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
                    }
                }
            }

            function toastComponent() {
                return {
                    toasts: [],
                    add(message, type = 'success') {
                        if (this.toasts.length >= 3) {
                            this.toasts.shift();
                        }
                        
                        const id = Date.now() + Math.random();
                        const toast = {
                            id,
                            message,
                            type,
                            show: true,
                            progressStyle: 'width: 100%'
                        };
                        this.toasts.push(toast);
                        
                        // Start progress bar countdown
                        setTimeout(() => {
                            toast.progressStyle = 'width: 0%';
                        }, 50);
                        
                        // Auto dismiss after 4 seconds
                        setTimeout(() => {
                            this.remove(id);
                        }, 4000);
                    },
                    remove(id) {
                        const index = this.toasts.findIndex(t => t.id === id);
                        if (index !== -1) {
                            this.toasts[index].show = false;
                            setTimeout(() => {
                                this.toasts = this.toasts.filter(t => t.id !== id);
                            }, 300);
                        }
                    }
                }
            }

            // Trigger flash messages jika ada dari Laravel session
            document.addEventListener('DOMContentLoaded', () => {
                @if (session('success'))
                    window.dispatchEvent(new CustomEvent('add-toast', { detail: { message: "{{ session('success') }}", type: 'success' } }));
                @endif
                @if (session('error'))
                    window.dispatchEvent(new CustomEvent('add-toast', { detail: { message: "{{ session('error') }}", type: 'error' } }));
                @endif
                @if (session('warning'))
                    window.dispatchEvent(new CustomEvent('add-toast', { detail: { message: "{{ session('warning') }}", type: 'warning' } }));
                @endif
                @if (session('info'))
                    window.dispatchEvent(new CustomEvent('add-toast', { detail: { message: "{{ session('info') }}", type: 'info' } }));
                @endif
            });
        </script>

        {{-- Bottom Navigation Bar (Mobile Only) --}}
        @if(!request()->routeIs('laporan.create'))
        <div class="sm:hidden fixed bottom-0 left-0 right-0 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-t border-gray-150 dark:border-slate-700/80 z-40 py-2.5 px-4 flex justify-between items-center no-print shadow-lg">
            {{-- Beranda --}}
            <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 flex-1 text-center {{ request()->routeIs('home') ? 'text-green-600 dark:text-green-400 font-bold' : 'text-gray-400 dark:text-gray-500' }}">
                <span class="text-lg">🏠</span>
                <span class="text-[9px]">Beranda</span>
            </a>

            {{-- Peta --}}
            <a href="{{ route('peta') }}" class="flex flex-col items-center gap-1 flex-1 text-center {{ request()->routeIs('peta') ? 'text-green-600 dark:text-green-400 font-bold' : 'text-gray-400 dark:text-gray-500' }}">
                <span class="text-lg">🗺️</span>
                <span class="text-[9px]">Peta</span>
            </a>

            {{-- Lapor+ (Elevated Button) --}}
            <a href="{{ route('laporan.create') }}" class="flex flex-col items-center justify-center -mt-6 w-14 h-14 bg-green-600 hover:bg-green-700 text-white rounded-full shadow-lg border-4 border-gray-50 dark:border-slate-900 transition-all hover:scale-110 active:scale-95 z-50">
                <span class="text-xl font-bold">+</span>
            </a>

            {{-- Event --}}
            <a href="{{ route('event.index') }}" class="flex flex-col items-center gap-1 flex-1 text-center {{ request()->routeIs('event.*') ? 'text-green-600 dark:text-green-400 font-bold' : 'text-gray-400 dark:text-gray-500' }}">
                <span class="text-lg">📅</span>
                <span class="text-[9px]">Event</span>
            </a>

            {{-- Profil --}}
            @auth
                <a href="{{ route('profil', auth()->user()) }}" class="flex flex-col items-center gap-1 flex-1 text-center {{ request()->routeIs('profil') ? 'text-green-600 dark:text-green-400 font-bold' : 'text-gray-400 dark:text-gray-500' }}">
                    <span class="text-lg">👤</span>
                    <span class="text-[9px]">Profil</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="flex flex-col items-center gap-1 flex-1 text-center {{ request()->routeIs('login') ? 'text-green-600 dark:text-green-400 font-bold' : 'text-gray-400 dark:text-gray-500' }}">
                    <span class="text-lg">👤</span>
                    <span class="text-[9px]">Masuk</span>
                </a>
            @endauth
        </div>
        @endif
    </body>
</html>
