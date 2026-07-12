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
                background-color: #fffaf0; /* floralwhite for a slight parchment feel */
                box-shadow: 0 20px 40px -15px rgba(22, 163, 74, 0.2);
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
                    background-color: #fffaf0 !important;
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
            <div id="certificate-frame" class="cert-container relative overflow-hidden flex flex-col justify-between text-center min-h-[700px] p-2 sm:p-4">
                
                {{-- Outer Border (Thick Green) --}}
                <div class="absolute inset-0 border-[16px] border-[#0f5132] m-2 sm:m-3 pointer-events-none"></div>
                {{-- Inner Border (Gold) --}}
                <div class="absolute inset-0 border-[3px] border-[#b8860b] m-8 sm:m-9 pointer-events-none"></div>
                
                {{-- Corner Ornaments --}}
                <div class="absolute top-9 left-9 w-12 h-12 sm:w-16 sm:h-16 border-t-[5px] border-l-[5px] border-[#b8860b] pointer-events-none"></div>
                <div class="absolute top-9 right-9 w-12 h-12 sm:w-16 sm:h-16 border-t-[5px] border-r-[5px] border-[#b8860b] pointer-events-none"></div>
                <div class="absolute bottom-9 left-9 w-12 h-12 sm:w-16 sm:h-16 border-b-[5px] border-l-[5px] border-[#b8860b] pointer-events-none"></div>
                <div class="absolute bottom-9 right-9 w-12 h-12 sm:w-16 sm:h-16 border-b-[5px] border-r-[5px] border-[#b8860b] pointer-events-none"></div>

                {{-- Watermark --}}
                <div class="absolute inset-0 flex items-center justify-center opacity-[0.04] pointer-events-none">
                    <span class="text-[350px] grayscale">🌿</span>
                </div>

                {{-- Content Container --}}
                <div class="relative z-10 flex flex-col justify-between h-full py-12 sm:py-16 px-10 sm:px-20">
                    
                    {{-- Header --}}
                    <div class="space-y-6">
                        <div class="flex items-center justify-center gap-3">
                            <div class="w-12 h-12 bg-[#0f5132] rounded-full flex items-center justify-center text-white shadow-md text-xl">
                                🌿
                            </div>
                            <span class="font-bold text-gray-800 text-2xl tracking-widest uppercase">Lapor<span class="text-[#198754]">Hijau</span></span>
                        </div>

                        <div class="space-y-2">
                            <h1 class="font-serif text-3xl sm:text-5xl md:text-6xl text-[#0f5132] font-black uppercase tracking-widest mt-6">
                                Sertifikat Penghargaan
                            </h1>
                            <p class="text-xs sm:text-sm tracking-[0.4em] text-[#b8860b] uppercase font-bold">
                                Certificate of Appreciation
                            </p>
                        </div>
                    </div>

                    {{-- Body Text --}}
                    <div class="my-14 space-y-6">
                        <p class="text-sm sm:text-base text-gray-500 italic font-serif">Sertifikat ini diberikan secara terhormat kepada:</p>
                        
                        <div class="py-4">
                            <h2 class="font-signature text-5xl sm:text-7xl md:text-8xl text-gray-900 inline-block px-12 border-b border-gray-300 pb-2">
                                {{ $redemption->user->name }}
                            </h2>
                        </div>
                        
                        <div class="space-y-4 max-w-3xl mx-auto pt-4">
                            <p class="text-sm sm:text-base md:text-lg text-gray-700 leading-relaxed font-serif">
                                Atas dedikasi, partisipasi, dan kontribusi nyata dalam upaya pelestarian lingkungan hidup.
                                Penghargaan tingkat <span class="font-bold text-[#b8860b] uppercase tracking-wide">"{{ $redemption->reward->name }}"</span> 
                                diberikan sebagai bentuk apresiasi tertinggi dari komunitas kami.
                            </p>
                            <p class="text-xs sm:text-sm text-gray-400 italic">
                                "{{ $redemption->reward->description }}"
                            </p>
                        </div>
                    </div>

                    {{-- Footer: Seal, Signatures, Verification --}}
                    <div class="mt-auto grid grid-cols-1 sm:grid-cols-3 items-end gap-8 sm:gap-4">
                        
                        {{-- Left: Verification --}}
                        <div class="text-center sm:text-left space-y-2 order-3 sm:order-1">
                            <p class="text-[10px] text-gray-400 uppercase tracking-wider font-bold">Verifikasi Sertifikat</p>
                            <div class="inline-block bg-gray-100/50 border border-gray-200 py-1.5 px-3 rounded text-center">
                                <p class="font-mono text-xs font-bold text-gray-800">ID: {{ $redemption->certificate_code }}</p>
                            </div>
                            <p class="text-[10px] text-gray-500 font-serif italic">Dikeluarkan pada {{ $redemption->redeemed_at->format('d F Y') }}</p>
                        </div>

                        {{-- Center: Seal --}}
                        <div class="flex justify-center order-1 sm:order-2">
                            <div class="w-28 h-28 sm:w-32 sm:h-32 rounded-full bg-gradient-to-br from-[#ffd700] via-[#b8860b] to-[#daa520] flex items-center justify-center p-1.5 shadow-[0_0_20px_rgba(184,134,11,0.4)] relative border-[3px] border-[#fffaf0] outline outline-1 outline-[#b8860b]">
                                <div class="w-full h-full rounded-full border-2 border-dashed border-[#fffaf0]/60 flex flex-col items-center justify-center text-white font-serif relative overflow-hidden">
                                    <div class="absolute inset-0 bg-white/10 rotate-45"></div>
                                    <span class="text-3xl sm:text-4xl leading-none shadow-sm relative z-10 drop-shadow-md">🌿</span>
                                    <span class="text-[7px] sm:text-[9px] uppercase tracking-[0.2em] font-black mt-2 shadow-sm relative z-10 text-white drop-shadow-md">Verified</span>
                                </div>
                            </div>
                        </div>

                        {{-- Right: Signature --}}
                        <div class="text-center space-y-2 order-2 sm:order-3">
                            <div class="font-signature text-4xl sm:text-5xl text-gray-800 -mb-2 sm:-mb-4 opacity-90 transform -rotate-2">
                                Admin LaporHijau
                            </div>
                            <div class="border-t border-gray-400 w-48 mx-auto pt-2">
                                <p class="text-[10px] text-gray-800 font-bold uppercase tracking-widest">Ketua Pelaksana</p>
                                <p class="text-[9px] text-gray-500 font-serif italic">Komunitas LaporHijau Indonesia</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
