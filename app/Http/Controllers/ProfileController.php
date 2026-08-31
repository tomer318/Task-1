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
        
        $allOrders = Order::with(['returnRequest'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();
        $ordersCount = $allOrders->count();

        $totalSpent = $allOrders->filter(function ($order) {
            $isReturned = $order->returnRequest && in_array($order->returnRequest->status, ['Đã hoàn tiền', 'Đã đổi/trả']);
            return in_array($order->status, ['Đã giao', 'Đã nhận hàng']) && !$isReturned;
        })->sum('total_price');

        // Logic xếp hạng thành viên TechZone (M-Club)
        if ($totalSpent >= 50000000) {
            $memberRank = 'M-VIP';
            $rankColor = 'from-zinc-900 via-stone-900 to-black text-amber-300 border-amber-500/40';
            $nextRank = null;
            $nextThreshold = 50000000;
            $neededSpent = 0;
            $progressPercent = 100;
        } elseif ($totalSpent >= 15000000) {
            $memberRank = 'M-MEM';
            $rankColor = 'from-amber-600 via-amber-700 to-amber-900 text-amber-200 border-amber-500/50';
            $nextRank = 'M-VIP';
            $nextThreshold = 50000000;
            $neededSpent = max(0, 50000000 - $totalSpent);
            $progressPercent = min(100, round((($totalSpent - 15000000) / (50000000 - 15000000)) * 100));
        } elseif ($totalSpent >= 3000000) {
            $memberRank = 'M-NEW';
            $rankColor = 'from-orange-500 via-amber-600 to-orange-700 text-orange-100 border-orange-400/50';
            $nextRank = 'M-MEM';
            $nextThreshold = 15000000;
            $neededSpent = max(0, 15000000 - $totalSpent);
            $progressPercent = min(100, round((($totalSpent - 3000000) / (15000000 - 3000000)) * 100));
        } else {
            $memberRank = 'M-NULL';
            $rankColor = 'from-slate-800 via-slate-900 to-slate-950 text-slate-300 border-slate-700';
            $nextRank = 'M-NEW';
            $nextThreshold = 3000000;
            $neededSpent = max(0, 3000000 - $totalSpent);
            $progressPercent = min(100, round(($totalSpent / 3000000) * 100));
        }

        $recentOrders = Order::with(['items', 'review', 'productReviews.product', 'cancellation', 'returnRequest'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(5, ['*'], 'orders_page');

        $myWishlists = \App\Models\Wishlist::with(['product.category', 'product.brand'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(8, ['*'], 'wishlist_page');

        $myProductReviews = ProductReview::with(['product', 'order'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(5, ['*'], 'product_reviews_page');

        $myOrderReviews = OrderReview::with('order')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(5, ['*'], 'order_reviews_page');

        $myReturnRequests = ReturnRequest::with(['order.items'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(5, ['*'], 'returns_page');

        $myNotifications = $user->notifications()
            ->latest()
            ->paginate(6, ['*'], 'notifications_page');

        return compact(
            'user', 'addresses', 'recentOrders', 'ordersCount', 'totalSpent', 
            'activeTab', 'myWishlists', 'myProductReviews', 'myOrderReviews', 'myReturnRequests', 'myNotifications',
            'memberRank', 'rankColor', 'nextRank', 'nextThreshold', 'neededSpent', 'progressPercent'
        );
    }

    public function wishlist(Request $request): View
    {
        return view('shop.member-profile', $this->getProfileData($request, 'my-wishlist'));
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