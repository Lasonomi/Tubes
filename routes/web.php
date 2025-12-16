<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Halaman utama (sebelum login)
Route::get('/', function () {
    return view('welcome');
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
            $newOrders       = \App\Models\Order::where('status', 'pending')->count();
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
                'pending'   => \App\Models\Order::where('status', 'pending')->count(),
                'paid'      => \App\Models\Order::where('status', 'paid')->count(),
                'shipped'   => \App\Models\Order::where('status', 'shipped')->count(),
                'completed' => \App\Models\Order::where('status', 'completed')->count(),
            ];

            return view('admin.charts', compact('topProducts', 'lowStockCategories', 'orderStats'));
        })->name('charts');

        // Halaman Notifikasi
        Route::get('/notifications', function () {
            $newOrders = \App\Models\Order::where('status', 'pending')->latest()->get();

            $lowStockProducts = \App\Models\Product::where('stock', '<', 10)
                ->orderBy('stock')
                ->get();

            $totalNotifications = $newOrders->count() + $lowStockProducts->count();

            return view('admin.notifications', compact('newOrders', 'lowStockProducts', 'totalNotifications'));
        })->name('notifications');
    });

// Include route auth Breeze
require __DIR__.'/auth.php';