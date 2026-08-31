<x-store-layout>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 text-white space-y-6"
         x-data="{
             selectedItems: {{ Js::from(array_keys(session('cart', []))) }},
             cartItems: {{ Js::from(session('cart', [])) }},
             coupon: {{ Js::from(session('coupon')) }},
             shippingCoupon: {{ Js::from(session('shipping_coupon')) }},
             availableCoupons: {{ Js::from($allCoupons ?? []) }},
             showVoucherModal: false,
             inputVoucherCode: '',
             shippingSpeed: 'normal',
             memberRank: '{{ $memberRank ?? 'M-NULL' }}',
             
             get selectAll() {
                 return this.selectedItems.length === Object.keys(this.cartItems).length && Object.keys(this.cartItems).length > 0;
             },
             set selectAll(value) {
                 if (value) {
                     this.selectedItems = Object.keys(this.cartItems);
                 } else {
                     this.selectedItems = [];
                 }
             },

             get totalQuantity() {
                 let qty = 0;
                 for (let key of this.selectedItems) {
                     if (this.cartItems[key]) {
                         qty += parseInt(this.cartItems[key].quantity);
                     }
                 }
                 return qty;
             },

             get totalPrice() {
                 let total = 0;
                 for (let key of this.selectedItems) {
                     if (this.cartItems[key]) {
                         total += parseFloat(this.cartItems[key].price) * parseInt(this.cartItems[key].quantity);
                     }
                 }
                 return total;
             },

             // 1. Giảm giá từ Order Coupon
             get discountAmount() {
                 if (!this.coupon || this.totalPrice === 0) return 0;
                 if (this.totalPrice < parseFloat(this.coupon.min_order_value || 0)) return 0;

                 let disc = (this.coupon.type === 'percent') 
                     ? (this.totalPrice * parseFloat(this.coupon.value)) / 100 
                     : parseFloat(this.coupon.value);

                 return Math.min(this.totalPrice, disc);
             },

             // 2. Phí ship cơ bản & Ưu đãi Ship Siêu Tốc
             get rawShippingFee() {
                 if (this.totalPrice === 0) return 0;
                 if (this.shippingSpeed === 'express') return 120000;
                 return this.totalPrice >= 300000 ? 0 : 30000;
             },

             // 3. Giảm giá từ Shipping Coupon
             get shippingDiscountAmount() {
                 if (!this.shippingCoupon || this.totalPrice === 0 || this.rawShippingFee === 0) return 0;
                 if (this.totalPrice < parseFloat(this.shippingCoupon.min_order_value || 0)) return 0;

                 let sDisc = (this.shippingCoupon.type === 'percent') 
                     ? (this.rawShippingFee * parseFloat(this.shippingCoupon.value)) / 100 
                     : parseFloat(this.shippingCoupon.value);

                 return Math.min(this.rawShippingFee, sDisc);
             },

             get finalShippingFee() {
                 return Math.max(0, this.rawShippingFee - this.shippingDiscountAmount);
             },

             // 4. Tổng thanh toán = Tiền hàng - Giảm giá đơn + Phí ship sau giảm
             get finalTotal() {
                 if (this.totalPrice === 0) return 0;
                 return Math.max(0, this.totalPrice - this.discountAmount + this.finalShippingFee);
             },

             formatMoney(val) {
                 return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(val);
             },

             isShippingCoupon(code) {
                 return code.startsWith('SHIP') || code.startsWith('FAST');
             }
         }">

        <!-- Top Navigation / Breadcrumb -->
        <div class="flex items-center justify-between text-xs text-slate-400 pb-3 border-b border-slate-800">
            <div class="flex items-center gap-2">
                <a href="{{ url('/') }}" class="text-rose-500 font-semibold hover:underline flex items-center gap-1">&lt; Tiếp tục mua sắm</a>
                <span>/</span>
                <span class="text-white">Giỏ hàng của bạn</span>
            </div>
            <div class="hidden sm:block text-emerald-400 font-semibold">
                📦 Miễn phí vận chuyển tiêu chuẩn với đơn hàng từ 300.000₫
            </div>
        </div>

        <!-- Flash Message -->
        @if (session('success'))
            <div class="flex items-center gap-3 bg-emerald-950/60 border border-emerald-800 text-emerald-300 px-4 py-3 rounded-2xl text-xs">
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="flex items-center gap-3 bg-rose-950/60 border border-rose-800 text-rose-300 px-4 py-3 rounded-2xl text-xs">
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(session('cart') && count(session('cart')) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- CỘT TRÁI: Danh sách sản phẩm trong giỏ -->
                <div class="lg:col-span-8 space-y-4">
                    
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 flex items-center justify-between text-xs shadow-xl">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" x-model="selectAll" class="w-4 h-4 rounded text-rose-600 bg-slate-950 border-slate-800 focus:ring-0">
                            <span class="font-bold text-white">Tất cả ({{ count(session('cart')) }})</span>
                        </label>
                        <form method="POST" action="{{ route('cart.clear') }}" onsubmit="return confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-rose-400 hover:text-rose-300 font-semibold cursor-pointer">Xóa tất cả</button>
                        </form>
                    </div>

                    @foreach(session('cart') as $key => $item)
                        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-5 space-y-4 shadow-xl relative">
                            <div class="flex items-start gap-4">
                                <input type="checkbox" value="{{ $key }}" x-model="selectedItems" class="w-4 h-4 rounded text-rose-600 bg-slate-950 border-slate-800 focus:ring-0 mt-2">

                                <div class="w-20 h-20 bg-slate-950 border border-slate-800 rounded-2xl p-2 shrink-0 flex items-center justify-center">
                                    <img src="{{ $item['image'] ? asset('storage/' . $item['image']) : 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=400' }}" 
                                         alt="{{ $item['name'] }}" class="max-h-full object-contain">
                                </div>

                                <div class="flex-1 space-y-1">
                                    <div class="flex items-start justify-between gap-2">
                                        <h3 class="text-xs sm:text-sm font-bold text-white">
                                            {{ $item['name'] }} | <span class="text-rose-400">{{ $item['version'] }} - {{ $item['color'] }}</span>
                                        </h3>
                                        
                                        <form method="POST" action="{{ route('cart.remove', $key) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-slate-500 hover:text-rose-400 text-base transition cursor-pointer" title="Xóa sản phẩm">🗑️</button>
                                        </form>
                                    </div>

                                    <div class="flex items-baseline gap-2 pt-1">
                                        <span class="text-sm sm:text-base font-black text-rose-500 font-mono">{{ number_format($item['price'], 0, ',', '.') }}₫</span>
                                        <span class="text-[11px] text-slate-500 line-through font-mono">{{ number_format($item['price'] * 1.15, 0, ',', '.') }}₫</span>
                                    </div>
                                </div>
                            </div>

                            <div class="p-3 bg-slate-950/70 border border-slate-800/80 rounded-2xl text-[11px] space-y-1.5 text-slate-300">
                                <div class="font-bold text-rose-400 flex items-center gap-1.5">
                                    <span>🎁</span> Khuyến mãi đi kèm:
                                </div>
                                <p class="text-slate-400 pl-5">Chỉ thêm 30K - Nhận sim/eSIM 5G VNSKY, có ngay 3GB data/ngày + 500 phút gọi nội mạng.</p>
                            </div>

                            <div class="flex items-center justify-between pt-2 border-t border-slate-800/80 text-xs">
                                <span class="text-slate-400">Số lượng:</span>
                                <div class="flex items-center border border-slate-800 bg-slate-950 rounded-xl overflow-hidden font-mono">
                                    <form method="POST" action="{{ route('cart.update', $key) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="quantity" value="{{ $item['quantity'] - 1 }}">
                                        <button type="submit" class="px-3 py-1.5 text-slate-400 hover:text-white hover:bg-slate-800 transition cursor-pointer">-</button>
                                    </form>
                                    <span class="px-3 py-1.5 font-bold text-white">{{ $item['quantity'] }}</span>
                                    <form method="POST" action="{{ route('cart.update', $key) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                                        <button type="submit" class="px-3 py-1.5 text-slate-400 hover:text-white hover:bg-slate-800 transition cursor-pointer">+</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- CỘT PHẢI: Khối Voucher Kép & Tổng tiền -->
                <div class="lg:col-span-4 space-y-4">
                    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-xl space-y-5 sticky top-24">
                        <h2 class="font-bold text-sm text-white pb-3 border-b border-slate-800">Thông tin đơn hàng</h2>

                        <!-- KHỐI ƯU ĐÃI & POPUP CHỌN VOUCHER -->
                        <div class="bg-slate-950 border border-slate-800 rounded-2xl p-4 space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="text-xs font-bold text-slate-200 uppercase tracking-wider flex items-center gap-1.5">
                                    <span class="text-rose-500">🎫</span> Mã Giảm Giá & Ưu Đãi
                                </div>
                                <button type="button" @click="showVoucherModal = true" class="text-xs text-rose-400 hover:text-rose-300 font-bold hover:underline cursor-pointer flex items-center gap-1">
                                    <span>Chọn Voucher</span> &gt;
                                </button>
                            </div>

                            <!-- 1. Voucher Đơn Hàng Đang Áp Dụng -->
                            @if (session('coupon'))
                                <div class="flex items-center justify-between bg-rose-950/30 border border-rose-800/50 p-2.5 rounded-xl text-xs">
                                    <div>
                                        <span class="px-1.5 py-0.5 rounded bg-rose-500/20 text-rose-400 font-mono font-bold text-[10px]">ĐƠN HÀNG</span>
                                        <span class="font-mono font-bold text-white ml-1">{{ session('coupon')['code'] }}</span>
                                        <div class="text-[10px] text-slate-400 mt-0.5">
                                            Giảm: {{ session('coupon')['type'] === 'percent' ? session('coupon')['value'] . '%' : number_format(session('coupon')['value'], 0, ',', '.') . '₫' }}
                                        </div>
                                    </div>
                                    <form action="{{ route('cart.coupon.remove') }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="type" value="order">
                                        <button type="submit" class="text-slate-400 hover:text-rose-400 text-xs underline cursor-pointer">Gỡ</button>
                                    </form>
                                </div>
                            @endif

                            <!-- 2. Voucher Ship Nhanh Đang Áp Dụng -->
                            @if (session('shipping_coupon'))
                                <div class="flex items-center justify-between bg-cyan-950/30 border border-cyan-800/50 p-2.5 rounded-xl text-xs">
                                    <div>
                                        <span class="px-1.5 py-0.5 rounded bg-cyan-500/20 text-cyan-400 font-mono font-bold text-[10px]">SHIP SIÊU TỐC</span>
                                        <span class="font-mono font-bold text-white ml-1">{{ session('shipping_coupon')['code'] }}</span>
                                        <div class="text-[10px] text-slate-400 mt-0.5">
                                            Giảm: {{ session('shipping_coupon')['type'] === 'percent' ? session('shipping_coupon')['value'] . '%' : number_format(session('shipping_coupon')['value'], 0, ',', '.') . '₫' }} phí hỏa tốc
                                        </div>
                                    </div>
                                    <form action="{{ route('cart.coupon.remove') }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="type" value="shipping">
                                        <button type="submit" class="text-slate-400 hover:text-rose-400 text-xs underline cursor-pointer">Gỡ</button>
                                    </form>
                                </div>
                            @endif

                            @if (!session('coupon') && !session('shipping_coupon'))
                                <div class="text-[11px] text-slate-400 flex items-center justify-between pt-1">
                                    <span>Bạn có thể áp dụng cùng lúc 2 voucher</span>
                                    <button type="button" @click="showVoucherModal = true" class="text-rose-400 font-bold hover:underline cursor-pointer">Bấm để chọn</button>
                                </div>
                            @endif
                        </div>

                        <!-- Chi tiết giá tiền -->
                        <div class="space-y-2.5 text-xs text-slate-300">
                            <div class="flex justify-between">
                                <span class="text-slate-400">Số lượng sản phẩm:</span>
                                <span class="font-mono font-bold text-white" x-text="totalQuantity"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400">Tổng tiền hàng:</span>
                                <span class="font-mono font-bold text-white" x-text="formatMoney(totalPrice)"></span>
                            </div>
                            
                            <!-- Giảm giá đơn hàng -->
                            <template x-if="discountAmount > 0">
                                <div class="flex justify-between text-emerald-400">
                                    <span>Giảm giá đơn hàng:</span>
                                    <span class="font-mono font-bold" x-text="'-' + formatMoney(discountAmount)"></span>
                                </div>
                            </template>

                            <!-- Giảm giá ship siêu tốc -->
                            <template x-if="shippingDiscountAmount > 0">
                                <div class="flex justify-between text-cyan-400">
                                    <span>Ưu đãi phí vận chuyển:</span>
                                    <span class="font-mono font-bold" x-text="'-' + formatMoney(shippingDiscountAmount)"></span>
                                </div>
                            </template>

                            <div class="flex justify-between items-center">
                                <span class="text-slate-400">Phí vận chuyển tạm tính:</span>
                                <span class="font-mono font-bold" 
                                      :class="finalShippingFee === 0 ? 'text-emerald-400' : 'text-amber-400'"
                                      x-text="finalShippingFee === 0 ? 'Miễn phí' : formatMoney(finalShippingFee)">
                                </span>
                            </div>
                        </div>

                        <!-- Tổng tiền thanh toán -->
                        <div class="pt-4 border-t border-slate-800 space-y-1">
                            <div class="flex items-baseline justify-between">
                                <span class="text-xs font-bold text-white">TỔNG TIỀN:</span>
                                <span class="text-xl font-black text-rose-500 font-mono" x-text="formatMoney(finalTotal)"></span>
                            </div>
                            <span class="text-[10px] text-slate-500 block text-right">(Đã bao gồm thuế VAT và ưu đãi kép)</span>
                        </div>

                        <!-- Nút Mua ngay -->
                        <a href="{{ url('/checkout') }}" class="block w-full py-4 bg-gradient-to-r from-rose-600 to-red-500 hover:from-rose-500 hover:to-red-400 text-white font-extrabold rounded-2xl text-center shadow-xl shadow-rose-600/30 transition text-sm">
                            MUA NGAY (<span x-text="totalQuantity"></span>)
                            <span class="block text-[10px] font-normal opacity-90">Giao nhanh từ 2 giờ hoặc nhận tại cửa hàng</span>
                        </a>
                    </div>
                </div>

            </div>
        @else
            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-12 text-center space-y-4 shadow-xl">
                <div class="text-5xl">🛒</div>
                <h2 class="text-base font-bold text-white">Giỏ hàng của bạn đang trống</h2>
                <p class="text-xs text-slate-400">Hãy chọn thêm sản phẩm vào giỏ hàng để tiếp tục mua sắm nhé!</p>
                <a href="{{ url('/') }}" class="inline-block px-6 py-3 bg-gradient-to-r from-rose-600 to-red-500 text-white font-bold rounded-xl text-xs shadow-lg transition">
                    Khám phá sản phẩm ngay
                </a>
            </div>
        @endif

        <!-- ==================== POPUP MODAL CHỌN VOUCHER KHẢ DỤNG & KHÔNG KHẢ DỤNG ==================== -->
        <div x-show="showVoucherModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
            <div class="fixed inset-0 bg-black/85 backdrop-blur-md" @click="showVoucherModal = false"></div>

            <div class="relative w-full max-w-xl bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-2xl space-y-5 max-h-[90vh] flex flex-col z-10 text-white"
                 x-data="{ voucherTab: 'applicable' }">
                
                <!-- Header -->
                <div class="flex items-center justify-between pb-3 border-b border-slate-800 shrink-0">
                    <div class="flex items-center gap-2">
                        <span class="text-lg text-rose-500">🎁</span>
                        <h3 class="font-bold text-base text-white">Chọn Voucher Khuyến Mãi</h3>
                    </div>
                    <button @click="showVoucherModal = false" class="text-slate-400 hover:text-white text-xl cursor-pointer">&times;</button>
                </div>

                <!-- Input nhập mã trực tiếp -->
                <form action="{{ route('cart.coupon.apply') }}" method="POST" class="flex gap-2 shrink-0">
                    @csrf
                    <input type="text" name="code" placeholder="Nhập mã ưu đãi khác..." required 
                           class="flex-1 bg-slate-950 border border-slate-800 focus:border-rose-500 rounded-xl px-3.5 py-2 text-xs text-white uppercase font-mono">
                    <button type="submit" class="px-5 py-2 bg-gradient-to-r from-rose-600 to-red-500 hover:from-rose-500 text-white font-bold rounded-xl text-xs shadow transition cursor-pointer">
                        Áp Dụng
                    </button>
                </form>

                <!-- 2 Tabs: Khả dụng & Không khả dụng -->
                <div class="flex border-b border-slate-800 text-xs font-bold shrink-0">
                    <button type="button" @click="voucherTab = 'applicable'" 
                            :class="voucherTab === 'applicable' ? 'text-rose-400 border-b-2 border-rose-500 pb-2.5' : 'text-slate-400 hover:text-white pb-2.5'"
                            class="flex-1 text-center transition cursor-pointer">
                        Voucher Khả Dụng
                    </button>
                    <button type="button" @click="voucherTab = 'inapplicable'" 
                            :class="voucherTab === 'inapplicable' ? 'text-rose-400 border-b-2 border-rose-500 pb-2.5' : 'text-slate-400 hover:text-white pb-2.5'"
                            class="flex-1 text-center transition cursor-pointer">
                        Chưa Đủ Điều Kiện
                    </button>
                </div>

                <!-- Danh sách Voucher -->
                <div class="overflow-y-auto flex-1 space-y-3 pr-1 scrollbar-none text-xs">
                    
                    <!-- TAB 1: KHẢ DỤNG -->
                    <div x-show="voucherTab === 'applicable'" class="space-y-3">
                        <template x-for="v in availableCoupons.filter(c => totalPrice >= parseFloat(c.min_order_value || 0))" :key="v.id">
                            <div class="p-3.5 bg-slate-950 border border-slate-800 hover:border-rose-500/50 rounded-2xl flex items-center justify-between gap-3 transition">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 rounded font-mono font-bold text-[10px]"
                                              :class="isShippingCoupon(v.code) ? 'bg-cyan-500/20 text-cyan-400 border border-cyan-500/30' : 'bg-rose-500/20 text-rose-400 border border-rose-500/30'"
                                              x-text="isShippingCoupon(v.code) ? 'SHIP SIÊU TỐC' : 'GIẢM ĐƠN HÀNG'"></span>
                                        <span class="font-mono font-bold text-white text-xs" x-text="v.code"></span>
                                    </div>
                                    <div class="font-semibold text-white text-xs" x-text="'Giảm ' + (v.type === 'percent' ? v.value + '%' : new Intl.NumberFormat('vi-VN').format(v.value) + '₫')"></div>
                                    <div class="text-[10px] text-slate-400" x-text="'Áp dụng cho đơn từ ' + new Intl.NumberFormat('vi-VN').format(v.min_order_value) + '₫'"></div>
                                </div>

                                <form action="{{ route('cart.coupon.apply') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="code" :value="v.code">
                                    <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-500 text-white font-bold rounded-xl text-xs transition cursor-pointer">
                                        Dùng Ngay
                                    </button>
                                </form>
                            </div>
                        </template>

                        <template x-if="availableCoupons.filter(c => totalPrice >= parseFloat(c.min_order_value || 0)).length === 0">
                            <div class="py-8 text-center text-slate-500 text-xs">Hiện tại không có voucher nào phù hợp cho giỏ hàng hiện tại.</div>
                        </template>
                    </div>

                    <!-- TAB 2: CHƯA ĐỦ ĐIỀU KIỆN -->
                    <div x-show="voucherTab === 'inapplicable'" style="display: none;" class="space-y-3">
                        <template x-for="v in availableCoupons.filter(c => totalPrice < parseFloat(c.min_order_value || 0))" :key="v.id">
                            <div class="p-3.5 bg-slate-950/60 border border-slate-800/80 rounded-2xl flex items-center justify-between gap-3 opacity-60">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 rounded bg-slate-800 text-slate-400 font-mono font-bold text-[10px]"
                                              x-text="isShippingCoupon(v.code) ? 'SHIP SIÊU TỐC' : 'GIẢM ĐƠN HÀNG'"></span>
                                        <span class="font-mono font-bold text-slate-300 text-xs" x-text="v.code"></span>
                                    </div>
                                    <div class="font-semibold text-slate-300 text-xs" x-text="'Giảm ' + (v.type === 'percent' ? v.value + '%' : new Intl.NumberFormat('vi-VN').format(v.value) + '₫')"></div>
                                    <div class="text-[10px] text-rose-400" x-text="'Mua thêm ' + new Intl.NumberFormat('vi-VN').format(v.min_order_value - totalPrice) + '₫ để mở khóa'"></div>
                                </div>

                                <button type="button" disabled class="px-3.5 py-1.5 bg-slate-900 border border-slate-800 text-slate-500 rounded-xl text-xs font-semibold cursor-not-allowed">
                                    Chưa đủ điều kiện
                                </button>
                            </div>
                        </template>
                    </div>

                </div>

                <!-- Footer -->
                <div class="pt-2 border-t border-slate-800 flex justify-end shrink-0">
                    <button type="button" @click="showVoucherModal = false" class="px-6 py-2.5 bg-slate-800 hover:bg-slate-700 text-white font-bold rounded-xl text-xs transition cursor-pointer">
                        Đóng
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-store-layout>