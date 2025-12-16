<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Models\Order;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Halaman utama (sebelum login)
// Halaman utama langsung ke login
Route::get('/', function () {
    return view('auth.login');
})->name('home');

// Redirect setelah login berdasarkan role
Route::get('/dashboard', function () {
    $user = Auth::user();
    $role = $user->role ?? 'user';

    if ($role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('shop.index');
})->middleware(['auth'])->name('dashboard');

// === Route untuk User Biasa (wajib login) ===
Route::middleware(['auth'])->group(function () {

    // Shop & Keranjang
    Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    // Halaman Nota/Invoice setelah checkout
        Route::get('/orders/invoice/{order}', function (\App\Models\Order $order) {
            // Pastikan order milik user yang login (keamanan)
            if ($order->user_id !== Auth::id()) {
                abort(403, 'Unauthorized');
            }

            return view('shop.orders.invoice', compact('order'));
        })->name('orders.invoice');

    // Riwayat Pesanan User
    Route::get('/orders/history', [OrderController::class, 'history'])->name('orders.history');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// === Route Khusus Admin ===
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard Admin
        Route::get('/dashboard', function () {
            $totalProducts   = \App\Models\Product::count();
            $newOrders       = Order::where('status', 'pending')->count();
            $notifications   = $newOrders + \App\Models\Product::where('stock', '<', 10)->count();
            $topProducts     = \App\Models\Product::orderByDesc('sold')->take(5)->get();
            $lowStock        = \App\Models\Product::where('stock', '<', 10)->orderBy('stock')->take(5)->get();

            return view('admin.dashboard', compact(
                'totalProducts',
                'newOrders',
                'notifications',
                'topProducts',
                'lowStock'
            ));
        })->name('dashboard');

        // Manage Produk (CRUD lengkap)
        Route::resource('products', ProductController::class);

        // Halaman Grafik
        Route::get('/charts', function () {
            $topProducts = \App\Models\Product::orderByDesc('sold')->take(10)->get();

            $lowStockCategories = \App\Models\Category::withCount([
                'products as low_count' => fn($query) => $query->where('stock', '<', 20)
            ])->having('low_count', '>', 0)->get();

            $orderStats = [
                'pending'   => Order::where('status', 'pending')->count(),
                'paid'      => Order::where('status', 'paid')->count(),
                'shipped'   => Order::where('status', 'shipped')->count(),
                'completed' => Order::where('status', 'completed')->count(),
            ];

            return view('admin.charts', compact('topProducts', 'lowStockCategories', 'orderStats'));
        })->name('charts');

        // Halaman Notifikasi
        Route::get('/notifications', function () {
            $newOrders = Order::where('status', 'pending')->latest()->get();

            $lowStockProducts = \App\Models\Product::where('stock', '<', 10)
                ->orderBy('stock')
                ->get();

            $totalNotifications = $newOrders->count() + $lowStockProducts->count();

            return view('admin.notifications', compact('newOrders', 'lowStockProducts', 'totalNotifications'));
        })->name('notifications');

        // Aksi Konfirmasi & Batalkan Pesanan dari Notifikasi
        Route::patch('/orders/{order}/confirm', function (Order $order) {
            $order->update(['status' => 'paid']);
            return back()->with('success', 'Pesanan #' . $order->id . ' dikonfirmasi sebagai Paid');
        })->name('orders.confirm');

        Route::patch('/orders/{order}/cancel', function (Order $order) {
            // Kembalikan stok & kurangi sold
            foreach ($order->items as $item) {
                $item->product->increment('stock', $item->quantity);
                $item->product->decrement('sold', $item->quantity);
            }

            $order->update(['status' => 'cancelled']);
            return back()->with('success', 'Pesanan #' . $order->id . ' dibatalkan & stok dikembalikan');
        })->name('orders.cancel');
    });

// Include route auth dari Breeze
require __DIR__.'/auth.php';