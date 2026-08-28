<x-admin-layout>
    <x-slot name="header">Thống Kê Đơn Hàng Đã Hủy</x-slot>

    <div class="space-y-6">
        <!-- Thống kê tag lý do hủy -->
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-xl space-y-4">
            <h2 class="font-bold text-sm text-white">📊 Các Lý Do / Tag Thường Xuyên Được Chọn Khi Hủy</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @forelse($allTags as $tag => $count)
                    <div class="p-4 bg-slate-950 border border-slate-800 rounded-2xl flex items-center justify-between">
                        <span class="text-xs text-slate-300 font-semibold">{{ $tag }}</span>
                        <span class="px-2.5 py-1 bg-rose-500/10 text-rose-400 border border-rose-500/20 rounded-lg font-mono font-bold text-xs">{{ $count }} lần</span>
                    </div>
                @empty
                    <div class="col-span-4 text-xs text-slate-500 text-center py-2">Chưa có dữ liệu thống kê tag.</div>
                @endforelse
            </div>
        </div>

        <!-- Danh sách chi tiết các đơn đã hủy -->
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-2xl space-y-4">
            <h2 class="font-bold text-sm text-white">Chi Tiết Đơn Hàng Đã Hủy</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-950 text-slate-400 uppercase font-mono border-b border-slate-800">
                        <tr>
                            <th class="px-5 py-4">Mã Đơn</th>
                            <th class="px-5 py-4">Khách Hàng</th>
                            <th class="px-5 py-4">Hủy Bởi</th>
                            <th class="px-5 py-4">Tags Lý Do</th>
                            <th class="px-5 py-4">Lý Do Chi Tiết</th>
                            <th class="px-5 py-4">Thời Gian</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 text-slate-300">
                        @forelse($cancellations as $c)
                            <tr class="hover:bg-slate-800/30 transition">
                                <td class="px-5 py-4 font-mono font-bold text-rose-400">#{{ $c->order->order_code ?? $c->order_id }}</td>
                                <td class="px-5 py-4 font-semibold text-white">{{ $c->order->customer_name ?? $c->user->name }}</td>
                                <td class="px-5 py-4">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $c->cancelled_by === 'customer' ? 'bg-amber-500/10 text-amber-400' : 'bg-rose-500/10 text-rose-400' }}">
                                        {{ $c->cancelled_by === 'customer' ? 'Khách Hàng' : 'Admin' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @if(is_array($c->tags))
                                            @foreach($c->tags as $t)
                                                <span class="px-2 py-0.5 bg-slate-950 border border-slate-800 rounded text-[10px] text-slate-300">{{ $t }}</span>
                                            @endforeach
                                        @endif
                                    </div>
                                </td>
                                <td class="px-5 py-4 italic text-slate-400">{{ $c->reason ?: 'Không nêu chi tiết' }}</td>
                                <td class="px-5 py-4 font-mono text-slate-500">{{ $c->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-500">Chưa có đơn hàng nào bị hủy.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>{{ $cancellations->links() }}</div>
        </div>
    </div>
</x-admin-layout>