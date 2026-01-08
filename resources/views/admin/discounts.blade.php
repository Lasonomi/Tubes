@extends('layouts.admin') <!-- atau layout admin kamu -->

@section('content')
    <div class="max-w-7xl mx-auto px-6 py-12">
        <h1 class="text-4xl font-bold text-gray-800 mb-12 text-center">Kelola Diskon Produk</h1>

        <!-- Form Tambah/Edit Diskon (Modal Style Inline) -->
        <div class="bg-white rounded-3xl shadow-lg border border-gray-200 p-8 mb-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-8">
                {{ isset($editDiscount) ? 'Edit Diskon' : 'Tambah Diskon Baru' }}
            </h2>

           <form action="{{ isset($editDiscount) ? route('admin.discounts') : route('admin.discounts') }}" method="POST">
                @csrf
                @if(isset($editDiscount))
                    @method('PATCH')
                    <input type="hidden" name="discount_id" value="{{ $editDiscount->id }}">
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="text-gray-700 font-medium">Pilih Produk</label>
                        <select name="product_id" class="mt-2 w-full px-6 py-4 border border-gray-300 rounded-2xl focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500 transition" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach(\App\Models\Product::all() as $product)
                                <option value="{{ $product->id }}" {{ (isset($editDiscount) && $editDiscount->product_id == $product->id) ? 'selected' : '' }}>
                                    {{ $product->name }} (Rp {{ number_format($product->price, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-gray-700 font-medium">Persentase Diskon (%)</label>
                        <input type="number" name="percentage" value="{{ isset($editDiscount) ? $editDiscount->percentage : '' }}" min="1" max="100" 
                               class="mt-2 w-full px-6 py-4 border border-gray-300 rounded-2xl focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500 transition" required>
                    </div>
                        <!-- Input Tanggal Mulai (Edit Mode) -->
                        <input type="date" name="start_date" 
                            value="{{ isset($editDiscount) && $editDiscount->start_date ? \Carbon\Carbon::parse($editDiscount->start_date)->format('Y-m-d') : '' }}" 
                            class="mt-2 w-full px-6 py-4 border border-gray-300 rounded-2xl focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500 transition">

                        <!-- Input Tanggal Berakhir (Edit Mode) -->
                        <input type="date" name="end_date" 
                            value="{{ isset($editDiscount) && $editDiscount->end_date ? \Carbon\Carbon::parse($editDiscount->end_date)->format('Y-m-d') : '' }}" 
                            class="mt-2 w-full px-6 py-4 border border-gray-300 rounded-2xl focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500 transition">
                     </div>
                        <div class="mt-10 text-right">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xl py-4 px-12 rounded-2xl transition shadow-2xl">
                            {{ isset($editDiscount) ? 'Update Diskon' : 'Simpan Diskon' }}
                        </button>
                    </div>
                </form>
        </div>

        <!-- List Diskon -->
        <div class="bg-white rounded-3xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="p-8 border-b border-gray-200">
                <h2 class="text-3xl font-bold text-gray-800">Daftar Diskon Aktif</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-6 px-8 text-left text-gray-700 font-medium">Produk</th>
                            <th class="py-6 px-8 text-center text-gray-700 font-medium">Diskon</th>
                            <th class="py-6 px-8 text-center text-gray-700 font-medium">Periode</th>
                            <th class="py-6 px-8 text-center text-gray-700 font-medium">Status</th>
                            <th class="py-6 px-8 text-center text-gray-700 font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($discounts as $discount)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-6 px-8">
                                    <p class="font-bold text-gray-800">{{ $discount->product->name }}</p>
                                    <p class="text-gray-600">Rp {{ number_format($discount->product->price, 0, ',', '.') }}</p>
                                </td>
                                <td class="py-6 px-8 text-center">
                                    <span class="text-3xl font-bold text-red-600">-{{ $discount->percentage }}%</span>
                                </td>
                                <td class="py-6 px-8 text-center text-gray-600">
                                    {{ optional(\Carbon\Carbon::parse($discount->start_date))->translatedFormat('d F Y') ?? '-' }}
                                    s/d
                                    {{ optional(\Carbon\Carbon::parse($discount->end_date))->translatedFormat('d F Y') ?? 'Selamanya' }}
                                </td>
                                <td class="py-6 px-8 text-center">
                                    @if($discount->isActive())
                                        <span class="bg-green-100 text-green-800 font-bold px-6 py-3 rounded-full">Aktif</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-800 font-bold px-6 py-3 rounded-full">Non-Aktif</span>
                                    @endif
                                </td>
                                <td class="py-6 px-8 text-center">
                                    <a href="{{ route('admin.discounts', ['edit' => $discount->id]) }}" class="text-indigo-600 hover:text-indigo-800 font-medium mr-6">
                                        Edit
                                    </a>
                                   <form action="{{ route('admin.discounts') }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="discount_id" value="{{ $discount->id }}">
                                        <button type="submit" onclick="return confirm('Hapus diskon ini?')" class="text-red-600 hover:text-red-800 font-medium">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-20 text-center text-gray-500 text-xl">
                                    Belum ada diskon. Tambah diskon baru di atas!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection