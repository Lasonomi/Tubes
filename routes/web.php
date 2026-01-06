<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\DiscountController;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

// === Halaman Utama: Langsung ke Shop (Guest Friendly!) ===
Route::get('/', function () {
    return redirect()->route('shop.index');
})->name('home');

// === Route Guest (Bisa Diakses Tanpa Login) ===
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/products/{product}', [ShopController::class, 'show'])->name('products.show');

// Keranjang Belanja (Guest OK - pakai session)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

// Wishlist (Guest OK - pakai session)
Route::get('/wishlist', [ShopController::class, 'wishlist'])->name('wishlist.index');
Route::post('/wishlist/add/{product}', [ShopController::class, 'addToWishlist'])->name('wishlist.add');
Route::delete('/wishlist/remove/{product}', [ShopController::class, 'removeFromWishlist'])->name('wishlist.remove');

// === Route Wajib Login ===
Route::middleware(['auth'])->group(function () {
    // Redirect setelah login berdasarkan role
    Route::get('/dashboard', function () {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('shop.index');
    })->middleware(['auth'])->name('dashboard');

    // Checkout
    Route::get('/checkout', [CartController::class, 'checkoutForm'])->name('checkout.form');
    Route::post('/checkout/process', [CartController::class, 'checkoutProcess'])->name('checkout.process');

    // Invoice
    Route::get('/orders/invoice/{order}', function (Order $order) {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        return view('shop.orders.invoice', compact('order'));
    })->name('orders.invoice');

    // Riwayat Pesanan
    Route::get('/orders/history', [OrderController::class, 'history'])->name('orders.history');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::patch('/profile/phone', [ProfileController::class, 'updatePhone'])->name('profile.phone');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/destroy', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Alamat
    Route::get('/addresses/create', [AddressController::class, 'create'])->name('addresses.create');
    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::patch('/addresses/{address}/primary', [AddressController::class, 'setPrimary'])->name('addresses.primary');
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
});

// === Route Khusus Admin ===
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', function () {
            $totalProducts = Product::count();
            $lastViewed = session('admin_notifications_viewed_at', now()->startOfDay());
            $newOrdersToday = Order::where('created_at', '>', $lastViewed)->count();
            $lowStockCount = Product::where('stock', '<', 10)->count();
            $notifications = $newOrdersToday + $lowStockCount;

            $monthlySales = Order::whereMonth('created_at', 12)
                                 ->whereYear('created_at', 2025)
                                 ->sum('total');

            $topProducts = Product::orderByDesc('sold')->take(5)->get();
            $lowStock = Product::where('stock', '<', 10)->orderBy('stock')->take(5)->get();

            return view('admin.dashboard', compact(
                'totalProducts', 'newOrdersToday', 'notifications', 'monthlySales', 'topProducts', 'lowStock'
            ));
        })->name('dashboard');

        Route::resource('products', ProductController::class);

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

            return view('admin.charts', compact('topProducts', 'lowStockCategories', 'orderStats', 'labels', 'data'));
        })->name('charts');

        Route::get('/notifications', function () {
            $newOrders = Order::with('user')->latest()->take(10)->get();
            $lowStockProducts = Product::where('stock', '<', 10)->orderBy('stock')->get();
            $totalNotifications = $newOrders->count() + $lowStockProducts->count();

            session(['admin_notifications_viewed_at' => now()]);

            return view('admin.notifications', compact('newOrders', 'lowStockProducts', 'totalNotifications'));
        })->name('notifications');

        // Diskon Admin (Satu File View)
        Route::get('/discounts', [DiscountController::class, 'index'])->name('discounts');
        Route::post('/discounts', [DiscountController::class, 'store'])->name('discounts');
        Route::patch('/discounts/{discount}', [DiscountController::class, 'update'])->name('discounts');
        Route::delete('/discounts/{discount}', [DiscountController::class, 'destroy'])->name('discounts');
    });

// Include route auth Breeze (login, register, forgot password, dll)
require __DIR__.'/auth.php';