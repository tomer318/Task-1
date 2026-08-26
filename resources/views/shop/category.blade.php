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
        
        <!-- Sidebar: Danh sách danh mục & Bộ Lọc Giá/Hãng -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Danh sách danh mục -->
            <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl shadow-xl">
                <h3 class="font-bold text-xs text-slate-400 uppercase tracking-wider mb-3 pb-2 border-b border-slate-800">
                    📂 Tất Cả Ngành Hàng
                </h3>
                <div class="space-y-1">
                    @foreach($allCategories as $c)
                        <a href="{{ route('shop.category', $c->slug) }}" 
                           class="flex items-center justify-between px-3 py-2 rounded-lg text-xs transition {{ $c->id === $category->id ? 'bg-rose-500/10 text-rose-400 font-bold border border-rose-500/30' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                            <span>{{ $c->name }}</span>
                            <span class="text-[11px] font-mono text-slate-500">{{ $c->products_count }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Bộ lọc giá và hãng -->
            <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl shadow-xl">
                <h3 class="font-bold text-xs text-slate-400 uppercase tracking-wider mb-4 pb-2 border-b border-slate-800">
                    🔍 Bộ Lọc Chi Tiết
                </h3>

                <form method="GET" action="{{ route('shop.category', $category->slug) }}" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-300 mb-2">Khoảng Giá ($)</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" name="min_price" placeholder="Từ $" value="{{ request('min_price') }}"
                                   class="bg-slate-950 border border-slate-800 rounded-lg p-2 text-white outline-none focus:border-rose-500">
                            <input type="number" name="max_price" placeholder="Đến $" value="{{ request('max_price') }}"
                                   class="bg-slate-950 border border-slate-800 rounded-lg p-2 text-white outline-none focus:border-rose-500">
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-300 mb-2">Hãng Sản Xuất</label>
                        <select name="brand" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2 text-white outline-none focus:border-rose-500">
                            <option value="">-- Tất cả các hãng --</option>
                            @foreach($brands as $b)
                                <option value="{{ $b->slug }}" {{ request('brand') == $b->slug ? 'selected' : '' }}>{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-gradient-to-r from-rose-600 to-red-500 hover:from-rose-500 hover:to-red-400 text-white font-bold rounded-xl shadow transition">
                        Áp Dụng Lọc
                    </button>
                    @if(request()->hasAny(['min_price', 'max_price', 'brand']))
                        <a href="{{ route('shop.category', $category->slug) }}" class="block text-center text-slate-400 hover:text-white pt-1">Xóa bộ lọc</a>
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

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                @forelse($products as $p)
                    <div class="bg-slate-900 border border-slate-800 hover:border-slate-700 rounded-2xl p-4 flex flex-col justify-between transition group shadow-lg">
                        <a href="{{ route('shop.product', $p->slug) }}">
                            <div class="w-full aspect-video bg-slate-950 rounded-xl mb-3 overflow-hidden flex items-center justify-center border border-slate-800">
                                @if($p->images->first())
                                    <img src="{{ asset('storage/' . $p->images->first()->image_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition">
                                @else
                                    <span class="text-slate-600 text-xl">📱</span>
                                @endif
                            </div>
                            <span class="text-[10px] text-rose-400 font-bold uppercase">{{ $p->brand->name ?? 'Công nghệ' }}</span>
                            <h3 class="font-bold text-xs text-white line-clamp-2 mt-1 group-hover:text-rose-400 transition">{{ $p->name }}</h3>
                        </a>

                        <div class="pt-3 mt-2 border-t border-slate-800 flex items-center justify-between">
                            <span class="text-sm font-extrabold text-rose-500 font-mono">${{ number_format($p->price, 2) }}</span>
                            <button @click="addToCart({{ $p->id }})" class="p-2 bg-slate-800 hover:bg-rose-600 text-white rounded-lg transition" title="Thêm vào giỏ">
                                🛒
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 py-16 text-center text-slate-500 text-xs bg-slate-900 border border-slate-800 rounded-2xl">
                        Không tìm thấy sản phẩm nào phù hợp!
                    </div>
                @endforelse
            </div>

            <div class="pt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-store-layout>