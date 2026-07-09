<x-app-layout>
    @section('title', $article->title . ' - LaporHijau')

    @push('styles')
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .prose h2 { font-size: 1.2rem; font-weight: 800; color: #111827; margin: 1.5rem 0 0.75rem; }
        .prose h3 { font-size: 1rem; font-weight: 700; color: #374151; margin: 1.25rem 0 0.5rem; }
        .prose p  { color: #4b5563; line-height: 1.8; margin-bottom: 1rem; font-size: 0.9375rem; }
        .prose ul, .prose ol { padding-left: 1.5rem; margin-bottom: 1rem; color: #4b5563; font-size: 0.9375rem; }
        .prose li { margin-bottom: 0.4rem; line-height: 1.7; }
        .prose strong { color: #111827; }
    </style>
    @endpush

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <a href="{{ route('artikel.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-green-700 transition-colors mb-5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Artikel
            </a>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- ── Konten Utama ───────────────────────────────── --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        {{-- Banner --}}
                        @php
                            $catGrads = [
                                'Daur Ulang'      => 'linear-gradient(135deg,#3b82f6,#6366f1)',
                                'Regulasi'        => 'linear-gradient(135deg,#8b5cf6,#ec4899)',
                                'Tips Lingkungan' => 'linear-gradient(135deg,#16a34a,#0ea5e9)',
                                'Edukasi'         => 'linear-gradient(135deg,#f59e0b,#ef4444)',
                                'Inspirasi'       => 'linear-gradient(135deg,#ec4899,#f97316)',
                            ];
                            $catEmojis = [
                                'Daur Ulang'      => '♻️',
                                'Regulasi'        => '⚖️',
                                'Tips Lingkungan' => '🌿',
                                'Edukasi'         => '📖',
                                'Inspirasi'       => '✨',
                            ];
                            $grad  = $catGrads[$article->category] ?? 'linear-gradient(135deg,#16a34a,#059669)';
                            $emoji = $catEmojis[$article->category] ?? '📄';
                        @endphp
                        <div class="h-64 relative overflow-hidden" style="background: {{ $grad }}">
                            @if ($article->image_url)
                                <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-6xl">
                                    {{ $emoji }}
                                </div>
                            @endif
                        </div>

                        <div class="p-6">
                            {{-- Category --}}
                            <span class="inline-flex items-center gap-1 text-xs font-bold px-3 py-1 rounded-full bg-green-100 text-green-700 mb-3">
                                {{ $article->category }}
                            </span>

                            {{-- Title --}}
                            <h1 class="text-xl font-extrabold text-gray-900 leading-tight mb-4">
                                {{ $article->title }}
                            </h1>

                            {{-- Meta --}}
                            <div class="flex flex-wrap items-center gap-3 pb-4 mb-6 border-b border-gray-100">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-7 h-7 rounded-full bg-green-500 flex items-center justify-center text-white text-xs font-bold">
                                        {{ strtoupper(substr($article->author->name, 0, 1)) }}
                                    </div>
                                    <span class="text-xs font-semibold text-gray-700">{{ $article->author->name }}</span>
                                </div>
                                <span class="text-gray-300">·</span>
                                <span class="text-xs text-gray-500">{{ $article->published_at->translatedFormat('d F Y') }}</span>
                                <span class="text-gray-300">·</span>
                                <span class="text-xs text-gray-500">{{ $article->reading_time }} menit baca</span>

                                {{-- Share --}}
                                <button id="btn-share" onclick="copyLink()"
                                        class="ml-auto inline-flex items-center gap-1.5 text-xs font-bold text-sky-600 bg-sky-50 hover:bg-sky-100 border border-sky-200 px-3 py-1.5 rounded-lg transition-all">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                                    Bagikan
                                </button>
                            </div>

                            {{-- Content --}}
                            <div class="prose">
                                {!! $article->content !!}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Sidebar ─────────────────────────────────────── --}}
                <div class="space-y-5">

                    {{-- Artikel Terkait --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <h3 class="font-bold text-gray-800 mb-4 text-sm">🔗 Artikel Terkait</h3>
                        @if ($related->isEmpty())
                            <p class="text-xs text-gray-400 text-center py-4">Belum ada artikel terkait</p>
                        @else
                            <div class="space-y-3">
                                @foreach ($related as $rel)
                                    <a href="{{ route('artikel.show', $rel->slug) }}"
                                       class="flex items-start gap-2.5 hover:bg-gray-50 rounded-xl p-2 -m-2 transition-colors">
                                        @if ($rel->image_url)
                                            <img src="{{ $rel->image_url }}" alt="{{ $rel->title }}" class="w-10 h-10 rounded-xl object-cover flex-shrink-0">
                                        @else
                                            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg flex-shrink-0"
                                                 style="background: {{ $catGrads[$rel->category] ?? 'linear-gradient(135deg,#16a34a,#059669)' }}">
                                                {{ $catEmojis[$rel->category] ?? '📄' }}
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-bold text-gray-800 line-clamp-2 leading-snug">{{ $rel->title }}</p>
                                            <p class="text-[10px] text-gray-400 mt-0.5">{{ $rel->reading_time }} mnt · {{ $rel->published_at->format('d M Y') }}</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- CTA Lapor --}}
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-2xl p-5 text-center">
                        <div class="text-3xl mb-2">🌿</div>
                        <h3 class="font-bold text-green-800 text-sm mb-1">Temukan masalah lingkungan?</h3>
                        <p class="text-xs text-green-700 mb-3">Laporkan sekarang dan bantu komunitas!</p>
                        <a href="{{ route('laporan.create') }}"
                           class="inline-block px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm">
                            Lapor Sekarang →
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function copyLink() {
            navigator.clipboard.writeText(window.location.href).then(() => {
                const btn = document.getElementById('btn-share');
                btn.textContent = '✓ Link disalin!';
                btn.classList.add('bg-green-50', 'text-green-700', 'border-green-200');
                setTimeout(() => {
                    btn.innerHTML = `<svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>Bagikan`;
                    btn.classList.remove('bg-green-50', 'text-green-700', 'border-green-200');
                }, 2000);
            });
        }
    </script>
    @endpush
</x-app-layout>
