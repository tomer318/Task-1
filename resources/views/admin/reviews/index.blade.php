<x-admin-layout>
    <x-slot name="header">Quản Lý Đánh Giá Khách Hàng</x-slot>

    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-2xl space-y-4">
        <h2 class="font-bold text-sm text-white">Danh Sách Khách Hàng Đã Đánh Giá</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-950 text-slate-400 uppercase font-mono border-b border-slate-800">
                    <tr>
                        <th class="px-6 py-4">Khách Hàng</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4 text-center">Số Lượt Đánh Giá</th>
                        <th class="px-6 py-4 text-right">Thao Tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-slate-300">
                    @forelse($users as $u)
                        <tr class="hover:bg-slate-800/40 transition">
                            <td class="px-6 py-4 font-bold text-white">{{ $u->name }}</td>
                            <td class="px-6 py-4 font-mono text-slate-400">{{ $u->email }}</td>
                            <td class="px-6 py-4 text-center font-bold text-emerald-400">{{ $u->product_reviews_count }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.reviews.user', $u->id) }}" class="px-3.5 py-1.5 bg-rose-600 hover:bg-rose-500 text-white font-bold rounded-xl text-xs transition">
                                    Xem Chi Tiết &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-500">Chưa có khách hàng nào để lại đánh giá.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div>{{ $users->links() }}</div>
    </div>
</x-admin-layout>