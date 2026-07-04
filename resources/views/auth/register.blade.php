<!DOCTYPE html>
<html lang="id"
      x-data="darkModeApp()"
      x-init="initDark()"
      :class="{ 'dark': dark }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun — LaporHijau</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen transition-colors duration-300">

    <div class="min-h-screen flex flex-col md:flex-row">
        
        {{-- ── Kolom Kiri: Panel Branding (40% Desktop) ── --}}
        <div class="w-full md:w-[40%] bg-gradient-to-br from-green-600 to-emerald-700 text-white p-8 md:p-12 flex flex-col justify-between relative overflow-hidden">
            {{-- Background subtle overlay pattern --}}
            <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
            
            {{-- Back to Home --}}
            <div class="relative z-10">
                <a href="/" class="inline-flex items-center gap-2 text-xs font-bold text-white/80 hover:text-white transition-colors bg-white/10 backdrop-blur px-3 py-2 rounded-xl">
                    ← Kembali ke Beranda
                </a>
            </div>

            {{-- Branding Tengah --}}
            <div class="my-auto py-12 relative z-10 space-y-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-2xl shadow-lg">
                        🌿
                    </div>
                    <span class="font-black text-2xl tracking-tight text-white">LaporHijau</span>
                </div>
                
                <h2 class="text-3xl font-extrabold leading-tight">
                    Bersama Jaga Lingkungan Indonesia 🌿
                </h2>
                
                <ul class="space-y-3.5 text-sm font-medium text-white/95">
                    <li class="flex items-start gap-2.5">
                        <span class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-xs flex-shrink-0">✓</span>
                        <span>Laporkan masalah lingkungan mudah & cepat</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-xs flex-shrink-0">✓</span>
                        <span>Pantau penanganan secara transparan</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-xs flex-shrink-0">✓</span>
                        <span>Dapatkan poin & badge kontribusi</span>
                    </li>
                </ul>
            </div>

            {{-- Footer info --}}
            <div class="relative z-10 mt-auto pt-6 border-t border-white/10 flex items-center justify-between text-xs text-white/70">
                <span>© 2026 LaporHijau. All rights reserved.</span>
                <div class="flex gap-2">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.477 2 12c0 4.42 2.865 8.166 6.839 9.489.5.092.682-.217.682-.482 0-.237-.008-.866-.013-1.7-2.782.603-3.369-1.34-3.369-1.34-.454-1.156-1.11-1.462-1.11-1.462-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.831.092-.646.35-1.086.636-1.336-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.203 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.743 0 .267.18.579.688.481C19.137 20.162 22 16.418 22 12c0-5.523-4.477-10-10-10z"/></svg>
                </div>
            </div>
        </div>

        {{-- ── Kolom Kanan: Form Register (60% Desktop) ── --}}
        <div class="w-full md:w-[60%] bg-white dark:bg-gray-800 p-8 md:p-16 flex flex-col justify-center relative">
            <div class="max-w-md w-full mx-auto space-y-8">
                
                {{-- Header Form --}}
                <div class="space-y-2">
                    <h1 class="text-3xl font-black text-gray-900 dark:text-white">Daftar Akun Baru 🌿</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Buat akun untuk berkontribusi dalam menjaga lingkungan kita</p>
                </div>

                {{-- Form --}}
                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    {{-- Nama --}}
                    <div class="space-y-1.5">
                        <label for="name" class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Nama Lengkap</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400">👤</span>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                                   placeholder="Ahmad Fauzi"
                                   class="w-full pl-10 pr-4 py-2.5 border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                        </div>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="space-y-1.5">
                        <label for="email" class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Alamat Email</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400">✉️</span>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                   placeholder="nama@email.com"
                                   class="w-full pl-10 pr-4 py-2.5 border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                        </div>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="space-y-1.5">
                        <label for="password" class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Kata Sandi</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400">🔑</span>
                            <input id="password" type="password" name="password" required autocomplete="new-password"
                                   placeholder="••••••••"
                                   class="w-full pl-10 pr-4 py-2.5 border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div class="space-y-1.5">
                        <label for="password_confirmation" class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Konfirmasi Kata Sandi</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400">🔑</span>
                            <input id="password_confirmation" type="password" name="password_confirmation" required
                                   placeholder="••••••••"
                                   class="w-full pl-10 pr-4 py-2.5 border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                        </div>
                        @error('password_confirmation')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit"
                            class="w-full py-3 px-4 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl text-sm transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2 mt-2">
                        Daftar Akun Baru →
                    </button>
                </form>

                {{-- Footer Link --}}
                <div class="text-center pt-4 border-t border-gray-100 dark:border-gray-700">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Sudah punya akun LaporHijau? 
                        <a href="{{ route('login') }}" class="text-green-600 hover:text-green-700 font-bold ml-1">Masuk sekarang</a>
                    </p>
                </div>

            </div>
        </div>

    </div>

    {{-- Dark mode script init --}}
    <script>
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
                }
            }
        }
    </script>
</body>
</html>
