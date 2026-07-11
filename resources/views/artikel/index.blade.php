<x-app-layout>
    @section('title', 'Artikel Edukasi Lingkungan — LaporHijau')
    @section('meta_description', 'Baca artikel edukasi lingkungan terbaru: daur ulang, tips hijau, regulasi, dan inspirasi aksi nyata untuk Indonesia lebih bersih.')

    @push('styles')
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .article-card { transition: transform 0.2s, box-shadow 0.2s; }
        .article-card:hover { transform: translateY(-3px); box-shadow: 0 12px 30px rgba(0,0,0,0.08); }
        .category-pill { font-size: 10px; font-weight: 700; padding: 2px 10px; border-radius: 20px; }
    </style>
    @endpush

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">📚 Artikel Edukasi</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Wawasan lingkungan untuk aksi nyata</p>
                </div>
                @auth
                    @if (auth()->user()->hasAnyRole(['admin', 'pemerintah']))
                        <a href="{{ route('artikel.create') }}"
                           class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-xl transition-all shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            Tulis Artikel
                        </a>
                    @endif
                @endauth
            </div>

            {{-- Filter + Search --}}
            <form method="GET" action="{{ route('artikel.index') }}" class="flex flex-wrap gap-2 mb-6">
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="🔍 Cari artikel..."
                       class="flex-1 min-w-48 px-4 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100">

                <div class="flex flex-wrap gap-1.5">
                    <a href="{{ route('artikel.index', array_merge(request()->except('kategori'), ['q' => request('q')])) }}"
                       class="px-3 py-2 text-xs font-bold rounded-xl transition-all {{ !request('kategori') ? 'bg-green-600 text-white shadow-md' : 'bg-white text-gray-500 border border-gray-200 hover:bg-gray-50' }}">
                        Semua
                    </a>
                    @foreach ($categories as $cat)
                        <a href="{{ route('artikel.index', array_merge(request()->except('kategori'), ['kategori' => $cat, 'q' => request('q')])) }}"
                           class="px-3 py-2 text-xs font-bold rounded-xl transition-all {{ request('kategori') === $cat ? 'bg-green-600 text-white shadow-md' : 'bg-white text-gray-500 border border-gray-200 hover:bg-gray-50' }}">
                            {{ $cat }}
                        </a>
                    @endforeach
                </div>

                @if (request('q') || request('kategori'))
                    <a href="{{ route('artikel.index') }}"
                       class="px-3 py-2 text-xs font-bold text-red-600 bg-red-50 border border-red-100 rounded-xl hover:bg-red-100 transition-all">
                        ✕ Reset
                    </a>
                @endif
            </form>

            {{-- Articles Grid --}}
            @if ($articles->isEmpty())
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-16 text-center">
                    <div class="text-5xl mb-3">📭</div>
                    <h3 class="text-base font-bold text-gray-700 mb-1">Artikel tidak ditemukan</h3>
                    <p class="text-sm text-gray-400">Coba kata kunci atau filter kategori lain</p>
                </div>
            @else
                @php
                    $catColors = [
                        'Daur Ulang'      => 'bg-blue-100 text-blue-700',
                        'Regulasi'        => 'bg-purple-100 text-purple-700',
                        'Tips Lingkungan' => 'bg-green-100 text-green-700',
                        'Edukasi'         => 'bg-amber-100 text-amber-700',
                        'Inspirasi'       => 'bg-pink-100 text-pink-700',
                    ];
                    $catEmojis = [
                        'Daur Ulang'      => '♻️',
                        'Regulasi'        => '⚖️',
                        'Tips Lingkungan' => '🌿',
                        'Edukasi'         => '📖',
                        'Inspirasi'       => '✨',
                    ];
                    $catGrads = [
                        'Daur Ulang'      => 'linear-gradient(135deg,#3b82f6,#6366f1)',
                        'Regulasi'        => 'linear-gradient(135deg,#8b5cf6,#ec4899)',
                        'Tips Lingkungan' => 'linear-gradient(135deg,#16a34a,#0ea5e9)',
                        'Edukasi'         => 'linear-gradient(135deg,#f59e0b,#ef4444)',
                        'Inspirasi'       => 'linear-gradient(135deg,#ec4899,#f97316)',
                    ];
                @endphp
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach ($articles as $article)
                        @php
                            $cc = $catColors[$article->category] ?? 'bg-gray-100 text-gray-600';
                            $ce = $catEmojis[$article->category] ?? '📄';
                            $cg = $catGrads[$article->category] ?? 'linear-gradient(135deg,#16a34a,#059669)';
                        @endphp
                        <div class="article-card bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700/80 shadow-sm overflow-hidden flex flex-col h-full group">
                            {{-- Thumbnail / Banner --}}
                            <a href="{{ route('artikel.show', $article->slug) }}" class="block relative h-48 overflow-hidden">
                                @if ($article->image_url)
                                    <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-4xl" style="background: {{ $cg }}">
                                        {{ $ce }}
                                    </div>
                                @endif
                                
                                <!-- Gradient Overlay -->
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-900/20 to-transparent"></div>
                            </a>

                            <div class="p-5 flex flex-col flex-1 justify-between">
                                <div>
                                    <span class="category-pill {{ $cc }}">{{ $article->category }}</span>

                                    <a href="{{ route('artikel.show', $article->slug) }}" class="block mt-2.5">
                                        <h3 class="font-extrabold text-gray-900 dark:text-white text-base leading-snug hover:text-green-600 dark:hover:text-green-400 transition-colors line-clamp-2">
                                            {{ $article->title }}
                                        </h3>
                                    </a>

                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 line-clamp-2 leading-relaxed">
                                        {{ $article->excerpt }}
                                    </p>
                                </div>

                                <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100 dark:border-slate-700">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-green-600 text-white flex items-center justify-center text-[10px] font-bold shadow-sm">
                                            {{ strtoupper(substr($article->author->name, 0, 1)) }}
                                        </div>
                                        <span class="text-[10px] font-bold text-gray-600 dark:text-gray-350">{{ $article->author->name }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-[10px] text-gray-400 dark:text-gray-500">
                                        <span>{{ $article->reading_time }} mnt baca</span>
                                        <span>·</span>
                                        <span>{{ $article->published_at->translatedFormat('d M Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $articles->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
