<x-app-layout>
    @section('title', 'Leaderboard Komunitas')

    @push('styles')
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .rank-gold   { background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-color: #f59e0b; }
        .rank-silver { background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%); border-color: #9ca3af; }
        .rank-bronze { background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%); border-color: #b45309; }

        .rank-badge-gold   { background: #f59e0b; color: white; }
        .rank-badge-silver { background: #9ca3af; color: white; }
        .rank-badge-bronze { background: #b45309; color: white; }

        .tab-active { background: #16a34a; color: white; }
        .tab-inactive { background: white; color: #6b7280; border: 1px solid #e5e7eb; }

        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0);    }
        }
        .rank-row { animation: slideInUp 0.3s ease forwards; }
        .rank-row:nth-child(1) { animation-delay: 0.05s; }
        .rank-row:nth-child(2) { animation-delay: 0.10s; }
        .rank-row:nth-child(3) { animation-delay: 0.15s; }
        .rank-row:nth-child(n+4) { animation-delay: 0.20s; }

        .highlight-self { background: #f0fdf4 !important; border-left: 3px solid #16a34a; }
    </style>
    @endpush

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="text-center mb-8">
                <div class="text-5xl mb-3">🏆</div>
                <h1 class="text-3xl font-bold text-gray-900">Leaderboard Komunitas</h1>
                <p class="text-gray-500 mt-2 text-sm">Pahlawan lingkungan terbaik LaporHijau</p>
            </div>

            {{-- Tab Periode --}}
            <div class="flex items-center justify-center gap-2 mb-8">
                <a href="{{ route('leaderboard', ['period' => 'weekly']) }}"
                   class="px-5 py-2 text-sm font-bold rounded-full transition-all {{ $period === 'weekly' ? 'tab-active shadow-md' : 'tab-inactive hover:bg-gray-50' }}">
                    📅 Mingguan
                </a>
                <a href="{{ route('leaderboard', ['period' => 'monthly']) }}"
                   class="px-5 py-2 text-sm font-bold rounded-full transition-all {{ $period === 'monthly' ? 'tab-active shadow-md' : 'tab-inactive hover:bg-gray-50' }}">
                    🗓️ Bulanan
                </a>
                <a href="{{ route('leaderboard', ['period' => 'all']) }}"
                   class="px-5 py-2 text-sm font-bold rounded-full transition-all {{ $period === 'all' ? 'tab-active shadow-md' : 'tab-inactive hover:bg-gray-50' }}">
                    ∞ Sepanjang Waktu
                </a>
            </div>

            {{-- Top 3 Podium --}}
            @if ($leaders->count() >= 3)
            <div class="flex items-end justify-center gap-4 mb-8">
                {{-- 2nd Place --}}
                <div class="text-center flex-1 max-w-[140px]">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-gray-300 to-gray-400 flex items-center justify-center text-white font-bold text-xl mx-auto mb-2 ring-4 ring-gray-200">
                        {{ strtoupper(substr($leaders[1]->name, 0, 1)) }}
                    </div>
                    <div class="text-xl mb-1">🥈</div>
                    <div class="bg-gradient-to-br from-gray-100 to-gray-200 border border-gray-300 rounded-xl p-3 min-h-[80px] flex flex-col items-center justify-center">
                        <p class="font-bold text-gray-700 text-sm line-clamp-1">{{ $leaders[1]->name }}</p>
                        <p class="text-xs text-gray-500 font-semibold">{{ number_format($leaders[1]->display_points) }} poin</p>
                    </div>
                </div>

                {{-- 1st Place --}}
                <div class="text-center flex-1 max-w-[160px] -translate-y-4">
                    <div class="w-18 h-18 rounded-full bg-gradient-to-br from-amber-400 to-yellow-500 flex items-center justify-center text-white font-bold text-2xl mx-auto mb-2 ring-4 ring-amber-300 shadow-lg" style="width:4.5rem;height:4.5rem;">
                        {{ strtoupper(substr($leaders[0]->name, 0, 1)) }}
                    </div>
                    <div class="text-2xl mb-1">👑</div>
                    <div class="bg-gradient-to-br from-amber-50 to-yellow-100 border-2 border-amber-400 rounded-2xl p-4 min-h-[100px] flex flex-col items-center justify-center shadow-md">
                        <p class="font-extrabold text-amber-800 text-sm line-clamp-1">{{ $leaders[0]->name }}</p>
                        <p class="text-xs text-amber-700 font-bold">{{ number_format($leaders[0]->display_points) }} poin</p>
                        @if ($leaders[0]->badges->isNotEmpty())
                            <div class="flex flex-wrap gap-0.5 justify-center mt-1.5">
                                @foreach ($leaders[0]->badges->take(3) as $b)
                                    <span title="{{ $b->name }}" class="text-base">{{ $b->icon }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- 3rd Place --}}
                <div class="text-center flex-1 max-w-[140px]">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-orange-600 to-amber-700 flex items-center justify-center text-white font-bold text-xl mx-auto mb-2 ring-4 ring-orange-300">
                        {{ strtoupper(substr($leaders[2]->name, 0, 1)) }}
                    </div>
                    <div class="text-xl mb-1">🥉</div>
                    <div class="bg-gradient-to-br from-orange-50 to-amber-100 border border-amber-600/40 rounded-xl p-3 min-h-[80px] flex flex-col items-center justify-center">
                        <p class="font-bold text-amber-900 text-sm line-clamp-1">{{ $leaders[2]->name }}</p>
                        <p class="text-xs text-amber-700 font-semibold">{{ number_format($leaders[2]->display_points) }} poin</p>
                    </div>
                </div>
            </div>
            @endif

            {{-- Full Rankings List --}}
            @if ($leaders->isEmpty())
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700/80 p-12 text-center shadow-sm">
                    {{-- Trophy with Question Mark SVG --}}
                    <div class="w-48 h-36 mx-auto mb-4 relative">
                        <svg viewBox="0 0 160 120" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full mx-auto">
                            <!-- Piala Kuning Emas -->
                            <path d="M55 40 H105 V70 C105 85, 80 90, 80 90 C80 90, 55 85, 55 70 Z" fill="#facc15"/>
                            <rect x="75" y="90" width="10" height="15" fill="#eab308"/>
                            <rect x="60" y="105" width="40" height="6" rx="3" fill="#cbd5e1" class="dark:fill-slate-600"/>
                            
                            <!-- Kup Kup Piala (Grip Kiri Kanan) -->
                            <path d="M55 45 H45 V60 H55" stroke="#facc15" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M105 45 H115 V60 H105" stroke="#facc15" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                            
                            <!-- Tanda Tanya di Tengah Piala -->
                            <text x="80" y="68" font-family="'Plus Jakarta Sans', sans-serif" font-size="24" font-weight="900" fill="#ca8a04" text-anchor="middle">?</text>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white mb-1">Belum ada data peringkat untuk periode ini</h3>
                    <p class="text-xs text-gray-550 dark:text-gray-400 max-w-xs mx-auto">Jadilah kontributor pertama! Mulailah melaporkan masalah lingkungan atau ikut berpartisipasi dalam event aksi lingkungan untuk mengumpulkan poin.</p>
                </div>
            @else
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="divide-y divide-gray-50">
                        @foreach ($leaders as $rank => $leader)
                            @php
                                $rankNum = $rank + 1;
                                $isSelf  = auth()->check() && auth()->id() === $leader->id;
                                $rowClass = match ($rankNum) {
                                    1 => 'rank-gold rank-row',
                                    2 => 'rank-silver rank-row',
                                    3 => 'rank-bronze rank-row',
                                    default => 'hover:bg-gray-50/50 rank-row',
                                };
                                if ($isSelf) $rowClass .= ' highlight-self';
                            @endphp
                            <div class="flex items-center gap-4 px-5 py-4 {{ $rowClass }} transition-colors">
                                {{-- Rank Badge --}}
                                <div class="flex-shrink-0 w-9 h-9 flex items-center justify-center rounded-full font-bold text-sm
                                    {{ $rankNum === 1 ? 'rank-badge-gold' : ($rankNum === 2 ? 'rank-badge-silver' : ($rankNum === 3 ? 'rank-badge-bronze' : 'bg-gray-100 text-gray-500')) }}">
                                    @if ($rankNum === 1) 🥇
                                    @elseif ($rankNum === 2) 🥈
                                    @elseif ($rankNum === 3) 🥉
                                    @else {{ $rankNum }}
                                    @endif
                                </div>

                                {{-- Avatar --}}
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-emerald-600 flex items-center justify-center text-white font-bold text-base flex-shrink-0">
                                    {{ strtoupper(substr($leader->name, 0, 1)) }}
                                </div>

                                {{-- Nama + Badges --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('profil', $leader) }}"
                                           class="font-bold text-gray-800 hover:text-green-700 transition-colors text-sm {{ $isSelf ? 'text-green-700' : '' }}">
                                            {{ $leader->name }}
                                            @if ($isSelf)
                                                <span class="text-xs text-green-600 font-semibold bg-green-100 px-1.5 py-0.5 rounded-full ml-1">Kamu</span>
                                            @endif
                                        </a>
                                    </div>
                                    @if ($leader->badges->isNotEmpty())
                                        <div class="flex flex-wrap gap-0.5 mt-0.5">
                                            @foreach ($leader->badges->take(5) as $b)
                                                <span title="{{ $b->name }}" class="text-sm">{{ $b->icon }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                {{-- Poin --}}
                                <div class="flex-shrink-0 text-right">
                                    <p class="text-base font-extrabold {{ $rankNum <= 3 ? 'text-amber-700' : 'text-gray-800' }}">
                                        {{ number_format($leader->display_points) }}
                                    </p>
                                    <p class="text-[10px] text-gray-400 uppercase tracking-wide">poin</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
