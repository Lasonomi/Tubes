@extends('layouts.admin')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-10">
            <h1 class="text-3xl font-semibold text-gray-800">Notifikasi Admin</h1>
            <span class="bg-red-100 text-red-800 text-lg font-bold px-5 py-3 rounded-full shadow-sm">
                {{ $totalNotifications }} Notifikasi
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Kolom 1: Pesanan Baru -->
            <div>
                <h2 class="text-2xl font-medium text-gray-800 mb-6 flex items-center">
                    <span class="bg-orange-100 text-orange-800 px-4 py-2 rounded-full text-sm font-bold mr-4">Pesanan</span>
                    Pesanan Baru ({{ $newOrders->count() }})
                </h2>

                @if($newOrders->count() > 0)
                    <div class="space-y-6">
                        @foreach($newOrders as $order)
                            <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <p class="font-bold text-lg">#{{ $order->id }} - {{ $order->user->name }}</p>
                                        <p class="text-sm text-gray-600">Tanggal: {{ $order->created_at->format('d M Y, H:i') }}</p>
                                        <p class="text-lg font-semibold mt-2">Total: Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                                    </div>
                                    <span class="bg-orange-500 text-white px-4 py-2 rounded-full text-sm font-bold">Pending</span>
                                </div>

                                <div class="border-t pt-4">
                                    <p class="font-medium mb-3">Item pesanan:</p>
                                    <ul class="space-y-2">
                                        @foreach($order->items as $item)
                                            <li class="flex justify-between text-sm">
                                                <span>{{ $item->quantity }}x {{ $item->product->name }}</span>
                                                <span>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <div class="flex gap-4 mt-6">
                                    <form action="{{ route('admin.orders.confirm', $order) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-lg transition">
                                            Konfirmasi (Paid)
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.orders.cancel', $order) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" onclick="return confirm('Batalkan pesanan ini? Stok akan dikembalikan.')" class="bg-red-600 hover:bg-red-800 text-white font-medium py-2 px-6 rounded-lg transition">
                                            Tolak / Batal
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 bg-gray-50 p-8 rounded-lg text-center">Tidak ada pesanan baru</p>
                @endif
            </div>

            <!-- Kolom 2: Stok Rendah -->
            <div>
                <h2 class="text-2xl font-medium text-gray-800 mb-6 flex items-center">
                    <span class="bg-red-100 text-red-800 px-4 py-2 rounded-full text-sm font-bold mr-4">Stok</span>
                    Produk Stok Rendah ({{ $lowStockProducts->count() }})
                </h2>

                @if($lowStockProducts->count() > 0)
                    <div class="space-y-6">
                        @foreach($lowStockProducts as $product)
                            <div class="bg-white rounded-lg shadow border border-gray-200 p-6 flex justify-between items-center">
                                <div>
                                    <p class="font-bold text-lg">{{ $product->name }}</p>
                                    <p class="text-sm text-gray-600">Kategori: {{ $product->category->name }}</p>
                                    <p class="font-bold text-red-600 mt-2">
                                        Stok: {{ $product->stock }} unit
                                        {{ $product->stock == 0 ? '(HABIS!)' : '' }}
                                    </p>
                                </div>
                                <a href="{{ route('admin.products.edit', $product) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-6 rounded-lg transition">
                                    Restok →
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 bg-gray-50 p-8 rounded-lg text-center">Semua stok aman</p>
                @endif
            </div>
        </div>
    </div>
@endsection