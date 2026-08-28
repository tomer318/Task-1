<x-admin-layout>
    <x-slot name="header">Quản Lý Đơn Hàng</x-slot>

    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-slate-800 pb-4">
            <div>
                <h2 class="font-bold text-sm text-white">📦 Danh Sách Đơn Hàng</h2>
                <p class="text-xs text-slate-400 mt-0.5">Kiểm duyệt tiến trình đơn hàng theo cấp bậc tuần tự.</p>
            </div>
            <span class="text-xs px-3 py-1 bg-slate-950 border border-slate-800 rounded-xl font-mono text-rose-400">
                Tổng đơn: {{ $orders->total() }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-950 text-slate-400 uppercase font-mono border-b border-slate-800">
                    <tr>
                        <th class="px-5 py-4">Mã Đơn (#)</th>
                        <th class="px-5 py-4">Khách Hàng</th>
                        <th class="px-5 py-4">Tổng Tiền</th>
                        <th class="px-5 py-4">Tiến Trình Đơn</th>
                        <th class="px-5 py-4">Thanh Toán</th>
                        <th class="px-5 py-4">Ngày Đặt</th>
                        <th class="px-5 py-4 text-right">Cập Nhật Trạng Thái</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-slate-300">
                    @foreach($orders as $ord)
                        @php
                            $isLocked = in_array($ord->status, ['Đã giao', 'Đã hủy']);
                            
                            // Xác định bước tiếp theo được phép
                            $nextOptions = [];
                            if ($ord->status === 'Chờ xử lý') {
                                $nextOptions = ['Chờ xử lý' => 'Chờ xử lý (Hiện tại)', 'Đã xử lý' => '-> Đã xử lý', 'Đã hủy' => '-> Hủy đơn'];
                            } elseif ($ord->status === 'Đã xử lý') {
                                $nextOptions = ['Đã xử lý' => 'Đã xử lý (Hiện tại)', 'Đang chuẩn bị hàng' => '-> Đang chuẩn bị hàng', 'Đã hủy' => '-> Hủy đơn'];
                            } elseif ($ord->status === 'Đang chuẩn bị hàng') {
                                $nextOptions = ['Đang chuẩn bị hàng' => 'Đang chuẩn bị hàng (Hiện tại)', 'Đang giao hàng' => '-> Đang giao hàng', 'Đã hủy' => '-> Hủy đơn'];
                            } elseif ($ord->status === 'Đang giao hàng') {
                                $nextOptions = ['Đang giao hàng' => 'Đang giao hàng (Hiện tại)', 'Đã giao' => '-> Đã giao'];
                            }
                        @endphp
                        <tr class="hover:bg-slate-800/30 transition">
                            <td class="px-5 py-4 font-mono font-bold text-rose-400">#{{ $ord->order_code ?? 'ORD-'.$ord->id }}</td>
                            <td class="px-5 py-4 font-semibold text-white">{{ $ord->customer_name }}</td>
                            <td class="px-5 py-4 font-mono font-bold text-emerald-400">{{ number_format($ord->total_price, 0, ',', '.') }}₫</td>
                            <td class="px-5 py-4">
                                <span class="px-2.5 py-1 rounded-full font-bold text-[10px] {{ $ord->status === 'Đã giao' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' : ($ord->status === 'Đã hủy' ? 'bg-rose-500/10 text-rose-400 border border-rose-500/30' : 'bg-amber-500/10 text-amber-400 border border-amber-500/30') }}">
                                    {{ $ord->status }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="px-2.5 py-1 rounded-full font-bold text-[10px] {{ $ord->payment_status === 'Đã thanh toán' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' : 'bg-rose-500/10 text-rose-400 border border-rose-500/30' }}">
                                    {{ $ord->payment_status }}
                                </span>
                            </td>
                            <td class="px-5 py-4 font-mono text-slate-400">{{ $ord->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-5 py-4 text-right">
                                <form action="{{ route('admin.orders.updateStatus', $ord->id) }}" method="POST" class="inline-flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')

                                    @if($isLocked)
                                        <span class="px-3 py-1.5 bg-slate-950 border border-slate-800 rounded-xl text-[11px] text-slate-500 font-mono">Đã khóa</span>
                                    @else
                                        <select name="status" class="bg-slate-950 border border-slate-800 text-white rounded-xl px-2.5 py-1.5 text-[11px] focus:ring-rose-500 focus:border-rose-500 font-medium">
                                            @foreach($nextOptions as $val => $label)
                                                <option value="{{ $val }}" {{ $ord->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>

                                        <select name="payment_status" class="bg-slate-950 border border-slate-800 text-white rounded-xl px-2 py-1.5 text-[11px]">
                                            <option value="Chưa thanh toán" {{ $ord->payment_status === 'Chưa thanh toán' ? 'selected' : '' }}>Chưa thanh toán</option>
                                            <option value="Đã thanh toán" {{ $ord->payment_status === 'Đã thanh toán' ? 'selected' : '' }}>Đã thanh toán</option>
                                        </select>

                                        <button type="submit" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-500 text-white rounded-xl font-bold transition text-[11px] shadow">
                                            Lưu
                                        </button>
                                    @endif

                                    <a href="{{ route('admin.orders.show', $ord->id) }}" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-bold transition text-[11px]">
                                        Xem
                                    </a>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div>{{ $orders->links() }}</div>
    </div>
</x-admin-layout>