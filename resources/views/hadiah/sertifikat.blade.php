<x-app-layout>
    @section('title', 'Sertifikat Penghargaan LaporHijau — ' . $redemption->reward->name)

    @push('styles')
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400;1,700&display=swap" rel="stylesheet">
        
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            .font-serif { font-family: 'Playfair Display', serif; }
            .font-signature { font-family: 'Great Vibes', cursive; }
            
            .cert-container {
                background-color: #fdfbf7;
                background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2316a34a' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
                box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.1);
            }

            .cert-inner {
                border: 2px solid #b8860b;
                outline: 10px solid #0f5132;
                outline-offset: -12px;
                background: linear-gradient(135deg, rgba(255,255,255,0.7) 0%, rgba(255,255,255,0.2) 100%);
            }

            .ribbon {
                position: absolute;
                bottom: -20px;
                left: 50%;
                transform: translateX(-50%);
                width: 40px;
                height: 50px;
                background: #0f5132;
                clip-path: polygon(0 0, 100% 0, 100% 100%, 50% 80%, 0 100%);
                z-index: -1;
            }

            /* Print-only overrides */
            @media print {
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
        <div class="max-w-[1000px] mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Navigation Action (no-print) --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 no-print bg-white dark:bg-slate-800 p-4 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm">
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
            <div id="certificate-frame" class="cert-container relative flex flex-col justify-between text-center min-h-[700px] p-4 sm:p-6">
                
                {{-- Inner Border Frame --}}
                <div class="cert-inner absolute inset-0 m-4 sm:m-6 pointer-events-none z-0"></div>
                
                {{-- Corner Ornaments (Gold) --}}
                <div class="absolute top-10 left-10 w-16 h-16 border-t-[3px] border-l-[3px] border-[#b8860b] pointer-events-none z-10"></div>
                <div class="absolute top-10 right-10 w-16 h-16 border-t-[3px] border-r-[3px] border-[#b8860b] pointer-events-none z-10"></div>
                <div class="absolute bottom-10 left-10 w-16 h-16 border-b-[3px] border-l-[3px] border-[#b8860b] pointer-events-none z-10"></div>
                <div class="absolute bottom-10 right-10 w-16 h-16 border-b-[3px] border-r-[3px] border-[#b8860b] pointer-events-none z-10"></div>

                {{-- Watermark --}}
                <div class="absolute inset-0 flex items-center justify-center opacity-[0.03] pointer-events-none z-0">
                    <span class="text-[400px] grayscale leading-none">🌿</span>
                </div>

                {{-- Content Container --}}
                <div class="relative z-10 flex flex-col justify-between h-full py-12 px-8 sm:px-16">
                    
                    {{-- Header --}}
                    <div class="space-y-5">
                        <div class="flex items-center justify-center gap-3 opacity-90">
                            <span class="font-bold text-[#0f5132] text-xl tracking-[0.3em] uppercase border-b-2 border-[#b8860b] pb-1">LaporHijau</span>
                        </div>

                        <div class="space-y-1 mt-6">
                            <h1 class="font-serif text-4xl sm:text-6xl text-[#0f5132] font-black uppercase tracking-widest" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.05);">
                                Sertifikat Penghargaan
                            </h1>
                            <p class="text-xs sm:text-sm tracking-[0.5em] text-[#b8860b] uppercase font-bold">
                                Certificate of Appreciation
                            </p>
                        </div>
                    </div>

                    {{-- Body Text --}}
                    <div class="my-10 space-y-6">
                        <p class="text-sm sm:text-lg text-gray-600 font-serif tracking-wide">
                            Dengan bangga diberikan kepada:
                        </p>
                        
                        <div class="py-2">
                            <h2 class="font-signature text-6xl sm:text-8xl text-[#1a1a1a] inline-block px-16 border-b-[1px] border-gray-400 pb-4">
                                {{ $redemption->user->name }}
                            </h2>
                        </div>
                        
                        <div class="space-y-5 max-w-3xl mx-auto pt-6">
                            <p class="text-sm sm:text-lg text-gray-700 leading-relaxed font-serif text-justify" style="text-align-last: center;">
                                Atas dedikasi, partisipasi aktif, dan kontribusi nyata dalam upaya pelestarian lingkungan hidup berkelanjutan.
                                Penghargaan <span class="font-bold text-[#0f5132] uppercase tracking-wider">"{{ $redemption->reward->name }}"</span> 
                                ini dianugerahkan sebagai bentuk apresiasi tertinggi dari LaporHijau.
                            </p>
                            <p class="text-xs sm:text-sm text-gray-500 font-serif italic">
                                "{{ $redemption->reward->description }}"
                            </p>
                        </div>
                    </div>

                    {{-- Footer: Seal, Signatures, Verification --}}
                    <div class="mt-auto grid grid-cols-1 sm:grid-cols-3 items-end gap-8 sm:gap-4 pb-4">
                        
                        {{-- Left: Verification --}}
                        <div class="text-center sm:text-left space-y-2 order-3 sm:order-1 px-4">
                            <p class="text-[9px] text-gray-400 uppercase tracking-widest font-bold">Kode Verifikasi</p>
                            <div class="inline-block border-b border-gray-300 pb-1">
                                <p class="font-mono text-xs font-bold text-gray-800 tracking-wider">{{ $redemption->certificate_code }}</p>
                            </div>
                            <p class="text-[10px] text-gray-500 font-serif italic">Diterbitkan: {{ $redemption->redeemed_at->format('d F Y') }}</p>
                        </div>

                        {{-- Center: Gold Seal --}}
                        <div class="flex justify-center order-1 sm:order-2 relative z-20">
                            <div class="w-28 h-28 sm:w-32 sm:h-32 rounded-full bg-gradient-to-br from-[#f6d365] via-[#ffb347] to-[#ff7e5f] flex items-center justify-center p-1.5 shadow-[0_10px_20px_rgba(255,140,0,0.3)] relative border-[4px] border-white z-10" style="background: radial-gradient(ellipse farthest-corner at right bottom, #FEDB37 0%, #FDB931 8%, #9f7928 30%, #8A6E2F 40%, transparent 80%), radial-gradient(ellipse farthest-corner at left top, #FFFFFF 0%, #FFFFAC 8%, #D1B464 25%, #5d4a1f 62.5%, #5d4a1f 100%);">
                                <div class="w-full h-full rounded-full border-2 border-dashed border-white/60 flex flex-col items-center justify-center text-white font-serif relative overflow-hidden bg-black/10">
                                    <span class="text-3xl sm:text-4xl leading-none relative z-10 drop-shadow-md">🌿</span>
                                    <span class="text-[8px] sm:text-[10px] uppercase tracking-[0.2em] font-black mt-1 shadow-sm relative z-10 text-white drop-shadow-md">Verified</span>
                                    <span class="text-[6px] uppercase tracking-widest text-white/80 mt-0.5">LaporHijau</span>
                                </div>
                            </div>
                            <div class="ribbon"></div>
                        </div>

                        {{-- Right: Signature --}}
                        <div class="text-center space-y-1 order-2 sm:order-3 px-4">
                            <div class="font-signature text-4xl sm:text-5xl text-[#0f5132] -mb-2 sm:-mb-3 opacity-90 transform -rotate-3" style="text-shadow: 1px 1px 0px rgba(0,0,0,0.05);">
                                John Doe
                            </div>
                            <div class="border-t border-gray-400 w-48 mx-auto pt-2">
                                <p class="text-[10px] text-gray-800 font-bold uppercase tracking-widest">Pimpinan LaporHijau</p>
                                <p class="text-[9px] text-gray-500 font-serif italic">Kementerian Lingkungan Hidup</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
