<x-app-layout>
    @section('title', 'Laporan Anonim Berhasil Terkirim — LaporHijau')

    <div class="bg-gradient-to-b from-green-50/70 to-white dark:from-slate-900/30 dark:to-slate-900 min-h-screen py-16 flex items-center justify-center">
        <div class="max-w-md w-full px-4 text-center space-y-8">

            {{-- Success Icon --}}
            <div class="w-20 h-20 bg-green-50 dark:bg-green-950/40 rounded-full flex items-center justify-center text-4xl mx-auto shadow-sm animate-bounce">
                🎉
            </div>

            <div class="space-y-3">
                <h1 class="text-2xl font-black text-gray-900 dark:text-white leading-tight">
                    Laporan Anonim Berhasil Dikirim!
                </h1>
                <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                    Terima kasih telah berkontribusi menjaga kelestarian lingkungan. Laporan Anda telah berhasil masuk ke sistem penanganan LaporHijau secara anonim.
                </p>
            </div>

            {{-- Code Box --}}
            <div class="bg-slate-900 text-slate-100 border border-slate-800 p-6 rounded-2xl space-y-2 shadow-md">
                <span class="text-[9px] text-slate-500 uppercase tracking-widest block font-bold">Kode Laporan Anda</span>
                <h2 class="text-3xl font-mono font-black text-emerald-400 select-all tracking-wider">
                    #{{ $code }}
                </h2>
                <p class="text-[10px] text-slate-400 leading-normal">
                    ⚠️ *Catat dan simpan kode di atas secara aman. Karena Anda melapor tanpa akun, kode ini adalah satu-satunya cara untuk melacak status penanganan laporan Anda di kemudian hari.*
                </p>
            </div>

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row gap-3 pt-2">
                <a href="{{ route('laporan-anonim.cek-form') }}" class="flex-1 py-3.5 bg-green-600 hover:bg-green-700 active:bg-green-800 text-white font-extrabold text-xs rounded-xl transition-all shadow-md">
                    Cek Status Laporan
                </a>
                <a href="{{ route('laporan-anonim.create') }}" class="flex-1 py-3.5 border border-gray-250 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-800 text-gray-650 dark:text-gray-300 font-bold text-xs rounded-xl transition-all">
                    Buat Laporan Lain
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
