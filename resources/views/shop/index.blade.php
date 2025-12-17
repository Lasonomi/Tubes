<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <!-- HEADER NAVIGATION LENGKAP (Semua di sini) -->
        <header class="bg-white border-b border-gray-200 sticky top-0 z-40 shadow-md">
            <div class="max-w-7xl mx-auto px-6 py-5">
                <div class="flex items-center justify-between">
                    <!-- Left: Nama User + Dropdown -->
                    <div x-data="{ dropdownOpen: false }" class="relative z-50">
                        <button @click="dropdownOpen = !dropdownOpen" class="flex items-center gap-4 text-gray-800 hover:text-indigo-600 font-medium text-xl transition">
                            <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="w-5 h-5 transition-transform duration-200" :class="{ 'rotate-180': dropdownOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="dropdownOpen" 
                             @click.away="dropdownOpen = false" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute left-0 mt-4 w-72 bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden z-50">
                            <a href="{{ route('profile.index') }}" class="block px-8 py-5 text-gray-800 hover:bg-indigo-50 transition text-lg font-medium">
                                Profil Saya
                            </a>
                            <a href="{{ route('orders.history') }}" class="block px-8 py-5 text-gray-800 hover:bg-indigo-50 transition text-lg">
                                Riwayat Pesanan
                            </a>
                            <a href="{{ route('wishlist.index') }}" class="block px-8 py-5 text-gray-800 hover:bg-indigo-50 transition text-lg">
                                Wishlist
                                @if(count(session('wishlist', [])) > 0)
                                    <span class="ml-3 bg-red-500 text-white text-xs font-bold rounded-full px-3 py-1">
                                        {{ count(session('wishlist', [])) }}
                                    </span>
                                @endif
                            </a>
                            <hr class="border-gray-200">
                            <form action="{{ route('logout') }}" method="POST" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-8 py-5 text-red-600 hover:bg-red-50 transition text-lg font-medium">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Center: Logo -->
                    <div class="absolute left-1/2 transform -translate-x-1/2">
                        <a href="{{ route('shop.index') }}" class="text-4xl font-bold text-indigo-600 hover:text-indigo-700 transition">
                            eTOKOBAYU
                        </a>
                    </div>

                    <!-- Right: Wishlist & Keranjang -->
                    <div class="flex items-center gap-10">
                        <!-- Wishlist -->
                        <a href="{{ route('wishlist.index') }}" class="relative flex items-center gap-4 text-gray-800 hover:text-red-600 transition text-xl font-medium">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            <span>Wishlist</span>
                            @if(count(session('wishlist', [])) > 0)
                                <span class="absolute -top-3 -right-6 bg-red-500 text-white text-sm font-bold rounded-full h-8 w-8 flex items-center justify-center shadow-lg">
                                    {{ count(session('wishlist', [])) }}
                                </span>
                            @endif
                        </a>

                        <!-- Keranjang -->
                        <a href="{{ route('cart.index') }}" class="relative flex items-center gap-4 text-gray-800 hover:text-indigo-600 transition text-xl font-medium">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span>Keranjang</span>
                            @if(count(session('cart', [])) > 0)
                                <span class="absolute -top-3 -right-6 bg-red-500 text-white text-sm font-bold rounded-full h-8 w-8 flex items-center justify-center shadow-lg">
                                    {{ count(session('cart', [])) }}
                                </span>
                            @endif
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Search Bar di Bawah Header -->
        <div class="bg-gray-100 py-6 border-b border-gray-200">
            <div class="max-w-4xl mx-auto px-6">
                <form action="{{ route('shop.index') }}" method="GET">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Cari produk, brand, atau kategori..." 
                               class="w-full px-8 py-5 pr-16 text-xl border-2 border-gray-300 rounded-full focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500 transition shadow-inner">
                        <button type="submit" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-indigo-600 hover:bg-indigo-700 text-white p-4 rounded-full transition shadow-lg">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Konten Shop -->
        <div class="max-w-7xl mx-auto px-6 py-12">
            <!-- Slider Promo -->
            <div class="mb-16">
                <h2 class="text-3xl font-bold text-gray-800 mb-8">Promo Spesial Hari Ini</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($featuredProducts as $product)
                        <a href="{{ route('products.show', $product) }}" class="block group relative">
                            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition transform hover:-translate-y-2">
                                <div class="relative">
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-80 object-cover group-hover:scale-105 transition">
                                    <div class="absolute top-4 left-4 bg-red-600 text-white font-bold text-xl px-6 py-3 rounded-full shadow-lg">
                                        -30%
                                    </div>
                                </div>
                                <div class="p-8 text-white">
                                    <h3 class="text-2xl font-bold mb-4">{{ $product->name }}</h3>
                                    <p class="text-4xl font-bold">Rp {{ number_format($product->price * 0.7, 0, ',', '.') }}</p>
                                    <p class="text-xl line-through opacity-80 mt-2">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                    <p class="mt-6 font-bold text-lg opacity-90">Lihat Detail →</p>
                                </div>
                            </div>

                            <!-- Wishlist Button -->
                            <div class="absolute top-4 right-4">
                                <form action="{{ route('wishlist.add', $product) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-white rounded-full p-4 shadow-2xl hover:shadow-3xl transition {{ in_array($product->id, session('wishlist', [])) ? 'text-red-600' : 'text-gray-400' }}">
                                        <svg class="w-8 h-8" fill="{{ in_array($product->id, session('wishlist', [])) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Kategori Cepat -->
            <div class="mb-16">
                <h2 class="text-3xl font-bold text-gray-800 mb-8">Belanja Berdasarkan Kategori</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    @foreach($categories as $cat)
                        <a href="{{ route('shop.index', ['category' => $cat->id]) }}" class="group">
                            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8 text-center hover:shadow-2xl transition transform hover:-translate-y-2">
                                <div class="w-32 h-32 bg-gray-200 rounded-full mx-auto mb-6 group-hover:scale-110 transition"></div>
                                <p class="text-xl font-bold text-gray-800">{{ $cat->name }}</p>
                                <p class="text-gray-600 mt-2">{{ $cat->products->count() }} produk</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Produk Terlaris -->
            <div class="mb-16">
                <h2 class="text-3xl font-bold text-gray-800 mb-8">Produk Terlaris</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    @foreach($topProducts as $product)
                        <a href="{{ route('products.show', $product) }}" class="block group relative">
                            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-2xl transition">
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-72 object-cover group-hover:scale-105 transition">
                                <div class="p-6">
                                    <p class="text-sm text-gray-600">{{ $product->category->name }}</p>
                                    <h3 class="text-xl font-bold text-gray-800 mt-2 line-clamp-2">{{ $product->name }}</h3>
                                    <p class="text-2xl font-bold text-indigo-600 mt-4">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                    <p class="text-sm text-gray-600 mt-2">{{ $product->sold }} terjual</p>
                                </div>
                            </div>

                            <!-- Wishlist Button -->
                            <div class="absolute top-4 right-4">
                                <form action="{{ route('wishlist.add', $product) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-white rounded-full p-4 shadow-2xl hover:shadow-3xl transition {{ in_array($product->id, session('wishlist', [])) ? 'text-red-600' : 'text-gray-400' }}">
                                        <svg class="w-8 h-8" fill="{{ in_array($product->id, session('wishlist', [])) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Semua Produk -->
            <div>
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-800">Semua Produk</h2>
                    <p class="text-xl text-gray-600">{{ $products->total() }} produk ditemukan</p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    @foreach($products as $product)
                        <a href="{{ route('products.show', $product) }}" class="block group relative">
                            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-2xl transition">
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-80 object-cover group-hover:scale-105 transition">
                                <div class="p-6">
                                    <p class="text-sm text-gray-600">{{ $product->category->name }}</p>
                                    <h3 class="text-xl font-bold text-gray-800 mt-2 line-clamp-2">{{ $product->name }}</h3>
                                    <p class="text-2xl font-bold text-indigo-600 mt-4">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                    <p class="text-sm text-gray-600 mt-2">Stok: {{ $product->stock }}</p>
                                    <p class="text-sm text-green-600 mt-3 font-medium">Lihat Detail →</p>
                                </div>
                            </div>

                            <!-- Wishlist Button -->
                            <div class="absolute top-4 right-4">
                                <form action="{{ route('wishlist.add', $product) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-white rounded-full p-4 shadow-2xl hover:shadow-3xl transition {{ in_array($product->id, session('wishlist', [])) ? 'text-red-600' : 'text-gray-400' }}">
                                        <svg class="w-8 h-8" fill="{{ in_array($product->id, session('wishlist', [])) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-12 flex justify-center">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>