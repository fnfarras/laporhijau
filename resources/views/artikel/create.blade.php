<x-app-layout>
    @section('title', 'Tulis Artikel - LaporHijau')

    @push('styles')
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
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
        #content-preview { display: none; }
        #content-preview .prose h2 { font-size: 1.2rem; font-weight: 800; color: #111827; margin: 1.5rem 0 0.75rem; }
        #content-preview .prose p { color: #4b5563; line-height: 1.8; margin-bottom: 1rem; }
        #content-preview .prose ul, #content-preview .prose ol { padding-left: 1.5rem; margin-bottom: 1rem; }
    </style>
    @endpush

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-6">
                <a href="{{ route('artikel.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-green-700 transition-colors mb-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Kembali ke Artikel
                </a>
                <h1 class="text-2xl font-bold text-gray-900">✍️ Tulis Artikel Baru</h1>
                <p class="text-sm text-gray-500 mt-1">Bagikan pengetahuanmu tentang lingkungan kepada komunitas</p>
            </div>

            <form method="POST" action="{{ route('artikel.store') }}" id="article-form" class="space-y-5">
                @csrf

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 space-y-4">
                    <h2 class="font-bold text-gray-800 text-sm">📌 Informasi Artikel</h2>

                    <div>
                        <label class="form-label" for="title">Judul Artikel <span class="text-red-400">*</span></label>
                        <input type="text" id="title" name="title"
                               class="form-input {{ $errors->has('title') ? 'is-invalid' : '' }}"
                               value="{{ old('title') }}"
                               placeholder="Masukkan judul artikel yang menarik...">
                        @error('title') <p class="error-msg">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label" for="category">Kategori <span class="text-red-400">*</span></label>
                        <select id="category" name="category"
                                class="form-input {{ $errors->has('category') ? 'is-invalid' : '' }}">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                        @error('category') <p class="error-msg">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Konten Editor --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="font-bold text-gray-800 text-sm">📝 Konten Artikel</h2>
                        <div class="flex gap-1">
                            <button type="button" onclick="showEditor()"
                                    id="btn-editor"
                                    class="px-3 py-1 text-xs font-bold rounded-lg bg-green-600 text-white">Editor</button>
                            <button type="button" onclick="showPreview()"
                                    id="btn-preview"
                                    class="px-3 py-1 text-xs font-bold rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200">Preview</button>
                        </div>
                    </div>

                    <div id="content-editor">
                        <textarea id="content" name="content" rows="16"
                                  class="form-input {{ $errors->has('content') ? 'is-invalid' : '' }}"
                                  placeholder="Tulis konten artikel di sini...

Tip: Gunakan tag HTML untuk format:
&lt;h2&gt;Judul Section&lt;/h2&gt;
&lt;p&gt;Paragraf...&lt;/p&gt;
&lt;ul&gt;&lt;li&gt;Poin&lt;/li&gt;&lt;/ul&gt;
&lt;strong&gt;Tebal&lt;/strong&gt;">{{ old('content') }}</textarea>
                        @error('content') <p class="error-msg">{{ $message }}</p> @enderror
                        <p class="text-[10px] text-gray-400 mt-1">Minimal 100 karakter. Gunakan tag HTML untuk format teks.</p>
                    </div>

                    <div id="content-preview" class="min-h-48 p-3 border border-gray-100 rounded-xl">
                        <div class="prose text-sm" id="preview-html"></div>
                    </div>
                </div>

                {{-- Publish Options --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="font-bold text-gray-800 mb-3 text-sm">⚙️ Opsi Publikasi</h2>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="publish" value="1"
                               {{ old('publish') ? 'checked' : 'checked' }}
                               class="w-4 h-4 text-green-600 rounded focus:ring-green-500">
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Publikasikan sekarang</p>
                            <p class="text-xs text-gray-400">Artikel langsung tampil di halaman /artikel</p>
                        </div>
                    </label>
                </div>

                <button type="submit"
                        class="w-full py-3.5 font-extrabold text-sm rounded-xl bg-green-600 hover:bg-green-700 text-white shadow-md hover:shadow-lg transition-all">
                    ✅ Simpan & Publikasikan Artikel
                </button>
            </form>

        </div>
    </div>

    @push('scripts')
    <script>
        function showEditor() {
            document.getElementById('content-editor').style.display = 'block';
            document.getElementById('content-preview').style.display = 'none';
            document.getElementById('btn-editor').className = 'px-3 py-1 text-xs font-bold rounded-lg bg-green-600 text-white';
            document.getElementById('btn-preview').className = 'px-3 py-1 text-xs font-bold rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200';
        }

        function showPreview() {
            const content = document.getElementById('content').value;
            document.getElementById('preview-html').innerHTML = content || '<p class="text-gray-400 italic">Belum ada konten untuk ditampilkan.</p>';
            document.getElementById('content-editor').style.display = 'none';
            document.getElementById('content-preview').style.display = 'block';
            document.getElementById('btn-preview').className = 'px-3 py-1 text-xs font-bold rounded-lg bg-green-600 text-white';
            document.getElementById('btn-editor').className = 'px-3 py-1 text-xs font-bold rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200';
        }
    </script>
    @endpush
</x-app-layout>
