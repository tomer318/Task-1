<x-admin-layout>
    <x-slot name="header">Chi Tiết Yêu Cầu Đổi / Trả #{{ $returnRequest->order->order_code }}</x-slot>

    <div class="space-y-6" x-data="{ previewMedia: null, mediaType: 'image' }">
        <a href="{{ route('admin.returns.index') }}" class="text-xs text-slate-400 hover:text-white font-semibold transition">&larr; Quay lại danh sách đổi trả</a>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- Cột Trái: Nội dung báo cáo & Bằng chứng ảnh/video -->
            <div class="lg:col-span-8 space-y-6">
                <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-2xl space-y-4 text-xs">
                    <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                        <h2 class="font-bold text-sm text-white">Báo Cáo Chi Tiết Từ Khách Hàng</h2>
                        <span class="font-mono text-slate-400">Gửi lúc: {{ $returnRequest->created_at->format('d/m/Y H:i') }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 block mb-1">Tags lý do:</span>
                        <div class="flex flex-wrap gap-1.5">
                            @if(is_array($returnRequest->tags))
                                @foreach($returnRequest->tags as $t)
                                    <span class="px-2.5 py-1 bg-rose-500/10 text-rose-400 border border-rose-500/20 rounded-lg font-semibold">{{ $t }}</span>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <div>
                        <span class="text-slate-400 block mb-1">Mô tả tình trạng từ khách:</span>
                        <p class="p-3.5 bg-slate-950 border border-slate-800 rounded-2xl text-slate-200 leading-relaxed">{{ $returnRequest->reason }}</p>
                    </div>

                    <!-- Bằng chứng hình ảnh & video -->
                    <div class="space-y-2 pt-2 border-t border-slate-800">
                        <span class="text-slate-300 font-bold block">Hình ảnh & Video clip đính kèm:</span>
                        <div class="flex flex-wrap gap-3">
                            @if(is_array($returnRequest->images))
                                @foreach($returnRequest->images as $img)
                                    <img src="{{ asset('storage/' . $img) }}" 
                                         @click="previewMedia = '{{ asset('storage/' . $img) }}'; mediaType = 'image'"
                                         class="w-20 h-20 object-cover rounded-2xl border border-slate-800 hover:border-rose-500 cursor-pointer transition">
                                @endforeach
                            @endif

                            @if($returnRequest->video_path)
                                <div @click="previewMedia = '{{ asset('storage/' . $returnRequest->video_path) }}'; mediaType = 'video'"
                                     class="w-20 h-20 bg-slate-950 border border-slate-800 hover:border-rose-500 rounded-2xl flex flex-col items-center justify-center cursor-pointer transition">
                                    <span class="text-2xl">▶️</span>
                                    <span class="text-[10px] text-slate-400 font-semibold">Xem Video</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Danh sách sản phẩm của đơn hàng -->
                <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-2xl space-y-3 text-xs">
                    <h3 class="font-bold text-sm text-white border-b border-slate-800 pb-3">Sản Phẩm Trong Đơn Hàng #{{ $returnRequest->order->order_code }}</h3>
                    @foreach($returnRequest->order->items as $item)
                        <div class="flex items-center justify-between p-3 bg-slate-950 border border-slate-800 rounded-2xl">
                            <div>
                                <span class="font-bold text-white uppercase block">{{ $item->product_name }}</span>
                                <span class="text-slate-400 text-[11px]">SL: {{ $item->quantity }} | Phân loại: {{ $item->version_name }} - {{ $item->color_name }}</span>
                            </div>
                            <span class="font-mono font-bold text-rose-500">{{ number_format($item->total, 0, ',', '.') }}₫</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Cột Phải: Duyệt / Xử lý tiến trình đổi trả -->
            <div class="lg:col-span-4 space-y-6 text-xs">
                <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-2xl space-y-4">
                    <h3 class="font-bold text-sm text-white border-b border-slate-800 pb-3">Xử Lý Đổi / Trả Hàng</h3>

                    <div class="p-3.5 bg-slate-950 rounded-2xl border border-slate-800 space-y-1">
                        <span class="text-slate-400 block text-[11px]">Trạng thái hiện tại:</span>
                        <div class="text-rose-400 font-bold text-sm">{{ $returnRequest->status }}</div>
                    </div>

                    @if(!in_array($returnRequest->status, ['Đã đổi/trả', 'Đã hoàn tiền', 'Từ chối']))
                        <form action="{{ route('admin.returns.updateStatus', $returnRequest->id) }}" method="POST" class="space-y-3">
                            @csrf
                            @method('PATCH')

                            <div>
                                <label class="block text-slate-400 font-semibold mb-1">Ghi chú gửi khách hàng:</label>
                                <textarea name="admin_note" rows="3" placeholder="Ghi chú kết quả kiểm tra, hướng dẫn gửi hàng..." class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white"></textarea>
                            </div>

                            @if($returnRequest->status === 'Chờ duyệt')
                                <button type="submit" name="action" value="process" class="w-full py-2.5 bg-amber-600 hover:bg-amber-500 text-white font-bold rounded-xl shadow transition">
                                    1. Tiếp Nhận & Chuyển Sang Đang Xử Lý
                                </button>
                            @elseif($returnRequest->status === 'Đang xử lý')
                                <button type="submit" name="action" value="complete" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl shadow transition">
                                    2. Hoàn Tất {{ $returnRequest->order->payment_status === 'Đã thanh toán' ? 'Hoàn Tiền Cho Khách' : 'Đổi / Trả Hàng' }}
                                </button>
                            @endif

                            <button type="submit" name="action" value="reject" class="w-full py-2.5 bg-slate-800 hover:bg-rose-950/60 border border-rose-800/40 text-rose-400 font-bold rounded-xl transition">
                                ✕ Từ Chối Yêu Cầu Đổi Trả
                            </button>
                        </form>
                    @else
                        <div class="p-3 bg-slate-950 border border-slate-800 rounded-xl text-slate-400">
                            Tiến trình đổi/trả này đã kết thúc.
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- Modal Phóng To Media -->
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 p-4" x-show="previewMedia" style="display: none;">
            <div class="relative max-w-3xl max-h-[85vh] flex flex-col items-center justify-center" @click.away="previewMedia = null">
                <button @click="previewMedia = null" class="absolute -top-10 right-0 text-white font-bold text-2xl">&times;</button>
                <template x-if="mediaType === 'image'">
                    <img :src="previewMedia" class="max-h-[80vh] rounded-2xl object-contain border border-slate-800">
                </template>
                <template x-if="mediaType === 'video'">
                    <video :src="previewMedia" controls autoplay class="max-h-[80vh] rounded-2xl border border-slate-800"></video>
                </template>
            </div>
        </div>
    </div>
</x-admin-layout>