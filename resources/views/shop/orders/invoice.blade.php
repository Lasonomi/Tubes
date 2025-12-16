<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-10">
                <div class="text-center mb-10">
                    <h1 class="text-4xl font-bold text-gray-800">Nota Pemesanan</h1>
                    <p class="text-xl text-gray-600 mt-4">Terima kasih atas pembelian Anda!</p>
                    <p class="text-lg text-green-600 font-bold mt-2">Pesanan Siap Diproses & Dikirim</p>
                </div>

                <div class="border-t border-b border-gray-300 py-6 mb-8">
                    <div class="grid grid-cols-2 gap-4 text-lg">
                        <div>
                            <p class="text-gray-600">No. Pesanan</p>
                            <p class="font-bold">#{{ $order->id }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-gray-600">Tanggal</p>
                            <p class="font-bold">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Status</p>
                            <p class="font-bold text-green-600">Paid - Siap Dikirim</p>
                        </div>
                        <div class="text-right">
                            <p class="text-gray-600">Total Pembayaran</p>
                            <p class="text-3xl font-bold text-indigo-600">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <h2 class="text-2xl font-semibold mb-6">Detail Produk</h2>
                <table class="w-full mb-10">
                    <thead class="bg-gray-100 border-b-2 border-gray-300">
                        <tr>
                            <th class="text-left py-4 px-6">Produk</th>
                            <th class="text-center py-4 px-6">Jumlah</th>
                            <th class="text-right py-4 px-6">Harga</th>
                            <th class="text-right py-4 px-6">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr class="border-b border-gray-200">
                                <td class="py-4 px-6">{{ $item->product->name }}</td>
                                <td class="py-4 px-6 text-center">{{ $item->quantity }}</td>
                                <td class="py-4 px-6 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="py-4 px-6 text-right font-bold">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="text-center">
                    <p class="text-lg text-gray-600 mb-6">Pesanan akan segera diproses dan dikirim. Terima kasih telah berbelanja di Wardière!</p>
                    <a href="{{ route('shop.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-8 rounded-lg transition inline-block">
                        Kembali Belanja
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>