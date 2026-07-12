<x-admin-layout>
    @section('title', 'Tulis Artikel Baru')

    @push('styles')
    <style>
        #content { font-family: 'Plus Jakarta Sans', monospace; }
    </style>
    @endpush

    <div class="max-w-4xl">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('admin.articles.index') }}" class="text-sm text-gray-400 hover:text-green-600 transition-colors">← Kembali</a>
            <span class="text-gray-300">/</span>
            <h1 class="text-xl font-bold text-gray-900">Tulis Artikel Baru</h1>
        </div>

        <form method="POST" action="{{ route('admin.articles.store') }}">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Konten Utama --}}
                <div class="lg:col-span-2 space-y-5">
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Judul Artikel <span class="text-red-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title') }}" required
                                   placeholder="Tulis judul artikel yang menarik..."
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-300 text-lg font-semibold @error('title') border-red-400 @enderror">
                            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Konten <span class="text-red-500">*</span></label>
                            <textarea name="content" id="content" rows="18" required
                                      placeholder="Tulis isi artikel di sini. Gunakan baris baru untuk paragraf baru. Minimal 50 karakter..."
                                      class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-300 resize-y leading-relaxed @error('content') border-red-400 @enderror">{{ old('content') }}</textarea>
                            @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            <p class="text-xs text-gray-400 mt-1" id="char-count">0 karakter</p>
                        </div>
                    </div>
                </div>

                {{-- Sidebar Pengaturan --}}
                <div class="space-y-4">
                    {{-- Publikasi --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <h3 class="text-sm font-bold text-gray-800 mb-4">Publikasi</h3>
                        <div class="flex items-center gap-3 mb-5 p-3 bg-green-50 rounded-xl border border-green-100">
                            <input type="checkbox" name="publish" id="publish" value="1" {{ old('publish') ? 'checked' : '' }}
                                   class="w-4 h-4 accent-green-600">
                            <label for="publish" class="text-sm font-semibold text-green-700">Publikasikan sekarang</label>
                        </div>
                        <p class="text-xs text-gray-400 mb-5">Jika tidak dicentang, artikel akan disimpan sebagai <strong>Draft</strong>.</p>
                        <div class="flex flex-col gap-2">
                            <button type="submit" class="w-full py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-xl transition-colors shadow-sm">
                                Simpan Artikel
                            </button>
                            <a href="{{ route('admin.articles.index') }}" class="w-full py-2.5 text-center border border-gray-200 text-gray-500 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-colors">
                                Batal
                            </a>
                        </div>
                    </div>

                    {{-- Kategori --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <h3 class="text-sm font-bold text-gray-800 mb-3">Kategori <span class="text-red-500">*</span></h3>
                        <div class="space-y-2">
                            @foreach ($categories as $cat)
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                    <input type="radio" name="category" value="{{ $cat }}" {{ old('category') === $cat ? 'checked' : '' }}
                                           class="w-4 h-4 accent-green-600">
                                    <span class="text-sm text-gray-700 group-hover:text-green-600 transition-colors">{{ $cat }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('category') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        const content = document.getElementById('content');
        const charCount = document.getElementById('char-count');
        function updateCount() {
            const len = content.value.length;
            charCount.textContent = len.toLocaleString() + ' karakter';
            charCount.className = 'text-xs mt-1 ' + (len < 50 ? 'text-red-400' : 'text-gray-400');
        }
        content.addEventListener('input', updateCount);
        updateCount();
    </script>
    @endpush
</x-admin-layout>
