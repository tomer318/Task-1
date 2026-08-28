<x-admin-layout>
    <x-slot name="header">Đánh Giá Của {{ $user->name }}</x-slot>

    <div class="space-y-6">
        <a href="{{ route('admin.reviews.index') }}" class="text-xs text-slate-400 hover:text-white font-semibold">&larr; Quay lại danh sách khách hàng</a>

        <div class="space-y-4">
            @foreach($productReviews as $pr)
                <div class="p-5 bg-slate-900 border border-slate-800 rounded-3xl shadow-xl space-y-3 text-xs">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="font-bold text-white text-sm uppercase">{{ $pr->product->name ?? 'Sản phẩm' }}</span>
                            <span class="text-slate-400 block mt-0.5">Đơn hàng: #{{ $pr->order->order_code ?? $pr->order_id }}</span>
                        </div>
                        <span class="px-2.5 py-1 rounded-full font-bold {{ $pr->is_active ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' : 'bg-rose-500/10 text-rose-400 border border-rose-500/30' }}">
                            {{ $pr->is_active ? 'Đang hiển thị' : 'Đã ẩn do vi phạm' }}
                        </span>
                    </div>

                    <p class="text-slate-300">{{ $pr->comment }}</p>

                    @if(is_array($pr->images) && count($pr->images) > 0)
                        <div class="flex gap-2">
                            @foreach($pr->images as $img)
                                <img src="{{ asset('storage/' . $img) }}" class="w-16 h-16 object-cover rounded-xl border border-slate-800">
                            @endforeach
                        </div>
                    @endif

                    <div class="pt-2 border-t border-slate-800 flex justify-end">
                        <form action="{{ route('admin.reviews.product.toggle', $pr->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-4 py-2 {{ $pr->is_active ? 'bg-rose-600 hover:bg-rose-700' : 'bg-emerald-600 hover:bg-emerald-700' }} text-white font-bold rounded-xl">
                                {{ $pr->is_active ? 'Ẩn Đánh Giá Vi Phạm' : 'Khôi Phục Đánh Giá' }}
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-admin-layout>