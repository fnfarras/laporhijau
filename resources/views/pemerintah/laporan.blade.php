<x-pemerintah-layout>
    @section('title', 'Kelola Laporan')

    <div class="pt-6 pb-4">
        <h1 class="text-2xl font-bold text-gray-900">Kelola Laporan</h1>
        <p class="text-sm text-gray-500 mt-0.5">{{ $reports->total() }} laporan ditemukan</p>
    </div>

    {{-- ── Filter ────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
        <form method="GET" action="{{ route('pemerintah.laporan') }}" class="flex flex-wrap gap-3 items-end">

            {{-- Status --}}
            <div class="flex-1 min-w-36">
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 bg-white">
                    <option value="">Semua status</option>
                    <option value="verified"    {{ $statusFilter === 'verified'    ? 'selected' : '' }}>✅ Verified</option>
                    <option value="in_progress" {{ $statusFilter === 'in_progress' ? 'selected' : '' }}>🔧 In Progress</option>
                    <option value="resolved"    {{ $statusFilter === 'resolved'    ? 'selected' : '' }}>🎉 Resolved</option>
                </select>
            </div>

            {{-- Kategori --}}
            <div class="flex-1 min-w-44">
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Kategori</label>
                <select name="category" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 bg-white">
                    <option value="">Semua kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $categoryFilter == $cat->id ? 'selected' : '' }}>
                            {{ $cat->icon }} {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Tanggal mulai --}}
            <div class="flex-1 min-w-36">
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}"
                    class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-sky-400">
            </div>

            {{-- Tanggal akhir --}}
            <div class="flex-1 min-w-36">
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Sampai Tanggal</label>
                <input type="date" name="date_to" value="{{ $dateTo }}"
                    class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-sky-400">
            </div>

            {{-- Tombol --}}
            <div class="flex gap-2">
                <button type="submit"
                    class="px-5 py-2 bg-sky-600 hover:bg-sky-700 text-white text-sm font-semibold rounded-xl transition-colors">
                    Filter
                </button>
                <a href="{{ route('pemerintah.laporan') }}"
                    class="px-5 py-2 border border-gray-200 text-gray-500 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- ── Tabel Laporan ─────────────────────────────────────── --}}
    @if ($reports->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 p-16 text-center">
            <div class="text-5xl mb-3">🔍</div>
            <h3 class="text-base font-bold text-gray-700 mb-1">Tidak ada laporan ditemukan</h3>
            <p class="text-sm text-gray-400">Coba ubah filter pencarian.</p>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-xs text-gray-400 uppercase tracking-wide bg-gray-50 border-b border-gray-100">
                            <th class="text-left px-5 py-3.5 font-semibold">Laporan</th>
                            <th class="text-left px-4 py-3.5 font-semibold">Kategori</th>
                            <th class="text-left px-4 py-3.5 font-semibold hidden md:table-cell">Pelapor</th>
                            <th class="text-left px-4 py-3.5 font-semibold hidden lg:table-cell">Tanggal</th>
                            <th class="text-left px-4 py-3.5 font-semibold">Status</th>
                            <th class="px-4 py-3.5 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($reports as $report)
                            @php
                                $statusConfig = [
                                    'verified'    => ['label' => 'Verified',     'class' => 'bg-green-100 text-green-700',  'icon' => '✅'],
                                    'in_progress' => ['label' => 'In Progress',  'class' => 'bg-blue-100 text-blue-700',    'icon' => '🔧'],
                                    'resolved'    => ['label' => 'Selesai',      'class' => 'bg-emerald-100 text-emerald-700','icon' => '🎉'],
                                ];
                                $sc = $statusConfig[$report->status] ?? ['label' => $report->status, 'class' => 'bg-gray-100 text-gray-600', 'icon' => '❓'];
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-5 py-4 max-w-xs">
                                    <a href="{{ route('laporan.show', $report) }}" target="_blank"
                                       class="font-semibold text-gray-800 hover:text-sky-600 transition-colors line-clamp-1 block">
                                        {{ $report->title }}
                                    </a>
                                    <p class="text-xs text-gray-400 mt-0.5 truncate">📍 {{ Str::limit($report->address, 45) }}</p>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex items-center gap-1 text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-full whitespace-nowrap">
                                        {{ $report->category->icon ?? '' }} {{ $report->category->name ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-xs text-gray-500 hidden md:table-cell">
                                    {{ $report->user->name ?? '-' }}
                                </td>
                                <td class="px-4 py-4 text-xs text-gray-400 hidden lg:table-cell whitespace-nowrap">
                                    {{ $report->created_at->format('d M Y') }}
                                </td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full {{ $sc['class'] }}">
                                        {{ $sc['icon'] }} {{ $sc['label'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        @if ($report->status === 'verified')
                                            <form method="POST" action="{{ route('pemerintah.update-status', $report) }}">
                                                @csrf
                                                <input type="hidden" name="action" value="in_progress">
                                                <button type="submit"
                                                    class="px-3 py-1.5 bg-sky-600 hover:bg-sky-700 text-white text-xs font-semibold rounded-lg transition-colors whitespace-nowrap">
                                                    🔧 Tangani
                                                </button>
                                            </form>
                                        @elseif ($report->status === 'in_progress')
                                            <form method="POST" action="{{ route('pemerintah.update-status', $report) }}">
                                                @csrf
                                                <input type="hidden" name="action" value="resolved">
                                                <button type="submit"
                                                    class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-lg transition-colors whitespace-nowrap">
                                                    🎉 Selesai
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-gray-300 italic">—</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if ($reports->hasPages())
            {{ $reports->links() }}
        @endif
    @endif
</x-pemerintah-layout>
