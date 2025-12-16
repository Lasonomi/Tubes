@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-semibold text-gray-800 mb-8">Tambah Produk Baru</h1>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            @include('admin.products.form', [
                'action' => route('admin.products.store'),
                'buttonText' => 'Simpan Produk',
                'categories' => $categories,
                'product' => new \App\Models\Product()
            ])
        </div>
    </div>
@endsection