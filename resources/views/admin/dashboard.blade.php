<x-admin-layout>
    <x-slot name="header">Tổng quan hệ thống</x-slot>

    <!-- Thống kê nhanh Widget Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl shadow-lg">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-semibold">Tổng Sản Phẩm</span>
                <span class="p-2 bg-rose-500/10 text-rose-400 rounded-lg">📦</span>
            </div>
            <div class="text-2xl font-extrabold text-white font-mono">{{ number_format($totalProducts) }}</div>
            <div class="text-[11px] text-emerald-400 mt-1">1000+ sản phẩm sẵn sàng</div>
        </div>

        <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl shadow-lg">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-semibold">Danh Mục Công Nghệ</span>
                <span class="p-2 bg-blue-500/10 text-blue-400 rounded-lg">🏷️</span>
            </div>
            <div class="text-2xl font-extrabold text-white font-mono">{{ $totalCategories }}</div>
            <div class="text-[11px] text-slate-400 mt-1">Điện thoại, Laptop, TV, Gia dụng...</div>
        </div>

        <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl shadow-lg">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-semibold">Tài Khoản Thành Viên</span>
                <span class="p-2 bg-purple-500/10 text-purple-400 rounded-lg">👥</span>
            </div>
            <div class="text-2xl font-extrabold text-white font-mono">{{ $totalUsers }}</div>
            <div class="text-[11px] text-slate-400 mt-1">Phân quyền qua Spatie RBAC</div>
        </div>

        <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl shadow-lg">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-semibold">Trạng Thái Server</span>
                <span class="p-2 bg-emerald-500/10 text-emerald-400 rounded-lg">⚡</span>
            </div>
            <div class="text-base font-bold text-emerald-400 font-mono">Running (Docker)</div>
            <div class="text-[11px] text-slate-400 mt-1">MySQL 8.4 • PHP 8.3</div>
        </div>
    </div>

    <!-- Sản phẩm mới thêm -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-sm font-bold text-white uppercase tracking-wider">Sản Phẩm Vừa Cập Nhật</h3>
                <p class="text-xs text-slate-400">Các thiết bị công nghệ mới nhất trong hệ thống</p>
            </div>
            <a href="{{ route('admin.products.index') }}" class="text-xs text-rose-400 hover:underline">Xem tất cả 1000+ sản phẩm →</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-800 text-slate-400 uppercase tracking-wider font-semibold">
                        <th class="py-3 px-4">Tên Sản Phẩm</th>
                        <th class="py-3 px-4">Danh Mục</th>
                        <th class="py-3 px-4">Giá Bán</th>
                        <th class="py-3 px-4">Tồn Kho</th>
                        <th class="py-3 px-4 text-right">Thao Tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @foreach($recentProducts as $p)
                        <tr class="hover:bg-slate-800/40 transition">
                            <td class="py-3.5 px-4 font-semibold text-white">{{ $p->name }}</td>
                            <td class="py-3.5 px-4 text-slate-300">{{ $p->category->name ?? 'N/A' }}</td>
                            <td class="py-3.5 px-4 text-rose-400 font-mono font-bold">${{ number_format($p->price, 2) }}</td>
                            <td class="py-3.5 px-4 text-emerald-400">{{ $p->stock }} chiếc</td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('admin.products.edit', $p) }}" class="text-slate-300 hover:text-white bg-slate-800 px-2.5 py-1 rounded-lg">Sửa</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>