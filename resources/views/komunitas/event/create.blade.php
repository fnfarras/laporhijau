<x-app-layout>
    @section('title', 'Buat Event - LaporHijau')

    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        #create-map { height: 280px; border-radius: 12px; z-index: 1; }
        .form-label { display: block; font-size: 13px; font-weight: 700; color: #374151; margin-bottom: 6px; }
        .form-input {
            width: 100%; padding: 10px 14px;
            border: 1.5px solid #e5e7eb; border-radius: 10px;
            font-size: 14px; color: #111827; outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .form-input:focus { border-color: #16a34a; box-shadow: 0 0 0 3px rgba(22,163,74,0.1); }
        .form-input.is-invalid { border-color: #ef4444; }
        .error-msg { font-size: 11px; color: #ef4444; margin-top: 4px; }
    </style>
    @endpush

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-6">
                <a href="{{ route('event.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-green-700 transition-colors mb-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Kembali ke Event
                </a>
                <h1 class="text-2xl font-bold text-gray-900">✨ Buat Event Baru</h1>
                <p class="text-sm text-gray-500 mt-1">Ajak komunitas untuk bergerak bersama menjaga lingkungan</p>
            </div>

            <form method="POST" action="{{ route('event.store') }}" class="space-y-5">
                @csrf

                {{-- Judul --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="font-bold text-gray-800 mb-4 text-sm">📌 Informasi Event</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="form-label" for="title">Judul Event <span class="text-red-400">*</span></label>
                            <input type="text" id="title" name="title"
                                   class="form-input {{ $errors->has('title') ? 'is-invalid' : '' }}"
                                   value="{{ old('title') }}"
                                   placeholder="Contoh: Bersih-Bersih Sungai Kampar">
                            @error('title') <p class="error-msg">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="form-label" for="category">Kategori <span class="text-red-400">*</span></label>
                            <select id="category" name="category" class="form-input {{ $errors->has('category') ? 'is-invalid' : '' }}">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                            @error('category') <p class="error-msg">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="form-label" for="description">Deskripsi <span class="text-red-400">*</span></label>
                            <textarea id="description" name="description" rows="4"
                                      class="form-input {{ $errors->has('description') ? 'is-invalid' : '' }}"
                                      placeholder="Jelaskan tujuan, manfaat, dan detail kegiatan event...">{{ old('description') }}</textarea>
                            @error('description') <p class="error-msg">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Lokasi --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="font-bold text-gray-800 mb-4 text-sm">📍 Lokasi & Waktu</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="form-label" for="location">Nama Lokasi <span class="text-red-400">*</span></label>
                            <input type="text" id="location" name="location"
                                   class="form-input {{ $errors->has('location') ? 'is-invalid' : '' }}"
                                   value="{{ old('location') }}"
                                   placeholder="Contoh: Tepi Sungai Kampar, Pekanbaru">
                            @error('location') <p class="error-msg">{{ $message }}</p> @enderror
                        </div>

                        {{-- GPS Picker --}}
                        <div>
                            <label class="form-label">Koordinat GPS <span class="text-xs text-gray-400 font-normal">(opsional)</span></label>
                            <button type="button" id="btn-gps"
                                    onclick="getMyLocation()"
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

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="form-label" for="event_date">Tanggal & Waktu <span class="text-red-400">*</span></label>
                                <input type="datetime-local" id="event_date" name="event_date"
                                       class="form-input {{ $errors->has('event_date') ? 'is-invalid' : '' }}"
                                       value="{{ old('event_date') }}"
                                       min="{{ now()->addHour()->format('Y-m-d\TH:i') }}">
                                @error('event_date') <p class="error-msg">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="form-label" for="max_participants">Max Peserta <span class="text-xs text-gray-400 font-normal">(opsional)</span></label>
                                <input type="number" id="max_participants" name="max_participants"
                                       class="form-input {{ $errors->has('max_participants') ? 'is-invalid' : '' }}"
                                       value="{{ old('max_participants') }}"
                                       min="1" placeholder="Kosongkan = tidak terbatas">
                                @error('max_participants') <p class="error-msg">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Laporan Terkait --}}
                @if ($resolvedReports->isNotEmpty())
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="font-bold text-gray-800 mb-4 text-sm">🔗 Laporan Terkait <span class="text-xs text-gray-400 font-normal">(opsional)</span></h2>
                    <select name="report_id" class="form-input">
                        <option value="">-- Tidak ada laporan terkait --</option>
                        @foreach ($resolvedReports as $report)
                            <option value="{{ $report->id }}" {{ old('report_id') == $report->id ? 'selected' : '' }}>
                                {{ $report->title }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-[10px] text-gray-400 mt-1">Hubungkan event dengan laporan resolved jika relevan</p>
                </div>
                @endif

                {{-- Submit --}}
                <button type="submit"
                        class="w-full py-3.5 font-extrabold text-sm rounded-xl bg-green-600 hover:bg-green-700 text-white shadow-md hover:shadow-lg transition-all">
                    🎉 Buat Event Sekarang
                </button>
            </form>

        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        let map, marker;

        document.addEventListener('DOMContentLoaded', function () {
            const defaultLat = {{ old('latitude', 0.5096) }};
            const defaultLng = {{ old('longitude', 101.4506) }};

            map = L.map('create-map').setView([defaultLat, defaultLng], 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);

            // Restore existing marker from old()
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
</x-app-layout>
