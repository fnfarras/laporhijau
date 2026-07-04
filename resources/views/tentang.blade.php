<x-app-layout>
    @section('title', 'Tentang Kami — LaporHijau')
    @section('meta_description', 'Kenali tim pengembang, misi visi, dan teknologi pendukung di balik LaporHijau — Platform Kolaborasi Aksi Lingkungan Nasional.')

    <div class="py-12 bg-gray-50 dark:bg-slate-900 transition-colors duration-300 font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">

            {{-- Hero Section --}}
            <div class="text-center max-w-3xl mx-auto space-y-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-bold rounded-full border border-green-200 dark:border-green-800/50">
                    <span>🌿</span> LaporHijau Civic Tech
                </div>
                <h1 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white tracking-tight">
                    Menjaga Lingkungan Lewat <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-500">Aksi Kolaboratif</span>
                </h1>
                <p class="text-base text-gray-500 dark:text-gray-400 leading-relaxed">
                    LaporHijau adalah platform pelaporan dan pemantauan masalah lingkungan hidup secara real-time yang memadukan peran aktif masyarakat, relawan, pemerintah, dan teknologi untuk masa depan bumi yang berkelanjutan.
                </p>
            </div>

            {{-- Visi & Misi Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700/80 p-8 shadow-sm flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center text-2xl text-green-600 dark:text-green-400">👁️</div>
                        <h3 class="text-xl font-extrabold text-gray-900 dark:text-white">Visi Kami</h3>
                        <p class="text-sm text-gray-550 dark:text-gray-400 leading-relaxed">
                            Menjadi platform pelopor berskala nasional yang mentransformasi partisipasi publik menjadi aksi nyata pelestarian lingkungan hidup demi terwujudnya kota yang bersih, hijau, dan sehat di seluruh penjuru Indonesia.
                        </p>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700/80 p-8 shadow-sm flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="w-12 h-12 bg-sky-100 dark:bg-sky-900/30 rounded-xl flex items-center justify-center text-2xl text-sky-600 dark:text-sky-400">🚀</div>
                        <h3 class="text-xl font-extrabold text-gray-900 dark:text-white">Misi Kami</h3>
                        <ul class="space-y-2.5 text-sm text-gray-550 dark:text-gray-400 leading-relaxed">
                            <li class="flex items-start gap-2">
                                <span class="text-green-500">✓</span> Mempermudah pelaporan isu kerusakan lingkungan secara real-time dengan bantuan teknologi geocoding berbasis GIS Leaflet.
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-green-500">✓</span> Menghubungkan relawan penggerak aksi lapangan dengan instansi pemerintah terkait guna percepatan penanganan masalah.
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-green-500">✓</span> Meningkatkan kesadaran lingkungan lewat gamifikasi poin kontribusi, lencana penghargaan, dan katalog penukaran hadiah.
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Tim Pengembang Section --}}
            <div class="space-y-8">
                <div class="text-center space-y-2">
                    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">Tim Pengembang</h2>
                    <p class="text-sm text-gray-550 dark:text-gray-450">Para inovator muda di balik terciptanya LaporHijau</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    {{-- Anggota 1 --}}
                    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700/80 p-6 text-center card-hover shadow-sm">
                        <div class="w-24 h-24 bg-gradient-to-tr from-green-400 to-emerald-600 rounded-full mx-auto flex items-center justify-center text-white text-3xl font-bold mb-4 shadow-md">
                            AF
                        </div>
                        <h4 class="font-extrabold text-gray-900 dark:text-white text-base">Ahmad Fauzi</h4>
                        <p class="text-xs text-green-600 dark:text-green-400 font-bold mb-2">Lead Full-Stack Developer</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 leading-relaxed">Bertanggung jawab atas arsitektur backend, sistem geocoding peta GIS Nominatim, serta performa server.</p>
                    </div>

                    {{-- Anggota 2 --}}
                    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700/80 p-6 text-center card-hover shadow-sm">
                        <div class="w-24 h-24 bg-gradient-to-tr from-sky-400 to-teal-500 rounded-full mx-auto flex items-center justify-center text-white text-3xl font-bold mb-4 shadow-md">
                            SR
                        </div>
                        <h4 class="font-extrabold text-gray-900 dark:text-white text-base">Siti Rahayu</h4>
                        <p class="text-xs text-sky-600 dark:text-sky-400 font-bold mb-2">UI/UX & Frontend Lead</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 leading-relaxed">Merancang antarmuka premium, transisi transparan, dark mode terintegrasi, serta responsive layout mobile.</p>
                    </div>

                    {{-- Anggota 3 --}}
                    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700/80 p-6 text-center card-hover shadow-sm sm:col-span-2 lg:col-span-1">
                        <div class="w-24 h-24 bg-gradient-to-tr from-amber-400 to-orange-500 rounded-full mx-auto flex items-center justify-center text-white text-3xl font-bold mb-4 shadow-md">
                            BS
                        </div>
                        <h4 class="font-extrabold text-gray-900 dark:text-white text-base">Budi Santoso</h4>
                        <p class="text-xs text-amber-600 dark:text-amber-450 font-bold mb-2">Data Analyst & Product QA</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 leading-relaxed">Menangani optimasi kueri basis data, pengujian performa, dan validasi data riset Pekanbaru.</p>
                    </div>
                </div>
            </div>

            {{-- Teknologi yang Digunakan --}}
            <div class="space-y-8">
                <div class="text-center space-y-2">
                    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">Teknologi Pendukung</h2>
                    <p class="text-sm text-gray-550 dark:text-gray-450">Teknologi kelas dunia yang menopang keandalan platform kami</p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                    <!-- Laravel -->
                    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-150 dark:border-slate-700/85 p-5 text-center flex flex-col items-center justify-center gap-3 hover:scale-105 transition-transform duration-200 shadow-xs">
                        <span class="text-3xl">🎯</span>
                        <span class="text-xs font-bold text-gray-800 dark:text-gray-200">Laravel 11</span>
                    </div>
                    <!-- Tailwind -->
                    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-150 dark:border-slate-700/85 p-5 text-center flex flex-col items-center justify-center gap-3 hover:scale-105 transition-transform duration-200 shadow-xs">
                        <span class="text-3xl">🎨</span>
                        <span class="text-xs font-bold text-gray-800 dark:text-gray-200">Tailwind CSS</span>
                    </div>
                    <!-- AlpineJS -->
                    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-150 dark:border-slate-700/85 p-5 text-center flex flex-col items-center justify-center gap-3 hover:scale-105 transition-transform duration-200 shadow-xs">
                        <span class="text-3xl">⚡</span>
                        <span class="text-xs font-bold text-gray-800 dark:text-gray-200">Alpine.js</span>
                    </div>
                    <!-- Leaflet -->
                    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-150 dark:border-slate-700/85 p-5 text-center flex flex-col items-center justify-center gap-3 hover:scale-105 transition-transform duration-200 shadow-xs">
                        <span class="text-3xl">🗺️</span>
                        <span class="text-xs font-bold text-gray-800 dark:text-gray-200">Leaflet.js GIS</span>
                    </div>
                    <!-- MySQL -->
                    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-150 dark:border-slate-700/85 p-5 text-center flex flex-col items-center justify-center gap-3 hover:scale-105 transition-transform duration-200 shadow-xs">
                        <span class="text-3xl">💾</span>
                        <span class="text-xs font-bold text-gray-800 dark:text-gray-200">MySQL 8 DB</span>
                    </div>
                    <!-- Cloudinary -->
                    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-150 dark:border-slate-700/85 p-5 text-center flex flex-col items-center justify-center gap-3 hover:scale-105 transition-transform duration-200 shadow-xs">
                        <span class="text-3xl">☁️</span>
                        <span class="text-xs font-bold text-gray-800 dark:text-gray-200">Cloudinary API</span>
                    </div>
                </div>
            </div>

            {{-- Kompetisi Lomba Section --}}
            <div class="bg-gradient-to-r from-green-600 to-emerald-600 rounded-3xl p-8 md:p-12 text-white relative overflow-hidden shadow-lg">
                {{-- Decorative background blobs --}}
                <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-green-500/20 rounded-full blur-3xl"></div>
                <div class="absolute -left-20 -top-20 w-80 h-80 bg-emerald-400/20 rounded-full blur-3xl"></div>

                <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div class="space-y-4">
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/10 text-white text-xs font-bold rounded-full border border-white/20">
                            🏆 Web Development Competition
                        </div>
                        <h2 class="text-3xl font-black tracking-tight leading-tight">NIFC 5.0 — National Inovative Future Competition</h2>
                        <p class="text-sm text-green-50 leading-relaxed max-w-xl">
                            Didesain khusus sebagai kontribusi solusi civic tech untuk tantangan pembangunan wilayah berbasis ramah lingkungan di tingkat nasional.
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="bg-white text-green-700 px-6 py-4 rounded-2xl font-black text-center shadow-lg transform rotate-2">
                            <span class="block text-2xl">🌟 CH-LEVEL</span>
                            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">LaporHijau Premium</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
