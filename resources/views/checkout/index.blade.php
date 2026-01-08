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
                <h1 class="text-4xl font-bold text-gray-800">Checkout Pesanan</h1>
                <div></div>
            </div>

            @if(!Auth::check())
                <div class="text-center py-20 bg-white rounded-3xl shadow-lg">
                    <p class="text-3xl text-gray-800 mb-8">Silakan login untuk melanjutkan pembayaran</p>
                    <div class="flex justify-center gap-8">
                        <a href="{{ route('login') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-2xl py-5 px-16 rounded-2xl transition shadow-2xl">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold text-2xl py-5 px-16 rounded-2xl transition shadow-2xl">
                            Daftar Baru
                        </a>
                    </div>
                    <p class="text-gray-600 mt-8">Keranjang Anda akan tetap tersimpan setelah login</p>
                </div>
            @else
                <form action="{{ route('checkout.process') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                        <!-- Kiri: Alamat & Pembayaran -->
                        <div class="lg:col-span-2 space-y-10">
                            <!-- Alamat Pengiriman -->
                            <div class="bg-white rounded-3xl shadow-lg border border-gray-200 p-8">
                                <h2 class="text-3xl font-bold text-gray-800 mb-8">Alamat Pengiriman</h2>
                                <div class="space-y-6">
                                    @foreach($addresses as $address)
                                        <label class="block border-4 rounded-3xl p-8 cursor-pointer transition hover:shadow-xl {{ $address->is_primary ? 'border-indigo-600 bg-indigo-50' : 'border-gray-200' }}">
                                            <input type="radio" name="address_id" value="{{ $address->id }}" 
                                                   class="float-right mt-2 w-6 h-6 text-indigo-600 focus:ring-indigo-500" 
                                                   {{ $address->is_primary ? 'checked' : '' }} required>
                                            <div>
                                                <p class="font-bold text-2xl text-gray-800">
                                                    {{ $address->recipient_name }} 
                                                    @if($address->is_primary)
                                                        <span class="text-indigo-600">(Alamat Utama)</span>
                                                    @endif
                                                </p>
                                                <p class="text-xl text-gray-700 mt-3">{{ $address->phone }}</p>
                                                <p class="text-lg text-gray-600 mt-4">
                                                    {{ $address->full_address }}, {{ $address->city }}, {{ $address->postal_code }}
                                                </p>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Metode Pembayaran -->
                            <div class="bg-white rounded-3xl shadow-lg border border-gray-200 p-8">
                                <h2 class="text-3xl font-bold text-gray-800 mb-8">Metode Pembayaran</h2>
                                <div class="space-y-6">
                                    <label class="block border-4 rounded-3xl p-8 cursor-pointer transition hover:shadow-xl border-gray-200">
                                        <input type="radio" name="payment_method" value="transfer" class="float-right mt-2 w-6 h-6 text-indigo-600 focus:ring-indigo-500" required>
                                        <div>
                                            <p class="font-bold text-2xl text-gray-800">Transfer Bank</p>
                                            <p class="text-xl text-gray-700 mt-4">BCA a.n eTOKOBAYU - 1234567890</p>
                                            <p class="text-lg text-gray-600 mt-3">Konfirmasi pembayaran manual setelah transfer</p>
                                        </div>
                                    </label>

                                    <label class="block border-4 rounded-3xl p-8 cursor-pointer transition hover:shadow-xl border-indigo-600 bg-indigo-50">
                                        <input type="radio" name="payment_method" value="qris" class="float-right mt-2 w-6 h-6 text-indigo-600 focus:ring-indigo-500" checked required>
                                        <div>
                                            <p class="font-bold text-2xl text-gray-800">QRIS (Semua E-Wallet & Mobile Banking)</p>
                                            <div class="mt-6 text-center">
                                                <img src="{{ asset('images/qris.jpg') }}" alt="QRIS eTOKOBAYU" class="w-64 h-64 mx-auto rounded-2xl shadow-lg">
                                                <p class="text-lg text-gray-600 mt-4">Scan QRIS di atas dengan aplikasi pembayaran Anda</p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Kanan: Ringkasan Pesanan (Sticky) -->
                        <div class="lg:col-span-1">
                            <div class="bg-white rounded-3xl shadow-lg border border-gray-200 p-8 sticky top-24">
                                <h2 class="text-3xl font-bold text-gray-800 mb-8">Ringkasan Pesanan</h2>

                                <div class="space-y-6 border-b-4 border-indigo-600 pb-8">
                                    @foreach($cart as $item)
                                        <div class="flex justify-between text-xl">
                                            <span class="text-gray-700">
                                                {{ $item['quantity'] }}x {{ $item['name'] }}
                                                @if($item['discount_percentage'] > 0)
                                                    <span class="text-red-600 font-bold ml-2">(-{{ $item['discount_percentage'] }}%)</span>
                                                @endif
                                            </span>
                                            <span class="font-bold text-indigo-600">
                                                Rp {{ number_format($item['discounted_price'] * $item['quantity'], 0, ',', '.') }}
                                                @if($item['discount_percentage'] > 0)
                                                    <span class="text-sm text-gray-500 line-through block">
                                                        Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                                    </span>
                                                @endif
                                            </span>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-8">
                                    <div class="flex justify-between mb-6">
                                        <p class="text-2xl font-bold text-gray-800">Total Pembayaran</p>
                                        <p class="text-4xl font-bold text-indigo-600">
                                            Rp {{ number_format($total, 0, ',', '.') }}
                                        </p>
                                    </div>

                                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-2xl py-6 rounded-3xl transition shadow-2xl">
                                        Bayar Sekarang
                                    </button>

                                    <p class="text-center text-gray-600 mt-6 text-sm">
                                        Dengan menekan bayar, Anda menyetujui syarat & ketentuan eTOKOBAYU.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>