@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-semibold text-gray-800 mb-8">Edit Produk</h1>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            @include('admin.products.form', [
                'action' => route('admin.products.update', $product),
                'method' => 'PUT',
                'buttonText' => 'Update Produk',
                'categories' => $categories,
                'product' => $product
            ])
        </div>
    </div>
@endsection