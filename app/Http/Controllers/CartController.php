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
        return view('cart.index');
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
            'id'       => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        $id       = $request->id;
        $quantity = $request->quantity;

        $cart = session('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $quantity;
            session(['cart' => $cart]);

            return back()->with('success', 'Jumlah produk berhasil diupdate!');
        }

        return back()->with('error', 'Produk tidak ditemukan di keranjang.');
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
    $user = auth()->user();

    if (!$user->phone || $user->addresses()->count() == 0) {
        return back()->with('error', 'Lengkapi nomor HP & alamat pengiriman di profile sebelum checkout!');
    }

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
    public function checkoutForm()
{
    $cart = session('cart', []);

    if (empty($cart)) {
        return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong!');
    }

    $user = Auth::user();

    // Validasi wajib punya HP & alamat
    if (!$user->phone || $user->addresses()->count() == 0) {
        return redirect()->route('profile.index')->with('error', 'Lengkapi nomor HP & alamat pengiriman di profile sebelum checkout!');
    }

    $addresses = $user->addresses()->get();
    $primaryAddress = $user->primaryAddress;

    $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

    return view('checkout.index', compact('cart', 'addresses', 'primaryAddress', 'total'));
}

public function checkoutProcess(Request $request)
{
    $request->validate([
        'address_id' => 'required|exists:addresses,id,user_id,' . Auth::id(),
        'payment_method' => 'required|in:transfer,qris',
    ]);

    $cart = session('cart', []);

    if (empty($cart)) {
        return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong!');
    }

    $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

    // Simpan order
    $order = Order::create([
        'user_id' => Auth::id(),
        'total' => $total,
        'status' => 'paid', // instant paid
        'payment_method' => $request->payment_method,
        'address_id' => $request->address_id,
    ]);

    foreach ($cart as $id => $item) {
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $id,
            'quantity' => $item['quantity'],
            'price' => $item['price'],
        ]);

        // Update stok & sold
        $product = Product::find($id);
        $product->decrement('stock', $item['quantity']);
        $product->increment('sold', $item['quantity']);
    }

    // Kosongkan cart
    session()->forget('cart');

    return redirect()->route('orders.invoice', $order)->with('success', 'Pesanan berhasil dibuat!');
}
}