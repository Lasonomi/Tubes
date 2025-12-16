<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Tampilkan isi keranjang
     */
    public function index()
    {
        $cart = session('cart', []);
        return view('shop.cart', compact('cart'));
    }

    /**
     * Tambah produk ke keranjang
     */
    public function add(Request $request, Product $product)
    {
        // Cek stok
        if ($product->stock < 1) {
            return back()->with('error', 'Maaf, stok produk sudah habis!');
        }

        $cart = session('cart', []);

        $newQuantity = 1;
        if (isset($cart[$product->id])) {
            $newQuantity = $cart[$product->id]['quantity'] + 1;
        }

        // Cek apakah stok cukup
        if ($newQuantity > $product->stock) {
            return back()->with('error', 'Stok tidak cukup! Hanya tersisa ' . $product->stock . ' unit.');
        }

        // Tambah atau update item di cart
        $cart[$product->id] = [
            'name'     => $product->name,
            'price'    => $product->price,
            'image'    => $product->image,
            'quantity' => $newQuantity,
        ];

        session(['cart' => $cart]);

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    /**
     * Update jumlah item di keranjang
     */
    public function update(Request $request)
    {
        $request->validate([
            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:0',
        ]);

        $cart = session('cart', []);

        foreach ($request->quantity as $id => $quantity) {
            $quantity = (int) $quantity;

            if ($quantity <= 0) {
                unset($cart[$id]); // Hapus kalau quantity 0
                continue;
            }

            $product = Product::find($id);

            if (!$product) {
                continue; // Skip kalau produk tidak ada
            }

            if ($quantity > $product->stock) {
                return back()->with('error', 'Stok tidak cukup untuk "' . $product->name . '" (tersisa ' . $product->stock . ')');
            }

            $cart[$id]['quantity'] = $quantity;
        }

        session(['cart' => $cart]);

        return back()->with('success', 'Keranjang berhasil diperbarui!');
    }

    /**
     * Hapus item dari keranjang
     */
    public function remove($id)
    {
        $cart = session('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session(['cart' => $cart]);

            return back()->with('success', 'Produk dihapus dari keranjang.');
        }

        return back()->with('error', 'Produk tidak ditemukan di keranjang.');
    }

    /**
     * Proses checkout & simpan order
     */
public function checkout()
{
    $cart = session('cart', []);

    if (empty($cart)) {
        return back()->with('error', 'Keranjang belanja kosong!');
    }

    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    // Buat order langsung dengan status 'paid' atau 'processing'
    $order = Order::create([
        'user_id' => Auth::id(),
        'total'   => $total,
        'status'  => 'paid', // langsung paid, siap packing
    ]);

    // Simpan item order + update stok & sold
    foreach ($cart as $productId => $item) {
        OrderItem::create([
            'order_id'   => $order->id,
            'product_id' => $productId,
            'quantity'   => $item['quantity'],
            'price'      => $item['price'],
        ]);

        $product = Product::find($productId);
        $product->decrement('stock', $item['quantity']);
        $product->increment('sold', $item['quantity']);
    }

    // Kosongkan keranjang
    session()->forget('cart');

    // Redirect ke halaman nota/invoice sederhana
    return redirect()->route('orders.invoice', $order->id)
                     ->with('success', 'Pembelian berhasil! Pesanan kamu siap diproses.');
}
}