<x-app-layout>
    @section('title', 'Toko Hadiah Poin — LaporHijau')

    @push('styles')
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            .reward-card {
                transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            }
            .reward-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 12px 20px -8px rgba(22, 163, 74, 0.15);
            }
        </style>
    @endpush

    <div class="bg-gradient-to-b from-green-50/70 to-white dark:from-slate-900/30 dark:to-slate-900 min-h-screen py-10 pb-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

            {{-- ── 1. HEADER SECTION ───────────────────────────────────── --}}
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700/80 p-6 sm:p-8 shadow-sm flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="space-y-2 text-center md:text-left">
                    <h1 class="text-3xl font-black text-gray-900 dark:text-white leading-tight">
                        🎁 Toko Hadiah LaporHijau
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-xl">
                        Tukarkan poin hasil kontribusi aksi lingkunganmu dengan berbagai hadiah dan penghargaan eksklusif.
                    </p>
                </div>

                {{-- User Points Status --}}
                <div class="w-full md:w-auto">
                    @auth
                        <div class="bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-800 rounded-2xl p-5 text-center md:text-right min-w-[200px] shadow-sm">
                            <span class="text-xs font-bold text-green-700 dark:text-green-400 uppercase tracking-wider block">Poin Tersedia</span>
                            <h2 class="text-3xl font-black text-green-700 dark:text-green-400 mt-1">
                                ⭐ {{ number_format($user->points) }} <span class="text-sm font-semibold">Poin</span>
                            </h2>
                        </div>
                    @else
                        <div class="bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900/50 rounded-2xl p-4 text-center max-w-md shadow-xs">
                            <p class="text-xs text-amber-800 dark:text-amber-400 font-semibold mb-2">
                                🔐 Masuk ke akun Anda untuk menukarkan poin dengan hadiah.
                            </p>
                            <a href="{{ route('login') }}" class="inline-block px-4 py-1.5 bg-amber-500 hover:bg-amber-600 active:bg-amber-700 text-white font-extrabold text-[10px] rounded-xl transition-colors shadow-sm">
                                Masuk Sekarang
                            </a>
                        </div>
                    @endauth
                </div>
            </div>

            {{-- ── Alert Success / Error ────────────────────────── --}}
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 rounded-2xl px-4 py-3 text-sm flex items-start gap-2">
                    <span class="mt-0.5">✅</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 rounded-2xl px-4 py-3 text-sm flex items-start gap-2">
                    <span class="mt-0.5">⚠️</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- ── 2. CATALOGUE GRID ───────────────────────────────────── --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach ($rewards as $reward)
                    @php
                        $alreadyRedeemed = in_array($reward->id, $myRedemptionIds);
                        $canRedeem = auth()->check() && ($user->points >= $reward->points_required);
                        
                        // Cek jika reward adalah tipe tunggal dan sudah dimiliki
                        $isSingleOwned = $alreadyRedeemed && in_array($reward->type, ['badge_spesial', 'title']);
                    @endphp
                    <div class="reward-card bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700/80 p-6 flex flex-col justify-between shadow-sm relative overflow-hidden">
                        
                        {{-- Special Ribbon / Badge --}}
                        @if($isSingleOwned)
                            <div class="absolute top-4 right-4 bg-gray-100 dark:bg-slate-700 text-gray-500 dark:text-gray-400 font-bold text-[9px] uppercase tracking-wider px-2.5 py-1 rounded-full border border-gray-200 dark:border-slate-600">
                                Sudah Diredeem
                            </div>
                        @endif

                        <div class="space-y-4">
                            {{-- Large Icon --}}
                            <div class="w-14 h-14 bg-green-50 dark:bg-green-950/40 rounded-2xl flex items-center justify-center text-3xl shadow-sm">
                                {{ $reward->icon }}
                            </div>

                            <div class="space-y-1">
                                <span class="text-[9px] font-bold uppercase tracking-wider text-green-600 bg-green-50 dark:bg-green-950/20 border border-green-200 dark:border-green-900 px-2 py-0.5 rounded-full inline-block">
                                    {{ $reward->type }}
                                </span>
                                <h3 class="font-black text-gray-900 dark:text-white text-base leading-snug">
                                    {{ $reward->name }}
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                                    {{ $reward->description }}
                                </p>
                            </div>
                        </div>

                        {{-- Footer Actions --}}
                        <div class="mt-6 pt-4 border-t border-gray-50 dark:border-slate-700/50 flex flex-col gap-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold text-gray-400">Harga Tukar</span>
                                <span class="font-extrabold text-sm text-green-600 dark:text-green-400">⭐ {{ number_format($reward->points_required) }} Poin</span>
                            </div>

                            @guest
                                <a href="{{ route('login') }}" class="w-full text-center py-3 bg-gray-100 hover:bg-gray-200 dark:bg-slate-750 dark:hover:bg-slate-700 text-gray-700 dark:text-gray-300 font-bold text-xs rounded-2xl transition-all shadow-sm">
                                    Login untuk Redeem
                                </a>
                            @else
                                @if($isSingleOwned)
                                    <button disabled class="w-full py-3 bg-gray-100 dark:bg-slate-700 text-gray-400 dark:text-gray-500 font-bold text-xs rounded-2xl cursor-not-allowed">
                                        Sudah Diredeem
                                    </button>
                                @elseif($canRedeem)
                                    <form method="POST" action="{{ route('hadiah.redeem', $reward) }}">
                                        @csrf
                                        <button type="submit" class="w-full py-3 bg-green-600 hover:bg-green-700 active:bg-green-800 text-white font-extrabold text-xs rounded-2xl transition-all shadow-md hover:shadow-lg">
                                            Redeem Sekarang
                                        </button>
                                    </form>
                                @else
                                    <button disabled class="w-full py-3 bg-gray-100 dark:bg-slate-700 text-gray-400 dark:text-gray-500 font-bold text-xs rounded-2xl cursor-not-allowed">
                                        Butuh {{ number_format($reward->points_required - $user->points) }} poin lagi
                                    </button>
                                @endif
                            @endguest
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>
