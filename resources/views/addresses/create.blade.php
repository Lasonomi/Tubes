<x-app-layout>
    <div class="max-w-4xl mx-auto py-12 px-6">
        <h1 class="text-3xl font-bold mb-8">Tambah Alamat Baru</h1>

        <form action="{{ route('addresses.store') }}" method="POST" class="space-y-6">
            @csrf

            <input name="recipient_name" placeholder="Nama Penerima" class="w-full p-4 border rounded-xl" required>
            <input name="phone" placeholder="No HP" class="w-full p-4 border rounded-xl" required>
            <textarea name="full_address" placeholder="Alamat Lengkap" class="w-full p-4 border rounded-xl" required></textarea>
            <input name="city" placeholder="Kota" class="w-full p-4 border rounded-xl" required>
            <input name="postal_code" placeholder="Kode Pos" class="w-full p-4 border rounded-xl" required>

            <button class="bg-indigo-600 text-white px-8 py-4 rounded-xl font-bold">
                Simpan Alamat
            </button>
        </form>
    </div>
</x-app-layout>
