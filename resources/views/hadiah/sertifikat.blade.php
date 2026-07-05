<x-app-layout>
    @section('title', 'Sertifikat Penghargaan LaporHijau — ' . $redemption->reward->name)

    @push('styles')
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            
            /* Styles for interactive UI */
            .cert-container {
                border-radius: 20px;
                box-shadow: 0 10px 30px -10px rgba(22, 163, 74, 0.2);
            }

            /* Print-only overrides */
            @media print {
                /* Hide everything except the certificate frame */
                body * {
                    visibility: hidden;
                }
                #certificate-frame, #certificate-frame * {
                    visibility: visible;
                }
                #certificate-frame {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                    height: 100%;
                    border: none !important;
                    box-shadow: none !important;
                    background: #f0fdf4 !important; /* Keep light green background on print */
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }
                .no-print {
                    display: none !important;
                }
            }
        </style>
    @endpush

    <div class="bg-gradient-to-b from-green-50/70 to-white dark:from-slate-900/30 dark:to-slate-900 min-h-screen py-10 pb-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Navigation Action (no-print) --}}
            <div class="flex items-center justify-between no-print bg-white dark:bg-slate-800 p-4 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm">
                <a href="{{ route('hadiah') }}" class="text-xs font-bold text-gray-500 hover:text-green-600 transition-colors flex items-center gap-1.5">
                    ← Kembali ke Toko Hadiah
                </a>

                <div class="flex items-center gap-2">
                    {{-- Cetak --}}
                    <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-4 py-2 border border-green-650 hover:bg-green-50 dark:hover:bg-green-950/20 text-green-700 dark:text-green-400 text-xs font-bold rounded-xl transition-all shadow-sm">
                        🖨️ Cetak Sertifikat
                    </button>

                    {{-- Bagikan --}}
                    @php
                        $shareText = 'Halo! Saya baru saja menukarkan poin kontribusi LaporHijau untuk mendapatkan penghargaan: *' . $redemption->reward->name . '*! Verifikasi sertifikat digital saya di sini: ' . url()->current() . ' 🌿';
                        $whatsappUrl = 'https://wa.me/?text=' . urlencode($shareText);
                    @endphp
                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-4 py-2 bg-green-650 hover:bg-green-750 text-white text-xs font-bold rounded-xl transition-all shadow-md">
                        💬 Bagikan ke WA
                    </a>
                </div>
            </div>

            {{-- ── CERTIFICATE CONTAINER ────────────────────────────────── --}}
            <div id="certificate-frame" class="cert-container bg-gradient-to-br from-green-50 to-emerald-50 dark:from-slate-900 dark:to-slate-800 p-8 sm:p-14 border-[12px] border-double border-green-700 dark:border-green-600 relative overflow-hidden flex flex-col justify-between text-center min-h-[560px]">
                
                {{-- Decorative Corners --}}
                <div class="absolute top-4 left-4 w-12 h-12 border-t-2 border-l-2 border-green-700/30"></div>
                <div class="absolute top-4 right-4 w-12 h-12 border-t-2 border-r-2 border-green-700/30"></div>
                <div class="absolute bottom-4 left-4 w-12 h-12 border-b-2 border-l-2 border-green-700/30"></div>
                <div class="absolute bottom-4 right-4 w-12 h-12 border-b-2 border-r-2 border-green-700/30"></div>

                {{-- Header --}}
                <div class="space-y-4">
                    <div class="flex items-center justify-center gap-2">
                        <div class="w-8 h-8 bg-green-650 rounded-lg flex items-center justify-center text-white text-sm shadow-sm">
                            🌿
                        </div>
                        <span class="font-extrabold text-gray-900 dark:text-white text-lg tracking-tight">Lapor<span class="text-green-600">Hijau</span></span>
                    </div>

                    <div class="space-y-1">
                        <h2 class="text-xs uppercase tracking-[0.25em] font-extrabold text-green-700 dark:text-green-400">Sertifikat Digital Penghargaan</h2>
                        <div class="w-20 h-0.5 bg-green-600 mx-auto"></div>
                    </div>
                </div>

                {{-- Body Text --}}
                <div class="my-10 space-y-6">
                    <p class="text-xs italic text-gray-400 dark:text-gray-500">Diberikan secara terhormat kepada:</p>
                    
                    <h1 class="text-3xl sm:text-4xl font-serif font-black text-gray-800 dark:text-white underline decoration-green-600/30 decoration-wavy underline-offset-8">
                        {{ $redemption->user->name }}
                    </h1>
                    
                    <div class="space-y-2 max-w-xl mx-auto">
                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Sebagai penerima penghargaan: <span class="text-green-600 dark:text-green-400 font-extrabold">{{ $redemption->reward->name }}</span>
                        </p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 leading-relaxed italic">
                            "{{ $redemption->reward->description }}"
                        </p>
                    </div>
                </div>

                {{-- Footer Verification --}}
                <div class="border-t border-green-600/20 pt-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-left">
                    <div class="space-y-1 text-center sm:text-left">
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-wider">Verifikasi Keaslian</p>
                        <p class="font-mono text-xs font-bold text-gray-700 dark:text-gray-300">Kode: {{ $redemption->certificate_code }}</p>
                        <p class="text-[9px] text-gray-400 dark:text-gray-500">Tanggal Tukar: {{ $redemption->redeemed_at->format('d F Y H:i') }}</p>
                    </div>

                    <div class="text-center sm:text-right space-y-1">
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 font-bold">Platform Lingkungan Komunitas Indonesia</p>
                        <a href="{{ url('/') }}" class="text-[9px] text-green-600 dark:text-green-400 font-semibold hover:underline">
                            laporhijau-production.up.railway.app
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
