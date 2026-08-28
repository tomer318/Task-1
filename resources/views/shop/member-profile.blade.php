<x-store-layout>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-6 text-white bg-slate-950 min-h-screen"
         x-data="{ 
             activeTab: '{{ $activeTab ?? 'overview' }}',
             showEditProfileModal: false,
             showAddAddressModal: false,
             showChangePasswordModal: false,
             selectedOrder: null,
             
             // Review Modal
             showReviewOrderModal: false,
             reviewingOrder: null,
             orderRating: 5,
             selectedTags: [],
             availableTags: ['Giao hàng siêu nhanh', 'Đóng gói sạch sẽ, cẩn thận', 'Nhân viên chuyên nghiệp', 'Sản phẩm nguyên seal chính hãng'],
             
             // Cancel Order Modal
             showCancelModal: false,
             cancellingOrder: null,
             selectedCancelTags: [],
             availableCancelTags: ['Tôi muốn đổi ý', 'Thay đổi phương thức thanh toán', 'Thay đổi địa chỉ / thông tin nhận', 'Không còn nhu cầu mua nữa', 'Thời gian giao quá lâu'],

             // Return Request Modal
             showReturnModal: false,
             returningOrder: null,
             selectedReturnTags: [],
             availableReturnTags: ['Sản phẩm không giống mô tả', 'Sản phẩm bị lỗi / hỏng', 'Giao thiếu phụ kiện / quà tặng', 'Hộp rách móp nặng'],

             openReviewModal(order) {
                 this.reviewingOrder = order;
                 this.orderRating = 5;
                 this.selectedTags = [];
                 this.showReviewOrderModal = true;
             },
             openCancelModal(order) {
                 this.cancellingOrder = order;
                 this.selectedCancelTags = [];
                 this.showCancelModal = true;
             },
             openReturnModal(order) {
                 this.returningOrder = order;
                 this.selectedReturnTags = [];
                 this.showReturnModal = true;
             },
             toggleTag(arrayName, tag) {
                 if (this[arrayName].includes(tag)) {
                     this[arrayName] = this[arrayName].filter(t => t !== tag);
                 } else {
                     this[arrayName].push(tag);
                 }
             },
             showOrderDetailModal(order) {
                 this.selectedOrder = order;
             },
             getOrderSubtotal(order) {
                 if (!order || !order.items) return 0;
                 return order.items.reduce((sum, item) => sum + parseFloat(item.total), 0);
             },
             getOrderDiscount(order) {
                 if (!order) return 0;
                 const sub = this.getOrderSubtotal(order);
                 return Math.max(0, sub - parseFloat(order.total_price));
             },
             getOrderDiscountPercent(order) {
                 const sub = this.getOrderSubtotal(order);
                 const disc = this.getOrderDiscount(order);
                 return (sub > 0 && disc > 0) ? Math.round((disc / sub) * 100) : 0;
             }
         }">

        <!-- Top Header Card -->
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-2xl">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
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
                        <h1 class="text-lg font-bold text-white flex items-center gap-2">{{ $user->name }}</h1>
                        <p class="text-xs text-slate-400 font-mono mt-0.5">{{ $user->phone ?? '0777190215' }}</p>
                        <p class="text-[11px] text-rose-400 font-medium">Cập nhật lại sau 01/01/2027</p>
                    </div>
                </div>

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
            <div class="flex items-center gap-4 overflow-x-auto pt-6 mt-6 border-t border-slate-800 text-xs font-semibold text-slate-300 scrollbar-none">
                <a href="{{ route('profile.promotion') }}" class="px-3.5 py-2 bg-slate-950 border border-slate-800 hover:border-rose-500 rounded-xl transition whitespace-nowrap">🏷️ Hạng thành viên</a>
                <a href="{{ route('profile.orders') }}" class="px-3.5 py-2 bg-slate-950 border border-slate-800 hover:border-rose-500 rounded-xl transition whitespace-nowrap">📜 Lịch sử mua hàng</a>
                <a href="{{ route('profile.returns') }}" class="px-3.5 py-2 bg-slate-950 border border-slate-800 hover:border-rose-500 rounded-xl transition whitespace-nowrap">🔄 Đổi / Trả của tôi</a>
                <a href="{{ route('profile.reviews') }}" class="px-3.5 py-2 bg-slate-950 border border-slate-800 hover:border-rose-500 rounded-xl transition whitespace-nowrap">⭐ Đánh giá của tôi</a>
                <a href="{{ route('profile.user.info') }}" class="px-3.5 py-2 bg-slate-950 border border-slate-800 hover:border-rose-500 rounded-xl transition whitespace-nowrap">📍 Sổ địa chỉ</a>
            </div>
        </div>

        <!-- Main Body -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- Sidebar Navigation -->
            <div class="lg:col-span-3 bg-slate-900 border border-slate-800 rounded-2xl p-2 space-y-1 text-xs font-semibold text-slate-300 shadow-xl">
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ ($activeTab ?? '') === 'overview' ? 'bg-gradient-to-r from-rose-600 to-red-500 text-white shadow-md' : 'hover:bg-slate-800 hover:text-white' }}">
                    <span>🏠</span> Tổng quan
                </a>
                <a href="{{ route('profile.notifications') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ ($activeTab ?? '') === 'my-notifications' ? 'bg-gradient-to-r from-rose-600 to-red-500 text-white shadow-md' : 'hover:bg-slate-800 hover:text-white' }}">
                    <span>🔔</span> Thông báo
                    @if(Auth::user()->unreadNotificationsCount() > 0)
                        <span class="ml-auto px-1.5 py-0.5 bg-rose-500 text-white font-bold text-[10px] rounded-full">
                            {{ Auth::user()->unreadNotificationsCount() }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('profile.orders') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ ($activeTab ?? '') === 'orders' ? 'bg-gradient-to-r from-rose-600 to-red-500 text-white shadow-md' : 'hover:bg-slate-800 hover:text-white' }}">
                    <span>📜</span> Lịch sử mua hàng
                </a>
                <a href="{{ route('profile.returns') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ ($activeTab ?? '') === 'my-returns' ? 'bg-gradient-to-r from-rose-600 to-red-500 text-white shadow-md' : 'hover:bg-slate-800 hover:text-white' }}">
                    <span>🔄</span> Đổi / Trả của tôi
                </a>
                <a href="{{ route('profile.reviews') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ ($activeTab ?? '') === 'my-reviews' ? 'bg-gradient-to-r from-rose-600 to-red-500 text-white shadow-md' : 'hover:bg-slate-800 hover:text-white' }}">
                    <span>⭐</span> Đánh giá của tôi
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
                                        <span class="text-slate-400 font-mono text-[11px]">Ngày đặt: {{ $latestOrder->created_at->format('d/m/Y H:i') }}</span>
                                        <span class="px-2.5 py-0.5 rounded font-bold text-[10px] {{ in_array($latestOrder->status, ['Đã giao', 'Đã nhận hàng']) ? 'bg-emerald-500/10 text-emerald-400' : 'bg-amber-500/10 text-amber-400' }}">
                                            {{ $latestOrder->status }}
                                        </span>
                                    </div>
                                    @foreach($latestOrder->items as $item)
                                        <div class="flex items-center justify-between text-xs gap-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-12 h-12 bg-slate-900 border border-slate-800 rounded-lg flex items-center justify-center shrink-0">📦</div>
                                                <div>
                                                    <div class="font-bold text-white uppercase">{{ $item->product_name }}</div>
                                                    <div class="text-slate-400">Số lượng: {{ $item->quantity }} | Phân loại: {{ $item->version_name }} - {{ $item->color_name }}</div>
                                                </div>
                                            </div>
                                            <span class="font-mono font-bold text-rose-500">{{ number_format($item->total, 0, ',', '.') }}₫</span>
                                        </div>
                                    @endforeach
                                    <div class="pt-2 border-t border-slate-800 flex justify-between items-center text-xs">
                                        <span class="text-slate-400">Thanh toán: <strong class="text-slate-200">{{ $latestOrder->payment_method }}</strong></span>
                                        <button @click="showOrderDetailModal(@js($latestOrder->load('items')))" class="text-xs text-rose-400 font-semibold hover:underline">
                                            Xem chi tiết &gt;
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-8 text-xs text-slate-400">Chưa có đơn hàng nào gần đây.</div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- TAB 2: THÔNG BÁO CỦA TÔI -->
                @if(($activeTab ?? '') === 'my-notifications')
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl space-y-6">
                        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                            <div>
                                <h2 class="font-bold text-sm text-white">🔔 Thông Báo Của Bạn</h2>
                                <p class="text-xs text-slate-400 mt-0.5">Cập nhật đơn hàng, đổi/trả và phản hồi từ hệ thống</p>
                            </div>
                            
                            @if(isset($myNotifications) && $myNotifications->where('is_read', false)->count() > 0)
                                <form action="{{ route('notifications.markAllRead') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-xs text-rose-400 hover:text-rose-300 font-semibold cursor-pointer">
                                        ✓ Đánh dấu đã đọc tất cả
                                    </button>
                                </form>
                            @endif
                        </div>

                        <div class="space-y-3">
                            @forelse($myNotifications as $noti)
                                <div class="p-4 rounded-2xl border transition flex items-start justify-between gap-4 {{ $noti->is_read ? 'bg-slate-950/60 border-slate-800/80 opacity-80' : 'bg-slate-950 border-rose-500/40 ring-1 ring-rose-500/20 shadow-lg' }}">
                                    <div class="flex items-start gap-3">
                                        <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 text-base {{ $noti->type === 'order' ? 'bg-rose-500/10 text-rose-400 border border-rose-500/20' : ($noti->type === 'return' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-blue-500/10 text-blue-400 border border-blue-500/20') }}">
                                            {{ $noti->type === 'order' ? '📦' : ($noti->type === 'return' ? '🔄' : '💬') }}
                                        </div>
                                        <div class="space-y-1">
                                            <div class="flex items-center gap-2">
                                                <strong class="text-white text-xs {{ !$noti->is_read ? 'font-black text-rose-400' : '' }}">{{ $noti->title }}</strong>
                                                @if(!$noti->is_read)
                                                    <span class="w-2 h-2 rounded-full bg-rose-500 inline-block"></span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-slate-300 leading-relaxed">{{ $noti->message }}</p>
                                            <span class="text-[10px] text-slate-500 font-mono block">{{ $noti->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>

                                    <div class="shrink-0 flex items-center gap-2">
                                        @if($noti->link)
                                            <form action="{{ route('notifications.read', $noti->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="px-3 py-1.5 bg-slate-900 hover:bg-slate-800 border border-slate-700 text-slate-300 hover:text-white rounded-lg text-xs font-semibold transition cursor-pointer">
                                                    Xem chi tiết &gt;
                                                </button>
                                            </form>
                                        @elseif(!$noti->is_read)
                                            <form action="{{ route('notifications.read', $noti->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="px-2.5 py-1 text-slate-400 hover:text-white text-[11px] font-semibold cursor-pointer">
                                                    Đã đọc
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="py-12 text-center text-xs text-slate-500">Bạn chưa có thông báo nào.</div>
                            @endforelse
                        </div>
                    </div>
                @endif

                <!-- TAB 3: LỊCH SỬ MUA HÀNG -->
                @if(($activeTab ?? '') === 'orders')
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl space-y-6" x-data="{ orderSubTab: 'all' }">
                        <div class="flex gap-4 border-b border-slate-800 pb-3 text-xs font-bold text-slate-400 overflow-x-auto scrollbar-none">
                            <button @click="orderSubTab = 'all'" :class="orderSubTab === 'all' ? 'text-rose-500 border-b-2 border-rose-500 pb-3 -mb-3' : 'hover:text-white'" class="whitespace-nowrap">Tất cả ({{ $recentOrders->count() }})</button>
                            <button @click="orderSubTab = 'Chờ xử lý'" :class="orderSubTab === 'Chờ xử lý' ? 'text-rose-500 border-b-2 border-rose-500 pb-3 -mb-3' : 'hover:text-white'" class="whitespace-nowrap">Chờ xử lý</button>
                            <button @click="orderSubTab = 'Đã xử lý'" :class="orderSubTab === 'Đã xử lý' ? 'text-rose-500 border-b-2 border-rose-500 pb-3 -mb-3' : 'hover:text-white'" class="whitespace-nowrap">Đã xử lý</button>
                            <button @click="orderSubTab = 'Đang chuẩn bị hàng'" :class="orderSubTab === 'Đang chuẩn bị hàng' ? 'text-rose-500 border-b-2 border-rose-500 pb-3 -mb-3' : 'hover:text-white'" class="whitespace-nowrap">Đang chuẩn bị hàng</button>
                            <button @click="orderSubTab = 'Đang giao hàng'" :class="orderSubTab === 'Đang giao hàng' ? 'text-rose-500 border-b-2 border-rose-500 pb-3 -mb-3' : 'hover:text-white'" class="whitespace-nowrap">Đang giao hàng</button>
                            <button @click="orderSubTab = 'Đã giao'" :class="orderSubTab === 'Đã giao' ? 'text-rose-500 border-b-2 border-rose-500 pb-3 -mb-3' : 'hover:text-white'" class="whitespace-nowrap">Đã giao</button>
                            <button @click="orderSubTab = 'Đã hủy'" :class="orderSubTab === 'Đã hủy' ? 'text-rose-500 border-b-2 border-rose-500 pb-3 -mb-3' : 'hover:text-white'" class="whitespace-nowrap">Đã huỷ</button>
                        </div>

                        @if(isset($recentOrders) && $recentOrders->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentOrders as $order)
                                    @php
                                        $hasReviewed = $order->review !== null || $order->productReviews->count() > 0;
                                        $canCancel = in_array($order->status, ['Chờ xử lý', 'Đã xử lý', 'Đang chuẩn bị hàng']);
                                    @endphp
                                    <div class="border border-slate-800 rounded-xl p-4 space-y-3 bg-slate-950"
                                         x-show="orderSubTab === 'all' || orderSubTab === '{{ $order->status }}'">
                                        
                                        <div class="flex items-center justify-between text-xs pb-2 border-b border-slate-800">
                                            <span class="font-mono font-bold text-slate-300">Đơn hàng: #{{ $order->order_code ?? 'ORD-'.$order->id }}</span>
                                            <span class="text-slate-400 font-mono text-[11px]">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</span>
                                            <span class="px-2.5 py-0.5 rounded font-bold text-[10px] {{ in_array($order->status, ['Đã giao', 'Đã nhận hàng']) ? 'bg-emerald-500/10 text-emerald-400' : ($order->status === 'Đã hủy' ? 'bg-rose-500/10 text-rose-400' : 'bg-amber-500/10 text-amber-400') }}">
                                                {{ $order->status }}
                                            </span>
                                        </div>

                                        @foreach($order->items as $item)
                                            <div class="flex items-center justify-between text-xs gap-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-12 h-12 bg-slate-900 border border-slate-800 rounded-lg flex items-center justify-center shrink-0">📦</div>
                                                    <div>
                                                        <div class="font-bold text-white uppercase">{{ $item->product_name }}</div>
                                                        <div class="text-slate-400">Số lượng: {{ $item->quantity }} | Phân loại: {{ $item->version_name }} - {{ $item->color_name }}</div>
                                                    </div>
                                                </div>
                                                <span class="font-mono font-bold text-rose-500">{{ number_format($item->total, 0, ',', '.') }}₫</span>
                                            </div>
                                        @endforeach

                                        <div class="pt-2 border-t border-slate-800 flex justify-between items-center text-xs">
                                            <span class="text-slate-400">Thanh toán: <strong class="text-slate-200">{{ $order->payment_method }} ({{ $order->payment_status }})</strong></span>
                                            <div class="flex items-center gap-2">
                                                <span class="font-mono font-bold text-white text-sm mr-2">Tổng: <span class="text-rose-500">{{ number_format($order->total_price, 0, ',', '.') }}₫</span></span>
                                                
                                                @if($canCancel)
                                                    <button type="button" @click="openCancelModal(@js($order))" class="px-3 py-1.5 bg-slate-900 hover:bg-rose-950/60 border border-rose-800/40 text-rose-400 rounded-lg text-xs font-semibold transition">
                                                        Hủy Đơn
                                                    </button>
                                                @endif

                                                @if($hasReviewed)
                                                    <span class="px-3 py-1.5 bg-slate-900 border border-emerald-500/30 text-emerald-400 rounded-lg text-xs font-semibold cursor-not-allowed">
                                                        ✓ Đã Đánh Giá
                                                    </span>
                                                @elseif(in_array($order->status, ['Đã giao', 'Đã nhận hàng']))
                                                    <button type="button" @click="openReviewModal(@js($order->load('items')))" class="px-3 py-1.5 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white rounded-lg text-xs font-semibold shadow transition">
                                                        ⭐ Đánh Giá
                                                    </button>
                                                @endif

                                                <button type="button" @click="showOrderDetailModal(@js($order->load(['items', 'returnRequest'])))" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-500 text-white rounded-lg text-xs font-semibold shadow transition">
                                                    Xem chi tiết &gt;
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="py-12 text-center text-xs text-slate-400">Không có đơn hàng nào.</div>
                        @endif
                    </div>
                @endif

                <!-- TAB 4: ĐỔI / TRẢ CỦA TÔI -->
                @if(($activeTab ?? '') === 'my-returns')
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl space-y-6">
                        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                            <h2 class="font-bold text-sm text-white">Yêu cầu Đổi / Trả của bạn</h2>
                            <span class="text-xs text-rose-400 font-mono">{{ $myReturnRequests->count() }} yêu cầu</span>
                        </div>

                        <div class="space-y-4">
                            @forelse($myReturnRequests as $ret)
                                <div class="p-4 bg-slate-950 border border-slate-800 rounded-2xl space-y-3 text-xs">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <span class="font-mono font-bold text-rose-400">Đơn hàng #{{ $ret->order->order_code }}</span>
                                            <span class="text-slate-500 ml-2 text-[11px]">{{ $ret->created_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                        <span class="px-2.5 py-1 rounded-full font-bold text-[10px] {{ in_array($ret->status, ['Đã đổi/trả', 'Đã hoàn tiền']) ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' : ($ret->status === 'Từ chối' ? 'bg-rose-500/10 text-rose-400 border border-rose-500/30' : 'bg-amber-500/10 text-amber-400 border border-amber-500/30') }}">
                                            {{ $ret->status }}
                                        </span>
                                    </div>

                                    <div>
                                        <span class="text-slate-400 block mb-1">Tags lý do:</span>
                                        <div class="flex flex-wrap gap-1.5">
                                            @if(is_array($ret->tags))
                                                @foreach($ret->tags as $t)
                                                    <span class="px-2.5 py-0.5 bg-rose-500/10 text-rose-400 border border-rose-500/20 rounded-md text-[10px] font-semibold">{{ $t }}</span>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>

                                    <p class="text-slate-300 leading-relaxed bg-slate-900/60 p-3 rounded-xl">"{{ $ret->reason }}"</p>

                                    @if(is_array($ret->images) && count($ret->images) > 0)
                                        <div class="flex gap-2">
                                            @foreach($ret->images as $img)
                                                <img src="{{ asset('storage/' . $img) }}" class="w-14 h-14 object-cover rounded-lg border border-slate-800">
                                            @endforeach
                                        </div>
                                    @endif

                                    @if($ret->admin_note)
                                        <div class="p-3 bg-slate-900 border-l-2 border-rose-500 rounded-r-xl space-y-1">
                                            <span class="text-rose-400 font-bold text-[11px] block">💬 Phản hồi từ Quản trị viên:</span>
                                            <p class="text-slate-300 text-[11px]">{{ $ret->admin_note }}</p>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="py-12 text-center text-xs text-slate-500">Bạn chưa có yêu cầu đổi/trả nào.</div>
                            @endforelse
                        </div>
                    </div>
                @endif

                <!-- TAB 5: ĐÁNH GIÁ CỦA TÔI -->
                @if(($activeTab ?? '') === 'my-reviews')
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl space-y-6" x-data="{ reviewSubTab: 'products' }">
                        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                            <h2 class="font-bold text-sm text-white">Lịch sử đánh giá của bạn</h2>
                            <div class="flex gap-2">
                                <button @click="reviewSubTab = 'products'" :class="reviewSubTab === 'products' ? 'bg-rose-600 text-white' : 'bg-slate-950 text-slate-400'" class="px-3 py-1 rounded-lg text-xs font-semibold transition">Đánh giá sản phẩm ({{ $myProductReviews->count() }})</button>
                                <button @click="reviewSubTab = 'orders'" :class="reviewSubTab === 'orders' ? 'bg-rose-600 text-white' : 'bg-slate-950 text-slate-400'" class="px-3 py-1 rounded-lg text-xs font-semibold transition">Đánh giá đơn hàng ({{ $myOrderReviews->count() }})</button>
                            </div>
                        </div>

                        <div x-show="reviewSubTab === 'products'" class="space-y-4">
                            @forelse($myProductReviews as $prev)
                                <div class="p-4 bg-slate-950 border border-slate-800 rounded-2xl space-y-3 text-xs">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-slate-900 rounded-lg flex items-center justify-center">📦</div>
                                            <div>
                                                <a href="{{ route('shop.product', $prev->product->slug ?? '#') }}" class="font-bold text-white uppercase hover:text-rose-400 transition">{{ $prev->product->name ?? 'Sản phẩm' }}</a>
                                                <span class="text-slate-500 block text-[11px]">Đơn hàng: #{{ $prev->order->order_code ?? $prev->order_id }} • {{ $prev->created_at->format('d/m/Y H:i') }}</span>
                                            </div>
                                        </div>
                                        <span class="text-amber-400 font-bold">★ {{ $prev->rating }}/5</span>
                                    </div>
                                    <p class="text-slate-300 leading-relaxed bg-slate-900/50 p-3 rounded-xl">{{ $prev->comment }}</p>
                                    @if(is_array($prev->images) && count($prev->images) > 0)
                                        <div class="flex gap-2">
                                            @foreach($prev->images as $img)
                                                <img src="{{ asset('storage/' . $img) }}" class="w-14 h-14 object-cover rounded-lg border border-slate-800">
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="py-12 text-center text-xs text-slate-500">Bạn chưa có đánh giá sản phẩm nào.</div>
                            @endforelse
                        </div>

                        <div x-show="reviewSubTab === 'orders'" style="display: none;" class="space-y-4">
                            @forelse($myOrderReviews as $orev)
                                <div class="p-4 bg-slate-950 border border-slate-800 rounded-2xl space-y-3 text-xs">
                                    <div class="flex items-center justify-between">
                                        <span class="font-mono font-bold text-rose-400">Đơn hàng #{{ $orev->order->order_code ?? $orev->order_id }}</span>
                                        <span class="text-amber-400 font-bold">★ {{ $orev->rating }}/5</span>
                                    </div>
                                    @if(is_array($orev->tags) && count($orev->tags) > 0)
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach($orev->tags as $t)
                                                <span class="px-2.5 py-0.5 bg-rose-500/10 text-rose-400 border border-rose-500/20 rounded-md text-[10px]">{{ $t }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                    @if($orev->comment)
                                        <p class="text-slate-300 italic">"{{ $orev->comment }}"</p>
                                    @endif
                                </div>
                            @empty
                                <div class="py-12 text-center text-xs text-slate-500">Bạn chưa có đánh giá dịch vụ đơn hàng nào.</div>
                            @endforelse
                        </div>
                    </div>
                @endif

                <!-- TAB 6: HẠNG THÀNH VIÊN VÀ ƯU ĐÃI -->
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
                    </div>
                @endif

                <!-- TAB 7: THÔNG TIN TÀI KHOẢN & SỔ ĐỊA CHỈ & MẬT KHẨU -->
                @if(($activeTab ?? '') === 'user-info')
                    <div class="space-y-6">
                        <!-- Thông tin cá nhân -->
                        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl space-y-4">
                            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                                <h2 class="font-bold text-sm text-white">Thông tin cá nhân</h2>
                                <button type="button" @click="showEditProfileModal = true" class="text-xs text-rose-400 font-semibold hover:underline flex items-center gap-1 cursor-pointer">
                                    ✏️ Cập nhật
                                </button>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                                <div><span class="text-slate-400 block">Họ và tên:</span><strong class="text-white text-sm">{{ $user->name }}</strong></div>
                                <div><span class="text-slate-400 block">Số điện thoại:</span><strong class="text-white text-sm font-mono">{{ $user->phone ?? '0777190215' }}</strong></div>
                                <div><span class="text-slate-400 block">Giới tính:</span><strong class="text-white text-sm">{{ $user->gender ?? 'Nam' }}</strong></div>
                                <div><span class="text-slate-400 block">Email:</span><strong class="text-white text-sm font-mono">{{ $user->email }}</strong></div>
                                <div><span class="text-slate-400 block">Ngày sinh:</span><strong class="text-white text-sm">{{ $user->birthday ? \Carbon\Carbon::parse($user->birthday)->format('d/m/Y') : '18/05/2005' }}</strong></div>
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
                                <button type="button" @click="showAddAddressModal = true" class="text-xs text-rose-400 font-semibold hover:underline cursor-pointer">
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
                                                <button type="submit" class="text-rose-400 hover:underline font-semibold text-xs cursor-pointer">Xóa</button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-xs text-slate-400 py-4">Bạn chưa thêm địa chỉ nào.</div>
                            @endif
                        </div>

                        <!-- Mật khẩu bảo mật -->
                        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl flex items-center justify-between text-xs">
                            <div>
                                <h2 class="font-bold text-sm text-white">Mật khẩu bảo mật</h2>
                                <p class="text-slate-400 mt-0.5">Cập nhật định kỳ để bảo vệ tài khoản của bạn.</p>
                            </div>
                            <button type="button" @click="showChangePasswordModal = true" class="px-4 py-2 border border-rose-500 text-rose-400 font-bold rounded-xl hover:bg-rose-500/10 transition cursor-pointer">
                                Thay đổi mật khẩu
                            </button>
                        </div>
                    </div>
                @endif

            </div>

        </div>

        <!-- ================= MODAL XEM CHI TIẾT ĐƠN HÀNG ================= -->
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4" x-show="selectedOrder" style="display: none;">
            <div class="bg-slate-900 border border-slate-800 w-full max-w-3xl rounded-3xl p-6 shadow-2xl space-y-5 text-xs text-white max-h-[90vh] overflow-y-auto" @click.away="selectedOrder = null">
                <div class="flex justify-between items-center border-b border-slate-800 pb-3">
                    <div>
                        <h3 class="font-bold text-base text-white">Chi tiết đơn hàng: <span class="text-rose-400 font-mono" x-text="selectedOrder ? '#' + selectedOrder.order_code : ''"></span></h3>
                        <span class="text-slate-400 text-[11px]" x-text="selectedOrder ? 'Ngày đặt: ' + selectedOrder.created_at : ''"></span>
                    </div>
                    <button @click="selectedOrder = null" class="text-slate-400 hover:text-white font-bold text-xl">&times;</button>
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
                                    <div class="w-12 h-12 bg-slate-900 border border-slate-800 rounded-xl flex items-center justify-center shrink-0 text-xl">📦</div>
                                    <div>
                                        <div class="font-bold text-white uppercase text-xs" x-text="item.product_name"></div>
                                        <div class="text-slate-400 text-[11px] mt-0.5">
                                            Biến thể: <span class="text-rose-400" x-text="item.version_name + ' - ' + item.color_name"></span> | 
                                            Đơn giá: <span class="font-mono text-slate-300" x-text="new Intl.NumberFormat('vi-VN').format(item.price) + '₫'"></span> | 
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
                        <span>1. Tổng tiền hàng gốc:</span>
                        <strong class="font-mono text-white" x-text="new Intl.NumberFormat('vi-VN').format(getOrderSubtotal(selectedOrder)) + '₫'"></strong>
                    </div>
                    <div class="flex justify-between text-slate-300">
                        <span>2. Phí vận chuyển:</span>
                        <strong class="font-mono text-emerald-400" x-text="getOrderSubtotal(selectedOrder) >= 300000 ? '0₫ (Miễn phí vì đơn hàng > 300.000₫)' : '30.000₫'"></strong>
                    </div>
                    <div class="flex justify-between text-emerald-400" x-show="getOrderDiscount(selectedOrder) > 0">
                        <span>3. Giảm giá Voucher (<span x-text="getOrderDiscountPercent(selectedOrder) + '%'"></span>):</span>
                        <strong class="font-mono font-bold" x-text="'-' + new Intl.NumberFormat('vi-VN').format(getOrderDiscount(selectedOrder)) + '₫'"></strong>
                    </div>
                    <div class="pt-2 border-t border-slate-800 flex justify-between items-baseline">
                        <span class="font-bold text-white text-sm">TỔNG THANH TOÁN THỰC TẾ:</span>
                        <strong class="font-mono font-black text-rose-500 text-base" x-text="selectedOrder ? new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(selectedOrder.total_price) : ''"></strong>
                    </div>
                </div>

                <div class="pt-2 flex gap-3">
                    <template x-if="selectedOrder && (selectedOrder.status === 'Đã giao' || selectedOrder.status === 'Đã nhận hàng')">
                        <div class="flex-1">
                            <template x-if="selectedOrder.return_request">
                                <button type="button" disabled class="w-full py-3 bg-slate-950 border border-amber-500/40 text-amber-400/80 font-bold rounded-xl cursor-not-allowed opacity-80 flex items-center justify-center gap-1.5">
                                    <span>🔄</span> 
                                    <span>Đã yêu cầu đổi/trả (<span x-text="selectedOrder.return_request.status"></span>)</span>
                                </button>
                            </template>

                            <template x-if="!selectedOrder.return_request">
                                <button type="button" 
                                        @click="const current = selectedOrder; selectedOrder = null; openReturnModal(current)"
                                        class="w-full py-3 bg-slate-900 border border-amber-500 hover:bg-slate-800 text-amber-400 font-bold rounded-xl transition shadow-lg shadow-amber-500/10">
                                    🔄 Yêu Cầu Đổi / Trả Hàng
                                </button>
                            </template>
                        </div>
                    </template>

                    <button type="button" @click="selectedOrder = null" class="flex-1 py-3 bg-rose-600 hover:bg-rose-500 text-white font-bold rounded-xl shadow transition">Đóng</button>
                </div>
            </div>
        </div>

        <!-- ================= MODAL HỦY ĐƠN HÀNG ================= -->
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/85 backdrop-blur-sm p-4" x-show="showCancelModal" style="display: none;">
            <div class="bg-slate-900 border border-slate-800 w-full max-w-lg rounded-3xl p-6 shadow-2xl space-y-5 text-xs text-white" @click.away="showCancelModal = false">
                <div class="flex justify-between items-center border-b border-slate-800 pb-3">
                    <h3 class="font-bold text-sm text-white">Xác Nhận Hủy Đơn Hàng <span class="text-rose-400 font-mono" x-text="cancellingOrder ? '#' + cancellingOrder.order_code : ''"></span></h3>
                    <button @click="showCancelModal = false" class="text-slate-400 hover:text-white font-bold text-xl">&times;</button>
                </div>

                <p class="text-amber-300 bg-amber-950/40 p-3 rounded-xl border border-amber-800/40 leading-relaxed">
                    ⚠️ Bạn có chắc chắn muốn hủy đơn hàng này không? Sau khi xác nhận hủy, đơn hàng sẽ không thể khôi phục lại.
                </p>

                <form :action="'/orders/' + (cancellingOrder ? cancellingOrder.id : '') + '/cancel'" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-slate-400 font-semibold mb-1.5">Chọn lý do hủy nhanh (Tags):</label>
                        <div class="flex flex-wrap gap-1.5">
                            <template x-for="tag in availableCancelTags">
                                <button type="button" @click="toggleTag('selectedCancelTags', tag)"
                                        :class="selectedCancelTags.includes(tag) ? 'bg-rose-600 text-white border-rose-500' : 'bg-slate-950 text-slate-400 border-slate-800 hover:border-slate-700'"
                                        class="px-2.5 py-1.5 rounded-lg border text-[11px] font-semibold transition"
                                        x-text="tag"></button>
                            </template>
                        </div>
                        <template x-for="tag in selectedCancelTags">
                            <input type="hidden" name="cancel_tags[]" :value="tag">
                        </template>
                    </div>

                    <div>
                        <label class="block text-slate-400 font-semibold mb-1">Nêu lý do chi tiết (nếu có):</label>
                        <textarea name="cancel_reason" rows="2" placeholder="Nhập lý do hủy..." class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white"></textarea>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="showCancelModal = false" class="flex-1 py-3 border border-slate-800 font-bold rounded-xl text-slate-400 hover:bg-slate-800">Không Hủy</button>
                        <button type="submit" class="flex-1 py-3 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl shadow-lg transition">Xác Nhận Hủy Đơn</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ================= MODAL YÊU CẦU ĐỔI / TRẢ HÀNG ================= -->
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/85 backdrop-blur-sm p-4" x-show="showReturnModal" style="display: none;">
            <div class="bg-slate-900 border border-slate-800 w-full max-w-2xl rounded-3xl p-6 shadow-2xl space-y-5 text-xs text-white max-h-[90vh] overflow-y-auto" @click.away="showReturnModal = false">
                <div class="flex justify-between items-center border-b border-slate-800 pb-3">
                    <h3 class="font-bold text-sm text-white">Yêu Cầu Đổi / Trả Hàng Cho Đơn <span class="text-rose-400 font-mono" x-text="returningOrder ? '#' + returningOrder.order_code : ''"></span></h3>
                    <button @click="showReturnModal = false" class="text-slate-400 hover:text-white font-bold text-xl">&times;</button>
                </div>

                <form :action="'/orders/' + (returningOrder ? returningOrder.id : '') + '/return'" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-slate-400 font-semibold mb-1.5">Chọn lý do đổi/trả (Tags):</label>
                        <div class="flex flex-wrap gap-1.5">
                            <template x-for="tag in availableReturnTags">
                                <button type="button" @click="toggleTag('selectedReturnTags', tag)"
                                        :class="selectedReturnTags.includes(tag) ? 'bg-rose-600 text-white border-rose-500' : 'bg-slate-950 text-slate-400 border-slate-800 hover:border-slate-700'"
                                        class="px-2.5 py-1.5 rounded-lg border text-[11px] font-semibold transition"
                                        x-text="tag"></button>
                            </template>
                        </div>
                        <template x-for="tag in selectedReturnTags">
                            <input type="hidden" name="return_tags[]" :value="tag">
                        </template>
                    </div>

                    <div>
                        <label class="block text-slate-400 font-semibold mb-1">Mô tả tình trạng hàng chi tiết (*):</label>
                        <textarea name="return_reason" rows="3" required placeholder="Vui lòng mô tả chi tiết lỗi hoặc vấn đề gặp phải..." class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-white"></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-[11px]">
                        <div>
                            <label class="block text-slate-400 mb-1">📸 Đính kèm ảnh thực tế sản phẩm (Tối đa 5MB):</label>
                            <input type="file" name="images[]" multiple accept="image/*" class="w-full text-slate-400 file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:bg-slate-800 file:text-white file:text-xs">
                        </div>
                        <div>
                            <label class="block text-slate-400 mb-1">🎥 Video clip khui hàng / lỗi (1-2 phút):</label>
                            <input type="file" name="video" accept="video/*" class="w-full text-slate-400 file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:bg-slate-800 file:text-white file:text-xs">
                        </div>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="showReturnModal = false" class="flex-1 py-3 border border-slate-800 font-bold rounded-xl text-slate-400 hover:bg-slate-800">Hủy</button>
                        <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-bold rounded-xl shadow-lg transition">Gửi Yêu Cầu Đổi / Trả</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ================= MODAL ĐÁNH GIÁ ĐƠN HÀNG VÀ SẢN PHẨM ================= -->
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/85 backdrop-blur-sm p-4" x-show="showReviewOrderModal" style="display: none;">
            <div class="bg-slate-900 border border-slate-800 w-full max-w-3xl rounded-3xl p-6 shadow-2xl space-y-5 text-xs text-white max-h-[90vh] overflow-y-auto" @click.away="showReviewOrderModal = false">
                <div class="flex justify-between items-center border-b border-slate-800 pb-3">
                    <h3 class="font-bold text-sm text-white">Đánh Giá Đơn Hàng <span class="text-rose-400 font-mono" x-text="reviewingOrder ? '#' + reviewingOrder.order_code : ''"></span></h3>
                    <button @click="showReviewOrderModal = false" class="text-slate-400 hover:text-white font-bold text-xl">&times;</button>
                </div>

                <form :action="'/orders/' + (reviewingOrder ? reviewingOrder.id : '') + '/reviews'" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div class="p-4 bg-slate-950 border border-slate-800 rounded-2xl space-y-3">
                        <h4 class="font-bold text-white text-xs">1. Đánh giá chất lượng dịch vụ đơn hàng</h4>
                        <div class="flex items-center gap-2">
                            <span class="text-slate-400">Mức độ hài lòng:</span>
                            <div class="flex gap-1 text-lg text-amber-400 cursor-pointer">
                                <template x-for="star in 5">
                                    <span @click="orderRating = star" x-text="star <= orderRating ? '★' : '☆'"></span>
                                </template>
                            </div>
                            <input type="hidden" name="order_rating" :value="orderRating">
                        </div>

                        <div>
                            <span class="text-slate-400 block mb-1.5">Gợi ý đánh giá nhanh:</span>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="tag in availableTags">
                                    <button type="button" @click="toggleTag('selectedTags', tag)"
                                            :class="selectedTags.includes(tag) ? 'bg-rose-600 text-white border-rose-500' : 'bg-slate-900 text-slate-400 border-slate-800 hover:border-slate-700'"
                                            class="px-3 py-1.5 rounded-xl border text-[11px] font-semibold transition"
                                            x-text="tag"></button>
                                </template>
                            </div>
                            <template x-for="tag in selectedTags">
                                <input type="hidden" name="order_tags[]" :value="tag">
                            </template>
                        </div>

                        <div>
                            <label class="block text-slate-400 mb-1">Cảm nhận về dịch vụ giao nhận:</label>
                            <input type="text" name="order_comment" placeholder="Ví dụ: Giao hàng rất cẩn thận, shipper nhiệt tình..." class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3.5 py-2.5 text-white">
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h4 class="font-bold text-white text-xs">2. Đánh giá chất lượng sản phẩm đã nhận</h4>
                        <template x-for="item in (reviewingOrder ? reviewingOrder.items : [])" :key="item.id">
                            <div class="p-4 bg-slate-950 border border-slate-800 rounded-2xl space-y-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-slate-900 rounded-lg flex items-center justify-center">📦</div>
                                    <div class="font-bold text-white uppercase text-xs" x-text="item.product_name"></div>
                                </div>
                                <div>
                                    <label class="block text-slate-400 mb-1">Nhận xét sản phẩm:</label>
                                    <textarea :name="'products[' + item.product_id + '][comment]'" rows="2" placeholder="Sản phẩm dùng tốt, pin trâu, đóng gói kỹ càng..." required class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3.5 py-2 text-white"></textarea>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-[11px]">
                                    <div>
                                        <label class="block text-slate-400 mb-1">📸 Đính kèm ảnh thực tế (Tối đa 5MB):</label>
                                        <input type="file" :name="'products[' + item.product_id + '][images][]'" multiple accept="image/*" class="w-full text-slate-400 file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:bg-slate-800 file:text-white file:text-xs">
                                    </div>
                                    <div>
                                        <label class="block text-slate-400 mb-1">🎥 Video clip ngắn (1-2 phút):</label>
                                        <input type="file" :name="'products[' + item.product_id + '][video]'" accept="video/*" class="w-full text-slate-400 file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:bg-slate-800 file:text-white file:text-xs">
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="showReviewOrderModal = false" class="flex-1 py-3 border border-slate-800 font-bold rounded-xl text-slate-400 hover:bg-slate-800">Hủy</button>
                        <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-rose-600 to-red-500 hover:from-rose-500 hover:to-red-400 text-white font-bold rounded-xl shadow-lg transition">Gửi Đánh Giá</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ================= MODAL 1: CẬP NHẬT THÔNG TIN CÁ NHÂN ================= -->
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4" x-show="showEditProfileModal" style="display: none;">
            <div class="bg-slate-900 border border-slate-800 w-full max-w-lg rounded-3xl p-6 shadow-2xl space-y-4 text-xs text-white" @click.away="showEditProfileModal = false">
                <div class="flex justify-between items-center border-b border-slate-800 pb-3">
                    <h3 class="font-bold text-sm text-white">Cập nhật thông tin cá nhân</h3>
                    <button type="button" @click="showEditProfileModal = false" class="text-slate-400 hover:text-white font-bold text-base">&times;</button>
                </div>
                <form method="POST" action="{{ route('profile.update') }}" class="space-y-3">
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

        <!-- ================= MODAL 2: THÊM ĐỊA CHỈ NHẬN HÀNG ================= -->
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4" 
             x-show="showAddAddressModal" style="display: none;"
             x-data="{
                 cityInput: '',
                 districtInput: '',
                 wardInput: '',
                 showCityDropdown: false,
                 showDistrictDropdown: false,
                 showWardDropdown: false,
                 
                 // Dữ liệu Tỉnh / Huyện / Xã mẫu phổ biến
                 locations: [
                     {
                         city: 'TP. Hồ Chí Minh',
                         districts: [
                             { name: 'Quận Bình Tân', wards: ['Phường An Lạc', 'Phường An Lạc A', 'Phường Bình Trị Đông', 'Phường Bình Trị Đông A', 'Phường Bình Trị Đông B', 'Phường Tân Tạo', 'Phường Tân Tạo A', 'Phường Bình Hưng Hòa'] },
                             { name: 'Quận 1', wards: ['Phường Bến Nghé', 'Phường Bến Thành', 'Phường Cầu Kho', 'Phường Cầu Ông Lãnh', 'Phường Cô Giang', 'Phường Đa Kao', 'Phường Tân Định'] },
                             { name: 'Quận 5', wards: ['Phường 1', 'Phường 2', 'Phường 3', 'Phường 4', 'Phường 5', 'Phường 6', 'Phường 7', 'Phường 8'] },
                             { name: 'Quận 10', wards: ['Phường 1', 'Phường 2', 'Phường 4', 'Phường 9', 'Phường 10', 'Phường 12', 'Phường 14'] },
                             { name: 'Quận Tân Bình', wards: ['Phường 1', 'Phường 2', 'Phường 3', 'Phường 4', 'Phường 12', 'Phường 13', 'Phường 15'] },
                             { name: 'Quận Gò Vấp', wards: ['Phường 1', 'Phường 3', 'Phường 5', 'Phường 8', 'Phường 10', 'Phường 11', 'Phường 17'] },
                             { name: 'TP. Thủ Đức', wards: ['Phường Hiệp Bình Chánh', 'Phường Hiệp Bình Phước', 'Phường Linh Đông', 'Phường Linh Tây', 'Phường Thảo Điền', 'Phường An Phú'] }
                         ]
                     },
                     {
                         city: 'Hà Nội',
                         districts: [
                             { name: 'Quận Cầu Giấy', wards: ['Phường Dịch Vọng', 'Phường Dịch Vọng Hậu', 'Phường Mai Dịch', 'Phường Nghĩa Đô', 'Phường Quan Hoa'] },
                             { name: 'Quận Hoàn Kiếm', wards: ['Phường Hàng Bạc', 'Phường Hàng Gai', 'Phường Tràng Tiền', 'Phường Lý Thái Tổ'] },
                             { name: 'Quận Đống Đa', wards: ['Phường Cát Linh', 'Phường Láng Hạ', 'Phường Ô Chợ Dừa', 'Phường Quang Trung'] },
                             { name: 'Quận Ba Đình', wards: ['Phường Cống Vị', 'Phường Điện Biên', 'Phường Đội Cấn', 'Phường Kim Mã'] },
                             { name: 'Quận Nam Từ Liêm', wards: ['Phường Mỹ Đình 1', 'Phường Mỹ Đình 2', 'Phường Cầu Diễn', 'Phường Mễ Trì'] }
                         ]
                     },
                     {
                         city: 'Đà Nẵng',
                         districts: [
                             { name: 'Quận Hải Châu', wards: ['Phường Hải Châu 1', 'Phường Hải Châu 2', 'Phường Thạch Thang', 'Phường Thanh Bình'] },
                             { name: 'Quận Thanh Khê', wards: ['Phường An Khê', 'Phường Chính Gián', 'Phường Tam Thuận', 'Phường Vĩnh Trung'] },
                             { name: 'Quận Sơn Trà', wards: ['Phường An Hải Bắc', 'Phường Phước Mỹ', 'Phường Thọ Quang'] }
                         ]
                     },
                     {
                         city: 'Cần Thơ',
                         districts: [
                             { name: 'Quận Ninh Kiều', wards: ['Phường An Cư', 'Phường An Hòa', 'Phường An Khánh', 'Phường Cái Khế', 'Phường Tân An'] },
                             { name: 'Quận Bình Thủy', wards: ['Phường An Thới', 'Phường Bình Thủy', 'Phường Trà An'] }
                         ]
                     },
                     {
                         city: 'Hải Phòng',
                         districts: [
                             { name: 'Quận Hồng Bàng', wards: ['Phường Hoàng Văn Thụ', 'Phường Minh Khai', 'Phường Phan Bội Châu'] },
                             { name: 'Quận Lê Chân', wards: ['Phường An Biên', 'Phường Cát Dài', 'Phường Dư Hàng'] }
                         ]
                     },
                     {
                         city: 'Bình Dương',
                         districts: [
                             { name: 'TP. Thủ Dầu Một', wards: ['Phường Phú Cường', 'Phường Hiệp Thành', 'Phường Chánh Nghĩa'] },
                             { name: 'TP. Thuận An', wards: ['Phường Lái Thiêu', 'Phường An Phú', 'Phường Bình Hòa'] }
                         ]
                     },
                     {
                         city: 'Đồng Nai',
                         districts: [
                             { name: 'TP. Biên Hòa', wards: ['Phường Quyết Thắng', 'Phường Thống Nhất', 'Phường Trảng Dài', 'Phường Tân Phong'] }
                         ]
                     }
                 ],

                 get filteredCities() {
                     if (!this.cityInput) return this.locations.map(l => l.city);
                     return this.locations.map(l => l.city).filter(c => c.toLowerCase().includes(this.cityInput.toLowerCase()));
                 },

                 get currentDistricts() {
                     let foundCity = this.locations.find(l => l.city.toLowerCase() === this.cityInput.toLowerCase());
                     return foundCity ? foundCity.districts : [];
                 },

                 get filteredDistricts() {
                     let list = this.currentDistricts.map(d => d.name);
                     if (!this.districtInput) return list;
                     return list.filter(d => d.toLowerCase().includes(this.districtInput.toLowerCase()));
                 },

                 get currentWards() {
                     let foundDistrict = this.currentDistricts.find(d => d.name.toLowerCase() === this.districtInput.toLowerCase());
                     return foundDistrict ? foundDistrict.wards : [];
                 },

                 get filteredWards() {
                     if (!this.wardInput) return this.currentWards;
                     return this.currentWards.filter(w => w.toLowerCase().includes(this.wardInput.toLowerCase()));
                 },

                 selectCity(city) {
                     this.cityInput = city;
                     this.districtInput = '';
                     this.wardInput = '';
                     this.showCityDropdown = false;
                 },

                 selectDistrict(district) {
                     this.districtInput = district;
                     this.wardInput = '';
                     this.showDistrictDropdown = false;
                 },

                 selectWard(ward) {
                     this.wardInput = ward;
                     this.showWardDropdown = false;
                 }
             }">
            <div class="bg-slate-900 border border-slate-800 w-full max-w-lg rounded-3xl p-6 shadow-2xl space-y-4 text-xs text-white" @click.away="showAddAddressModal = false">
                <div class="flex justify-between items-center border-b border-slate-800 pb-3">
                    <h3 class="font-bold text-sm text-white">Thêm địa chỉ nhận hàng</h3>
                    <button type="button" @click="showAddAddressModal = false" class="text-slate-400 hover:text-white font-bold text-base cursor-pointer">&times;</button>
                </div>

                <form method="POST" action="{{ route('user.addresses.store') }}" class="space-y-3.5">
                    @csrf
                    
                    <!-- 1. Tỉnh / Thành phố -->
                    <div class="relative" @click.away="showCityDropdown = false">
                        <label class="block text-slate-400 font-semibold mb-1">Tỉnh / Thành phố</label>
                        <input type="text" name="city" x-model="cityInput" 
                               @focus="showCityDropdown = true" 
                               @input="showCityDropdown = true" 
                               placeholder="Gõ để tìm (ví dụ: Hà Nội, TP. Hồ Chí Minh...)" required 
                               autocomplete="off"
                               class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-white focus:ring-rose-500 focus:border-rose-500">
                        
                        <!-- Dropdown gợi ý Tỉnh/Thành -->
                        <div x-show="showCityDropdown && filteredCities.length > 0" 
                             class="absolute left-0 right-0 top-full mt-1.5 bg-slate-950 border border-slate-800 rounded-xl shadow-2xl z-50 max-h-48 overflow-y-auto divide-y divide-slate-800/60 scrollbar-none"
                             style="display: none;">
                            <template x-for="city in filteredCities" :key="city">
                                <div @click="selectCity(city)" 
                                     class="px-3.5 py-2 hover:bg-rose-600/20 hover:text-rose-400 cursor-pointer transition text-xs flex items-center justify-between">
                                    <span x-text="city"></span>
                                    <span class="text-[10px] text-slate-500">Tỉnh/Thành</span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- 2. Quận / Huyện -->
                    <div class="relative" @click.away="showDistrictDropdown = false">
                        <label class="block text-slate-400 font-semibold mb-1">Quận / Huyện</label>
                        <input type="text" name="district" x-model="districtInput" 
                               @focus="showDistrictDropdown = true" 
                               @input="showDistrictDropdown = true" 
                               :disabled="!cityInput"
                               placeholder="Gõ để tìm (ví dụ: Quận Bình Tân, Cầu Giấy...)" required 
                               autocomplete="off"
                               :class="!cityInput ? 'opacity-50 cursor-not-allowed' : ''"
                               class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-white focus:ring-rose-500 focus:border-rose-500">
                        
                        <!-- Dropdown gợi ý Quận/Huyện -->
                        <div x-show="showDistrictDropdown && filteredDistricts.length > 0" 
                             class="absolute left-0 right-0 top-full mt-1.5 bg-slate-950 border border-slate-800 rounded-xl shadow-2xl z-50 max-h-48 overflow-y-auto divide-y divide-slate-800/60 scrollbar-none"
                             style="display: none;">
                            <template x-for="district in filteredDistricts" :key="district">
                                <div @click="selectDistrict(district)" 
                                     class="px-3.5 py-2 hover:bg-rose-600/20 hover:text-rose-400 cursor-pointer transition text-xs flex items-center justify-between">
                                    <span x-text="district"></span>
                                    <span class="text-[10px] text-slate-500">Quận/Huyện</span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- 3. Phường / Xã -->
                    <div class="relative" @click.away="showWardDropdown = false">
                        <label class="block text-slate-400 font-semibold mb-1">Phường / Xã</label>
                        <input type="text" name="ward" x-model="wardInput" 
                               @focus="showWardDropdown = true" 
                               @input="showWardDropdown = true" 
                               :disabled="!districtInput"
                               placeholder="Gõ để tìm (ví dụ: Phường An Lạc...)" required 
                               autocomplete="off"
                               :class="!districtInput ? 'opacity-50 cursor-not-allowed' : ''"
                               class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-white focus:ring-rose-500 focus:border-rose-500">
                        
                        <!-- Dropdown gợi ý Phường/Xã -->
                        <div x-show="showWardDropdown && filteredWards.length > 0" 
                             class="absolute left-0 right-0 top-full mt-1.5 bg-slate-950 border border-slate-800 rounded-xl shadow-2xl z-50 max-h-48 overflow-y-auto divide-y divide-slate-800/60 scrollbar-none"
                             style="display: none;">
                            <template x-for="ward in filteredWards" :key="ward">
                                <div @click="selectWard(ward)" 
                                     class="px-3.5 py-2 hover:bg-rose-600/20 hover:text-rose-400 cursor-pointer transition text-xs flex items-center justify-between">
                                    <span x-text="ward"></span>
                                    <span class="text-[10px] text-slate-500">Phường/Xã</span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- 4. Địa chỉ chi tiết -->
                    <div>
                        <label class="block text-slate-400 font-semibold mb-1">Địa chỉ chi tiết (Số nhà, tên đường)</label>
                        <input type="text" name="address_detail" placeholder="Ví dụ: 102/1c Lê Tấn Bế" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-white focus:ring-rose-500 focus:border-rose-500">
                    </div>

                    <!-- 5. Nhãn địa chỉ -->
                    <div>
                        <label class="block text-slate-400 font-semibold mb-1">Nhãn địa chỉ</label>
                        <input type="text" name="label" value="Nhà" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-white">
                    </div>

                    <!-- Đặt làm mặc định -->
                    <div class="flex items-center gap-2 pt-1">
                        <input type="checkbox" name="is_default" value="1" id="is_default" class="rounded text-rose-600 bg-slate-950 border-slate-800">
                        <label for="is_default" class="text-slate-300 font-medium cursor-pointer">Đặt làm địa chỉ mặc định</label>
                    </div>

                    <!-- Buttons -->
                    <div class="pt-3 flex gap-3">
                        <button type="button" @click="showAddAddressModal = false" class="flex-1 py-3 border border-slate-800 font-bold rounded-xl text-slate-400 hover:bg-slate-800 cursor-pointer">Hủy</button>
                        <button type="submit" class="flex-1 py-3 bg-rose-600 hover:bg-rose-500 text-white font-bold rounded-xl shadow-md cursor-pointer">Thêm địa chỉ</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ================= MODAL 3: ĐỔI MẬT KHẨU ================= -->
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4" x-show="showChangePasswordModal" style="display: none;">
            <div class="bg-slate-900 border border-slate-800 w-full max-w-lg rounded-3xl p-6 shadow-2xl space-y-4 text-xs text-white" @click.away="showChangePasswordModal = false">
                <div class="flex justify-between items-center border-b border-slate-800 pb-3">
                    <h3 class="font-bold text-sm text-white">Đổi mật khẩu bảo mật</h3>
                    <button type="button" @click="showChangePasswordModal = false" class="text-slate-400 hover:text-white font-bold text-base">&times;</button>
                </div>
                <form method="POST" action="{{ route('password.update') }}" class="space-y-3">
                    @csrf
                    @method('put')
                    <div>
                        <label class="block text-slate-400 font-semibold mb-1">Mật khẩu hiện tại</label>
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