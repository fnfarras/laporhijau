<x-admin-layout>
    @section('title', 'Tambah Hadiah')

    <div class="max-w-2xl">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('admin.rewards.index') }}" class="text-sm text-gray-400 hover:text-green-600 transition-colors">← Kembali</a>
            <span class="text-gray-300">/</span>
            <h1 class="text-xl font-bold text-gray-900">Tambah Hadiah Baru</h1>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <form method="POST" action="{{ route('admin.rewards.store') }}" class="space-y-5">
                @csrf

                <div class="grid grid-cols-2 gap-5">
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Hadiah <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               placeholder="Contoh: Sertifikat Kontributor Hijau"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-300 @error('name') border-red-400 @enderror">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="3" required
                                  placeholder="Deskripsi singkat hadiah ini..."
                                  class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-300 resize-none @error('description') border-red-400 @enderror">{{ old('description') }}</textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Poin Dibutuhkan <span class="text-red-500">*</span></label>
                        <input type="number" name="points_required" value="{{ old('points_required', 100) }}" required min="1"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-300 @error('points_required') border-red-400 @enderror">
                        @error('points_required') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tipe Hadiah <span class="text-red-500">*</span></label>
                        <select name="type" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-300 bg-white @error('type') border-red-400 @enderror">
                            <option value="certificate" {{ old('type') === 'certificate' ? 'selected' : '' }}>📜 Sertifikat</option>
                            <option value="merchandise" {{ old('type') === 'merchandise' ? 'selected' : '' }}>👕 Merchandise</option>
                            <option value="voucher"     {{ old('type') === 'voucher'     ? 'selected' : '' }}>🎟️ Voucher</option>
                        </select>
                        @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Ikon (Emoji)</label>
                        <input type="text" name="icon" value="{{ old('icon', '🎁') }}" maxlength="10"
                               placeholder="🎁"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-300">
                    </div>

                    <div class="flex items-center gap-3 pt-6">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}
                               class="w-4 h-4 accent-green-600">
                        <label for="is_active" class="text-sm font-semibold text-gray-700">Aktifkan hadiah ini</label>
                    </div>
                </div>

                <div class="flex gap-3 pt-2 border-t border-gray-100">
                    <button type="submit" class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-xl transition-colors shadow-sm">
                        Simpan Hadiah
                    </button>
                    <a href="{{ route('admin.rewards.index') }}" class="px-6 py-2.5 border border-gray-200 text-gray-500 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
