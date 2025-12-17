<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-5xl mx-auto px-6">
            <h1 class="text-4xl font-bold text-gray-800 mb-12 text-center">Checkout Pesanan</h1>

            <form action="{{ route('checkout.process') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                    <!-- Alamat & Pembayaran -->
                    <div class="lg:col-span-2 space-y-10">
                        <!-- Pilih Alamat -->
                        <div class="bg-white rounded-3xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-800 mb-6">Alamat Pengiriman</h2>
                            <div class="space-y-4">
                                @foreach($addresses as $address)
                                    <label class="block border-2 rounded-2xl p-6 cursor-pointer transition hover:shadow-lg {{ $address->is_primary ? 'border-indigo-600 bg-indigo-50' : 'border-gray-200' }}">
                                        <input type="radio" name="address_id" value="{{ $address->id }}" 
                                               class="float-right" {{ $address->is_primary ? 'checked' : '' }} required>
                                        <p class="font-bold text-xl">{{ $address->recipient_name }} {{ $address->is_primary ? '(Alamat Utama)' : '' }}</p>
                                        <p class="text-gray-700 mt-2">{{ $address->phone }}</p>
                                        <p class="text-gray-600 mt-3">{{ $address->full_address }}</p>
                                        <p class="text-gray-600">{{ $address->city }}, {{ $address->postal_code }}</p>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Metode Pembayaran -->
                        <div class="bg-white rounded-3xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-800 mb-6">Metode Pembayaran</h2>
                            <div class="space-y-4">
                                <label class="block border-2 rounded-2xl p-6 cursor-pointer hover:shadow-lg transition border-gray-200">
                                    <input type="radio" name="payment_method" value="transfer" class="float-right" required>
                                    <p class="font-bold text-xl">Transfer Bank</p>
                                    <p class="text-gray-600 mt-3">BCA a.n WARDIÈRE - 1234567890</p>
                                    <p class="text-sm text-gray-500 mt-2">Konfirmasi pembayaran manual setelah transfer</p>
                                </label>

                                <label class="block border-2 rounded-2xl p-6 cursor-pointer hover:shadow-lg transition border-gray-200">
                                    <input type="radio" name="payment_method" value="qris" class="float-right" checked required>
                                    <p class="font-bold text-xl">QRIS (Semua E-Wallet & Mobile Banking)</p>
                                    <div class="mt-6 text-center">
                                        <img src="{{ asset('images/qris-example.jpg') }}" alt="QRIS Wardière" class="w-64 h-64 mx-auto rounded-xl shadow-lg">
                                        <p class="text-gray-600 mt-4">Scan QRIS di atas dengan aplikasi pembayaran</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Ringkasan Order -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-3xl shadow-lg border border-gray-200 p-8 sticky top-24">
                            <h2 class="text-2xl font-bold text-gray-800 mb-6">Ringkasan Pesanan</h2>
                            <div class="space-y-4 mb-8">
                                @foreach($cart as $item)
                                    <div class="flex justify-between">
                                        <p class="text-gray-700">{{ $item['quantity'] }}x {{ $item['name'] }}</p>
                                        <p class="font-medium">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</p>
                                    </div>
                                @endforeach
                            </div>

                            <div class="border-t-4 border-indigo-600 pt-6">
                                <div class="flex justify-between mb-8">
                                    <p class="text-2xl font-bold text-gray-800">Total Pembayaran</p>
                                    <p class="text-4xl font-bold text-indigo-600">Rp {{ number_format($total, 0, ',', '.') }}</p>
                                </div>

                                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-2xl py-6 rounded-2xl transition shadow-2xl">
                                    Bayar Sekarang
                                </button>
                            </div>

                            <p class="text-sm text-gray-500 text-center mt-6">
                                Dengan menekan bayar, Anda menyetujui syarat & ketentuan Wardière
                            </p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>