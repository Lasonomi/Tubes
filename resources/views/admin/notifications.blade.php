@extends('layouts.admin')

@section('content')
    <div class="max-w-7xl mx-auto">
        <h1 class="text-4xl font-bold text-gray-800 mb-12">Notifikasi Admin</h1>

        <!-- Pesanan Baru -->
        <div class="mb-16">
            <div class="flex items-center mb-8">
                <svg class="w-12 h-12 text-indigo-600 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <h2 class="text-3xl font-bold text-gray-800">
                    Pesanan Baru ({{ $newOrders->count() }} terbaru)
                </h2>
            </div>

            @if($newOrders->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    @foreach($newOrders as $index => $order)
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition">
                            <!-- Header Card -->
                            <div class="bg-gradient-to-r from-indigo-50 to-white p-6 border-b border-gray-200">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-2xl font-bold text-gray-800">
                                            #{{ $order->id }} - {{ $order->user->name }}
                                        </p>
                                        <p class="text-gray-700 mt-2">Email: {{ $order->user->email }}</p>
                                        @if($order->user->phone)
                                            <p class="text-gray-700">HP: {{ $order->user->phone }}</p>
                                        @endif
                                        <p class="text-gray-600 mt-3">
                                            Tanggal: {{ $order->created_at->format('d M Y, H:i') }}
                                        </p>
                                    </div>
                                    <span class="bg-green-600 text-white font-bold px-6 py-3 rounded-full text-lg">
                                        Paid
                                    </span>
                                </div>

                                <p class="text-3xl font-bold text-indigo-600 mt-6">
                                    Total: Rp {{ number_format($order->total, 0, ',', '.') }}
                                </p>
                            </div>

                            <!-- Item Pesanan -->
                            <div class="p-6 bg-gray-50">
                                <p class="font-semibold text-lg text-gray-800 mb-4">Item Pesanan:</p>
                                <ul class="space-y-4">
                                    @foreach($order->items as $item)
                                        <li class="flex justify-between items-center bg-white rounded-lg p-4 shadow-sm">
                                            <span class="text-gray-800">
                                                {{ $item->quantity }}x {{ $item->product->name }}
                                            </span>
                                            <span class="font-medium text-indigo-600">
                                                Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <!-- Tombol Print Nota -->
                            <div class="p-6 text-center bg-white">
                                <a href="{{ route('orders.invoice', $order) }}" target="_blank"
                                   class="inline-block bg-black hover:bg-gray-800 text-white font-bold text-lg py-4 px-12 rounded-lg transition shadow-lg">
                                    Print Nota Pesanan
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-20 bg-gray-50 rounded-2xl">
                    <p class="text-2xl text-gray-500">Belum ada pesanan baru hari ini</p>
                </div>
            @endif
        </div>

        <!-- Stok Rendah -->
        <div>
            <div class="flex items-center mb-8">
                <svg class="w-12 h-12 text-red-600 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <h2 class="text-3xl font-bold text-gray-800">
                    Produk Stok Rendah (< 10 unit) - {{ $lowStockProducts->count() }}
                </h2>
            </div>

            @if($lowStockProducts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($lowStockProducts as $product)
                        <div class="bg-red-50 rounded-2xl p-8 border-2 border-red-200 hover:shadow-xl transition text-center">
                            <p class="text-2xl font-bold text-gray-800 mb-2">{{ $product->name }}</p>
                            <p class="text-gray-600 mb-6">{{ $product->category->name }}</p>
                            <p class="text-5xl font-bold text-red-600 mb-6">{{ $product->stock }}</p>
                            <p class="text-lg text-gray-700 mb-8">unit tersisa</p>
                            <a href="{{ route('admin.products.edit', $product) }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-4 px-10 rounded-lg transition shadow-lg">
                                Restok Sekarang →
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-20 bg-green-50 rounded-2xl">
                    <svg class="w-32 h-32 text-green-500 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-3xl font-bold text-green-600">Semua stok aman!</p>
                </div>
            @endif
        </div>
    </div>
@endsection