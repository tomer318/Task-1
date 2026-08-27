<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->latest()->paginate(10);
        $pendingCount = Order::where('status', 'Chờ xử lý')->count();
        return view('admin.orders.index', compact('orders', 'pendingCount'));
    }

    public function show($id)
    {
        $order = Order::with(['user', 'items.product'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Chờ xử lý,Đã xử lý,Đang chuẩn bị hàng,Đang giao hàng,Đã giao,Đã hủy',
            'payment_status' => 'required|in:Chưa thanh toán,Đã thanh toán',
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status,
        ]);

        return back()->with('success', 'Đã cập nhật trạng thái đơn hàng #' . ($order->order_code ?? $order->id) . ' thành công!');
    }
}