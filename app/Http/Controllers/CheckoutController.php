<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
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
        $memberRank = $user ? $user->member_rank : 'M-NULL';
        $expressDiscountPercent = $user ? $user->express_shipping_discount_percent : 0;

        return view('shop.checkout', compact('cart', 'user', 'addresses', 'memberRank', 'expressDiscountPercent'));
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

        // 1. KIỂM TRA TỒN KHO TRƯỚC KHI ĐẶT HÀNG
        foreach ($cart as $item) {
            $product = Product::find($item['product_id'] ?? null);
            if ($product) {
                if ($product->stock < $item['quantity']) {
                    return back()->with('error', "Sản phẩm '{$product->name}' trong kho chỉ còn {$product->stock} chiếc, không đủ số lượng bạn yêu cầu!");
                }

                // Kiểm tra thêm tồn kho của biến thể (nếu có)
                if (!empty($item['version']) || !empty($item['color'])) {
                    $variant = $product->variants()
                        ->where('version_name', $item['version'] ?? '')
                        ->where('color_name', $item['color'] ?? '')
                        ->first();
                    if ($variant && $variant->stock < $item['quantity']) {
                        return back()->with('error', "Phiên bản '{$variant->version_name} - {$variant->color_name}' của sản phẩm '{$product->name}' chỉ còn {$variant->stock} chiếc!");
                    }
                }
            }
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

            $shippingCouponSession = session('shipping_coupon');
            $shippingDiscountAmount = 0;
            $shippingCouponModel = null;

            $user = $request->user();
            $expressDiscountPercent = $user ? $user->express_shipping_discount_percent : 0;
            $shippingSpeed = $request->input('shipping_speed', 'normal');

            $baseShippingFee = ($subtotal - $discountAmount >= 300000 || $subtotal == 0) ? 0 : 30000;

            if ($shippingSpeed === 'express') {
                $rawExpressFee = 120000 - (120000 * $expressDiscountPercent / 100);
                if ($shippingCouponSession) {
                    $shippingCouponModel = Coupon::where('code', $shippingCouponSession['code'])->first();
                    if ($shippingCouponModel) {
                        $shippingDiscountAmount = ($shippingCouponModel->type === 'percent')
                            ? ($rawExpressFee * $shippingCouponModel->value) / 100
                            : $shippingCouponModel->value;
                    }
                }
                $shippingFee = max(0, $rawExpressFee - $shippingDiscountAmount);
            } else {
                $shippingFee = $baseShippingFee;
            }

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

            // 2. TẠO ORDER ITEM & TRỪ TỒN KHO TỰ ĐỘNG
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

                // Trừ tồn kho sản phẩm chính
                if (!empty($item['product_id'])) {
                    $prod = Product::find($item['product_id']);
                    if ($prod) {
                        $prod->decrement('stock', $item['quantity']);

                        // Trừ tồn kho biến thể tương ứng (nếu có)
                        $variant = $prod->variants()
                            ->where('version_name', $item['version'] ?? '')
                            ->where('color_name', $item['color'] ?? '')
                            ->first();
                        if ($variant) {
                            $variant->decrement('stock', $item['quantity']);
                        }
                    }
                }
            }

            if ($couponModel) {
                $couponModel->increment('used_count');
            }
            if ($shippingCouponModel) {
                $shippingCouponModel->increment('used_count');
            }

            NotificationService::send(
                $order->user_id,
                'order',
                'Đặt hàng thành công #' . $order->order_code,
                'Đơn hàng #' . $order->order_code . ' trị giá ' . number_format($order->total_price, 0, ',', '.') . '₫ (Ưu đãi hạng ' . ($user->member_rank ?? 'M-NULL') . ') của bạn đã được tiếp nhận.',
                route('profile.orders')
            );

            DB::commit();

            if ($request->payment_method === 'VNPAY') {
                $vnpayUrl = VNPayService::createPaymentUrl($order);
                session()->forget(['cart', 'coupon', 'shipping_coupon']);
                return redirect()->away($vnpayUrl);
            }

            if ($request->payment_method === 'ZALOPAY') {
                $zaloPayUrl = ZaloPayService::createPaymentUrl($order);
                session()->forget(['cart', 'coupon', 'shipping_coupon']);

                if ($zaloPayUrl) {
                    return redirect()->away($zaloPayUrl);
                }

                return redirect()->route('profile.orders')->with('error', 'Không thể tạo phiên thanh toán ZaloPay Sandbox.');
            }

            session()->forget(['cart', 'coupon', 'shipping_coupon']);

            return redirect()->route('checkout.success')->with([
                'success' => 'Đặt hàng thành công!',
                'order_code' => $order->order_code
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

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

    public function zaloPayCallback(Request $request)
    {
        $status = $request->input('status');
        $userId = Auth::id();

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