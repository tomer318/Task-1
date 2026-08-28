<x-admin-layout>
    <x-slot name="header">Quản Lý Yêu Cầu Đổi / Trả Hàng</x-slot>

    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-2xl space-y-4">
        <h2 class="font-bold text-sm text-white">📦 Danh Sách Yêu Cầu Đổi / Trả Từ Khách Hàng</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-950 text-slate-400 uppercase font-mono border-b border-slate-800">
                    <tr>
                        <th class="px-5 py-4">Mã Đơn</th>
                        <th class="px-5 py-4">Khách Hàng</th>
                        <th class="px-5 py-4">Tags Lý Do</th>
                        <th class="px-5 py-4">Đính Kèm</th>
                        <th class="px-5 py-4">Tiến Trình Đổi Trả</th>
                        <th class="px-5 py-4">Ngày Yêu Cầu</th>
                        <th class="px-5 py-4 text-right">Thao Tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-slate-300">
                    @forelse($returnRequests as $rr)
                        <tr class="hover:bg-slate-800/30 transition">
                            <td class="px-5 py-4 font-mono font-bold text-rose-400">#{{ $rr->order->order_code }}</td>
                            <td class="px-5 py-4 font-semibold text-white">{{ $rr->user->name }}</td>
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @if(is_array($rr->tags))
                                        @foreach($rr->tags as $t)
                                            <span class="px-2 py-0.5 bg-rose-500/10 text-rose-400 border border-rose-500/20 rounded text-[10px] font-semibold">{{ $t }}</span>
                                        @endforeach
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="font-mono text-slate-400">
                                    {{ is_array($rr->images) ? count($rr->images) : 0 }} ảnh / {{ $rr->video_path ? '1 video' : '0 video' }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="px-2.5 py-1 rounded-full font-bold text-[10px] {{ in_array($rr->status, ['Đã đổi/trả', 'Đã hoàn tiền']) ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' : ($rr->status === 'Từ chối' ? 'bg-rose-500/10 text-rose-400 border border-rose-500/30' : 'bg-amber-500/10 text-amber-400 border border-amber-500/30') }}">
                                    {{ $rr->status }}
                                </span>
                            </td>
                            <td class="px-5 py-4 font-mono text-slate-500">{{ $rr->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('admin.returns.show', $rr->id) }}" class="px-3.5 py-1.5 bg-rose-600 hover:bg-rose-500 text-white font-bold rounded-xl transition text-xs">
                                    Xem & Xử Lý &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-500">Chưa có yêu cầu đổi/trả nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div>{{ $returnRequests->links() }}</div>
    </div>
</x-admin-layout>