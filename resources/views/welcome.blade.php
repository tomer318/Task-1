<x-store-layout>
    <div class="space-y-10 text-white">
        
        <!-- Hero Banner Carousel -->
        <div class="relative overflow-hidden rounded-3xl border border-slate-800 shadow-2xl bg-slate-900"
             x-data="{
                 activeSlide: 0,
                 slides: [
                     {
                         tag: '🔥 SIÊU PHẨM FLAGSHIP 2026',
                         title: 'Công Nghệ Đỉnh Cao',
                         highlight: 'Trải Nghiệm Tương Lai',
                         desc: 'Khám phá hơn 1,000+ sản phẩm laptop gaming, smartphone, TV 4K và phụ kiện cao cấp chính hãng tại TechZone.',
                         bgClass: 'from-slate-900 via-rose-950/40 to-slate-900',
                         buttonText: 'Khám Phá Ngay',
                         buttonLink: '/'
                     },
                     {
                         tag: '⚡ LAPTOP GAMING GIẢM SỐC',
                         title: 'Hiệu Năng Vô Địch',
                         highlight: 'Chiến Game Đỉnh Cao',
                         desc: 'Sở hữu ngay các dòng laptop trang bị RTX dòng mới nhất với ưu đãi giảm giá trực tiếp đến 3.000.000₫.',
                         bgClass: 'from-slate-900 via-blue-950/40 to-slate-900',
                         buttonText: 'Săn Deal Ngay',
                         buttonLink: '/'
                     },
                     {
                         tag: '♻️ THU CŨ ĐỔI MỚI LÊN ĐỜI',
                         title: 'Trợ Giá Lên Đến',
                         highlight: '5.000.000₫ Mỗi Máy',
                         desc: 'Đổi máy cũ lấy máy mới thủ tục siêu nhanh, hỗ trợ trả góp 0% lãi suất xét duyệt trong 5 phút.',
                         bgClass: 'from-slate-900 via-emerald-950/40 to-slate-900',
                         buttonText: 'Xem Thể Lệ',
                         buttonLink: '/'
                     }
                 ],
                 timer: null,
                 init() {
                     this.timer = setInterval(() => {
                         this.next();
                     }, 5000); // Tự động chuyển slide sau mỗi 5 giây
                 },
                 next() {
                     this.activeSlide = (this.activeSlide + 1) % this.slides.length;
                 },
                 prev() {
                     this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length;
                 }
             }"
             @mouseenter="clearInterval(timer)"
             @mouseleave="timer = setInterval(() => next(), 5000)">

            <!-- Vùng chứa các Slide -->
            <div class="relative min-h-[280px] sm:min-h-[320px] flex items-center">
                <template x-for="(slide, index) in slides" :key="index">
                    <div x-show="activeSlide === index"
                         x-transition:enter="transition ease-out duration-700 transform"
                         x-transition:enter-start="opacity-0 translate-x-10"
                         x-transition:enter-end="opacity-100 translate-x-0"
                         x-transition:leave="transition ease-in duration-500 transform"
                         x-transition:leave-start="opacity-100 translate-x-0"
                         x-transition:leave-end="opacity-0 -translate-x-10"
                         class="absolute inset-0 p-8 sm:p-12 flex flex-col justify-center bg-gradient-to-r"
                         :class="slide.bgClass"
                         style="display: none;">
                        
                        <div class="max-w-2xl space-y-4">
                            <!-- Badge Tag -->
                            <span class="inline-block px-3.5 py-1 bg-rose-500/20 text-rose-400 text-xs font-bold rounded-full uppercase tracking-wider border border-rose-500/30"
                                  x-text="slide.tag">
                            </span>
                            
                            <!-- Tiêu đề -->
                            <h1 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight leading-tight">
                                <span x-text="slide.title"></span> <br>
                                <span class="bg-clip-text text-transparent bg-gradient-to-r from-rose-500 to-orange-400" x-text="slide.highlight"></span>
                            </h1>

                            <!-- Mô tả -->
                            <p class="text-xs sm:text-sm text-slate-400 leading-relaxed max-w-xl" x-text="slide.desc"></p>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Nút bấm chuyển trái (Prev) chuẩn icon vector -->
            <button @click="prev()" type="button" 
                    class="absolute left-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-white/90 hover:bg-white text-slate-900 flex items-center justify-center transition-all duration-300 shadow-2xl hover:scale-110 active:scale-95 cursor-pointer z-20 border border-slate-200 group">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-none stroke-rose-600 group-hover:stroke-rose-700 stroke-[2.5]" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
            </button>

            <!-- Nút bấm chuyển phải (Next) chuẩn icon vector -->
            <button @click="next()" type="button" 
                    class="absolute right-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-white/90 hover:bg-white text-slate-900 flex items-center justify-center transition-all duration-300 shadow-2xl hover:scale-110 active:scale-95 cursor-pointer z-20 border border-slate-200 group">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-none stroke-rose-600 group-hover:stroke-rose-700 stroke-[2.5]" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            </button>

            <!-- Chấm chỉ số Slide -->
            <div class="absolute bottom-4 right-6 flex items-center gap-2 z-20">
                <template x-for="(slide, index) in slides" :key="index">
                    <button @click="activeSlide = index" type="button"
                            class="h-2 rounded-full transition-all duration-300 cursor-pointer"
                            :class="activeSlide === index ? 'w-8 bg-rose-500' : 'w-2 bg-slate-700 hover:bg-slate-500'">
                    </button>
                </template>
            </div>
        </div>

        <!-- BỘ LỌC ĐA TIÊU CHÍ TECHZONE -->
        <div style="margin-top: 48px; margin-bottom: 24px;" class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 shadow-2xl space-y-5">
            <form action="/" method="GET" id="filterForm" class="space-y-4 text-xs">
                
                <!-- Hàng 1: Header bộ lọc & Sắp xếp -->
                <div class="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-slate-800">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-xl bg-gradient-to-tr from-rose-600 to-orange-500 text-white flex items-center justify-center font-bold text-sm shadow-md">
                            ⚡
                        </span>
                        <div>
                            <h2 class="font-bold text-sm text-white">Bộ Lọc Tìm Kiếm Nâng Cao</h2>
                            <p class="text-[11px] text-slate-400">Chọn cấu hình, phân khúc giá và nhu cầu sử dụng thực tế</p>
                        </div>
                    </div>

                    <!-- Dropdown Sắp xếp -->
                    <div class="flex items-center gap-2">
                        <span class="text-slate-400 font-medium">Sắp xếp:</span>
                        <select name="sort" onchange="document.getElementById('filterForm').submit()" 
                                class="bg-slate-950 border border-slate-800 focus:border-rose-500 rounded-xl px-3 py-2 text-xs text-white focus:outline-none cursor-pointer font-medium">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên: A - Z</option>
                        </select>
                    </div>
                </div>

                <!-- Hàng 2: Lọc theo Nhu Cầu Nhanh (Demand Pills) -->
                <div class="space-y-2">
                    <span class="text-slate-400 font-semibold block">Nhu cầu sử dụng:</span>
                    <div class="flex flex-wrap gap-2">
                        @php $currentDemand = request('demand', ''); @endphp
                        <label class="cursor-pointer">
                            <input type="radio" name="demand" value="" onchange="document.getElementById('filterForm').submit()" class="hidden peer" {{ empty($currentDemand) ? 'checked' : '' }}>
                            <span class="px-3 py-1.5 rounded-xl border border-slate-800 bg-slate-950 text-slate-400 peer-checked:bg-gradient-to-r peer-checked:from-rose-600 peer-checked:to-red-500 peer-checked:text-white peer-checked:border-rose-500 transition block font-medium">
                                Tất cả nhu cầu
                            </span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="demand" value="gaming" onchange="document.getElementById('filterForm').submit()" class="hidden peer" {{ $currentDemand == 'gaming' ? 'checked' : '' }}>
                            <span class="px-3 py-1.5 rounded-xl border border-slate-800 bg-slate-950 text-slate-400 peer-checked:bg-gradient-to-r peer-checked:from-rose-600 peer-checked:to-red-500 peer-checked:text-white peer-checked:border-rose-500 transition block font-medium">
                                🎮 Chiến Game & Đồ Họa
                            </span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="demand" value="office" onchange="document.getElementById('filterForm').submit()" class="hidden peer" {{ $currentDemand == 'office' ? 'checked' : '' }}>
                            <span class="px-3 py-1.5 rounded-xl border border-slate-800 bg-slate-950 text-slate-400 peer-checked:bg-gradient-to-r peer-checked:from-rose-600 peer-checked:to-red-500 peer-checked:text-white peer-checked:border-rose-500 transition block font-medium">
                                💻 Mỏng Nhẹ & Văn Phòng
                            </span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="demand" value="flagship" onchange="document.getElementById('filterForm').submit()" class="hidden peer" {{ $currentDemand == 'flagship' ? 'checked' : '' }}>
                            <span class="px-3 py-1.5 rounded-xl border border-slate-800 bg-slate-950 text-slate-400 peer-checked:bg-gradient-to-r peer-checked:from-rose-600 peer-checked:to-red-500 peer-checked:text-white peer-checked:border-rose-500 transition block font-medium">
                                💎 Cao Cấp / Flagship
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Hàng 3: Lọc theo Khoảng giá -->
                <div class="space-y-2 pt-2 border-t border-slate-800/60">
                    <span class="text-slate-400 font-semibold block">Khoảng giá:</span>
                    <div class="flex flex-wrap gap-2">
                        @php $currentPriceRange = request('price_range', ''); @endphp
                        <label class="cursor-pointer">
                            <input type="radio" name="price_range" value="" onchange="document.getElementById('filterForm').submit()" class="hidden peer" {{ empty($currentPriceRange) ? 'checked' : '' }}>
                            <span class="px-3 py-1.5 rounded-xl border border-slate-800 bg-slate-950 text-slate-400 peer-checked:border-rose-500 peer-checked:bg-rose-500/10 peer-checked:text-rose-400 transition block font-medium">
                                Tất cả mức giá
                            </span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="price_range" value="under_5m" onchange="document.getElementById('filterForm').submit()" class="hidden peer" {{ $currentPriceRange == 'under_5m' ? 'checked' : '' }}>
                            <span class="px-3 py-1.5 rounded-xl border border-slate-800 bg-slate-950 text-slate-400 peer-checked:border-rose-500 peer-checked:bg-rose-500/10 peer-checked:text-rose-400 transition block font-medium">
                                Dưới 5 triệu
                            </span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="price_range" value="5m_15m" onchange="document.getElementById('filterForm').submit()" class="hidden peer" {{ $currentPriceRange == '5m_15m' ? 'checked' : '' }}>
                            <span class="px-3 py-1.5 rounded-xl border border-slate-800 bg-slate-950 text-slate-400 peer-checked:border-rose-500 peer-checked:bg-rose-500/10 peer-checked:text-rose-400 transition block font-medium">
                                5 - 15 triệu
                            </span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="price_range" value="15m_25m" onchange="document.getElementById('filterForm').submit()" class="hidden peer" {{ $currentPriceRange == '15m_25m' ? 'checked' : '' }}>
                            <span class="px-3 py-1.5 rounded-xl border border-slate-800 bg-slate-950 text-slate-400 peer-checked:border-rose-500 peer-checked:bg-rose-500/10 peer-checked:text-rose-400 transition block font-medium">
                                15 - 25 triệu
                            </span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="price_range" value="above_25m" onchange="document.getElementById('filterForm').submit()" class="hidden peer" {{ $currentPriceRange == 'above_25m' ? 'checked' : '' }}>
                            <span class="px-3 py-1.5 rounded-xl border border-slate-800 bg-slate-950 text-slate-400 peer-checked:border-rose-500 peer-checked:bg-rose-500/10 peer-checked:text-rose-400 transition block font-medium">
                                Trên 25 triệu
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Hàng 4: Lọc theo Cấu hình RAM & Ổ cứng -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2 border-t border-slate-800/60">
                    <!-- RAM -->
                    <div class="space-y-2">
                        <span class="text-slate-400 font-semibold block">Dung lượng RAM:</span>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['8GB', '16GB', '32GB'] as $ramVal)
                                @php
                                    $checkedRam = is_array(request('ram')) ? in_array($ramVal, request('ram')) : request('ram') == $ramVal;
                                @endphp
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="ram[]" value="{{ $ramVal }}" onchange="document.getElementById('filterForm').submit()" class="hidden peer" {{ $checkedRam ? 'checked' : '' }}>
                                    <span class="px-3 py-1.5 rounded-xl border border-slate-800 bg-slate-950 text-slate-400 peer-checked:border-cyan-500 peer-checked:bg-cyan-500/10 peer-checked:text-cyan-400 transition block font-mono font-bold">
                                        {{ $ramVal }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Ổ cứng / Bộ nhớ trong -->
                    <div class="space-y-2">
                        <span class="text-slate-400 font-semibold block">Ổ cứng / ROM:</span>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['256GB', '512GB', '1TB'] as $storageVal)
                                @php
                                    $checkedStorage = is_array(request('storage')) ? in_array($storageVal, request('storage')) : request('storage') == $storageVal;
                                @endphp
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="storage[]" value="{{ $storageVal }}" onchange="document.getElementById('filterForm').submit()" class="hidden peer" {{ $checkedStorage ? 'checked' : '' }}>
                                    <span class="px-3 py-1.5 rounded-xl border border-slate-800 bg-slate-950 text-slate-400 peer-checked:border-cyan-500 peer-checked:bg-cyan-500/10 peer-checked:text-cyan-400 transition block font-mono font-bold">
                                        {{ $storageVal }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Hàng 5: Lọc theo Thương hiệu -->
                @if(isset($brands) && $brands->count() > 0)
                    <div class="space-y-2 pt-2 border-t border-slate-800/60">
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
                            @if(request()->hasAny(['price_range', 'brand', 'keyword', 'sort', 'category', 'ram', 'storage', 'demand']))
                                <a href="/" class="px-3 py-1.5 text-xs text-rose-400 hover:text-rose-300 hover:underline transition ml-auto font-semibold">
                                    ✕ Xóa tất cả bộ lọc
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