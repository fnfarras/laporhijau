<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LaporHijau') }} — @yield('title', 'Platform Laporan Lingkungan')</title>

        <!-- SEO Meta Tags -->
        <meta name="description" content="@yield('meta_description', 'LaporHijau — Platform civic tech untuk pelaporan, pemantauan, dan penanganan masalah lingkungan hidup secara kolaboratif di Indonesia.')">
        <meta name="keywords" content="laporan lingkungan, civic tech, lingkungan hidup, sampah, banjir, komunitas, relawan">
        <meta name="author" content="LaporHijau">
        <meta name="robots" content="index, follow">
        <link rel="canonical" href="{{ url()->current() }}">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="@yield('title', 'LaporHijau — Platform Lingkungan Komunitas')">
        <meta property="og:description" content="@yield('meta_description', 'Platform civic tech untuk pelaporan masalah lingkungan di Indonesia.')">
        <meta property="og:site_name" content="LaporHijau">
        <meta property="og:locale" content="id_ID">

        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="@yield('title', 'LaporHijau')">
        <meta name="twitter:description" content="@yield('meta_description', 'Platform pelaporan masalah lingkungan Indonesia.')">

        <!-- Plus Jakarta Sans — design system LaporHijau -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- App CSS & JS (Vite) -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Extra styles (Leaflet, dll) -->
        @stack('styles')

    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white border-b border-gray-200">
                    <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Flash messages -->
            @if (session('success'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 flex items-center gap-2">
                        <span class="text-green-500">✅</span>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 flex items-center gap-2">
                        <span>❌</span>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- Extra scripts (Leaflet, dll) -->
        @stack('scripts')
    </body>
</html>
