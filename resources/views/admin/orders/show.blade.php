<x-admin-layout>
    <x-slot name="header">Chi Tiết Đơn Hàng #{{ $order->order_code ?? 'ORD-2026-001' }}</x-slot>

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
            
            <!-- Cột Trái: Danh sách sản phẩm & Tổng tiền -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Danh sách sản phẩm -->
                <div class="bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl">
                    <div class="p-5 border-b border-slate-800 flex justify-between items-center">
                        <h2 class="font-bold text-sm text-white">Danh Sách Mặt Hàng</h2>
                        <span class="text-xs text-slate-400 font-mono">1 sản phẩm</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-950 text-slate-400 uppercase tracking-wider font-mono border-b border-slate-800">
                                <tr>
                                    <th class="px-6 py-4">Sản Phẩm</th>
                                    <th class="px-6 py-4">Đơn Giá</th>
                                    <th class="px-6 py-4 text-center">Số Lượng</th>
                                    <th class="px-6 py-4 text-right">Thành Tiền</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/60 text-slate-300">
                                <tr class="hover:bg-slate-800/30 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 rounded-xl bg-slate-950 border border-slate-800 p-1 flex items-center justify-center shrink-0">
                                                <img src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=400" alt="Lenovo LOQ" class="max-h-full object-contain">
                                            </div>
                                            <div>
                                                <div class="font-bold text-white">Laptop Lenovo LOQ Essential Ryzen 5 7535HS RTX 3050</div>
                                                <div class="text-[11px] text-rose-400 font-mono">Tiêu Chuẩn - Đen</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-mono">19.990.000₫</td>
                                    <td class="px-6 py-4 text-center font-mono font-bold">1</td>
                                    <td class="px-6 py-4 text-right font-mono font-bold text-emerald-400">19.990.000₫</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tổng kết tiền thanh toán -->
                <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-2xl space-y-3 text-xs">
                    <div class="flex justify-between text-slate-400">
                        <span>Tiền hàng:</span>
                        <span class="font-mono font-bold text-white">19.990.000₫</span>
                    </div>
                    <div class="flex justify-between text-emerald-400">
                        <span>Giảm giá Voucher (SALE1090):</span>
                        <span class="font-mono font-bold">-1.999.000₫</span>
                    </div>
                    <div class="flex justify-between text-slate-400">
                        <span>Phí vận chuyển:</span>
                        <span class="font-mono font-bold text-emerald-400">Miễn phí</span>
                    </div>
                    <div class="pt-3 border-t border-slate-800 flex justify-between items-baseline">
                        <span class="font-bold text-white text-sm">TỔNG THU:</span>
                        <span class="font-mono font-black text-xl text-rose-500">17.991.000₫</span>
                    </div>
                </div>
            </div>

            <!-- Cột Phải: Thông tin giao hàng & Form trạng thái -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Xử lý trạng thái -->
                <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-2xl space-y-4">
                    <h2 class="font-bold text-sm text-white border-b border-slate-800 pb-3">Tiến Trình & Thanh Toán</h2>

                    <form action="{{ route('admin.orders.updateStatus', $order->id ?? 1) }}" method="POST" class="space-y-4 text-xs">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label class="block text-slate-400 font-semibold mb-1">Trạng thái đơn hàng:</label>
                            <select name="status" class="w-full bg-slate-950 border border-slate-800 text-white rounded-xl px-3.5 py-2.5 focus:ring-rose-500 focus:border-rose-500 font-medium">
                                <option value="Chờ xử lý" selected>Chờ xử lý</option>
                                <option value="Đã xử lý">Đã xử lý</option>
                                <option value="Đang chuẩn bị hàng">Đang chuẩn bị hàng</option>
                                <option value="Đang giao hàng">Đang giao hàng</option>
                                <option value="Đã giao">Đã giao</option>
                                <option value="Đã hủy">Đã hủy</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-slate-400 font-semibold mb-1">Tình trạng thanh toán:</label>
                            <select name="payment_status" class="w-full bg-slate-950 border border-slate-800 text-white rounded-xl px-3.5 py-2.5 focus:ring-rose-500 focus:border-rose-500 font-medium">
                                <option value="Chưa thanh toán" selected>Chưa thanh toán</option>
                                <option value="Đã thanh toán">Đã thanh toán</option>
                            </select>
                        </div>

                        <button type="submit" class="w-full py-2.5 bg-gradient-to-r from-rose-600 to-red-500 hover:from-rose-500 hover:to-red-400 text-white font-bold rounded-xl shadow-lg transition text-xs">
                            Cập nhật tiến trình
                        </button>
                    </form>
                </div>

                <!-- Thông tin khách hàng & Địa chỉ nhận hàng -->
                <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-2xl space-y-4 text-xs">
                    <h2 class="font-bold text-sm text-white border-b border-slate-800 pb-3">Thông Tin Người Nhận</h2>

                    <div class="space-y-2.5 text-slate-300">
                        <div>
                            <span class="text-slate-500 block text-[11px]">Họ và tên:</span>
                            <span class="font-bold text-white text-sm">Ngô Lê Hoàng Thuận</span>
                        </div>
                        <div>
                            <span class="text-slate-500 block text-[11px]">Số điện thoại:</span>
                            <span class="font-mono text-white">0987 654 321</span>
                        </div>
                        <div>
                            <span class="text-slate-500 block text-[11px]">Email:</span>
                            <span class="font-mono text-slate-400">user@gmail.com</span>
                        </div>
                        <div>
                            <span class="text-slate-500 block text-[11px]">Địa chỉ giao hàng:</span>
                            <span class="text-slate-300 leading-relaxed block">123 Đường Số 7, Phường An Lạc A, Quận Bình Tân, TP. Hồ Chí Minh</span>
                        </div>
                        <div>
                            <span class="text-slate-500 block text-[11px]">Phương thức thanh toán:</span>
                            <span class="font-bold text-rose-400">COD (Thanh toán khi nhận hàng)</span>
                        </div>
                        <div>
                            <span class="text-slate-500 block text-[11px]">Ghi chú của khách:</span>
                            <span class="text-slate-400 italic">"Giao trong giờ hành chính giúp mình nhé."</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-admin-layout>