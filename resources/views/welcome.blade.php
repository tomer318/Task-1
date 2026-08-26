<x-store-layout>
    <div class="space-y-12">
        
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

        <!-- Danh Mục Nổi Bật -->
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
                        <span class="text-[10px] text-slate-500 font-mono mt-0.5">{{ $cat->products_count }} SP</span>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Sản Phẩm Mới Nhất -->
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
                                    <img src="{{ asset('storage/' . $p->images->first()->image_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition">
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
                            <span class="text-sm font-extrabold text-rose-500 font-mono">${{ number_format($p->price, 2) }}</span>
                            <button @click="addToCart({{ $p->id }})" class="px-3 py-1.5 bg-slate-800 hover:bg-rose-600 text-white text-xs font-semibold rounded-xl transition flex items-center gap-1.5 shadow">
                                <span>+ Thêm</span>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Sản Phẩm Nổi Bật / Cao Cấp -->
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
                                    <img src="{{ asset('storage/' . $p->images->first()->image_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition">
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
                            <span class="text-sm font-extrabold text-rose-500 font-mono">${{ number_format($p->price, 2) }}</span>
                            <button @click="addToCart({{ $p->id }})" class="px-3 py-1.5 bg-slate-800 hover:bg-rose-600 text-white text-xs font-semibold rounded-xl transition flex items-center gap-1.5 shadow">
                                <span>+ Thêm</span>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</x-store-layout>