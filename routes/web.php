<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\Admin\ProductController;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

// Halaman utama langsung ke login
Route::get('/', function () {
    return view('auth.login');
})->name('home');

// Redirect setelah login berdasarkan role
Route::get('/dashboard', function () {
    $user = Auth::user();
    $role = $user->role ?? 'user';

    return $role === 'admin'
        ? redirect()->route('admin.dashboard')
        : redirect()->route('shop.index');
})->middleware(['auth'])->name('dashboard');

// === Route untuk User Biasa (wajib login) ===
Route::middleware(['auth'])->group(function () {
    // Shop
    Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
    Route::get('/products/{product}', [ShopController::class, 'show'])->name('products.show');

    // Keranjang Belanja
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout Terpisah
    Route::get('/checkout', [CartController::class, 'checkoutForm'])->name('checkout.form');
    Route::post('/checkout/process', [CartController::class, 'checkoutProcess'])->name('checkout.process');
    // Invoice setelah checkout
    Route::get('/orders/invoice/{order}', function (Order $order) {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        return view('shop.orders.invoice', compact('order'));
    })->name('orders.invoice');

    // Riwayat Pesanan
    Route::get('/orders/history', [OrderController::class, 'history'])->name('orders.history');

    // Profile User
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::patch('/profile/phone', [ProfileController::class, 'updatePhone'])->name('profile.phone');

    // Breeze default (ubah nama/email/password)
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/destroy', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Alamat User
    Route::get('/addresses/create', [AddressController::class, 'create'])
        ->name('addresses.create');

    Route::post('/addresses', [AddressController::class, 'store'])
        ->name('addresses.store');
    Route::patch('/addresses/{address}/primary', [AddressController::class, 'setPrimary'])->name('addresses.primary');
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');

        // Wishlist
    Route::get('/wishlist', [ShopController::class, 'wishlist'])->name('wishlist.index');
    Route::post('/wishlist/add/{product}', [ShopController::class, 'addToWishlist'])->name('wishlist.add');
    Route::delete('/wishlist/remove/{product}', [ShopController::class, 'removeFromWishlist'])->name('wishlist.remove');
});

// === Route Khusus Admin ===
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard Admin
        Route::get('/dashboard', function () {
            $totalProducts = Product::count();

            // Waktu terakhir admin buka notifikasi (default: awal hari ini)
            $lastViewed = session('admin_notifications_viewed_at', now()->startOfDay());

            // Pesanan baru setelah admin terakhir buka notifikasi
            $newOrdersToday = Order::where('created_at', '>', $lastViewed)->count();

            // Stok rendah (selalu penting)
            $lowStockCount = Product::where('stock', '<', 10)->count();

            // Total notifikasi belum dilihat
            $notifications = $newOrdersToday + $lowStockCount;

            // Penjualan bulan ini (Desember 2025)
            $monthlySales = Order::whereMonth('created_at', 12)
                                 ->whereYear('created_at', 2025)
                                 ->sum('total');

            $topProducts = Product::orderByDesc('sold')->take(5)->get();
            $lowStock = Product::where('stock', '<', 10)->orderBy('stock')->take(5)->get();

            return view('admin.dashboard', compact(
                'totalProducts',
                'newOrdersToday',
                'notifications',
                'monthlySales',
                'topProducts',
                'lowStock'
            ));
        })->name('dashboard');

        // Manage Produk
        Route::resource('products', ProductController::class);

        // Grafik
        Route::get('/charts', function () {
            $topProducts = Product::orderByDesc('sold')->take(10)->get();

            $lowStockCategories = Category::withCount([
                'products as low_count' => fn($query) => $query->where('stock', '<', 20)
            ])->having('low_count', '>', 0)->get();

            $orderStats = [
                'paid'      => Order::where('status', 'paid')->count(),
                'shipped'   => Order::where('status', 'shipped')->count(),
                'completed' => Order::where('status', 'completed')->count(),
            ];

            // Penjualan harian realtime — hanya sampai hari ini
            $startDate = Carbon::create(2025, 12, 1);
            $endDate = Carbon::today();

            $dailySales = Order::selectRaw('DATE(created_at) as date, SUM(total) as total')
                ->whereBetween('created_at', [$startDate, $endDate->endOfDay()])
                ->groupBy('date')
                ->pluck('total', 'date');

            $labels = [];
            $data = [];
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $labels[] = $date->format('d Dec');
                $data[] = $dailySales->get($date->toDateString(), 0);
            }

            return view('admin.charts', compact(
                'topProducts',
                'lowStockCategories',
                'orderStats',
                'labels',
                'data'
            ));
        })->name('charts');

        // Notifikasi Admin
        Route::get('/notifications', function () {
            $newOrders = Order::with('user')->latest()->take(10)->get();

            $lowStockProducts = Product::where('stock', '<', 10)
                ->orderBy('stock')
                ->get();

            $totalNotifications = $newOrders->count() + $lowStockProducts->count();

            // MARK AS READ: Simpan waktu admin buka notifikasi
            session(['admin_notifications_viewed_at' => now()]);

            return view('admin.notifications', compact(
                'newOrders',
                'lowStockProducts',
                'totalNotifications'
            ));
        })->name('notifications');
    });

// Include route auth Breeze
require __DIR__.'/auth.php';