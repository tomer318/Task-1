<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->get();
        $latestProducts = Product::with(['category', 'brand', 'images'])->latest()->take(8)->get();
        $featuredProducts = Product::with(['category', 'brand', 'images'])->where('price', '>=', 500)->take(8)->get();

        return view('welcome', compact('categories', 'latestProducts', 'featuredProducts'));
    }

    public function category(Request $request, string $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $allCategories = Category::withCount('products')->get();
        $brands = Brand::all();

        $query = Product::with(['brand', 'images'])->where('category_id', $category->id);

        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float)$request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float)$request->max_price);
        }
        if ($request->filled('brand')) {
            $query->whereHas('brand', function ($q) use ($request) {
                $q->where('slug', $request->brand);
            });
        }

        $products = $query->latest()->paginate(12)->withQueryString();

        return view('shop.category', compact('category', 'allCategories', 'products', 'brands'));
    }

    public function product(string $slug)
    {
        $product = Product::with(['category', 'brand', 'images'])->where('slug', $slug)->firstOrFail();
        $relatedProducts = Product::with(['images', 'brand'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('shop.product', compact('product', 'relatedProducts'));
    }
}