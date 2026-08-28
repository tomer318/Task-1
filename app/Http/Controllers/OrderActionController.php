<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderCancellation;
use App\Models\ReturnRequest;

class OrderActionController extends Controller
{
    // 1. Khách hàng hủy đơn hàng
    public function cancel(Request $request, Order $order)
    {
        if ($order->user_id !== $request->user()->id) {
            abort(403);
        }

        $allowedCancelStatus = ['Chờ xử lý', 'Đã xử lý', 'Đang chuẩn bị hàng'];
        if (!in_array($order->status, $allowedCancelStatus)) {
            return back()->with('error', 'Đơn hàng đang giao hoặc đã hoàn tất, không thể hủy!');
        }

        $tags = $request->input('cancel_tags', []);

        OrderCancellation::create([
            'order_id' => $order->id,
            'user_id' => $request->user()->id,
            'tags' => is_array($tags) ? $tags : json_decode($tags, true),
            'reason' => $request->input('cancel_reason'),
            'cancelled_by' => 'customer',
        ]);

        $order->update(['status' => 'Đã hủy']);

        return back()->with('success', 'Đơn hàng #' . $order->order_code . ' đã được hủy thành công.');
    }

    // 2. Khách hàng gửi yêu cầu Đổi / Trả hàng
    public function requestReturn(Request $request, Order $order)
    {
        if ($order->user_id !== $request->user()->id) {
            abort(403);
        }

        if ($order->status !== 'Đã giao') {
            return back()->with('error', 'Chỉ có thể yêu cầu đổi trả khi đơn hàng đã được giao thành công!');
        }

        if ($order->returnRequest) {
            return back()->with('error', 'Đơn hàng này đã có yêu cầu đổi/trả đang được xử lý.');
        }

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                if ($img->isValid()) {
                    $imagePaths[] = $img->store('returns/images', 'public');
                }
            }
        }

        $videoPath = null;
        if ($request->hasFile('video') && $request->file('video')->isValid()) {
            $videoPath = $request->file('video')->store('returns/videos', 'public');
        }

        $tags = $request->input('return_tags', []);

        ReturnRequest::create([
            'order_id' => $order->id,
            'user_id' => $request->user()->id,
            'tags' => is_array($tags) ? $tags : json_decode($tags, true),
            'reason' => $request->input('return_reason'),
            'images' => $imagePaths,
            'video_path' => $videoPath,
            'status' => 'Chờ duyệt',
        ]);

        return back()->with('success', 'Yêu cầu đổi/trả đã được gửi! Đội ngũ TECHZONE sẽ xem xét và phản hồi sớm nhất.');
    }
}