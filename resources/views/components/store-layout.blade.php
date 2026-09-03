<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'TECHZONE' }} - Siêu Thị Điện Máy & Công Nghệ</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Be Vietnam Pro', sans-serif; }</style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col selection:bg-rose-500 selection:text-white"
      x-data="{ openCart: false }">

    <!-- Top Promo Bar -->
    <div class="bg-gradient-to-r from-rose-700 via-rose-600 to-orange-600 text-white text-xs font-semibold py-1.5 px-4 text-center">
        ⚡ TECHZONE DEAL: Miễn phí vận chuyển toàn quốc cho đơn hàng công nghệ từ 300.000₫!
    </div>

    <!-- Header / Navbar -->
    <header class="sticky top-0 z-50 bg-slate-950/90 backdrop-blur-md border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
            
            <!-- Logo -->
            <a href="/" class="flex items-center gap-3 shrink-0">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-rose-600 to-orange-500 flex items-center justify-center shadow-lg shadow-rose-600/30 font-extrabold text-white text-lg">
                    T
                </div>
                <div>
                    <div class="font-extrabold text-lg tracking-wider text-white">TECH<span class="text-rose-500">ZONE</span></div>
                    <div class="text-[10px] text-slate-400 uppercase tracking-widest">Storefront 2026</div>
                </div>
            </a>

            <!-- Form Tìm Kiếm Sản Phẩm Realtime Gợi Ý -->
            <div class="flex-1 max-w-lg mx-4 hidden sm:block relative"
                 x-data="{
                     query: '{{ request('keyword') }}',
                     results: [],
                     loading: false,
                     isOpen: false,
                     fetchResults() {
                         if (this.query.trim().length < 2) {
                             this.results = [];
                             this.isOpen = false;
                             return;
                         }
                         this.loading = true;
                         fetch(`/api/search-suggestions?q=${encodeURIComponent(this.query)}`)
                             .then(res => res.json())
                             .then(data => {
                                 this.results = data;
                                 this.isOpen = true;
                             })
                             .catch(() => { this.results = []; })
                             .finally(() => { this.loading = false; });
                     }
                 }"
                 @click.away="isOpen = false">
                
                <form action="/" method="GET">
                    <div class="relative flex items-center">
                        <input type="text" 
                               name="keyword" 
                               x-model="query"
                               @input.debounce.300ms="fetchResults()"
                               @focus="if(query.trim().length >= 2) fetchResults()"
                               placeholder="Tìm kiếm laptop gaming, iPhone, tai nghe..." 
                               autocomplete="off"
                               class="w-full bg-slate-900 border border-slate-800 rounded-2xl py-2 pl-10 pr-8 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500 transition shadow-inner">
                        
                        <div class="absolute left-3.5 flex items-center pointer-events-none text-slate-500">
                            <template x-if="!loading">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </template>
                            <template x-if="loading">
                                <svg class="w-4 h-4 animate-spin text-rose-500" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                            </template>
                        </div>

                        <button type="button" 
                                x-show="query.length > 0" 
                                @click="query = ''; results = []; isOpen = false" 
                                class="absolute right-3 text-slate-500 hover:text-slate-300 text-xs font-bold">
                            ✕
                        </button>
                    </div>
                </form>

                <!-- Dropdown danh sách gợi ý realtime -->
                <div x-show="isOpen && (results.length > 0 || (query.trim().length >= 2 && !loading))"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-1"
                     class="absolute top-full left-0 right-0 mt-2 bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl overflow-hidden z-50 divide-y divide-slate-800/60"
                     style="display: none;">
                    
                    <template x-if="results.length > 0">
                        <div>
                            <div class="px-3.5 py-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider bg-slate-950/60 flex justify-between items-center">
                                <span>Gợi ý sản phẩm phù hợp</span>
                                <span class="font-mono text-rose-400" x-text="results.length + ' kết quả'"></span>
                            </div>
                            <div class="max-h-80 overflow-y-auto custom-scrollbar pr-1">
                                <template x-for="item in results" :key="item.id">
                                    <a :href="item.url" class="flex items-center gap-3 p-3 hover:bg-slate-800/70 transition group">
                                        <div class="w-11 h-11 rounded-xl bg-slate-950 border border-slate-800/80 flex items-center justify-center shrink-0 overflow-hidden">
                                            <template x-if="item.image">
                                                <img :src="item.image" class="w-full h-full object-contain p-1 group-hover:scale-105 transition" :alt="item.name">
                                            </template>
                                            <template x-if="!item.image">
                                                <span class="text-sm">⚡</span>
                                            </template>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-1.5 text-[10px] text-slate-400">
                                                <span class="text-rose-400 font-bold uppercase" x-text="item.brand"></span>
                                                <span>•</span>
                                                <span x-text="item.category"></span>
                                            </div>
                                            <div class="text-xs font-semibold text-slate-200 group-hover:text-rose-400 truncate transition" x-text="item.name"></div>
                                        </div>
                                        <div class="text-xs font-bold text-rose-500 font-mono shrink-0" x-text="item.price"></div>
                                    </a>
                                </template>
                            </div>
                            <div class="p-2.5 bg-slate-950 text-center border-t border-slate-800">
                                <a :href="'/?keyword=' + encodeURIComponent(query)" class="text-[11px] text-rose-400 hover:text-rose-300 font-semibold inline-flex items-center gap-1">
                                    Xem tất cả kết quả cho "<span x-text="query"></span>" →
                                </a>
                            </div>
                        </div>
                    </template>

                    <template x-if="results.length === 0 && !loading && query.trim().length >= 2">
                        <div class="p-4 text-center text-xs text-slate-400">
                            Không tìm thấy sản phẩm nào khớp với từ khóa "<span class="text-white" x-text="query"></span>"
                        </div>
                    </template>
                </div>
            </div>

            <!-- User & Cart Actions -->
            <div class="flex items-center gap-4 text-xs font-semibold shrink-0">
                
                <!-- Nút Giỏ Hàng Mở Drawer -->
                <a href="{{ route('cart.index') }}" class="relative flex items-center gap-2 px-3.5 py-2 bg-slate-900 border border-slate-800 hover:border-slate-700 rounded-xl transition">
                    <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <span class="text-white font-mono">
                        {{ number_format(array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], session('cart', []))), 0, ',', '.') }}₫
                    </span>
                    <span class="px-1.5 py-0.5 bg-rose-600 text-white text-[10px] font-bold rounded-full">
                        {{ array_sum(array_column(session('cart', []), 'quantity')) }}
                    </span>
                </a>

                @auth
                    @role('Admin')
                        <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-2 bg-gradient-to-r from-rose-600 to-red-500 hover:from-rose-500 hover:to-red-400 text-white rounded-xl shadow-md transition">
                            Admin Panel
                        </a>
                    @else
                        <!-- Avatar dẫn thẳng về Profile -->
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 p-1.5 pr-3 bg-slate-900 border border-slate-800 hover:border-rose-500/50 rounded-xl transition group">
                            <div class="w-7 h-7 rounded-lg bg-gradient-to-tr from-rose-600 to-orange-500 flex items-center justify-center font-bold text-white text-xs shadow">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="text-xs text-slate-300 group-hover:text-rose-400 font-medium transition">{{ Auth::user()->name }}</span>
                        </a>
                    @endrole

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-slate-400 hover:text-rose-400 text-xs transition underline">Đăng xuất</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-slate-300 hover:text-white transition">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-xl transition">Đăng ký</a>
                @endauth
            </div>
        </div>

        <!-- Thanh Tìm Kiếm phụ trên Mobile (màn hình nhỏ) -->
        <div class="block sm:hidden px-4 pb-3">
            <form action="/" method="GET">
                <div class="relative flex items-center">
                    <input type="text" 
                           name="keyword" 
                           value="{{ request('keyword') }}" 
                           placeholder="Tìm kiếm sản phẩm..." 
                           class="w-full bg-slate-900 border border-slate-800 rounded-xl py-2 pl-9 pr-8 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-rose-500">
                    <div class="absolute left-3 flex items-center pointer-events-none text-slate-500">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
            </form>
        </div>
    </header>

    <!-- Main Page Content -->
    <main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{ $slot }}
    </main>

    <footer class="bg-slate-950 border-t border-slate-900 py-8 text-center text-xs text-slate-500">
        TECHZONE E-Commerce Storefront © 2026 • Session Cart & Laravel
    </footer>

    <!-- TechBot AI Widget -->
    <x-techbot-widget />
</body>
</html>