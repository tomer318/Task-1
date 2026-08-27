<?php

use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserAddressController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\UserController;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// 1. Giao diện Cửa hàng (Storefront)
Route::get('/', [ShopController::class, 'index'])->name('home');
Route::get('/category/{slug}', [ShopController::class, 'category'])->name('shop.category');
Route::get('/product/{slug}', [ShopController::class, 'product'])->name('shop.product');

// 2. Giỏ hàng & Mã giảm giá
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{key}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{key}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/cart/coupon/apply', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
Route::delete('/cart/coupon/remove', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');

// 3. Điều hướng Dashboard theo Role
Route::get('/dashboard', function (Request $request) {
    /** @var \App\Models\User $user */
    $user = $request->user();
    if ($user && $user->hasRole('Admin')) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');


// 4. Hồ sơ cá nhân
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/orders', [ProfileController::class, 'orders'])->name('profile.orders');
    Route::get('/profile/promotion', [ProfileController::class, 'promotion'])->name('profile.promotion');
    Route::get('/profile/user-info', [ProfileController::class, 'userInfo'])->name('profile.user.info');
    
    Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process');

    Route::get('/checkout/success', function () {
        return view('shop.order-success');
    })->name('checkout.success');
    
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/user/addresses', [UserAddressController::class, 'store'])->name('user.addresses.store');
    Route::delete('/user/addresses/{address}', [UserAddressController::class, 'destroy'])->name('user.addresses.destroy');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 5. Quản trị Admin
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalUsers = User::count();
        
        // Thống kê đơn hàng & doanh thu
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'Chờ xử lý')->count();
        $totalRevenue = Order::where('payment_status', 'Đã thanh toán')
                             ->orWhere('status', 'Đã giao')
                             ->sum('total_price');

        // Lấy danh sách hiển thị
        $recentProducts = Product::with(['category', 'brand'])->latest()->take(6)->get();
        $recentOrders = Order::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalProducts', 
            'totalCategories', 
            'totalUsers', 
            'totalOrders', 
            'pendingOrders', 
            'totalRevenue', 
            'recentProducts', 
            'recentOrders'
        ));
    })->name('dashboard');

    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('brands', BrandController::class);
    Route::resource('users', UserController::class);
    Route::resource('coupons', CouponController::class);
    
    // Quản lý Đơn hàng
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
});

require __DIR__.'/auth.php';