<x-app-layout>
    @section('title', 'Katalog Penukaran Hadiah')

    @push('styles')
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        .reward-card {
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.4s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.4s;
        }
        .reward-card:hover {
            transform: translateY(-6px);
            border-color: rgba(22, 163, 74, 0.35);
            box-shadow: 0 20px 35px -10px rgba(22, 163, 74, 0.08);
        }

        .glow-points {
            box-shadow: 0 0 15px rgba(22, 163, 74, 0.15);
            border: 1px solid rgba(22, 163, 74, 0.2);
        }
    </style>
    @endpush

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- ── Alert Messages ── --}}
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-300 rounded-2xl flex items-start gap-3 shadow-sm animate-pulse-subtle">
                    <span class="text-xl">🎉</span>
                    <div>
                        <p class="font-bold text-sm">Transaksi Berhasil</p>
                        <p class="text-xs text-green-700/90 dark:text-green-400/90 mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-300 rounded-2xl flex items-start gap-3 shadow-sm">
                    <span class="text-xl">⚠️</span>
                    <div>
                        <p class="font-bold text-sm">Penukaran Gagal</p>
                        <p class="text-xs text-red-700/90 dark:text-red-400/90 mt-0.5">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            {{-- ── Header Area ── --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10 pb-6 border-b border-gray-100 dark:border-gray-800">
                <div>
                    <h1 class="text-3xl font-black text-gray-900 dark:text-white flex items-center gap-2">
                        🎁 Penukaran Hadiah
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Tukarkan poin kontribusi lingkungan Anda dengan berbagai reward menarik dari Pemda & Swasta.
                    </p>
                </div>

                {{-- User Points display --}}
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-950/20 dark:to-emerald-950/10 p-5 rounded-2xl glow-points flex items-center gap-4 min-w-[220px]">
                    <div class="w-12 h-12 rounded-xl bg-green-600 text-white flex items-center justify-center text-2xl shadow-md">
                        ⭐
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-green-600 dark:text-green-400 uppercase tracking-wider">Poin Anda Saat Ini</p>
                        <p class="text-2xl font-black text-gray-900 dark:text-white">
                            {{ number_format(auth()->user()->points) }}
                            <span class="text-xs font-semibold text-gray-400 dark:text-gray-500">Poin</span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- ── Rewards Grid ── --}}
            <h2 class="text-lg font-black text-gray-800 dark:text-white mb-6 flex items-center gap-2">
                <span>📚</span> Katalog Hadiah Aktif
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                @foreach ($rewards as $reward)
                    @php
                        $canRedeem = auth()->user()->points >= $reward['points'];
                    @endphp
                    <div class="reward-card bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-3xl p-6 flex flex-col justify-between shadow-sm relative overflow-hidden">
                        
                        {{-- Category Ribbon --}}
                        <div class="absolute top-4 right-4">
                            <span class="text-[10px] font-extrabold uppercase px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300">
                                {{ $reward['category'] }}
                            </span>
                        </div>

                        <div>
                            {{-- Icon & Info --}}
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-green-50 to-emerald-100 dark:from-green-950/30 dark:to-emerald-900/20 flex items-center justify-center text-3xl shadow-inner">
                                    {{ $reward['icon'] }}
                                </div>
                                <div class="pr-20">
                                    <h3 class="font-extrabold text-gray-900 dark:text-white text-base leading-snug">
                                        {{ $reward['title'] }}
                                    </h3>
                                    <p class="text-xs font-black text-green-600 dark:text-green-400 mt-1 flex items-center gap-1">
                                        <span>⭐</span> {{ $reward['points'] }} Poin
                                    </p>
                                </div>
                            </div>

                            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed mb-6">
                                {{ $reward['description'] }}
                            </p>
                        </div>

                        {{-- Action Form --}}
                        <div>
                            @if ($canRedeem)
                                <form action="{{ route('hadiah.redeem') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menukarkan {{ $reward['points'] }} poin dengan {{ $reward['title'] }}?')">
                                    @csrf
                                    <input type="hidden" name="reward_id" value="{{ $reward['id'] }}">
                                    <button type="submit" class="w-full py-3 px-4 bg-green-600 hover:bg-green-700 text-white font-extrabold rounded-xl text-xs transition-all shadow-md shadow-green-100 dark:shadow-none flex items-center justify-center gap-2">
                                        🎁 Tukarkan Sekarang
                                    </button>
                                </form>
                            @else
                                <button disabled class="w-full py-3 px-4 bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 font-extrabold rounded-xl text-xs cursor-not-allowed">
                                    🔒 Poin Tidak Cukup (Kurang {{ $reward['points'] - auth()->user()->points }} Poin)
                                </button>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>

            {{-- ── Claim History ── --}}
            <div class="bg-gray-50 dark:bg-gray-800/40 rounded-3xl border border-gray-100 dark:border-gray-800 p-6 md:p-8">
                <h3 class="text-lg font-black text-gray-900 dark:text-white mb-1 flex items-center gap-2">
                    <span>🕒</span> Riwayat Penukaran Saya
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-6">
                    Gunakan kode voucher di bawah ini untuk diserahkan ke instansi pemda atau diklaim ke e-wallet mitra.
                </p>

                @if (count($myRedemptions) === 0)
                    <div class="text-center py-8 text-xs text-gray-400 italic">
                        Belum ada hadiah yang ditukarkan. Ayo kumpulkan poin dengan melaporkan isu lingkungan!
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach ($myRedemptions as $log)
                            @php
                                // Ekstrak kode voucher dari reason "Penukaran Hadiah: ... (Kode Voucher: LH-XXXX)"
                                preg_match('/\(Kode Voucher:\s*(.*?)\)/', $log->reason, $matches);
                                $code = $matches[1] ?? 'N/A';
                                
                                // Bersihkan judul hadiah
                                $cleanReason = preg_replace('/\s*\(Kode Voucher:\s*(.*?)\)/', '', $log->reason);
                                $cleanReason = str_replace('Penukaran Hadiah: ', '', $cleanReason);
                            @endphp
                            <div class="p-4 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm">
                                <div>
                                    <h4 class="font-bold text-xs text-gray-800 dark:text-white leading-tight">
                                        {{ $cleanReason }}
                                    </h4>
                                    <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1">
                                        Klaim pada: {{ $log->created_at->translatedFormat('d F Y, H:i') }} WIB
                                    </p>
                                </div>

                                {{-- Voucher Code Copy Block --}}
                                <div class="flex items-center gap-2">
                                    <div class="px-3.5 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-center">
                                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider leading-none mb-0.5">Kode Voucher</p>
                                        <code class="text-xs font-black text-green-600 dark:text-green-400 select-all">{{ $code }}</code>
                                    </div>
                                    <button onclick="copyToClipboard('{{ $code }}', this)" class="p-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-xl text-xs transition-colors" title="Salin Kode">
                                        📋
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        function copyToClipboard(text, btn) {
            navigator.clipboard.writeText(text).then(() => {
                const originalText = btn.innerHTML;
                btn.innerHTML = '✅';
                btn.title = 'Tersalin!';
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.title = 'Salin Kode';
                }, 2000);
            }).catch(err => {
                alert('Gagal menyalin kode voucher: ' + err);
            });
        }
    </script>
    @endpush
</x-app-layout>
