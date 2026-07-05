<x-app-layout>
    @section('title', 'Cara Melapor')

    <div class="py-12 pb-24" style="font-family: 'Plus Jakarta Sans', sans-serif;">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header --}}
            <div class="text-center mb-12">
                <h1 class="text-3xl font-black text-gray-900 dark:text-white sm:text-4xl">
                    🌿 Panduan Lengkap Cara Melapor
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 max-w-lg mx-auto">
                    Pilih metode yang paling nyaman bagimu untuk menyuarakan kelestarian lingkungan di sekitar Riau.
                </p>
                <div class="w-16 h-1 bg-green-500 mx-auto mt-4 rounded-full"></div>
            </div>

            {{-- Grid Section --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
                
                {{-- Section 1: Via Website --}}
                <div class="bg-white dark:bg-slate-800 rounded-3xl border border-gray-150 dark:border-slate-700/80 p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-4">
                            <span class="text-2xl">🌐</span>
                            <h2 class="text-lg font-bold text-gray-950 dark:text-white">Via Website (Direkomendasikan)</h2>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-6 leading-relaxed">
                            Laporkan masalah langsung lewat aplikasi web untuk mendapatkan tracking terperinci, sistem poin kontribusi, serta lencana keaktifan.
                        </p>
                        
                        <div class="space-y-4 mb-8">
                            <div class="flex gap-3">
                                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-green-100 dark:bg-green-950/40 text-green-700 dark:text-green-400 flex items-center justify-center text-xs font-bold">1</span>
                                <div class="text-xs">
                                    <p class="font-bold text-gray-900 dark:text-white">Masuk / Daftar Akun</p>
                                    <p class="text-gray-450 dark:text-gray-400 mt-0.5">Membuat akun LaporHijau untuk melacak kontribusi Anda.</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-green-100 dark:bg-green-950/40 text-green-700 dark:text-green-400 flex items-center justify-center text-xs font-bold">2</span>
                                <div class="text-xs">
                                    <p class="font-bold text-gray-900 dark:text-white">Ambil Foto & Tentukan Lokasi</p>
                                    <p class="text-gray-450 dark:text-gray-400 mt-0.5">Foto tumpukan sampah atau pencemaran air dan tandai koordinat lokasi pada peta GIS.</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-green-100 dark:bg-green-950/40 text-green-700 dark:text-green-400 flex items-center justify-center text-xs font-bold">3</span>
                                <div class="text-xs">
                                    <p class="font-bold text-gray-900 dark:text-white">Verifikasi Lapangan</p>
                                    <p class="text-gray-450 dark:text-gray-400 mt-0.5">Tim relawan LaporHijau terdekat akan memvalidasi langsung ke lokasi kejadian.</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-green-100 dark:bg-green-950/40 text-green-700 dark:text-green-400 flex items-center justify-center text-xs font-bold">4</span>
                                <div class="text-xs">
                                    <p class="font-bold text-gray-900 dark:text-white">Penanganan Pemerintah</p>
                                    <p class="text-gray-450 dark:text-gray-400 mt-0.5">Instansi dinas terkait membersihkan lokasi hingga tuntas. Anda mendapatkan +50 poin kontribusi!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <a href="{{ route('laporan.create') }}" class="block text-center w-full py-3.5 bg-green-600 hover:bg-green-700 text-white font-bold text-sm rounded-xl transition-all shadow-md">
                        📸 Lapor Sekarang
                    </a>
                </div>

                {{-- Section 2: Via WhatsApp --}}
                <div class="bg-white dark:bg-slate-800 rounded-3xl border border-gray-150 dark:border-slate-700/80 p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-4">
                            <span class="text-2xl">💬</span>
                            <h2 class="text-lg font-bold text-gray-950 dark:text-white">Via WhatsApp (Alternatif)</h2>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-6 leading-relaxed">
                            Metode cepat dan familiar untuk warga yang kesulitan menggunakan formulir website. Laporan Anda akan diinput oleh tim kami.
                        </p>

                        <div class="space-y-4 mb-6">
                            <div class="flex gap-3">
                                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 flex items-center justify-center text-xs font-bold">1</span>
                                <div class="text-xs">
                                    <p class="font-bold text-gray-900 dark:text-white">Klik Tombol WhatsApp</p>
                                    <p class="text-gray-450 dark:text-gray-400 mt-0.5">Sistem akan secara otomatis membuka aplikasi WhatsApp dengan pesan template resmi.</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 flex items-center justify-center text-xs font-bold">2</span>
                                <div class="text-xs">
                                    <p class="font-bold text-gray-900 dark:text-white">Bagikan Lokasi GPS</p>
                                    <p class="text-gray-450 dark:text-gray-400 mt-0.5">Izinkan akses lokasi browser atau ketik alamat spesifik Anda secara manual.</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 flex items-center justify-center text-xs font-bold">3</span>
                                <div class="text-xs">
                                    <p class="font-bold text-gray-900 dark:text-white">Kirim Foto Masalah</p>
                                    <p class="text-gray-450 dark:text-gray-400 mt-0.5">Lampirkan foto tumpukan sampah/sungai tercemar sebagai bukti fisik lapangan.</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 flex items-center justify-center text-xs font-bold">4</span>
                                <div class="text-xs">
                                    <p class="font-bold text-gray-900 dark:text-white">Proses Input Oleh Admin</p>
                                    <p class="text-gray-450 dark:text-gray-400 mt-0.5">Admin LaporHijau akan memproses data Anda ke dalam sistem dalam waktu singkat.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="mb-3 p-3 bg-amber-50/50 dark:bg-amber-950/15 border border-amber-100 dark:border-amber-900/40 text-[10px] text-amber-850 dark:text-amber-400 font-bold rounded-xl text-center">
                            💡 Catatan: Laporan via WhatsApp diproses dalam 1x24 jam oleh tim relawan kami.
                        </div>
                        <button onclick="laporWhatsAppGuide()" class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer border-0">
                            💬 Lapor via WhatsApp
                        </button>
                    </div>
                </div>

            </div>

            {{-- Laporan Anonim Section --}}
            <div class="bg-gradient-to-r from-slate-900 to-slate-800 text-white rounded-3xl p-8 mb-16 shadow-md space-y-6">
                <div class="flex items-center gap-3">
                    <span class="text-3xl">🔒</span>
                    <h2 class="text-xl font-bold">Laporan Anonim</h2>
                </div>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Khawatir identitasmu diketahui? Kamu bisa melapor tanpa akun dan identitas sepenuhnya terlindungi. Kami menjamin kerahasiaan penuh laporan Anda tanpa melacak IP address atau data personal lainnya.
                </p>
                <div class="pt-2">
                    <a href="{{ route('laporan-anonim.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white text-xs font-extrabold rounded-xl transition-all shadow-sm">
                        Lapor Secara Anonim →
                    </a>
                </div>
            </div>

            {{-- FAQ Section --}}
            <div class="bg-gray-55 dark:bg-slate-800/40 rounded-3xl border border-gray-150 dark:border-slate-700/80 p-8 mt-12" x-data="{ activeFaq: null }">
                <h2 class="text-xl font-black text-gray-950 dark:text-white mb-6 text-center">❓ FAQ (Tanya Jawab Laporan)</h2>
                
                <div class="space-y-3">
                    
                    {{-- FAQ 1 --}}
                    <div class="border-b border-gray-200/60 dark:border-slate-750 pb-3">
                        <button @click="activeFaq = (activeFaq === 1 ? null : 1)" class="w-full flex items-center justify-between font-bold text-sm text-gray-800 dark:text-gray-200 text-left py-2 border-0 bg-transparent cursor-pointer">
                            <span>Apakah laporan saya akan ditindaklanjuti?</span>
                            <span class="text-green-600 font-bold" x-text="activeFaq === 1 ? '−' : '+'">+</span>
                        </button>
                        <div x-show="activeFaq === 1" x-transition class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed mt-2" style="display: none;">
                            Ya, tentu saja. LaporHijau adalah platform resmi yang terintegrasi dengan jaringan dinas kebersihan dan tata kota. Setiap laporan yang valid akan langsung diteruskan ke pemerintah dan dimonitor secara transparan melalui sistem SLA (Service Level Agreement).
                        </div>
                    </div>

                    {{-- FAQ 2 --}}
                    <div class="border-b border-gray-200/60 dark:border-slate-750 pb-3">
                        <button @click="activeFaq = (activeFaq === 2 ? null : 2)" class="w-full flex items-center justify-between font-bold text-sm text-gray-800 dark:text-gray-200 text-left py-2 border-0 bg-transparent cursor-pointer">
                            <span>Berapa lama proses verifikasi laporan?</span>
                            <span class="text-green-600 font-bold" x-text="activeFaq === 2 ? '−' : '+'">+</span>
                        </button>
                        <div x-show="activeFaq === 2" x-transition class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed mt-2" style="display: none;">
                            Proses verifikasi oleh relawan lokal LaporHijau memakan waktu maksimal 48 jam (sesuai standar SLA Verifikasi). Status verifikasi ini dapat Anda pantau secara langsung pada detail laporan.
                        </div>
                    </div>

                    {{-- FAQ 3 --}}
                    <div class="border-b border-gray-200/60 dark:border-slate-750 pb-3">
                        <button @click="activeFaq = (activeFaq === 3 ? null : 3)" class="w-full flex items-center justify-between font-bold text-sm text-gray-800 dark:text-gray-200 text-left py-2 border-0 bg-transparent cursor-pointer">
                            <span>Apa yang terjadi setelah saya melapor?</span>
                            <span class="text-green-600 font-bold" x-text="activeFaq === 3 ? '−' : '+'">+</span>
                        </button>
                        <div x-show="activeFaq === 3" x-transition class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed mt-2" style="display: none;">
                            Laporan akan masuk status *Pending*, lalu dikunjungi oleh relawan untuk verifikasi menjadi *Verified*. Setelah itu, instansi pemerintah dinas terkait akan melakukan pembersihan (status *In Progress*) hingga selesai sepenuhnya (*Resolved*).
                        </div>
                    </div>

                    {{-- FAQ 4 --}}
                    <div class="border-b border-gray-200/60 dark:border-slate-750 pb-3">
                        <button @click="activeFaq = (activeFaq === 4 ? null : 4)" class="w-full flex items-center justify-between font-bold text-sm text-gray-800 dark:text-gray-200 text-left py-2 border-0 bg-transparent cursor-pointer">
                            <span>Apakah saya bisa melapor secara anonim?</span>
                            <span class="text-green-600 font-bold" x-text="activeFaq === 4 ? '−' : '+'">+</span>
                        </button>
                        <div x-show="activeFaq === 4" x-transition class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed mt-2" style="display: none;">
                            Ya, jika melapor melalui website Anda dapat mengaktifkan pilihan profil anonim saat mengisi form. Jika melapor via WhatsApp, identitas nomor telepon Anda hanya digunakan oleh admin internal dan tidak dipublikasikan ke peta sebaran publik.
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            function laporWhatsAppGuide() {
                const whatsappNumber = "{{ env('WHATSAPP_NUMBER', '6281234567890') }}";
                
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;
                            
                            const text = `Halo Admin LaporHijau! 🌿

Saya ingin melaporkan masalah lingkungan:

📍 *Lokasi:* ${lat}, ${lng}
🗺️ *Google Maps:* https://maps.google.com/?q=${lat},${lng}

📝 *Deskripsi masalah:*
[Mohon isi deskripsi masalah di sini]

📸 *Foto:* [Lampirkan foto masalah]

Terima kasih!`;
                            
                            const encodedText = encodeURIComponent(text);
                            window.open(`https://wa.me/${whatsappNumber}?text=${encodedText}`, '_blank');
                        },
                        function(error) {
                            const text = `Halo Admin LaporHijau! 🌿

Saya ingin melaporkan masalah lingkungan:

📍 *Lokasi:* [Mohon isi alamat lokasi]
📝 *Deskripsi:* [Mohon isi deskripsi]
📸 *Foto:* [Lampirkan foto]

Terima kasih!`;
                            const encodedText = encodeURIComponent(text);
                            window.open(`https://wa.me/${whatsappNumber}?text=${encodedText}`, '_blank');
                        }
                    );
                } else {
                    const text = `Halo Admin LaporHijau! 🌿

Saya ingin melaporkan masalah lingkungan:

📍 *Lokasi:* [Mohon isi alamat lokasi]
📝 *Deskripsi:* [Mohon isi deskripsi]
📸 *Foto:* [Lampirkan foto]

Terima kasih!`;
                    const encodedText = encodeURIComponent(text);
                    window.open(`https://wa.me/${whatsappNumber}?text=${encodedText}`, '_blank');
                }
            }
        </script>
    @endpush
</x-app-layout>
