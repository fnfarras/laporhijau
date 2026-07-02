<x-app-layout>
    @section('title', 'Event Komunitas - LaporHijau')

    @push('styles')
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        .event-card {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .event-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 40px rgba(22, 163, 74, 0.12);
        }

        .banner-gradient-1 { background: linear-gradient(135deg, #16a34a 0%, #059669 50%, #0ea5e9 100%); }
        .banner-gradient-2 { background: linear-gradient(135deg, #0ea5e9 0%, #6366f1 100%); }
        .banner-gradient-3 { background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%); }
        .banner-gradient-4 { background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%); }
        .banner-gradient-5 { background: linear-gradient(135deg, #10b981 0%, #3b82f6 100%); }
        .banner-gradient-6 { background: linear-gradient(135deg, #f97316 0%, #eab308 100%); }

        .category-badge-Bersih-bersih   { background: #dcfce7; color: #166534; }
        .category-badge-Tanam-Pohon     { background: #d1fae5; color: #065f46; }
        .category-badge-Gotong-Royong   { background: #dbeafe; color: #1e40af; }
        .category-badge-Edukasi         { background: #ede9fe; color: #5b21b6; }
        .category-badge-Pengolahan-Sampah { background: #fef3c7; color: #92400e; }
        .category-badge-Umum            { background: #f3f4f6; color: #374151; }

        .tab-active   { background: #16a34a; color: white; box-shadow: 0 4px 12px rgba(22,163,74,0.3); }
        .tab-inactive { background: white; color: #6b7280; border: 1px solid #e5e7eb; }
    </style>
    @endpush

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">🌿 Event Komunitas</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Bergabung dalam kegiatan lingkungan bersama komunitas</p>
                </div>
                @auth
                    @if (auth()->user()->hasAnyRole(['relawan', 'pemerintah', 'admin']))
                        <a href="{{ route('event.create') }}"
                           class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-xl transition-all shadow-md hover:shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            Buat Event
                        </a>
                    @endif
                @endauth
            </div>

            {{-- Filter Tab --}}
            <div class="flex gap-2 mb-6">
                <a href="{{ route('event.index', ['filter' => 'upcoming']) }}"
                   class="px-5 py-2 text-sm font-bold rounded-full transition-all {{ $filter === 'upcoming' ? 'tab-active' : 'tab-inactive hover:bg-gray-50' }}">
                    📅 Upcoming
                </a>
                <a href="{{ route('event.index', ['filter' => 'past']) }}"
                   class="px-5 py-2 text-sm font-bold rounded-full transition-all {{ $filter === 'past' ? 'tab-active' : 'tab-inactive hover:bg-gray-50' }}">
                    📁 Past Event
                </a>
            </div>

            @if (session('success'))
                <div class="mb-5 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 text-sm font-medium px-4 py-3 rounded-xl">
                    <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Grid Event --}}
            @if ($events->isEmpty())
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-16 text-center">
                    <div class="text-5xl mb-3">{{ $filter === 'upcoming' ? '📅' : '📁' }}</div>
                    <h3 class="text-base font-bold text-gray-700 mb-1">
                        {{ $filter === 'upcoming' ? 'Belum ada event mendatang' : 'Belum ada past event' }}
                    </h3>
                    <p class="text-sm text-gray-400">
                        {{ $filter === 'upcoming' ? 'Event baru akan segera hadir. Pantau terus!' : 'Riwayat event akan tampil di sini.' }}
                    </p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach ($events as $i => $event)
                        @php
                            $gradients = ['banner-gradient-1','banner-gradient-2','banner-gradient-3','banner-gradient-4','banner-gradient-5','banner-gradient-6'];
                            $grad      = $gradients[$i % count($gradients)];
                            $catClass  = 'category-badge-' . str_replace([' ', '/'], ['-', '-'], $event->category);
                            $isUpcoming = $event->isUpcoming();

                            // Auth user's participation status
                            $userParticipant = null;
                            if (auth()->check()) {
                                $userParticipant = $event->participants->where('user_id', auth()->id())->first();
                            }
                            $isRsvped = $userParticipant && $userParticipant->status === 'registered';
                        @endphp

                        <div class="event-card bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                            {{-- Banner --}}
                            <a href="{{ route('event.show', $event) }}" class="block">
                                <div class="{{ $grad }} h-36 relative flex items-center justify-center">
                                    <div class="text-white text-center px-4">
                                        <div class="text-3xl mb-1">
                                            @if ($event->category === 'Bersih-bersih') 🧹
                                            @elseif ($event->category === 'Tanam Pohon') 🌳
                                            @elseif ($event->category === 'Gotong Royong') 🤝
                                            @elseif ($event->category === 'Edukasi') 📚
                                            @elseif ($event->category === 'Pengolahan Sampah') ♻️
                                            @else 🌿
                                            @endif
                                        </div>
                                        <span class="text-xs font-semibold bg-white/20 backdrop-blur px-2.5 py-0.5 rounded-full">
                                            {{ $event->category }}
                                        </span>
                                    </div>

                                    @if (!$isUpcoming)
                                        <div class="absolute top-3 right-3 bg-gray-800/70 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                                            SELESAI
                                        </div>
                                    @endif
                                </div>
                            </a>

                            {{-- Content --}}
                            <div class="p-4">
                                <a href="{{ route('event.show', $event) }}" class="block">
                                    <h3 class="font-bold text-gray-900 text-sm leading-snug mb-2 hover:text-green-700 transition-colors line-clamp-2">
                                        {{ $event->title }}
                                    </h3>
                                </a>

                                <div class="space-y-1.5 mb-3">
                                    <div class="flex items-center gap-1.5 text-xs text-gray-500">
                                        <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                        <span class="truncate">{{ $event->location }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-xs text-gray-500">
                                        <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span>{{ $event->event_date->translatedFormat('D, d M Y • H:i') }} WIB</span>
                                    </div>
                                </div>

                                {{-- Peserta bar --}}
                                @php
                                    $activeCount = $event->active_participants_count ?? $event->activeParticipants()->count();
                                    $maxP = $event->max_participants;
                                    $pct  = $maxP ? min(100, round($activeCount / $maxP * 100)) : 0;
                                @endphp
                                <div class="flex items-center justify-between text-xs mb-3">
                                    <span class="text-gray-500">
                                        <span class="font-bold text-gray-800">{{ $activeCount }}</span>
                                        {{ $maxP ? '/ ' . $maxP . ' peserta' : 'peserta' }}
                                    </span>
                                    @if ($isUpcoming && $maxP)
                                        <span class="text-green-600 font-semibold">{{ $event->spotsRemaining() }} tersisa</span>
                                    @endif
                                </div>
                                @if ($maxP)
                                    <div class="w-full bg-gray-100 rounded-full h-1.5 mb-3">
                                        <div class="bg-green-500 h-1.5 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                                    </div>
                                @endif

                                {{-- Countdown (Alpine.js) --}}
                                @if ($isUpcoming)
                                    <div x-data="countdown('{{ $event->event_date->toISOString() }}')" class="text-xs text-amber-600 font-bold mb-3">
                                        <span x-text="display"></span>
                                    </div>
                                @endif

                                {{-- RSVP Button --}}
                                @if ($isUpcoming)
                                    @auth
                                        @if ($isRsvped)
                                            <form method="POST" action="{{ route('event.rsvp', $event) }}">
                                                @csrf
                                                <button type="submit"
                                                        class="w-full py-2 text-xs font-bold rounded-xl bg-red-50 text-red-600 border border-red-200 hover:bg-red-100 transition-all">
                                                    ✕ Batalkan RSVP
                                                </button>
                                            </form>
                                        @elseif ($event->isFull())
                                            <button disabled class="w-full py-2 text-xs font-bold rounded-xl bg-gray-100 text-gray-400 cursor-not-allowed">
                                                Kuota Penuh
                                            </button>
                                        @else
                                            <form method="POST" action="{{ route('event.rsvp', $event) }}">
                                                @csrf
                                                <button type="submit"
                                                        class="w-full py-2 text-xs font-bold rounded-xl bg-green-600 hover:bg-green-700 text-white transition-all shadow-sm">
                                                    ✅ RSVP Sekarang (+15 poin)
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}"
                                           class="block w-full py-2 text-xs font-bold rounded-xl bg-green-600 hover:bg-green-700 text-white text-center transition-all shadow-sm">
                                            Login untuk RSVP
                                        </a>
                                    @endauth
                                @else
                                    <div class="w-full py-2 text-xs font-bold rounded-xl bg-gray-100 text-gray-500 text-center">
                                        Event telah selesai
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>

    @push('scripts')
    <script>
        function countdown(targetDate) {
            return {
                display: '',
                init() {
                    this.update();
                    setInterval(() => this.update(), 1000);
                },
                update() {
                    const diff = new Date(targetDate) - new Date();
                    if (diff <= 0) { this.display = '⏰ Event sudah dimulai!'; return; }
                    const d = Math.floor(diff / 86400000);
                    const h = Math.floor((diff % 86400000) / 3600000);
                    const m = Math.floor((diff % 3600000) / 60000);
                    const s = Math.floor((diff % 60000) / 1000);
                    if (d > 0)      this.display = `⏳ ${d} hari ${h} jam lagi`;
                    else if (h > 0) this.display = `⏳ ${h} jam ${m} menit lagi`;
                    else            this.display = `⏳ ${m} menit ${s} detik lagi`;
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
