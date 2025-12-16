<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header: Judul + Ikon Keranjang -->
            <div class="flex justify-between items-center mb-10">
                <h1 class="text-4xl font-bold text-gray-800">Toko BAYU</h1>
                
                <a href="{{ route('cart.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-6 rounded-lg flex items-center shadow-md transition">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Keranjang
                    <span class="ml-2 bg-white text-indigo-600 rounded-full px-3 py-1 text-sm font-bold">
                        {{ count(session('cart', [])) }}
                    </span>
                </a>
                <a href="{{ route('orders.history') }}" class="text-indigo-600 hover:text-indigo-800 font-medium ml-8">
                 Riwayat Pembelian
                </a>
            </div>

            <!-- Notif Success / Error -->
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

            <!-- Search & Filter -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-10">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk..." class="rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">

                    <select name="category" class="rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg px-8 transition">
                        Cari Produk
                    </button>
                </form>
            </div>

            <!-- Daftar Produk -->
            @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    @foreach($products as $product)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-xl hover:border-indigo-200 transition duration-300">
                            <!-- Gambar -->
                            <div class="h-64 bg-gray-100 relative">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <span class="text-lg font-medium">No Image</span>
                                    </div>
                                @endif

                                <!-- Badge Stok Rendah -->
                                @if($product->stock < 10)
                                    <span class="absolute top-4 left-4 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                                        Stok Terbatas!
                                    </span>
                                @endif
                            </div>

                            <!-- Detail -->
                            <div class="p-6">
                                <h3 class="text-xl font-semibold text-gray-800 mb-2 line-clamp-2">{{ $product->name }}</h3>
                                <p class="text-sm text-gray-600 mb-3">{{ $product->category->name }}</p>
                                <p class="text-2xl font-bold text-indigo-600 mb-2">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                <p class="text-sm text-gray-500 mb-5">Stok tersisa: <span class="font-bold {{ $product->stock < 10 ? 'text-red-600' : '' }}">{{ $product->stock }}</span></p>

                                <!-- Tombol Tambah Keranjang -->
                                <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-auto">
                                    @csrf
                                    <button type="submit" 
                                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 rounded-lg transition transform hover:scale-105"
                                        {{ $product->stock < 1 ? 'disabled' : '' }}>
                                        {{ $product->stock < 1 ? 'Stok Habis' : 'Tambah ke Keranjang' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-12 flex justify-center">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-20 bg-white rounded-lg shadow-sm border border-gray-200">
                    <p class="text-2xl text-gray-500 mb-4">Belum ada produk yang tersedia</p>
                    <p class="text-gray-600">Admin sedang menambahkan produk baru. Silakan cek lagi nanti!</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>