<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()->with('category', 'discount');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $products = $query->latest()->paginate(12)->withQueryString();

        $categories = Category::all();

        $featuredProducts = Product::with('discount')
            ->whereHas('discount', fn($q) => $q->active())
            ->inRandomOrder()
            ->take(6)
            ->get();

        // TAMBAHKAN BARIS INI: Produk Terlaris
        $topProducts = Product::with('category', 'discount')
            ->orderByDesc('sold')
            ->take(8)
            ->get();

        return view('shop.index', compact('products', 'categories', 'featuredProducts', 'topProducts'));
    }

    public function show(Product $product)
    {
    // Load relasi category & reviews kalau ada
    $product->load('category');

    // Produk terkait (same category, random)
    $relatedProducts = Product::where('category_id', $product->category_id)
        ->where('id', '!=', $product->id)
        ->inRandomOrder()
        ->take(4)
        ->get();

    return view('shop.show', compact('product', 'relatedProducts'));
    }
    public function wishlist()
    {
    $wishlist = session('wishlist', []);

    return view('shop.wishlist', compact('wishlist'));
    }

    public function addToWishlist(Product $product)
    {
    $wishlist = session('wishlist', []);

    if (!in_array($product->id, $wishlist)) {
        $wishlist[] = $product->id;
        session(['wishlist' => $wishlist]);

        return back()->with('success', $product->name . ' ditambahkan ke Wishlist ❤️');
    }

    return back()->with('info', $product->name . ' sudah ada di Wishlist');
    }

    public function removeFromWishlist(Product $product)
    {
    $wishlist = session('wishlist', []);

    if (($key = array_search($product->id, $wishlist)) !== false) {
        unset($wishlist[$key]);
        session(['wishlist' => array_values($wishlist)]); // reindex

        return back()->with('success', $product->name . ' dihapus dari Wishlist');
    }

    return back();
    }
 }