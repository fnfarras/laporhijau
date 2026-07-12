<x-admin-layout>
    @section('title', 'Manajemen Hadiah')

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Hadiah</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola hadiah yang dapat ditukar oleh pengguna</p>
        </div>
        <a href="{{ route('admin.rewards.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-xl transition-colors shadow-sm">
            + Tambah Hadiah
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse ($rewards as $reward)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                <div class="p-5">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-2xl">
                                {{ $reward->icon ?? '🎁' }}
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 text-sm leading-tight">{{ $reward->name }}</h3>
                                <span class="text-xs text-gray-400 capitalize">{{ $reward->type }}</span>
                            </div>
                        </div>
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $reward->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $reward->is_active ? '✅ Aktif' : '⏸ Nonaktif' }}
                        </span>
                    </div>

                    <p class="text-xs text-gray-500 leading-relaxed mb-4 line-clamp-2">{{ $reward->description }}</p>

                    <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                        <div>
                            <p class="text-xs text-gray-400">Poin dibutuhkan</p>
                            <p class="text-lg font-black text-amber-600">⭐ {{ number_format($reward->points_required) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-400">Ditukar</p>
                            <p class="text-lg font-black text-gray-700">{{ $reward->redemptions_count }}x</p>
                        </div>
                    </div>
                </div>

                <div class="flex border-t border-gray-100 divide-x divide-gray-100">
                    <a href="{{ route('admin.rewards.edit', $reward) }}"
                       class="flex-1 py-3 text-center text-xs font-semibold text-blue-600 hover:bg-blue-50 transition-colors">
                        ✏️ Edit
                    </a>
                    <form method="POST" action="{{ route('admin.rewards.destroy', $reward) }}"
                          onsubmit="return confirm('Hapus hadiah \'{{ $reward->name }}\'?')"
                          class="flex-1">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full py-3 text-xs font-semibold text-red-500 hover:bg-red-50 transition-colors">
                            🗑️ Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-3 p-6">
                <x-empty-state icon="🎁" title="Belum Ada Hadiah" message="Tambahkan hadiah pertama agar pengguna dapat menukarkan poin mereka." />
                <div class="mt-4 text-center">
                    <a href="{{ route('admin.rewards.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold text-sm rounded-xl transition-colors">
                        + Tambah Hadiah
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    @if ($rewards->hasPages())
        <div class="mt-6">{{ $rewards->links() }}</div>
    @endif
</x-admin-layout>
