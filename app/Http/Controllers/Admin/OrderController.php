<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderCancellation;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['items', 'cancellation', 'returnRequest'])->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(int|string $id)
    {
        $order = Order::with(['items', 'cancellation', 'returnRequest'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, int|string $id)
    {
        $order = Order::with('items')->findOrFail($id);
        $newStatus = $request->input('status');

        $nextAllowed = [
            'Chờ xử lý' => ['Đã xử lý', 'Đã hủy'],
            'Đã xử lý' => ['Đang chuẩn bị hàng', 'Đã hủy'],
            'Đang chuẩn bị hàng' => ['Đang giao hàng', 'Đã hủy'],
            'Đang giao hàng' => ['Đã giao'],
            'Đã giao' => [],
            'Đã hủy' => [],
        ];

        if (in_array($order->status, ['Đã giao', 'Đã hủy'])) {
            return back()->with('error', 'Đơn hàng đã ở trạng thái kết thúc (' . $order->status . '), không thể chỉnh sửa!');
        }

        if ($newStatus !== $order->status) {
            $allowed = $nextAllowed[$order->status] ?? [];
            if (!in_array($newStatus, $allowed)) {
                return back()->with('error', 'Không thể chuyển từ "' . $order->status . '" sang "' . $newStatus . '". Vui lòng tuân thủ quy trình tuần tự!');
            }

            if ($newStatus === 'Đã hủy') {
                if (!$order->cancellation) {
                    OrderCancellation::create([
                        'order_id' => $order->id,
                        'user_id' => Auth::id(),
                        'tags' => ['Admin hủy theo quy trình'],
                        'reason' => 'Admin hủy đơn hàng từ bảng quản trị.',
                        'cancelled_by' => 'admin',
                    ]);
                }

                // ADMIN HỦY ĐƠN -> HOÀN TỒN KHO TỰ ĐỘNG
                foreach ($order->items as $item) {
                    if ($item->product_id) {
                        $prod = \App\Models\Product::find($item->product_id);
                        if ($prod) {
                            $prod->increment('stock', $item->quantity);

                            $variant = $prod->variants()
                                ->where('version_name', $item->version_name)
                                ->where('color_name', $item->color_name)
                                ->first();
                            if ($variant) {
                                $variant->increment('stock', $item->quantity);
                            }
                        }
                    }
                }
            }

            $order->status = $newStatus;
        }

        if ($request->has('payment_status')) {
            $order->payment_status = $request->input('payment_status');
        }

        $order->save();

        NotificationService::send(
            $order->user_id,
            'order',
            'Cập nhật tiến trình đơn hàng #' . $order->order_code,
            'Đơn hàng #' . $order->order_code . ' của bạn đã chuyển sang trạng thái: "' . $order->status . '".',
            route('profile.orders')
        );

        return back()->with('success', 'Cập nhật tiến trình đơn hàng #' . $order->order_code . ' thành công!');
    }

    public function cancelledOrders()
    {
        $cancellations = OrderCancellation::with(['order', 'user'])->latest()->paginate(15);
        
        $allTags = [];
        foreach (OrderCancellation::all() as $c) {
            if (is_array($c->tags)) {
                foreach ($c->tags as $t) {
                    $allTags[$t] = ($allTags[$t] ?? 0) + 1;
                }
            }
        }
        arsort($allTags);

        return view('admin.orders.cancelled', compact('cancellations', 'allTags'));
    }
}