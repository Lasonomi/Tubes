<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();

        $query = Product::with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhereHas('category', function($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                });
            });
        }


        if ($request->filled('sort')) {
            if ($request->sort == 'price_asc') $query->orderBy('price');
            if ($request->sort == 'price_desc') $query->orderByDesc('price');
            if ($request->sort == 'newest') $query->latest();
        }

        $products = $query->paginate(16);

        // Data tambahan untuk home
        $featuredProducts = Product::where('stock', '>', 0)->inRandomOrder()->take(3)->get(); // contoh featured
        $topProducts = Product::orderByDesc('sold')->take(8)->get();

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