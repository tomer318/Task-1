@if(request()->hasAny(['price_range', 'brand', 'keyword', 'sort', 'category', 'ram', 'storage', 'demand']))
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
                <div class="pt-6 flex justify-center ajax-pagination">
                    {{ $filteredProducts->links() }}
                </div>
            @endif
        @else
            <div class="py-12 bg-slate-900 border border-slate-800 rounded-3xl text-center space-y-2">
                <div class="text-3xl">🔍</div>
                <p class="text-xs text-slate-400">Không tìm thấy sản phẩm nào phù hợp với bộ lọc hiện tại.</p>
                <button type="button" @click="resetFilters()" class="text-xs text-rose-400 hover:underline inline-block font-semibold cursor-pointer">
                    Xóa bộ lọc để xem tất cả
                </button>
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

    <!-- SẢN PHẨM CAO CẤP -->
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