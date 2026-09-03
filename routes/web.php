<?php

use App\Http\Controllers\ShopController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserAddressController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\OrderActionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReviewManagementController;
use App\Http\Controllers\Admin\ReturnManagementController;
use App\Http\Controllers\LiveChatController;
use App\Http\Controllers\Admin\LiveChatManagementController;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// LiveChat phía Khách hàng
Route::get('/live-chat/messages', [LiveChatController::class, 'getMessages'])->name('chat.messages');
Route::post('/live-chat/send', [LiveChatController::class, 'sendMessage'])->name('chat.send');
Route::post('/live-chat/request-agent', [LiveChatController::class, 'requestAgent'])->name('chat.request_agent');
Route::get('/api/search-suggestions', [ShopController::class, 'searchSuggestions'])->name('api.search.suggestions');

// 1. Cửa hàng
Route::get('/', [ShopController::class, 'index'])->name('home');
Route::get('/category/{slug}', [ShopController::class, 'category'])->name('shop.category');
Route::get('/product/{slug}', [ShopController::class, 'product'])->name('shop.product');

// 2. Giỏ hàng & Voucher
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{key}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{key}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/cart/coupon/apply', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
Route::delete('/cart/coupon/remove', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');

// 3. Callback cổng thanh toán VNPay & zaloPay
Route::get('/checkout/vnpay/callback', [CheckoutController::class, 'vnpayCallback'])->name('checkout.vnpay.callback');
Route::get('/checkout/zalopay/callback', [CheckoutController::class, 'zaloPayCallback'])->name('checkout.zalopay.callback');

// 4. Dashboard Route
Route::get('/dashboard', function (Request $request) {
    /** @var \App\Models\User $user */
    $user = $request->user();
    if ($user && $user->hasRole('Admin')) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

// 5. Khách hàng
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/orders', [ProfileController::class, 'orders'])->name('profile.orders');
    Route::get('/profile/reviews', [ProfileController::class, 'reviews'])->name('profile.reviews');
    Route::get('/profile/returns', [ProfileController::class, 'returns'])->name('profile.returns');
    Route::get('/profile/promotion', [ProfileController::class, 'promotion'])->name('profile.promotion');
    Route::get('/profile/user-info', [ProfileController::class, 'userInfo'])->name('profile.user.info');

    // Đặt hàng
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success', function () { return view('shop.order-success'); })->name('checkout.success');

    // Thao tác Hủy đơn & Đổi trả & Đánh giá
    Route::post('/orders/{order}/cancel', [OrderActionController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{order}/return', [OrderActionController::class, 'requestReturn'])->name('orders.return');
    Route::post('/orders/{order}/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/user/addresses', [UserAddressController::class, 'store'])->name('user.addresses.store');
    Route::delete('/user/addresses/{address}', [UserAddressController::class, 'destroy'])->name('user.addresses.destroy');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Thông báo cho Khách hàng
    Route::get('/profile/notifications', [ProfileController::class, 'notifications'])->name('profile.notifications');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');

    // Wishlist Routes
    Route::get('/wishlist', [\App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist.index');
    Route::get('/profile/wishlist', [ProfileController::class, 'wishlist'])->name('profile.wishlist');
    Route::post('/wishlist/toggle/{product}', [\App\Http\Controllers\WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/{product}', [\App\Http\Controllers\WishlistController::class, 'destroy'])->name('wishlist.destroy');

    // Xuất hóa đơn PDF
    Route::get('/orders/{id}/invoice', [OrderActionController::class, 'downloadInvoice'])->name('orders.invoice');
});

// 6. Quản trị Admin
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalUsers = User::count();
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'Chờ xử lý')->count();
        $totalRevenue = Order::where('payment_status', 'Đã thanh toán')->orWhere('status', 'Đã giao')->sum('total_price');
        $recentProducts = Product::with(['category', 'brand'])->latest()->take(6)->get();
        $recentOrders = Order::latest()->take(5)->get();

        return view('admin.dashboard', compact('totalProducts', 'totalCategories', 'totalUsers', 'totalOrders', 'pendingOrders', 'totalRevenue', 'recentProducts', 'recentOrders'));
    })->name('dashboard');

    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('brands', BrandController::class);
    Route::resource('users', UserController::class);
    Route::resource('coupons', CouponController::class);

    // Đơn hàng & Hủy đơn
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/cancelled', [OrderController::class, 'cancelledOrders'])->name('orders.cancelled');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

    // Quản lý Đổi / Trả hàng
    Route::get('/returns', [ReturnManagementController::class, 'index'])->name('returns.index');
    Route::get('/returns/{returnRequest}', [ReturnManagementController::class, 'show'])->name('returns.show');
    Route::patch('/returns/{returnRequest}/status', [ReturnManagementController::class, 'updateStatus'])->name('returns.updateStatus');

    // Đánh giá & Độ hài lòng
    Route::get('/reviews', [ReviewManagementController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/users/{user}', [ReviewManagementController::class, 'userReviews'])->name('reviews.user');
    Route::patch('/reviews/products/{review}/toggle', [ReviewManagementController::class, 'toggleProductReview'])->name('reviews.product.toggle');
    Route::get('/order-satisfaction', [ReviewManagementController::class, 'orderSatisfaction'])->name('order.satisfaction');
    Route::post('/order-satisfaction/{review}/reply', [ReviewManagementController::class, 'replyOrderReview'])->name('order.satisfaction.reply');
    
    // Quản lý Live Chat CSKH
    Route::get('/live-chat', [LiveChatManagementController::class, 'index'])->name('chat.index');
    Route::get('/live-chat/{session}/messages', [LiveChatManagementController::class, 'getSessionMessages'])->name('chat.session.messages');
    Route::post('/live-chat/{session}/reply', [LiveChatManagementController::class, 'reply'])->name('chat.reply');
    Route::post('/live-chat/{session}/close', [LiveChatManagementController::class, 'close'])->name('chat.close');
});
// 7. TechBot AI Assistant Route
Route::post('/techbot/chat', [ChatbotController::class, 'message'])->name('techbot.chat');

// Route hỗ trợ chạy migrate & seeder trực tiếp trên trình duyệt
Route::get('/admin/setup-db', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh', [
            '--force' => true,
            '--seed' => true
        ]);
        return '<h2 style="color: green; font-family: sans-serif; padding: 20px;">✅ Đã khởi tạo lại toàn bộ Database và nạp dữ liệu mẫu thành công! <br><br><a href="/" style="display:inline-block; padding:10px 20px; background:#e11d48; color:#fff; text-decoration:none; border-radius:8px;">Về Trang Chủ Xem Sản Phẩm</a></h2>';
    } catch (\Exception $e) {
        return '<h2 style="color: red; font-family: sans-serif; padding: 20px;">❌ Lỗi: ' . $e->getMessage() . '</h2>';
    }
});

require __DIR__.'/auth.php';