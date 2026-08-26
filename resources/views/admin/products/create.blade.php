<x-admin-layout>
    <x-slot name="header">Thêm Sản Phẩm Mới</x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-2xl">
            <h2 class="font-bold text-base text-white mb-4 pb-3 border-b border-slate-800 flex items-center justify-between">
                <span>Thông Tin Thiết Bị Mới</span>
                <span class="text-xs font-normal text-slate-400">Nhập đầy đủ thông tin chi tiết</span>
            </h2>

            <form action="{{ route('admin.products.store') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Tên sản phẩm -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Tên sản phẩm</label>
                    <input type="text" name="name" required placeholder="Ví dụ: iPhone 16 Pro Max 256GB..." 
                           value="{{ old('name') }}"
                           class="w-full bg-slate-950/80 border border-slate-800 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 rounded-xl text-sm text-white placeholder-slate-600 px-4 py-2.5 transition outline-none">
                    @error('name') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
                </div>

                <!-- Danh mục & Hãng sản xuất -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Danh mục ngành hàng</label>
                        <select name="category_id" required class="w-full bg-slate-950/80 border border-slate-800 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 rounded-xl text-sm text-white px-4 py-2.5 transition outline-none">
                            <option value="" class="bg-slate-900 text-slate-400">-- Chọn danh mục --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }} class="bg-slate-900 text-white">
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Thương hiệu / Hãng</label>
                        <select name="brand_id" class="w-full bg-slate-950/80 border border-slate-800 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 rounded-xl text-sm text-white px-4 py-2.5 transition outline-none">
                            <option value="" class="bg-slate-900 text-slate-400">-- Chọn hãng (Brand) --</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }} class="bg-slate-900 text-white">
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('brand_id') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Giá & Tồn kho -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Giá bán ($)</label>
                        <input type="number" step="0.01" name="price" required placeholder="0.00" 
                               value="{{ old('price') }}"
                               class="w-full bg-slate-950/80 border border-slate-800 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 rounded-xl text-sm text-white placeholder-slate-600 px-4 py-2.5 transition outline-none font-mono">
                        @error('price') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Số lượng kho</label>
                        <input type="number" name="stock" required placeholder="0" 
                               value="{{ old('stock', 0) }}"
                               class="w-full bg-slate-950/80 border border-slate-800 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 rounded-xl text-sm text-white placeholder-slate-600 px-4 py-2.5 transition outline-none font-mono">
                        @error('stock') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Mô tả -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Mô tả sản phẩm</label>
                    <textarea name="description" rows="4" placeholder="Nhập thông số kỹ thuật, bảo hành chính hãng..." 
                              class="w-full bg-slate-950/80 border border-slate-800 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 rounded-xl text-sm text-white placeholder-slate-600 px-4 py-2.5 transition outline-none">{{ old('description') }}</textarea>
                    @error('description') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-800">
                    <a href="{{ route('admin.products.index') }}" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-xs font-semibold transition">Hủy bỏ</a>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-rose-600 to-red-500 hover:from-rose-500 hover:to-red-400 text-white rounded-xl text-xs font-semibold shadow-md transition">Lưu Sản Phẩm</button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>