<x-admin-layout>
    @section('title', 'Manajemen Artikel')

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Artikel</h1>
            <p class="text-sm text-gray-500 mt-0.5">{{ $articles->total() }} artikel terdaftar</p>
        </div>
        <a href="{{ route('admin.articles.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-xl transition-colors shadow-sm">
            + Tulis Artikel
        </a>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
        <form method="GET" action="{{ route('admin.articles.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="min-w-40">
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-300 bg-white">
                    <option value="">Semua</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>✅ Published</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>📝 Draft</option>
                </select>
            </div>
            <div class="min-w-48">
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Kategori</label>
                <select name="category" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-300 bg-white">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl transition-colors">Filter</button>
                <a href="{{ route('admin.articles.index') }}" class="px-5 py-2 border border-gray-200 text-gray-500 text-sm font-semibold rounded-xl hover:bg-gray-50">Reset</a>
            </div>
        </form>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs text-gray-400 uppercase tracking-wide bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-5 py-3.5 font-semibold">Artikel</th>
                        <th class="text-left px-4 py-3.5 font-semibold">Kategori</th>
                        <th class="text-left px-4 py-3.5 font-semibold hidden md:table-cell">Penulis</th>
                        <th class="text-left px-4 py-3.5 font-semibold">Status</th>
                        <th class="text-right px-4 py-3.5 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($articles as $article)
                        @php $published = $article->isPublished(); @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-5 py-4 max-w-sm">
                                <a href="{{ route('artikel.show', $article->slug) }}" target="_blank"
                                   class="font-semibold text-gray-800 hover:text-red-600 transition-colors line-clamp-1 block">
                                    {{ $article->title }}
                                </a>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $article->created_at->format('d M Y') }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700">
                                    {{ $article->category }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-xs text-gray-500 hidden md:table-cell">
                                {{ $article->author?->name ?? '—' }}
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                    {{ $published ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $published ? '✅ Published' : '📝 Draft' }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.articles.edit', $article) }}"
                                       class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition-colors">
                                        ✏️ Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.articles.destroy', $article) }}"
                                          onsubmit="return confirm('Hapus artikel ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold rounded-lg border border-red-200 transition-colors">
                                            🗑️ Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-16 text-center text-gray-400">
                                <div class="text-4xl mb-2">📰</div>
                                <p class="text-sm font-semibold">Belum ada artikel</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($articles->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">{{ $articles->links() }}</div>
        @endif
    </div>
</x-admin-layout>
