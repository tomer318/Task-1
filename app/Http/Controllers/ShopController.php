<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $categories = \App\Models\Category::withCount('products')->get();
        $brands = \App\Models\Brand::all();

        $query = \App\Models\Product::with(['category', 'brand', 'images']);

        // 1. Lọc theo từ khóa
        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        // 2. Lọc theo Danh mục
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category)->orWhere('id', $request->category);
            });
        }

        // 3. Lọc theo Thương hiệu
        if ($request->filled('brand')) {
            $brandsInput = is_array($request->brand) ? $request->brand : [$request->brand];
            $query->whereIn('brand_id', $brandsInput);
        }

        // 4. Lọc theo Khoảng giá
        if ($request->filled('price_range')) {
            switch ($request->price_range) {
                case 'under_5m':
                    $query->where('price', '<', 5000000);
                    break;
                case '5m_15m':
                    $query->whereBetween('price', [5000000, 15000000]);
                    break;
                case '15m_25m':
                    $query->whereBetween('price', [15000000, 25000000]);
                    break;
                case 'above_25m':
                    $query->where('price', '>', 25000000);
                    break;
            }
        }

        // 5. Sắp xếp
        switch ($request->input('sort', 'latest')) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->latest();
                break;
        }

        // Nếu có bộ lọc thì hiển thị danh sách sản phẩm đã lọc
        $filteredProducts = $query->paginate(12)->withQueryString();

        // Sản phẩm mới & Sản phẩm nổi bật khi không áp bộ lọc
        $latestProducts = \App\Models\Product::with(['category', 'brand', 'images'])->latest()->take(8)->get();
        $featuredProducts = \App\Models\Product::with(['category', 'brand', 'images'])->where('price', '>', 15000000)->take(8)->get();

        return view('welcome', compact('categories', 'brands', 'filteredProducts', 'latestProducts', 'featuredProducts'));
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

    public function product($slug)
    {
        $product = Product::with(['category', 'brand', 'images', 'variants', 'specifications'])
            ->where('slug', $slug)
            ->firstOrFail();

        $groupedSpecs = $product->specifications->groupBy(function ($item) {
            return $item->group_name ?? 'Cấu hình chung';
        });

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(5)
            ->get();

        // Lấy sản phẩm cùng danh mục để so sánh
        $comparableProducts = Product::with(['brand', 'specifications'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->get();

        return view('shop.product', compact('product', 'groupedSpecs', 'relatedProducts', 'comparableProducts'));
    }
}