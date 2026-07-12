<x-admin-layout>
    @section('title', 'Edit Hadiah')

    <div class="max-w-2xl">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('admin.rewards.index') }}" class="text-sm text-gray-400 hover:text-red-600 transition-colors">← Kembali</a>
            <span class="text-gray-300">/</span>
            <h1 class="text-xl font-bold text-gray-900">Edit Hadiah</h1>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <form method="POST" action="{{ route('admin.rewards.update', $reward) }}" class="space-y-5">
                @csrf @method('PUT')

                <div class="grid grid-cols-2 gap-5">
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Hadiah <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $reward->name) }}" required
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-300 @error('name') border-red-400 @enderror">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="3" required
                                  class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-300 resize-none @error('description') border-red-400 @enderror">{{ old('description', $reward->description) }}</textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Poin Dibutuhkan <span class="text-red-500">*</span></label>
                        <input type="number" name="points_required" value="{{ old('points_required', $reward->points_required) }}" required min="1"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-300">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tipe Hadiah <span class="text-red-500">*</span></label>
                        <select name="type" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-300 bg-white">
                            <option value="certificate" {{ old('type', $reward->type) === 'certificate' ? 'selected' : '' }}>📜 Sertifikat</option>
                            <option value="merchandise" {{ old('type', $reward->type) === 'merchandise' ? 'selected' : '' }}>👕 Merchandise</option>
                            <option value="voucher"     {{ old('type', $reward->type) === 'voucher'     ? 'selected' : '' }}>🎟️ Voucher</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Ikon (Emoji)</label>
                        <input type="text" name="icon" value="{{ old('icon', $reward->icon) }}" maxlength="10"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-300">
                    </div>

                    <div class="flex items-center gap-3 pt-6">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $reward->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 accent-red-600">
                        <label for="is_active" class="text-sm font-semibold text-gray-700">Hadiah aktif</label>
                    </div>
                </div>

                <div class="flex gap-3 pt-2 border-t border-gray-100">
                    <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-xl transition-colors shadow-sm">
                        Perbarui Hadiah
                    </button>
                    <a href="{{ route('admin.rewards.index') }}" class="px-6 py-2.5 border border-gray-200 text-gray-500 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
