<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-6">
            <h1 class="text-4xl font-bold text-gray-800 mb-12 text-center">Wishlist Favorit ❤️</h1>

            @if(count(session('wishlist', [])) == 0)
                <div class="text-center py-24 bg-white rounded-3xl shadow-lg">
                    <svg class="w-40 h-40 text-gray-300 mx-auto mb-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <p class="text-3xl text-gray-600 mb-6">Wishlist Anda masih kosong</p>
                    <p class="text-xl text-gray-500 mb-10">Simpan produk favoritmu untuk dibeli nanti!</p>
                    <a href="{{ route('shop.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-2xl py-5 px-16 rounded-2xl transition shadow-2xl">
                        Cari Produk Sekarang →
                    </a>
                </div>
            @else
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    @foreach(session('wishlist', []) as $productId)
                        @php $product = \App\Models\Product::find($productId) @endphp
                        @if($product)
                            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-2xl transition group">
                                <a href="{{ route('products.show', $product) }}">
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-80 object-cover group-hover:scale-105 transition">
                                </a>
                                <div class="p-6">
                                    <h3 class="text-xl font-bold text-gray-800 line-clamp-2">{{ $product->name }}</h3>
                                    <p class="text-2xl font-bold text-indigo-600 mt-4">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

                                    <div class="flex justify-between items-center mt-6">
                                        <form action="{{ route('cart.add', $product) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-xl transition shadow">
                                                🛒 Tambah ke Keranjang
                                            </button>
                                        </form>

                                        <form action="{{ route('wishlist.remove', $product) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 transition">
                                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>