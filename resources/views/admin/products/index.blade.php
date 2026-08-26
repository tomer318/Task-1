<x-admin-layout>
    <x-slot name="header">Quản lý Sản phẩm</x-slot>

    <div class="space-y-6">
        @if (session('success'))
            <div class="flex items-center gap-3 bg-emerald-950/60 border border-emerald-800 text-emerald-300 px-4 py-3 rounded-xl text-xs">
                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-2xl">
            <div class="p-5 border-b border-slate-800 flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-base text-white">Danh Sách Thiết Bị Công Nghệ</h2>
                    <p class="text-xs text-slate-400">Quản lý 1,000+ sản phẩm mẫu điện máy và đồ gia dụng</p>
                </div>
                <a href="{{ route('admin.products.create') }}" class="px-4 py-2 bg-gradient-to-r from-rose-600 to-red-500 hover:from-rose-500 hover:to-red-400 text-white rounded-xl text-xs font-semibold shadow-md transition">
                    + Thêm sản phẩm mới
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-950/60 border-b border-slate-800 text-slate-400 font-semibold uppercase tracking-wider">
                            <th class="py-3.5 px-5">ID</th>
                            <th class="py-3.5 px-5">Tên sản phẩm</th>
                            <th class="py-3.5 px-5">Danh mục</th>
                            <th class="py-3.5 px-5">Hãng (Brand)</th>
                            <th class="py-3.5 px-5">Giá bán ($)</th>
                            <th class="py-3.5 px-5">Tồn kho</th>
                            <th class="py-3.5 px-5 text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 text-slate-300">
                        @foreach ($products as $p)
                            <tr class="hover:bg-slate-800/40 transition">
                                <td class="py-3.5 px-5 font-mono text-slate-500">#{{ $p->id }}</td>
                                <td class="py-3.5 px-5 font-semibold text-white">{{ $p->name }}</td>
                                <td class="py-3.5 px-5 text-slate-400">{{ $p->category->name ?? 'N/A' }}</td>
                                <td class="py-3.5 px-5">
                                    <span class="px-2 py-0.5 rounded-md text-xs bg-slate-800 text-rose-300 border border-slate-700 font-medium">
                                        {{ $p->brand->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 font-mono font-bold text-rose-400">${{ number_format($p->price, 2) }}</td>
                                <td class="py-3.5 px-5">
                                    <span class="px-2 py-0.5 rounded-md text-[11px] bg-slate-800 text-emerald-400 font-mono">{{ $p->stock }}</span>
                                </td>
                                <td class="py-3.5 px-5 text-right space-x-2">
                                    <a href="{{ route('admin.products.edit', $p) }}" class="text-slate-300 hover:text-white bg-slate-800 px-2.5 py-1 rounded-lg">Sửa</a>
                                    <form action="{{ route('admin.products.destroy', $p) }}" method="POST" class="inline" onsubmit="return confirm('Xóa sản phẩm này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-400 hover:text-rose-300 bg-rose-950/50 border border-rose-800/40 px-2.5 py-1 rounded-lg">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-800 bg-slate-950/40">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>