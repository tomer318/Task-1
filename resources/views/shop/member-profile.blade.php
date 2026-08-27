<x-store-layout>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-6 text-white bg-slate-950 min-h-screen"
         x-data="{ 
             activeTab: '{{ $activeTab ?? 'overview' }}',
             showEditProfileModal: false,
             showAddAddressModal: false,
             showChangePasswordModal: false
         }">

        <!-- Top Header Card chuẩn TechZone Theme -->
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
                            S-NULL
                        </span>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-white flex items-center gap-2">
                            {{ $user->name }}
                        </h1>
                        <p class="text-xs text-slate-400 font-mono mt-0.5">{{ $user->phone ?? '0777190215' }}</p>
                        <p class="text-[11px] text-rose-400 font-medium">Cập nhật lại sau 01/01/2027</p>
                    </div>
                </div>

                <!-- Thống kê: Đơn hàng & Chi tiêu -->
                <div class="md:col-span-7 grid grid-cols-2 gap-4 border-t md:border-t-0 md:border-l border-slate-800 pt-4 md:pt-0 md:pl-6">
                    <div class="bg-slate-950/60 border border-slate-800/80 rounded-2xl p-4 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-rose-500/10 border border-rose-500/20 flex items-center justify-center text-lg">📦</div>
                        <div>
                            <div class="text-xl font-mono font-extrabold text-white">{{ $ordersCount ?? 0 }}</div>
                            <div class="text-[11px] text-slate-400">Tổng số đơn hàng đã mua</div>
                        </div>
                    </div>

                    <div class="bg-slate-950/60 border border-slate-800/80 rounded-2xl p-4 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-lg">💎</div>
                        <div>
                            <div class="text-base sm:text-xl font-mono font-extrabold text-emerald-400">{{ number_format($totalSpent ?? 0, 0, ',', '.') }}₫</div>
                            <div class="text-[11px] text-slate-400">Tổng tiền tích lũy</div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Quick Action Bar -->
            <div class="flex items-center gap-4 overflow-x-auto pt-6 mt-6 border-t border-slate-800 text-xs font-semibold text-slate-300">
                <a href="{{ route('profile.promotion') }}" class="px-3.5 py-2 bg-slate-950 border border-slate-800 hover:border-rose-500 rounded-xl transition whitespace-nowrap">🏷️ Hạng thành viên</a>
                <a href="{{ route('profile.orders') }}" class="px-3.5 py-2 bg-slate-950 border border-slate-800 hover:border-rose-500 rounded-xl transition whitespace-nowrap">📜 Lịch sử mua hàng</a>
                <a href="{{ route('profile.user.info') }}" class="px-3.5 py-2 bg-slate-950 border border-slate-800 hover:border-rose-500 rounded-xl transition whitespace-nowrap">📍 Sổ địa chỉ</a>
                <a href="{{ route('profile.user.info') }}" class="px-3.5 py-2 bg-slate-950 border border-slate-800 hover:border-rose-500 rounded-xl transition whitespace-nowrap">⚙️ Thông tin tài khoản</a>
            </div>
        </div>

        <!-- Main Body: Sidebar & Content -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- Sidebar Navigation -->
            <div class="lg:col-span-3 bg-slate-900 border border-slate-800 rounded-2xl p-2 space-y-1 text-xs font-semibold text-slate-300 shadow-xl">
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ ($activeTab ?? '') === 'overview' ? 'bg-gradient-to-r from-rose-600 to-red-500 text-white shadow-md' : 'hover:bg-slate-800 hover:text-white' }}">
                    <span>🏠</span> Tổng quan
                </a>
                <a href="{{ route('profile.orders') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ ($activeTab ?? '') === 'orders' ? 'bg-gradient-to-r from-rose-600 to-red-500 text-white shadow-md' : 'hover:bg-slate-800 hover:text-white' }}">
                    <span>📜</span> Lịch sử mua hàng
                </a>
                <a href="{{ route('profile.promotion') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ ($activeTab ?? '') === 'promotion' ? 'bg-gradient-to-r from-rose-600 to-red-500 text-white shadow-md' : 'hover:bg-slate-800 hover:text-white' }}">
                    <span>💎</span> Hạng thành viên và ưu đãi
                </a>
                <a href="{{ route('profile.user.info') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ ($activeTab ?? '') === 'user-info' ? 'bg-gradient-to-r from-rose-600 to-red-500 text-white shadow-md' : 'hover:bg-slate-800 hover:text-white' }}">
                    <span>⚙️</span> Thông tin tài khoản
                </a>
                <form method="POST" action="{{ route('logout') }}" class="pt-2 border-t border-slate-800">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-rose-400 hover:bg-rose-500/10 transition text-left font-bold">
                        <span>🚪</span> Đăng xuất
                    </button>
                </form>
            </div>

            <!-- Content Area -->
            <div class="lg:col-span-9 space-y-6">

                <!-- TAB 1: TỔNG QUAN -->
                @if(($activeTab ?? '') === 'overview')
                    <div class="space-y-6">
                        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl space-y-4">
                            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                                <h2 class="font-bold text-sm text-white">Đơn hàng gần đây</h2>
                                <a href="{{ route('profile.orders') }}" class="text-xs text-rose-400 hover:underline font-semibold">Xem tất cả &rarr;</a>
                            </div>

                            @if(isset($recentOrders) && $recentOrders->count() > 0)
                                @php $latestOrder = $recentOrders->first(); @endphp
                                <div class="bg-slate-950 border border-slate-800 rounded-xl p-4 space-y-3">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="font-mono font-bold text-slate-300">Đơn hàng: #{{ $latestOrder->order_code ?? 'ORD-'.$latestOrder->id }}</span>
                                        <span class="text-slate-400">Ngày đặt: {{ $latestOrder->created_at->format('d/m/Y') }}</span>
                                        <span class="px-2.5 py-0.5 rounded font-bold text-[10px] bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">{{ $latestOrder->status }}</span>
                                    </div>
                                    @foreach($latestOrder->items as $item)
                                        <div class="flex items-center justify-between text-xs gap-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-12 h-12 bg-slate-900 border border-slate-800 rounded-lg flex items-center justify-center shrink-0">📦</div>
                                                <div>
                                                    <div class="font-bold text-white uppercase">{{ $item->product_name }}</div>
                                                    <div class="text-slate-400">Số lượng: {{ $item->quantity }}</div>
                                                </div>
                                            </div>
                                            <span class="font-mono font-bold text-rose-500">{{ number_format($latestOrder->total_price, 0, ',', '.') }}₫</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 text-xs text-slate-400">Chưa có đơn hàng nào gần đây.</div>
                            @endif
                        </div>

                        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl space-y-3">
                            <h2 class="font-bold text-sm text-white">Ưu đãi của bạn</h2>
                            <div class="p-8 text-center text-xs text-slate-400 bg-slate-950 rounded-xl border border-dashed border-slate-800">
                                Bạn chưa có ưu đãi nào. <a href="{{ route('home') }}" class="text-rose-400 font-semibold hover:underline">Xem sản phẩm</a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- TAB 2: LỊCH SỬ MUA HÀNG (Đã bổ sung đầy đủ 6 tab chuẩn mẫu CellPhoneS) -->
                @if(($activeTab ?? '') === 'orders')
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl space-y-6" x-data="{ orderSubTab: 'all' }">
                        <div class="flex gap-6 border-b border-slate-800 pb-3 text-xs font-bold text-slate-400 overflow-x-auto scrollbar-none">
                            <button @click="orderSubTab = 'all'" :class="orderSubTab === 'all' ? 'text-rose-500 border-b-2 border-rose-500 pb-3 -mb-3' : 'hover:text-white'" class="whitespace-nowrap">Tất cả ({{ $recentOrders->count() }})</button>
                            <button @click="orderSubTab = 'pending'" :class="orderSubTab === 'pending' ? 'text-rose-500 border-b-2 border-rose-500 pb-3 -mb-3' : 'hover:text-white'" class="whitespace-nowrap">Chờ xác nhận</button>
                            <button @click="orderSubTab = 'processing'" :class="orderSubTab === 'processing' ? 'text-rose-500 border-b-2 border-rose-500 pb-3 -mb-3' : 'hover:text-white'" class="whitespace-nowrap">Đang xử lý</button>
                            <button @click="orderSubTab = 'shipping'" :class="orderSubTab === 'shipping' ? 'text-rose-500 border-b-2 border-rose-500 pb-3 -mb-3' : 'hover:text-white'" class="whitespace-nowrap">Đang vận chuyển</button>
                            <button @click="orderSubTab = 'completed'" :class="orderSubTab === 'completed' ? 'text-rose-500 border-b-2 border-rose-500 pb-3 -mb-3' : 'hover:text-white'" class="whitespace-nowrap">Đã nhận hàng</button>
                            <button @click="orderSubTab = 'cancelled'" :class="orderSubTab === 'cancelled' ? 'text-rose-500 border-b-2 border-rose-500 pb-3 -mb-3' : 'hover:text-white'" class="whitespace-nowrap">Đã huỷ</button>
                        </div>

                        @if(isset($recentOrders) && $recentOrders->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentOrders as $order)
                                    <div class="border border-slate-800 rounded-xl p-4 space-y-3 bg-slate-950"
                                         x-show="orderSubTab === 'all' || 
                                                 (orderSubTab === 'pending' && ('{{ $order->status }}' === 'Chờ xử lý' || '{{ $order->status }}' === 'Chờ xác nhận')) || 
                                                 (orderSubTab === 'processing' && '{{ $order->status }}' === 'Đang xử lý') || 
                                                 (orderSubTab === 'shipping' && '{{ $order->status }}' === 'Đang vận chuyển') || 
                                                 (orderSubTab === 'completed' && ('{{ $order->status }}' === 'Đã giao' || '{{ $order->status }}' === 'Đã nhận hàng')) || 
                                                 (orderSubTab === 'cancelled' && ('{{ $order->status }}' === 'Đã hủy' || '{{ $order->status }}' === 'Đã huỷ'))">
                                        
                                        <div class="flex items-center justify-between text-xs pb-2 border-b border-slate-800">
                                            <span class="font-mono font-bold text-slate-300">Đơn hàng: #{{ $order->order_code ?? 'ORD-'.$order->id }}</span>
                                            <span class="text-slate-400">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</span>
                                            <span class="px-2.5 py-0.5 rounded font-bold text-[10px] {{ in_array($order->status, ['Đã giao', 'Đã nhận hàng']) ? 'bg-emerald-500/10 text-emerald-400' : 'bg-amber-500/10 text-amber-400' }}">
                                                {{ $order->status }}
                                            </span>
                                        </div>

                                        @foreach($order->items as $item)
                                            <div class="flex items-center justify-between text-xs gap-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-12 h-12 bg-slate-900 border border-slate-800 rounded-lg flex items-center justify-center shrink-0">📦</div>
                                                    <div>
                                                        <div class="font-bold text-white uppercase">{{ $item->product_name }}</div>
                                                        <div class="text-slate-400">Số lượng: {{ $item->quantity }} | Phân loại: {{ $item->version_name }}</div>
                                                    </div>
                                                </div>
                                                <span class="font-mono font-bold text-rose-500">{{ number_format($item->total, 0, ',', '.') }}₫</span>
                                            </div>
                                        @endforeach

                                        <div class="pt-2 border-t border-slate-800 flex justify-between items-center text-xs">
                                            <span class="text-slate-400">Thanh toán: <strong class="text-slate-200">{{ $order->payment_method }}</strong></span>
                                            <span class="font-mono font-bold text-white text-sm">Tổng thanh toán: <span class="text-rose-500">{{ number_format($order->total_price, 0, ',', '.') }}₫</span></span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="py-12 text-center text-xs text-slate-400">Không có đơn hàng nào.</div>
                        @endif
                    </div>
                @endif

                <!-- TAB 3: HẠNG THÀNH VIÊN VÀ ƯU ĐÃI -->
                @if(($activeTab ?? '') === 'promotion')
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl space-y-6">
                        <h2 class="font-bold text-sm text-white">Hạng thành viên của bạn</h2>
                        <div class="p-5 bg-gradient-to-r from-slate-950 to-slate-900 border border-slate-800 text-white rounded-2xl space-y-3 shadow-lg">
                            <div class="flex justify-between items-center text-xs">
                                <span class="font-bold uppercase tracking-wider text-rose-400">S-NULL Club</span>
                                <span class="font-mono text-slate-400">Thành viên TechZone</span>
                            </div>
                            <div class="text-base font-bold">{{ $user->name }}</div>
                            <div class="text-xs text-slate-400 pt-2 border-t border-slate-800">
                                Tổng tích lũy: <strong class="text-emerald-400 font-mono">{{ number_format($totalSpent ?? 0, 0, ',', '.') }}₫</strong>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <h3 class="font-bold text-xs text-white">Mã giảm giá khả dụng</h3>
                            <div class="p-4 border border-slate-800 rounded-xl flex items-center justify-between text-xs bg-slate-950">
                                <div>
                                    <div class="font-bold text-rose-400 font-mono text-sm">SALE1090</div>
                                    <div class="text-slate-400">Giảm 10% cho đơn hàng từ 20.000.000₫</div>
                                </div>
                                <span class="px-3 py-1.5 bg-rose-600 text-white font-bold rounded-lg">Đã lưu</span>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- TAB 4: THÔNG TIN TÀI KHOẢN & SỔ ĐỊA CHỈ -->
                @if(($activeTab ?? '') === 'user-info')
                    <div class="space-y-6">
                        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl space-y-4">
                            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                                <h2 class="font-bold text-sm text-white">Thông tin cá nhân</h2>
                                <button @click="showEditProfileModal = true" class="text-xs text-rose-400 font-semibold hover:underline flex items-center gap-1">
                                    ✏️ Cập nhật
                                </button>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                                <div>
                                    <span class="text-slate-400 block">Họ và tên:</span>
                                    <strong class="text-white text-sm">{{ $user->name }}</strong>
                                </div>
                                <div>
                                    <span class="text-slate-400 block">Số điện thoại:</span>
                                    <strong class="text-white text-sm font-mono">{{ $user->phone ?? '0777190215' }}</strong>
                                </div>
                                <div>
                                    <span class="text-slate-400 block">Giới tính:</span>
                                    <strong class="text-white text-sm">{{ $user->gender ?? 'Nam' }}</strong>
                                </div>
                                <div>
                                    <span class="text-slate-400 block">Email:</span>
                                    <strong class="text-white text-sm font-mono">{{ $user->email }}</strong>
                                </div>
                                <div>
                                    <span class="text-slate-400 block">Ngày sinh:</span>
                                    <strong class="text-white text-sm">{{ $user->birthday ? \Carbon\Carbon::parse($user->birthday)->format('d/m/Y') : '18/05/2005' }}</strong>
                                </div>
                                <div>
                                    <span class="text-slate-400 block">Địa chỉ mặc định:</span>
                                    <strong class="text-white text-sm">
                                        @php $defaultAddr = $addresses->where('is_default', true)->first() ?? $addresses->first(); @endphp
                                        {{ $defaultAddr ? ($defaultAddr->address_detail . ', ' . $defaultAddr->ward . ', ' . $defaultAddr->district . ', ' . $defaultAddr->city) : '102/1c Lê Tấn Bế, Phường An Lạc, Quận Bình Tân, TP.HCM' }}
                                    </strong>
                                </div>
                            </div>
                        </div>

                        <!-- Sổ địa chỉ -->
                        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl space-y-4">
                            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                                <h2 class="font-bold text-sm text-white">Sổ địa chỉ nhận hàng</h2>
                                <button @click="showAddAddressModal = true" class="text-xs text-rose-400 font-semibold hover:underline">
                                    + Thêm địa chỉ
                                </button>
                            </div>

                            @if(isset($addresses) && count($addresses) > 0)
                                <div class="space-y-3">
                                    @foreach($addresses as $addr)
                                        <div class="p-4 border border-slate-800 rounded-xl flex items-center justify-between text-xs bg-slate-950">
                                            <div>
                                                <div class="font-bold text-white">
                                                    {{ $addr->label ?? 'Nhà' }} 
                                                    @if($addr->is_default) <span class="px-2 py-0.5 bg-rose-500/10 text-rose-400 border border-rose-500/20 rounded font-bold text-[10px] ml-2">Mặc định</span> @endif
                                                </div>
                                                <div class="text-slate-300 mt-1">{{ $user->name }} - <span class="font-mono">{{ $user->phone ?? '0777190215' }}</span></div>
                                                <div class="text-slate-400 mt-0.5">{{ $addr->address_detail }}, {{ $addr->ward }}, {{ $addr->district }}, {{ $addr->city }}</div>
                                            </div>
                                            <form action="{{ route('user.addresses.destroy', $addr->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-rose-400 hover:underline font-semibold text-xs">Xóa</button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-xs text-slate-400 py-4">Bạn chưa thêm địa chỉ nào.</div>
                            @endif
                        </div>

                        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl flex items-center justify-between text-xs">
                            <div>
                                <h2 class="font-bold text-sm text-white">Mật khẩu bảo mật</h2>
                                <p class="text-slate-400 mt-0.5">Cập nhật định kỳ để bảo vệ tài khoản của bạn.</p>
                            </div>
                            <button @click="showChangePasswordModal = true" class="px-4 py-2 border border-rose-500 text-rose-400 font-bold rounded-xl hover:bg-rose-500/10 transition">
                                Thay đổi mật khẩu
                            </button>
                        </div>
                    </div>
                @endif

            </div>

        </div>

        <!-- ================= MODAL 1: CẬP NHẬT THÔNG TIN CÁ NHÂN ================= -->
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm" x-show="showEditProfileModal" style="display: none;">
            <div class="bg-slate-900 border border-slate-800 w-full max-w-lg rounded-3xl p-6 shadow-2xl space-y-4 text-xs text-white" @click.away="showEditProfileModal = false">
                <div class="flex justify-between items-center border-b border-slate-800 pb-3">
                    <h3 class="font-bold text-sm text-white">Cập nhật thông tin cá nhân</h3>
                    <button @click="showEditProfileModal = false" class="text-slate-400 hover:text-white font-bold text-base">&times;</button>
                </div>

                <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                    @csrf
                    @method('patch')
                    <div>
                        <label class="block text-slate-400 font-semibold mb-1">Họ và tên</label>
                        <input type="text" name="name" value="{{ $user->name }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-white">
                    </div>
                    <div>
                        <label class="block text-slate-400 font-semibold mb-1">Giới tính</label>
                        <select name="gender" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-white">
                            <option value="Nam" {{ ($user->gender ?? 'Nam') === 'Nam' ? 'selected' : '' }}>Nam</option>
                            <option value="Nữ" {{ ($user->gender ?? '') === 'Nữ' ? 'selected' : '' }}>Nữ</option>
                            <option value="Khác" {{ ($user->gender ?? '') === 'Khác' ? 'selected' : '' }}>Khác</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-400 font-semibold mb-1">Ngày sinh</label>
                        <input type="date" name="birthday" value="{{ $user->birthday ?? '2005-05-18' }}" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-white">
                    </div>
                    <div>
                        <label class="block text-slate-400 font-semibold mb-1">Số điện thoại</label>
                        <input type="text" name="phone" value="{{ $user->phone ?? '0777190215' }}" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-white font-mono">
                    </div>
                    <div>
                        <label class="block text-slate-400 font-semibold mb-1">Email</label>
                        <input type="email" name="email" value="{{ $user->email }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-white font-mono">
                    </div>

                    <div class="pt-3 flex gap-3">
                        <button type="button" @click="showEditProfileModal = false" class="flex-1 py-3 border border-slate-800 font-bold rounded-xl text-slate-400 hover:bg-slate-800">Hủy</button>
                        <button type="submit" class="flex-1 py-3 bg-rose-600 hover:bg-rose-500 text-white font-bold rounded-xl shadow-md">Cập nhật thông tin</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ================= MODAL 2: THÊM ĐỊA CHỈ MỚI ================= -->
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm" x-show="showAddAddressModal" style="display: none;">
            <div class="bg-slate-900 border border-slate-800 w-full max-w-lg rounded-3xl p-6 shadow-2xl space-y-4 text-xs text-white" @click.away="showAddAddressModal = false">
                <div class="flex justify-between items-center border-b border-slate-800 pb-3">
                    <h3 class="font-bold text-sm text-white">Thêm địa chỉ nhận hàng</h3>
                    <button @click="showAddAddressModal = false" class="text-slate-400 hover:text-white font-bold text-base">&times;</button>
                </div>

                <form method="POST" action="{{ route('user.addresses.store') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-slate-400 font-semibold mb-1">Tỉnh / Thành phố</label>
                        <input type="text" name="city" placeholder="Ví dụ: TP. Hồ Chí Minh" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-white">
                    </div>
                    <div>
                        <label class="block text-slate-400 font-semibold mb-1">Quận / Huyện</label>
                        <input type="text" name="district" placeholder="Ví dụ: Quận Bình Tân" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-white">
                    </div>
                    <div>
                        <label class="block text-slate-400 font-semibold mb-1">Phường / Xã</label>
                        <input type="text" name="ward" placeholder="Ví dụ: Phường An Lạc" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-white">
                    </div>
                    <div>
                        <label class="block text-slate-400 font-semibold mb-1">Địa chỉ chi tiết (Số nhà, tên đường)</label>
                        <input type="text" name="address_detail" placeholder="Ví dụ: 102/1c Lê Tấn Bế" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-white">
                    </div>
                    <div>
                        <label class="block text-slate-400 font-semibold mb-1">Nhãn địa chỉ</label>
                        <input type="text" name="label" value="Nhà" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-white">
                    </div>
                    <div class="flex items-center gap-2 pt-2">
                        <input type="checkbox" name="is_default" value="1" id="is_default" class="rounded text-rose-600 bg-slate-950 border-slate-800">
                        <label for="is_default" class="text-slate-300 font-medium cursor-pointer">Đặt làm địa chỉ mặc định</label>
                    </div>

                    <div class="pt-3 flex gap-3">
                        <button type="button" @click="showAddAddressModal = false" class="flex-1 py-3 border border-slate-800 font-bold rounded-xl text-slate-400 hover:bg-slate-800">Hủy</button>
                        <button type="submit" class="flex-1 py-3 bg-rose-600 hover:bg-rose-500 text-white font-bold rounded-xl shadow-md">Thêm địa chỉ</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ================= MODAL 3: ĐỔI MẬT KHẨU ================= -->
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm" x-show="showChangePasswordModal" style="display: none;">
            <div class="bg-slate-900 border border-slate-800 w-full max-w-lg rounded-3xl p-6 shadow-2xl space-y-4 text-xs text-white" @click.away="showChangePasswordModal = false">
                <div class="flex justify-between items-center border-b border-slate-800 pb-3">
                    <h3 class="font-bold text-sm text-white">Đổi mật khẩu bảo mật</h3>
                    <button @click="showChangePasswordModal = false" class="text-slate-400 hover:text-white font-bold text-base">&times;</button>
                </div>

                <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                    @csrf
                    @method('put')
                    <div>
                        <label class="block text-slate-400 font-semibold mb-1">Mật khẩu cũ</label>
                        <input type="password" name="current_password" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-white">
                    </div>
                    <div>
                        <label class="block text-slate-400 font-semibold mb-1">Mật khẩu mới</label>
                        <input type="password" name="password" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-white">
                    </div>
                    <div>
                        <label class="block text-slate-400 font-semibold mb-1">Xác nhận mật khẩu mới</label>
                        <input type="password" name="password_confirmation" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-white">
                    </div>

                    <div class="pt-3 flex gap-3">
                        <button type="button" @click="showChangePasswordModal = false" class="flex-1 py-3 border border-slate-800 font-bold rounded-xl text-slate-400 hover:bg-slate-800">Hủy</button>
                        <button type="submit" class="flex-1 py-3 bg-rose-600 hover:bg-rose-500 text-white font-bold rounded-xl shadow-md">Đổi mật khẩu</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-store-layout>