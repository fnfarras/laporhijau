@props(['size' => 'md', 'showText' => true, 'textColor' => 'default'])

@php
    $sizes = [
        'sm' => ['box' => 'w-7 h-7', 'text' => 'text-base', 'leaf' => '14'],
        'md' => ['box' => 'w-8 h-8', 'text' => 'text-lg',   'leaf' => '16'],
        'lg' => ['box' => 'w-10 h-10','text' => 'text-xl',  'leaf' => '20'],
        'xl' => ['box' => 'w-14 h-14','text' => 'text-3xl', 'leaf' => '28'],
    ];
    $s = $sizes[$size] ?? $sizes['md'];
    $textColorClass = match($textColor) {
        'white' => 'text-white',
        'gray'  => 'text-gray-700 dark:text-white',
        default => 'text-gray-900 dark:text-white',
    };
@endphp

<div class="flex items-center gap-2.5">
    {{-- Logo Icon --}}
    <div class="{{ $s['box'] }} relative flex-shrink-0">
        <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full drop-shadow-sm">
            <defs>
                <linearGradient id="lh-grad-{{ $size }}" x1="0" y1="0" x2="40" y2="40" gradientUnits="userSpaceOnUse">
                    <stop offset="0%" stop-color="#22c55e"/>
                    <stop offset="100%" stop-color="#15803d"/>
                </linearGradient>
            </defs>
            {{-- Rounded background --}}
            <rect width="40" height="40" rx="10" fill="url(#lh-grad-{{ $size }})"/>
            {{-- Leaf shape --}}
            <path d="M20 8 C28 8 33 14 33 21 C33 28 27 34 20 34 C13 34 7 28 7 21 C7 14 12 8 20 8 Z"
                  fill="white" fill-opacity="0.25"/>
            {{-- Stem --}}
            <line x1="20" y1="34" x2="20" y2="26" stroke="white" stroke-width="2.5" stroke-linecap="round"/>
            {{-- Center vein --}}
            <path d="M20 12 L20 28" stroke="white" stroke-width="1.5" stroke-linecap="round" opacity="0.7"/>
            {{-- Left vein --}}
            <path d="M20 18 C16 16 11 18 10 21" stroke="white" stroke-width="1.2" stroke-linecap="round" opacity="0.6"/>
            {{-- Right vein --}}
            <path d="M20 18 C24 16 29 18 30 21" stroke="white" stroke-width="1.2" stroke-linecap="round" opacity="0.6"/>
            {{-- Report pin dot --}}
            <circle cx="27" cy="13" r="4" fill="white" fill-opacity="0.9"/>
            <circle cx="27" cy="12" r="1.5" fill="#16a34a"/>
            <path d="M27 14.5 L27 17" stroke="white" stroke-width="1.5" stroke-linecap="round" fill-opacity="0.9"/>
        </svg>
    </div>

    @if($showText)
        <span class="font-black {{ $s['text'] }} {{ $textColorClass }} tracking-tight" style="font-family: 'Plus Jakarta Sans', sans-serif;">
            Lapor<span class="text-green-500">Hijau</span>
        </span>
    @endif
</div>
