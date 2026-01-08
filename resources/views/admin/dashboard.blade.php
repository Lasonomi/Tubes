@extends('layouts.admin')

@section('content')
    <div class="max-w-7xl mx-auto">
        <h1 class="text-4xl font-bold text-gray-800 mb-12">Dashboard Admin</h1>

        <!-- Statistik Utama dengan Badge Notifikasi -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
            <!-- Total Produk -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8 text-center hover:shadow-xl transition">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <p class="text-lg font-medium text-gray-600">Total Produk</p>
                <p class="text-4xl font-bold text-indigo-600 mt-3">{{ $totalProducts }}</p>
            </div>

            <!-- Pesanan Baru Hari Ini -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8 text-center hover:shadow-xl transition">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <p class="text-lg font-medium text-gray-600">Total Pesanan</p>
                <p class="text-4xl font-bold text-green-600 mt-3">{{ $totalOrders }}</p>
            </div>

            <!-- Notifikasi Belum Dilihat -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8 text-center hover:shadow-xl transition relative overflow-hidden">
                @if($notifications > 0)
                    <div class="absolute -top-4 -right-4 bg-red-600 text-white font-bold text-2xl px-8 py-4 rounded-bl-3xl shadow-2xl transform rotate-12">
                        {{ $notifications }}
                    </div>
                @endif
                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <p class="text-lg font-medium text-gray-600">Notifikasi Belum Dilihat</p>
                <p class="text-4xl font-bold text-orange-600 mt-3">{{ $notifications }}</p>
                <p class="text-sm text-gray-500 mt-3">
                    {{ $newOrdersToday }} pesanan baru + stok rendah
                </p>
                <a href="{{ route('admin.notifications') }}" class="mt-6 inline-block bg-orange-600 hover:bg-orange-700 text-white font-medium py-3 px-8 rounded-lg transition shadow">
                    Lihat Semua Notifikasi →
                </a>
            </div>

            <!-- Total Penjualan Bulan Ini (Opsional) -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8 text-center hover:shadow-xl transition">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-lg font-medium text-gray-600">Total Penjualan</p>
                <p class="text-4xl font-bold text-purple-600 mt-3">
                    Rp {{ number_format($monthlySales, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <!-- Leaderboard & Stok Rendah -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            <!-- Top 5 Produk Terlaris -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <svg class="w-8 h-8 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Top 5 Produk Terlaris
                </h2>
                <div class="space-y-4">
                    @forelse($topProducts as $index => $product)
                        <div class="flex items-center justify-between p-4 rounded-lg {{ $index == 0 ? 'bg-yellow-50 border-2 border-yellow-400' : 'bg-gray-50' }} hover:shadow transition">
                            <div class="flex items-center">
                                <span class="text-2xl font-bold text-gray-400 mr-4 w-8 text-right">#{{ $index + 1 }}</span>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $product->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $product->category->name }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-indigo-600">{{ $product->sold }}</p>
                                <p class="text-sm text-gray-600">terjual</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-10">Belum ada penjualan</p>
                    @endforelse
                </div>
            </div>

            <!-- Stok Rendah -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <svg class="w-8 h-8 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Stok Rendah (< 10 unit)
                </h2>
                <div class="space-y-5">
                    @forelse($lowStock as $product)
                        <div class="flex justify-between items-center p-5 rounded-lg bg-red-50 border border-red-200 hover:shadow transition">
                            <div>
                                <p class="font-bold text-gray-800">{{ $product->name }}</p>
                                <p class="text-sm text-gray-600">{{ $product->category->name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-3xl font-bold text-red-600">{{ $product->stock }}</p>
                                <p class="text-sm text-gray-600">unit tersisa</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-16">
                            <svg class="w-20 h-20 text-green-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-xl font-semibold text-green-600">Semua stok aman!</p>
                        </div>
                    @endforelse
                </div>
                @if($lowStock->count() > 0)
                    <a href="{{ route('admin.products.index') }}" class="block mt-8 text-center bg-red-600 hover:bg-red-700 text-white font-medium py-4 rounded-lg transition shadow">
                        Restok Sekarang →
                    </a>
                @endif
            </div>
        </div>

        <!-- Link ke Halaman Lain -->
        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-8">
            <a href="{{ route('admin.products.index') }}" class="bg-white rounded-2xl shadow-lg p-8 text-center hover:shadow-xl transition border border-gray-200">
                <svg class="w-16 h-16 text-indigo-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <p class="text-xl font-bold text-gray-800">Kelola Produk</p>
            </a>

            <a href="{{ route('admin.charts') }}" class="bg-white rounded-2xl shadow-lg p-8 text-center hover:shadow-xl transition border border-gray-200">
                <svg class="w-16 h-16 text-green-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <p class="text-xl font-bold text-gray-800">Lihat Grafik & Statistik</p>
            </a>

            <a href="{{ route('admin.notifications') }}" class="bg-white rounded-2xl shadow-lg p-8 text-center hover:shadow-xl transition border border-gray-200 relative overflow-hidden">
                @if($notifications > 0)
                    <div class="absolute -top-4 -right-4 bg-red-600 text-white font-bold text-3xl px-10 py-6 rounded-bl-3xl shadow-2xl transform rotate-12">
                        {{ $notifications }}
                    </div>
                @endif
                <svg class="w-16 h-16 text-orange-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <p class="text-xl font-bold text-gray-800">Cek Notifikasi</p>
            </a>
        </div>
    </div>
@endsection