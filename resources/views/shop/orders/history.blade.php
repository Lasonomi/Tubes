<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-10 text-center">Riwayat Pembelian</h1>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg mb-8 text-center">
                    {{ session('success') }}
                </div>
            @endif

            @if($orders->count() > 0)
                <div class="space-y-8">
                    @foreach($orders as $order)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                            <div class="flex justify-between items-center mb-6">
                                <div>
                                    <p class="text-sm text-gray-600">Order ID: #{{ $order->id }}</p>
                                    <p class="text-lg font-semibold">Tanggal: {{ $order->created_at->format('d M Y, H:i') }}</p>
                                    <p class="text-sm">Status: <span class="font-medium {{ $order->status == 'pending' ? 'text-orange-600' : 'text-green-600' }}">{{ ucfirst($order->status) }}</span></p>
                                </div>
                                <p class="text-2xl font-bold text-indigo-600">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                            </div>

                            <div class="border-t pt-6">
                                <h3 class="font-medium mb-4">Produk yang dibeli:</h3>
                                <ul class="space-y-3">
                                    @foreach($order->items as $item)
                                        <li class="flex justify-between">
                                            <span>{{ $item->quantity }}x {{ $item->product->name }}</span>
                                            <span>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-20 bg-white rounded-lg shadow-sm border">
                    <p class="text-2xl text-gray-500 mb-6">Belum ada riwayat pembelian</p>
                    <a href="{{ route('shop.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white py-3 px-8 rounded-lg">
                        Mulai Belanja
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>