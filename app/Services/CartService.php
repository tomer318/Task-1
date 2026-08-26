<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class CartService
{
    protected function getCartKey(): string
    {
        $identifier = Auth::check() ? 'user_' . Auth::id() : 'session_' . session()->getId();
        return 'cart_' . $identifier;
    }

    public function getCart(): array
    {
        $key = $this->getCartKey();
        try {
            return Cache::store('redis')->get($key, []);
        } catch (\Exception $e) {
            return session()->get($key, []);
        }
    }

    protected function saveCart(array $cart): void
    {
        $key = $this->getCartKey();
        try {
            Cache::store('redis')->put($key, $cart, 60 * 60 * 24 * 7); // Lưu 7 ngày
        } catch (\Exception $e) {
            session()->put($key, $cart);
        }
    }

    public function add(int $productId, int $quantity = 1): array
    {
        $product = Product::with('images')->findOrFail($productId);
        $cart = $this->getCart();

        $primaryImage = $product->images->where('is_primary', true)->first()?->image_path 
            ?? $product->images->first()?->image_path 
            ?? null;

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => (float)$product->price,
                'quantity' => $quantity,
                'image' => $primaryImage ? asset('storage/' . $primaryImage) : null,
                'slug' => $product->slug,
            ];
        }

        $this->saveCart($cart);
        return $this->getCartSummary();
    }

    public function update(int $productId, int $quantity): array
    {
        $cart = $this->getCart();
        if (isset($cart[$productId])) {
            if ($quantity <= 0) {
                unset($cart[$productId]);
            } else {
                $cart[$productId]['quantity'] = $quantity;
            }
            $this->saveCart($cart);
        }
        return $this->getCartSummary();
    }

    public function remove(int $productId): array
    {
        $cart = $this->getCart();
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            $this->saveCart($cart);
        }
        return $this->getCartSummary();
    }

    public function getCartSummary(): array
    {
        $cart = $this->getCart();
        $totalQuantity = 0;
        $totalPrice = 0.0;

        foreach ($cart as $item) {
            $totalQuantity += $item['quantity'];
            $totalPrice += $item['price'] * $item['quantity'];
        }

        return [
            'items' => array_values($cart),
            'total_quantity' => $totalQuantity,
            'total_price' => $totalPrice,
            'formatted_total' => '$' . number_format($totalPrice, 2),
        ];
    }
}