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
     * Tambah produk ke keranjang (dengan diskon otomatis)
     */
   public function add(Request $request, Product $product)
    {
        if ($product->stock < 1) {
            return back()->with('error', 'Maaf, stok produk sudah habis!');
        }

        $cart = session('cart', []);

        $quantity = 1;
        if (isset($cart[$product->id])) {
            $quantity = $cart[$product->id]['quantity'] + 1;
        }

        if ($quantity > $product->stock) {
            return back()->with('error', 'Stok tidak cukup! Hanya tersisa ' . $product->stock . ' unit.');
        }

        // Hitung harga setelah diskon (jika ada diskon aktif)
        $finalPrice = $product->discounted_price; // accessor dari model Product

        $cart[$product->id] = [
            'name'               => $product->name,
            'price'              => $product->price, // harga asli (untuk tampilan dicoret)
            'discounted_price'   => $finalPrice,     // harga setelah diskon (untuk perhitungan)
            'discount_percentage'=> $product->discount_percentage ?? 0,
            'image'              => $product->image,
            'quantity'           => $quantity,
            'stock'              => $product->stock,
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
            // Cek stok cukup
            $product = Product::find($id);
            if ($quantity > $product->stock) {
                return back()->with('error', 'Stok tidak cukup! Hanya tersisa ' . $product->stock . ' unit.');
            }

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
     * Tampilkan form checkout
     */
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

        // Hitung total dengan diskon
        $total = collect($cart)->sum(fn($item) => $item['discounted_price'] * $item['quantity']);

        return view('checkout.index', compact('cart', 'addresses', 'total'));
    }

    /**
     * Proses checkout & simpan order (pakai harga diskon)
     */
    public function checkoutProcess(Request $request)
    {
        $request->validate([
            'address_id'      => 'required|exists:addresses,id,user_id,' . Auth::id(),
            'payment_method'  => 'required|in:transfer,qris',
        ]);

        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong!');
        }

        // Hitung total dengan harga diskon
        $total = collect($cart)->sum(fn($item) => $item['discounted_price'] * $item['quantity']);

        // Simpan order
        $order = Order::create([
            'user_id'        => Auth::id(),
            'total'          => $total,
            'status'         => 'paid',
            'payment_method' => $request->payment_method,
            'address_id'     => $request->address_id,
        ]);

        // Simpan item order + update stok & sold
        foreach ($cart as $id => $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $id,
                'quantity'   => $item['quantity'],
                'price'      => $item['discounted_price'], // simpan harga diskon ke DB
            ]);

            $product = Product::find($id);
            $product->decrement('stock', $item['quantity']);
            $product->increment('sold', $item['quantity']);
        }

        // Kosongkan cart
        session()->forget('cart');

        return redirect()->route('orders.invoice', $order)->with('success', 'Pesanan berhasil dibuat! Terima kasih telah berbelanja di WARDIÈRE.');
    }
}