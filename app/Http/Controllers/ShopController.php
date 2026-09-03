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
        $categories = Category::withCount('products')->get();
        $brands = Brand::all();

        $query = Product::with(['category', 'brand', 'images', 'specifications']);

        // 1. Lọc theo Từ khóa
        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        // 2. Lọc theo Danh mục
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category)->orWhere('id', $request->category);
            });
        }

        // 3. Lọc theo Thương hiệu (Hỗ trợ nhiều hãng)
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

        // 5. Lọc theo RAM (8GB, 16GB, 32GB...)
        if ($request->filled('ram')) {
            $rams = is_array($request->ram) ? $request->ram : [$request->ram];
            $query->where(function ($q) use ($rams) {
                foreach ($rams as $ram) {
                    $q->orWhere('name', 'LIKE', "%{$ram}%")
                      ->orWhereHas('specifications', function ($sub) use ($ram) {
                          $sub->where('spec_key', 'LIKE', '%RAM%')->where('spec_value', 'LIKE', "%{$ram}%");
                      });
                }
            });
        }

        // 6. Lọc theo Ổ cứng / Bộ nhớ trong (256GB, 512GB, 1TB...)
        if ($request->filled('storage')) {
            $storages = is_array($request->storage) ? $request->storage : [$request->storage];
            $query->where(function ($q) use ($storages) {
                foreach ($storages as $st) {
                    $q->orWhere('name', 'LIKE', "%{$st}%")
                      ->orWhereHas('specifications', function ($sub) use ($st) {
                          $sub->where(function ($k) {
                              $k->where('spec_key', 'LIKE', '%Ổ cứng%')
                                ->orWhere('spec_key', 'LIKE', '%Bộ nhớ trong%')
                                ->orWhere('spec_key', 'LIKE', '%ROM%')
                                ->orWhere('spec_key', 'LIKE', '%SSD%');
                          })->where('spec_value', 'LIKE', "%{$st}%");
                      });
                }
            });
        }

        // 7. Lọc theo Nhu cầu sử dụng
        if ($request->filled('demand')) {
            switch ($request->demand) {
                case 'gaming':
                    $query->where(function ($q) {
                        $q->where('name', 'LIKE', '%Gaming%')
                          ->orWhere('name', 'LIKE', '%RTX%')
                          ->orWhere('name', 'LIKE', '%LOQ%')
                          ->orWhere('name', 'LIKE', '%ROG%')
                          ->orWhere('name', 'LIKE', '%Zephyrus%');
                    });
                    break;
                case 'office':
                    $query->where(function ($q) {
                        $q->where('name', 'LIKE', '%MacBook%')
                          ->orWhere('name', 'LIKE', '%Pavilion%')
                          ->orWhere('name', 'LIKE', '%XPS%')
                          ->orWhere('name', 'LIKE', '%Air%')
                          ->orWhere('name', 'LIKE', '%Slim%');
                    });
                    break;
                case 'flagship':
                    $query->where('price', '>', 20000000);
                    break;
            }
        }

        // 8. Sắp xếp
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

        $filteredProducts = $query->paginate(12)->withQueryString();

        $latestProducts = Product::with(['category', 'brand', 'images'])->latest()->take(8)->get();
        $featuredProducts = Product::with(['category', 'brand', 'images'])->where('price', '>', 15000000)->take(8)->get();

        return view('welcome', compact('categories', 'brands', 'filteredProducts', 'latestProducts', 'featuredProducts'));
    }

    public function category(Request $request, string $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $allCategories = Category::withCount('products')->get();
        $brands = Brand::all();

        $query = Product::with(['brand', 'images', 'specifications'])->where('category_id', $category->id);

        // Lọc giá theo khoảng hoặc input trực tiếp
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

        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float)$request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float)$request->max_price);
        }

        // Lọc hãng
        if ($request->filled('brand')) {
            $brandsInput = is_array($request->brand) ? $request->brand : [$request->brand];
            $query->where(function ($q) use ($brandsInput) {
                $q->whereIn('brand_id', $brandsInput)
                  ->orWhereHas('brand', fn($b) => $b->whereIn('slug', $brandsInput));
            });
        }

        // Lọc RAM
        if ($request->filled('ram')) {
            $rams = is_array($request->ram) ? $request->ram : [$request->ram];
            $query->where(function ($q) use ($rams) {
                foreach ($rams as $ram) {
                    $q->orWhere('name', 'LIKE', "%{$ram}%")
                      ->orWhereHas('specifications', function ($sub) use ($ram) {
                          $sub->where('spec_key', 'LIKE', '%RAM%')->where('spec_value', 'LIKE', "%{$ram}%");
                      });
                }
            });
        }

        // Sắp xếp
        switch ($request->input('sort', 'latest')) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();

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

    /**
     * API gợi ý sản phẩm realtime cho thanh tìm kiếm
     */
    public function searchSuggestions(Request $request)
    {
        $keyword = trim($request->input('q', ''));

        if (mb_strlen($keyword) < 2) {
            return response()->json([]);
        }

        $products = Product::with(['category', 'brand', 'images'])
            ->where(function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%')
                      ->orWhereHas('brand', function ($q) use ($keyword) {
                          $q->where('name', 'like', '%' . $keyword . '%');
                      })
                      ->orWhereHas('category', function ($q) use ($keyword) {
                          $q->where('name', 'like', '%' . $keyword . '%');
                      });
            })
            ->take(6)
            ->get()
            ->map(function ($p) {
                $image = $p->images->first() 
                    ? asset('storage/' . $p->images->first()->image_path)
                    : ($p->image ? asset('storage/' . $p->image) : null);

                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'slug' => $p->slug,
                    'url' => route('shop.product', $p->slug),
                    'price' => number_format($p->price, 0, ',', '.') . '₫',
                    'brand' => $p->brand->name ?? 'TechZone',
                    'category' => $p->category->name ?? '',
                    'image' => $image,
                ];
            });

        return response()->json($products);
    }
}