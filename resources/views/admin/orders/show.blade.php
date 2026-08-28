<x-admin-layout>
    <x-slot name="header">Chi Tiết Đơn Hàng #{{ $order->order_code ?? 'ORD-'.$order->id }}</x-slot>

    @php
        $subtotal = $order->items->sum('total');
        $discountAmount = max(0, $subtotal - $order->total_price);
        $discountPercent = ($subtotal > 0 && $discountAmount > 0) ? round(($discountAmount / $subtotal) * 100) : 0;
        
        $isLocked = in_array($order->status, ['Đã giao', 'Đã hủy']);
        
        $nextOptions = [];
        if ($order->status === 'Chờ xử lý') {
            $nextOptions = ['Chờ xử lý' => 'Chờ xử lý (Hiện tại)', 'Đã xử lý' => '-> Đã xử lý', 'Đã hủy' => '-> Hủy đơn'];
        } elseif ($order->status === 'Đã xử lý') {
            $nextOptions = ['Đã xử lý' => 'Đã xử lý (Hiện tại)', 'Đang chuẩn bị hàng' => '-> Đang chuẩn bị hàng', 'Đã hủy' => '-> Hủy đơn'];
        } elseif ($order->status === 'Đang chuẩn bị hàng') {
            $nextOptions = ['Đang chuẩn bị hàng' => 'Đang chuẩn bị hàng (Hiện tại)', 'Đang giao hàng' => '-> Đang giao hàng', 'Đã hủy' => '-> Hủy đơn'];
        } elseif ($order->status === 'Đang giao hàng') {
            $nextOptions = ['Đang giao hàng' => 'Đang giao hàng (Hiện tại)', 'Đã giao' => '-> Đã giao'];
        }
    @endphp

    <div class="space-y-6">
        <!-- Top Bar Navigation -->
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-2 text-xs text-slate-400 hover:text-white font-semibold transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Quay lại danh sách đơn hàng
            </a>
            <button onclick="window.print()" class="flex items-center gap-2 px-4 py-2 bg-slate-900 border border-slate-800 hover:bg-slate-800 text-slate-300 hover:text-white rounded-xl text-xs font-bold transition shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                In hóa đơn
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- Cột Trái: Danh sách sản phẩm & Bóc tách tiền -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Danh sách sản phẩm -->
                <div class="bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl">
                    <div class="p-5 border-b border-slate-800 flex justify-between items-center">
                        <h2 class="font-bold text-sm text-white">Danh Sách Mặt Hàng</h2>
                        <span class="text-xs text-slate-400 font-mono">{{ $order->items->count() }} sản phẩm</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-950 text-slate-400 uppercase tracking-wider font-mono border-b border-slate-800">
                                <tr>
                                    <th class="px-6 py-4">Sản Phẩm</th>
                                    <th class="px-6 py-4">Đơn Giá Gốc</th>
                                    <th class="px-6 py-4 text-center">Số Lượng</th>
                                    <th class="px-6 py-4 text-right">Thành Tiền Gốc</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/60 text-slate-300">
                                @foreach($order->items as $item)
                                    <tr class="hover:bg-slate-800/30 transition">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-12 h-12 rounded-xl bg-slate-950 border border-slate-800 p-1 flex items-center justify-center shrink-0">
                                                    📦
                                                </div>
                                                <div>
                                                    <div class="font-bold text-white">{{ $item->product_name }}</div>
                                                    <div class="text-[11px] text-rose-400 font-mono">{{ $item->version_name }} - {{ $item->color_name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 font-mono">{{ number_format($item->price, 0, ',', '.') }}₫</td>
                                        <td class="px-6 py-4 text-center font-mono font-bold">{{ $item->quantity }}</td>
                                        <td class="px-6 py-4 text-right font-mono font-bold text-white">{{ number_format($item->total, 0, ',', '.') }}₫</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Bóc tách chi tiết tiền minh bạch -->
                <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-2xl space-y-3 text-xs">
                    <div class="flex justify-between text-slate-300">
                        <span>1. Tổng tiền hàng gốc:</span>
                        <span class="font-mono font-bold text-white">{{ number_format($subtotal, 0, ',', '.') }}₫</span>
                    </div>

                    <div class="flex justify-between text-slate-300">
                        <span>2. Phí vận chuyển:</span>
                        <span class="font-mono font-bold text-emerald-400">
                            {{ $subtotal >= 300000 ? '0₫ (Miễn phí vì đơn hàng > 300.000₫)' : '30.000₫' }}
                        </span>
                    </div>

                    @if($discountAmount > 0)
                        <div class="flex justify-between text-emerald-400">
                            <span>3. Giảm giá Voucher @if($discountPercent > 0) ({{ $discountPercent }}%) @endif:</span>
                            <span class="font-mono font-bold">-{{ number_format($discountAmount, 0, ',', '.') }}₫</span>
                        </div>
                    @endif

                    <div class="pt-3 border-t border-slate-800 flex justify-between items-baseline">
                        <span class="font-bold text-white text-sm">TỔNG THU THỰC TẾ:</span>
                        <span class="font-mono font-black text-xl text-rose-500">{{ number_format($order->total_price, 0, ',', '.') }}₫</span>
                    </div>
                </div>
            </div>

            <!-- Cột Phải: Thông tin giao hàng & Form trạng thái -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Xử lý trạng thái -->
                <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-2xl space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                        <h2 class="font-bold text-sm text-white">Tiến Trình & Thanh Toán</h2>
                        @if($isLocked)
                            <span class="px-2.5 py-0.5 rounded-full font-bold text-[10px] {{ $order->status === 'Đã giao' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' : 'bg-rose-500/10 text-rose-400 border border-rose-500/30' }}">
                                Đã khóa ({{ $order->status }})
                            </span>
                        @endif
                    </div>

                    @if($isLocked)
                        <!-- Trạng thái đã hoàn tất hoặc đã hủy -> Khóa và chỉ hiển thị thông tin tĩnh -->
                        <div class="space-y-3 text-xs">
                            <div class="p-3.5 bg-slate-950 rounded-2xl border border-slate-800 space-y-1">
                                <span class="text-slate-400 block text-[11px]">Trạng thái đơn hàng:</span>
                                <div class="font-bold text-sm {{ $order->status === 'Đã giao' ? 'text-emerald-400' : 'text-rose-400' }}">
                                    {{ $order->status }}
                                </div>
                            </div>

                            <div class="p-3.5 bg-slate-950 rounded-2xl border border-slate-800 space-y-1">
                                <span class="text-slate-400 block text-[11px]">Tình trạng thanh toán:</span>
                                <div class="font-bold text-sm {{ $order->payment_status === 'Đã thanh toán' ? 'text-emerald-400' : 'text-rose-400' }}">
                                    {{ $order->payment_status }}
                                </div>
                            </div>

                            <div class="p-3 bg-slate-950/60 border border-slate-800/80 rounded-xl text-center text-slate-500 text-[11px]">
                                🔒 Đơn hàng đã ở trạng thái kết thúc, không thể thay đổi tiến trình.
                            </div>
                        </div>
                    @else
                        <!-- Form chuyển trạng thái tuần tự -->
                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="space-y-4 text-xs">
                            @csrf
                            @method('PATCH')

                            <div>
                                <label class="block text-slate-400 font-semibold mb-1">Trạng thái đơn hàng (Theo thứ tự):</label>
                                <select name="status" class="w-full bg-slate-950 border border-slate-800 text-white rounded-xl px-3.5 py-2.5 focus:ring-rose-500 focus:border-rose-500 font-medium">
                                    @foreach($nextOptions as $val => $label)
                                        <option value="{{ $val }}" {{ $order->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-slate-400 font-semibold mb-1">Tình trạng thanh toán:</label>
                                <select name="payment_status" class="w-full bg-slate-950 border border-slate-800 text-white rounded-xl px-3.5 py-2.5 focus:ring-rose-500 focus:border-rose-500 font-medium">
                                    <option value="Chưa thanh toán" {{ $order->payment_status === 'Chưa thanh toán' ? 'selected' : '' }}>Chưa thanh toán</option>
                                    <option value="Đã thanh toán" {{ $order->payment_status === 'Đã thanh toán' ? 'selected' : '' }}>Đã thanh toán</option>
                                </select>
                            </div>

                            <button type="submit" class="w-full py-2.5 bg-gradient-to-r from-rose-600 to-red-500 hover:from-rose-500 hover:to-red-400 text-white font-bold rounded-xl shadow-lg transition text-xs">
                                Cập nhật tiến trình
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Thông tin khách hàng & Địa chỉ nhận hàng -->
                <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-2xl space-y-4 text-xs">
                    <h2 class="font-bold text-sm text-white border-b border-slate-800 pb-3">Thông Tin Người Nhận</h2>

                    <div class="space-y-2.5 text-slate-300">
                        <div>
                            <span class="text-slate-500 block text-[11px]">Họ và tên:</span>
                            <span class="font-bold text-white text-sm">{{ $order->customer_name }}</span>
                        </div>
                        <div>
                            <span class="text-slate-500 block text-[11px]">Số điện thoại:</span>
                            <span class="font-mono text-white">{{ $order->customer_phone }}</span>
                        </div>
                        <div>
                            <span class="text-slate-500 block text-[11px]">Email:</span>
                            <span class="font-mono text-slate-400">{{ $order->customer_email }}</span>
                        </div>
                        <div>
                            <span class="text-slate-500 block text-[11px]">Địa chỉ giao hàng:</span>
                            <span class="text-slate-300 leading-relaxed block">{{ $order->shipping_address }}</span>
                        </div>
                        <div>
                            <span class="text-slate-500 block text-[11px]">Phương thức thanh toán:</span>
                            <span class="font-bold text-rose-400">{{ $order->payment_method }}</span>
                        </div>
                        @if($order->notes)
                            <div>
                                <span class="text-slate-500 block text-[11px]">Ghi chú của khách:</span>
                                <span class="text-slate-400 italic">"{{ $order->notes }}"</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-admin-layout>