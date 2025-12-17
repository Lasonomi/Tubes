<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-12">
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
                </div>
            </div>
        </header>
        <div class="max-w-7xl mx-auto px-6">
            <h1 class="text-4xl font-bold text-gray-800 mb-12 text-center">Akun Saya</h1>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-10">
                <!-- Sidebar Menu -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-3xl shadow-lg border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold py-6 px-8 text-xl">
                            Menu Akun
                        </div>
                        <ul class="divide-y divide-gray-200">
                            <li class="bg-indigo-50">
                                <a href="{{ route('profile.index') }}" class="block py-5 px-8 text-indigo-600 font-semibold">
                                    Profil Saya
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('orders.history') }}" class="block py-5 px-8 text-gray-700 hover:bg-gray-50 transition">
                                    Riwayat Pesanan
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('wishlist.index') }}" class="block py-5 px-8 text-gray-700 hover:bg-gray-50 transition">
                                    Wishlist
                                </a>
                            </li>
                            <li>
                                <a href="#" class="block py-5 px-8 text-gray-700 hover:bg-gray-50 transition">
                                    Voucher
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Konten Utama -->
                <div class="lg:col-span-3 space-y-12">
                    <!-- Informasi Akun -->
                    <div class="bg-white rounded-3xl shadow-lg border border-gray-200 p-8">
                        <h2 class="text-3xl font-bold text-gray-800 mb-8">Informasi Akun</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <p class="text-gray-600 font-medium">Nama Lengkap</p>
                                <p class="text-2xl font-semibold text-gray-800 mt-2">{{ Auth::user()->name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 font-medium">Email</p>
                                <p class="text-2xl font-semibold text-gray-800 mt-2">{{ Auth::user()->email }}</p>
                            </div>
                            <div>
                                <form action="{{ route('profile.phone') }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <p class="text-gray-600 mb-2">Nomor HP</p>
                                    <input type="text" name="phone" value="{{ auth()->user()->phone }}" class="w-full rounded-lg border-gray-300" placeholder="08xxxxxxxxxx" required>
                                    <button type="submit" class="mt-3 bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-6 rounded-lg">Simpan</button>
                                </form>
                            </div>
                        </div>

                        <!-- Tombol Aksi (pakai route Breeze yang sudah ada) -->
                        <div class="mt-10 flex flex-wrap gap-6">
                            <a href="{{ route('profile.edit') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-12 rounded-2xl transition shadow-lg">
                                Update Informasi Profile
                            </a>

                            <!-- Hapus Akun (pakai route destroy Breeze) -->
                            <form action="{{ route('profile.destroy') }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin ingin menghapus akun secara permanen?')" 
                                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-4 px-12 rounded-2xl transition shadow-lg">
                                    Hapus Akun
                                </button>
                            </form>
                        </div>

                        <p class="text-gray-600 mt-6">
                            Password dapat diubah di halaman Update Profile.
                        </p>
                    </div>

                    <!-- Daftar Alamat -->
                    <div class="bg-white rounded-lg shadow-sm border p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-semibold">Alamat Tersimpan ({{ $addresses->count() }})</h2>
                            <button onclick="document.getElementById('addAddressModal').classList.remove('hidden')" class="bg-indigo-600 hover:bg-indigo-700 text-white py-3 px-6 rounded-lg">
                                Tambah Alamat Baru
                            </button>
                        </div>

                        @if($addresses->count() == 0)
                            <p class="text-center text-gray-500 py-10">Anda tidak memiliki alamat yang disimpan</p>
                        @else
                            <div class="space-y-6">
                                @foreach($addresses as $address)
                                    <div class="border rounded-lg p-6 {{ $address->is_primary ? 'border-indigo-600' : '' }}">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="font-bold">{{ $address->recipient_name }} {{ $address->is_primary ? '(Utama)' : '' }}</p>
                                                <p>{{ $address->phone }}</p>
                                                <p class="mt-2">{{ $address->full_address }}, {{ $address->city }}, {{ $address->postal_code }}</p>
                                            </div>
                                            <div class="flex gap-4">
                                                @if(!$address->is_primary)
                                                    <form action="{{ route('addresses.primary', $address) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="text-indigo-600 hover:underline">Jadikan Utama</button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('addresses.destroy', $address) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Hapus alamat ini?')" class="text-red-600 hover:underline">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Modal Tambah Alamat Baru -->   
                    <div id="addAddressModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                        <div class="bg-white rounded-lg p-8 w-full max-w-lg">
                            <h3 class="text-2xl font-bold mb-6">Tambah Alamat Baru</h3>
                            <form action="{{ route('addresses.store') }}" method="POST">
                                @csrf
                                <div class="space-y-4">
                                    <input type="text" name="recipient_name" placeholder="Nama Penerima" class="w-full rounded-lg border-gray-300" required>
                                    <input type="text" name="phone" placeholder="Nomor HP" class="w-full rounded-lg border-gray-300" required>
                                    <textarea name="full_address" placeholder="Alamat Lengkap" rows="3" class="w-full rounded-lg border-gray-300" required></textarea>
                                    <input type="text" name="city" placeholder="Kota" class="w-full rounded-lg border-gray-300" required>
                                    <input type="text" name="postal_code" placeholder="Kode Pos" class="w-full rounded-lg border-gray-300" required>
                                </div>
                                <div class="mt-6 flex justify-end gap-4">
                                    <button type="button" onclick="document.getElementById('addAddressModal').classList.add('hidden')" class="py-2 px-6 border rounded-lg">Batal</button>
                                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-6 rounded-lg">Simpan Alamat</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- PUSAT BANTUAN & KONTAK (di paling bawah) -->
                    <div class="bg-white rounded-3xl shadow-lg border border-gray-200 p-8">
                        <h2 class="text-3xl font-bold text-gray-800 mb-10 text-center">Pusat Bantuan & Kontak</h2>

                        <!-- FAQ -->
                        <div class="mb-12">
                            <h3 class="text-2xl font-bold text-gray-800 mb-6">Pertanyaan yang Sering Diajukan (FAQ)</h3>
                            <div class="space-y-4">
                                <details class="bg-gray-50 rounded-2xl p-6 cursor-pointer hover:bg-gray-100 transition group">
                                    <summary class="text-xl font-semibold text-gray-800 flex items-center justify-between">
                                        Berapa lama pengiriman?
                                        <svg class="w-6 h-6 text-gray-600 group-open:rotate-180 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </summary>
                                    <p class="mt-4 text-gray-700">Pengiriman 1-3 hari kerja untuk Jabodetabek, 3-7 hari untuk luar Jabodetabek. Gratis ongkir untuk pembelian di atas Rp 500.000.</p>
                                </details>

                                <details class="bg-gray-50 rounded-2xl p-6 cursor-pointer hover:bg-gray-100 transition group">
                                    <summary class="text-xl font-semibold text-gray-800 flex items-center justify-between">
                                        Apakah bisa COD?
                                        <svg class="w-6 h-6 text-gray-600 group-open:rotate-180 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </summary>
                                    <p class="mt-4 text-gray-700">Saat ini belum support COD. Pembayaran via Transfer Bank atau QRIS.</p>
                                </details>

                                <details class="bg-gray-50 rounded-2xl p-6 cursor-pointer hover:bg-gray-100 transition group">
                                    <summary class="text-xl font-semibold text-gray-800 flex items-center justify-between">
                                        Bagaimana cara retur barang?
                                        <svg class="w-6 h-6 text-gray-600 group-open:rotate-180 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </summary>
                                    <p class="mt-4 text-gray-700">Retur bisa dilakukan dalam 7 hari setelah barang diterima. Hubungi CS untuk proses.</p>
                                </details>

                                <details class="bg-gray-50 rounded-2xl p-6 cursor-pointer hover:bg-gray-100 transition group">
                                    <summary class="text-xl font-semibold text-gray-800 flex items-center justify-between">
                                        Apakah ada garansi?
                                        <svg class="w-6 h-6 text-gray-600 group-open:rotate-180 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </summary>
                                    <p class="mt-4 text-gray-700">Ya, garansi uang kembali 100% dalam 7 hari jika barang cacat atau tidak sesuai.</p>
                                </details>
                            </div>
                        </div>

                        <!-- Customer Service -->
                        <div class="mb-12">
                            <h3 class="text-2xl font-bold text-gray-800 mb-6">Hubungi Customer Service</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                                <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition text-center">
                                    <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                    <p class="text-xl font-bold">WhatsApp</p>
                                    <p class="text-2xl mt-2">0812-3456-7890</p>
                                    <p class="mt-2 opacity-90">Senin - Minggu: 08.00 - 22.00</p>
                                </div>

                                <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition text-center">
                                    <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-xl font-bold">Email</p>
                                    <p class="text-2xl mt-2">cs@wardiere.com</p>
                                    <p class="mt-2 opacity-90">Balas dalam 1x24 jam</p>
                                </div>

                                <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition text-center">
                                    <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-xl font-bold">Jam Operasional</p>
                                    <p class="text-2xl mt-2">08.00 - 22.00 WIB</p>
                                    <p class="mt-2 opacity-90">Setiap hari termasuk akhir pekan</p>
                                </div>
                            </div>
                        </div>

                        <!-- Form Kontak -->
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800 mb-6">Kirim Pesan ke Kami</h3>
                            <form action="#" method="POST" class="space-y-6">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <input type="text" name="name" value="{{ Auth::user()->name }}" class="w-full px-6 py-4 text-lg border border-gray-300 rounded-2xl focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500 transition" required>
                                    <input type="email" name="email" value="{{ Auth::user()->email }}" class="w-full px-6 py-4 text-lg border border-gray-300 rounded-2xl focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500 transition" required>
                                </div>
                                <textarea name="message" rows="8" placeholder="Tulis pesan atau keluhan Anda di sini..." class="w-full px-6 py-4 text-lg border border-gray-300 rounded-2xl focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500 transition resize-none" required></textarea>

                                <div class="text-center">
                                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xl py-5 px-16 rounded-2xl transition shadow-2xl">
                                        Kirim Pesan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>