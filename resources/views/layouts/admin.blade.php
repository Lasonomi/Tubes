<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Halaman</title>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full text-gray-800 antialiased">
    <div x-data="{ sidebarOpen: true }" class="flex h-full">
        <!-- Sidebar Light Simple -->
        <aside :class="sidebarOpen ? 'w-64' : 'w-20'" class="bg-white border-r border-gray-200 transition-all duration-300 fixed inset-y-0 left-0 z-50 flex flex-col shadow-sm">
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <h1 :class="sidebarOpen ? 'block' : 'hidden'" class="text-xl font-bold text-gray-800">Halaman Admin</h1>
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

           <nav class="flex-1 px-4 py-6 space-y-1">
    <!-- Dashboard -->
    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700' }}">
        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        <span :class="sidebarOpen ? 'ml-3 block' : 'hidden'">Dashboard</span>
    </a>

    <!-- Produk - TAMBAHKAN INI -->
    <a href="{{ route('admin.products.index') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.products*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700' }}">
        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        <span :class="sidebarOpen ? 'ml-3 block' : 'hidden'">Produk</span>
    </a>

    <!-- Grafik -->
    <a href="{{ route('admin.charts') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.charts') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700' }}">
        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        <span :class="sidebarOpen ? 'ml-3 block' : 'hidden'">Grafik</span>
    </a>

    <!-- Notifikasi -->
            <a href="{{ route('admin.notifications') }}" class="relative flex items-center px-4 py-3 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.notifications') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700' }}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            <span :class="sidebarOpen ? 'ml-3 block' : 'hidden'">Notifikasi</span>
            <!-- Badge jumlah notifikasi -->
            @php
                $totalNotif = \App\Models\Order::where('status', 'pending')->count() + \App\Models\Product::where('stock', '<', 10)->count();
            @endphp
            @if($totalNotif > 0)
                <span class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center">
                    {{ $totalNotif > 99 ? '99+' : $totalNotif }}
                </span>
            @endif
        </a>
    </nav>

            <div class="p-4 border-t border-gray-200">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100">
                        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H5a3 3 0 01-3-3v-1m6-4V9a3 3 0 013-3h5a3 3 0 013 3v1"/></svg>
                        <span :class="sidebarOpen ? 'ml-3 block' : 'hidden'">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <main :class="sidebarOpen ? 'ml-64' : 'ml-20'" class="flex-1 p-10 overflow-y-auto transition-all duration-300">
            @yield('content')
        </main>
        @stack('scripts')
    </div>
</body>
</html>