<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Thêm sản phẩm mới</h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <form action="{{ route('admin.products.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block font-medium text-sm text-gray-700">Tên sản phẩm</label>
                    <input type="text" name="name" required class="w-full border-gray-300 rounded-md shadow-sm mt-1" value="{{ old('name') }}">
                </div>
                <div>
                    <label class="block font-medium text-sm text-gray-700">Danh mục</label>
                    <select name="category_id" required class="w-full border-gray-300 rounded-md shadow-sm mt-1">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium text-sm text-gray-700">Giá bán</label>
                        <input type="number" step="0.01" name="price" required class="w-full border-gray-300 rounded-md shadow-sm mt-1" value="{{ old('price') }}">
                    </div>
                    <div>
                        <label class="block font-medium text-sm text-gray-700">Số lượng kho</label>
                        <input type="number" name="stock" required class="w-full border-gray-300 rounded-md shadow-sm mt-1" value="{{ old('stock', 0) }}">
                    </div>
                </div>
                <div>
                    <label class="block font-medium text-sm text-gray-700">Mô tả</label>
                    <textarea name="description" rows="3" class="w-full border-gray-300 rounded-md shadow-sm mt-1">{{ old('description') }}</textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <a href="{{ route('admin.products.index') }}" class="px-4 py-2 bg-gray-300 rounded-md text-sm">Hủy</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-semibold">Lưu sản phẩm</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>