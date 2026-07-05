<x-app-layout>
    @section('title', 'Cek Status Laporan Anonim — LaporHijau')

    <div class="bg-gradient-to-b from-green-50/70 to-white dark:from-slate-900/30 dark:to-slate-900 min-h-screen py-16 flex items-center justify-center">
        <div class="max-w-md w-full px-4 space-y-8">

            {{-- Brand / Icon --}}
            <div class="text-center space-y-3">
                <div class="w-16 h-16 bg-white dark:bg-slate-800 border border-gray-150 dark:border-slate-700/80 rounded-2xl flex items-center justify-center text-3xl mx-auto shadow-sm">
                    🔒
                </div>
                <h1 class="text-2xl font-black text-gray-900 dark:text-white leading-tight">
                    Lacak Laporan Anonim
                </h1>
                <p class="text-xs text-gray-500 dark:text-gray-400 max-w-sm mx-auto leading-relaxed">
                    Masukkan kode unik laporan Anda untuk memeriksa status validasi, progres penanganan, dan penyelesaian isu lingkungan.
                </p>
            </div>

            {{-- Form Cek Status --}}
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700/80 p-6 sm:p-8 shadow-sm space-y-5">
                
                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-800 rounded-2xl px-4 py-3 text-xs flex items-start gap-2">
                        <span class="mt-0.5">⚠️</span>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('laporan-anonim.cek') }}" class="space-y-4">
                    @csrf

                    <div class="space-y-2">
                        <label for="code" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Kode Laporan</label>
                        <div class="relative">
                            <input type="text" id="code" name="code" value="{{ old('code') }}" placeholder="cth: LA-123456 atau #LA-123456" required class="w-full text-sm py-3.5 pl-10 pr-4 border border-gray-200 dark:border-slate-700 rounded-2xl bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300 font-mono text-center font-bold tracking-widest uppercase">
                            <span class="absolute left-4 top-4 text-sm text-gray-450">#</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3.5 bg-green-600 hover:bg-green-700 active:bg-green-800 text-white font-extrabold text-xs rounded-2xl transition-all shadow-md">
                        Cek Status Sekarang
                    </button>
                </form>
            </div>

            {{-- Back Link --}}
            <div class="text-center">
                <a href="{{ route('laporan-anonim.create') }}" class="text-xs font-bold text-green-600 hover:underline">
                    Buat Laporan Anonim Baru
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
