<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Coupon;

class CartController extends Controller
{
    public function index()
    {
        return view('cart.index');
    }

    public function add(Request $request, Product $product)
    {
        $quantity = (int)($request->input('quantity', 1));
        $version = $request->input('version');
        $color = $request->input('color');

        if (!$version || !$color) {
            $defaultVariant = $product->variants()->first();
            $version = $defaultVariant ? $defaultVariant->version_name : 'Tiêu Chuẩn';
            $color = $defaultVariant ? $defaultVariant->color_name : 'Đen';
        }

        $variant = $product->variants()
            ->where('version_name', $version)
            ->where('color_name', $color)
            ->first();

        $price = $variant ? $variant->price : $product->price;

        $cart = session()->get('cart', []);
        $cartKey = $product->id . '_' . md5($version . '_' . $color);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
        } else {
            $cart[$cartKey] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $price,
                'image' => $product->image,
                'quantity' => $quantity,
                'version' => $version,
                'color' => $color,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index');
    }

    public function update(Request $request, string $key)
    {
        $quantity = (int)$request->input('quantity', 1);
        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            if ($quantity > 0) {
                $cart[$key]['quantity'] = $quantity;
            } else {
                unset($cart[$key]);
            }
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index');
    }

    public function remove(string $key)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        
        $code = strtoupper(trim($request->code));
        $coupon = Coupon::where('code', $code)->where('is_active', true)->first();

        if (!$coupon) {
            return back()->with('error', 'Mã giảm giá không tồn tại hoặc đã bị khóa!');
        }

        if ($coupon->expires_at && $coupon->expires_at->isPast()) {
            return back()->with('error', 'Mã giảm giá đã hết hạn sử dụng!');
        }

        if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
            return back()->with('error', 'Mã giảm giá đã hết lượt sử dụng!');
        }

        $cart = session('cart', []);
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        if ($subtotal < $coupon->min_order_value) {
            $formattedMin = number_format($coupon->min_order_value, 0, ',', '.');
            return back()->with('error', 'Đơn hàng tối thiểu phải từ ' . $formattedMin . '₫ mới được áp dụng mã này!');
        }

        session()->put('coupon', [
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'min_order_value' => $coupon->min_order_value,
        ]);

        return back()->with('success', 'Áp dụng mã giảm giá ' . $coupon->code . ' thành công!');
    }

    public function removeCoupon()
    {
        session()->forget('coupon');
        return back()->with('success', 'Đã hủy mã giảm giá!');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index');
    }
}