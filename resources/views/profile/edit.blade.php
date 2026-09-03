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
        showConfirmPass: false,
        selectedOrder: null,

        // Dữ liệu đơn hàng mẫu từ hệ thống
        demoOrder: {
            id: '{{ $user->orders->first()->id ?? 1 }}',
            order_code: '00182S2309000983',
            created_at: '10/09/2025 14:30',
            status: 'Đã nhận hàng',
            payment_method: 'VNPAY',
            payment_status: 'Đã thanh toán',
            total_price: 3890000,
            shipping_fee: 0,
            discount_amount: 0,
            items: [
                {
                    product_name: 'Xiaomi Redmi Note 14 Pro 5G',
                    version_name: '8GB/256GB',
                    color_name: 'Xanh Dương',
                    quantity: 1,
                    price: 3890000,
                    total: 3890000
                }
            ]
        }
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
                                    <div class="text-right shrink-0 space-y-1">
                                        <div class="text-[10px] text-slate-400">Tổng thanh toán:</div>
                                        <div class="text-xs font-mono font-extrabold text-rose-500">$389.00</div>
                                        <div class="flex items-center gap-2 justify-end pt-1">
                                            <a :href="'/orders/' + demoOrder.id + '/invoice'" target="_blank" class="px-2 py-1 bg-slate-900 hover:bg-slate-800 border border-rose-500/50 text-rose-400 rounded-lg text-[10px] font-semibold transition inline-flex items-center gap-1">
                                                <span>📄</span> In Hóa Đơn
                                            </a>
                                            <button @click="selectedOrder = demoOrder" class="text-[10px] text-slate-300 hover:text-white font-semibold">Chi tiết &gt;</button>
                                        </div>
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
                                <div class="text-right space-y-2">
                                    <div class="text-sm font-mono font-extrabold text-rose-500">$389.00</div>
                                    <div class="flex items-center gap-2">
                                        <!-- Nút In Hóa Đơn PDF ở danh sách -->
                                        <a :href="'/orders/' + demoOrder.id + '/invoice'" 
                                           target="_blank" 
                                           class="px-3 py-1.5 bg-slate-900 hover:bg-slate-800 border border-rose-500/50 text-rose-400 font-bold rounded-xl text-[11px] transition flex items-center gap-1.5 shadow">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <span>In Hóa Đơn</span>
                                        </a>

                                        <!-- Nút mở modal xem chi tiết -->
                                        <button type="button" @click="selectedOrder = demoOrder" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-500 text-white font-bold rounded-xl text-[11px] transition shadow">
                                            Xem chi tiết &gt;
                                        </button>
                                    </div>
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

        <!-- ==================== MODAL CHI TIẾT ĐƠN HÀNG VÀ IN HÓA ĐƠN PDF (5.1b) ==================== -->
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4" x-show="selectedOrder" style="display: none;">
            <div class="bg-slate-900 border border-slate-800 w-full max-w-2xl rounded-3xl p-6 shadow-2xl space-y-5 text-xs text-white max-h-[90vh] overflow-y-auto" @click.away="selectedOrder = null">
                <div class="flex justify-between items-center border-b border-slate-800 pb-3">
                    <div>
                        <h3 class="font-bold text-base text-white">Chi tiết đơn hàng: <span class="text-rose-400 font-mono" x-text="selectedOrder ? '#' + selectedOrder.order_code : ''"></span></h3>
                        <span class="text-slate-400 text-[11px]" x-text="selectedOrder ? 'Ngày đặt: ' + selectedOrder.created_at : ''"></span>
                    </div>
                    <button @click="selectedOrder = null" class="text-slate-400 hover:text-white font-bold text-xl cursor-pointer">&times;</button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 bg-slate-950 border border-slate-800 rounded-2xl space-y-1">
                        <span class="text-slate-400 block text-[11px]">Trạng thái đơn hàng:</span>
                        <div class="text-rose-400 font-bold text-sm" x-text="selectedOrder ? selectedOrder.status : ''"></div>
                    </div>
                    <div class="p-4 bg-slate-950 border border-slate-800 rounded-2xl space-y-1">
                        <span class="text-slate-400 block text-[11px]">Tình trạng thanh toán:</span>
                        <div class="text-emerald-400 font-bold text-sm" x-text="selectedOrder ? (selectedOrder.payment_method + ' - ' + selectedOrder.payment_status) : ''"></div>
                    </div>
                </div>

                <div class="space-y-2">
                    <h4 class="font-bold text-white text-xs">Danh sách sản phẩm đã đặt</h4>
                    <div class="space-y-2">
                        <template x-for="item in (selectedOrder ? selectedOrder.items : [])">
                            <div class="flex items-center justify-between p-3.5 bg-slate-950 border border-slate-800 rounded-2xl">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-slate-900 border border-slate-800 rounded-xl flex items-center justify-center shrink-0 text-xl">📱</div>
                                    <div>
                                        <div class="font-bold text-white uppercase text-xs" x-text="item.product_name"></div>
                                        <div class="text-slate-400 text-[11px] mt-0.5">
                                            Phân loại: <span class="text-rose-400" x-text="item.version_name + ' - ' + item.color_name"></span> | 
                                            SL: <span class="font-bold text-white" x-text="item.quantity"></span>
                                        </div>
                                    </div>
                                </div>
                                <span class="font-mono font-bold text-white text-sm" x-text="new Intl.NumberFormat('vi-VN').format(item.total) + '₫'"></span>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="p-4 bg-slate-950 border border-slate-800 rounded-2xl space-y-2 text-xs">
                    <div class="flex justify-between text-slate-300">
                        <span>1. Phí vận chuyển:</span>
                        <strong class="font-mono text-emerald-400">0₫ (Miễn phí toàn quốc)</strong>
                    </div>
                    <div class="pt-2 border-t border-slate-800 flex justify-between items-baseline">
                        <span class="font-bold text-white text-sm">TỔNG TIỀN THANH TOÁN:</span>
                        <strong class="font-mono font-black text-rose-500 text-base" x-text="selectedOrder ? new Intl.NumberFormat('vi-VN').format(selectedOrder.total_price) + '₫' : ''"></strong>
                    </div>
                </div>

                <!-- Cụm nút hành động dưới đáy Modal -->
                <div class="pt-2 flex flex-wrap gap-3">
                    <!-- Nút Xuất Hóa Đơn PDF chính -->
                    <a :href="'/orders/' + (selectedOrder ? selectedOrder.id : '') + '/invoice'" 
                       target="_blank"
                       class="flex-1 min-w-[150px] py-3 bg-slate-950 border border-rose-500/60 hover:bg-rose-500/10 text-rose-400 font-bold rounded-xl text-center transition flex items-center justify-center gap-2 shadow">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>In / Xuất Hóa Đơn PDF</span>
                    </a>

                    <button type="button" @click="selectedOrder = null" class="flex-1 min-w-[100px] py-3 bg-slate-800 hover:bg-slate-700 text-white font-bold rounded-xl shadow transition cursor-pointer">
                        Đóng
                    </button>
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
            <div class="relative w-full max-w-lg bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-2xl space-y-5"
                 x-data="{
                     citySearch: 'TP. Hồ Chí Minh',
                     cityOpen: false,
                     cities: [
                         'TP. Hồ Chí Minh', 'Hà Nội', 'Đà Nẵng', 'Hải Phòng', 'Cần Thơ', 
                         'Bình Dương', 'Đồng Nai', 'Bà Rịa - Vũng Tàu', 'Long An', 'Tiền Giang',
                         'Lâm Đồng', 'Khánh Hòa', 'Thừa Thiên Huế', 'Quảng Nam', 'Quảng Ninh'
                     ],
                     get filteredCities() {
                         if (this.citySearch === '') return this.cities;
                         return this.cities.filter(c => c.toLowerCase().includes(this.citySearch.toLowerCase()));
                     },

                     districtSearch: 'Quận 10',
                     districtOpen: false,
                     districts: ['Quận 1', 'Quận 3', 'Quận 5', 'Quận 10', 'Quận 11', 'Tân Bình', 'Tân Phú', 'Bình Tân', 'Phú Nhuận', 'Bình Thạnh', 'TP. Thủ Đức', 'Ba Đình', 'Hoàn Kiếm', 'Đống Đa', 'Cầu Giấy', 'Hải Châu', 'Thanh Khê'],
                     get filteredDistricts() {
                         if (this.districtSearch === '') return this.districts;
                         return this.districts.filter(d => d.toLowerCase().includes(this.districtSearch.toLowerCase()));
                     },

                     wardSearch: 'Phường 13',
                     wardOpen: false,
                     wards: ['Phường 1', 'Phường 2', 'Phường 3', 'Phường 4', 'Phường 5', 'Phường 10', 'Phường 13', 'Phường 14', 'Phường 15', 'Phường Bến Thành', 'Phường Tân Sơn Nhì'],
                     get filteredWards() {
                         if (this.wardSearch === '') return this.wards;
                         return this.wards.filter(w => w.toLowerCase().includes(this.wardSearch.toLowerCase()));
                     }
                 }">
                
                <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                    <h3 class="font-bold text-base text-white">Thêm địa chỉ nhận hàng</h3>
                    <button @click="showAddressModal = false" class="text-slate-400 hover:text-white text-lg">✕</button>
                </div>

                <form method="POST" action="{{ route('user.addresses.store') }}" class="space-y-4 text-xs">
                    @csrf

                    <!-- TỈNH / THÀNH PHỐ AUTOCOMPLETE -->
                    <div class="relative" @click.away="cityOpen = false">
                        <label class="block font-semibold text-slate-300 mb-1.5">Tỉnh / Thành phố</label>
                        <input type="text" name="city" x-model="citySearch" @focus="cityOpen = true" @input="cityOpen = true" required placeholder="Gõ để tìm kiếm..." 
                               class="w-full bg-slate-950 border border-slate-800 focus:border-rose-500 rounded-xl text-white px-4 py-2.5 outline-none font-medium">
                        
                        <div x-show="cityOpen && filteredCities.length > 0" style="display: none;" 
                             class="absolute z-50 left-0 right-0 mt-1 bg-slate-950 border border-slate-800 rounded-xl shadow-2xl max-h-48 overflow-y-auto divide-y divide-slate-900">
                            <template x-for="c in filteredCities" :key="c">
                                <div @click="citySearch = c; cityOpen = false" 
                                     class="px-4 py-2.5 text-slate-300 hover:bg-rose-500/20 hover:text-white cursor-pointer transition flex items-center justify-between">
                                    <span x-text="c"></span>
                                    <span class="text-rose-400 text-[10px]" x-show="citySearch === c">✓</span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <!-- QUẬN / HUYỆN AUTOCOMPLETE -->
                        <div class="relative" @click.away="districtOpen = false">
                            <label class="block font-semibold text-slate-300 mb-1.5">Quận / Huyện</label>
                            <input type="text" name="district" x-model="districtSearch" @focus="districtOpen = true" @input="districtOpen = true" required placeholder="Gõ quận..." 
                                   class="w-full bg-slate-950 border border-slate-800 focus:border-rose-500 rounded-xl text-white px-4 py-2.5 outline-none font-medium">
                            
                            <div x-show="districtOpen && filteredDistricts.length > 0" style="display: none;" 
                                 class="absolute z-50 left-0 right-0 mt-1 bg-slate-950 border border-slate-800 rounded-xl shadow-2xl max-h-48 overflow-y-auto divide-y divide-slate-900">
                                <template x-for="d in filteredDistricts" :key="d">
                                    <div @click="districtSearch = d; districtOpen = false" 
                                         class="px-4 py-2.5 text-slate-300 hover:bg-rose-500/20 hover:text-white cursor-pointer transition">
                                        <span x-text="d"></span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- PHƯỜNG / XÃ AUTOCOMPLETE -->
                        <div class="relative" @click.away="wardOpen = false">
                            <label class="block font-semibold text-slate-300 mb-1.5">Phường / Xã</label>
                            <input type="text" name="ward" x-model="wardSearch" @focus="wardOpen = true" @input="wardOpen = true" required placeholder="Gõ phường..." 
                                   class="w-full bg-slate-950 border border-slate-800 focus:border-rose-500 rounded-xl text-white px-4 py-2.5 outline-none font-medium">
                            
                            <div x-show="wardOpen && filteredWards.length > 0" style="display: none;" 
                                 class="absolute z-50 left-0 right-0 mt-1 bg-slate-950 border border-slate-800 rounded-xl shadow-2xl max-h-48 overflow-y-auto divide-y divide-slate-900">
                                <template x-for="w in filteredWards" :key="w">
                                    <div @click="wardSearch = w; wardOpen = false" 
                                         class="px-4 py-2.5 text-slate-300 hover:bg-rose-500/20 hover:text-white cursor-pointer transition">
                                        <span x-text="w"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-300 mb-1.5">Địa chỉ cụ thể (Số nhà, tên đường)</label>
                        <input type="text" name="address_detail" required placeholder="Ví dụ: 828 Sư Vạn Hạnh..." 
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