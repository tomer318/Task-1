<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Order;
use App\Models\OrderReview;
use App\Models\ProductReview;
use App\Models\ReturnRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    private function getProfileData(Request $request, string $activeTab): array
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $addresses = method_exists($user, 'addresses') ? $user->addresses : collect([]);
        
        // 1. Toàn bộ đơn để tính tổng tiền & thống kê
        $allOrders = Order::with(['returnRequest'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();
        $ordersCount = $allOrders->count();

        // Chỉ tính tiền tích lũy cho các đơn Đã giao VÀ chưa bị hoàn tiền / đổi trả thành công
        $totalSpent = $allOrders->filter(function ($order) {
            $isReturned = $order->returnRequest && in_array($order->returnRequest->status, ['Đã hoàn tiền', 'Đã đổi/trả']);
            return in_array($order->status, ['Đã giao', 'Đã nhận hàng']) && !$isReturned;
        })->sum('total_price');

        // 2. Phân trang riêng cho Lịch sử mua hàng (5 đơn / trang)
        $recentOrders = Order::with(['items', 'review', 'productReviews.product', 'cancellation', 'returnRequest'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(5, ['*'], 'orders_page');

        // 3. Phân trang Đánh giá sản phẩm & Đánh giá đơn hàng (5 mục / trang)
        $myProductReviews = ProductReview::with(['product', 'order'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(5, ['*'], 'product_reviews_page');

        $myOrderReviews = OrderReview::with('order')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(5, ['*'], 'order_reviews_page');

        // 4. Phân trang Yêu cầu Đổi / Trả (5 yêu cầu / trang)
        $myReturnRequests = ReturnRequest::with(['order.items'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(5, ['*'], 'returns_page');

        // 5. Phân trang Thông báo (6 thông báo / trang)
        $myNotifications = $user->notifications()
            ->latest()
            ->paginate(6, ['*'], 'notifications_page');

        return compact(
            'user', 'addresses', 'recentOrders', 'ordersCount', 'totalSpent', 
            'activeTab', 'myProductReviews', 'myOrderReviews', 'myReturnRequests', 'myNotifications'
        );
    }

    public function edit(Request $request): View
    {
        return view('shop.member-profile', $this->getProfileData($request, 'overview'));
    }

    public function orders(Request $request): View
    {
        return view('shop.member-profile', $this->getProfileData($request, 'orders'));
    }

    public function reviews(Request $request): View
    {
        return view('shop.member-profile', $this->getProfileData($request, 'my-reviews'));
    }

    public function returns(Request $request): View
    {
        return view('shop.member-profile', $this->getProfileData($request, 'my-returns'));
    }

    public function promotion(Request $request): View
    {
        return view('shop.member-profile', $this->getProfileData($request, 'promotion'));
    }

    public function userInfo(Request $request): View
    {
        return view('shop.member-profile', $this->getProfileData($request, 'user-info'));
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.user.info')->with('status', 'profile-updated');
    }

    public function notifications(Request $request): View
    {
        return view('shop.member-profile', $this->getProfileData($request, 'my-notifications'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}