<x-admin-layout>
    @section('title', 'Buat Event Baru')

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
</x-admin-layout>
