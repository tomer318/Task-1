<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderReview;
use App\Models\ProductReview;
use App\Models\User;
use App\Services\NotificationService;

class ReviewManagementController extends Controller
{
    // 1. Quản lý Đánh giá sản phẩm theo Khách hàng
    public function index(Request $request)
    {
        $users = User::whereHas('productReviews')->withCount('productReviews')->paginate(10);
        return view('admin.reviews.index', compact('users'));
    }

    public function userReviews(User $user)
    {
        $productReviews = ProductReview::with(['product', 'order'])->where('user_id', $user->id)->latest()->get();
        return view('admin.reviews.user_detail', compact('user', 'productReviews'));
    }

    public function toggleProductReview(Request $request, ProductReview $review)
    {
        $newActiveStatus = !$review->is_active;
        $violationReason = $request->violation_reason ?? 'Nội dung phản cảm hoặc không phù hợp quy chuẩn.';

        $review->update([
            'is_active' => $newActiveStatus,
            'violation_reason' => $violationReason
        ]);

        // Nếu admin gỡ đánh giá vi phạm, gửi thông báo lý do về cho khách hàng
        if (!$newActiveStatus) {
            NotificationService::send(
                $review->user_id,
                'review',
                'Đánh giá sản phẩm đã bị gỡ do vi phạm',
                'Đánh giá của bạn cho sản phẩm "' . ($review->product->name ?? 'Sản phẩm') . '" đã bị ẩn do: ' . $violationReason,
                route('profile.reviews')
            );
        }

        return back()->with('success', 'Đã cập nhật trạng thái hiển thị đánh giá!');
    }

    // 2. Thống kê & Quản lý Sự Hài Lòng Đơn Hàng
    public function orderSatisfaction()
    {
        $reviews = OrderReview::with(['order', 'user'])->latest()->paginate(15);
        $totalReviews = OrderReview::count();
        $fiveStarCount = OrderReview::where('rating', 5)->count();
        $satisfactionRate = $totalReviews > 0 ? round(($fiveStarCount / $totalReviews) * 100) : 100;

        return view('admin.reviews.order_satisfaction', compact('reviews', 'totalReviews', 'fiveStarCount', 'satisfactionRate'));
    }

    public function replyOrderReview(Request $request, OrderReview $review)
    {
        $replyContent = $request->reply ?? 'TECHZONE đã tiếp nhận đánh giá về đơn hàng #' . ($review->order->order_code ?? $review->order_id) . '. Cảm ơn quý khách đã tin tưởng và ủng hộ cửa hàng!';

        $review->update([
            'admin_reply' => $replyContent,
            'replied_at' => now(),
        ]);

        // Gửi thông báo cảm ơn / phản hồi từ shop cho khách hàng
        NotificationService::send(
            $review->user_id,
            'review',
            'Shop đã phản hồi đánh giá đơn hàng #' . ($review->order->order_code ?? $review->order_id),
            $replyContent,
            route('profile.reviews')
        );

        return back()->with('success', 'Đã gửi phản hồi tới khách hàng thành công!');
    }
}