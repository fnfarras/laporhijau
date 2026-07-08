<x-app-layout>
    @section('title', 'Buat Laporan')

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            #map { height: 220px; border-radius: 12px; z-index: 1; }
            .photo-preview-item {
                position: relative;
                border-radius: 8px;
                overflow: hidden;
                aspect-ratio: 1;
            }
            .photo-preview-item img { width: 100%; height: 100%; object-fit: cover; }
            .photo-preview-item .remove-btn {
                position: absolute; top: 4px; right: 4px;
                background: rgba(0,0,0,0.6); color: white;
                border: none; border-radius: 50%;
                width: 24px; height: 24px;
                cursor: pointer; font-size: 14px;
                display: flex; align-items: center; justify-content: center;
            }
        </style>
    @endpush

    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('masyarakat.laporan') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <nav class="flex items-center text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-slate-500 mb-1.5 no-print">
                    <a href="{{ route('home') }}" class="hover:text-green-600 transition-colors">Beranda</a>
                    <span class="mx-1.5">/</span>
                    <a href="{{ route('masyarakat.laporan') }}" class="hover:text-green-600 transition-colors">Laporan</a>
                    <span class="mx-1.5">/</span>
                    <span class="text-gray-500">Buat Laporan</span>
                </nav>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                    Buat Laporan Masalah Lingkungan
                </h2>
            </div>
    </x-slot>

    <div class="py-8 pb-28" style="font-family: 'Plus Jakarta Sans', sans-serif;" 
         x-data="{ step: {{ $errors->has('photos') || $errors->has('photos.*') ? 3 : ($errors->has('address') || $errors->has('latitude') || $errors->has('longitude') ? 2 : 1) }} }" 
         x-init="$watch('step', val => { if(val === 2) { setTimeout(() => { window.map && window.map.invalidateSize() }, 150) } })">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Progress Bar Step Wizard -->
            <div class="mb-6 bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700/80 p-4.5 shadow-sm">
                <div class="flex items-center justify-between text-xs font-extrabold text-gray-500 dark:text-gray-400 mb-2.5">
                    <span :class="{'text-green-600 dark:text-green-400': step >= 1}">1. Detail Laporan</span>
                    <span :class="{'text-green-600 dark:text-green-400': step >= 2}">2. Lokasi Kejadian</span>
                    <span :class="{'text-green-600 dark:text-green-400': step >= 3}">3. Foto Bukti</span>
                </div>
                <div class="w-full bg-gray-100 dark:bg-slate-700 h-2.5 rounded-full overflow-hidden">
                    <div class="bg-green-600 h-full transition-all duration-300" :style="'width: ' + (step === 1 ? '33.33%' : (step === 2 ? '66.66%' : '100%'))"></div>
                </div>
            </div>

            <form method="POST" action="{{ route('laporan.store') }}" enctype="multipart/form-data" id="report-form">
                @csrf

                {{-- ── Error Summary ──────────────────────────────────── --}}
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                        <p class="font-semibold text-red-700 mb-2">Ada kesalahan pada form:</p>
                        <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- ── Card: Informasi Laporan ────────────────────────── --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6" x-show="step === 1" x-transition>
                    <h3 class="text-base font-bold text-gray-800 mb-5 pb-3 border-b border-gray-100">
                        📋 Informasi Laporan
                    </h3>

                    {{-- Judul --}}
                    <div class="mb-5">
                        <label for="title" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Judul Laporan <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="title"
                            name="title"
                            value="{{ old('title') }}"
                            placeholder="cth: Tumpukan sampah di Jl. Merdeka No. 12"
                            class="w-full px-4 py-3 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition {{ $errors->has('title') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}"
                        >
                        @error('title')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kategori --}}
                    <div class="mb-5">
                        <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Kategori Masalah <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="category_id"
                            name="category_id"
                            class="w-full px-4 py-3 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition bg-white {{ $errors->has('category_id') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}"
                        >
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->icon }} {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Deskripsi Masalah <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            id="description"
                            name="description"
                            rows="5"
                            placeholder="Jelaskan masalah lingkungan yang kamu temukan secara detail..."
                            class="w-full px-4 py-3 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition resize-none {{ $errors->has('description') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}"
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- ── Card: Lokasi ───────────────────────────────────── --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6" x-show="step === 2" x-transition style="display: none;">
                    <h3 class="text-base font-bold text-gray-800 mb-2 pb-3 border-b border-gray-100">
                        📍 Lokasi Kejadian
                    </h3>
                    <p class="text-xs text-gray-500 mb-4">Klik tombol GPS atau klik langsung pada peta untuk menentukan lokasi.</p>

                    {{-- Geocoding Search Box (Nominatim - Gratis) --}}
                    <div class="relative mb-3" id="geocode-container">
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">🔍 Cari Alamat (ketik lalu tekan Enter)</label>
                        <div class="flex gap-2">
                            <input type="text" id="geocode-input"
                                   placeholder="cth: Jl. Sudirman, Padang atau nama tempat..."
                                   class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <button type="button" onclick="geocodeAddress()"
                                    class="px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-xl transition-all">
                                Cari
                            </button>
                        </div>
                        <div id="geocode-results" class="absolute z-50 w-full bg-white border border-gray-200 rounded-xl shadow-lg mt-1 hidden max-h-48 overflow-y-auto"></div>
                        <p class="text-[10px] text-gray-400 mt-1">Powered by OpenStreetMap Nominatim — Gratis & tanpa API key</p>
                    </div>

                    {{-- GPS Button --}}
                    <button
                        type="button"
                        id="btn-gps"
                        onclick="getGpsLocation()"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold rounded-lg mb-4 transition-colors"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span id="gps-btn-text">Gunakan Lokasi Saya</span>
                    </button>

                    {{-- Leaflet Map --}}
                    <div id="map" class="mb-4 border border-gray-200"></div>


                    {{-- Address --}}
                    <div class="mb-4">
                        <label for="address" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Alamat / Keterangan Lokasi <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="address"
                            name="address"
                            value="{{ old('address') }}"
                            placeholder="cth: Jl. Merdeka No. 12, Kel. Sukamaju, Kec. Riau"
                            class="w-full px-4 py-3 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition {{ $errors->has('address') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}"
                        >
                        @error('address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Hidden: lat/lng --}}
                    <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
                    <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">

                    @error('latitude')
                        <p class="text-red-500 text-xs -mt-2 mb-2">{{ $message }}</p>
                    @enderror

                    {{-- Koordinat info --}}
                    <div id="coords-display" class="text-xs text-gray-400 {{ old('latitude') ? '' : 'hidden' }}">
                        📌 Koordinat: <span id="coords-text">
                            {{ old('latitude') ? old('latitude').', '.old('longitude') : '' }}
                        </span>
                    </div>
                </div>

                {{-- ── Card: Foto ─────────────────────────────────────── --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6" x-show="step === 3" x-transition style="display: none;">
                    <h3 class="text-base font-bold text-gray-800 mb-2 pb-3 border-b border-gray-100">
                        📷 Foto Bukti
                    </h3>
                    <p class="text-xs text-gray-500 mb-4">Maksimal 5 foto (JPG, PNG, WebP), ukuran maks 5MB per foto.</p>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        {{-- Button Ambil dari Kamera --}}
                        <button
                            type="button"
                            onclick="document.getElementById('camera-capture').click()"
                            class="flex flex-col items-center justify-center p-6 border-2 border-dashed border-gray-300 dark:border-slate-700 rounded-2xl hover:border-green-500 hover:bg-green-50/50 dark:hover:bg-slate-800/40 transition-all cursor-pointer text-center group"
                        >
                            <span class="text-3xl mb-2 group-hover:scale-110 transition-transform">📸</span>
                            <span class="text-xs font-bold text-gray-700 dark:text-gray-300">Ambil Foto</span>
                            <span class="text-[9px] text-gray-400 mt-0.5">Buka Kamera HP</span>
                        </button>

                        {{-- Button Pilih dari Galeri --}}
                        <button
                            type="button"
                            onclick="document.getElementById('photos').click()"
                            class="flex flex-col items-center justify-center p-6 border-2 border-dashed border-gray-300 dark:border-slate-700 rounded-2xl hover:border-green-500 hover:bg-green-50/50 dark:hover:bg-slate-800/40 transition-all cursor-pointer text-center group"
                        >
                            <span class="text-3xl mb-2 group-hover:scale-110 transition-transform">🖼️</span>
                            <span class="text-xs font-bold text-gray-700 dark:text-gray-300">Pilih Galeri</span>
                            <span class="text-[9px] text-gray-400 mt-0.5">Pilih dari File/Galeri</span>
                        </button>
                    </div>

                    <input
                        type="file"
                        id="photos"
                        name="photos[]"
                        multiple
                        accept="image/*"
                        class="hidden"
                        onchange="previewPhotos(event)"
                    >

                    <input
                        type="file"
                        id="camera-capture"
                        accept="image/*"
                        capture="environment"
                        class="hidden"
                        onchange="capturePhoto(event)"
                    >

                    <div id="photo-preview-grid" class="grid grid-cols-5 gap-2">
                        {{-- Preview thumbnails rendered by JS --}}
                    </div>

                    @error('photos')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                    @error('photos.*')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

            </form>
        </div>

        {{-- ── Sticky Submit Bar ────────────────────────────────────── --}}
        <div class="fixed bottom-0 left-0 right-0 z-50 bg-white dark:bg-slate-800 border-t border-gray-200 dark:border-slate-700/80 shadow-2xl transition-colors">
            <div class="max-w-3xl mx-auto px-4 py-4 flex items-center justify-between gap-4">
                <p class="text-xs text-gray-500 dark:text-gray-400 hidden sm:block">+5 poin setelah laporan terkirim 🌿</p>
                
                <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                    {{-- Kembali --}}
                    <button
                        type="button"
                        x-show="step > 1"
                        @click="step--"
                        class="px-5 py-3 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 font-bold text-sm rounded-xl transition-all w-1/2 sm:w-auto text-center"
                        style="display: none;"
                    >
                        Kembali
                    </button>
 
                    {{-- Lanjut --}}
                    <button
                        type="button"
                        x-show="step < 3"
                        @click="step++"
                        class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold text-sm rounded-xl transition-all shadow-md shadow-green-200 dark:shadow-none w-1/2 sm:w-auto text-center"
                    >
                        Lanjut
                    </button>
 
                    {{-- Kirim Laporan --}}
                    <button
                        form="report-form"
                        type="submit"
                        id="submit-btn"
                        x-show="step === 3"
                        class="px-8 py-3 bg-green-600 hover:bg-green-700 active:bg-green-800 text-white font-bold text-sm rounded-xl transition-colors flex items-center justify-center gap-2 shadow-lg shadow-green-200 dark:shadow-none w-1/2 sm:w-auto text-center"
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

            // Klik peta untuk pilih koordinat manual
            map.on('click', function(e) {
                setMarker(e.latlng.lat, e.latlng.lng, true);
            });

            // Restore marker jika ada old value (form validation failed)
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

            // ── Photo Preview ──────────────────────────────────────────────
            let selectedFiles = [];

            function previewPhotos(event) {
                const newFiles = Array.from(event.target.files);

                // Gabungkan dengan file sebelumnya, max 5
                selectedFiles = [...selectedFiles, ...newFiles].slice(0, 5);

                renderPreviews();
            }

            function capturePhoto(event) {
                const newFiles = Array.from(event.target.files);
                if (newFiles.length === 0) return;

                // Gabungkan dengan file sebelumnya, max 5
                selectedFiles = [...selectedFiles, ...newFiles].slice(0, 5);

                renderPreviews();

                // Reset camera input agar bisa digunakan kembali
                event.target.value = '';
            }

            function renderPreviews() {
                const grid = document.getElementById('photo-preview-grid');
                grid.innerHTML = '';

                // Buat DataTransfer baru untuk input file
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

            // ── Geocoding dengan Nominatim (Gratis, tanpa API key) ─────────
            let geocodeTimeout = null;

            document.getElementById('geocode-input').addEventListener('keydown', function(e) {
                if (e.key === 'Enter') { e.preventDefault(); geocodeAddress(); }
            });

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
                        resultsEl.innerHTML = '<div class="px-3 py-2 text-xs text-red-400">Lokasi tidak ditemukan. Coba kata kunci lain.</div>';
                        return;
                    }

                    resultsEl.innerHTML = data.map(item => `
                        <div class="geocode-item px-3 py-2.5 text-xs text-gray-700 hover:bg-green-50 cursor-pointer border-b border-gray-100 last:border-0 transition-colors"
                             data-lat="${item.lat}" data-lng="${item.lon}" data-name="${item.display_name}">
                            <span class="text-green-600 mr-1">📍</span>${item.display_name}
                        </div>
                    `).join('');

                    // Auto-fly ke hasil pertama jika tekan Cari
                    if (fly) {
                        const first = data[0];
                        flyToLocation(parseFloat(first.lat), parseFloat(first.lon), first.display_name);
                        if (data.length === 1) resultsEl.classList.add('hidden');
                    }

                    // Click handler untuk setiap hasil
                    resultsEl.querySelectorAll('.geocode-item').forEach(el => {
                        el.addEventListener('click', () => {
                            flyToLocation(parseFloat(el.dataset.lat), parseFloat(el.dataset.lng), el.dataset.name);
                            document.getElementById('geocode-input').value = el.dataset.name.split(',')[0];
                            resultsEl.classList.add('hidden');
                        });
                    });
                } catch(err) {
                    resultsEl.innerHTML = '<div class="px-3 py-2 text-xs text-red-400">Error: tidak bisa terhubung ke layanan geocoding.</div>';
                }
            }

            function flyToLocation(lat, lng, name) {
                setMarker(lat, lng);
                map.flyTo([lat, lng], 16, { duration: 1.2 });
                // Auto-isi field address jika masih kosong
                const addrInput = document.getElementById('address');
                if (!addrInput.value) {
                    addrInput.value = name.split(',').slice(0, 3).join(',').trim();
                }
            }

            // Tutup dropdown jika klik di luar
            document.addEventListener('click', function(e) {
                if (!document.getElementById('geocode-container').contains(e.target)) {
                    document.getElementById('geocode-results').classList.add('hidden');
                }
            });

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

