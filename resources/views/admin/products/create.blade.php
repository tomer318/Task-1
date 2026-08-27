<x-app-layout>
    <div class="min-h-screen bg-slate-950 py-8 px-4 sm:px-6 lg:px-8 text-slate-100"
         x-data="{
             variants: [{ version_name: 'Tiêu Chuẩn', color_name: 'Đen', price: 0, stock: 10 }],
             specs: [{ group_name: 'Cấu hình & Bộ nhớ', spec_key: 'Loại CPU', spec_value: '' }],
             addVariant() {
                 this.variants.push({ version_name: '', color_name: '', price: 0, stock: 10 });
             },
             removeVariant(index) {
                 this.variants.splice(index, 1);
             },
             addSpec() {
                 this.specs.push({ group_name: 'Cấu hình & Bộ nhớ', spec_key: '', spec_value: '' });
             },
             removeSpec(index) {
                 this.specs.splice(index, 1);
             }
         }">

        <div class="max-w-5xl mx-auto space-y-6">
            <div class="flex items-center justify-between">
                <h1 class="text-xl font-black text-white">Thêm Sản Phẩm Mới</h1>
                <a href="{{ route('admin.products.index') }}" class="text-xs text-slate-400 hover:text-white">← Quay lại danh sách</a>
            </div>

            <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- 1. Thông tin chung -->
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 space-y-4 shadow-xl">
                    <h2 class="font-bold text-sm text-rose-500 uppercase tracking-wider">1. Thông tin chung</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                        <div>
                            <label class="block font-semibold mb-1 text-slate-300">Tên sản phẩm *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl p-2.5 text-white outline-none focus:border-rose-500">
                        </div>
                        <div>
                            <label class="block font-semibold mb-1 text-slate-300">Slug (để trống sẽ tự tạo)</label>
                            <input type="text" name="slug" value="{{ old('slug') }}" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-2.5 text-white outline-none focus:border-rose-500">
                        </div>
                        <div>
                            <label class="block font-semibold mb-1 text-slate-300">Danh mục *</label>
                            <select name="category_id" required class="w-full bg-slate-950 border border-slate-800 rounded-xl p-2.5 text-white outline-none focus:border-rose-500">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold mb-1 text-slate-300">Thương hiệu *</label>
                            <select name="brand_id" required class="w-full bg-slate-950 border border-slate-800 rounded-xl p-2.5 text-white outline-none focus:border-rose-500">
                                @foreach($brands as $b)
                                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold mb-1 text-slate-300">Giá mặc định (VNĐ) *</label>
                            <input type="number" name="price" value="{{ old('price', 0) }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl p-2.5 text-white outline-none focus:border-rose-500 font-mono">
                        </div>
                        <div>
                            <label class="block font-semibold mb-1 text-slate-300">Tổng tồn kho *</label>
                            <input type="number" name="stock" value="{{ old('stock', 10) }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl p-2.5 text-white outline-none focus:border-rose-500 font-mono">
                        </div>
                    </div>

                    <div class="text-xs">
                        <label class="block font-semibold mb-1 text-slate-300">Mô tả / Tính năng nổi bật</label>
                        <textarea name="description" rows="3" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-2.5 text-white outline-none focus:border-rose-500">{{ old('description') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs pt-2">
                        <div>
                            <label class="block font-semibold mb-1 text-slate-300">Ảnh đại diện chính</label>
                            <input type="file" name="image" class="w-full text-slate-400">
                        </div>
                        <div>
                            <label class="block font-semibold mb-1 text-slate-300">Thư viện ảnh phụ (chọn nhiều)</label>
                            <input type="file" name="images[]" multiple class="w-full text-slate-400">
                        </div>
                    </div>
                </div>

                <!-- 2. Quản lý Biến thể -->
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 space-y-4 shadow-xl">
                    <div class="flex items-center justify-between">
                        <h2 class="font-bold text-sm text-rose-500 uppercase tracking-wider">2. Biến thể (Phiên bản & Màu sắc)</h2>
                        <button type="button" @click="addVariant()" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-500 text-white rounded-lg text-xs font-bold transition">
                            + Thêm biến thể
                        </button>
                    </div>

                    <div class="space-y-3">
                        <template x-for="(v, index) in variants" :key="index">
                            <div class="p-3 bg-slate-950 border border-slate-800 rounded-xl grid grid-cols-1 sm:grid-cols-12 gap-3 items-center text-xs">
                                <div class="sm:col-span-3">
                                    <label class="block text-[10px] text-slate-400 mb-0.5">Phiên bản (RAM/SSD)</label>
                                    <input type="text" :name="`variants[${index}][version_name]`" x-model="v.version_name" placeholder="VD: 8GB 256GB" required class="w-full bg-slate-900 border border-slate-800 rounded-lg p-2 text-white outline-none">
                                </div>
                                <div class="sm:col-span-3">
                                    <label class="block text-[10px] text-slate-400 mb-0.5">Màu sắc</label>
                                    <input type="text" :name="`variants[${index}][color_name]`" x-model="v.color_name" placeholder="VD: Đen, Titan..." required class="w-full bg-slate-900 border border-slate-800 rounded-lg p-2 text-white outline-none">
                                </div>
                                <div class="sm:col-span-3">
                                    <label class="block text-[10px] text-slate-400 mb-0.5">Giá riêng (VNĐ)</label>
                                    <input type="number" :name="`variants[${index}][price]`" x-model="v.price" required class="w-full bg-slate-900 border border-slate-800 rounded-lg p-2 text-white outline-none font-mono">
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block text-[10px] text-slate-400 mb-0.5">Tồn kho</label>
                                    <input type="number" :name="`variants[${index}][stock]`" x-model="v.stock" required class="w-full bg-slate-900 border border-slate-800 rounded-lg p-2 text-white outline-none font-mono">
                                </div>
                                <div class="sm:col-span-1 text-center pt-3">
                                    <button type="button" @click="removeVariant(index)" class="text-rose-500 hover:text-rose-400 text-sm">🗑️</button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- 3. Quản lý Thông số kỹ thuật -->
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 space-y-4 shadow-xl">
                    <div class="flex items-center justify-between">
                        <h2 class="font-bold text-sm text-rose-500 uppercase tracking-wider">3. Bảng thông số kỹ thuật</h2>
                        <button type="button" @click="addSpec()" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold transition">
                            + Thêm dòng thông số
                        </button>
                    </div>

                    <div class="space-y-3">
                        <template x-for="(s, index) in specs" :key="index">
                            <div class="p-3 bg-slate-950 border border-slate-800 rounded-xl grid grid-cols-1 sm:grid-cols-12 gap-3 items-center text-xs">
                                <div class="sm:col-span-3">
                                    <label class="block text-[10px] text-slate-400 mb-0.5">Nhóm thông số</label>
                                    <select :name="`specifications[${index}][group_name]`" x-model="s.group_name" class="w-full bg-slate-900 border border-slate-800 rounded-lg p-2 text-white outline-none">
                                        <option value="Cấu hình & Bộ nhớ">Cấu hình & Bộ nhớ</option>
                                        <option value="Màn hình">Màn hình</option>
                                        <option value="Camera">Camera</option>
                                        <option value="Pin & Tiện ích">Pin & Tiện ích</option>
                                        <option value="Cổng giao tiếp & Pin">Cổng giao tiếp & Pin</option>
                                        <option value="Âm thanh & Tiện ích">Âm thanh & Tiện ích</option>
                                        <option value="Cấu hình & Đồ họa">Cấu hình & Đồ họa</option>
                                    </select>
                                </div>
                                <div class="sm:col-span-4">
                                    <label class="block text-[10px] text-slate-400 mb-0.5">Tên thông số (Key)</label>
                                    <input type="text" :name="`specifications[${index}][spec_key]`" x-model="s.spec_key" placeholder="VD: Loại CPU, RAM..." required class="w-full bg-slate-900 border border-slate-800 rounded-lg p-2 text-white outline-none">
                                </div>
                                <div class="sm:col-span-4">
                                    <label class="block text-[10px] text-slate-400 mb-0.5">Giá trị (Value)</label>
                                    <input type="text" :name="`specifications[${index}][spec_value]`" x-model="s.spec_value" placeholder="VD: Intel Core Ultra 5..." required class="w-full bg-slate-900 border border-slate-800 rounded-lg p-2 text-white outline-none">
                                </div>
                                <div class="sm:col-span-1 text-center pt-3">
                                    <button type="button" @click="removeSpec(index)" class="text-rose-500 hover:text-rose-400 text-sm">🗑️</button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.products.index') }}" class="px-6 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl text-xs font-semibold">Hủy</a>
                    <button type="submit" class="px-8 py-2.5 bg-gradient-to-r from-rose-600 to-red-500 text-white font-bold rounded-xl text-xs shadow-lg shadow-rose-600/30">
                        Lưu Sản Phẩm
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>