<x-store-layout>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 text-white space-y-6"
         x-data="{
             selectedItems: {{ Js::from(array_keys(session('cart', []))) }},
             cartItems: {{ Js::from(session('cart', [])) }},
             
             // Kiểm tra xem đã chọn tất cả chưa
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

             // Tính tổng số lượng sản phẩm được chọn
             get totalQuantity() {
                 let qty = 0;
                 for (let key of this.selectedItems) {
                     if (this.cartItems[key]) {
                         qty += parseInt(this.cartItems[key].quantity);
                     }
                 }
                 return qty;
             },

             // Tính tổng tiền hàng dựa trên các item được tick chọn
             get totalPrice() {
                 let total = 0;
                 for (let key of this.selectedItems) {
                     if (this.cartItems[key]) {
                         total += parseFloat(this.cartItems[key].price) * parseInt(this.cartItems[key].quantity);
                     }
                 }
                 return total;
             },

             formatMoney(val) {
                 return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(val);
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
                📦 Miễn phí vận chuyển với đơn hàng từ 300.000₫
            </div>
        </div>

        @if(session('cart') && count(session('cart')) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- CỘT TRÁI: Danh sách sản phẩm trong giỏ -->
                <div class="lg:col-span-8 space-y-4">
                    
                    <!-- Chọn tất cả bar -->
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 flex items-center justify-between text-xs shadow-xl">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" x-model="selectAll" class="w-4 h-4 rounded text-rose-600 bg-slate-950 border-slate-800 focus:ring-0">
                            <span class="font-bold text-white">Tất cả ({{ count(session('cart')) }})</span>
                        </label>
                        <form method="POST" action="{{ route('cart.clear') }}" onsubmit="return confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-rose-400 hover:text-rose-300 font-semibold">Xóa tất cả</button>
                        </form>
                    </div>

                    <!-- Danh sách các item -->
                    @foreach(session('cart') as $key => $item)
                        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-5 space-y-4 shadow-xl relative">
                            <div class="flex items-start gap-4">
                                <!-- Checkbox chọn item -->
                                <input type="checkbox" value="{{ $key }}" x-model="selectedItems" class="w-4 h-4 rounded text-rose-600 bg-slate-950 border-slate-800 focus:ring-0 mt-2">

                                <!-- Ảnh sản phẩm -->
                                <div class="w-20 h-20 bg-slate-950 border border-slate-800 rounded-2xl p-2 shrink-0 flex items-center justify-center">
                                    <img src="{{ $item['image'] ? asset('storage/' . $item['image']) : 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=400' }}" 
                                         alt="{{ $item['name'] }}" class="max-h-full object-contain">
                                </div>

                                <!-- Tên & Giá -->
                                <div class="flex-1 space-y-1">
                                    <div class="flex items-start justify-between gap-2">
                                        <h3 class="text-xs sm:text-sm font-bold text-white">
                                            {{ $item['name'] }} | <span class="text-rose-400">{{ $item['version'] }} - {{ $item['color'] }}</span>
                                        </h3>
                                        
                                        <!-- Nút Xóa item -->
                                        <form method="POST" action="{{ route('cart.remove', $key) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-slate-500 hover:text-rose-400 text-base transition" title="Xóa sản phẩm">🗑️</button>
                                        </form>
                                    </div>

                                    <div class="flex items-baseline gap-2 pt-1">
                                        <span class="text-sm sm:text-base font-black text-rose-500 font-mono">{{ number_format($item['price'], 0, ',', '.') }}₫</span>
                                        <span class="text-[11px] text-slate-500 line-through font-mono">{{ number_format($item['price'] * 1.15, 0, ',', '.') }}₫</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Khuyến mãi kèm theo trong giỏ -->
                            <div class="p-3 bg-slate-950/70 border border-slate-800/80 rounded-2xl text-[11px] space-y-1.5 text-slate-300">
                                <div class="font-bold text-rose-400 flex items-center gap-1.5">
                                    <span>🎁</span> Khuyến mãi đi kèm:
                                </div>
                                <p class="text-slate-400 pl-5">Chỉ thêm 30K - Nhận sim/eSIM 5G VNSKY, có ngay 3GB data/ngày + 500 phút gọi nội mạng.</p>
                            </div>

                            <!-- Tăng giảm số lượng -->
                            <div class="flex items-center justify-between pt-2 border-t border-slate-800/80 text-xs">
                                <span class="text-slate-400">Số lượng:</span>
                                <div class="flex items-center border border-slate-800 bg-slate-950 rounded-xl overflow-hidden font-mono">
                                    <form method="POST" action="{{ route('cart.update', $key) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="quantity" value="{{ $item['quantity'] - 1 }}">
                                        <button type="submit" class="px-3 py-1.5 text-slate-400 hover:text-white hover:bg-slate-800 transition">-</button>
                                    </form>
                                    <span class="px-3 py-1.5 font-bold text-white">{{ $item['quantity'] }}</span>
                                    <form method="POST" action="{{ route('cart.update', $key) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                                        <button type="submit" class="px-3 py-1.5 text-slate-400 hover:text-white hover:bg-slate-800 transition">+</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- CỘT PHẢI: Thông tin đơn hàng & Tổng tiền -->
                <div class="lg:col-span-4 space-y-4">
                    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-xl space-y-5 sticky top-24">
                        <h2 class="font-bold text-sm text-white pb-3 border-b border-slate-800">Thông tin đơn hàng</h2>

                        <!-- Mã giảm giá -->
                        <div class="flex items-center justify-between p-3 bg-slate-950 border border-slate-800 rounded-2xl text-xs">
                            <span class="text-slate-300 flex items-center gap-2">🎟️ Áp dụng mã giảm giá</span>
                            <button type="button" class="text-rose-400 font-bold hover:underline">Chọn</button>
                        </div>

                        <!-- Chi tiết giá tiền -->
                        <div class="space-y-2.5 text-xs text-slate-300">
                            <div class="flex justify-between">
                                <span class="text-slate-400">Số lượng sản phẩm chọn:</span>
                                <span class="font-mono font-bold text-white" x-text="totalQuantity"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400">Tổng tiền hàng:</span>
                                <span class="font-mono font-bold text-white" x-text="formatMoney(totalPrice)"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400">Phí vận chuyển:</span>
                                <span class="font-mono text-emerald-400 font-bold">Miễn phí</span>
                            </div>
                        </div>

                        <!-- Tổng tiền thanh toán -->
                        <div class="pt-4 border-t border-slate-800 space-y-1">
                            <div class="flex items-baseline justify-between">
                                <span class="text-xs font-bold text-white">TỔNG TIỀN:</span>
                                <span class="text-xl font-black text-rose-500 font-mono" x-text="formatMoney(totalPrice)"></span>
                            </div>
                            <span class="text-[10px] text-slate-500 block text-right">(Đã bao gồm thuế VAT và được làm tròn)</span>
                        </div>

                        <!-- Nút Mua ngay / Thanh toán -->
                        <a href="{{ url('/checkout') }}" class="block w-full py-4 bg-gradient-to-r from-rose-600 to-red-500 hover:from-rose-500 hover:to-red-400 text-white font-extrabold rounded-2xl text-center shadow-xl shadow-rose-600/30 transition text-sm">
                            MUA NGAY (<span x-text="totalQuantity"></span>)
                            <span class="block text-[10px] font-normal opacity-90">Giao nhanh từ 2 giờ hoặc nhận tại cửa hàng</span>
                        </a>
                    </div>
                </div>

            </div>
        @else
            <!-- Trạng thái giỏ hàng trống -->
            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-12 text-center space-y-4 shadow-xl">
                <div class="text-5xl">🛒</div>
                <h2 class="text-base font-bold text-white">Giỏ hàng của bạn đang trống</h2>
                <p class="text-xs text-slate-400">Hãy chọn thêm sản phẩm vào giỏ hàng để tiếp tục mua sắm nhé!</p>
                <a href="{{ url('/') }}" class="inline-block px-6 py-3 bg-gradient-to-r from-rose-600 to-red-500 text-white font-bold rounded-xl text-xs shadow-lg transition">
                    Khám phá sản phẩm ngay
                </a>
            </div>
        @endif
    </div>
</x-store-layout>