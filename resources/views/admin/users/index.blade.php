<x-admin-layout>
    <x-slot name="header">Quản lý Người Dùng & Role</x-slot>

    <div class="space-y-6">
        @if (session('success'))
            <div class="flex items-center gap-3 bg-emerald-950/60 border border-emerald-800 text-emerald-300 px-4 py-3 rounded-xl text-xs">
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-2xl">
            <div class="p-5 border-b border-slate-800 flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-base text-white">Danh Sách Thành Viên & Phân Quyền</h2>
                    <p class="text-xs text-slate-400">Quản lý tài khoản khách hàng và phân quyền hệ thống</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-950/60 border-b border-slate-800 text-slate-400 font-semibold uppercase tracking-wider">
                            <th class="py-3.5 px-6">ID</th>
                            <th class="py-3.5 px-6">Họ Tên</th>
                            <th class="py-3.5 px-6">Email</th>
                            <th class="py-3.5 px-6">Ngày Đăng Ký</th>
                            <th class="py-3.5 px-6 text-right">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 text-slate-300">
                        @foreach ($users as $u)
                            <tr class="hover:bg-slate-800/40 transition">
                                <td class="py-4 px-6 font-mono text-slate-500">#{{ $u->id }}</td>
                                <td class="py-4 px-6 font-semibold text-white">{{ $u->name }}</td>
                                <td class="py-4 px-6 font-mono text-slate-400">{{ $u->email }}</td>
                                <td class="py-4 px-6 font-mono text-slate-500">{{ $u->created_at->format('d/m/Y H:i') }}</td>
                                <td class="py-4 px-6 text-right space-x-2">
                                    <form action="{{ route('admin.users.destroy', $u) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc muốn xóa tài khoản này?');">
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
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>