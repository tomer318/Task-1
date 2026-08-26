<x-admin-layout>
    <x-slot name="header">Thêm Danh Mục Mới</x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-2xl">
            <h2 class="font-bold text-base text-white mb-4 pb-3 border-b border-slate-800">Tạo Nhóm Ngành Hàng Mới</h2>
            
            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Tên danh mục</label>
                    <input type="text" name="name" required placeholder="Ví dụ: Thiết bị đeo thông minh..." 
                           class="w-full bg-slate-950/80 border border-slate-800 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 rounded-xl text-sm text-white placeholder-slate-600 px-4 py-2.5 transition outline-none" 
                           value="{{ old('name') }}">
                    @error('name')
                        <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3 pt-3 border-t border-slate-800">
                    <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-xs font-semibold transition">Hủy bỏ</a>
                    <button type="submit" class="px-5 py-2 bg-gradient-to-r from-rose-600 to-red-500 hover:from-rose-500 hover:to-red-400 text-white rounded-xl text-xs font-semibold shadow-md transition">Lưu Danh Mục</button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>