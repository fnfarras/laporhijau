@props(['icon' => '🍃', 'title' => 'Tidak ada data', 'message' => 'Belum ada data yang tersedia saat ini.'])

<div class="flex flex-col items-center justify-center py-16 px-4 text-center">
    <div class="w-24 h-24 bg-green-50 dark:bg-green-900/10 rounded-full flex items-center justify-center text-5xl mb-5 shadow-inner ring-4 ring-green-50/50">
        <span class="opacity-90 transform -rotate-6">{{ $icon }}</span>
    </div>
    <h3 class="text-xl font-extrabold text-gray-900 dark:text-white mb-2 tracking-tight">{{ $title }}</h3>
    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto leading-relaxed">{{ $message }}</p>
    @if(isset($action))
        <div class="mt-8">
            {{ $action }}
        </div>
    @endif
</div>
