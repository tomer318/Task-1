<x-store-layout>
    @php
        $defaultVariant = $product->variants->first();
        $defaultPrice = $defaultVariant ? $defaultVariant->price : $product->price;
        $uniqueVersions = $product->variants->pluck('version_name')->unique();
        $uniqueColors = $product->variants->pluck('color_name')->unique();
    @endphp

    <div class="space-y-6 text-white" 
         x-data="{ 
            selectedImage: '{{ $product->image ? asset('storage/' . $product->image) : 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800' }}',
            selectedVersion: '{{ $defaultVariant?->version_name ?? 'Tiêu Chuẩn' }}',
            selectedColor: '{{ $defaultVariant?->color_name ?? 'Đen' }}',
            currentPrice: {{ $defaultPrice }},
            quantity: 1,
            showAllSpecsModal: false,
            showStickyBar: false,
            activeSpecTab: '{{ $groupedSpecs->keys()->first() ?? 'Cấu hình' }}',
            variants: {{ Js::from($product->variants) }},
            
            init() {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        // Khi nút to KHÔNG còn trên màn hình thì hiện thanh ghim
                        this.showStickyBar = !entry.isIntersecting;
                    });
                }, { threshold: 0.1 });

                if (this.$refs.mainBuyBtn) {
                    observer.observe(this.$refs.mainBuyBtn);
                }
            },

            updateSelection(version, color) {
                this.selectedVersion = version;
                this.selectedColor = color;
                let found = this.variants.find(v => v.version_name === version && v.color_name === color);
                if (found) {
                    this.currentPrice = found.price;
                }
            },

            formatMoney(val) {
                return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(val);
            }
         }">

        <!-- Breadcrumb & Top Title Bar -->
        <div class="flex flex-wrap items-center justify-between gap-3 text-xs text-slate-400">
            <div class="flex items-center gap-2">
                <a href="/" class="hover:text-white transition">Trang chủ</a>
                <span>/</span>
                <a href="{{ route('shop.category', $product->category->slug) }}" class="hover:text-white transition">{{ $product->category->name }}</a>
                <span>/</span>
                <span class="text-rose-500 font-semibold">{{ $product->brand?->name }}</span>
            </div>
            <div class="flex items-center gap-4">
                <button class="hover:text-rose-400 flex items-center gap-1 transition">❤️ Yêu thích</button>
                <button class="hover:text-rose-400 flex items-center gap-1 transition">💬 Hỏi đáp</button>
                <button @click="showAllSpecsModal = true" class="hover:text-rose-400 flex items-center gap-1 transition">⚙️ Thông số</button>
                <button class="hover:text-rose-400 flex items-center gap-1 transition">⚖️ So sánh</button>
            </div>
        </div>

        <!-- Tên sản phẩm & Tag đánh giá -->
        <div class="flex flex-wrap items-center gap-3 border-b border-slate-800 pb-4">
            <h1 class="text-xl sm:text-2xl font-black tracking-tight text-white">{{ $product->name }}</h1>
            <span class="px-2.5 py-0.5 bg-rose-500/10 text-rose-400 border border-rose-500/20 text-xs font-bold rounded-lg">Hàng mới về</span>
            <div class="flex items-center gap-1 text-xs text-amber-400 font-semibold">
                <span>⭐ 5.0</span>
                <span class="text-slate-500">(4 đánh giá)</span>
            </div>
        </div>

        <!-- MAIN 2-COLUMN LAYOUT -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- CỘT TRÁI: Gallery & Tính năng nổi bật & Bảng tóm tắt thông số -->
            <div class="lg:col-span-7 space-y-6">
                
                <!-- Main Image Preview -->
                <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 relative overflow-hidden flex flex-col items-center justify-center min-h-[380px] shadow-2xl">
                    <img :src="selectedImage" alt="{{ $product->name }}" class="max-h-72 object-contain hover:scale-105 transition duration-300">
                    
                    <!-- Feature Tag Box (CellphoneS Highlight) -->
                    <div class="w-full mt-6 p-4 bg-slate-950/70 border border-slate-800 rounded-2xl text-xs space-y-1.5 text-slate-300">
                        <div class="font-bold text-rose-500 uppercase tracking-wider text-[11px] mb-1">✨ TÍNH NĂNG NỔI BẬT</div>
                        <p class="leading-relaxed">{{ $product->description ?? 'Màn hình sắc nét, cấu hình hiệu năng cao, tối ưu pin bền bỉ và sạc nhanh tiện lợi.' }}</p>
                    </div>
                </div>

                <!-- Gallery Thumbnails -->
                <div class="flex items-center gap-3 overflow-x-auto pb-2 scrollbar-none">
                    @if($product->image)
                        <button type="button" @click="selectedImage = '{{ asset('storage/' . $product->image) }}'" 
                                class="w-16 h-16 rounded-xl bg-slate-900 border border-slate-800 hover:border-rose-500 p-1 shrink-0 transition flex items-center justify-center">
                            <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-contain">
                        </button>
                    @endif
                    @foreach($product->images as $img)
                        <button type="button" @click="selectedImage = '{{ asset('storage/' . $img->image_path) }}'" 
                                class="w-16 h-16 rounded-xl bg-slate-900 border border-slate-800 hover:border-rose-500 p-1 shrink-0 transition flex items-center justify-center">
                            <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-contain">
                        </button>
                    @endforeach
                </div>

                <!-- Cam kết sản phẩm -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 pt-2">
                    <div class="p-3.5 bg-slate-900 border border-slate-800 rounded-2xl flex items-start gap-3">
                        <span class="text-xl text-rose-500">🛡️</span>
                        <div class="text-xs">
                            <strong class="text-white block font-semibold">Bảo hành 12 - 18 tháng</strong>
                            <span class="text-slate-400 text-[11px]">Tại trung tâm bảo hành chính hãng. 1 đổi 1 trong 30 ngày nếu lỗi.</span>
                        </div>
                    </div>
                    <div class="p-3.5 bg-slate-900 border border-slate-800 rounded-2xl flex items-start gap-3">
                        <span class="text-xl text-emerald-400">📦</span>
                        <div class="text-xs">
                            <strong class="text-white block font-semibold">Hộp phụ kiện đầy đủ</strong>
                            <span class="text-slate-400 text-[11px]">Máy mới nguyên seal, cáp sạc, củ sạc, cây lấy sim (hoặc adapter).</span>
                        </div>
                    </div>
                </div>

                <!-- Tóm tắt Thông số kỹ thuật (Spec Preview Box) -->
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                        <h3 class="font-bold text-sm text-white flex items-center gap-2">
                            <span>⚙️</span> Thông số kỹ thuật
                        </h3>
                        <button @click="showAllSpecsModal = true" class="text-xs text-rose-400 hover:underline font-semibold flex items-center gap-1">
                            Xem tất cả thông số &gt;
                        </button>
                    </div>

                    <div class="divide-y divide-slate-800/60 text-xs">
                        @forelse($product->specifications->take(7) as $spec)
                            <div class="grid grid-cols-12 py-2.5">
                                <span class="col-span-5 text-slate-400">{{ $spec->spec_key }}</span>
                                <span class="col-span-7 font-semibold text-slate-200">{{ $spec->spec_value }}</span>
                            </div>
                        @empty
                            <div class="py-4 text-center text-slate-500">Thông số kỹ thuật đang được cập nhật</div>
                        @endforelse
                    </div>

                    <button @click="showAllSpecsModal = true" class="w-full py-2.5 bg-slate-950 border border-slate-800 hover:border-rose-500 text-slate-300 hover:text-white rounded-xl text-xs font-semibold transition">
                        Xem cấu hình chi tiết
                    </button>
                </div>

            </div>

            <!-- CỘT PHẢI: Chọn Phiên bản, Màu sắc, Khuyến mãi & Nút Mua -->
            <div class="lg:col-span-5 space-y-5">
                
                <!-- Box Giá Tiền & Trả Góp -->
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-xl space-y-3">
                    <div class="flex items-baseline justify-between">
                        <div>
                            <span class="text-2xl sm:text-3xl font-black text-rose-500 font-mono" x-text="formatMoney(currentPrice)"></span>
                            <span class="text-xs text-slate-500 line-through ml-2" x-text="formatMoney(currentPrice * 1.15)"></span>
                        </div>
                        <div class="text-right">
                            <span class="text-[11px] text-slate-400 block">Hoặc trả góp từ</span>
                            <span class="text-xs font-bold text-amber-400 font-mono" x-text="formatMoney(currentPrice / 6) + '/tháng'"></span>
                        </div>
                    </div>
                    <div class="p-2.5 bg-slate-950/80 rounded-xl border border-slate-800/80 text-[11px] text-slate-300 flex items-center justify-between">
                        <span>🎓 Đặc quyền Học sinh - Sinh viên</span>
                        <span class="text-rose-400 font-bold">Giảm thêm 5%</span>
                    </div>
                </div>

                <!-- Chọn Phiên bản (RAM/Bộ nhớ) -->
                @if($uniqueVersions->count() > 0)
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Chọn phiên bản:</label>
                        <div class="grid grid-cols-2 gap-2.5">
                            @foreach($uniqueVersions as $version)
                                <button type="button" 
                                        @click="updateSelection('{{ $version }}', selectedColor)"
                                        :class="selectedVersion === '{{ $version }}' ? 'border-rose-500 bg-rose-500/10 text-white ring-1 ring-rose-500' : 'border-slate-800 bg-slate-900 text-slate-400 hover:border-slate-700'"
                                        class="p-2.5 rounded-xl border text-xs font-bold transition flex items-center justify-between">
                                    <span>{{ $version }}</span>
                                    <span x-show="selectedVersion === '{{ $version }}'" class="text-rose-500 text-xs">✓</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Chọn Màu sắc -->
                @if($uniqueColors->count() > 0)
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Chọn màu sắc:</label>
                        <div class="grid grid-cols-3 gap-2.5">
                            @foreach($uniqueColors as $color)
                                <button type="button" 
                                        @click="updateSelection(selectedVersion, '{{ $color }}')"
                                        :class="selectedColor === '{{ $color }}' ? 'border-rose-500 bg-rose-500/10 text-white ring-1 ring-rose-500' : 'border-slate-800 bg-slate-900 text-slate-400 hover:border-slate-700'"
                                        class="p-2.5 rounded-xl border text-xs font-semibold transition text-center flex flex-col items-center gap-1">
                                    <span class="w-3.5 h-3.5 rounded-full border border-slate-700 bg-slate-700 inline-block"></span>
                                    <span>{{ $color }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Khuyến mãi kèm theo -->
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 space-y-3 shadow-xl">
                    <div class="font-bold text-xs text-rose-400 flex items-center gap-2">
                        <span>🎁</span> KHUYẾN MÃI ĐẶC QUYỀN TECHZONE
                    </div>
                    <ul class="space-y-2 text-xs text-slate-300">
                        <li class="flex items-start gap-2">
                            <span class="w-4 h-4 rounded-full bg-rose-500/20 text-rose-400 flex items-center justify-center text-[10px] shrink-0 mt-0.5">1</span>
                            <span>Giảm thêm <strong class="text-white">500.000₫</strong> khi thanh toán qua mã QR Momo/VNPAY.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="w-4 h-4 rounded-full bg-rose-500/20 text-rose-400 flex items-center justify-center text-[10px] shrink-0 mt-0.5">2</span>
                            <span>Tặng phiếu mua hàng trị giá <strong class="text-white">300.000₫</strong> khi mua kèm tai nghe gaming/buds.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="w-4 h-4 rounded-full bg-rose-500/20 text-rose-400 flex items-center justify-center text-[10px] shrink-0 mt-0.5">3</span>
                            <span>Hỗ trợ trả góp 0% qua thẻ tín dụng hoặc cty tài chính lên đến 12 tháng.</span>
                        </li>
                    </ul>
                </div>

                <!-- Nút Mua hàng & Thêm Giỏ Hàng -->
                <div class="space-y-3 pt-2">
                    <form method="POST" action="{{ route('cart.add', $product) }}" class="space-y-3">
                        @csrf
                        <input type="hidden" name="quantity" :value="quantity">
                        <input type="hidden" name="version" :value="selectedVersion">
                        <input type="hidden" name="color" :value="selectedColor">

                        <button type="submit" 
                                x-ref="mainBuyBtn" 
                                class="w-full py-3.5 bg-gradient-to-r from-rose-600 to-red-500 hover:from-rose-500 hover:to-red-400 text-white font-extrabold rounded-2xl shadow-xl shadow-rose-600/30 transition text-sm flex flex-col items-center justify-center">
                            <span>MUA NGAY</span>
                            <span class="text-[11px] font-normal opacity-90">Giao hàng tận nơi miễn phí hoặc nhận tại cửa hàng</span>
                        </button>
                    </form>

                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" class="py-3 bg-slate-900 hover:bg-slate-800 border border-slate-800 text-white font-bold rounded-xl text-xs transition text-center flex flex-col items-center justify-center">
                            <span>TRẢ GÓP 0%</span>
                            <span class="text-[10px] text-slate-400 font-normal">Duyệt hồ sơ online</span>
                        </button>
                        
                        <form method="POST" action="{{ route('cart.add', $product) }}" class="w-full">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <input type="hidden" name="version" :value="selectedVersion">
                            <input type="hidden" name="color" :value="selectedColor">
                            <button type="submit" class="w-full h-full py-3 bg-slate-900 hover:bg-slate-800 border border-rose-500/40 text-rose-400 font-bold rounded-xl text-xs transition flex items-center justify-center gap-2">
                                <span>🛒</span> Thêm giỏ hàng
                            </button>
                        </form>
                    </div>
                </div>

            </div>

        </div>

        <!-- ==================== PHẦN SẢN PHẨM TƯƠNG TỰ ==================== -->
        @if($relatedProducts->count() > 0)
            <div class="pt-8 space-y-4">
                <h2 class="font-bold text-base text-white flex items-center gap-2">
                    <span>🔥</span> Có thể bạn cũng thích
                </h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
                    @foreach($relatedProducts as $rel)
                        <a href="{{ route('shop.product', $rel->slug) }}" class="bg-slate-900 border border-slate-800 hover:border-rose-500/50 rounded-2xl p-4 space-y-3 group transition flex flex-col justify-between">
                            <div class="h-32 flex items-center justify-center overflow-hidden">
                                <img src="{{ $rel->image ? asset('storage/' . $rel->image) : 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=400' }}" 
                                     alt="{{ $rel->name }}" class="max-h-full object-contain group-hover:scale-105 transition">
                            </div>
                            <div class="space-y-1">
                                <h3 class="text-xs font-bold text-slate-200 line-clamp-2 group-hover:text-rose-400 transition">{{ $rel->name }}</h3>
                                <div class="text-xs font-mono font-extrabold text-rose-500">{{ number_format($rel->price, 0, ',', '.') }}₫</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- ==================== ĐÁNH GIÁ & NHẬN XÉT ==================== -->
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-xl space-y-6">
            <h2 class="font-bold text-base text-white pb-3 border-b border-slate-800">
                Đánh giá {{ $product->name }}
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                <div class="md:col-span-4 text-center md:border-r border-slate-800 pr-4 space-y-2">
                    <div class="text-4xl font-black text-rose-500">5.0<span class="text-base text-slate-500 font-normal">/5</span></div>
                    <div class="text-amber-400 text-sm">★★★★★</div>
                    <div class="text-xs text-slate-400">4 lượt đánh giá từ khách hàng</div>
                </div>
                <div class="md:col-span-8 space-y-2 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Hiệu năng</span>
                        <span class="text-amber-400">★★★★★ 5/5</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Thời lượng pin / Độ bền</span>
                        <span class="text-amber-400">★★★★★ 5/5</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Màn hình / Thiết kế</span>
                        <span class="text-amber-400">★★★★★ 5/5</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== MODAL POPUP THÔNG SỐ KỸ THUẬT ==================== -->
        <div x-show="showAllSpecsModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
            <div class="fixed inset-0 bg-black/80 backdrop-blur-sm" @click="showAllSpecsModal = false"></div>
            
            <div class="relative w-full max-w-2xl bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-2xl space-y-5 max-h-[85vh] flex flex-col">
                <div class="flex items-center justify-between pb-3 border-b border-slate-800 shrink-0">
                    <h3 class="font-bold text-base text-white flex items-center gap-2">
                        <span>⚙️</span> Thông số kĩ thuật chi tiết
                    </h3>
                    <button @click="showAllSpecsModal = false" class="text-slate-400 hover:text-white text-lg">✕</button>
                </div>

                <!-- Tabs danh mục thông số -->
                <div class="flex items-center gap-2 overflow-x-auto pb-2 border-b border-slate-800 shrink-0 scrollbar-none text-xs font-semibold">
                    @foreach($groupedSpecs as $groupName => $specs)
                        <button type="button" 
                                @click="activeSpecTab = '{{ $groupName }}'"
                                :class="activeSpecTab === '{{ $groupName }}' ? 'text-rose-400 border-b-2 border-rose-500 pb-1.5' : 'text-slate-400 hover:text-slate-200'"
                                class="whitespace-nowrap px-2 transition">
                            {{ $groupName }}
                        </button>
                    @endforeach
                </div>

                <!-- Nội dung thông số theo Tab cuộn được -->
                <div class="overflow-y-auto space-y-6 pr-2">
                    @foreach($groupedSpecs as $groupName => $specs)
                        <div x-show="activeSpecTab === '{{ $groupName }}'" class="space-y-3">
                            <h4 class="font-bold text-xs text-rose-500 uppercase tracking-wider">{{ $groupName }}</h4>
                            <div class="bg-slate-950 border border-slate-800/80 rounded-2xl divide-y divide-slate-800/60 text-xs">
                                @foreach($specs as $item)
                                    <div class="grid grid-cols-12 p-3 gap-3">
                                        <span class="col-span-5 text-slate-400">{{ $item->spec_key }}</span>
                                        <span class="col-span-7 font-medium text-slate-200">{{ $item->spec_value }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- ==================== STICKY BOTTOM BAR ==================== -->
        <div x-show="showStickyBar" 
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-y-full opacity-0"
             x-transition:enter-end="translate-y-0 opacity-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-y-0 opacity-100"
             x-transition:leave-end="translate-y-full opacity-0"
             style="display: none;"
             class="fixed bottom-0 inset-x-0 bg-slate-950/95 backdrop-blur-md border-t border-slate-800 p-3 z-40 hidden md:block shadow-2xl">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img :src="selectedImage" class="w-10 h-10 object-contain rounded-lg bg-slate-900 border border-slate-800 p-1">
                    <div>
                        <div class="text-xs font-bold text-white">{{ $product->name }}</div>
                        <div class="text-[11px] text-slate-400">
                            Phiên bản: <span class="text-rose-400 font-semibold" x-text="selectedVersion + ' - ' + selectedColor"></span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <span class="text-base font-black text-rose-500 font-mono" x-text="formatMoney(currentPrice)"></span>
                    </div>
                    <form method="POST" action="{{ route('cart.add', $product) }}">
                        @csrf
                        <input type="hidden" name="quantity" value="1">
                        <input type="hidden" name="version" :value="selectedVersion">
                        <input type="hidden" name="color" :value="selectedColor">
                        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-rose-600 to-red-500 hover:from-rose-500 hover:to-red-400 text-white font-bold rounded-xl text-xs shadow transition">
                            MUA NGAY
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-store-layout>