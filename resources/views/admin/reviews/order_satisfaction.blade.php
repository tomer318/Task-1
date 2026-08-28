<x-admin-layout>
    <x-slot name="header">Khảo Sát & Độ Hài Lòng Đơn Hàng</x-slot>

    <div class="space-y-6">
        <!-- Khối thống kê tổng quan -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="bg-slate-900 border border-slate-800 p-5 rounded-3xl shadow-xl">
                <span class="text-xs text-slate-400">Tỷ lệ hài lòng:</span>
                <div class="text-3xl font-black text-emerald-400 font-mono mt-1">{{ $satisfactionRate }}%</div>
            </div>
            <div class="bg-slate-900 border border-slate-800 p-5 rounded-3xl shadow-xl">
                <span class="text-xs text-slate-400">Đơn hàng 5 sao:</span>
                <div class="text-3xl font-black text-amber-400 font-mono mt-1">{{ $fiveStarCount }} / {{ $totalReviews }}</div>
            </div>
            <div class="bg-slate-900 border border-slate-800 p-5 rounded-3xl shadow-xl">
                <span class="text-xs text-slate-400">Tổng số lượt đánh giá:</span>
                <div class="text-3xl font-black text-rose-500 font-mono mt-1">{{ $totalReviews }}</div>
            </div>
        </div>

        <!-- Danh sách khảo sát từng đơn hàng -->
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-2xl space-y-4">
            <h2 class="font-bold text-sm text-white">Chi Tiết Đánh Giá Dịch Vụ Đơn Hàng</h2>
            <div class="space-y-4 text-xs">
                @forelse($reviews as $rev)
                    <div class="p-4 bg-slate-950 border border-slate-800 rounded-2xl space-y-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="font-mono font-bold text-rose-400">#{{ $rev->order->order_code ?? $rev->order_id }}</span>
                                <span class="text-slate-400 ml-2">Khách hàng: <strong class="text-white">{{ $rev->user->name ?? 'Khách' }}</strong></span>
                            </div>
                            <span class="text-amber-400 font-bold text-sm">★ {{ $rev->rating }}/5</span>
                        </div>

                        @if(is_array($rev->tags) && count($rev->tags) > 0)
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($rev->tags as $t)
                                    <span class="px-2.5 py-1 bg-rose-500/10 text-rose-400 border border-rose-500/20 rounded-lg text-[10px] font-semibold">{{ $t }}</span>
                                @endforeach
                            </div>
                        @endif

                        @if($rev->comment)
                            <p class="text-slate-300 italic">"{{ $rev->comment }}"</p>
                        @endif

                        <!-- Phản hồi cảm ơn từ shop -->
                        <div class="pt-2 border-t border-slate-800 flex items-center justify-between">
                            <span class="text-slate-500 text-[11px]">
                                {{ $rev->admin_reply ? 'Đã phản hồi lúc ' . $rev->replied_at->format('d/m/Y H:i') : 'Chưa gửi phản hồi' }}
                            </span>

                            @if(!$rev->admin_reply)
                                <form action="{{ route('admin.order.satisfaction.reply', $rev->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3.5 py-1.5 bg-gradient-to-r from-rose-600 to-red-500 text-white font-bold rounded-xl hover:opacity-90 transition">
                                        💬 Tiếp nhận & Cảm ơn khách
                                    </button>
                                </form>
                            @else
                                <span class="text-emerald-400 font-semibold">✓ Đã tiếp nhận đánh giá</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-slate-500">Chưa có đánh giá dịch vụ đơn hàng nào.</div>
                @endforelse
            </div>
            <div>{{ $reviews->links() }}</div>
        </div>
    </div>
</x-admin-layout>