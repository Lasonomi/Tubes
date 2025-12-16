<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-10 text-center">Keranjang Belanja</h1>

            <!-- Notif -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg mb-8 text-center font-medium shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg mb-8 text-center font-medium shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            @if(empty($cart))
                <div class="text-center py-20 bg-white rounded-lg shadow-sm border border-gray-200">
                    <p class="text-2xl text-gray-500 mb-6">Keranjang kamu kosong</p>
                    <a href="{{ route('shop.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-8 rounded-lg transition">
                        Mulai Belanja
                    </a>
                </div>
            @else
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-10">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="py-4 px-6 text-left text-gray-700 font-medium">Produk</th>
                                    <th class="py-4 px-6 text-center text-gray-700 font-medium">Jumlah</th>
                                    <th class="py-4 px-6 text-right text-gray-700 font-medium">Harga Satuan</th>
                                    <th class="py-4 px-6 text-right text-gray-700 font-medium">Subtotal</th>
                                    <th class="py-4 px-6 text-center text-gray-700 font-medium">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($cart as $id => $item)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="py-6 px-6 flex items-center">
                                            @if($item['image'])
                                                <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="w-20 h-20 object-cover rounded-lg mr-6 shadow-sm">
                                            @endif
                                            <div>
                                                <h3 class="font-semibold text-gray-800">{{ $item['name'] }}</h3>
                                            </div>
                                        </td>
                                        <td class="py-6 px-6 text-center">
                                            <form action="{{ route('cart.update') }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="quantity[{{ $id }}]" value="{{ $item['quantity'] }}">
                                                <input type="number" name="quantity[{{ $id }}]" value="{{ $item['quantity'] }}" min="1" max="999" class="w-20 text-center rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                                            </form>
                                        </td>
                                        <td class="py-6 px-6 text-right text-gray-600">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                        <td class="py-6 px-6 text-right font-bold text-gray-800">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</td>
                                        <td class="py-6 px-6 text-center">
                                            <button onclick="if(confirm('Hapus item ini dari keranjang?')) window.location='{{ route('cart.remove', $id) }}'" class="text-red-600 hover:text-red-800 font-medium">
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tombol Update & Checkout (Besar & Menonjol) -->
                <div class="flex flex-col sm:flex-row justify-between items-center gap-6">
                    <form action="{{ route('cart.update') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-4 px-10 rounded-lg transition shadow-md text-lg">
                            Update Keranjang
                        </button>
                    </form>

                    <form action="{{ route('cart.checkout') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-12 rounded-lg transition shadow-md text-xl">
                            Checkout & Bayar Sekarang
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>