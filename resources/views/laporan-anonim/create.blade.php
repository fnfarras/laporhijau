<x-app-layout>
    @section('title', 'Lapor Anonim Masalah Lingkungan — LaporHijau')

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            .form-card {
                border-radius: 20px;
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
            }
            #map {
                height: 320px;
                border-radius: 16px;
                z-index: 10;
            }
            .step-indicator.active {
                color: #16a34a;
                border-color: #16a34a;
                font-weight: 700;
            }
            .step-line.active {
                background-color: #16a34a;
            }
            .photo-preview-item {
                position: relative;
                aspect-ratio: 1;
                border-radius: 12px;
                overflow: hidden;
                border: 1px solid #e2e8f0;
            }
            .photo-preview-item img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            .photo-preview-item .remove-btn {
                position: absolute;
                top: 6px;
                right: 6px;
                width: 22px;
                height: 22px;
                background: rgba(239, 68, 68, 0.9);
                color: white;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 14px;
                cursor: pointer;
                transition: background 0.15s ease;
                border: none;
            }
            .photo-preview-item .remove-btn:hover {
                background: rgb(220, 38, 38);
            }
        </style>
    @endpush

    <div x-data="{ step: 1 }" class="bg-gradient-to-b from-green-50/70 to-white dark:from-slate-900/30 dark:to-slate-900 min-h-screen py-8 pb-32">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Breadcrumb & Back --}}
            <div class="no-print">
                <a href="{{ route('home') }}" class="text-xs font-bold text-gray-500 hover:text-green-600 transition-colors flex items-center gap-1.5">
                    ← Kembali ke Beranda
                </a>
            </div>

            {{-- Banner Anonim Terlindungi --}}
            <div class="bg-gradient-to-r from-slate-900 to-slate-800 text-white rounded-2xl p-4 sm:p-5 flex items-start gap-3 shadow-md">
                <span class="text-2xl mt-0.5">🔒</span>
                <div class="space-y-1">
                    <h3 class="font-bold text-sm">Mode Anonim Aktif</h3>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Identitas Anda sepenuhnya terlindungi. Kami tidak menyimpan alamat IP, tidak mendeteksi lokasi geografis tanpa izin, dan data pribadi Anda tidak akan pernah dipublikasikan di arsip publik.
                    </p>
                </div>
            </div>

            {{-- ── STEP PROGRESS INDICATOR ─────────────────────────────── --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-150 dark:border-slate-700/80 p-5 shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-2 flex-1 justify-center sm:justify-start">
                    <span :class="step >= 1 ? 'active' : ''" class="step-indicator w-8 h-8 rounded-full border-2 border-gray-250 flex items-center justify-center text-xs font-bold text-gray-400">1</span>
                    <span class="text-xs font-bold text-gray-600 dark:text-gray-300 hidden sm:inline">Detail Laporan</span>
                </div>
                <div :class="step >= 2 ? 'active' : ''" class="step-line h-0.5 bg-gray-200 flex-1 mx-4 hidden sm:block"></div>
                <div class="flex items-center gap-2 flex-1 justify-center">
                    <span :class="step >= 2 ? 'active' : ''" class="step-indicator w-8 h-8 rounded-full border-2 border-gray-250 flex items-center justify-center text-xs font-bold text-gray-400">2</span>
                    <span class="text-xs font-bold text-gray-600 dark:text-gray-300 hidden sm:inline">Lokasi Kejadian</span>
                </div>
                <div :class="step >= 3 ? 'active' : ''" class="step-line h-0.5 bg-gray-200 flex-1 mx-4 hidden sm:block"></div>
                <div class="flex items-center gap-2 flex-1 justify-center sm:justify-end">
                    <span :class="step >= 3 ? 'active' : ''" class="step-indicator w-8 h-8 rounded-full border-2 border-gray-250 flex items-center justify-center text-xs font-bold text-gray-400">3</span>
                    <span class="text-xs font-bold text-gray-600 dark:text-gray-300 hidden sm:inline">Foto Bukti</span>
                </div>
            </div>

            {{-- Form Errors Alert --}}
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 rounded-2xl p-4 text-xs space-y-1">
                    <p class="font-bold">⚠️ Ada kesalahan pengisian form:</p>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ── FORM ELEMENT ────────────────────────────────────────── --}}
            <form id="report-form" method="POST" action="{{ route('laporan-anonim.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- STEP 1: DETAIL LAPORAN --}}
                <div x-show="step === 1" class="form-card bg-white dark:bg-slate-800 border border-gray-150 dark:border-slate-700/80 p-6 sm:p-8 space-y-6">
                    <div class="border-b border-gray-100 dark:border-slate-700 pb-3 flex items-center gap-2">
                        <span class="text-xl">📝</span>
                        <h2 class="font-black text-lg text-gray-900 dark:text-white">Informasi Laporan</h2>
                    </div>

                    {{-- Judul Laporan --}}
                    <div class="space-y-2">
                        <label for="title" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Judul Laporan <span class="text-red-500">*</span></label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="cth: Tumpukan sampah liar di pinggir Sungai Siak" required class="w-full text-sm py-3 px-4 border border-gray-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300">
                    </div>

                    {{-- Kategori --}}
                    <div class="space-y-2">
                        <label for="category_id" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Kategori Masalah <span class="text-red-500">*</span></label>
                        <select id="category_id" name="category_id" required class="w-full text-sm py-3 px-4 border border-gray-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->icon }} {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="space-y-2">
                        <label for="description" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Deskripsi Masalah <span class="text-red-500">*</span></label>
                        <textarea id="description" name="description" rows="5" placeholder="Jelaskan masalah lingkungan yang kamu temukan secara detail..." required class="w-full text-sm py-3 px-4 border border-gray-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300">{{ old('description') }}</textarea>
                    </div>

                    <div class="border-t border-gray-50 dark:border-slate-700/50 pt-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Nama Samaran (opsional) --}}
                        <div class="space-y-2">
                            <label for="anonymous_name" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Nama Samaran (Opsional)</label>
                            <input type="text" id="anonymous_name" name="anonymous_name" value="{{ old('anonymous_name') }}" placeholder="cth: Warga RT 05 / Kosongkan" class="w-full text-sm py-3 px-4 border border-gray-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300">
                        </div>

                        {{-- Kontak WA (opsional) --}}
                        <div class="space-y-2">
                            <label for="anonymous_contact" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Nomor WA untuk Update (Opsional)</label>
                            <input type="text" id="anonymous_contact" name="anonymous_contact" value="{{ old('anonymous_contact') }}" placeholder="cth: 08123456789" class="w-full text-sm py-3 px-4 border border-gray-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300">
                        </div>
                    </div>
                </div>

                {{-- STEP 2: LOKASI KEJADIAN --}}
                <div x-show="step === 2" style="display: none;" class="form-card bg-white dark:bg-slate-800 border border-gray-150 dark:border-slate-700/80 p-6 sm:p-8 space-y-6">
                    <div class="border-b border-gray-100 dark:border-slate-700 pb-3 flex items-center gap-2">
                        <span class="text-xl">📍</span>
                        <h2 class="font-black text-lg text-gray-900 dark:text-white">Lokasi Kejadian</h2>
                    </div>

                    {{-- Search Address (Geocoding) --}}
                    <div id="geocode-container" class="relative">
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Cari Alamat/Lokasi</label>
                        <div class="flex gap-2">
                            <input type="text" id="geocode-input" placeholder="Cari nama jalan, komplek, atau wilayah..." class="flex-1 text-sm py-3 px-4 border border-gray-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300">
                            <button type="button" onclick="geocodeAddress()" class="px-5 bg-green-600 hover:bg-green-700 text-white font-bold text-xs rounded-xl shadow-sm transition-colors">
                                Cari
                            </button>
                        </div>
                        <div id="geocode-results" class="absolute left-0 right-0 top-full mt-1.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-lg z-50 hidden max-h-56 overflow-y-auto"></div>
                    </div>

                    {{-- Map Picker --}}
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Tandai di Peta <span class="text-red-500">*</span></label>
                            <button type="button" id="btn-gps" onclick="getGpsLocation()" class="px-3.5 py-1.5 bg-sky-500 hover:bg-sky-600 text-white text-[10px] font-extrabold uppercase rounded-lg transition-colors flex items-center gap-1.5">
                                📡 <span id="gps-btn-text">Gunakan Lokasi Saya</span>
                            </button>
                        </div>

                        <div id="map"></div>
                        <p class="text-[10px] text-gray-400">💡 *Geser peta dan klik di manapun untuk menandai koordinat secara manual.*</p>
                    </div>

                    {{-- Selected coordinates & address input --}}
                    <div class="space-y-4 pt-4 border-t border-gray-50 dark:border-slate-700/50">
                        {{-- Koordinat Display --}}
                        <div id="coords-display" class="hidden bg-gray-50 dark:bg-slate-750 px-4 py-2.5 rounded-xl text-[11px] font-mono flex items-center justify-between text-gray-600 dark:text-gray-300">
                            <span>📌 Koordinat Terpilih:</span>
                            <span id="coords-text" class="font-extrabold text-green-600">0.00, 0.00</span>
                        </div>

                        <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
                        <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">

                        {{-- Alamat Tertulis --}}
                        <div class="space-y-2">
                            <label for="address" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Alamat Lengkap Kejadian <span class="text-red-500">*</span></label>
                            <input type="text" id="address" name="address" value="{{ old('address') }}" placeholder="Tuliskan nama jalan, kelurahan, atau penunjuk jalan terdekat..." required class="w-full text-sm py-3 px-4 border border-gray-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300">
                        </div>
                    </div>
                </div>

                {{-- STEP 3: FOTO BUKTI --}}
                <div x-show="step === 3" style="display: none;" class="form-card bg-white dark:bg-slate-800 border border-gray-150 dark:border-slate-700/80 p-6 sm:p-8 space-y-6">
                    <div class="border-b border-gray-100 dark:border-slate-700 pb-3 flex items-center gap-2">
                        <span class="text-xl">📸</span>
                        <h2 class="font-black text-lg text-gray-900 dark:text-white">Foto Bukti (Maks 3)</h2>
                    </div>

                    {{-- Camera & Gallery Buttons Grid --}}
                    <div class="grid grid-cols-2 gap-4">
                        {{-- Camera Capture --}}
                        <label class="flex flex-col items-center justify-center gap-2.5 p-6 border-2 border-dashed border-green-200 dark:border-green-800 hover:bg-green-50/50 dark:hover:bg-green-950/10 rounded-2xl cursor-pointer transition-all">
                            <span class="text-3xl">📸</span>
                            <span class="text-xs font-bold text-green-700 dark:text-green-400">Ambil Foto</span>
                            <input type="file" id="camera-capture" accept="image/*" capture="environment" class="hidden" onchange="capturePhoto(event)">
                        </label>

                        {{-- Gallery File Selection --}}
                        <label class="flex flex-col items-center justify-center gap-2.5 p-6 border-2 border-dashed border-green-200 dark:border-green-800 hover:bg-green-50/50 dark:hover:bg-green-950/10 rounded-2xl cursor-pointer transition-all">
                            <span class="text-3xl">🖼️</span>
                            <span class="text-xs font-bold text-green-700 dark:text-green-400">Pilih Galeri</span>
                            <input type="file" id="photos" name="photos[]" multiple accept="image/*" class="hidden" onchange="previewPhotos(event)">
                        </label>
                    </div>

                    {{-- Photo Previews --}}
                    <div class="space-y-2 pt-4">
                        <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Daftar Foto Dipilih (Maks 3)</span>
                        <div id="photo-preview-grid" class="grid grid-cols-3 gap-2.5">
                            {{-- Previews rendered dynamically by JS --}}
                        </div>
                    </div>
                </div>

            </form>

            {{-- ── STICKY ACTIONS BAR ──────────────────────────────────── --}}
            <div class="fixed bottom-0 left-0 right-0 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-t border-gray-100 dark:border-slate-800 py-4 z-50 no-print">
                <div class="max-w-3xl mx-auto px-4 flex items-center justify-between gap-4">
                    {{-- Kembali --}}
                    <button
                        type="button"
                        x-show="step > 1"
                        @click="step--"
                        class="px-6 py-3 border border-gray-250 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-800 text-gray-650 dark:text-gray-300 font-bold text-sm rounded-xl transition-all w-1/2 sm:w-auto text-center"
                        style="display: none;"
                    >
                        Kembali
                    </button>
                    
                    {{-- Spacer if step === 1 --}}
                    <div x-show="step === 1" class="hidden sm:block"></div>

                    {{-- Lanjut --}}
                    <button
                        type="button"
                        x-show="step < 3"
                        @click="step++; if(step === 2) { setTimeout(() => map.invalidateSize(), 150); }"
                        class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold text-sm rounded-xl transition-all shadow-md w-1/2 sm:w-auto text-center"
                    >
                        Lanjut
                    </button>

                    {{-- Kirim Laporan --}}
                    <button
                        form="report-form"
                        type="submit"
                        id="submit-btn"
                        x-show="step === 3"
                        class="px-8 py-3 bg-green-600 hover:bg-green-700 active:bg-green-800 text-white font-bold text-sm rounded-xl transition-colors flex items-center justify-center gap-2 shadow-md w-1/2 sm:w-auto text-center"
                        style="display: none;"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Kirim Laporan
                    </button>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            // ── Leaflet Map Setup ──────────────────────────────────────────
            const defaultLat = {{ old('latitude', -0.9471) }};
            const defaultLng = {{ old('longitude', 100.4172) }};

            const map = L.map('map').setView([defaultLat, defaultLng], 13);
            window.map = map;

            L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            let marker = null;

            async function reverseGeocode(lat, lng) {
                const addressInput = document.getElementById('address');
                const originalPlaceholder = addressInput.placeholder;
                addressInput.placeholder = '⏳ Mencari alamat otomatis...';
                addressInput.readOnly = true;

                try {
                    const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}&zoom=18`, {
                        headers: {
                            'Accept-Language': 'id-ID,id;q=0.9,en;q=0.8'
                        }
                    });
                    if (res.ok) {
                        const data = await res.json();
                        if (data && data.display_name) {
                            addressInput.value = data.display_name;
                        }
                    }
                } catch (error) {
                    console.error('Gagal memuat geocoding:', error);
                } finally {
                    addressInput.placeholder = originalPlaceholder;
                    addressInput.readOnly = false;
                }
            }

            function setMarker(lat, lng, triggerGeocode = false) {
                if (marker) map.removeLayer(marker);
                marker = L.marker([lat, lng], {
                    icon: L.divIcon({
                        html: '<div style="background:#16a34a;width:20px;height:20px;border-radius:50%;border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.3)"></div>',
                        iconSize: [20, 20],
                        iconAnchor: [10, 10],
                        className: ''
                    })
                }).addTo(map);

                document.getElementById('latitude').value  = lat.toFixed(7);
                document.getElementById('longitude').value = lng.toFixed(7);
                document.getElementById('coords-text').textContent = lat.toFixed(6) + ', ' + lng.toFixed(6);
                document.getElementById('coords-display').classList.remove('hidden');

                if (triggerGeocode) {
                    reverseGeocode(lat, lng);
                }
            }

            map.on('click', function(e) {
                setMarker(e.latlng.lat, e.latlng.lng, true);
            });

            @if(old('latitude'))
                setMarker({{ old('latitude') }}, {{ old('longitude') }}, false);
                map.setView([{{ old('latitude') }}, {{ old('longitude') }}], 15);
            @endif

            // ── GPS Location ───────────────────────────────────────────────
            function getGpsLocation() {
                const btn     = document.getElementById('btn-gps');
                const btnText = document.getElementById('gps-btn-text');

                if (!navigator.geolocation) {
                    alert('Browser kamu tidak mendukung GPS. Klik peta untuk pilih lokasi manual.');
                    return;
                }

                btnText.textContent = 'Mencari lokasi...';
                btn.disabled = true;

                navigator.geolocation.getCurrentPosition(
                    function(pos) {
                        const lat = pos.coords.latitude;
                        const lng = pos.coords.longitude;
                        setMarker(lat, lng, true);
                        map.setView([lat, lng], 16);
                        btnText.textContent = 'Lokasi Ditemukan ✓';
                        btn.classList.replace('bg-sky-500', 'bg-green-600');
                        btn.classList.replace('hover:bg-sky-600', 'hover:bg-green-700');
                        setTimeout(() => {
                            btnText.textContent = 'Gunakan Lokasi Saya';
                            btn.disabled = false;
                            btn.classList.replace('bg-green-600', 'bg-sky-500');
                            btn.classList.replace('hover:bg-green-700', 'hover:bg-sky-600');
                        }, 3000);
                    },
                    function(err) {
                        alert('Gagal mendapatkan lokasi GPS. Klik peta untuk pilih manual.');
                        btnText.textContent = 'Gunakan Lokasi Saya';
                        btn.disabled = false;
                    },
                    { timeout: 10000, enableHighAccuracy: true }
                );
            }

            // ── Geocoding Search ───────────────────────────────────────────
            let geocodeTimeout = null;

            document.getElementById('geocode-input').addEventListener('input', function() {
                clearTimeout(geocodeTimeout);
                const q = this.value.trim();
                if (q.length < 3) { document.getElementById('geocode-results').classList.add('hidden'); return; }
                geocodeTimeout = setTimeout(() => geocodeAddress(false), 500);
            });

            async function geocodeAddress(fly = true) {
                const input = document.getElementById('geocode-input').value.trim();
                if (!input) return;

                const resultsEl = document.getElementById('geocode-results');
                resultsEl.innerHTML = '<div class="px-3 py-2 text-xs text-gray-400 animate-pulse">🔍 Mencari...</div>';
                resultsEl.classList.remove('hidden');

                try {
                    const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(input)}&countrycodes=id&limit=5`;
                    const res = await fetch(url, { headers: { 'Accept-Language': 'id' } });
                    const data = await res.json();

                    if (!data.length) {
                        resultsEl.innerHTML = '<div class="px-3 py-2 text-xs text-red-400">Lokasi tidak ditemukan.</div>';
                        return;
                    }

                    resultsEl.innerHTML = data.map(item => `
                        <div class="geocode-item px-3 py-2.5 text-xs text-gray-700 hover:bg-green-50 cursor-pointer border-b border-gray-100 last:border-0 transition-colors"
                             data-lat="${item.lat}" data-lng="${item.lon}" data-name="${item.display_name}">
                            <span class="text-green-600 mr-1">📍</span>${item.display_name}
                        </div>
                    `).join('');

                    if (fly) {
                        const first = data[0];
                        flyToLocation(parseFloat(first.lat), parseFloat(first.lon), first.display_name);
                        if (data.length === 1) resultsEl.classList.add('hidden');
                    }

                    resultsEl.querySelectorAll('.geocode-item').forEach(el => {
                        el.addEventListener('click', () => {
                            flyToLocation(parseFloat(el.dataset.lat), parseFloat(el.dataset.lng), el.dataset.name);
                            document.getElementById('geocode-input').value = el.dataset.name.split(',')[0];
                            resultsEl.classList.add('hidden');
                        });
                    });
                } catch(err) {
                    resultsEl.innerHTML = '<div class="px-3 py-2 text-xs text-red-400">Error geocoding.</div>';
                }
            }

            function flyToLocation(lat, lng, name) {
                setMarker(lat, lng);
                map.flyTo([lat, lng], 16, { duration: 1.2 });
                const addrInput = document.getElementById('address');
                if (!addrInput.value) {
                    addrInput.value = name.split(',').slice(0, 3).join(',').trim();
                }
            }

            document.addEventListener('click', function(e) {
                if (!document.getElementById('geocode-container').contains(e.target)) {
                    document.getElementById('geocode-results').classList.add('hidden');
                }
            });

            // ── Photo Upload Previews (Max 3) ──────────────────────────────────
            let selectedFiles = [];

            function previewPhotos(event) {
                const newFiles = Array.from(event.target.files);
                selectedFiles = [...selectedFiles, ...newFiles].slice(0, 3);
                renderPreviews();
            }

            function capturePhoto(event) {
                const newFiles = Array.from(event.target.files);
                if (newFiles.length === 0) return;
                selectedFiles = [...selectedFiles, ...newFiles].slice(0, 3);
                renderPreviews();
                event.target.value = ''; // Reset camera input
            }

            function renderPreviews() {
                const grid = document.getElementById('photo-preview-grid');
                grid.innerHTML = '';

                const dt = new DataTransfer();

                selectedFiles.forEach((file, index) => {
                    dt.items.add(file);

                    const imgUrl = URL.createObjectURL(file);
                    const item = document.createElement('div');
                    item.className = 'photo-preview-item';
                    item.innerHTML = `
                        <img src="${imgUrl}" alt="Preview">
                        <button class="remove-btn" type="button" onclick="removePhoto(${index})">×</button>
                    `;
                    grid.appendChild(item);
                });

                document.getElementById('photos').files = dt.files;
            }

            function removePhoto(index) {
                selectedFiles.splice(index, 1);
                renderPreviews();
            }

            // ── Form submit loading state ──────────────────────────────────
            document.getElementById('report-form').addEventListener('submit', function() {
                const btn = document.getElementById('submit-btn');
                btn.disabled = true;
                btn.innerHTML =
                    '<svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">' +
                    '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>' +
                    '<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>' +
                    '</svg> Mengirim laporan...';
            });
        </script>
    @endpush
</x-app-layout>
