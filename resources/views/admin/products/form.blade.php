<form action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($method ?? null)
        @method($method)
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-gray-700 font-medium mb-2">Nama Produk</label>
            <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" class="w-full rounded-lg border-gray-300" required>
            @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-2">Kategori</label>
            <select name="category_id" class="w-full rounded-lg border-gray-300" required>
                <option value="">Pilih Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-2">Harga</label>
            <input type="number" name="price" value="{{ old('price', $product->price ?? '') }}" class="w-full rounded-lg border-gray-300" required min="0">
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-2">Stok</label>
            <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" class="w-full rounded-lg border-gray-300" required min="0">
        </div>

        <div class="md:col-span-2">
            <label class="block text-gray-700 font-medium mb-2">Gambar Produk</label>
            <input type="file" name="image" class="w-full">
            @if(isset($product) && $product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="Current" class="mt-4 w-32 h-32 object-cover rounded">
            @endif
        </div>

        <div class="md:col-span-2">
            <label class="block text-gray-700 font-medium mb-2">Deskripsi</label>
            <textarea name="description" rows="4" class="w-full rounded-lg border-gray-300">{{ old('description', $product->description ?? '') }}</textarea>
        </div>
    </div>

    <div class="mt-8 flex justify-end gap-4">
        <a href="{{ route('admin.products.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Batal</a>
        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">{{ $buttonText }}</button>
    </div>
</form>