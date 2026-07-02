<x-app-layout>
    @section('title', $event->title . ' - LaporHijau')

    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        #event-map { height: 220px; border-radius: 12px; z-index: 1; }
        .participant-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 14px; color: white;
            flex-shrink: 0;
        }
    </style>
    @endpush

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Back --}}
            <a href="{{ route('event.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-green-700 transition-colors mb-5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Daftar Event
            </a>

            @if (session('success'))
                <div class="mb-5 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 text-sm font-medium px-4 py-3 rounded-xl">
                    <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- ── Main Content ───────────────────────────────────── --}}
                <div class="lg:col-span-2 space-y-5">

                    {{-- Hero Banner --}}
                    @php
                        $gradMap = [
                            'Bersih-bersih'      => 'linear-gradient(135deg, #16a34a 0%, #059669 50%, #0ea5e9 100%)',
                            'Tanam Pohon'        => 'linear-gradient(135deg, #10b981 0%, #3b82f6 100%)',
                            'Gotong Royong'      => 'linear-gradient(135deg, #0ea5e9 0%, #6366f1 100%)',
                            'Edukasi'            => 'linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%)',
                            'Pengolahan Sampah'  => 'linear-gradient(135deg, #f59e0b 0%, #ef4444 100%)',
                            'Umum'               => 'linear-gradient(135deg, #f97316 0%, #eab308 100%)',
                        ];
                        $grad = $gradMap[$event->category] ?? $gradMap['Umum'];
                        $emojiMap = [
                            'Bersih-bersih'     => '🧹',
                            'Tanam Pohon'       => '🌳',
                            'Gotong Royong'     => '🤝',
                            'Edukasi'           => '📚',
                            'Pengolahan Sampah' => '♻️',
                            'Umum'              => '🌿',
                        ];
                        $emoji = $emojiMap[$event->category] ?? '🌿';
                        $isUpcoming = $event->isUpcoming();
                        $activeCount = $event->activeParticipants->count();
                        $isRsvped = $userParticipant && $userParticipant->status === 'registered';
                    @endphp

                    <div class="rounded-2xl overflow-hidden" style="background: {{ $grad }};">
                        <div class="px-8 py-10 text-white">
                            <div class="text-5xl mb-3">{{ $emoji }}</div>
                            <span class="text-xs font-semibold bg-white/20 backdrop-blur px-3 py-1 rounded-full">{{ $event->category }}</span>
                            <h1 class="text-2xl font-extrabold mt-3 leading-tight">{{ $event->title }}</h1>
                            <p class="text-white/80 text-sm mt-1">Diorganisir oleh <span class="font-bold">{{ $event->organizer->name }}</span></p>
                        </div>
                    </div>

                    {{-- Info Cards --}}
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-3 flex items-center gap-2.5">
                            <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center text-sm">📅</div>
                            <div>
                                <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wide">Tanggal</p>
                                <p class="text-xs font-bold text-gray-800">{{ $event->event_date->translatedFormat('D, d M Y') }}</p>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-3 flex items-center gap-2.5">
                            <div class="w-8 h-8 bg-sky-100 rounded-lg flex items-center justify-center text-sm">⏰</div>
                            <div>
                                <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wide">Waktu</p>
                                <p class="text-xs font-bold text-gray-800">{{ $event->event_date->format('H:i') }} WIB</p>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-3 flex items-center gap-2.5">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center text-sm">👥</div>
                            <div>
                                <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wide">Peserta</p>
                                <p class="text-xs font-bold text-gray-800">
                                    {{ $activeCount }}{{ $event->max_participants ? '/' . $event->max_participants : '' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Countdown --}}
                    @if ($isUpcoming)
                        <div x-data="countdown('{{ $event->event_date->toISOString() }}')"
                             class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 flex items-center gap-2">
                            <span class="text-lg">⏳</span>
                            <span class="text-sm font-bold text-amber-700" x-text="display"></span>
                        </div>
                    @else
                        <div class="bg-gray-100 rounded-xl px-4 py-3 flex items-center gap-2">
                            <span class="text-lg">✅</span>
                            <span class="text-sm font-bold text-gray-500">Event ini sudah selesai dilaksanakan</span>
                        </div>
                    @endif

                    {{-- Deskripsi --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <h2 class="font-bold text-gray-800 mb-3 text-base">📋 Deskripsi Event</h2>
                        <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">{{ $event->description }}</p>

                        @if ($event->location)
                            <div class="flex items-start gap-2 mt-4 pt-4 border-t border-gray-50">
                                <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <p class="text-sm text-gray-600">{{ $event->location }}</p>
                            </div>
                        @endif
                    </div>

                    {{-- Peta Lokasi --}}
                    @if ($event->latitude && $event->longitude)
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                            <h2 class="font-bold text-gray-800 mb-3 text-base">📍 Lokasi Event</h2>
                            <div id="event-map"></div>
                        </div>
                    @endif

                    {{-- Laporan Terkait --}}
                    @if ($event->report)
                        <div class="bg-sky-50 border border-sky-200 rounded-2xl p-4">
                            <p class="text-xs font-bold text-sky-700 mb-1">🔗 Terkait Laporan</p>
                            <a href="{{ route('laporan.show', $event->report) }}" class="text-sm font-semibold text-sky-800 hover:underline">
                                {{ $event->report->title }}
                            </a>
                        </div>
                    @endif

                </div>

                {{-- ── Sidebar ────────────────────────────────────────── --}}
                <div class="space-y-5">

                    {{-- RSVP Card --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 sticky top-20">
                        @php
                            $spotsLeft = $event->spotsRemaining();
                        @endphp

                        {{-- Spots progress --}}
                        @if ($event->max_participants)
                            <div class="mb-4">
                                <div class="flex items-center justify-between text-xs mb-1.5">
                                    <span class="text-gray-500">{{ $activeCount }} dari {{ $event->max_participants }} peserta</span>
                                    <span class="font-bold {{ $spotsLeft > 0 ? 'text-green-600' : 'text-red-500' }}">
                                        {{ $spotsLeft > 0 ? $spotsLeft . ' tersisa' : 'Penuh' }}
                                    </span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2">
                                    @php $pct = min(100, round($activeCount / $event->max_participants * 100)); @endphp
                                    <div class="h-2 rounded-full transition-all {{ $pct >= 100 ? 'bg-red-400' : 'bg-green-500' }}"
                                         style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                        @else
                            <p class="text-xs text-gray-500 mb-4"><span class="font-bold text-gray-800">{{ $activeCount }}</span> peserta terdaftar</p>
                        @endif

                        @if ($isUpcoming)
                            @auth
                                @if ($isRsvped)
                                    <form method="POST" action="{{ route('event.rsvp', $event) }}">
                                        @csrf
                                        <button type="submit" class="w-full py-3 font-bold rounded-xl bg-red-50 text-red-600 border-2 border-red-200 hover:bg-red-100 transition-all text-sm">
                                            ✕ Batalkan RSVP
                                        </button>
                                    </form>
                                    <p class="text-[10px] text-red-400 text-center mt-2">Membatalkan RSVP mengurangi -15 poin</p>
                                @elseif ($event->isFull())
                                    <button disabled class="w-full py-3 font-bold rounded-xl bg-gray-100 text-gray-400 cursor-not-allowed text-sm">
                                        Kuota Penuh
                                    </button>
                                @else
                                    <form method="POST" action="{{ route('event.rsvp', $event) }}">
                                        @csrf
                                        <button type="submit"
                                                class="w-full py-3 font-bold rounded-xl bg-green-600 hover:bg-green-700 text-white transition-all shadow-md hover:shadow-lg text-sm">
                                            ✅ RSVP Sekarang
                                        </button>
                                    </form>
                                    <p class="text-[10px] text-green-600 text-center mt-2 font-semibold">+15 poin saat RSVP berhasil</p>
                                @endif
                            @else
                                <a href="{{ route('login') }}"
                                   class="block w-full py-3 font-bold rounded-xl bg-green-600 hover:bg-green-700 text-white text-center transition-all shadow-md text-sm">
                                    Login untuk RSVP
                                </a>
                            @endauth
                        @else
                            <div class="w-full py-3 text-center font-bold rounded-xl bg-gray-100 text-gray-400 text-sm">
                                Event Telah Selesai
                            </div>
                        @endif
                    </div>

                    {{-- Daftar Peserta --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <h3 class="font-bold text-gray-800 mb-3 text-sm">
                            👥 Peserta Terdaftar
                            <span class="text-xs font-normal text-gray-400">({{ $activeCount }})</span>
                        </h3>

                        @if ($event->activeParticipants->isEmpty())
                            <p class="text-xs text-gray-400 text-center py-4">Belum ada peserta. Jadilah yang pertama!</p>
                        @else
                            <div class="space-y-2.5 max-h-64 overflow-y-auto pr-1">
                                @php
                                    $avatarColors = ['#16a34a','#0ea5e9','#f59e0b','#ef4444','#8b5cf6','#ec4899','#10b981'];
                                @endphp
                                @foreach ($event->activeParticipants as $idx => $participant)
                                    <div class="flex items-center gap-2.5">
                                        <div class="participant-avatar"
                                             style="background: {{ $avatarColors[$idx % count($avatarColors)] }}">
                                            {{ strtoupper(substr($participant->user->name ?? '?', 0, 1)) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-semibold text-gray-800 truncate">{{ $participant->user->name }}</p>
                                            <p class="text-[10px] text-gray-400">{{ $participant->created_at->diffForHumans() }}</p>
                                        </div>
                                        @if ($participant->status === 'attended')
                                            <span class="text-[10px] bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-semibold">Hadir</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @if ($event->latitude && $event->longitude)
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const map = L.map('event-map').setView([{{ $event->latitude }}, {{ $event->longitude }}], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            const icon = L.divIcon({
                html: `<div style="background:#16a34a;width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-size:18px;border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.3)">📍</div>`,
                className: '',
                iconSize: [36, 36],
                iconAnchor: [18, 18],
            });

            L.marker([{{ $event->latitude }}, {{ $event->longitude }}], { icon })
                .addTo(map)
                .bindPopup('<strong>{{ addslashes($event->title) }}</strong><br>{{ addslashes($event->location) }}')
                .openPopup();
        });
    </script>
    @endif
    <script>
        function countdown(targetDate) {
            return {
                display: '',
                init() { this.update(); setInterval(() => this.update(), 1000); },
                update() {
                    const diff = new Date(targetDate) - new Date();
                    if (diff <= 0) { this.display = 'Event sudah dimulai!'; return; }
                    const d = Math.floor(diff / 86400000);
                    const h = Math.floor((diff % 86400000) / 3600000);
                    const m = Math.floor((diff % 3600000) / 60000);
                    const s = Math.floor((diff % 60000) / 1000);
                    if (d > 0)      this.display = `${d} hari ${h} jam lagi`;
                    else if (h > 0) this.display = `${h} jam ${m} menit lagi`;
                    else            this.display = `${m} menit ${s} detik lagi`;
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
