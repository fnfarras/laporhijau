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
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700/80 shadow-sm p-12 text-center">
                    {{-- Calendar with Leaves SVG --}}
                    <div class="w-48 h-36 mx-auto mb-4 relative">
                        <svg viewBox="0 0 160 120" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full mx-auto">
                            <!-- Badan Kalender -->
                            <rect x="40" y="30" width="80" height="70" rx="8" fill="#cbd5e1" class="dark:fill-slate-600"/>
                            <rect x="40" y="45" width="80" height="55" rx="4" fill="#f8fafc" class="dark:fill-slate-700"/>
                            <!-- Header Kalender Merah/Hijau -->
                            <rect x="40" y="30" width="80" height="15" rx="4" fill="#ef4444"/>
                            
                            <!-- Cincin Pengikat Kalender -->
                            <rect x="55" y="22" width="6" height="12" rx="3" fill="#64748b"/>
                            <rect x="99" y="22" width="6" height="12" rx="3" fill="#64748b"/>
                            
                            <!-- Angka & Baris Keterangan di Kalender -->
                            <rect x="50" y="55" width="20" height="20" rx="4" fill="#e2e8f0" class="dark:fill-slate-650"/>
                            <rect x="50" y="80" width="60" height="6" rx="3" fill="#e2e8f0" class="dark:fill-slate-650"/>
                            <rect x="50" y="90" width="40" height="6" rx="3" fill="#e2e8f0" class="dark:fill-slate-650"/>
                            
                            <!-- Daun Hijau Muncul di Belakang -->
                            <path d="M120 40 C140 40, 140 60, 120 70 C110 60, 110 40, 120 40 Z" fill="#22c55e"/>
                            <path d="M120 40 C130 30, 140 30, 140 45 C130 50, 120 45, 120 40 Z" fill="#15803d"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white mb-1">
                        {{ $filter === 'upcoming' ? 'Belum ada event mendatang' : 'Belum ada past event' }}
                    </h3>
                    <p class="text-xs text-gray-550 dark:text-gray-400 max-w-xs mx-auto">
                        {{ $filter === 'upcoming' ? 'Aksi kolaboratif penanaman pohon dan bersih-bersih baru akan segera hadir. Pantau terus halaman ini!' : 'Riwayat event aksi penanganan lingkungan akan tampil di sini.' }}
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

                        <div class="event-card bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700/80 shadow-sm overflow-hidden flex flex-col h-full group">
                            {{-- Banner --}}
                            <a href="{{ route('event.show', $event) }}" class="block relative h-48 overflow-hidden">
                                @if ($event->banner_url)
                                    <img src="{{ $event->banner_url }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full {{ $grad }}"></div>
                                @endif
                                
                                <!-- Gradient Overlay -->
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-900/20 to-transparent"></div>

                                <!-- Category Badge on Banner -->
                                <div class="absolute bottom-4 left-4 right-4 flex items-center justify-between">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold bg-white/95 text-gray-800 shadow-sm backdrop-blur">
                                        @if ($event->category === 'Bersih-bersih') 🧹
                                        @elseif ($event->category === 'Tanam Pohon') 🌳
                                        @elseif ($event->category === 'Gotong Royong') 🤝
                                        @elseif ($event->category === 'Edukasi') 📚
                                        @elseif ($event->category === 'Pengolahan Sampah') ♻️
                                        @else 🌿
                                        @endif
                                        {{ $event->category }}
                                    </span>

                                    @if (!$isUpcoming)
                                        <span class="bg-slate-900/90 text-white text-[9px] font-extrabold px-2.5 py-1 rounded-lg tracking-wider">
                                            SELESAI
                                        </span>
                                    @endif
                                </div>
                            </a>

                            {{-- Content --}}
                            <div class="p-5 flex flex-col flex-1 justify-between bg-white dark:bg-slate-800 transition-colors duration-300">
                                <div>
                                    <a href="{{ route('event.show', $event) }}" class="block">
                                        <h3 class="font-extrabold text-gray-900 dark:text-white text-base leading-snug mb-2.5 hover:text-green-600 dark:hover:text-green-400 transition-colors line-clamp-2">
                                            {{ $event->title }}
                                        </h3>
                                    </a>

                                    <div class="space-y-2 mb-4">
                                        <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                            <span class="truncate">{{ $event->location }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <span>{{ $event->event_date->translatedFormat('D, d M Y • H:i') }} WIB</span>
                                        </div>
                                    </div>

                                    {{-- Peserta bar --}}
                                    @php
                                        $activeCount = $event->active_participants_count ?? $event->activeParticipants()->count();
                                        $maxP = $event->max_participants;
                                        $pct  = $maxP ? min(100, round($activeCount / $maxP * 100)) : 0;
                                    @endphp
                                    <div class="flex items-center justify-between text-xs mb-1.5">
                                        <span class="text-gray-500 dark:text-gray-400">
                                            <span class="font-extrabold text-gray-800 dark:text-gray-200">{{ $activeCount }}</span>
                                            {{ $maxP ? '/ ' . $maxP . ' peserta' : 'peserta' }}
                                        </span>
                                        @if ($isUpcoming && $maxP)
                                            <span class="text-green-600 dark:text-green-400 font-bold">{{ $event->spotsRemaining() }} tersisa</span>
                                        @endif
                                    </div>
                                    @if ($maxP)
                                        <div class="w-full bg-gray-100 dark:bg-slate-700 rounded-full h-1.5 mb-4">
                                            <div class="bg-green-600 dark:bg-green-500 h-1.5 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                                        </div>
                                    @endif

                                    {{-- Countdown (Alpine.js) --}}
                                    @if ($isUpcoming)
                                        <div x-data="countdown('{{ $event->event_date->toISOString() }}')" class="text-xs text-amber-600 dark:text-amber-500 font-bold mb-4 flex items-center gap-1">
                                            <span x-text="display"></span>
                                        </div>
                                    @endif
                                </div>

                                {{-- RSVP Button --}}
                                <div class="mt-2">
                                    @if ($isUpcoming)
                                        @auth
                                            @if ($isRsvped)
                                                <form method="POST" action="{{ route('event.rsvp', $event) }}">
                                                    @csrf
                                                    <button type="submit"
                                                            class="w-full py-2.5 text-xs font-bold rounded-xl bg-red-50 dark:bg-red-950/20 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-900/30 hover:bg-red-100/70 transition-all cursor-pointer">
                                                        ✕ Batalkan RSVP
                                                    </button>
                                                </form>
                                            @elseif ($event->isFull())
                                                <button disabled class="w-full py-2.5 text-xs font-bold rounded-xl bg-gray-100 dark:bg-slate-700 text-gray-400 dark:text-gray-500 cursor-not-allowed">
                                                    Kuota Penuh
                                                </button>
                                            @else
                                                <form method="POST" action="{{ route('event.rsvp', $event) }}">
                                                    @csrf
                                                    <button type="submit"
                                                            class="w-full py-2.5 text-xs font-bold rounded-xl bg-green-600 hover:bg-green-700 text-white transition-all shadow-sm hover:shadow cursor-pointer">
                                                        ✅ RSVP Sekarang (+15 poin)
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            <a href="{{ route('login') }}"
                                               class="block w-full py-2.5 text-xs font-bold rounded-xl bg-green-600 hover:bg-green-700 text-white text-center transition-all shadow-sm hover:shadow">
                                                Login untuk RSVP
                                            </a>
                                        @endauth
                                    @else
                                        <div class="w-full py-2.5 text-xs font-bold rounded-xl bg-gray-100 dark:bg-slate-700 text-gray-500 dark:text-gray-400 text-center">
                                            Event telah selesai
                                        </div>
                                    @endif
                                </div>
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
