<x-store-layout>
    <!-- Thanh chuyển nhanh danh mục (Quick Category Bar) -->
    <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-6 border-b border-slate-800 scrollbar-none">
        <span class="text-xs text-slate-400 font-semibold shrink-0 uppercase tracking-wider mr-2">Danh mục:</span>
        @foreach($allCategories as $catItem)
            <a href="{{ route('shop.category', $catItem->slug) }}" 
               class="px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition {{ $catItem->id === $category->id ? 'bg-gradient-to-r from-rose-600 to-red-500 text-white shadow-lg shadow-rose-600/30' : 'bg-slate-900 border border-slate-800 text-slate-300 hover:text-white hover:border-slate-700' }}">
                {{ $catItem->name }} ({{ $catItem->products_count }})
            </a>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar: Danh sách danh mục & Bộ Lọc Nâng Cao -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Danh sách danh mục -->
            <div class="bg-slate-900 border border-slate-800 p-5 rounded-3xl shadow-xl">
                <h3 class="font-bold text-xs text-slate-400 uppercase tracking-wider mb-3 pb-2 border-b border-slate-800">
                    📂 Tất Cả Ngành Hàng
                </h3>
                <div class="space-y-1">
                    @foreach($allCategories as $c)
                        <a href="{{ route('shop.category', $c->slug) }}" 
                           class="flex items-center justify-between px-3 py-2 rounded-xl text-xs transition {{ $c->id === $category->id ? 'bg-rose-500/10 text-rose-400 font-bold border border-rose-500/30' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                            <span>{{ $c->name }}</span>
                            <span class="text-[11px] font-mono text-slate-500">{{ $c->products_count }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Bộ lọc chi tiết -->
            <div class="bg-slate-900 border border-slate-800 p-5 rounded-3xl shadow-xl space-y-4">
                <h3 class="font-bold text-xs text-slate-400 uppercase tracking-wider pb-2 border-b border-slate-800">
                    🔍 Bộ Lọc Chi Tiết
                </h3>

                <form method="GET" action="{{ route('shop.category', $category->slug) }}" class="space-y-4 text-xs">
                    
                    <!-- Lọc theo Hãng -->
                    <div>
                        <label class="block font-semibold text-slate-300 mb-2">Thương hiệu</label>
                        <select name="brand" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-2.5 text-white outline-none focus:border-rose-500 cursor-pointer">
                            <option value="">-- Tất cả các hãng --</option>
                            @foreach($brands as $b)
                                <option value="{{ $b->slug }}" {{ request('brand') == $b->slug ? 'selected' : '' }}>{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Lọc khoảng giá preset -->
                    <div>
                        <label class="block font-semibold text-slate-300 mb-2">Phân khúc giá</label>
                        <select name="price_range" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-2.5 text-white outline-none focus:border-rose-500 cursor-pointer">
                            <option value="">-- Tất cả mức giá --</option>
                            <option value="under_5m" {{ request('price_range') == 'under_5m' ? 'selected' : '' }}>Dưới 5 triệu</option>
                            <option value="5m_15m" {{ request('price_range') == '5m_15m' ? 'selected' : '' }}>5 - 15 triệu</option>
                            <option value="15m_25m" {{ request('price_range') == '15m_25m' ? 'selected' : '' }}>15 - 25 triệu</option>
                            <option value="above_25m" {{ request('price_range') == 'above_25m' ? 'selected' : '' }}>Trên 25 triệu</option>
                        </select>
                    </div>

                    <!-- Lọc RAM -->
                    <div>
                        <label class="block font-semibold text-slate-300 mb-2">Dung lượng RAM</label>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach(['8GB', '16GB', '32GB'] as $r)
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="ram[]" value="{{ $r }}" class="hidden peer" {{ (is_array(request('ram')) && in_array($r, request('ram'))) ? 'checked' : '' }}>
                                    <span class="px-2.5 py-1 rounded-lg border border-slate-800 bg-slate-950 text-slate-400 peer-checked:border-rose-500 peer-checked:bg-rose-500/10 peer-checked:text-rose-400 transition block font-mono text-[11px] font-bold">
                                        {{ $r }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3 bg-gradient-to-r from-rose-600 to-red-500 hover:from-rose-500 hover:to-red-400 text-white font-bold rounded-xl shadow-lg transition cursor-pointer">
                        Áp Dụng Lọc
                    </button>

                    @if(request()->hasAny(['min_price', 'max_price', 'brand', 'price_range', 'ram']))
                        <a href="{{ route('shop.category', $category->slug) }}" class="block text-center text-rose-400 hover:underline pt-1">
                            ✕ Xóa bộ lọc
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Lưới danh sách sản phẩm -->
        <div class="lg:col-span-3 space-y-6">
            <div class="flex items-center justify-between">
                <h1 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                    {{ $category->name }}
                </h1>
                <span class="text-xs text-slate-400 font-mono">{{ $products->total() }} sản phẩm</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
                @forelse($products as $p)
                    <div class="bg-slate-900 border border-slate-800 hover:border-slate-700 rounded-3xl p-4 flex flex-col justify-between transition group shadow-xl">
                        <a href="{{ route('shop.product', $p->slug) }}">
                            <div class="w-full aspect-video bg-slate-950 rounded-2xl mb-3 overflow-hidden flex items-center justify-center border border-slate-800/60">
                                @if($p->images->first())
                                    <img src="{{ asset('storage/' . $p->images->first()->image_path) }}" class="w-full h-full object-contain p-2 group-hover:scale-105 transition">
                                @elseif($p->image)
                                    <img src="{{ asset('storage/' . $p->image) }}" class="w-full h-full object-contain p-2 group-hover:scale-105 transition">
                                @else
                                    <span class="text-slate-600 text-2xl">⚡</span>
                                @endif
                            </div>
                            <span class="text-[10px] text-rose-400 font-bold uppercase">{{ $p->brand->name ?? 'Công nghệ' }}</span>
                            <h3 class="font-bold text-xs text-white line-clamp-2 mt-1 group-hover:text-rose-400 transition">{{ $p->name }}</h3>
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
                @empty
                    <div class="col-span-3 py-16 text-center text-slate-500 text-xs bg-slate-900 border border-slate-800 rounded-3xl">
                        Không tìm thấy sản phẩm nào phù hợp với bộ lọc!
                    </div>
                @endforelse
            </div>

            <div class="pt-4 flex justify-center">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-store-layout>