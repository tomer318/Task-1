<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReturnRequest;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class ReturnManagementController extends Controller
{
    public function index()
    {
        $returnRequests = ReturnRequest::with(['order.items', 'user'])->latest()->paginate(15);
        return view('admin.returns.index', compact('returnRequests'));
    }

    public function show(ReturnRequest $returnRequest)
    {
        $returnRequest->load(['order.items', 'user']);
        return view('admin.returns.show', compact('returnRequest'));
    }

    public function updateStatus(Request $request, ReturnRequest $returnRequest)
    {
        $action = $request->input('action');
        $order = $returnRequest->order;

        if ($action === 'process') {
            $returnRequest->update(['status' => 'Đang xử lý']);
        } elseif ($action === 'complete') {
            $finalStatus = ($order->payment_status === 'Đã thanh toán') ? 'Đã hoàn tiền' : 'Đã đổi/trả';
            $returnRequest->update([
                'status' => $finalStatus,
                'admin_note' => $request->input('admin_note', 'Đã xử lý hoàn tất đổi/trả và hoàn tiền.'),
                'processed_at' => now(),
            ]);
        } elseif ($action === 'reject') {
            $returnRequest->update([
                'status' => 'Từ chối',
                'admin_note' => $request->input('admin_note', 'Yêu cầu không đáp ứng chính sách đổi trả của shop.'),
                'processed_at' => now(),
            ]);
        }

        // Bắn thông báo cập nhật kết quả đổi/trả cho khách hàng
        NotificationService::send(
            $returnRequest->user_id,
            'return',
            'Cập nhật yêu cầu Đổi / Trả đơn hàng #' . $order->order_code,
            'Yêu cầu đổi trả cho đơn #' . $order->order_code . ' hiện ở trạng thái: "' . $returnRequest->status . '". ' . ($request->admin_note ? 'Ghi chú: ' . $request->admin_note : ''),
            route('profile.returns')
        );

        return back()->with('success', 'Đã cập nhật tiến trình yêu cầu đổi/trả!');
    }
}