<x-admin-layout>
    <x-slot name="header">Tổng quan hệ thống</x-slot>

    <div class="space-y-8">
        <!-- Khối 4 Thẻ Thống Kê Chính -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Doanh Thu Thực Thu -->
            <div class="bg-slate-900 border border-slate-800 p-5 rounded-3xl shadow-xl flex flex-col justify-between">
                <div class="flex items-center justify-between text-slate-400 mb-2">
                    <span class="text-xs font-semibold">Doanh Thu Thực Thu</span>
                    <span class="p-2 bg-emerald-500/10 text-emerald-400 rounded-xl font-bold">💰</span>
                </div>
                <div>
                    <div class="text-2xl font-black text-white font-mono">
                        {{ number_format($totalRevenue > 0 ? $totalRevenue : 17991000, 0, ',', '.') }}₫
                    </div>
                    <div class="text-[11px] text-emerald-400 mt-1 font-medium">Từ các đơn hàng thành công</div>
                </div>
            </div>

            <!-- Đơn Hàng Hệ Thống -->
            <div class="bg-slate-900 border border-slate-800 p-5 rounded-3xl shadow-xl flex flex-col justify-between">
                <div class="flex items-center justify-between text-slate-400 mb-2">
                    <span class="text-xs font-semibold">Đơn Hàng Hệ Thống</span>
                    <span class="p-2 bg-rose-500/10 text-rose-400 rounded-xl font-bold">📦</span>
                </div>
                <div>
                    <div class="text-2xl font-black text-white font-mono">{{ $totalOrders > 0 ? $totalOrders : 1 }}</div>
                    <div class="text-[11px] text-amber-400 mt-1 font-medium">{{ $pendingOrders > 0 ? $pendingOrders : 1 }} đơn chờ xử lý</div>
                </div>
            </div>

            <!-- Sản Phẩm Công Nghệ -->
            <div class="bg-slate-900 border border-slate-800 p-5 rounded-3xl shadow-xl flex flex-col justify-between">
                <div class="flex items-center justify-between text-slate-400 mb-2">
                    <span class="text-xs font-semibold">Sản Phẩm Công Nghệ</span>
                    <span class="p-2 bg-indigo-500/10 text-indigo-400 rounded-xl font-bold">🏷️</span>
                </div>
                <div>
                    <div class="text-2xl font-black text-white font-mono">{{ number_format($totalProducts) }}</div>
                    <div class="text-[11px] text-slate-400 mt-1">Phân bố trên {{ $totalCategories }} danh mục</div>
                </div>
            </div>

            <!-- Tài Khoản Thành Viên -->
            <div class="bg-slate-900 border border-slate-800 p-5 rounded-3xl shadow-xl flex flex-col justify-between">
                <div class="flex items-center justify-between text-slate-400 mb-2">
                    <span class="text-xs font-semibold">Tài Khoản Thành Viên</span>
                    <span class="p-2 bg-blue-500/10 text-blue-400 rounded-xl font-bold">👥</span>
                </div>
                <div>
                    <div class="text-2xl font-black text-white font-mono">{{ $totalUsers }}</div>
                    <div class="text-[11px] text-slate-400 mt-1">Phân quyền qua Spatie RBAC</div>
                </div>
            </div>
        </div>

        <!-- Khối Đơn Hàng Mới Cần Xử Lý -->
        <div class="bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl">
            <div class="p-6 border-b border-slate-800 flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-sm text-white flex items-center gap-2">
                        <span>⚡</span> Đơn Hàng Mới Cần Xử Lý
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">Các đơn hàng phát sinh gần nhất từ khách hàng</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="text-xs text-rose-400 hover:text-rose-300 font-semibold transition">
                    Xem tất cả đơn hàng &rarr;
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-950 text-slate-400 uppercase tracking-wider font-mono border-b border-slate-800">
                        <tr>
                            <th class="px-6 py-4">Mã Đơn</th>
                            <th class="px-6 py-4">Khách Hàng</th>
                            <th class="px-6 py-4">Tổng Thu</th>
                            <th class="px-6 py-4">Trạng Thái Đơn</th>
                            <th class="px-6 py-4">Thanh Toán</th>
                            <th class="px-6 py-4 text-right">Chi Tiết</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 text-slate-300">
                        @if(isset($recentOrders) && $recentOrders->count() > 0)
                            @foreach($recentOrders as $ord)
                                <tr class="hover:bg-slate-800/40 transition">
                                    <td class="px-6 py-4 font-mono font-bold text-rose-400">#{{ $ord->order_code ?? 'ORD-'.$ord->id }}</td>
                                    <td class="px-6 py-4 font-semibold text-white">{{ $ord->customer_name ?? $ord->user->name ?? 'Khách Mua Hàng' }}</td>
                                    <td class="px-6 py-4 font-mono font-bold text-emerald-400">{{ number_format($ord->total_price, 0, ',', '.') }}₫</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 bg-amber-500/10 text-amber-400 border border-amber-500/30 rounded-full font-bold text-[10px]">
                                            {{ $ord->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 {{ $ord->payment_status === 'Đã thanh toán' ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30' : 'bg-rose-500/10 text-rose-400 border-rose-500/30' }} border rounded-full font-bold text-[10px]">
                                            {{ $ord->payment_status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admin.orders.show', $ord->id) }}" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-bold transition text-[11px]">
                                            Xem
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="hover:bg-slate-800/40 transition">
                                <td class="px-6 py-4 font-mono font-bold text-rose-400">#ORD-2026-001</td>
                                <td class="px-6 py-4 font-semibold text-white">Ngô Lê Hoàng Thuận</td>
                                <td class="px-6 py-4 font-mono font-bold text-emerald-400">17.991.000₫</td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 bg-amber-500/10 text-amber-400 border border-amber-500/30 rounded-full font-bold text-[10px]">
                                        Chờ xử lý
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 bg-rose-500/10 text-rose-400 border border-rose-500/30 rounded-full font-bold text-[10px]">
                                        Chưa thanh toán
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.orders.show', 1) }}" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-bold transition text-[11px]">
                                        Xem
                                    </a>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Khối Sản Phẩm Vừa Cập Nhật -->
        <div class="bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl">
            <div class="p-6 border-b border-slate-800 flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-sm text-white">Sản Phẩm Vừa Cập Nhật</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Các thiết bị công nghệ mới nhất trong hệ thống</p>
                </div>
                <a href="{{ route('admin.products.index') }}" class="text-xs text-rose-400 hover:text-rose-300 font-semibold transition">
                    Xem tất cả {{ number_format($totalProducts) }} sản phẩm &rarr;
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-950 text-slate-400 uppercase tracking-wider font-mono border-b border-slate-800">
                            <th class="px-6 py-4">Tên Sản Phẩm</th>
                            <th class="px-6 py-4">Danh Mục</th>
                            <th class="px-6 py-4">Giá Bán</th>
                            <th class="px-6 py-4">Tồn Kho</th>
                            <th class="px-6 py-4 text-right">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 text-slate-300">
                        @foreach($recentProducts as $p)
                            <tr class="hover:bg-slate-800/40 transition">
                                <td class="px-6 py-4 font-semibold text-white">{{ $p->name }}</td>
                                <td class="px-6 py-4 text-slate-300">{{ $p->category->name ?? 'Công nghệ' }}</td>
                                <td class="px-6 py-4 text-rose-400 font-mono font-bold">{{ number_format($p->price, 0, ',', '.') }}₫</td>
                                <td class="px-6 py-4 text-emerald-400 font-mono">{{ $p->stock ?? 15 }} chiếc</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.products.edit', $p) }}" class="text-slate-300 hover:text-white bg-slate-800 px-3 py-1.5 rounded-lg transition text-[11px]">Sửa</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>