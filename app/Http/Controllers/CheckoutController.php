<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Coupon;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use App\Services\VNPayService;
use App\Services\ZaloPayService;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống!');
        }
        
        $user = $request->user();
        $addresses = method_exists($user, 'addresses') ? $user->addresses : collect([]);

        return view('shop.checkout', compact('cart', 'user', 'addresses'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'payment_method' => 'required|string',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        $shippingAddress = $request->shipping_address;
        if ($request->has('selected_address_id') && !empty($request->selected_address_id)) {
            $addressObj = \App\Models\UserAddress::find($request->selected_address_id);
            if ($addressObj) {
                $shippingAddress = $addressObj->address ?? $addressObj->full_address;
            }
        }

        if (empty($shippingAddress)) {
            $shippingAddress = '102/1c Lê Tấn Bế, Phường An Lạc, Quận Bình Tân, TP.HCM';
        }

        DB::beginTransaction();
        try {
            $subtotal = 0;
            foreach ($cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }

            $couponSession = session('coupon');
            $discountAmount = 0;
            $couponModel = null;

            if ($couponSession) {
                $couponModel = Coupon::where('code', $couponSession['code'])->first();
                if ($couponModel) {
                    $discountAmount = ($couponModel->type === 'percent') 
                        ? ($subtotal * $couponModel->value) / 100 
                        : $couponModel->value;
                }
            }

            $shippingSpeed = $request->input('shipping_speed', 'normal');
            $baseShippingFee = ($subtotal - $discountAmount >= 300000 || $subtotal == 0) ? 0 : 30000;
            $shippingFee = ($shippingSpeed === 'express') ? 120000 : $baseShippingFee;

            $totalPrice = max(0, $subtotal - $discountAmount + $shippingFee);

            $order = Order::create([
                'user_id' => $request->user()->id,
                'order_code' => 'ORD-' . date('Ymd') . '-' . rand(1000, 9999),
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'customer_email' => $request->user()->email ?? 'user@gmail.com',
                'shipping_address' => $shippingAddress,
                'notes' => $request->notes ?? '',
                'payment_method' => $request->payment_method,
                'payment_status' => 'Chưa thanh toán',
                'status' => 'Chờ xử lý',
                'total_price' => $totalPrice,
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'] ?? null,
                    'product_name' => $item['name'],
                    'version_name' => $item['version'] ?? 'Tiêu chuẩn',
                    'color_name' => $item['color'] ?? 'Mặc định',
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'total' => $item['price'] * $item['quantity'],
                ]);
            }

            if ($couponModel) {
                $couponModel->increment('used_count');
            }

            // Gửi thông báo đặt hàng thành công
            NotificationService::send(
                $order->user_id,
                'order',
                'Đặt hàng thành công #' . $order->order_code,
                'Đơn hàng #' . $order->order_code . ' trị giá ' . number_format($order->total_price, 0, ',', '.') . '₫ của bạn đã được tiếp nhận.',
                route('profile.orders')
            );

            DB::commit();

            // 1. Chuyển hướng Cổng VNPay
            if ($request->payment_method === 'VNPAY') {
                $vnpayUrl = VNPayService::createPaymentUrl($order);
                session()->forget(['cart', 'coupon']);
                return redirect()->away($vnpayUrl);
            }

            // 2. Chuyển hướng Cổng ZaloPay Sandbox
            if ($request->payment_method === 'ZALOPAY') {
                $zaloPayUrl = ZaloPayService::createPaymentUrl($order);
                session()->forget(['cart', 'coupon']);

                if ($zaloPayUrl) {
                    return redirect()->away($zaloPayUrl);
                }

                return redirect()->route('profile.orders')->with('error', 'Không thể tạo phiên thanh toán ZaloPay Sandbox.');
            }

            // 3. Mặc định COD
            session()->forget(['cart', 'coupon']);

            return redirect()->route('checkout.success')->with([
                'success' => 'Đặt hàng thành công!',
                'order_code' => $order->order_code
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // Callback VNPay
    public function vnpayCallback(Request $request)
    {
        $vnp_ResponseCode = $request->vnp_ResponseCode;
        $vnp_TxnRef = $request->vnp_TxnRef;
        $orderCode = explode('-', $vnp_TxnRef)[0] . '-' . explode('-', $vnp_TxnRef)[1] . '-' . explode('-', $vnp_TxnRef)[2];

        $order = Order::where('order_code', $orderCode)->first();

        if ($order && $vnp_ResponseCode === '00') {
            $order->update(['payment_status' => 'Đã thanh toán']);

            NotificationService::send(
                $order->user_id,
                'order',
                'Thanh toán VNPay thành công #' . $order->order_code,
                'Đơn hàng #' . $order->order_code . ' đã được thanh toán thành công qua cổng VNPay Sandbox.',
                route('profile.orders')
            );

            return redirect()->route('checkout.success')->with([
                'success' => 'Thanh toán VNPay thành công!',
                'order_code' => $order->order_code
            ]);
        }

        return redirect()->route('profile.orders')->with('error', 'Giao dịch thanh toán VNPay không thành công hoặc đã bị hủy.');
    }

    // Callback ZaloPay
    public function zaloPayCallback(Request $request)
    {
        $status = $request->input('status');
        $apptransid = $request->input('apptransid');
        $userId = Auth::id();

        // Tìm đơn hàng mới nhất đang chờ thanh toán qua ZALOPAY của user hiện tại
        $order = Order::where('user_id', $userId)
            ->where('payment_method', 'ZALOPAY')
            ->latest()
            ->first();

        if ($order && (int) $status === 1) {
            $order->update(['payment_status' => 'Đã thanh toán']);

            NotificationService::send(
                $order->user_id,
                'order',
                'Thanh toán ZaloPay thành công #' . $order->order_code,
                'Đơn hàng #' . $order->order_code . ' đã được thanh toán thành công qua ví ZaloPay.',
                route('profile.orders')
            );

            return redirect()->route('checkout.success')->with([
                'success' => 'Thanh toán ZaloPay thành công!',
                'order_code' => $order->order_code
            ]);
        }

        return redirect()->route('profile.orders')->with('info', 'Đã quay lại từ cổng ZaloPay.');
    }
}