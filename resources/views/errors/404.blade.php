<!DOCTYPE html>
<html lang="id"
      x-data="darkModeApp()"
      x-init="initDark()"
      :class="{ 'dark': dark }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Halaman Tidak Ditemukan — LaporHijau</title>
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
        
        {{-- SVG Tree with falling leaves --}}
        <div class="w-48 h-48 mx-auto relative">
            <svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
                <!-- Tanah -->
                <path d="M40 160C80 155 120 155 160 160" stroke="#334155" stroke-width="4" stroke-linecap="round" class="dark:stroke-slate-600"/>
                
                <!-- Batang Pohon -->
                <path d="M100 160V100M100 120L80 100M100 110L120 95" stroke="#78350f" stroke-width="6" stroke-linecap="round"/>
                
                <!-- Daun Berguguran -->
                <!-- Daun 1 (Tetap di pohon) -->
                <path d="M75 95C70 85 80 85 85 95C80 105 70 105 75 95Z" fill="#16a34a" />
                <path d="M120 90C115 80 125 80 130 90C125 100 115 100 120 90Z" fill="#15803d" />
                
                <!-- Daun Berguguran (Jatuh) -->
                <path d="M90 125C85 115 95 115 100 125C95 135 85 135 90 125Z" fill="#eab308" class="animate-bounce" style="animation-duration: 3s;"/>
                <path d="M60 145C55 135 65 135 70 145C65 155 55 155 60 145Z" fill="#ca8a04" class="animate-bounce" style="animation-duration: 4s;"/>
                <path d="M135 140C130 130 140 130 145 140C140 150 130 150 135 140Z" fill="#b45309" class="animate-bounce" style="animation-duration: 5s;"/>
            </svg>
        </div>

        <div class="space-y-2">
            <h1 class="text-7xl font-black text-green-600 tracking-tight">404</h1>
            <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white">Halaman Tidak Ditemukan</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto leading-relaxed">
                Sepertinya halaman yang kamu cari sudah dipindahkan atau tidak pernah ada. 
                 Jangan menyerah seperti lingkungan kita!
            </p>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
            <a href="/" 
               class="w-full sm:w-auto px-6 py-3 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-xl shadow-md transition-all">
                Kembali ke Beranda
            </a>
            <a href="/laporan/create" 
               class="w-full sm:w-auto px-6 py-3 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm font-bold rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-all">
                Laporkan Masalah
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
