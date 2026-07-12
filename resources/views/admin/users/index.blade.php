<x-admin-layout>
    @section('title', 'Manajemen Pengguna')

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Pengguna</h1>
            <p class="text-sm text-gray-500 mt-0.5">{{ $users->total() }} pengguna terdaftar</p>
        </div>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-48">
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Cari Pengguna</label>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Nama atau email..."
                    class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-300">
            </div>
            <div class="min-w-36">
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Role</label>
                <select name="role" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-300 bg-white">
                    <option value="">Semua role</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-xl transition-colors">Filter</button>
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2 border border-gray-200 text-gray-500 text-sm font-semibold rounded-xl hover:bg-gray-50">Reset</a>
            </div>
        </form>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs text-gray-400 uppercase tracking-wide bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-5 py-3.5 font-semibold">Pengguna</th>
                        <th class="text-left px-4 py-3.5 font-semibold">Bergabung</th>
                        <th class="text-left px-4 py-3.5 font-semibold">Poin</th>
                        <th class="text-left px-4 py-3.5 font-semibold">Role Saat Ini</th>
                        <th class="text-right px-4 py-3.5 font-semibold">Ubah Role</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-green-400 to-emerald-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-xs text-gray-400">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-4">
                                <span class="font-semibold text-green-600">⭐ {{ number_format($user->points) }}</span>
                            </td>
                            <td class="px-4 py-4">
                                @php $roleName = $user->roles->first()?->name ?? 'Tidak ada role'; @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                    {{ $roleName === 'admin' ? 'bg-amber-100 text-amber-700' :
                                       ($roleName === 'relawan' ? 'bg-green-100 text-green-700' :
                                       ($roleName === 'pemerintah' ? 'bg-sky-100 text-sky-700' : 'bg-gray-100 text-gray-600')) }}">
                                    {{ ucfirst($roleName) }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                @if ($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.update-role', $user) }}" class="flex items-center justify-end gap-2">
                                        @csrf @method('PATCH')
                                        <select name="role" class="px-2 py-1.5 border border-gray-200 rounded-lg text-xs bg-white focus:outline-none focus:ring-1 focus:ring-green-400">
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->name }}" {{ $user->roles->first()?->name === $role->name ? 'selected' : '' }}>
                                                    {{ ucfirst($role->name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-lg transition-colors">Simpan</button>
                                    </form>
                                @else
                                    <span class="text-xs text-gray-300 text-right block">— Akun Anda</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-4">
                                <x-empty-state icon="👥" title="Tidak Ada Pengguna" message="Tidak ada pengguna yang ditemukan sesuai filter pencarian." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($users->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">{{ $users->links() }}</div>
        @endif
    </div>
</x-admin-layout>
