<x-admin-layout>
    @section('title', 'Buat Event Baru')

    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <style>
        #create-map { height: 280px; border-radius: 12px; z-index: 1; }
    </style>
    @endpush

    <div class="max-w-2xl">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('admin.events.index') }}" class="text-sm text-gray-400 hover:text-green-600 transition-colors">← Kembali</a>
            <span class="text-gray-300">/</span>
            <h1 class="text-xl font-bold text-gray-900">Buat Event Aksi Baru</h1>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <form method="POST" action="{{ route('admin.events.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Judul Event <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           placeholder="Contoh: Bersih-bersih Pantai Dumai"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-300 @error('title') border-red-400 @enderror">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="4" required
                              placeholder="Deskripsi lengkap event, tujuan, dan hal yang perlu dibawa peserta..."
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-300 resize-none @error('description') border-red-400 @enderror">{{ old('description') }}</textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal & Waktu <span class="text-red-500">*</span></label>
                        <input type="datetime-local" name="event_date" value="{{ old('event_date') }}" required
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-300 @error('event_date') border-red-400 @enderror">
                        @error('event_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Maks. Peserta</label>
                        <input type="number" name="max_participants" value="{{ old('max_participants') }}" min="1"
                               placeholder="Kosongkan = tidak terbatas"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-300">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Lokasi <span class="text-red-500">*</span></label>
                    <input type="text" name="location" value="{{ old('location') }}" required
                           placeholder="Contoh: Pantai Teluk Makmur, Dumai"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-300 @error('location') border-red-400 @enderror">
                    @error('location') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Koordinat GPS <span class="text-xs text-gray-400 font-normal">(opsional)</span></label>
                    <button type="button" id="btn-gps" onclick="getMyLocation()"
                            class="inline-flex items-center gap-2 px-3 py-2 text-xs font-bold text-green-700 bg-green-50 hover:bg-green-100 border border-green-200 rounded-lg transition-all mb-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        Gunakan Lokasi Saya
                    </button>
                    <p class="text-[10px] text-gray-400 mb-2">atau klik peta untuk pilih lokasi</p>
                    <div id="create-map" class="border border-gray-200"></div>
                    <input type="hidden" id="latitude"  name="latitude"  value="{{ old('latitude') }}">
                    <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">
                    <p id="coords-display" class="text-xs text-green-600 font-semibold mt-1 hidden"></p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kategori <span class="text-red-500">*</span></label>
                    <select name="category" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-300 bg-white @error('category') border-red-400 @enderror">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                    @error('category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                @if ($resolvedReports->isNotEmpty())
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tautkan ke Laporan (Opsional)</label>
                    <select name="report_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-300 bg-white">
                        <option value="">-- Tidak ditautkan --</option>
                        @foreach ($resolvedReports as $report)
                            <option value="{{ $report->id }}" {{ old('report_id') == $report->id ? 'selected' : '' }}>
                                #{{ $report->id }} — {{ Str::limit($report->title, 60) }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-400 mt-1">Tautkan event ini ke laporan yang sudah diselesaikan sebagai tindak lanjut.</p>
                </div>
                @endif

                <div class="flex gap-3 pt-2 border-t border-gray-100">
                    <button type="submit" class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-xl transition-colors shadow-sm">
                        Buat Event
                    </button>
                    <a href="{{ route('admin.events.index') }}" class="px-6 py-2.5 border border-gray-200 text-gray-500 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        let map, marker;

        document.addEventListener('DOMContentLoaded', function () {
            const defaultLat = {{ old('latitude', -0.9471) }};
            const defaultLng = {{ old('longitude', 100.4172) }};

            map = L.map('create-map').setView([defaultLat, defaultLng], 12);
            L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                attribution: '© OpenStreetMap © CARTO'
            }).addTo(map);

            @if (old('latitude') && old('longitude'))
                setMarker({{ old('latitude') }}, {{ old('longitude') }});
            @endif

            map.on('click', function (e) {
                setMarker(e.latlng.lat, e.latlng.lng);
            });
        });

        function setMarker(lat, lng) {
            if (marker) map.removeLayer(marker);
            marker = L.marker([lat, lng], {
                icon: L.divIcon({
                    html: `<div style="background:#16a34a;width:30px;height:30px;border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-size:14px;border:2px solid white;box-shadow:0 2px 6px rgba(0,0,0,0.3)">📍</div>`,
                    className: '',
                    iconSize: [30, 30],
                    iconAnchor: [15, 15],
                }),
                draggable: true
            }).addTo(map);

            document.getElementById('latitude').value  = lat.toFixed(7);
            document.getElementById('longitude').value = lng.toFixed(7);

            const display = document.getElementById('coords-display');
            display.textContent = `📌 ${lat.toFixed(5)}, ${lng.toFixed(5)}`;
            display.classList.remove('hidden');

            marker.on('dragend', function(e) {
                const pos = e.target.getLatLng();
                setMarker(pos.lat, pos.lng);
            });
        }

        function getMyLocation() {
            if (!navigator.geolocation) {
                alert('Browser kamu tidak mendukung geolokasi.');
                return;
            }
            const btn = document.getElementById('btn-gps');
            btn.textContent = '⏳ Mendapatkan lokasi...';
            btn.disabled = true;

            navigator.geolocation.getCurrentPosition(function (pos) {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                map.setView([lat, lng], 15);
                setMarker(lat, lng);
                btn.innerHTML = '<svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Lokasi didapat';
                btn.disabled = false;
            }, function () {
                alert('Tidak dapat mengakses lokasi. Pastikan izin lokasi diaktifkan.');
                btn.textContent = '📍 Gunakan Lokasi Saya';
                btn.disabled = false;
            });
        }
    </script>
    @endpush
</x-admin-layout>
