<x-admin-layout>
    <x-slot name="header">Quản Lý Đơn Hàng</x-slot>

    <div class="space-y-6">
        @if (session('success'))
            <div class="flex items-center gap-3 bg-emerald-950/60 border border-emerald-800 text-emerald-300 px-4 py-3 rounded-xl text-xs">
                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Header Section -->
        <div class="flex items-center justify-between bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-2xl">
            <div>
                <h1 class="text-xl font-black text-white tracking-wide">📦 Quản Lý Đơn Hàng</h1>
                <p class="text-xs text-slate-400 mt-1">Kiểm duyệt tiến trình đơn hàng và cập nhật tình trạng thanh toán.</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs font-bold text-rose-400 font-mono">
                    Tổng đơn: {{ $orders->total() }}
                </span>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-950 text-slate-400 uppercase tracking-wider border-b border-slate-800 font-mono">
                        <tr>
                            <th class="px-6 py-4">Mã Đơn (#)</th>
                            <th class="px-6 py-4">Khách Hàng</th>
                            <th class="px-6 py-4">Tổng Tiền</th>
                            <th class="px-6 py-4">Tiến Trình Đơn</th>
                            <th class="px-6 py-4">Thanh Toán</th>
                            <th class="px-6 py-4">Ngày Đặt</th>
                            <th class="px-6 py-4 text-center">Cập Nhật Trạng Thái</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/80 text-slate-300">
                        @forelse($orders as $order)
                            <tr class="hover:bg-slate-800/50 transition">
                                <td class="px-6 py-4 font-mono font-bold text-rose-400">#{{ $order->order_code ?? 'ORD-'.$order->id }}</td>
                                <td class="px-6 py-4 font-semibold text-white">{{ $order->customer_name ?? $order->user->name ?? 'Khách' }}</td>
                                <td class="px-6 py-4 font-mono font-bold text-emerald-400">{{ number_format($order->total_price, 0, ',', '.') }}₫</td>
                                
                                <!-- Trạng thái đơn hàng -->
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 bg-amber-500/10 text-amber-400 border border-amber-500/30 rounded-full font-bold text-[10px]">
                                        {{ $order->status }}
                                    </span>
                                </td>

                                <!-- Trạng thái thanh toán -->
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 {{ $order->payment_status === 'Đã thanh toán' ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30' : 'bg-rose-500/10 text-rose-400 border-rose-500/30' }} border rounded-full font-bold text-[10px]">
                                        {{ $order->payment_status }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-slate-500 font-mono">{{ $order->created_at->format('d/m/Y H:i') }}</td>

                                <!-- Form cập nhật trạng thái -->
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            
                                            <select name="status" class="bg-slate-950 border border-slate-800 text-slate-300 text-[11px] rounded-xl px-2 py-1.5 font-medium">
                                                <option value="Chờ xử lý" {{ $order->status === 'Chờ xử lý' ? 'selected' : '' }}>Chờ xử lý</option>
                                                <option value="Đã xử lý" {{ $order->status === 'Đã xử lý' ? 'selected' : '' }}>Đã xử lý</option>
                                                <option value="Đang chuẩn bị hàng" {{ $order->status === 'Đang chuẩn bị hàng' ? 'selected' : '' }}>Đang chuẩn bị hàng</option>
                                                <option value="Đang giao hàng" {{ $order->status === 'Đang giao hàng' ? 'selected' : '' }}>Đang giao hàng</option>
                                                <option value="Đã giao" {{ $order->status === 'Đã giao' ? 'selected' : '' }}>Đã giao</option>
                                                <option value="Đã hủy" {{ $order->status === 'Đã hủy' ? 'selected' : '' }}>Đã hủy</option>
                                            </select>

                                            <select name="payment_status" class="bg-slate-950 border border-slate-800 text-slate-300 text-[11px] rounded-xl px-2 py-1.5 font-medium">
                                                <option value="Chưa thanh toán" {{ $order->payment_status === 'Chưa thanh toán' ? 'selected' : '' }}>Chưa thanh toán</option>
                                                <option value="Đã thanh toán" {{ $order->payment_status === 'Đã thanh toán' ? 'selected' : '' }}>Đã thanh toán</option>
                                            </select>

                                            <button type="submit" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-500 text-white rounded-xl font-bold transition shadow-md shadow-rose-600/30 text-[11px]">
                                                Lưu
                                            </button>
                                        </form>

                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 hover:text-white rounded-xl font-bold transition border border-slate-700/60 text-[11px]">
                                            Xem
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-slate-500">Chưa có đơn hàng nào trong hệ thống.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="p-4 bg-slate-950 border-t border-slate-800">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>