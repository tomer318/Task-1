<x-store-layout>
    <div class="space-y-10 text-white">
        
        <!-- Hero Banner Công Nghệ -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-rose-950/40 to-slate-900 border border-slate-800 p-8 sm:p-12 shadow-2xl">
            <div class="max-w-2xl space-y-4">
                <span class="inline-block px-3 py-1 bg-rose-500/20 text-rose-400 text-xs font-bold rounded-full uppercase tracking-wider border border-rose-500/30">
                    🔥 Siêu Phẩm Flagship 2026
                </span>
                <h1 class="text-3xl sm:text-5xl font-extrabold text-white tracking-tight leading-tight">
                    Công Nghệ Đỉnh Cao <br>
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-rose-500 to-orange-400">Trải Nghiệm Tương Lai</span>
                </h1>
                <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">
                    Khám phá hơn 1,000+ sản phẩm laptop gaming, smartphone, TV 4K và phụ kiện cao cấp chính hãng tại TechZone.
                </p>
            </div>
        </div>

        <!-- BỘ LỌC NÂNG CAO TECHZONE (CÁCH BIỆT RÕ RÀNG VỚI INLINE STYLE) -->
        <div style="margin-top: 48px; margin-bottom: 24px;" class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 shadow-2xl space-y-4">
            <form action="/" method="GET" id="filterForm" class="space-y-4 text-xs">
                
                <!-- Hàng 1: Header bộ lọc & Sắp xếp -->
                <div class="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-slate-800">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center justify-center font-bold text-sm">
                            ⚡
                        </span>
                        <div>
                            <h2 class="font-bold text-sm text-white">Bộ Lọc Tìm Kiếm Nhanh</h2>
                            <p class="text-[11px] text-slate-400">Lọc cấu hình và phân khúc giá theo nhu cầu</p>
                        </div>
                    </div>

                    <!-- Dropdown Sắp xếp -->
                    <div class="flex items-center gap-2">
                        <span class="text-slate-400 font-medium">Sắp xếp:</span>
                        <select name="sort" onchange="document.getElementById('filterForm').submit()" 
                                class="bg-slate-950 border border-slate-800 focus:border-rose-500 rounded-xl px-3 py-2 text-xs text-white focus:outline-none cursor-pointer">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên: A - Z</option>
                        </select>
                    </div>
                </div>

                <!-- Hàng 2: Lọc theo Khoảng giá (Price Pills) -->
                <div class="space-y-2">
                    <span class="text-slate-400 font-semibold block">Khoảng giá:</span>
                    <div class="flex flex-wrap gap-2">
                        @php $currentPriceRange = request('price_range', ''); @endphp
                        
                        <label class="cursor-pointer">
                            <input type="radio" name="price_range" value="" onchange="document.getElementById('filterForm').submit()" class="hidden peer" {{ empty($currentPriceRange) ? 'checked' : '' }}>
                            <span class="px-3 py-1.5 rounded-xl border border-slate-800 bg-slate-950 text-slate-400 peer-checked:bg-rose-600 peer-checked:text-white peer-checked:border-rose-500 transition block font-medium">
                                Tất cả mức giá
                            </span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="price_range" value="under_5m" onchange="document.getElementById('filterForm').submit()" class="hidden peer" {{ $currentPriceRange == 'under_5m' ? 'checked' : '' }}>
                            <span class="px-3 py-1.5 rounded-xl border border-slate-800 bg-slate-950 text-slate-400 peer-checked:bg-rose-600 peer-checked:text-white peer-checked:border-rose-500 transition block font-medium">
                                Dưới 5 triệu
                            </span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="price_range" value="5m_15m" onchange="document.getElementById('filterForm').submit()" class="hidden peer" {{ $currentPriceRange == '5m_15m' ? 'checked' : '' }}>
                            <span class="px-3 py-1.5 rounded-xl border border-slate-800 bg-slate-950 text-slate-400 peer-checked:bg-rose-600 peer-checked:text-white peer-checked:border-rose-500 transition block font-medium">
                                5 - 15 triệu
                            </span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="price_range" value="15m_25m" onchange="document.getElementById('filterForm').submit()" class="hidden peer" {{ $currentPriceRange == '15m_25m' ? 'checked' : '' }}>
                            <span class="px-3 py-1.5 rounded-xl border border-slate-800 bg-slate-950 text-slate-400 peer-checked:bg-rose-600 peer-checked:text-white peer-checked:border-rose-500 transition block font-medium">
                                15 - 25 triệu
                            </span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="price_range" value="above_25m" onchange="document.getElementById('filterForm').submit()" class="hidden peer" {{ $currentPriceRange == 'above_25m' ? 'checked' : '' }}>
                            <span class="px-3 py-1.5 rounded-xl border border-slate-800 bg-slate-950 text-slate-400 peer-checked:bg-rose-600 peer-checked:text-white peer-checked:border-rose-500 transition block font-medium">
                                Trên 25 triệu
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Hàng 3: Lọc theo Thương hiệu -->
                @if(isset($brands) && $brands->count() > 0)
                    <div class="space-y-2 pt-2 border-t border-slate-800/80">
                        <span class="text-slate-400 font-semibold block">Thương hiệu:</span>
                        <div class="flex flex-wrap gap-2 items-center">
                            @foreach($brands as $brand)
                                @php
                                    $checked = is_array(request('brand')) ? in_array($brand->id, request('brand')) : request('brand') == $brand->id;
                                @endphp
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="brand[]" value="{{ $brand->id }}" onchange="document.getElementById('filterForm').submit()" class="hidden peer" {{ $checked ? 'checked' : '' }}>
                                    <span class="px-3 py-1.5 rounded-xl border border-slate-800 bg-slate-950 text-slate-400 peer-checked:border-rose-500 peer-checked:bg-rose-500/10 peer-checked:text-rose-400 transition block font-semibold">
                                        {{ $brand->name }}
                                    </span>
                                </label>
                            @endforeach

                            <!-- Nút Xóa toàn bộ lọc -->
                            @if(request()->hasAny(['price_range', 'brand', 'keyword', 'sort', 'category']))
                                <a href="/" class="px-3 py-1.5 text-xs text-rose-400 hover:text-rose-300 hover:underline transition ml-auto font-semibold">
                                    ✕ Xóa bộ lọc
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

            </form>
        </div>

        @if(request()->hasAny(['price_range', 'brand', 'keyword', 'sort', 'category']))
            <!-- HIỂN THỊ KẾT QUẢ KHI CÓ BỘ LỌC ĐƯỢC CHỌN -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-cyan-500"></span>
                        Kết Quả Lọc ({{ $filteredProducts->total() }} sản phẩm)
                    </h2>
                </div>

                @if($filteredProducts->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                        @foreach($filteredProducts as $p)
                            <div class="bg-slate-900 border border-slate-800 hover:border-slate-700 rounded-2xl p-4 flex flex-col justify-between transition group shadow-xl">
                                <a href="{{ route('shop.product', $p->slug) }}">
                                    <div class="w-full aspect-video bg-slate-950 rounded-xl mb-3 overflow-hidden flex items-center justify-center border border-slate-800/60">
                                        @if($p->images->first())
                                            <img src="{{ asset('storage/' . $p->images->first()->image_path) }}" class="w-full h-full object-contain p-2 group-hover:scale-105 transition">
                                        @elseif($p->image)
                                            <img src="{{ asset('storage/' . $p->image) }}" class="w-full h-full object-contain p-2 group-hover:scale-105 transition">
                                        @else
                                            <span class="text-slate-600 text-2xl">⚡</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-[10px] text-rose-400 font-bold uppercase">{{ $p->brand->name ?? 'TechZone' }}</span>
                                        <span class="text-[10px] text-slate-500">• {{ $p->category->name }}</span>
                                    </div>
                                    <h3 class="font-semibold text-xs text-white line-clamp-2 group-hover:text-rose-400 transition">{{ $p->name }}</h3>
                                </a>

                                <div class="pt-3 mt-3 border-t border-slate-800/80 flex items-center justify-between">
                                    <span class="text-sm font-extrabold text-rose-500 font-mono">{{ number_format($p->price, 0, ',', '.') }}₫</span>
                                    
                                    <form method="POST" action="{{ route('cart.add', $p) }}">
                                        @csrf
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="px-3 py-1.5 bg-slate-800 hover:bg-rose-600 text-white text-xs font-semibold rounded-xl transition flex items-center gap-1 cursor-pointer">
                                            <span>+ Thêm</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($filteredProducts->hasPages())
                        <div class="pt-6 flex justify-center">
                            {{ $filteredProducts->links() }}
                        </div>
                    @endif
                @else
                    <div class="py-12 bg-slate-900 border border-slate-800 rounded-3xl text-center space-y-2">
                        <div class="text-3xl">🔍</div>
                        <p class="text-xs text-slate-400">Không tìm thấy sản phẩm nào phù hợp với bộ lọc hiện tại.</p>
                        <a href="/" class="text-xs text-rose-400 hover:underline inline-block font-semibold">Xóa bộ lọc để xem tất cả</a>
                    </div>
                @endif
            </div>
        @else
            <!-- DANH MỤC NGÀNH HÀNG -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                        Danh Mục Ngành Hàng
                    </h2>
                </div>
                
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                    @foreach($categories as $cat)
                        <a href="{{ route('shop.category', $cat->slug) }}" 
                           class="p-4 bg-slate-900 border border-slate-800 hover:border-rose-500/50 rounded-2xl flex flex-col items-center justify-center text-center group transition shadow-lg">
                            <div class="w-10 h-10 rounded-xl bg-slate-950 border border-slate-800 flex items-center justify-center text-lg mb-2 group-hover:scale-110 transition">
                                💻
                            </div>
                            <span class="text-xs font-semibold text-slate-200 group-hover:text-rose-400 transition line-clamp-1">{{ $cat->name }}</span>
                            <span class="text-[10px] text-slate-500 font-mono mt-0.5">{{ $cat->products_count ?? $cat->products()->count() }} SP</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- SẢN PHẨM MỚI NHẤT -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                        Sản Phẩm Mới Cập Nhật
                    </h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    @foreach($latestProducts as $p)
                        <div class="bg-slate-900 border border-slate-800 hover:border-slate-700 rounded-2xl p-4 flex flex-col justify-between transition group shadow-xl">
                            <a href="{{ route('shop.product', $p->slug) }}">
                                <div class="w-full aspect-video bg-slate-950 rounded-xl mb-3 overflow-hidden flex items-center justify-center border border-slate-800/60">
                                    @if($p->images->first())
                                        <img src="{{ asset('storage/' . $p->images->first()->image_path) }}" class="w-full h-full object-contain p-2 group-hover:scale-105 transition">
                                    @elseif($p->image)
                                        <img src="{{ asset('storage/' . $p->image) }}" class="w-full h-full object-contain p-2 group-hover:scale-105 transition">
                                    @else
                                        <span class="text-slate-600 text-2xl">⚡</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[10px] text-rose-400 font-bold uppercase">{{ $p->brand->name ?? 'TechZone' }}</span>
                                    <span class="text-[10px] text-slate-500">• {{ $p->category->name }}</span>
                                </div>
                                <h3 class="font-semibold text-xs text-white line-clamp-2 group-hover:text-rose-400 transition">{{ $p->name }}</h3>
                            </a>

                            <div class="pt-3 mt-3 border-t border-slate-800/80 flex items-center justify-between">
                                <span class="text-sm font-extrabold text-rose-500 font-mono">{{ number_format($p->price, 0, ',', '.') }}₫</span>
                                
                                <form method="POST" action="{{ route('cart.add', $p) }}">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="px-3 py-1.5 bg-slate-800 hover:bg-rose-600 text-white text-xs font-semibold rounded-xl transition flex items-center gap-1 cursor-pointer">
                                        <span>+ Thêm</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- SẢN PHẨM NỔI BẬT / CAO CẤP -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                        Sản Phẩm Cao Cấp / Nổi Bật
                    </h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    @foreach($featuredProducts as $p)
                        <div class="bg-slate-900 border border-slate-800 hover:border-slate-700 rounded-2xl p-4 flex flex-col justify-between transition group shadow-xl">
                            <a href="{{ route('shop.product', $p->slug) }}">
                                <div class="w-full aspect-video bg-slate-950 rounded-xl mb-3 overflow-hidden flex items-center justify-center border border-slate-800/60">
                                    @if($p->images->first())
                                        <img src="{{ asset('storage/' . $p->images->first()->image_path) }}" class="w-full h-full object-contain p-2 group-hover:scale-105 transition">
                                    @elseif($p->image)
                                        <img src="{{ asset('storage/' . $p->image) }}" class="w-full h-full object-contain p-2 group-hover:scale-105 transition">
                                    @else
                                        <span class="text-slate-600 text-2xl">💎</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[10px] text-amber-400 font-bold uppercase">{{ $p->brand->name ?? 'Premium' }}</span>
                                    <span class="text-[10px] text-slate-500">• {{ $p->category->name }}</span>
                                </div>
                                <h3 class="font-semibold text-xs text-white line-clamp-2 group-hover:text-rose-400 transition">{{ $p->name }}</h3>
                            </a>

                            <div class="pt-3 mt-3 border-t border-slate-800/80 flex items-center justify-between">
                                <span class="text-sm font-extrabold text-rose-500 font-mono">{{ number_format($p->price, 0, ',', '.') }}₫</span>
                                
                                <form method="POST" action="{{ route('cart.add', $p) }}">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="px-3 py-1.5 bg-slate-800 hover:bg-rose-600 text-white text-xs font-semibold rounded-xl transition flex items-center gap-1 cursor-pointer">
                                        <span>+ Thêm</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</x-store-layout>