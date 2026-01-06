<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-6">
            <!-- Tombol Kembali & Judul -->
            <div class="flex items-center justify-between mb-12">
                <a href="{{ url()->previous() }}" class="flex items-center gap-3 text-indigo-600 hover:text-indigo-800 font-medium text-xl transition">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali
                </a>
                <h1 class="text-4xl font-bold text-gray-800">Keranjang Belanja</h1>
                <div></div>
            </div>

            @if(count(session('cart', [])) == 0)
                <div class="text-center py-24 bg-white rounded-3xl shadow-lg">
                    <svg class="w-40 h-40 text-gray-300 mx-auto mb-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <p class="text-3xl text-gray-600 mb-6">Keranjang belanja Anda masih kosong</p>
                    <p class="text-xl text-gray-500 mb-10">Temukan produk favoritmu dan tambahkan ke keranjang!</p>
                    <a href="{{ route('shop.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-2xl py-5 px-16 rounded-2xl transition shadow-2xl">
                        Mulai Belanja →
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                    <!-- Daftar Produk (Kiri) -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-3xl shadow-lg border border-gray-200 overflow-hidden">
                            <div class="p-8 border-b border-gray-200">
                                <h2 class="text-2xl font-bold text-gray-800">
                                    Produk di Keranjang ({{ count(session('cart')) }} item)
                                </h2>
                            </div>

                            <div class="divide-y divide-gray-200">
                                @php $total = 0 @endphp
                                @foreach(session('cart') as $id => $item)
                                    @php 
                                        // Subtotal pakai harga diskon
                                        $subtotal = $item['discounted_price'] * $item['quantity']; 
                                        $total += $subtotal; 
                                    @endphp
                                    <div class="p-8 flex items-center gap-8 hover:bg-gray-50 transition">
                                        <!-- Gambar -->
                                        <div class="w-32 h-32 flex-shrink-0">
                                            @if($item['image'])
                                                <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover rounded-2xl shadow-lg">
                                            @else
                                                <div class="w-full h-full bg-gray-200 rounded-2xl flex items-center justify-center">
                                                    <span class="text-gray-500 text-lg">No Image</span>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Nama & Harga Diskon -->
                                        <div class="flex-1">
                                            <h3 class="text-2xl font-bold text-gray-800">{{ $item['name'] }}</h3>

                                            <div class="mt-4 flex items-end gap-4">
                                                <div>
                                                    <p class="text-3xl font-bold text-indigo-600">
                                                        Rp {{ number_format($item['discounted_price'], 0, ',', '.') }}
                                                    </p>
                                                    @if($item['discount_percentage'] > 0)
                                                        <p class="text-xl text-gray-500 line-through">
                                                            Rp {{ number_format($item['price'], 0, ',', '.') }}
                                                        </p>
                                                    @endif
                                                </div>

                                                @if($item['discount_percentage'] > 0)
                                                    <span class="bg-red-100 text-red-800 font-bold px-6 py-3 rounded-full text-xl">
                                                        -{{ $item['discount_percentage'] }}%
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-lg text-gray-600 mt-2">per item</p>
                                        </div>

                                        <!-- Jumlah +/- -->
                                        <div class="flex items-center gap-4">
                                            <form action="{{ route('cart.update') }}" method="POST" class="flex items-center">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $id }}">
                                                <button type="button" onclick="decrement(this)" class="w-14 h-14 bg-gray-200 hover:bg-gray-300 rounded-l-2xl flex items-center justify-center text-2xl font-bold transition">
                                                    −
                                                </button>
                                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $item['stock'] ?? 999 }}"
                                                       class="w-24 text-center text-2xl font-bold py-4 border-t border-b border-gray-300 focus:outline-none">
                                                <button type="button" onclick="increment(this)" class="w-14 h-14 bg-gray-200 hover:bg-gray-300 rounded-r-2xl flex items-center justify-center text-2xl font-bold transition">
                                                    +
                                                </button>
                                            </form>
                                        </div>

                                        <!-- Subtotal Diskon -->
                                        <div class="text-right min-w-48">
                                            <p class="text-lg text-gray-600">Subtotal</p>
                                            <p class="text-3xl font-bold text-indigo-600 mt-2">
                                                Rp {{ number_format($subtotal, 0, ',', '.') }}
                                            </p>
                                            @if($item['discount_percentage'] > 0)
                                                <p class="text-lg text-gray-500 line-through">
                                                    Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                                </p>
                                            @endif
                                        </div>

                                        <!-- Hapus -->
                                        <div class="ml-6">
                                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" onclick="return confirm('Hapus item ini dari keranjang?')" 
                                                        class="text-red-600 hover:text-red-800 transition p-3 rounded-full hover:bg-red-50">
                                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Ringkasan Belanja (Kanan, Sticky) -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-3xl shadow-lg border border-gray-200 p-8 sticky top-24">
                            <h2 class="text-3xl font-bold text-gray-800 mb-8">Ringkasan Belanja</h2>

                            <div class="border-b-4 border-indigo-600 pb-8">
                                <div class="flex justify-between text-xl mb-4">
                                    <span class="text-gray-600">Total Harga ({{ count(session('cart')) }} item)</span>
                                    <span class="font-bold text-indigo-600 text-3xl">
                                        Rp {{ number_format($total, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Tombol Checkout -->
                            <div class="mt-10">
                                <a href="{{ route('checkout.form') }}" class="block text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-2xl py-6 rounded-3xl transition shadow-2xl flex items-center justify-center gap-4">
                                    Lanjut ke Checkout →
                                </a>
                            </div>

                            <!-- Lanjut Belanja -->
                            <div class="mt-8 text-center">
                                <a href="{{ route('shop.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-lg">
                                    ← Lanjut Belanja
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Script + / - -->
    <script>
        function increment(button) {
            const input = button.parentNode.querySelector('input[type=number]');
            const max = parseInt(input.getAttribute('max')) || 999;
            if (parseInt(input.value) < max) {
                input.stepUp();
                input.form.submit();
            }
        }

        function decrement(button) {
            const input = button.parentNode.querySelector('input[type=number]');
            if (input.value > 1) {
                input.stepDown();
                input.form.submit();
            }
        }
    </script>
</x-app-layout>