<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

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

    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index');
    }
}