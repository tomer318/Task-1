<x-admin-layout>
    <x-slot name="header">Thêm Mã Giảm Giá Mới</x-slot>

    <div class="max-w-3xl mx-auto space-y-6">
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-2xl">
            <h2 class="text-base font-bold text-white mb-4">Thông tin mã ưu đãi</h2>

            <form action="{{ route('admin.coupons.store') }}" method="POST" class="space-y-4 text-xs">
                @csrf

                <div>
                    <label class="block text-slate-300 font-semibold mb-1">Mã Code (viết liền không dấu)</label>
                    <input type="text" name="code" value="{{ old('code') }}" required placeholder="VD: TECHZONE50, SALE10" 
                           class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-white uppercase focus:ring-rose-500 focus:border-rose-500 font-mono">
                    @error('code') <span class="text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-slate-300 font-semibold mb-1">Loại giảm giá</label>
                        <select name="type" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-white focus:ring-rose-500 focus:border-rose-500">
                            <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>Tiền mặt cố định (₫)</option>
                            <option value="percent" {{ old('type') === 'percent' ? 'selected' : '' }}>Phần trăm (%)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-300 font-semibold mb-1">Mức giảm</label>
                        <input type="number" step="any" name="value" value="{{ old('value') }}" required placeholder="VD: 50000 hoặc 10" 
                               class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-white focus:ring-rose-500 focus:border-rose-500 font-mono">
                        @error('value') <span class="text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-slate-300 font-semibold mb-1">Đơn tối thiểu (₫)</label>
                        <input type="number" name="min_order_value" value="{{ old('min_order_value', 0) }}" placeholder="0" 
                               class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-white focus:ring-rose-500 focus:border-rose-500 font-mono">
                    </div>
                    <div>
                        <label class="block text-slate-300 font-semibold mb-1">Giới hạn số lượt</label>
                        <input type="number" name="usage_limit" value="{{ old('usage_limit') }}" placeholder="Để trống = Không giới hạn" 
                               class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-white focus:ring-rose-500 focus:border-rose-500 font-mono">
                    </div>
                    <div>
                        <label class="block text-slate-300 font-semibold mb-1">Hạn sử dụng</label>
                        <input type="date" name="expires_at" value="{{ old('expires_at') }}" 
                               class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-white focus:ring-rose-500 focus:border-rose-500">
                    </div>
                </div>

                <div class="pt-2">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 rounded text-rose-600 bg-slate-950 border-slate-800 focus:ring-0">
                        <span class="text-slate-300 font-semibold">Kích hoạt mã ngay sau khi tạo</span>
                    </label>
                </div>

                <div class="pt-4 border-t border-slate-800 flex justify-end gap-3">
                    <a href="{{ route('admin.coupons.index') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-semibold transition">Hủy</a>
                    <button type="submit" class="px-5 py-2 bg-gradient-to-r from-rose-600 to-red-500 text-white font-bold rounded-xl shadow-lg transition">Tạo mã</button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>