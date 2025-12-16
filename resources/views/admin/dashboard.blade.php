@extends('layouts.admin')

@section('content')
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-semibold text-gray-800 mb-8">Dashboard Admin</h1>

        <!-- Cards Statistik Simple -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-600">Total Produk</h3>
                <p class="text-3xl font-bold text-indigo-600 mt-4">{{ $totalProducts }}</p>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-600">Pesanan Baru</h3>
                <p class="text-3xl font-bold text-green-600 mt-4">{{ $newOrders }}</p>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-600">Notifikasi</h3>
                <p class="text-3xl font-bold text-orange-600 mt-4">{{ $notifications ?? 0 }}</p>
            </div>
        </div>

        <!-- Leaderboard & Stok Rendah -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Barang Terlaris</h2>
                <table class="w-full">
                    <thead class="border-b border-gray-200">
                        <tr>
                            <th class="text-left py-3 text-gray-600">Produk</th>
                            <th class="text-right py-3 text-gray-600">Terjual</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topProducts as $product)
                            <tr class="border-b border-gray-100">
                                <td class="py-4">{{ $product->name }}</td>
                                <td class="py-4 text-right font-medium">{{ $product->sold }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="py-8 text-center text-gray-500">Belum ada penjualan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Stok Terendah</h2>
                <div class="space-y-4">
                    @forelse($lowStock as $product)
                        <div class="flex justify-between items-center">
                            <span>{{ $product->name }}</span>
                            <div class="flex items-center">
                                <div class="w-32 bg-gray-200 rounded-full h-4 mr-4">
                                    <div class="bg-red-500 h-4 rounded-full" style="width: {{ min(100, $product->stock * 10) }}%"></div>
                                </div>
                                <span class="font-medium text-red-600">{{ $product->stock }} unit</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">Semua stok aman</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Placeholder Grafik -->
        <div class="mt-10 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Grafik Trend Stok</h2>
            <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center text-gray-500 border border-dashed border-gray-300">
                Grafik akan ditambah nanti (Chart.js)
            </div>
        </div>
    </div>
@endsection