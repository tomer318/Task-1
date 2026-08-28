<x-store-layout>
    @php
        $defaultVariant = $product->variants->first();
        $defaultPrice = $defaultVariant ? $defaultVariant->price : $product->price;
        $uniqueVersions = $product->variants->pluck('version_name')->unique();
        $uniqueColors = $product->variants->pluck('color_name')->unique();
        $isFavorited = Auth::check() && $product->isFavoritedBy(Auth::user());
    @endphp

    <style>
        /* Tùy biến Scrollbar chuẩn Dark Minimal */
        .custom-dark-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-dark-scrollbar::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.8);
            border-radius: 9999px;
        }
        .custom-dark-scrollbar::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 9999px;
        }
        .custom-dark-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #06b6d4;
        }
    </style>

    <div class="space-y-6 text-white" 
         x-data="{ 
            selectedImage: '{{ $product->image ? asset('storage/' . $product->image) : 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800' }}',
            selectedVersion: '{{ $defaultVariant?->version_name ?? 'Tiêu Chuẩn' }}',
            selectedColor: '{{ $defaultVariant?->color_name ?? 'Đen' }}',
            currentPrice: {{ $defaultPrice }},
            quantity: 1,
            showAllSpecsModal: false,
            showStickyBar: false,
            isFavorited: {{ $isFavorited ? 'true' : 'false' }},
            activeSpecTab: '{{ $groupedSpecs->keys()->first() ?? 'Cấu hình' }}',
            variants: {{ Js::from($product->variants) }},
            
            // Dữ liệu So Sánh với Search Dropdown thông minh
            showCompareModal: false,
            compareSearchQuery: '',
            showCompareDropdown: false,
            comparableList: {{ Js::from($comparableProducts ?? []) }},
            selectedCompareId: {{ $comparableProducts?->first()?->id ?? 'null' }},
            
            get targetProduct() {
                return this.comparableList.find(p => p.id == this.selectedCompareId) || null;
            },

            get filteredCompareList() {
                if (!this.compareSearchQuery.trim()) return this.comparableList;
                return this.comparableList.filter(item => 
                    item.name.toLowerCase().includes(this.compareSearchQuery.toLowerCase()) ||
                    (item.brand && item.brand.name.toLowerCase().includes(this.compareSearchQuery.toLowerCase()))
                );
            },

            selectCompareProduct(prod) {
                this.selectedCompareId = prod.id;
                this.compareSearchQuery = prod.name;
                this.showCompareDropdown = false;
            },

            init() {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        this.showStickyBar = !entry.isIntersecting;
                    });
                }, { threshold: 0.1 });

                if (this.$refs.mainBuyBtn) {
                    observer.observe(this.$refs.mainBuyBtn);
                }

                if (this.targetProduct) {
                    this.compareSearchQuery = this.targetProduct.name;
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
            },

            getSpecValue(prod, keyName) {
                if (!prod || !prod.specifications) return '—';
                let s = prod.specifications.find(item => item.spec_key.toLowerCase().trim() === keyName.toLowerCase().trim());
                return s ? s.spec_value : '—';
            },

            toggleWishlist() {
                @guest
                    window.location.href = '{{ route('login') }}';
                    return;
                @endguest

                fetch('{{ route('wishlist.toggle', $product->id) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data && data.success) {
                        this.isFavorited = data.is_favorited;
                        const badge = document.getElementById('wishlist-count');
                        if (badge) badge.innerText = data.count;
                    }
                })
                .catch(err => console.error(err));
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
                <button type="button" @click="toggleWishlist()" :class="isFavorited ? 'text-rose-500 font-bold' : 'text-slate-400 hover:text-white'" class="flex items-center gap-1.5 transition cursor-pointer">
                    <span x-text="isFavorited ? '❤️' : '🤍'"></span>
                    <span x-text="isFavorited ? 'Đã thích' : 'Yêu thích'"></span>
                </button>
                <button @click="showCompareModal = true" class="hover:text-cyan-400 flex items-center gap-1.5 transition text-cyan-500 font-semibold cursor-pointer">
                    <span>⚖️</span> So sánh
                </button>
                <button @click="showAllSpecsModal = true" class="hover:text-rose-400 flex items-center gap-1 transition cursor-pointer">⚙️ Thông số</button>
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
                    
                    <div class="w-full mt-6 p-4 bg-slate-950/70 border border-slate-800 rounded-2xl text-xs space-y-1.5 text-slate-300">
                        <div class="font-bold text-rose-500 uppercase tracking-wider text-[11px] mb-1">✨ TÍNH NĂNG NỔI BẬT</div>
                        <p class="leading-relaxed">{{ $product->description ?? 'Màn hình sắc nét, cấu hình hiệu năng cao, tối ưu pin bền bỉ và sạc nhanh tiện lợi.' }}</p>
                    </div>
                </div>

                <!-- Gallery Thumbnails -->
                <div class="flex items-center gap-3 overflow-x-auto pb-2 custom-dark-scrollbar">
                    @if($product->image)
                        <button type="button" @click="selectedImage = '{{ asset('storage/' . $product->image) }}'" 
                                class="w-16 h-16 rounded-xl bg-slate-900 border border-slate-800 hover:border-rose-500 p-1 shrink-0 transition flex items-center justify-center cursor-pointer">
                            <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-contain">
                        </button>
                    @endif
                    @foreach($product->images as $img)
                        <button type="button" @click="selectedImage = '{{ asset('storage/' . $img->image_path) }}'" 
                                class="w-16 h-16 rounded-xl bg-slate-900 border border-slate-800 hover:border-rose-500 p-1 shrink-0 transition flex items-center justify-center cursor-pointer">
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

                <!-- Tóm tắt Thông số kỹ thuật -->
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                        <h3 class="font-bold text-sm text-white flex items-center gap-2">
                            <span>⚙️</span> Thông số kỹ thuật
                        </h3>
                        <button @click="showAllSpecsModal = true" class="text-xs text-rose-400 hover:underline font-semibold flex items-center gap-1 cursor-pointer">
                            Xem tất cả thông số &gt;
                        </button>
                    </div>

                    <div class="divide-y divide-slate-800/60 text-xs">
                        @forelse($product->specifications->take(6) as $spec)
                            <div class="grid grid-cols-12 py-2.5">
                                <span class="col-span-5 text-slate-400">{{ $spec->spec_key }}</span>
                                <span class="col-span-7 font-semibold text-slate-200">{{ $spec->spec_value }}</span>
                            </div>
                        @empty
                            <div class="py-4 text-center text-slate-500">Thông số kỹ thuật đang được cập nhật</div>
                        @endforelse
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                        <button @click="showAllSpecsModal = true" class="py-2.5 bg-slate-950 border border-slate-800 hover:border-rose-500 text-slate-300 hover:text-white rounded-xl text-xs font-semibold transition cursor-pointer">
                            Xem cấu hình chi tiết
                        </button>
                        <button @click="showCompareModal = true" class="py-2.5 bg-slate-950 border border-cyan-500/40 hover:border-cyan-500 text-cyan-400 hover:text-cyan-300 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer shadow-lg shadow-cyan-500/10">
                            <span>⚖️</span> So sánh cấu hình
                        </button>
                    </div>
                </div>

            </div>

            <!-- CỘT PHẢI: Giá Tiền, Phiên bản, Khuyến mãi & Nút Mua -->
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
                                        class="p-2.5 rounded-xl border text-xs font-bold transition flex items-center justify-between cursor-pointer">
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
                                        class="p-2.5 rounded-xl border text-xs font-semibold transition text-center flex flex-col items-center gap-1 cursor-pointer">
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
                            <span>Giảm thêm <strong class="text-white">500.000₫</strong> khi thanh toán qua ZaloPay/VNPAY.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="w-4 h-4 rounded-full bg-rose-500/20 text-rose-400 flex items-center justify-center text-[10px] shrink-0 mt-0.5">2</span>
                            <span>Tặng phiếu mua hàng trị giá <strong class="text-white">300.000₫</strong> khi mua phụ kiện kèm theo.</span>
                        </li>
                    </ul>
                </div>

                <!-- Nút Mua hàng & Thao tác chính -->
                <div class="space-y-3 pt-2">
                    <form method="POST" action="{{ route('cart.add', $product) }}" class="space-y-3">
                        @csrf
                        <input type="hidden" name="quantity" :value="quantity">
                        <input type="hidden" name="version" :value="selectedVersion">
                        <input type="hidden" name="color" :value="selectedColor">

                        <button type="submit" 
                                x-ref="mainBuyBtn" 
                                class="w-full py-3.5 bg-gradient-to-r from-rose-600 to-red-500 hover:from-rose-500 hover:to-red-400 text-white font-extrabold rounded-2xl shadow-xl shadow-rose-600/30 transition text-sm flex flex-col items-center justify-center cursor-pointer">
                            <span>MUA NGAY</span>
                            <span class="text-[11px] font-normal opacity-90">Giao hàng tận nơi miễn phí toàn quốc</span>
                        </button>
                    </form>

                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" class="py-3 bg-slate-900 hover:bg-slate-800 border border-slate-800 text-white font-bold rounded-xl text-xs transition text-center flex flex-col items-center justify-center cursor-pointer">
                            <span>TRẢ GÓP 0%</span>
                            <span class="text-[10px] text-slate-400 font-normal">Duyệt hồ sơ nhanh</span>
                        </button>
                        
                        <form method="POST" action="{{ route('cart.add', $product) }}" class="w-full">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <input type="hidden" name="version" :value="selectedVersion">
                            <input type="hidden" name="color" :value="selectedColor">
                            <button type="submit" class="w-full h-full py-3 bg-slate-900 hover:bg-slate-800 border border-rose-500/40 text-rose-400 font-bold rounded-xl text-xs transition flex items-center justify-center gap-2 cursor-pointer">
                                <span>🛒</span> Thêm giỏ hàng
                            </button>
                        </form>
                    </div>

                    <!-- NÚT SO SÁNH SẢN PHẨM NỔI BẬT -->
                    <button type="button" 
                            @click="showCompareModal = true"
                            class="w-full py-3 bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 hover:from-cyan-950/40 hover:to-slate-900 border border-cyan-500/40 hover:border-cyan-400 text-cyan-400 font-extrabold rounded-2xl text-xs uppercase tracking-wider transition shadow-lg shadow-cyan-500/10 flex items-center justify-center gap-2 cursor-pointer">
                        <span class="text-base">⚖️</span>
                        <span>So Sánh Với Sản Phẩm Khác Cùng Loại</span>
                    </button>
                </div>

            </div>

        </div>

        <!-- ==================== MODAL SO SÁNH (LAYOUT MỚI: TÁCH RIÊNG SEARCH VÀ CUỘN BẢNG RÕ RÀNG) ==================== -->
        <div x-show="showCompareModal" class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4" style="display: none;">
            <div class="fixed inset-0 bg-black/85 backdrop-blur-md" @click="showCompareModal = false; showCompareDropdown = false;"></div>
            
            <div class="relative w-full max-w-5xl bg-slate-900 border border-slate-800 rounded-3xl p-5 sm:p-6 shadow-2xl space-y-4 max-h-[92vh] flex flex-col z-10 text-white">
                
                <!-- Modal Header -->
                <div class="flex items-center justify-between pb-3 border-b border-slate-800 shrink-0">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 flex items-center justify-center font-bold text-lg">
                            ⚖️
                        </div>
                        <div>
                            <h3 class="font-bold text-sm sm:text-base text-white">Bảng So Sánh Thông Số Đối Đầu</h3>
                            <p class="text-[11px] text-slate-400">Chọn sản phẩm so sánh để đối chiếu trực tiếp thông số</p>
                        </div>
                    </div>
                    <button @click="showCompareModal = false; showCompareDropdown = false;" class="text-slate-400 hover:text-white text-2xl font-bold cursor-pointer leading-none">&times;</button>
                </div>

                <!-- THANH TÌM KIẾM SẢN PHẨM SO SÁNH (MINIMAL SEARCH BAR) -->
                <div class="bg-slate-950/80 p-3 rounded-2xl border border-slate-800/80 shrink-0 relative" @click.away="showCompareDropdown = false">
                    <div class="relative flex items-center">
                        <!-- Icon Search bên trái input -->
                        <span class="absolute left-3.5 text-slate-400 text-xs pointer-events-none">🔍</span>
                        
                        <input type="text" 
                               x-model="compareSearchQuery"
                               @focus="showCompareDropdown = true"
                               @input="showCompareDropdown = true"
                               placeholder="Tìm kiếm thiết bị khác để so sánh..." 
                               class="w-full bg-slate-900 border border-slate-800 focus:border-cyan-500 rounded-xl pl-9 pr-10 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-cyan-500 transition shadow-inner">
                        
                        <!-- Nút Xóa nhanh / Mũi tên mở gọn gàng bên phải -->
                        <div class="absolute right-3 flex items-center gap-1.5">
                            <button type="button" 
                                    x-show="compareSearchQuery" 
                                    @click="compareSearchQuery = ''; showCompareDropdown = true" 
                                    class="text-slate-500 hover:text-rose-400 text-xs transition px-1 cursor-pointer"
                                    title="Xóa tìm kiếm">
                                ✕
                            </button>
                            <span class="text-slate-600 text-[10px] pointer-events-none" :class="showCompareDropdown ? 'rotate-180 transition transform' : 'transition transform'">▼</span>
                        </div>
                    </div>

                    <!-- Dropdown danh sách gợi ý -->
                    <div x-show="showCompareDropdown" 
                         class="absolute left-0 right-0 top-full mt-2 bg-slate-900 border border-slate-700/80 rounded-2xl shadow-2xl z-50 max-h-56 overflow-y-auto divide-y divide-slate-800/80 custom-dark-scrollbar backdrop-blur-md"
                         style="display: none;">
                        <template x-for="item in filteredCompareList" :key="item.id">
                            <div @click="selectCompareProduct(item)"
                                 class="p-2.5 hover:bg-cyan-950/40 cursor-pointer transition flex items-center justify-between gap-3"
                                 :class="selectedCompareId === item.id ? 'bg-cyan-950/30' : ''">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-slate-950 border border-slate-800 p-1 flex items-center justify-center shrink-0">
                                        <img :src="item.image ? ('/storage/' + item.image) : 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=200'" class="max-h-full object-contain">
                                    </div>
                                    <div>
                                        <div class="font-bold text-xs text-white line-clamp-1" x-text="item.name"></div>
                                        <div class="text-[10px] text-cyan-400 font-mono" x-text="item.brand ? item.brand.name : 'TECHZONE'"></div>
                                    </div>
                                </div>
                                <div class="font-mono font-bold text-rose-400 text-xs shrink-0" x-text="new Intl.NumberFormat('vi-VN').format(item.price) + '₫'"></div>
                            </div>
                        </template>
                        <template x-if="filteredCompareList.length === 0">
                            <div class="p-4 text-center text-slate-400 text-xs">Không tìm thấy sản phẩm phù hợp.</div>
                        </template>
                    </div>
                </div>

                <!-- BẢNG SO SÁNH 2 CỘT SONG SONG: LƯỚT TOÀN BỘ BẢNG MƯỢT MÀ BẰNG SCROLLBAR -->
                <div class="overflow-y-auto flex-1 pr-1.5 space-y-4 custom-dark-scrollbar text-xs">
                    
                    <!-- 1. Header Card so sánh 2 sản phẩm cố định -->
                    <div class="grid grid-cols-12 gap-3 p-4 bg-slate-950 rounded-2xl border border-slate-800/80 items-center sticky top-0 z-20 shadow-md">
                        <div class="col-span-3 font-bold text-slate-400 uppercase tracking-wider text-[11px]">Sản Phẩm</div>
                        
                        <!-- Cột Máy 1 (Hiện tại) -->
                        <div class="col-span-4 text-center space-y-1.5">
                            <div class="w-16 h-16 mx-auto bg-slate-900 rounded-xl p-1.5 border border-slate-800 flex items-center justify-center">
                                <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=400' }}" class="max-h-full object-contain">
                            </div>
                            <div class="font-bold text-white line-clamp-2">{{ $product->name }}</div>
                            <div class="font-mono font-black text-rose-500 text-xs sm:text-sm">{{ number_format($product->price, 0, ',', '.') }}₫</div>
                            <span class="inline-block px-2 py-0.5 bg-rose-500/10 text-rose-400 border border-rose-500/20 text-[9px] rounded font-bold">Đang xem</span>
                        </div>

                        <div class="col-span-1 text-center font-black text-slate-600 text-sm">VS</div>

                        <!-- Cột Máy 2 (Đối chiếu) -->
                        <div class="col-span-4 text-center space-y-1.5">
                            <template x-if="targetProduct">
                                <div class="space-y-1.5">
                                    <div class="w-16 h-16 mx-auto bg-slate-900 rounded-xl p-1.5 border border-slate-800 flex items-center justify-center">
                                        <img :src="targetProduct.image ? ('/storage/' + targetProduct.image) : 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=400'" class="max-h-full object-contain">
                                    </div>
                                    <div class="font-bold text-white line-clamp-2" x-text="targetProduct.name"></div>
                                    <div class="font-mono font-black text-cyan-400 text-xs sm:text-sm" x-text="new Intl.NumberFormat('vi-VN').format(targetProduct.price) + '₫'"></div>
                                    <a :href="'/product/' + targetProduct.slug" class="inline-block px-2.5 py-0.5 bg-cyan-600 hover:bg-cyan-500 text-white text-[9px] rounded font-bold transition">
                                        Xem trang máy &gt;
                                    </a>
                                </div>
                            </template>
                            <template x-if="!targetProduct">
                                <div class="py-6 text-slate-500 text-xs">Vui lòng chọn sản phẩm để so sánh</div>
                            </template>
                        </div>
                    </div>

                    <!-- 2. Danh sách so sánh từng thông số kỹ thuật (Dài bao nhiêu cuộn bấy nhiêu) -->
                    <div class="bg-slate-950 rounded-2xl border border-slate-800/80 divide-y divide-slate-800/60">
                        <div class="p-3 bg-slate-900/80 font-bold text-cyan-400 uppercase tracking-wider text-[11px] flex items-center justify-between">
                            <span>Chi Tiết Bảng Cấu Hình</span>
                            <span class="text-[10px] text-slate-400 font-normal">Cuộn để xem hết</span>
                        </div>

                        <!-- Thương hiệu -->
                        <div class="grid grid-cols-12 p-3 items-center">
                            <span class="col-span-3 font-semibold text-slate-400">Thương hiệu</span>
                            <span class="col-span-4 font-bold text-white">{{ $product->brand?->name ?? '—' }}</span>
                            <span class="col-span-1 text-center text-slate-600">•</span>
                            <span class="col-span-4 font-bold text-cyan-300" x-text="targetProduct?.brand?.name ?? '—'"></span>
                        </div>

                        <!-- Lặp qua tất cả thông số của sản phẩm -->
                        @foreach($product->specifications as $spec)
                            <div class="grid grid-cols-12 p-3 items-center hover:bg-slate-900/40 transition">
                                <span class="col-span-3 text-slate-400">{{ $spec->spec_key }}</span>
                                <span class="col-span-4 font-semibold text-slate-200">{{ $spec->spec_value }}</span>
                                <span class="col-span-1 text-center text-slate-600">•</span>
                                <span class="col-span-4 font-semibold text-slate-300" x-text="getSpecValue(targetProduct, '{{ $spec->spec_key }}')"></span>
                            </div>
                        @endforeach
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="pt-2 border-t border-slate-800 flex justify-end shrink-0">
                    <button @click="showCompareModal = false; showCompareDropdown = false;" class="px-6 py-2.5 bg-slate-800 hover:bg-slate-700 text-white font-bold rounded-xl text-xs transition cursor-pointer">
                        Đóng Bảng So Sánh
                    </button>
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
        @php
            $reviews = $product->reviews()->with('user')->latest()->get();
            $avgRating = $reviews->count() > 0 ? round($reviews->avg('rating'), 1) : 5.0;
        @endphp

        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-xl space-y-6"
             x-data="{ previewMedia: null, mediaType: 'image' }">
            <h2 class="font-bold text-base text-white pb-3 border-b border-slate-800 flex items-center justify-between">
                <span>Đánh giá & Nhận xét {{ $product->name }}</span>
                <span class="text-xs text-rose-400 font-mono">{{ $reviews->count() }} lượt đánh giá</span>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center p-4 bg-slate-950 rounded-2xl border border-slate-800">
                <div class="md:col-span-4 text-center md:border-r border-slate-800 pr-4 space-y-2">
                    <div class="text-4xl font-black text-rose-500">{{ number_format($avgRating, 1) }}<span class="text-base text-slate-500 font-normal">/5</span></div>
                    <div class="text-amber-400 text-sm">
                        @for($i = 1; $i <= 5; $i++)
                            <span>{{ $i <= round($avgRating) ? '★' : '☆' }}</span>
                        @endfor
                    </div>
                    <div class="text-xs text-slate-400">{{ $reviews->count() }} lượt đánh giá từ khách hàng</div>
                </div>
                <div class="md:col-span-8 space-y-2 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Chất lượng sản phẩm</span>
                        <span class="text-amber-400">★★★★★ 5/5</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Đúng với mô tả</span>
                        <span class="text-amber-400">★★★★★ 5/5</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Thời lượng pin / Độ bền</span>
                        <span class="text-amber-400">★★★★★ 5/5</span>
                    </div>
                </div>
            </div>

            <!-- Danh sách đánh giá -->
            <div class="space-y-4">
                @forelse($reviews as $rev)
                    <div class="p-4 bg-slate-950 border border-slate-800/80 rounded-2xl space-y-3">
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-rose-600/20 text-rose-400 font-bold flex items-center justify-center">
                                    {{ strtoupper(substr($rev->user->name ?? 'K', 0, 1)) }}
                                </div>
                                <div>
                                    <span class="font-bold text-white block">{{ $rev->user->name ?? 'Khách hàng' }}</span>
                                    <span class="text-slate-500 text-[10px]">{{ $rev->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                            <div class="text-amber-400 text-xs">
                                @for($i = 1; $i <= 5; $i++)
                                    <span>{{ $i <= $rev->rating ? '★' : '☆' }}</span>
                                @endfor
                            </div>
                        </div>

                        <p class="text-xs text-slate-300 leading-relaxed">{{ $rev->comment }}</p>

                        @if((is_array($rev->images) && count($rev->images) > 0) || $rev->video_path)
                            <div class="flex flex-wrap items-center gap-2 pt-1">
                                @if(is_array($rev->images))
                                    @foreach($rev->images as $img)
                                        <img src="{{ asset('storage/' . $img) }}" 
                                             @click="previewMedia = '{{ asset('storage/' . $img) }}'; mediaType = 'image'"
                                             class="w-16 h-16 object-cover rounded-xl border border-slate-800 hover:border-rose-500 cursor-pointer transition">
                                    @endforeach
                                @endif

                                @if($rev->video_path)
                                    <div @click="previewMedia = '{{ asset('storage/' . $rev->video_path) }}'; mediaType = 'video'"
                                         class="w-16 h-16 bg-slate-900 border border-slate-800 hover:border-rose-500 rounded-xl flex flex-col items-center justify-center cursor-pointer transition relative">
                                        <span class="text-xl">▶️</span>
                                        <span class="text-[9px] text-slate-400 font-semibold">Video</span>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="py-8 text-center text-xs text-slate-500">Chưa có đánh giá nào cho sản phẩm này. Hãy mua hàng và để lại đánh giá đầu tiên nhé!</div>
                @endforelse
            </div>

            <!-- Modal Phóng To Media -->
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 p-4" x-show="previewMedia" style="display: none;">
                <div class="relative max-w-2xl max-h-[85vh] flex flex-col items-center justify-center" @click.away="previewMedia = null">
                    <button @click="previewMedia = null" class="absolute -top-10 right-0 text-white font-bold text-2xl cursor-pointer">&times;</button>
                    
                    <template x-if="mediaType === 'image'">
                        <img :src="previewMedia" class="max-h-[80vh] rounded-2xl object-contain border border-slate-800">
                    </template>
                    <template x-if="mediaType === 'video'">
                        <video :src="previewMedia" controls autoplay class="max-h-[80vh] rounded-2xl border border-slate-800"></video>
                    </template>
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
                    <button @click="showAllSpecsModal = false" class="text-slate-400 hover:text-white text-lg cursor-pointer">✕</button>
                </div>

                <div class="flex items-center gap-2 overflow-x-auto pb-2 border-b border-slate-800 shrink-0 custom-dark-scrollbar text-xs font-semibold">
                    @foreach($groupedSpecs as $groupName => $specs)
                        <button type="button" 
                                @click="activeSpecTab = '{{ $groupName }}'"
                                :class="activeSpecTab === '{{ $groupName }}' ? 'text-rose-400 border-b-2 border-rose-500 pb-1.5' : 'text-slate-400 hover:text-slate-200'"
                                class="whitespace-nowrap px-2 transition cursor-pointer">
                            {{ $groupName }}
                        </button>
                    @endforeach
                </div>

                <div class="overflow-y-auto space-y-6 pr-2 custom-dark-scrollbar">
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
                        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-rose-600 to-red-500 hover:from-rose-500 hover:to-red-400 text-white font-bold rounded-xl text-xs shadow transition cursor-pointer">
                            MUA NGAY
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-store-layout>