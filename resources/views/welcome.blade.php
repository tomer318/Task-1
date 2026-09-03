<x-store-layout>
    <div class="space-y-10 text-white" 
         x-data="{
             expanded: {{ request()->hasAny(['demand', 'price_range', 'ram', 'storage', 'brand']) ? 'true' : 'false' }},
             loading: false,

             // Hàm lọc dữ liệu bằng AJAX
             applyFilter() {
                 this.loading = true;
                 const form = document.getElementById('filterForm');
                 const formData = new FormData(form);
                 const params = new URLSearchParams();

                 for (const [key, value] of formData.entries()) {
                     if (value.trim() !== '') {
                         params.append(key, value);
                     }
                 }

                 const targetUrl = '/?' + params.toString();

                 // Cập nhật đường link trên trình duyệt không cần reload trang
                 window.history.pushState({}, '', targetUrl);

                 fetch(targetUrl, {
                     headers: {
                         'X-Requested-With': 'XMLHttpRequest',
                         'Accept': 'application/json'
                     }
                 })
                 .then(res => res.json())
                 .then(data => {
                     document.getElementById('productSection').innerHTML = data.html;
                 })
                 .catch(err => console.error('Lỗi khi lọc:', err))
                 .finally(() => {
                     this.loading = false;
                 });
             },

             // Hàm reset bộ lọc
             resetFilters() {
                 const form = document.getElementById('filterForm');
                 form.reset();
                 window.history.pushState({}, '', '/');
                 this.applyFilter();
             }
         }">
        
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
                     }, 5000);
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
                            <span class="inline-block px-3.5 py-1 bg-rose-500/20 text-rose-400 text-xs font-bold rounded-full uppercase tracking-wider border border-rose-500/30"
                                  x-text="slide.tag">
                            </span>
                            <h1 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight leading-tight">
                                <span x-text="slide.title"></span> <br>
                                <span class="bg-clip-text text-transparent bg-gradient-to-r from-rose-500 to-orange-400" x-text="slide.highlight"></span>
                            </h1>
                            <p class="text-xs sm:text-sm text-slate-400 leading-relaxed max-w-xl" x-text="slide.desc"></p>
                        </div>
                    </div>
                </template>
            </div>

            <button @click="prev()" type="button" 
                    class="absolute left-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-white/90 hover:bg-white text-slate-900 flex items-center justify-center transition-all duration-300 shadow-2xl hover:scale-110 active:scale-95 cursor-pointer z-20 border border-slate-200 group">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-none stroke-rose-600 group-hover:stroke-rose-700 stroke-[2.5]" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
            </button>

            <button @click="next()" type="button" 
                    class="absolute right-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-white/90 hover:bg-white text-slate-900 flex items-center justify-center transition-all duration-300 shadow-2xl hover:scale-110 active:scale-95 cursor-pointer z-20 border border-slate-200 group">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-none stroke-rose-600 group-hover:stroke-rose-700 stroke-[2.5]" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            </button>

            <div class="absolute bottom-4 right-6 flex items-center gap-2 z-20">
                <template x-for="(slide, index) in slides" :key="index">
                    <button @click="activeSlide = index" type="button"
                            class="h-2 rounded-full transition-all duration-300 cursor-pointer"
                            :class="activeSlide === index ? 'w-8 bg-rose-500' : 'w-2 bg-slate-700 hover:bg-slate-500'">
                    </button>
                </template>
            </div>
        </div>

        <!-- BỘ LỌC ĐA TIÊU CHÍ TECHZONE (KHÔNG LOAD LẠI TRANG) -->
        <div style="margin-top: 48px; margin-bottom: 24px;" 
             class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 shadow-2xl transition-all duration-300 relative">
            
            <!-- Hiệu ứng mờ khi đang tải AJAX -->
            <div x-show="loading" class="absolute inset-0 bg-slate-950/50 backdrop-blur-[1px] z-30 rounded-3xl flex items-center justify-center" style="display: none;">
                <div class="flex items-center gap-2 text-rose-500 text-xs font-bold font-mono">
                    <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    Đang lọc sản phẩm...
                </div>
            </div>

            <form action="/" method="GET" id="filterForm" class="space-y-4 text-xs" @submit.prevent="applyFilter()">
                
                <!-- Thanh Header: Luôn luôn hiển thị -->
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5 cursor-pointer select-none" @click="expanded = !expanded">
                        <span class="w-8 h-8 rounded-xl bg-gradient-to-tr from-rose-600 to-orange-500 text-white flex items-center justify-center font-bold text-sm shadow-md">
                            ⚡
                        </span>
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="font-bold text-sm text-white">Bộ Lọc Tìm Kiếm Nâng Cao</h2>
                                <span class="text-[10px] px-2 py-0.5 rounded-full bg-slate-800 text-rose-400 font-semibold border border-slate-700" 
                                      x-text="expanded ? 'Thu gọn ▲' : 'Bấm để lọc chi tiết ▼'"></span>
                            </div>
                            <p class="text-[11px] text-slate-400">Chọn cấu hình, phân khúc giá và nhu cầu sử dụng thực tế</p>
                        </div>
                    </div>

                    <!-- Dropdown Sắp xếp & Nút bật tắt nhanh -->
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-2">
                            <span class="text-slate-400 font-medium">Sắp xếp:</span>
                            <select name="sort" @change="applyFilter()" 
                                    class="bg-slate-950 border border-slate-800 focus:border-rose-500 rounded-xl px-3 py-2 text-xs text-white focus:outline-none cursor-pointer font-medium">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên: A - Z</option>
                            </select>
                        </div>

                        <button type="button" @click="expanded = !expanded"
                                class="px-3 py-2 bg-slate-950 hover:bg-slate-800 border border-slate-800 text-slate-300 rounded-xl font-semibold transition">
                            <span x-text="expanded ? 'Đóng bộ lọc' : 'Mở bộ lọc'"></span>
                        </button>
                    </div>
                </div>

                <!-- Phần Thân Bộ Lọc: Trượt lên / xuống khi bấm -->
                <div x-show="expanded" 
                     x-collapse
                     class="space-y-4 pt-4 border-t border-slate-800/80">

                    <!-- Lọc theo Nhu Cầu Nhanh (Demand Pills) -->
                    <div class="space-y-2">
                        <span class="text-slate-400 font-semibold block">Nhu cầu sử dụng:</span>
                        <div class="flex flex-wrap gap-2">
                            @php $currentDemand = request('demand', ''); @endphp
                            <label class="cursor-pointer">
                                <input type="radio" name="demand" value="" @change="applyFilter()" class="hidden peer" {{ empty($currentDemand) ? 'checked' : '' }}>
                                <span class="px-3 py-1.5 rounded-xl border border-slate-800 bg-slate-950 text-slate-400 peer-checked:bg-gradient-to-r peer-checked:from-rose-600 peer-checked:to-red-500 peer-checked:text-white peer-checked:border-rose-500 transition block font-medium">
                                    Tất cả nhu cầu
                                </span>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="demand" value="gaming" @change="applyFilter()" class="hidden peer" {{ $currentDemand == 'gaming' ? 'checked' : '' }}>
                                <span class="px-3 py-1.5 rounded-xl border border-slate-800 bg-slate-950 text-slate-400 peer-checked:bg-gradient-to-r peer-checked:from-rose-600 peer-checked:to-red-500 peer-checked:text-white peer-checked:border-rose-500 transition block font-medium">
                                    🎮 Chiến Game & Đồ Họa
                                </span>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="demand" value="office" @change="applyFilter()" class="hidden peer" {{ $currentDemand == 'office' ? 'checked' : '' }}>
                                <span class="px-3 py-1.5 rounded-xl border border-slate-800 bg-slate-950 text-slate-400 peer-checked:bg-gradient-to-r peer-checked:from-rose-600 peer-checked:to-red-500 peer-checked:text-white peer-checked:border-rose-500 transition block font-medium">
                                    💻 Mỏng Nhẹ & Văn Phòng
                                </span>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="demand" value="flagship" @change="applyFilter()" class="hidden peer" {{ $currentDemand == 'flagship' ? 'checked' : '' }}>
                                <span class="px-3 py-1.5 rounded-xl border border-slate-800 bg-slate-950 text-slate-400 peer-checked:bg-gradient-to-r peer-checked:from-rose-600 peer-checked:to-red-500 peer-checked:text-white peer-checked:border-rose-500 transition block font-medium">
                                    💎 Cao Cấp / Flagship
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Lọc theo Khoảng giá -->
                    <div class="space-y-2 pt-2 border-t border-slate-800/60">
                        <span class="text-slate-400 font-semibold block">Khoảng giá:</span>
                        <div class="flex flex-wrap gap-2">
                            @php $currentPriceRange = request('price_range', ''); @endphp
                            <label class="cursor-pointer">
                                <input type="radio" name="price_range" value="" @change="applyFilter()" class="hidden peer" {{ empty($currentPriceRange) ? 'checked' : '' }}>
                                <span class="px-3 py-1.5 rounded-xl border border-slate-800 bg-slate-950 text-slate-400 peer-checked:border-rose-500 peer-checked:bg-rose-500/10 peer-checked:text-rose-400 transition block font-medium">
                                    Tất cả mức giá
                                </span>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="price_range" value="under_5m" @change="applyFilter()" class="hidden peer" {{ $currentPriceRange == 'under_5m' ? 'checked' : '' }}>
                                <span class="px-3 py-1.5 rounded-xl border border-slate-800 bg-slate-950 text-slate-400 peer-checked:border-rose-500 peer-checked:bg-rose-500/10 peer-checked:text-rose-400 transition block font-medium">
                                    Dưới 5 triệu
                                </span>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="price_range" value="5m_15m" @change="applyFilter()" class="hidden peer" {{ $currentPriceRange == '5m_15m' ? 'checked' : '' }}>
                                <span class="px-3 py-1.5 rounded-xl border border-slate-800 bg-slate-950 text-slate-400 peer-checked:border-rose-500 peer-checked:bg-rose-500/10 peer-checked:text-rose-400 transition block font-medium">
                                    5 - 15 triệu
                                </span>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="price_range" value="15m_25m" @change="applyFilter()" class="hidden peer" {{ $currentPriceRange == '15m_25m' ? 'checked' : '' }}>
                                <span class="px-3 py-1.5 rounded-xl border border-slate-800 bg-slate-950 text-slate-400 peer-checked:border-rose-500 peer-checked:bg-rose-500/10 peer-checked:text-rose-400 transition block font-medium">
                                    15 - 25 triệu
                                </span>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="price_range" value="above_25m" @change="applyFilter()" class="hidden peer" {{ $currentPriceRange == 'above_25m' ? 'checked' : '' }}>
                                <span class="px-3 py-1.5 rounded-xl border border-slate-800 bg-slate-950 text-slate-400 peer-checked:border-rose-500 peer-checked:bg-rose-500/10 peer-checked:text-rose-400 transition block font-medium">
                                    Trên 25 triệu
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Lọc RAM & Ổ cứng -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2 border-t border-slate-800/60">
                        <div class="space-y-2">
                            <span class="text-slate-400 font-semibold block">Dung lượng RAM:</span>
                            <div class="flex flex-wrap gap-2">
                                @foreach(['8GB', '16GB', '32GB'] as $ramVal)
                                    @php
                                        $checkedRam = is_array(request('ram')) ? in_array($ramVal, request('ram')) : request('ram') == $ramVal;
                                    @endphp
                                    <label class="cursor-pointer">
                                        <input type="checkbox" name="ram[]" value="{{ $ramVal }}" @change="applyFilter()" class="hidden peer" {{ $checkedRam ? 'checked' : '' }}>
                                        <span class="px-3 py-1.5 rounded-xl border border-slate-800 bg-slate-950 text-slate-400 peer-checked:border-cyan-500 peer-checked:bg-cyan-500/10 peer-checked:text-cyan-400 transition block font-mono font-bold">
                                            {{ $ramVal }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="space-y-2">
                            <span class="text-slate-400 font-semibold block">Ổ cứng / ROM:</span>
                            <div class="flex flex-wrap gap-2">
                                @foreach(['256GB', '512GB', '1TB'] as $storageVal)
                                    @php
                                        $checkedStorage = is_array(request('storage')) ? in_array($storageVal, request('storage')) : request('storage') == $storageVal;
                                    @endphp
                                    <label class="cursor-pointer">
                                        <input type="checkbox" name="storage[]" value="{{ $storageVal }}" @change="applyFilter()" class="hidden peer" {{ $checkedStorage ? 'checked' : '' }}>
                                        <span class="px-3 py-1.5 rounded-xl border border-slate-800 bg-slate-950 text-slate-400 peer-checked:border-cyan-500 peer-checked:bg-cyan-500/10 peer-checked:text-cyan-400 transition block font-mono font-bold">
                                            {{ $storageVal }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Lọc Thương hiệu -->
                    @if(isset($brands) && $brands->count() > 0)
                        <div class="space-y-2 pt-2 border-t border-slate-800/60">
                            <span class="text-slate-400 font-semibold block">Thương hiệu:</span>
                            <div class="flex flex-wrap gap-2 items-center">
                                @foreach($brands as $brand)
                                    @php
                                        $checked = is_array(request('brand')) ? in_array($brand->id, request('brand')) : request('brand') == $brand->id;
                                    @endphp
                                    <label class="cursor-pointer">
                                        <input type="checkbox" name="brand[]" value="{{ $brand->id }}" @change="applyFilter()" class="hidden peer" {{ $checked ? 'checked' : '' }}>
                                        <span class="px-3 py-1.5 rounded-xl border border-slate-800 bg-slate-950 text-slate-400 peer-checked:border-rose-500 peer-checked:bg-rose-500/10 peer-checked:text-rose-400 transition block font-semibold">
                                            {{ $brand->name }}
                                        </span>
                                    </label>
                                @endforeach

                                <button type="button" @click="resetFilters()" class="px-3 py-1.5 text-xs text-rose-400 hover:text-rose-300 hover:underline transition ml-auto font-semibold cursor-pointer">
                                    ✕ Xóa tất cả bộ lọc
                                </button>
                            </div>
                        </div>
                    @endif

                </div>
            </form>
        </div>

        <!-- KHU VỰC CHỨA SẢN PHẨM: ĐƯỢC CẬP NHẬT TỨC THÌ QUA AJAX -->
        <div id="productSection">
            @include('shop.partials.product-grid')
        </div>

    </div>
</x-store-layout>