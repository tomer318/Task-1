<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
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
        // Debug nếu cần thiết
        // dd($request->all());

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'payment_method' => 'required|string',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        // Xử lý lấy địa chỉ nhận hàng
        $shippingAddress = $request->shipping_address;
        if ($request->has('selected_address_id') && !empty($request->selected_address_id)) {
            $addressObj = \App\Models\UserAddress::find($request->selected_address_id);
            if ($addressObj) {
                $shippingAddress = $addressObj->address ?? $addressObj->full_address;
            }
        }

        if (empty($shippingAddress)) {
            $shippingAddress = '123 Đường Số 7, Quận Bình Tân, TP.HCM'; // Mặc định phòng hờ
        }

        DB::beginTransaction();
        try {
            $subtotal = 0;
            foreach ($cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }

            $coupon = session('coupon');
            $discountAmount = 0;
            if ($coupon) {
                $discountAmount = ($coupon['type'] === 'percent') ? ($subtotal * $coupon['value']) / 100 : $coupon['value'];
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

            DB::commit();

            $orderCode = $order->order_code;
            session()->forget(['cart', 'coupon']);

            return redirect()->route('checkout.success')->with([
                'success' => 'Đặt hàng thành công!',
                'order_code' => $orderCode
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}