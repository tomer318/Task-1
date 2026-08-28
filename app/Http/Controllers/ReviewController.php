<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderReview;
use App\Models\ProductReview;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function store(Request $request, Order $order)
    {
        if ($order->user_id !== $request->user()->id) {
            abort(403);
        }

        if (!$order->review) {
            $tags = $request->input('order_tags', []);
            OrderReview::create([
                'order_id' => $order->id,
                'user_id' => $request->user()->id,
                'rating' => $request->input('order_rating', 5),
                'tags' => is_array($tags) ? $tags : json_decode($tags, true),
                'comment' => $request->input('order_comment'),
            ]);
        }

        if ($request->has('products')) {
            foreach ($request->products as $productId => $pData) {
                $alreadyReviewed = ProductReview::where('order_id', $order->id)
                    ->where('product_id', $productId)
                    ->exists();

                if (!$alreadyReviewed && !empty($pData['comment'])) {
                    $imagePaths = [];
                    if (isset($pData['images']) && is_array($pData['images'])) {
                        foreach ($pData['images'] as $img) {
                            if ($img->isValid()) {
                                $imagePaths[] = $img->store('reviews/images', 'public');
                            }
                        }
                    }

                    $videoPath = null;
                    if (isset($pData['video']) && $pData['video']->isValid()) {
                        $videoPath = $pData['video']->store('reviews/videos', 'public');
                    }

                    ProductReview::create([
                        'order_id' => $order->id,
                        'product_id' => $productId,
                        'user_id' => $request->user()->id,
                        'rating' => $pData['rating'] ?? 5,
                        'comment' => $pData['comment'],
                        'images' => $imagePaths,
                        'video_path' => $videoPath,
                    ]);
                }
            }
        }

        return back()->with('success', 'Cảm ơn bạn đã gửi đánh giá đơn hàng và sản phẩm!');
    }
}