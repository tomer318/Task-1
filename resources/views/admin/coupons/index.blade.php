<x-admin-layout>
    <x-slot name="header">Quản Lý Mã Giảm Giá</x-slot>

    <div class="space-y-6">
        @if (session('success'))
            <div class="flex items-center gap-3 bg-emerald-950/60 border border-emerald-800 text-emerald-300 px-4 py-3 rounded-xl text-xs">
                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-2xl">
            <div class="p-5 border-b border-slate-800 flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-base text-white">Danh Sách Mã Giảm Giá</h2>
                    <p class="text-xs text-slate-400">Quản lý các chương trình khuyến mãi và voucher cho khách hàng</p>
                </div>
                <a href="{{ route('admin.coupons.create') }}" class="px-4 py-2 bg-gradient-to-r from-rose-600 to-red-500 hover:from-rose-500 hover:to-red-400 text-white rounded-xl text-xs font-semibold shadow-md transition">
                    + Thêm mã giảm giá
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-950/60 border-b border-slate-800 text-slate-400 font-semibold uppercase tracking-wider">
                            <th class="py-3.5 px-6">Mã Code</th>
                            <th class="py-3.5 px-6">Loại Giảm</th>
                            <th class="py-3.5 px-6">Mức Giảm</th>
                            <th class="py-3.5 px-6">Đơn Tối Thiểu</th>
                            <th class="py-3.5 px-6">Lượt Dùng</th>
                            <th class="py-3.5 px-6">Hạn Dùng</th>
                            <th class="py-3.5 px-6">Trạng Thái</th>
                            <th class="py-3.5 px-6 text-right">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 text-slate-300">
                        @forelse ($coupons as $coupon)
                            <tr class="hover:bg-slate-800/40 transition">
                                <td class="py-4 px-6 font-mono font-bold text-rose-400">{{ $coupon->code }}</td>
                                <td class="py-4 px-6">{{ $coupon->type === 'percent' ? 'Phần trăm (%)' : 'Tiền mặt (₫)' }}</td>
                                <td class="py-4 px-6 font-mono font-bold text-emerald-400">
                                    {{ $coupon->type === 'percent' ? $coupon->value . '%' : number_format($coupon->value, 0, ',', '.') . '₫' }}
                                </td>
                                <td class="py-4 px-6 font-mono text-slate-400">{{ number_format($coupon->min_order_value, 0, ',', '.') }}₫</td>
                                <td class="py-4 px-6 font-mono">{{ $coupon->used_count }} / {{ $coupon->usage_limit ?? '∞' }}</td>
                                <td class="py-4 px-6 font-mono text-slate-400">{{ $coupon->expires_at ? $coupon->expires_at->format('d/m/Y') : 'Không giới hạn' }}</td>
                                <td class="py-4 px-6">
                                    @if ($coupon->is_active)
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">Hoạt động</span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-800 text-slate-400 border border-slate-700">Tắt</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right space-x-2">
                                    <a href="{{ route('admin.coupons.edit', $coupon) }}" class="text-slate-300 hover:text-white bg-slate-800 px-3 py-1.5 rounded-lg transition">Sửa</a>
                                    <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="inline" onsubmit="return confirm('Xóa mã giảm giá này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-400 hover:text-rose-300 bg-rose-950/50 border border-rose-800/40 px-3 py-1.5 rounded-lg transition">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-8 text-slate-500">Chưa có mã giảm giá nào được tạo.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-800 bg-slate-950/40">
                {{ $coupons->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>