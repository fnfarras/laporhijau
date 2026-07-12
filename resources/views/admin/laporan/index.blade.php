<x-admin-layout>
    @section('title', 'Semua Laporan')

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Laporan</h1>
            <p class="text-sm text-gray-500 mt-0.5">Pantau dan kelola seluruh laporan di platform</p>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-3 md:grid-cols-6 gap-3 mb-6">
        @php
            $statusCards = [
                ['label'=>'Total',       'value'=>$counts['total'],       'color'=>'gray',   'icon'=>'📝'],
                ['label'=>'Pending',     'value'=>$counts['pending'],     'color'=>'amber',  'icon'=>'⏳'],
                ['label'=>'Verified',    'value'=>$counts['verified'],    'color'=>'green',  'icon'=>'✅'],
                ['label'=>'Diproses',    'value'=>$counts['in_progress'], 'color'=>'blue',   'icon'=>'🔧'],
                ['label'=>'Selesai',     'value'=>$counts['resolved'],    'color'=>'emerald','icon'=>'🎉'],
                ['label'=>'Ditolak',     'value'=>$counts['rejected'],    'color'=>'red',    'icon'=>'❌'],
            ];
            $colorMap = [
                'gray'    => 'bg-gray-50 border-gray-200 text-gray-700',
                'amber'   => 'bg-amber-50 border-amber-200 text-amber-700',
                'green'   => 'bg-green-50 border-green-200 text-green-700',
                'blue'    => 'bg-blue-50 border-blue-200 text-blue-700',
                'emerald' => 'bg-emerald-50 border-emerald-200 text-emerald-700',
                'red'     => 'bg-red-50 border-red-200 text-red-700',
            ];
        @endphp
        @foreach ($statusCards as $card)
            <a href="{{ route('admin.laporan.index', ['status' => strtolower($card['label']) === 'total' ? '' : strtolower($card['label'])]) }}"
               class="p-3 rounded-2xl border {{ $colorMap[$card['color']] }} text-center hover:shadow-sm transition-shadow block">
                <div class="text-xl mb-1">{{ $card['icon'] }}</div>
                <p class="text-2xl font-black">{{ $card['value'] }}</p>
                <p class="text-xs font-semibold mt-0.5">{{ $card['label'] }}</p>
            </a>
        @endforeach
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
        <form method="GET" action="{{ route('admin.laporan.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-48">
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Cari Laporan</label>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Judul atau alamat..."
                    class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-300">
            </div>
            <div class="min-w-36">
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-300 bg-white">
                    <option value="">Semua Status</option>
                    <option value="pending"     {{ request('status') === 'pending'     ? 'selected' : '' }}>⏳ Pending</option>
                    <option value="verified"    {{ request('status') === 'verified'    ? 'selected' : '' }}>✅ Verified</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>🔧 In Progress</option>
                    <option value="resolved"    {{ request('status') === 'resolved'    ? 'selected' : '' }}>🎉 Resolved</option>
                    <option value="rejected"    {{ request('status') === 'rejected'    ? 'selected' : '' }}>❌ Rejected</option>
                </select>
            </div>
            <div class="min-w-44">
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Kategori</label>
                <select name="category" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-300 bg-white">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->icon }} {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-xl transition-colors">Filter</button>
                <a href="{{ route('admin.laporan.index') }}" class="px-5 py-2 border border-gray-200 text-gray-500 text-sm font-semibold rounded-xl hover:bg-gray-50">Reset</a>
            </div>
        </form>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-50 flex items-center justify-between">
            <p class="text-xs text-gray-500">{{ $reports->total() }} laporan ditemukan</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs text-gray-400 uppercase tracking-wide bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-5 py-3 font-semibold">Laporan</th>
                        <th class="text-left px-4 py-3 font-semibold hidden md:table-cell">Kategori</th>
                        <th class="text-left px-4 py-3 font-semibold hidden lg:table-cell">Pelapor</th>
                        <th class="text-left px-4 py-3 font-semibold">Status</th>
                        <th class="text-left px-4 py-3 font-semibold hidden lg:table-cell">Tanggal</th>
                        <th class="text-right px-4 py-3 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($reports as $report)
                        @php
                            $statusConfig = [
                                'pending'     => ['icon'=>'⏳','class'=>'bg-amber-100 text-amber-700'],
                                'verified'    => ['icon'=>'✅','class'=>'bg-green-100 text-green-700'],
                                'in_progress' => ['icon'=>'🔧','class'=>'bg-blue-100 text-blue-700'],
                                'resolved'    => ['icon'=>'🎉','class'=>'bg-emerald-100 text-emerald-700'],
                                'rejected'    => ['icon'=>'❌','class'=>'bg-red-100 text-red-700'],
                            ];
                            $sc = $statusConfig[$report->status] ?? ['icon'=>'❓','class'=>'bg-gray-100 text-gray-600'];
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-5 py-4 max-w-xs">
                                <a href="{{ route('laporan.show', $report) }}" target="_blank"
                                   class="font-semibold text-gray-800 hover:text-green-600 transition-colors line-clamp-1 block">
                                    {{ $report->title }}
                                </a>
                                <p class="text-xs text-gray-400 mt-0.5 truncate">📍 {{ Str::limit($report->address, 45) }}</p>
                            </td>
                            <td class="px-4 py-4 hidden md:table-cell">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                    {{ $report->category?->icon ?? '📋' }} {{ $report->category?->name ?? '—' }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-xs text-gray-500 hidden lg:table-cell">
                                @if($report->is_anonymous)
                                    🔒 {{ $report->reporter_name }} <span class="text-gray-300">(Anonim)</span>
                                @else
                                    {{ $report->reporter_name }}
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $sc['class'] }}">
                                    {{ $sc['icon'] }} {{ ucfirst(str_replace('_',' ',$report->status)) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-xs text-gray-400 hidden lg:table-cell whitespace-nowrap">
                                {{ $report->created_at->format('d M Y') }}
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('laporan.show', $report) }}" target="_blank"
                                       class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-semibold rounded-lg transition-colors">
                                        👁️ Lihat
                                    </a>
                                    <form method="POST" action="{{ route('admin.laporan.destroy', $report) }}"
                                          onsubmit="return confirm('Hapus PERMANEN laporan \'{{ addslashes($report->title) }}\'?\n\nAksi ini tidak dapat dibatalkan.')">
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
                            <td colspan="6" class="px-5 py-16 text-center text-gray-400">
                                <div class="text-4xl mb-2">📭</div>
                                <p class="text-sm font-semibold">Tidak ada laporan ditemukan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($reports->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">{{ $reports->links() }}</div>
        @endif
    </div>
</x-admin-layout>
