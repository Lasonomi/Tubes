<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-6">
            <!-- Breadcrumb -->
            <nav class="text-sm text-gray-600 mb-8 flex items-center gap-2">
                <a href="{{ route('shop.index') }}" class="hover:text-indigo-600 transition">Home</a>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <a href="{{ route('shop.index', ['category' => $product->category_id]) }}" class="hover:text-indigo-600 transition">
                    {{ $product->category->name }}
                </a>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-gray-800 font-medium">{{ $product->name }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Gambar Produk -->
                <div class="order-2 lg:order-1">
                    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                                 class="w-full h-full max-h-screen object-contain">
                        @else
                            <div class="w-full h-96 bg-gray-200 rounded-3xl flex items-center justify-center">
                                <span class="text-3xl text-gray-500 font-medium">No Image Available</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Info Produk -->
                <div class="order-1 lg:order-2 space-y-10">
                    <div>
                        <h1 class="text-5xl font-bold text-gray-800 leading-tight">{{ $product->name }}</h1>
                        <p class="text-2xl text-gray-600 mt-4">{{ $product->category->name }}</p>
                        <p class="text-lg text-gray-500 mt-2">{{ $product->sold }} terjual</p>
                    </div>

                    <!-- Harga & Stok -->
                    <div class="flex items-end gap-8">
                        <div>
                           @if($product->discount_percentage > 0)
                                <p class="text-5xl font-bold text-indigo-600">
                                    Rp {{ number_format($product->discounted_price, 0, ',', '.') }}
                                </p>
                                <p class="text-2xl text-gray-500 line-through mt-2">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </p>
                                <span class="bg-red-100 text-red-800 font-bold px-6 py-3 rounded-full mt-4 inline-block text-xl">
                                    -{{ $product->discount_percentage }}%
                                </span>
                            @else
                                <p class="text-5xl font-bold text-indigo-600">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </p>
                            @endif
                        </div>
                        <div>
                            @if($product->stock < 10)
                                <span class="bg-red-100 text-red-800 font-bold text-xl px-8 py-4 rounded-full shadow">
                                    Stok tersisa: {{ $product->stock }}
                                </span>
                            @else
                                <span class="text-green-600 font-bold text-xl">
                                    ✓ Stok tersedia: {{ $product->stock }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="bg-gray-50 rounded-2xl p-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">Deskripsi Produk</h3>
                        <p class="text-lg text-gray-700 leading-relaxed whitespace-pre-line">
                            {{ $product->description ?? 'Deskripsi produk belum tersedia. Hubungi admin untuk informasi lebih lanjut.' }}
                        </p>
                    </div>

                    <!-- Aksi: Jumlah + Keranjang + Wishlist -->
                    <div class="space-y-6">
                        <form action="{{ route('cart.add', $product) }}" method="POST" class="flex items-end gap-6">
                            @csrf
                            <div class="flex items-center">
                                <label class="text-xl font-bold text-gray-800 mr-6">Jumlah:</label>
                                <div class="flex items-center border-4 border-indigo-200 rounded-2xl overflow-hidden">
                                    <button type="button" onclick="this.parentNode.querySelector('input[type=number]').stepDown()" 
                                            class="w-16 h-16 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 font-bold text-3xl transition">
                                        −
                                    </button>
                                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" 
                                           class="w-32 text-center text-3xl font-bold py-4 focus:outline-none">
                                    <button type="button" onclick="this.parentNode.querySelector('input[type=number]').stepUp()" 
                                            class="w-16 h-16 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 font-bold text-3xl transition">
                                        +
                                    </button>
                                </div>
                            </div>

                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-2xl py-6 px-16 rounded-2xl transition shadow-2xl flex items-center gap-4">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Tambah ke Keranjang
                            </button>
                        </form>

                        <!-- Wishlist Button -->
                        <div class="flex items-center gap-6">
                            <form action="{{ route('wishlist.add', $product) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="flex items-center gap-4 bg-red-100 hover:bg-red-200 text-red-600 font-bold text-xl py-5 px-12 rounded-2xl transition shadow-lg">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                    Tambah ke Wishlist
                                </button>
                            </form>

                            <button class="text-gray-600 hover:text-gray-800 transition">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m9 9v-3m-3 3h6"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Garansi & Pengiriman -->
                    <div class="bg-green-50 rounded-2xl p-8 border-2 border-green-200">
                        <div class="space-y-6">
                            <div class="flex items-center gap-6">
                                <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="text-xl font-bold text-gray-800">Pengiriman Cepat & Aman</p>
                                    <p class="text-gray-600">Estimasi 1-3 hari kerja</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-6">
                                <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="text-xl font-bold text-gray-800">Garansi Uang Kembali</p>
                                    <p class="text-gray-600">100% uang kembali dalam 7 hari</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Produk Terkait -->
            @if($relatedProducts->count() > 0)
                <div class="mt-24">
                    <h2 class="text-4xl font-bold text-gray-800 mb-12 text-center">Produk Terkait</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-10">
                        @foreach($relatedProducts as $related)
                            <a href="{{ route('products.show', $related) }}" class="bg-white rounded-3xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-2xl transition group">
                                <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->name }}" class="w-full h-80 object-cover group-hover:scale-105 transition">
                                <div class="p-8">
                                    <p class="text-sm text-gray-600">{{ $related->category->name }}</p>
                                    <h3 class="text-xl font-bold text-gray-800 mt-3 line-clamp-2">{{ $related->name }}</h3>
                                    <p class="text-3xl font-bold text-indigo-600 mt-6">Rp {{ number_format($related->price, 0, ',', '.') }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>