<x-admin-layout>
    <x-slot name="header">Quản lý Danh mục</x-slot>

    <div class="space-y-6">
        @if (session('success'))
            <div class="flex items-center gap-3 bg-emerald-950/60 border border-emerald-800 text-emerald-300 px-4 py-3 rounded-xl text-xs">
                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="flex items-center gap-3 bg-rose-950/60 border border-rose-800 text-rose-300 px-4 py-3 rounded-xl text-xs">
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif

        <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-2xl">
            <div class="p-5 border-b border-slate-800 flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-base text-white">Danh Mục Ngành Hàng</h2>
                    <p class="text-xs text-slate-400">Quản lý nhóm phân loại thiết bị điện máy và công nghệ</p>
                </div>
                <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 bg-gradient-to-r from-rose-600 to-red-500 hover:from-rose-500 hover:to-red-400 text-white rounded-xl text-xs font-semibold shadow-md transition">
                    + Thêm danh mục mới
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-950/60 border-b border-slate-800 text-slate-400 font-semibold uppercase tracking-wider">
                            <th class="py-3.5 px-6">ID</th>
                            <th class="py-3.5 px-6">Tên Danh Mục</th>
                            <th class="py-3.5 px-6">Slug Đường Dẫn</th>
                            <th class="py-3.5 px-6">Số Lượng Sản Phẩm</th>
                            <th class="py-3.5 px-6 text-right">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 text-slate-300">
                        @foreach ($categories as $cat)
                            <tr class="hover:bg-slate-800/40 transition">
                                <td class="py-4 px-6 font-mono text-slate-500">#{{ $cat->id }}</td>
                                <td class="py-4 px-6 font-semibold text-white">{{ $cat->name }}</td>
                                <td class="py-4 px-6 font-mono text-slate-400">{{ $cat->slug }}</td>
                                <td class="py-4 px-6">
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-800 text-indigo-400 border border-slate-700/60">
                                        {{ $cat->products_count }} sản phẩm
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right space-x-2">
                                    <a href="{{ route('admin.categories.edit', $cat) }}" class="text-slate-300 hover:text-white bg-slate-800 px-3 py-1.5 rounded-lg transition">Sửa</a>
                                    <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" class="inline" onsubmit="return confirm('Xóa danh mục này? Lưu ý chỉ xóa được khi không có sản phẩm liên quan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-400 hover:text-rose-300 bg-rose-950/50 border border-rose-800/40 px-3 py-1.5 rounded-lg transition">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-800 bg-slate-950/40">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>