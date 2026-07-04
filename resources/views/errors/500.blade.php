<!DOCTYPE html>
<html lang="id"
      x-data="darkModeApp()"
      x-init="initDark()"
      :class="{ 'dark': dark }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 Kesalahan Server Internal — LaporHijau</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 dark:bg-slate-900 text-gray-900 dark:text-gray-100 transition-colors duration-300 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full text-center space-y-6">
        
        {{-- SVG gears or broken tools --}}
        <div class="w-48 h-48 mx-auto relative">
            <svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
                <!-- Gir 1 (Besar) -->
                <circle cx="100" cy="100" r="40" stroke="#64748b" stroke-width="8" stroke-dasharray="12 8" class="animate-spin" style="animation-duration: 10s;"/>
                <circle cx="100" cy="100" r="25" fill="#475569"/>
                <circle cx="100" cy="100" r="10" fill="#f8fafc" class="dark:fill-slate-900"/>
                
                <!-- Gir 2 (Kecil) -->
                <circle cx="150" cy="140" r="25" stroke="#16a34a" stroke-width="6" stroke-dasharray="8 6" class="animate-spin" style="animation-duration: 6s; animation-direction: reverse;"/>
                <circle cx="150" cy="140" r="15" fill="#15803d"/>
                <circle cx="150" cy="140" r="6" fill="#f8fafc" class="dark:fill-slate-900"/>
                
                <!-- Petir/Kerusakan -->
                <path d="M90 40L110 70L85 90L120 130" stroke="#ef4444" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" class="animate-pulse"/>
            </svg>
        </div>

        <div class="space-y-2">
            <h1 class="text-7xl font-black text-red-500 tracking-tight">500</h1>
            <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white">Ups, ada yang tidak beres</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto leading-relaxed">
                Server kami mengalami masalah internal saat memproses permintaan ini. 
                Kami sedang berupaya memperbaikinya secepat mungkin. Silakan coba muat ulang halaman.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
            <button onclick="window.location.reload()" 
                    class="w-full sm:w-auto px-6 py-3 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-xl shadow-md transition-all">
                Muat Ulang Halaman
            </button>
            <a href="/" 
               class="w-full sm:w-auto px-6 py-3 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm font-bold rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-all">
                Kembali ke Beranda
            </a>
        </div>

    </div>

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
