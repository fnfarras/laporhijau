<x-admin-layout>
    @section('title', 'Manajemen Kategori')

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Kategori</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola kategori laporan lingkungan</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Form Tambah Kategori --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 sticky top-24">
                <h2 class="text-sm font-bold text-gray-800 mb-4">Tambah Kategori Baru</h2>
                <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">Nama Kategori <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               placeholder="Contoh: Sampah & Kebersihan"
                               class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-300 @error('name') border-red-400 @enderror">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">Ikon (Emoji) <span class="text-red-500">*</span></label>
                        <input type="text" name="icon" value="{{ old('icon') }}" required maxlength="10"
                               placeholder="🗑️"
                               class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-300 @error('icon') border-red-400 @enderror">
                        @error('icon') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit" class="w-full py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-xl transition-colors">
                        + Tambah Kategori
                    </button>
                </form>
            </div>
        </div>

        {{-- Daftar Kategori --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-50">
                    <h2 class="text-sm font-bold text-gray-800">{{ $categories->count() }} Kategori Terdaftar</h2>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse ($categories as $category)
                        <div class="flex items-center gap-4 px-5 py-3.5">
                            <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center text-xl flex-shrink-0">
                                {{ $category->icon }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-800 text-sm">{{ $category->name }}</p>
                                <p class="text-xs text-gray-400">{{ $category->reports_count }} laporan</p>
                            </div>

                            {{-- Form Edit Inline --}}
                            <form method="POST" action="{{ route('admin.categories.update', $category) }}"
                                  class="flex items-center gap-2 flex-shrink-0" id="edit-form-{{ $category->id }}">
                                @csrf @method('PUT')
                                <input type="text" name="icon" value="{{ $category->icon }}" maxlength="10"
                                       class="w-12 px-2 py-1.5 border border-gray-200 rounded-lg text-sm text-center focus:outline-none focus:ring-1 focus:ring-green-400">
                                <input type="text" name="name" value="{{ $category->name }}"
                                       class="w-40 px-3 py-1.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-green-400">
                                <button type="submit" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition-colors">
                                    Simpan
                                </button>
                            </form>

                            {{-- Hapus --}}
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                  onsubmit="return confirm('Hapus kategori \'{{ $category->name }}\'? Pastikan tidak ada laporan yang menggunakan kategori ini.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-red-400 hover:text-green-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="p-6">
                            <x-empty-state icon="📂" title="Belum Ada Kategori" message="Tambahkan kategori laporan pertama agar pengguna bisa mengkategorikan masalah lingkungan." />
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
