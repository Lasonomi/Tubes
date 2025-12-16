@extends('layouts.admin')

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between items-center mb-10">
            <h1 class="text-3xl font-semibold text-gray-800">Notifikasi</h1>
            <span class="bg-red-100 text-red-800 text-lg font-bold px-4 py-2 rounded-full">
                {{ $totalNotifications }} Notifikasi Baru
            </span>
        </div>

        <div class="space-y-8">
            <!-- Notifikasi Pesanan Baru -->
            @if($newOrders->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                        <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm mr-3">Baru</span>
                        Pesanan Baru Menunggu Konfirmasi ({{ $newOrders->count() }})
                    </h2>
                    <div class="space-y-4">
                        @foreach($newOrders as $order)
                            <div class="border-l-4 border-orange-500 pl-6 py-4 bg-orange-50 rounded-r-lg">
                                <p class="font-medium">Order #{{ $order->id }} - {{ $order->user->name }}</p>
                                <p class="text-sm text-gray-600">Total: Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                                <p class="text-sm text-gray-500">Tanggal: {{ $order->created_at->format('d M Y, H:i') }}</p>
                                <a href="#" class="text-indigo-600 hover:underline text-sm mt-2 inline-block">Lihat Detail →</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Notifikasi Stok Rendah / Habis -->
            @if($lowStockProducts->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                        <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm mr-3">Penting</span>
                        Stok Produk Rendah ({{ $lowStockProducts->count() }} produk)
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($lowStockProducts as $product)
                            <div class="border-l-4 {{ $product->stock == 0 ? 'border-red-600' : 'border-yellow-500' }} pl-6 py-4 {{ $product->stock == 0 ? 'bg-red-50' : 'bg-yellow-50' }} rounded-r-lg">
                                <p class="font-medium">{{ $product->name }}</p>
                                <p class="text-sm text-gray-600">Kategori: {{ $product->category->name }}</p>
                                <p class="font-bold mt-2 {{ $product->stock == 0 ? 'text-red-700' : 'text-yellow-700' }}">
                                    Stok tersisa: {{ $product->stock }} unit
                                    {{ $product->stock == 0 ? '(HABIS!)' : '' }}
                                </p>
                                <a href="{{ route('admin.products.edit', $product) }}" class="text-indigo-600 hover:underline text-sm mt-2 inline-block">Restok Sekarang →</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Kalau Tidak Ada Notifikasi -->
            @if($newOrders->count() == 0 && $lowStockProducts->count() == 0)
                <div class="text-center py-20 bg-white rounded-lg shadow-sm border border-gray-200">
                    <p class="text-2xl text-gray-500 mb-4">Semua aman!</p>
                    <p class="text-gray-600">Tidak ada notifikasi baru saat ini.</p>
                </div>
            @endif
        </div>
    </div>
@endsection