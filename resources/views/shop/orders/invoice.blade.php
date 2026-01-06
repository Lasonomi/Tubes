<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-5xl mx-auto px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                <!-- Header Nota -->
                <div class="bg-indigo-600 text-white py-8 px-10 text-center">
                    <h1 class="text-5xl font-bold">Nota Pemesanan</h1>
                    <p class="text-2xl mt-4 opacity-90">Terima kasih atas pembelian Anda!</p>
                    <p class="text-xl mt-2 font-semibold">Pesanan Siap Diproses & Dikirim</p>
                </div>

                <div class="p-10">
                    <!-- Info Pesanan & Total -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                        <div class="space-y-4">
                            <div>
                                <p class="text-gray-600 text-sm">No. Pesanan</p>
                                <p class="text-2xl font-bold">#{{ $order->id }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">Tanggal Pemesanan</p>
                                <p class="text-xl font-semibold">{{ $order->created_at->format('d F Y, H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">Status Pembayaran</p>
                                <p class="text-xl font-bold text-green-600">Lunas (Paid)</p>
                            </div>
                        </div>

                        <div class="text-right">
                            <p class="text-gray-600 text-sm">Total Pembayaran</p>
                            <p class="text-5xl font-bold text-indigo-600">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <!-- Informasi Pemesan & Pengiriman -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-12">
                        <!-- Pemesan -->
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h2 class="text-2xl font-semibold mb-6 flex items-center">
                                <svg class="w-8 h-8 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Informasi Pemesan
                            </h2>
                            <div class="space-y-3 text-lg">
                                <p><span class="font-medium">Nama:</span> {{ $order->user->name }}</p>
                                <p><span class="font-medium">Email:</span> {{ $order->user->email }}</p>
                                <p><span class="font-medium">No. HP:</span> {{ $order->user->phone ?? '-' }}</p>
                            </div>
                        </div>

                        <!-- Pengiriman -->
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h2 class="text-2xl font-semibold mb-6 flex items-center">
                                <svg class="w-8 h-8 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-1.5 7.5H5.5L4 7m4 10h8m-4 3v-4m-6 4h12"/>
                                </svg>
                                Alamat Pengiriman
                            </h2>
                            @if($order->user->primaryAddress)
                                <div class="space-y-3 text-lg">
                                    <p class="font-bold">{{ $order->user->primaryAddress->recipient_name }}</p>
                                    <p>{{ $order->user->primaryAddress->phone }}</p>
                                    <p class="mt-3">{{ $order->user->primaryAddress->full_address }}</p>
                                    <p>{{ $order->user->primaryAddress->city }}, {{ $order->user->primaryAddress->postal_code }}</p>
                                    <span class="inline-block bg-green-100 text-green-800 text-sm font-bold px-3 py-1 rounded-full mt-4">Alamat Utama</span>
                                </div>
                            @else
                                <p class="text-gray-500">Alamat pengiriman tidak tersedia</p>
                            @endif
                        </div>
                    </div>

                    <!-- Detail Produk -->
                    <h2 class="text-2xl font-semibold mb-6">Detail Produk yang Dipesan</h2>
                    <div class="overflow-x-auto mb-12">
                        <table class="w-full border-collapse">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="text-left py-4 px-6 border-b-2 border-gray-300">Produk</th>
                                    <th class="text-center py-4 px-6 border-b-2 border-gray-300">Jumlah</th>
                                    <th class="text-right py-4 px-6 border-b-2 border-gray-300">Harga Satuan</th>
                                    <th class="text-right py-4 px-6 border-b-2 border-gray-300">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-6 px-6">
                                            <div class="flex items-center">
                                                @if($item->product->image)
                                                    <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-16 h-16 object-cover rounded mr-4">
                                                @endif
                                                <div>
                                                    <p class="font-medium">{{ $item->product->name }}</p>
                                                    <p class="text-sm text-gray-500">{{ $item->product->category->name }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-6 px-6 text-center font-medium">{{ $item->quantity }}</td>
                                        <td class="py-6 px-6 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td class="py-6 px-6 text-right font-bold text-indigo-600">
                                            Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="py-6 px-6 text-right text-2xl font-bold">Total</td>
                                    <td class="py-6 px-6 text-right text-3xl font-bold text-indigo-600">
                                        Rp {{ number_format($order->total, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Footer -->
                    <div class="text-center pt-8 border-t">
                        <p class="text-xl text-gray-700 mb-6">Pesanan Anda akan segera kami proses dan kirim. Mohon ditunggu ya!</p>
                        <div class="flex justify-center gap-6">
                            <button onclick="window.print()" class="bg-gray-800 hover:bg-gray-900 text-white font-medium py-3 px-8 rounded-lg transition">
                                Cetak Nota
                            </button>
                            <a href="{{ route('shop.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-8 rounded-lg transition">
                                Kembali Belanja
                            </a>
                        </div>
                        <p class="text-sm text-gray-500 mt-8">Terima kasih telah berbelanja di <span class="font-bold">eTOKOBAYU</span> 🛒</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script untuk print -->
    <script>
        // Optional: hide button saat print
        window.onbeforeprint = function() {
            document.querySelectorAll('button[onclick="window.print()"]').forEach(btn => btn.style.display = 'none');
        }
        window.onafterprint = function() {
            document.querySelectorAll('button[onclick="window.print()"]').forEach(btn => btn.style.display = 'block');
        }
    </script>
</x-app-layout>