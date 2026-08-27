<x-store-layout>
    @php
        $subtotal = 0;
        foreach(session('cart', []) as $ci) {
            $subtotal += $ci['price'] * $ci['quantity'];
        }
        $coupon = session('coupon');
        $discount = 0;
        if ($coupon) {
            $discount = ($coupon['type'] === 'percent') ? ($subtotal * $coupon['value']) / 100 : $coupon['value'];
        }
        $baseShipping = ($subtotal - $discount >= 300000 || $subtotal == 0) ? 0 : 30000;
    @endphp

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 text-white space-y-6"
         x-data="{ 
             deliveryMethod: 'delivery', 
             shippingSpeed: 'normal', 
             baseShip: {{ $baseShipping }},
             get shippingFee() {
                 return this.shippingSpeed === 'express' ? 120000 : this.baseShip;
             },
             subtotal: {{ $subtotal }},
             discount: {{ $discount }},
             get finalTotal() {
                 return Math.max(0, this.subtotal - this.discount + this.shippingFee);
             },
             companyInvoice: false,
             useExistingAddress: {{ isset($addresses) && count($addresses) > 0 ? 'true' : 'false' }}
         }">
        
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-xs text-slate-400 pb-3 border-b border-slate-800">
            <a href="{{ route('cart.index') }}" class="text-rose-500 font-semibold hover:underline">&lt; Quay lại giỏ hàng</a>
            <span>/</span>
            <span class="text-white">Thanh toán đơn hàng</span>
        </div>

        <form action="{{ route('checkout.process') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            @csrf

            <!-- CỘT TRÁI: Thông tin giao hàng chi tiết -->
            <div class="lg:col-span-7 space-y-6">
                
                <!-- Thông tin khách hàng & Email nhận hóa đơn -->
                <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-xl space-y-4">
                    <h2 class="font-bold text-sm text-white border-b border-slate-800 pb-3">👤 Thông Tin Khách Hàng</h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div>
                            <label class="block text-slate-400 font-semibold mb-1">Họ và tên</label>
                            <input type="text" name="customer_name" value="{{ old('customer_name', $user->name ?? '') }}" required 
                                   class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-white focus:ring-rose-500 focus:border-rose-500">
                        </div>
                        <div>
                            <label class="block text-slate-400 font-semibold mb-1">Email nhận hóa đơn (VAT)</label>
                            <input type="email" name="customer_email" value="{{ old('customer_email', $user->email ?? '') }}" required 
                                   class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-white font-mono focus:ring-rose-500 focus:border-rose-500">
                        </div>
                    </div>
                    <label class="flex items-center gap-2 text-[11px] text-slate-400 cursor-pointer pt-1">
                        <input type="checkbox" checked class="rounded text-rose-600 bg-slate-950 border-slate-800">
                        Nhận email thông báo và ưu đãi từ TECHZONE
                    </label>
                </div>

                <!-- Chọn hình thức nhận hàng & Sổ địa chỉ -->
                <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-xl space-y-5">
                    <h2 class="font-bold text-sm text-white border-b border-slate-800 pb-3">📦 Hình Thức Nhận Hàng</h2>
                    
                    <div class="flex bg-slate-950 border border-slate-800 rounded-2xl p-1 gap-1 text-xs font-bold">
                        <button type="button" @click="deliveryMethod = 'delivery'" 
                                :class="deliveryMethod === 'delivery' ? 'bg-gradient-to-r from-rose-600 to-red-500 text-white shadow-md' : 'text-slate-400 hover:text-white'"
                                class="flex-1 py-2.5 rounded-xl transition">Giao hàng tận nơi</button>
                        <button type="button" @click="deliveryMethod = 'store'" 
                                :class="deliveryMethod === 'store' ? 'bg-gradient-to-r from-rose-600 to-red-500 text-white shadow-md' : 'text-slate-400 hover:text-white'"
                                class="flex-1 py-2.5 rounded-xl transition">Nhận tại cửa hàng</button>
                    </div>

                    <!-- Form Giao tận nơi -->
                    <div class="space-y-4 text-xs" x-show="deliveryMethod === 'delivery'">
                        <div>
                            <label class="block text-slate-400 font-semibold mb-1">Số điện thoại người nhận</label>
                            <input type="text" name="customer_phone" value="{{ old('customer_phone', $user->phone ?? '0777190215') }}" required 
                                   class="w-full sm:w-1/2 bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-white font-mono">
                        </div>

                        <!-- Sổ địa chỉ -->
                        @if(isset($addresses) && count($addresses) > 0)
                            <div class="space-y-3 pt-2">
                                <div class="flex items-center justify-between">
                                    <label class="text-slate-400 font-semibold">Chọn địa chỉ từ sổ địa chỉ của bạn:</label>
                                    <button type="button" @click="useExistingAddress = !useExistingAddress" class="text-rose-400 hover:underline font-semibold">
                                        <span x-show="useExistingAddress">+ Nhập địa chỉ mới khác</span>
                                        <span x-show="!useExistingAddress" style="display: none;">← Chọn từ sổ địa chỉ có sẵn</span>
                                    </button>
                                </div>

                                <div class="space-y-2" x-show="useExistingAddress">
                                    @foreach($addresses as $addr)
                                        <label class="flex items-start gap-3 p-3.5 bg-slate-950 border border-slate-800 rounded-2xl cursor-pointer hover:border-rose-500 transition">
                                            <input type="radio" name="selected_address_id" value="{{ $addr->id }}" {{ $loop->first ? 'checked' : '' }} class="mt-1 text-rose-600">
                                            <div>
                                                <span class="font-bold text-white block">{{ $addr->name ?? $user->name }} - {{ $addr->phone ?? '0777190215' }}</span>
                                                <span class="text-slate-400 text-[11px] block mt-0.5">{{ $addr->address ?? $addr->full_address }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Nhập địa chỉ mới -->
                        <div class="space-y-1" x-show="!useExistingAddress" @if(isset($addresses) && count($addresses) > 0) style="display: none;" @endif>
                            <label class="block text-slate-400 font-semibold mb-1">Địa chỉ giao hàng chi tiết mới</label>
                            <textarea name="shipping_address" rows="3" placeholder="Số nhà, tên đường, phường/xã, quận/huyện..." 
                                      class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-white">{{ old('shipping_address', '102/1c Lê Tấn Bế, khu phố 50, Phường An Lạc, Quận Bình Tân, TP. Hồ Chí Minh') }}</textarea>
                        </div>

                        <!-- Chọn phương thức giao hàng -->
                        <div class="space-y-2.5 pt-3 border-t border-slate-800">
                            <label class="block text-slate-400 font-semibold">Chọn phương thức giao hàng</label>
                            
                            <!-- Giao thông thường (Mặc định) -->
                            <label class="flex items-center justify-between p-3.5 bg-slate-950 border rounded-2xl cursor-pointer transition"
                                   :class="shippingSpeed === 'normal' ? 'border-rose-500 bg-rose-950/20' : 'border-slate-800'">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="shipping_speed" value="normal" x-model="shippingSpeed" class="text-rose-600">
                                    <div>
                                        <span class="font-bold text-white block">🚚 Giao thông thường (Tiêu chuẩn)</span>
                                        <span class="text-[11px] text-slate-400">Trước 21 giờ ngày mai • Tiết kiệm và ổn định</span>
                                    </div>
                                </div>
                                <span class="font-mono font-bold text-emerald-400" x-text="baseShip === 0 ? 'Miễn phí' : new Intl.NumberFormat('vi-VN').format(baseShip) + '₫'"></span>
                            </label>

                            <!-- Giao siêu tốc (+120k) -->
                            <label class="flex items-center justify-between p-3.5 bg-slate-950 border rounded-2xl cursor-pointer transition"
                                   :class="shippingSpeed === 'express' ? 'border-rose-500 bg-rose-950/20' : 'border-slate-800'">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="shipping_speed" value="express" x-model="shippingSpeed" class="text-rose-600">
                                    <div>
                                        <span class="font-bold text-white block">⚡ Giao siêu tốc (Hỏa tốc)</span>
                                        <span class="text-[11px] text-slate-400">Giao nhanh trong vòng 2 giờ tận tay</span>
                                    </div>
                                </div>
                                <span class="font-mono font-bold text-rose-400">+120.000₫</span>
                            </label>
                        </div>
                    </div>

                    <!-- Form Nhận tại cửa hàng -->
                    <div class="space-y-3 text-xs" x-show="deliveryMethod === 'store'" style="display: none;">
                        <div class="p-4 bg-slate-950 border border-slate-800 rounded-2xl text-slate-300">
                            <span class="font-bold text-white block">📍 TECHZONE Quận Bình Tân (Showroom chính)</span>
                            <span class="text-[11px] text-slate-400 block mt-1">123 Đường Số 7, Phường An Lạc A, Quận Bình Tân, TP.HCM</span>
                            <span class="text-[11px] text-emerald-400 font-semibold block mt-1">✓ Còn hàng - Sẵn sàng giao ngay lập tức tại cửa hàng</span>
                        </div>
                    </div>

                    <!-- Ghi chú đơn hàng -->
                    <div class="pt-2 text-xs">
                        <label class="block text-slate-400 font-semibold mb-1">Ghi chú đơn hàng (nếu có)</label>
                        <input type="text" name="notes" placeholder="Ví dụ: Gọi trước khi giao, canh giờ hành chính..." 
                               class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-white">
                    </div>

                    <!-- Xuất hóa đơn công ty -->
                    <div class="pt-2 border-t border-slate-800 space-y-3 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-300 font-semibold">Bạn có muốn xuất hóa đơn công ty không?</span>
                            <div class="flex items-center gap-4">
                                <label class="flex items-center gap-1.5 cursor-pointer"><input type="radio" name="has_company_invoice" value="1" @click="companyInvoice = true" class="text-rose-600"> Có</label>
                                <label class="flex items-center gap-1.5 cursor-pointer"><input type="radio" name="has_company_invoice" value="0" @click="companyInvoice = false" checked class="text-rose-600"> Không</label>
                            </div>
                        </div>

                        <div class="space-y-3 p-3 bg-slate-950 border border-slate-800 rounded-xl" x-show="companyInvoice" style="display: none;">
                            <input type="text" name="company_name" placeholder="Tên công ty" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-white">
                            <div class="grid grid-cols-2 gap-2">
                                <input type="text" name="company_tax_code" placeholder="Mã số thuế" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-white">
                                <input type="text" name="company_address" placeholder="Địa chỉ công ty" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-white">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Phương Thức Thanh Toán -->
                <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-xl space-y-4">
                    <h2 class="font-bold text-sm text-white border-b border-slate-800 pb-3">💳 Chọn Phương Thức Thanh Toán</h2>
                    
                    <div class="space-y-3 text-xs">
                        <label class="flex items-center gap-3 p-3.5 bg-slate-950 border border-rose-500/60 rounded-2xl cursor-pointer">
                            <input type="radio" name="payment_method" value="COD" checked class="text-rose-600">
                            <div>
                                <span class="font-bold text-white block">Thanh toán khi nhận hàng (COD)</span>
                                <span class="text-[11px] text-slate-400">Thanh toán tiền mặt trực tiếp cho nhân viên giao hàng.</span>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-3.5 bg-slate-950 border border-slate-800 rounded-2xl cursor-pointer">
                            <input type="radio" name="payment_method" value="Banking" class="text-rose-600">
                            <div>
                                <span class="font-bold text-white block">Chuyển khoản ngân hàng qua mã QR</span>
                                <span class="text-[11px] text-slate-400">Quét mã QR bằng ứng dụng ngân hàng bất kỳ.</span>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-3.5 bg-slate-950 border border-slate-800 rounded-2xl cursor-pointer">
                            <input type="radio" name="payment_method" value="VNPay" class="text-rose-600">
                            <div>
                                <span class="font-bold text-white block">VNPay-QR / Thẻ nội địa</span>
                                <span class="text-[11px] text-slate-400">Thanh toán an toàn qua cổng VNPay.</span>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-3.5 bg-slate-950 border border-slate-800 rounded-2xl cursor-pointer">
                            <input type="radio" name="payment_method" value="MoMo" class="text-rose-600">
                            <div>
                                <span class="font-bold text-white block">Ví điện tử MoMo</span>
                                <span class="text-[11px] text-slate-400">Giảm thêm 2% tối đa 200.000₫ khi thanh toán qua MoMo.</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- CỘT PHẢI: Tóm tắt đơn hàng & Nút Thanh Toán Nằm TRONG Form -->
            <div class="lg:col-span-5 space-y-6">
                <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-xl space-y-5 sticky top-24">
                    <h2 class="font-bold text-sm text-white pb-3 border-b border-slate-800">Thông Tin Đơn Hàng ({{ count($cart) }})</h2>

                    <div class="space-y-3 max-h-56 overflow-y-auto pr-2 divide-y divide-slate-800/60">
                        @foreach($cart as $item)
                            @php $itemTotal = $item['price'] * $item['quantity']; @endphp
                            <div class="flex items-center justify-between pt-3 first:pt-0 text-xs">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-slate-950 border border-slate-800 rounded-xl p-1 flex items-center justify-center shrink-0">
                                        <img src="{{ $item['image'] ? asset('storage/' . $item['image']) : 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=400' }}" alt="" class="max-h-full object-contain">
                                    </div>
                                    <div>
                                        <div class="font-bold text-white line-clamp-1">{{ $item['name'] }}</div>
                                        <div class="text-[11px] text-slate-400">SL: {{ $item['quantity'] }} | <span class="text-rose-400">{{ $item['version'] ?? 'Tiêu chuẩn' }}</span></div>
                                    </div>
                                </div>
                                <span class="font-mono font-bold text-white shrink-0">{{ number_format($itemTotal, 0, ',', '.') }}₫</span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Chi tiết giá tiền -->
                    <div class="space-y-2.5 text-xs text-slate-300 pt-3 border-t border-slate-800">
                        <div class="flex justify-between">
                            <span class="text-slate-400">Tổng tiền hàng:</span>
                            <span class="font-mono font-bold text-white">{{ number_format($subtotal, 0, ',', '.') }}₫</span>
                        </div>
                        @if($coupon)
                            <div class="flex justify-between text-emerald-400">
                                <span>Giảm giá trực tiếp ({{ $coupon['code'] }}):</span>
                                <span class="font-mono font-bold">-{{ number_format($discount, 0, ',', '.') }}₫</span>
                            </div>
                        @endif
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400">Phí vận chuyển:</span>
                            <span class="font-mono font-bold text-emerald-400" 
                                  x-text="shippingFee === 0 ? 'Miễn phí' : new Intl.NumberFormat('vi-VN').format(shippingFee) + '₫'">
                            </span>
                        </div>
                    </div>

                    <!-- Tổng tiền thanh toán -->
                    <div class="pt-4 border-t border-slate-800 space-y-1">
                        <div class="flex items-baseline justify-between">
                            <span class="text-xs font-bold text-white">TỔNG TIỀN:</span>
                            <span class="text-xl font-black text-rose-500 font-mono" 
                                  x-text="new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(finalTotal)">
                            </span>
                        </div>
                        @if($discount > 0)
                            <div class="flex justify-between text-[11px] text-emerald-400 pt-1">
                                <span>Bạn đã tiết kiệm được:</span>
                                <span class="font-mono font-bold">-{{ number_format($discount, 0, ',', '.') }}₫</span>
                            </div>
                        @endif
                        <span class="text-[10px] text-slate-500 block text-right">(Đã bao gồm VAT và phí ship)</span>
                    </div>

                    <!-- Nút Submit nằm TRONG form -->
                    <button type="submit" class="w-full py-4 bg-gradient-to-r from-rose-600 to-red-500 hover:from-rose-500 hover:to-red-400 text-white font-extrabold rounded-2xl text-center shadow-xl shadow-rose-600/35 transition text-sm cursor-pointer">
                        XÁC NHẬN ĐẶT HÀNG
                    </button>

                    <p class="text-[10px] text-slate-500 text-center leading-relaxed">
                        Bằng việc đặt hàng, bạn đồng ý với <a href="#" class="text-rose-400 underline">Điều khoản sử dụng</a> của TECHZONE.
                    </p>
                </div>
            </div>
        </form>
        <!-- Thông báo lỗi hoặc thành công nếu có -->
        @if (session('error'))
            <div class="bg-rose-950/80 border border-rose-800 text-rose-300 p-4 rounded-2xl text-xs font-semibold">
                {{ session('error') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="bg-rose-950/80 border border-rose-800 text-rose-300 p-4 rounded-2xl text-xs space-y-1">
                <span class="font-bold">Vui lòng kiểm tra lại thông tin:</span>
                @foreach ($errors->all() as $error)
                    <div>- {{ $error }}</div>
                @endforeach
            </div>
        @endif
    </div>
</x-store-layout>