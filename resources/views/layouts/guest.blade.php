<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>eTOKOBAYU - Masuk</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .bg-blue-gradient {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #60a5fa 100%);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="font-sans antialiased min-h-screen bg-blue-gradient flex items-center justify-center relative overflow-hidden text-white">
    <!-- Abstract Background (opsional) -->
    <div class="absolute inset-0 opacity-20">
        <div class="absolute top-0 left-0 w-96 h-96 bg-blue-300 rounded-full mix-blend-multiply filter blur-3xl animate-blob"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-300 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-2"></div>
        <div class="absolute bottom-0 left-1/2 w-96 h-96 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-4"></div>
    </div>

    <!-- Card Login -->
    <div class="relative z-10 w-full max-w-md px-6">
        <div class="glass-card rounded-3xl shadow-2xl overflow-hidden">
            <div class="px-10 py-12 text-center">
                <!-- Logo -->
                <h1 class="text-5xl font-bold mb-4">eTOKOBAYU</h1>
                <p class="text-lg opacity-90 mb-8">Masuk untuk mulai berbelanja</p>

                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>