<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    // Trang xem danh sách sản phẩm yêu thích
    public function index()
    {
        $wishlists = Wishlist::with(['product.category', 'product.brand'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(8);

        return view('shop.wishlist', compact('wishlists'));
    }

    // Toggle yêu thích (Thêm / Xóa qua AJAX)
    public function toggle(Request $request, Product $product)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để lưu sản phẩm yêu thích!',
                'redirect' => route('login')
            ], 401);
        }

        $userId = Auth::id();
        $wishlistItem = Wishlist::where('user_id', $userId)
            ->where('product_id', $product->id)
            ->first();

        if ($wishlistItem) {
            $wishlistItem->delete();
            $isFavorited = false;
            $message = 'Đã xóa khỏi danh sách yêu thích';
        } else {
            Wishlist::create([
                'user_id' => $userId,
                'product_id' => $product->id,
            ]);
            $isFavorited = true;
            $message = 'Đã thêm vào danh sách yêu thích!';
        }

        $count = Wishlist::where('user_id', $userId)->count();

        return response()->json([
            'success' => true,
            'is_favorited' => $isFavorited,
            'count' => $count,
            'message' => $message,
        ]);
    }

    // Xóa khỏi danh sách từ trang Wishlist
    public function destroy(Product $product)
    {
        Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->delete();

        return back()->with('success', 'Đã bỏ sản phẩm khỏi danh sách yêu thích.');
    }
}