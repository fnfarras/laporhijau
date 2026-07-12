<x-admin-layout>
    @section('title', 'Edit Event')

    <div class="max-w-2xl">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('admin.events.index') }}" class="text-sm text-gray-400 hover:text-red-600 transition-colors">← Kembali</a>
            <span class="text-gray-300">/</span>
            <h1 class="text-xl font-bold text-gray-900">Edit Event</h1>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <form method="POST" action="{{ route('admin.events.update', $event) }}" class="space-y-5">
                @csrf @method('PUT')

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Judul Event <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $event->title) }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-300 @error('title') border-red-400 @enderror">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="4" required
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-300 resize-none @error('description') border-red-400 @enderror">{{ old('description', $event->description) }}</textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal & Waktu <span class="text-red-500">*</span></label>
                        <input type="datetime-local" name="event_date"
                               value="{{ old('event_date', $event->event_date->format('Y-m-d\TH:i')) }}" required
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-300">
                        @error('event_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Maks. Peserta</label>
                        <input type="number" name="max_participants" value="{{ old('max_participants', $event->max_participants) }}" min="1"
                               placeholder="Kosongkan = tidak terbatas"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-300">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Lokasi <span class="text-red-500">*</span></label>
                    <input type="text" name="location" value="{{ old('location', $event->location) }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-300 @error('location') border-red-400 @enderror">
                    @error('location') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kategori <span class="text-red-500">*</span></label>
                    <select name="category" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-300 bg-white">
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category', $event->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                @if ($resolvedReports->isNotEmpty())
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tautkan ke Laporan</label>
                    <select name="report_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-300 bg-white">
                        <option value="">-- Tidak ditautkan --</option>
                        @foreach ($resolvedReports as $report)
                            <option value="{{ $report->id }}" {{ old('report_id', $event->report_id) == $report->id ? 'selected' : '' }}>
                                #{{ $report->id }} — {{ Str::limit($report->title, 60) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Info event saat ini --}}
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-xs text-gray-500 space-y-1">
                    <p>Penyelenggara: <strong>{{ $event->organizer?->name ?? '—' }}</strong></p>
                    <p>Peserta terdaftar: <strong>{{ $event->activeParticipants()->count() }} orang</strong></p>
                    <p>Dibuat: {{ $event->created_at->format('d M Y, H:i') }}</p>
                </div>

                <div class="flex gap-3 pt-2 border-t border-gray-100">
                    <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-xl transition-colors shadow-sm">
                        Perbarui Event
                    </button>
                    <a href="{{ route('event.show', $event) }}" target="_blank"
                       class="px-6 py-2.5 border border-gray-200 text-gray-500 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-colors">
                        👁️ Lihat Event
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
