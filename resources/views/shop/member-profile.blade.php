<x-store-layout>
    <div class="space-y-6">

        <!-- Top Header Card: Thông tin thành viên & Tích lũy -->
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-2xl">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                
                <!-- Avatar & Tên -->
                <div class="md:col-span-5 flex items-center gap-4">
                    <div class="relative">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-rose-600 to-orange-500 p-0.5 shadow-lg shadow-rose-600/30">
                            <div class="w-full h-full bg-slate-950 rounded-2xl flex items-center justify-center font-black text-2xl text-rose-500">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        </div>
                        <span class="absolute -bottom-1 -right-1 px-1.5 py-0.5 bg-rose-600 text-white text-[9px] font-black uppercase rounded-md tracking-wider">
                            S-VIP
                        </span>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-white flex items-center gap-2">
                            {{ $user->name }}
                        </h1>
                        <p class="text-xs text-slate-400 font-mono mt-0.5">{{ $user->email }}</p>
                        <span class="inline-block mt-1.5 px-2 py-0.5 bg-slate-800 text-rose-400 text-[10px] font-bold rounded-md border border-slate-700">
                            Thành viên TechZone Club
                        </span>
                    </div>
                </div>

                <!-- Thống kê: Đơn hàng & Chi tiêu -->
                <div class="md:col-span-7 grid grid-cols-2 gap-4 border-t md:border-t-0 md:border-l border-slate-800 pt-4 md:pt-0 md:pl-6">
                    <div class="bg-slate-950/60 border border-slate-800/80 rounded-2xl p-4 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-rose-500/10 border border-rose-500/20 flex items-center justify-center text-lg">
                            📦
                        </div>
                        <div>
                            <div class="text-xl font-mono font-extrabold text-white">{{ $ordersCount ?? 0 }}</div>
                            <div class="text-[11px] text-slate-400">Đơn hàng đã mua</div>
                        </div>
                    </div>

                    <div class="bg-slate-950/60 border border-slate-800/80 rounded-2xl p-4 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-lg">
                            💎
                        </div>
                        <div>
                            <div class="text-xl font-mono font-extrabold text-emerald-400">${{ number_format($totalSpent ?? 0, 2) }}</div>
                            <div class="text-[11px] text-slate-400">Tổng chi tiêu tích lũy</div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Quick Action Tags -->
            <div class="flex items-center gap-3 overflow-x-auto pt-6 mt-6 border-t border-slate-800 text-xs font-semibold scrollbar-none">
                <span class="px-3 py-1.5 bg-slate-950 text-rose-400 border border-rose-500/30 rounded-xl flex items-center gap-1.5">
                    ✨ Hạng: Tech-Diamond
                </span>
                <span class="px-3 py-1.5 bg-slate-950 text-slate-300 border border-slate-800 rounded-xl flex items-center gap-1.5">
                    🎟️ 03 Mã giảm giá sẵn có
                </span>
                <span class="px-3 py-1.5 bg-slate-950 text-slate-300 border border-slate-800 rounded-xl flex items-center gap-1.5">
                    📍 Sổ địa chỉ nhận hàng
                </span>
                <span class="px-3 py-1.5 bg-slate-950 text-slate-300 border border-slate-800 rounded-xl flex items-center gap-1.5">
                    🎓 Đặc quyền S-Student
                </span>
            </div>
        </div>

        <!-- Main Body: Sidebar Tabs & Content Sections -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- Sidebar Navigation -->
            <div class="lg:col-span-4 space-y-2">
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-3 space-y-1 text-xs font-semibold">
                    <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl bg-gradient-to-r from-rose-600 to-red-500 text-white shadow-md">
                        <span>🏠</span>
                        <span>Tổng quan tài khoản</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-300 hover:bg-slate-800 hover:text-white transition">
                        <span>📜</span>
                        <span>Lịch sử mua hàng</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-300 hover:bg-slate-800 hover:text-white transition">
                        <span>🛡️</span>
                        <span>Tra cứu bảo hành & thiết bị</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-300 hover:bg-slate-800 hover:text-white transition">
                        <span>🎁</span>
                        <span>Ưu đãi & Hạng thành viên</span>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-300 hover:bg-slate-800 hover:text-white transition">
                        <span>⚙️</span>
                        <span>Thông tin tài khoản & Mật khẩu</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="pt-2 border-t border-slate-800">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-3.5 py-2 rounded-xl text-rose-400 hover:bg-rose-500/10 transition">
                            <span>🚪</span>
                            <span>Đăng xuất tài khoản</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Content Area: Đơn hàng & Tiện ích -->
            <div class="lg:col-span-8 space-y-6">

                <!-- Block: Đơn hàng gần đây -->
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                        <h2 class="font-bold text-sm text-white flex items-center gap-2">
                            <span>📦</span> Đơn Hàng Gần Đây
                        </h2>
                        <a href="/" class="text-xs text-rose-400 hover:underline">Tiếp tục mua sắm →</a>
                    </div>

                    @if(isset($recentOrders) && $recentOrders->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentOrders as $order)
                                <div class="p-4 bg-slate-950 border border-slate-800 rounded-xl flex items-center justify-between">
                                    <div>
                                        <div class="text-xs font-mono text-slate-400">Mã: #{{ $order->id }} • {{ $order->created_at->format('d/m/Y') }}</div>
                                        <div class="text-sm font-bold text-white mt-1">${{ number_format($order->total_price, 2) }}</div>
                                    </div>
                                    <span class="px-2.5 py-1 bg-emerald-500/10 text-emerald-400 text-[11px] font-bold rounded-lg">Đã giao thành công</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Demo Mockup Item theo mẫu CellphoneS -->
                        <div class="p-4 bg-slate-950/80 border border-slate-800 rounded-2xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-3.5">
                                <div class="w-14 h-14 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-center text-2xl shrink-0">
                                    📱
                                </div>
                                <div>
                                    <div class="text-[11px] font-mono text-slate-500">Đơn hàng: #TZ2026082601 • 26/08/2026</div>
                                    <h3 class="text-xs font-bold text-white mt-0.5">XIAOMI REDMI NOTE 14 PRO 5G 256GB</h3>
                                    <span class="inline-block mt-1 px-2 py-0.5 bg-emerald-500/10 text-emerald-400 text-[10px] font-bold rounded">Đã nhận hàng</span>
                                </div>
                            </div>
                            <div class="text-right w-full sm:w-auto border-t sm:border-t-0 border-slate-800 pt-2 sm:pt-0">
                                <div class="text-xs text-slate-400">Tổng thanh toán:</div>
                                <div class="text-sm font-mono font-extrabold text-rose-500">$389.00</div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Block: Ưu đãi & Sản phẩm yêu thích -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 text-center space-y-3">
                        <div class="w-12 h-12 rounded-2xl bg-rose-500/10 border border-rose-500/20 flex items-center justify-center text-xl mx-auto">
                            🎁
                        </div>
                        <h3 class="font-bold text-xs text-white">Ưu Đãi Của Bạn</h3>
                        <p class="text-[11px] text-slate-500">Bạn đang có 2 voucher giảm 10% cho phụ kiện gaming.</p>
                        <button class="px-4 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-xl transition">Xem kho Voucher</button>
                    </div>

                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 text-center space-y-3">
                        <div class="w-12 h-12 rounded-2xl bg-orange-500/10 border border-orange-500/20 flex items-center justify-center text-xl mx-auto">
                            ❤️
                        </div>
                        <h3 class="font-bold text-xs text-white">Sản Phẩm Yêu Thích</h3>
                        <p class="text-[11px] text-slate-500">Chưa có sản phẩm nào trong danh sách quan tâm.</p>
                        <a href="/" class="inline-block px-4 py-1.5 bg-rose-600 hover:bg-rose-500 text-white text-xs font-semibold rounded-xl transition">Khám phá ngay</a>
                    </div>
                </div>

            </div>

        </div>

    </div>
</x-store-layout>