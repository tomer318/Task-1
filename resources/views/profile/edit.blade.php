<x-store-layout>
    <div class="space-y-6" x-data="{ 
        tab: 'user-info', 
        orderFilter: 'all', 
        warrantyFilter: 'all',
        showEditModal: false,
        showPasswordModal: false,
        showAddressModal: false,
        selectedLabel: 'Nhà',
        showCurrentPass: false,
        showNewPass: false,
        showConfirmPass: false
    }">

        <!-- Toast thông báo -->
        @if (session('status') === 'profile-updated')
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl text-emerald-400 text-xs font-semibold flex items-center justify-between">
                <span>✓ Đã cập nhật thông tin tài khoản thành công!</span>
            </div>
        @elseif (session('status') === 'password-updated')
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl text-emerald-400 text-xs font-semibold flex items-center justify-between">
                <span>✓ Đã đổi mật khẩu thành công!</span>
            </div>
        @elseif (session('status') === 'address-created')
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl text-emerald-400 text-xs font-semibold flex items-center justify-between">
                <span>✓ Đã thêm địa chỉ nhận hàng mới!</span>
            </div>
        @elseif (session('status') === 'address-deleted')
            <div class="p-4 bg-rose-500/10 border border-rose-500/30 rounded-2xl text-rose-400 text-xs font-semibold flex items-center justify-between">
                <span>✓ Đã xóa địa chỉ thành công!</span>
            </div>
        @endif

        <!-- Top Member Header -->
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-2xl">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                <div class="md:col-span-5 flex items-center gap-4">
                    <div class="relative shrink-0">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-rose-600 to-orange-500 p-0.5 shadow-lg shadow-rose-600/30">
                            <div class="w-full h-full bg-slate-950 rounded-2xl flex items-center justify-center font-black text-2xl text-rose-500">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        </div>
                        <span class="absolute -bottom-1 -right-1 px-1.5 py-0.5 bg-rose-600 text-white text-[9px] font-black uppercase rounded-md tracking-wider">
                            S-NULL
                        </span>
                    </div>
                    <div>
                        <h1 class="text-base sm:text-lg font-bold text-white leading-tight">{{ $user->name }}</h1>
                        <p class="text-xs text-slate-400 font-mono mt-0.5">{{ $user->phone ?? '077*****15' }}</p>
                        <div class="flex items-center gap-2 mt-1.5">
                            <span class="px-2 py-0.5 bg-slate-800 text-rose-400 text-[10px] font-bold rounded border border-slate-700">S-NULL</span>
                            <span class="text-[10px] text-slate-500">Cập nhật lại sau 01/01/2027</span>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-7 grid grid-cols-2 gap-4 border-t md:border-t-0 md:border-l border-slate-800 pt-4 md:pt-0 md:pl-6">
                    <div class="bg-slate-950/70 border border-slate-800/80 rounded-2xl p-4 flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-rose-500/10 border border-rose-500/20 flex items-center justify-center text-lg">🛒</div>
                        <div>
                            <div class="text-xl font-mono font-extrabold text-white">1</div>
                            <div class="text-[11px] text-slate-400">Tổng số đơn hàng đã mua</div>
                        </div>
                    </div>

                    <div class="bg-slate-950/70 border border-slate-800/80 rounded-2xl p-4 flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-lg">💰</div>
                        <div>
                            <div class="text-xl font-mono font-extrabold text-emerald-400">0₫</div>
                            <div class="text-[11px] text-slate-400">Tổng tiền tích lũy</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2 overflow-x-auto pt-5 mt-5 border-t border-slate-800 text-xs font-semibold scrollbar-none">
                <button @click="tab = 'membership'" class="px-3.5 py-1.5 bg-slate-950 hover:bg-slate-800 border border-slate-800 rounded-xl text-slate-300 flex items-center gap-1.5 transition">💎 Hạng thành viên</button>
                <button @click="tab = 'membership'" class="px-3.5 py-1.5 bg-slate-950 hover:bg-slate-800 border border-slate-800 rounded-xl text-slate-300 flex items-center gap-1.5 transition">🎟️ Mã giảm giá</button>
                <button @click="tab = 'orders'" class="px-3.5 py-1.5 bg-slate-950 hover:bg-slate-800 border border-slate-800 rounded-xl text-slate-300 flex items-center gap-1.5 transition">📜 Lịch sử mua hàng</button>
                <button @click="tab = 'user-info'" class="px-3.5 py-1.5 bg-slate-950 hover:bg-slate-800 border border-slate-800 rounded-xl text-slate-300 flex items-center gap-1.5 transition">📍 Sổ địa chỉ</button>
            </div>
        </div>

        <!-- Main Body Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-4 space-y-3">
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-2.5 space-y-1 text-xs font-semibold shadow-xl">
                    <button @click="tab = 'overview'" :class="tab === 'overview' ? 'bg-gradient-to-r from-rose-600 to-red-500 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white'" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition text-left">
                        <span>🏠</span><span>Tổng quan</span>
                    </button>
                    <button @click="tab = 'orders'" :class="tab === 'orders' ? 'bg-gradient-to-r from-rose-600 to-red-500 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white'" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition text-left">
                        <span>📜</span><span>Lịch sử mua hàng</span>
                    </button>
                    <button @click="tab = 'warranty'" :class="tab === 'warranty' ? 'bg-gradient-to-r from-rose-600 to-red-500 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white'" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition text-left">
                        <span>🛡️</span><span>Tra cứu bảo hành</span>
                    </button>
                    <button @click="tab = 'membership'" :class="tab === 'membership' ? 'bg-gradient-to-r from-rose-600 to-red-500 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white'" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition text-left">
                        <span>💎</span><span>Hạng thành viên và ưu đãi</span>
                    </button>
                    <button @click="tab = 'user-info'" :class="tab === 'user-info' ? 'bg-gradient-to-r from-rose-600 to-red-500 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white'" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition text-left">
                        <span>⚙️</span><span>Thông tin tài khoản</span>
                    </button>
                    <form method="POST" action="{{ route('logout') }}" class="pt-2 border-t border-slate-800">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-3.5 py-2 rounded-xl text-rose-400 hover:bg-rose-500/10 transition text-left">
                            <span>🚪</span><span>Đăng xuất</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Content Area -->
            <div class="lg:col-span-8">

                <!-- 1. TAB: THÔNG TIN TÀI KHOẢN (USER-INFO) -->
                <div x-show="tab === 'user-info'" class="space-y-6">
                    <div class="p-4 bg-slate-900 border border-slate-800 rounded-2xl flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2 text-slate-300">
                            <span class="text-rose-500">ℹ️</span>
                            <span>Vui lòng cập nhật đầy đủ thông tin để có trải nghiệm tốt hơn.</span>
                        </div>
                        <button @click="showEditModal = true" class="text-rose-400 hover:underline font-semibold">Cập nhật</button>
                    </div>

                    <!-- Card 1: Thông tin cá nhân -->
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                            <h2 class="font-bold text-sm text-white">Thông tin cá nhân</h2>
                            <button @click="showEditModal = true" class="text-xs text-rose-400 hover:text-rose-300 flex items-center gap-1 font-semibold">
                                ✏️ Cập nhật
                            </button>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-8 text-xs">
                            <div class="flex justify-between sm:justify-start sm:gap-6 border-b sm:border-0 border-slate-800/60 pb-2 sm:pb-0">
                                <span class="text-slate-400 w-28 shrink-0">Họ và tên:</span>
                                <span class="text-white font-semibold">{{ $user->name }}</span>
                            </div>
                            <div class="flex justify-between sm:justify-start sm:gap-6 border-b sm:border-0 border-slate-800/60 pb-2 sm:pb-0">
                                <span class="text-slate-400 w-28 shrink-0">Số điện thoại:</span>
                                <span class="text-white font-mono">{{ $user->phone ?? 'Chưa cập nhật' }}</span>
                            </div>
                            <div class="flex justify-between sm:justify-start sm:gap-6 border-b sm:border-0 border-slate-800/60 pb-2 sm:pb-0">
                                <span class="text-slate-400 w-28 shrink-0">Giới tính:</span>
                                <span class="text-slate-300">{{ $user->gender ?? 'Chưa cập nhật' }}</span>
                            </div>
                            <div class="flex justify-between sm:justify-start sm:gap-6 border-b sm:border-0 border-slate-800/60 pb-2 sm:pb-0">
                                <span class="text-slate-400 w-28 shrink-0">Email:</span>
                                <span class="text-white font-mono">{{ $user->email }}</span>
                            </div>
                            <div class="flex justify-between sm:justify-start sm:gap-6">
                                <span class="text-slate-400 w-28 shrink-0">Ngày sinh:</span>
                                <span class="text-white font-mono">{{ $user->birthday ? \Carbon\Carbon::parse($user->birthday)->format('d/m/Y') : 'Chưa cập nhật' }}</span>
                            </div>
                            <div class="flex justify-between sm:justify-start sm:gap-6">
                                <span class="text-slate-400 w-28 shrink-0">Địa chỉ mặc định:</span>
                                <span class="text-slate-300 line-clamp-1">
                                    {{ $user->addresses ? ($user->addresses->where('is_default', true)->first()?->address_detail ?? ($user->addresses->first()?->address_detail ?? 'Chưa thiết lập')) : 'Chưa thiết lập' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Sổ địa chỉ -->
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                            <h2 class="font-bold text-sm text-white">Sổ địa chỉ</h2>
                            <button @click="showAddressModal = true" class="text-xs text-rose-400 hover:text-rose-300 font-semibold flex items-center gap-1">
                                + Thêm địa chỉ
                            </button>
                        </div>

                        @if($user->addresses && $user->addresses->count() > 0)
                            <div class="space-y-3">
                                @foreach($user->addresses as $addr)
                                    <div class="p-3.5 bg-slate-950 border border-slate-800 rounded-xl flex items-center justify-between text-xs">
                                        <div class="space-y-1">
                                            <div class="flex items-center gap-2">
                                                <span class="px-2 py-0.5 bg-slate-800 text-slate-300 font-bold rounded text-[10px]">{{ $addr->label }}</span>
                                                @if($addr->is_default)
                                                    <span class="px-2 py-0.5 bg-rose-500/10 text-rose-400 font-bold rounded text-[10px]">Mặc định</span>
                                                @endif
                                            </div>
                                            <div class="text-white font-semibold">{{ $addr->address_detail }}</div>
                                            <div class="text-slate-400 text-[11px]">{{ $addr->ward }}, {{ $addr->district }}, {{ $addr->city }}</div>
                                        </div>
                                        <form method="POST" action="{{ route('user.addresses.destroy', $addr) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-500 hover:text-rose-400 transition" title="Xóa">🗑️</button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="py-6 text-center space-y-2">
                                <div class="text-3xl">🏠</div>
                                <p class="text-xs text-slate-500">Bạn chưa có địa chỉ nhận hàng nào được tạo</p>
                            </div>
                        @endif
                    </div>

                    <!-- Card 3: Mật khẩu & Liên kết tài khoản -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 space-y-3">
                            <div class="flex items-center justify-between">
                                <h3 class="font-bold text-xs text-white">Mật khẩu</h3>
                                <button @click="showPasswordModal = true" class="text-xs text-rose-400 hover:underline">Thay đổi mật khẩu</button>
                            </div>
                            <p class="text-[11px] text-slate-400">Cập nhật lần cuối: <span class="text-slate-300 font-mono">{{ $user->updated_at->format('d/m/Y H:i') }}</span></p>
                        </div>

                        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 space-y-3">
                            <h3 class="font-bold text-xs text-white">Tài khoản liên kết</h3>
                            <div class="space-y-2 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-300 flex items-center gap-1.5">🌐 Google</span>
                                    <span class="px-2 py-0.5 bg-emerald-500/10 text-emerald-400 text-[10px] font-bold rounded">Đã liên kết</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-300 flex items-center gap-1.5">💬 Zalo</span>
                                    <button class="text-rose-400 hover:underline text-[11px]">Liên kết ngay</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. TAB: TỔNG QUAN -->
                <div x-show="tab === 'overview'" style="display: none;" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                        <div class="md:col-span-7 bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-xl space-y-3">
                            <h2 class="font-bold text-sm text-white pb-2 border-b border-slate-800">Đơn hàng gần đây</h2>
                            <div class="p-3.5 bg-slate-950 border border-slate-800/80 rounded-xl space-y-2.5">
                                <div class="flex items-center justify-between text-[11px] text-slate-400 pb-2 border-b border-slate-800/60">
                                    <span>Đơn hàng: <strong class="text-white font-mono">#00182S2309000983</strong> • 10/09/2025</span>
                                    <span class="px-2 py-0.5 bg-emerald-500/10 text-emerald-400 font-bold rounded">Đã nhận hàng</span>
                                </div>
                                <div class="flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-12 h-12 bg-slate-900 border border-slate-800 rounded-lg flex items-center justify-center text-xl shrink-0">📱</div>
                                        <div>
                                            <h4 class="text-[11px] font-bold text-white line-clamp-1">XIAOMI REDMI NOTE 14 PRO 5G</h4>
                                            <div class="text-[10px] text-slate-400 font-mono">$389.00</div>
                                            <span class="inline-block px-1.5 py-0.2 bg-rose-500/10 text-rose-400 text-[8px] font-bold rounded">Đã xuất VAT</span>
                                        </div>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <div class="text-[10px] text-slate-400">Tổng thanh toán:</div>
                                        <div class="text-xs font-mono font-extrabold text-rose-500">$389.00</div>
                                        <button @click="tab = 'orders'" class="text-[10px] text-rose-400 hover:underline">Xem chi tiết &gt;</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="md:col-span-5 bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-xl flex flex-col items-center justify-center text-center space-y-3">
                            <h2 class="font-bold text-sm text-white w-full text-left pb-2 border-b border-slate-800">Ưu đãi của bạn</h2>
                            <div class="py-4 space-y-2">
                                <div class="text-4xl">🎁</div>
                                <p class="text-xs text-slate-400">Bạn chưa có ưu đãi nào.</p>
                                <a href="/" class="inline-block text-xs font-semibold text-rose-400 hover:underline">Xem sản phẩm</a>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-xl space-y-3">
                        <div class="flex items-center gap-2 pb-2 border-b border-slate-800">
                            <h2 class="font-bold text-sm text-white">Gói cam kết giá thu</h2>
                            <span class="px-2 py-0.5 bg-emerald-500/10 text-emerald-400 text-[10px] font-bold rounded">✓ S-BuyBack</span>
                        </div>
                        <div class="py-6 text-center text-xs text-slate-500">Bạn chưa có gói cam kết giá thu nào</div>
                    </div>

                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-xl space-y-3">
                        <h2 class="font-bold text-sm text-white pb-2 border-b border-slate-800">Sản phẩm yêu thích</h2>
                        <div class="py-6 text-center text-xs text-slate-400">
                            Bạn chưa có sản phẩm nào yêu thích? <a href="/" class="text-rose-400 font-semibold hover:underline">Mua sắm ngay</a>
                        </div>
                    </div>
                </div>

                <!-- 3. TAB: LỊCH SỬ MUA HÀNG -->
                <div x-show="tab === 'orders'" style="display: none;" class="space-y-6">
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl space-y-5">
                        <div class="flex items-center gap-3 overflow-x-auto border-b border-slate-800 pb-3 text-xs scrollbar-none font-semibold">
                            <button @click="orderFilter = 'all'" :class="orderFilter === 'all' ? 'text-rose-400 border-b-2 border-rose-500 pb-2' : 'text-slate-400 hover:text-white'">Tất cả</button>
                            <button @click="orderFilter = 'pending'" :class="orderFilter === 'pending' ? 'text-rose-400 border-b-2 border-rose-500 pb-2' : 'text-slate-400 hover:text-white'">Chờ xác nhận</button>
                            <button @click="orderFilter = 'delivered'" :class="orderFilter === 'delivered' ? 'text-rose-400 border-b-2 border-rose-500 pb-2' : 'text-slate-400 hover:text-white'">Đã nhận hàng</button>
                        </div>
                        <div class="p-4 bg-slate-950 border border-slate-800 rounded-2xl space-y-3">
                            <div class="flex items-center justify-between text-xs text-slate-400 pb-3 border-b border-slate-800/80">
                                <span>Đơn hàng: <strong class="text-white font-mono">#00182S2309000983</strong> • Ngày đặt: 10/09/2025</span>
                                <span class="px-2 py-0.5 bg-emerald-500/10 text-emerald-400 font-bold rounded">Đã nhận hàng</span>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-14 h-14 bg-slate-900 border border-slate-800 rounded-xl flex items-center justify-center text-2xl">📱</div>
                                    <div>
                                        <h4 class="text-xs font-bold text-white uppercase">Xiaomi Redmi Note 14 Pro 5G 8GB 256GB Xanh Dương</h4>
                                        <div class="text-[11px] text-slate-400 font-mono mt-0.5">$389.00</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-mono font-extrabold text-rose-500">$389.00</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. TAB: BẢO HÀNH -->
                <div x-show="tab === 'warranty'" style="display: none;" class="space-y-6">
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl text-center py-12 space-y-2">
                        <div class="text-4xl">🛠️</div>
                        <p class="text-xs text-slate-500">Bạn chưa có đơn bảo hành hoặc sửa chữa nào</p>
                    </div>
                </div>

                <!-- 5. TAB: HẠNG THÀNH VIÊN -->
                <div x-show="tab === 'membership'" style="display: none;" class="space-y-6">
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="bg-slate-950 border-2 border-rose-500 rounded-2xl p-4 space-y-2">
                                <span class="text-xs font-black text-white">S-NULL</span>
                                <div class="text-xs text-slate-300 font-bold pt-2">{{ $user->name }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">Đã mua 0₫ / 3.000.000₫</div>
                            </div>
                            <div class="bg-slate-950/60 border border-slate-800 rounded-2xl p-4 space-y-2 opacity-60">
                                <span class="text-xs font-black text-amber-400">S-NEW</span>
                                <div class="text-xs text-slate-400 pt-2">🔒 Chưa mở khóa</div>
                            </div>
                            <div class="bg-slate-950/60 border border-slate-800 rounded-2xl p-4 space-y-2 opacity-60">
                                <span class="text-xs font-black text-rose-400">S-MEM</span>
                                <div class="text-xs text-slate-400 pt-2">🔒 Chưa mở khóa</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- ==================== MODAL 1: CẬP NHẬT THÔNG TIN CÁ NHÂN ==================== -->
        <div x-show="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
            <div class="fixed inset-0 bg-black/75 backdrop-blur-sm" @click="showEditModal = false"></div>
            <div class="relative w-full max-w-lg bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-2xl space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                    <h3 class="font-bold text-base text-white">Cập nhật thông tin cá nhân</h3>
                    <button @click="showEditModal = false" class="text-slate-400 hover:text-white text-lg">✕</button>
                </div>

                <form method="POST" action="{{ route('profile.update') }}" class="space-y-4 text-xs">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block font-semibold text-slate-300 mb-1.5">Họ và tên</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full bg-slate-950 border border-slate-800 focus:border-rose-500 rounded-xl text-white px-4 py-2.5 outline-none">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-300 mb-1.5">Giới tính</label>
                        <select name="gender" class="w-full bg-slate-950 border border-slate-800 focus:border-rose-500 rounded-xl text-white px-4 py-2.5 outline-none">
                            <option value="" class="bg-slate-900 text-slate-400">Chọn giới tính</option>
                            <option value="Nam" {{ old('gender', $user->gender) === 'Nam' ? 'selected' : '' }} class="bg-slate-900">Nam</option>
                            <option value="Nữ" {{ old('gender', $user->gender) === 'Nữ' ? 'selected' : '' }} class="bg-slate-900">Nữ</option>
                            <option value="Khác" {{ old('gender', $user->gender) === 'Khác' ? 'selected' : '' }} class="bg-slate-900">Khác</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-300 mb-1.5">Ngày sinh</label>
                        <input type="date" name="birthday" value="{{ old('birthday', $user->birthday ? \Carbon\Carbon::parse($user->birthday)->format('Y-m-d') : '') }}"
                               class="w-full bg-slate-950 border border-slate-800 focus:border-rose-500 rounded-xl text-white px-4 py-2.5 outline-none font-mono">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-300 mb-1.5">Số điện thoại</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Ví dụ: 0777190215"
                               class="w-full bg-slate-950 border border-slate-800 focus:border-rose-500 rounded-xl text-white px-4 py-2.5 outline-none font-mono">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-300 mb-1.5">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="w-full bg-slate-950 border border-slate-800 focus:border-rose-500 rounded-xl text-white px-4 py-2.5 outline-none font-mono">
                    </div>

                    <div class="grid grid-cols-2 gap-3 pt-3 border-t border-slate-800">
                        <button type="reset" class="py-3 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl font-bold transition">Thiết lập lại</button>
                        <button type="submit" class="py-3 bg-gradient-to-r from-rose-600 to-red-500 hover:from-rose-500 hover:to-red-400 text-white font-bold rounded-xl shadow-lg transition">Cập nhật thông tin</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ==================== MODAL 2: THÊM ĐỊA CHỈ NHẬN HÀNG ==================== -->
        <div x-show="showAddressModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
            <div class="fixed inset-0 bg-black/75 backdrop-blur-sm" @click="showAddressModal = false"></div>
            <div class="relative w-full max-w-lg bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-2xl space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                    <h3 class="font-bold text-base text-white">Thêm địa chỉ</h3>
                    <button @click="showAddressModal = false" class="text-slate-400 hover:text-white text-lg">✕</button>
                </div>

                <form method="POST" action="{{ route('user.addresses.store') }}" class="space-y-4 text-xs">
                    @csrf

                    <div>
                        <label class="block font-semibold text-slate-300 mb-1.5">Tỉnh/Thành phố</label>
                        <input type="text" name="city" required placeholder="Ví dụ: TP. Hồ Chí Minh..." 
                               class="w-full bg-slate-950 border border-slate-800 focus:border-rose-500 rounded-xl text-white px-4 py-2.5 outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-300 mb-1.5">Quận/Huyện</label>
                            <input type="text" name="district" required placeholder="Ví dụ: Quận 10, Tân Phú..." 
                                   class="w-full bg-slate-950 border border-slate-800 focus:border-rose-500 rounded-xl text-white px-4 py-2.5 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-300 mb-1.5">Phường/Xã</label>
                            <input type="text" name="ward" required placeholder="Ví dụ: Phường 14..." 
                                   class="w-full bg-slate-950 border border-slate-800 focus:border-rose-500 rounded-xl text-white px-4 py-2.5 outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-300 mb-1.5">Địa chỉ nhà (Số nhà, tên đường)</label>
                        <input type="text" name="address_detail" required placeholder="Ví dụ: 828 Sư Vạn Hạnh, P.13..." 
                               class="w-full bg-slate-950 border border-slate-800 focus:border-rose-500 rounded-xl text-white px-4 py-2.5 outline-none">
                    </div>

                    <div class="space-y-2 pt-2 border-t border-slate-800/80">
                        <label class="block font-semibold text-slate-300">Loại địa chỉ</label>
                        <div class="flex items-center gap-3">
                            <button type="button" @click="selectedLabel = 'Nhà'" :class="selectedLabel === 'Nhà' ? 'border-rose-500 text-rose-400 bg-rose-500/10' : 'border-slate-800 text-slate-400 bg-slate-950'" class="px-4 py-2 border rounded-xl font-bold transition">Nhà</button>
                            <button type="button" @click="selectedLabel = 'Văn phòng'" :class="selectedLabel === 'Văn phòng' ? 'border-rose-500 text-rose-400 bg-rose-500/10' : 'border-slate-800 text-slate-400 bg-slate-950'" class="px-4 py-2 border rounded-xl font-bold transition">Văn phòng</button>
                        </div>
                        <input type="hidden" name="label" :value="selectedLabel">
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <span class="text-slate-300 font-semibold">Đặt làm địa chỉ mặc định</span>
                        <input type="checkbox" name="is_default" value="1" class="w-4 h-4 rounded text-rose-600 bg-slate-950 border-slate-800 focus:ring-0">
                    </div>

                    <div class="pt-3 border-t border-slate-800">
                        <button type="submit" class="w-full py-3 bg-gradient-to-r from-rose-600 to-red-500 hover:from-rose-500 hover:to-red-400 text-white font-bold rounded-xl shadow-lg transition">Thêm địa chỉ</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ==================== MODAL 3: ĐỔI MẬT KHẨU ==================== -->
        <div x-show="showPasswordModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
            <div class="fixed inset-0 bg-black/75 backdrop-blur-sm" @click="showPasswordModal = false"></div>
            <div class="relative w-full max-w-lg bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-2xl space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                    <h3 class="font-bold text-base text-white">Đổi mật khẩu</h3>
                    <button @click="showPasswordModal = false" class="text-slate-400 hover:text-white text-lg">✕</button>
                </div>

                <form method="POST" action="{{ route('password.update') }}" class="space-y-4 text-xs">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block font-semibold text-slate-300 mb-1.5">Mật khẩu cũ</label>
                        <div class="relative">
                            <input :type="showCurrentPass ? 'text' : 'password'" name="current_password" required placeholder="Nhập mật khẩu cũ của bạn"
                                   class="w-full bg-slate-950 border border-slate-800 focus:border-rose-500 rounded-xl text-white px-4 py-2.5 outline-none pr-10">
                            <button type="button" @click="showCurrentPass = !showCurrentPass" class="absolute right-3 top-2.5 text-slate-400 hover:text-white">👁️</button>
                        </div>
                        @error('current_password', 'updatePassword') <p class="mt-1 text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-300 mb-1.5">Mật khẩu mới</label>
                        <div class="relative">
                            <input :type="showNewPass ? 'text' : 'password'" name="password" required placeholder="Nhập mật khẩu mới của bạn"
                                   class="w-full bg-slate-950 border border-slate-800 focus:border-rose-500 rounded-xl text-white px-4 py-2.5 outline-none pr-10">
                            <button type="button" @click="showNewPass = !showNewPass" class="absolute right-3 top-2.5 text-slate-400 hover:text-white">👁️</button>
                        </div>
                        <p class="text-[10px] text-slate-500 mt-1">ℹ️ Mật khẩu tối thiểu 8 ký tự.</p>
                        @error('password', 'updatePassword') <p class="mt-1 text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-300 mb-1.5">Nhập lại mật khẩu mới</label>
                        <div class="relative">
                            <input :type="showConfirmPass ? 'text' : 'password'" name="password_confirmation" required placeholder="Nhập lại mật khẩu mới của bạn"
                                   class="w-full bg-slate-950 border border-slate-800 focus:border-rose-500 rounded-xl text-white px-4 py-2.5 outline-none pr-10">
                            <button type="button" @click="showConfirmPass = !showConfirmPass" class="absolute right-3 top-2.5 text-slate-400 hover:text-white">👁️</button>
                        </div>
                        @error('password_confirmation', 'updatePassword') <p class="mt-1 text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-3 border-t border-slate-800">
                        <button type="submit" class="w-full py-3 bg-gradient-to-r from-rose-600 to-red-500 hover:from-rose-500 hover:to-red-400 text-white font-bold rounded-xl shadow-lg transition">Đổi mật khẩu</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-store-layout>