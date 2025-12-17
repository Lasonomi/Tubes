<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>eTOKOBAYU @yield('title', '')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Favicon (opsional nanti tambah) -->
    <!-- <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon"> -->
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation Header (Sticky) -->
        @include('layouts.navigation')

        <!-- Page Content -->
        <main class="flex-1">
            {{ $slot }}
        </main>

        <!-- Footer (opsional nanti) -->
        <footer class="bg-white border-t border-gray-200 py-8 mt-20">
            <div class="max-w-7xl mx-auto px-6 text-center text-gray-600">
                <p class="text-lg font-medium">© 2025 eTOKOBAYU. All rights reserved.</p>
                <p class="mt-2">Toko Online Fashion Premium</p>
            </div>
        </footer>
    </div>

    <!-- Alpine.js untuk interaktivitas (jika perlu dropdown, modal, dll) -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>