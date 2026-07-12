<x-admin-layout>
    @section('title', 'Manajemen Event')

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Event Aksi</h1>
            <p class="text-sm text-gray-500 mt-0.5">{{ $events->total() }} event terdaftar</p>
        </div>
        <a href="{{ route('admin.events.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-xl transition-colors shadow-sm">
            + Buat Event
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs text-gray-400 uppercase tracking-wide bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-5 py-3.5 font-semibold">Event</th>
                        <th class="text-left px-4 py-3.5 font-semibold hidden md:table-cell">Kategori</th>
                        <th class="text-left px-4 py-3.5 font-semibold">Tanggal</th>
                        <th class="text-left px-4 py-3.5 font-semibold">Peserta</th>
                        <th class="text-left px-4 py-3.5 font-semibold hidden lg:table-cell">Penyelenggara</th>
                        <th class="text-left px-4 py-3.5 font-semibold">Status</th>
                        <th class="text-right px-4 py-3.5 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($events as $event)
                        @php $upcoming = $event->event_date->isFuture(); @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-5 py-4 max-w-xs">
                                <a href="{{ route('event.show', $event) }}" target="_blank"
                                   class="font-semibold text-gray-800 hover:text-green-600 transition-colors line-clamp-1 block">
                                    {{ $event->title }}
                                </a>
                                <p class="text-xs text-gray-400 mt-0.5 truncate">📍 {{ $event->location }}</p>
                            </td>
                            <td class="px-4 py-4 hidden md:table-cell">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700">
                                    {{ $event->category }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-xs text-gray-600 whitespace-nowrap">
                                {{ $event->event_date->format('d M Y') }}<br>
                                <span class="text-gray-400">{{ $event->event_date->format('H:i') }}</span>
                            </td>
                            <td class="px-4 py-4 text-xs">
                                <span class="font-semibold text-gray-700">{{ $event->active_participants_count }}</span>
                                @if ($event->max_participants)
                                    <span class="text-gray-400">/ {{ $event->max_participants }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-xs text-gray-500 hidden lg:table-cell">
                                {{ $event->organizer?->name ?? '—' }}
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                    {{ $upcoming ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $upcoming ? '🟢 Akan Datang' : '⚫ Selesai' }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.events.edit', $event) }}"
                                       class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition-colors">
                                        ✏️ Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.events.destroy', $event) }}"
                                          onsubmit="return confirm('Hapus event \'{{ $event->title }}\'? Semua peserta RSVP juga akan dihapus.')">
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
                            <td colspan="7" class="px-5 py-16 text-center text-gray-400">
                                <div class="text-4xl mb-2">🗓️</div>
                                <p class="text-sm font-semibold">Belum ada event</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($events->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">{{ $events->links() }}</div>
        @endif
    </div>
</x-admin-layout>
