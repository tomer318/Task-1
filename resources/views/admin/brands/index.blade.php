<x-admin-layout>
    <x-slot name="header">Quản lý Thương hiệu (Hãng)</x-slot>

    <div class="space-y-6">
        @if (session('success'))
            <div class="flex items-center gap-3 bg-emerald-950/60 border border-emerald-800 text-emerald-300 px-4 py-3 rounded-xl text-xs">
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
                    <h2 class="font-bold text-base text-white">Danh Sách Hãng Sản Xuất</h2>
                    <p class="text-xs text-slate-400">Các thương hiệu Apple, Samsung, Xiaomi, Asus, Sony, LG...</p>
                </div>
                <a href="{{ route('admin.brands.create') }}" class="px-4 py-2 bg-gradient-to-r from-rose-600 to-red-500 hover:from-rose-500 hover:to-red-400 text-white rounded-xl text-xs font-semibold shadow-md transition">
                    + Thêm thương hiệu mới
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-950/60 border-b border-slate-800 text-slate-400 font-semibold uppercase tracking-wider">
                            <th class="py-3.5 px-6">ID</th>
                            <th class="py-3.5 px-6">Tên Hãng</th>
                            <th class="py-3.5 px-6">Slug Định Danh</th>
                            <th class="py-3.5 px-6">Số Lượng Sản Phẩm</th>
                            <th class="py-3.5 px-6 text-right">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 text-slate-300">
                        @foreach ($brands as $brand)
                            <tr class="hover:bg-slate-800/40 transition">
                                <td class="py-4 px-6 font-mono text-slate-500">#{{ $brand->id }}</td>
                                <td class="py-4 px-6 font-bold text-white flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                    {{ $brand->name }}
                                </td>
                                <td class="py-4 px-6 font-mono text-slate-400">{{ $brand->slug }}</td>
                                <td class="py-4 px-6">
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-800 text-emerald-400 border border-slate-700/60">
                                        {{ $brand->products_count }} sản phẩm
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right space-x-2">
                                    <a href="{{ route('admin.brands.edit', $brand) }}" class="text-slate-300 hover:text-white bg-slate-800 px-3 py-1.5 rounded-lg transition">Sửa</a>
                                    <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" class="inline" onsubmit="return confirm('Xóa thương hiệu này?');">
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
                {{ $brands->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>