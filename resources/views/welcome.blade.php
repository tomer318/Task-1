<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>TECHZONE - Siêu Thị Công Nghệ & Điện Máy</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>body { font-family: 'Instrument Sans', sans-serif; }</style>
    </head>
    <body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col selection:bg-rose-500 selection:text-white">
        
        <!-- Top Promo Bar -->
        <div class="bg-gradient-to-r from-rose-700 via-rose-600 to-orange-600 text-white text-xs font-semibold py-1.5 px-4 text-center">
            🔥 SIÊU HỘI CÔNG NGHỆ: Giảm tới 50% cho iPhone, Laptop AI, Tivi 4K & Gia dụng thông minh!
        </div>

        <!-- Main Header -->
        <header class="sticky top-0 z-50 bg-slate-950/90 backdrop-blur-md border-b border-slate-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
                
                <!-- Logo -->
                <a href="/" class="flex items-center gap-3 shrink-0">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-rose-600 to-orange-500 flex items-center justify-center shadow-lg shadow-rose-600/30 font-extrabold text-white text-lg">
                        T
                    </div>
                    <div>
                        <div class="font-extrabold text-lg tracking-wider text-white flex items-center gap-1">
                            TECH<span class="text-rose-500">ZONE</span>
                        </div>
                        <div class="text-[10px] text-slate-400 uppercase tracking-widest">Điện Máy & Công Nghệ</div>
                    </div>
                </a>

                <!-- Search Bar -->
                <div class="flex-1 max-w-xl hidden md:block">
                    <div class="relative">
                        <input type="text" placeholder="Bạn cần tìm iPhone 16, Laptop Gaming, Smart TV, Tủ lạnh...?" 
                               class="w-full bg-slate-900 border border-slate-800 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 rounded-xl text-xs text-white placeholder-slate-500 px-4 py-2.5 pl-10 transition outline-none">
                        <svg class="w-4 h-4 text-slate-500 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- User Actions -->
                <nav class="flex items-center gap-3 text-xs font-semibold">
                    @auth
                        @role('Admin')
                            <a href="{{ route('admin.products.index') }}" class="px-4 py-2 bg-gradient-to-r from-rose-600 to-red-500 hover:from-rose-500 hover:to-red-400 text-white rounded-xl shadow-md transition-all">
                                ⚙️ Quản trị Admin
                            </a>
                        @else
                            <a href="{{ url('/dashboard') }}" class="px-3.5 py-2 border border-slate-800 bg-slate-900 hover:bg-slate-800 text-slate-200 rounded-xl transition">
                                👤 {{ Auth::user()->name }}
                            </a>
                        @endrole

                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="p-2 text-slate-400 hover:text-rose-400 transition" title="Đăng xuất">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-slate-300 hover:text-white transition">
                            Đăng nhập
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-4 py-2 bg-gradient-to-r from-rose-600 to-red-500 hover:from-rose-500 hover:to-red-400 text-white rounded-xl shadow-md transition-all">
                                Đăng ký
                            </a>
                        @endif
                    @endauth
                </nav>
            </div>
        </header>

        <!-- Main Body Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full space-y-10">
            
            <!-- Hero Banner Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Main Promo Banner (8 cols) -->
                <div class="lg:col-span-8 bg-gradient-to-br from-slate-900 via-slate-900 to-rose-950/40 border border-slate-800 rounded-3xl p-8 sm:p-12 flex flex-col justify-between relative overflow-hidden shadow-2xl">
                    <div class="max-w-md z-10">
                        <span class="inline-block px-3 py-1 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-full text-xs font-bold uppercase tracking-wider mb-3">
                            ⚡ Flash Sale Tuần Lễ Công Nghệ
                        </span>
                        <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight leading-tight mb-3">
                            Khám Phá Thiết Bị Điện Tử & Gia Dụng Thế Hệ Mới
                        </h1>
                        <p class="text-slate-400 text-sm leading-relaxed mb-6">
                            Điện thoại Flagship, Laptop Gaming, TV OLED 4K và thiết bị điện gia dụng với hơn 1000+ sản phẩm chính hãng sẵn kho.
                        </p>
                        <div class="flex items-center gap-3">
                            <a href="#products-list" class="px-6 py-3 bg-gradient-to-r from-rose-600 to-red-500 hover:from-rose-500 hover:to-red-400 text-white text-xs font-bold uppercase tracking-wider rounded-xl shadow-lg shadow-rose-600/30 transition-all active:scale-95">
                                Mua Sắm Ngay ↓
                            </a>
                            @guest
                                <a href="{{ route('login') }}" class="px-5 py-3 border border-slate-700 hover:border-slate-500 text-slate-300 text-xs font-semibold rounded-xl transition">
                                    Đăng nhập Demo
                                </a>
                            @endguest
                        </div>
                    </div>
                </div>

                <!-- Admin & Credentials Widget (4 cols) -->
                <div class="lg:col-span-4 bg-slate-900/90 border border-slate-800 rounded-3xl p-6 flex flex-col justify-between shadow-xl">
                    <div>
                        <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                            <span class="font-bold text-sm text-white flex items-center gap-2">
                                🔐 Cổng Truy Cập Demo
                            </span>
                            <span class="text-[10px] bg-rose-500/10 text-rose-400 border border-rose-500/20 px-2 py-0.5 rounded-full font-mono">Spatie RBAC</span>
                        </div>
                        <div class="space-y-3 mt-4 text-xs">
                            <div class="p-3 bg-slate-950/80 border border-slate-800 rounded-xl">
                                <div class="text-rose-400 font-semibold mb-1">🔑 Tài khoản Quản Trị Viên:</div>
                                <div class="font-mono text-slate-300">admin@gmail.com | 12345678</div>
                            </div>
                            <div class="p-3 bg-slate-950/80 border border-slate-800 rounded-xl">
                                <div class="text-blue-400 font-semibold mb-1">👤 Tài khoản Khách hàng:</div>
                                <div class="font-mono text-slate-300">user@gmail.com | 12345678</div>
                            </div>
                        </div>
                    </div>
                    @auth
                        @role('Admin')
                            <a href="{{ route('admin.products.index') }}" class="w-full mt-4 py-2.5 bg-rose-600 hover:bg-rose-500 text-white text-center rounded-xl text-xs font-bold transition">
                                Vào Trang Quản Trị CRUD Sản Phẩm →
                            </a>
                        @endrole
                    @else
                        <a href="{{ route('login') }}" class="w-full mt-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 text-center rounded-xl text-xs font-semibold transition">
                            Đăng nhập để vào CRUD Admin →
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Categories Horizontal List -->
            <div class="space-y-3">
                <h2 class="text-lg font-bold text-white flex items-center gap-2">
                    📦 Danh Mục Nổi Bật
                </h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                    @foreach($categories as $cat)
                        <div class="p-4 bg-slate-900/60 hover:bg-slate-800 border border-slate-800 rounded-2xl transition cursor-pointer text-center group">
                            <div class="text-xs font-semibold text-slate-200 group-hover:text-rose-400 transition">{{ $cat->name }}</div>
                            <div class="text-[11px] text-slate-500 mt-1">{{ $cat->products_count }} sản phẩm</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Products Grid -->
            <div id="products-list" class="space-y-4 pt-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-white">🔥 Sản Phẩm Công Nghệ Mới Nhất</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Hiển thị các mẫu điện thoại, laptop, tivi và gia dụng hot nhất</p>
                    </div>
                    @auth
                        @role('Admin')
                            <a href="{{ route('admin.products.create') }}" class="text-xs font-semibold text-rose-400 hover:underline">+ Thêm sản phẩm mới</a>
                        @endrole
                    @endauth
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($featuredProducts as $item)
                        <div class="bg-slate-900 border border-slate-800 hover:border-slate-700 rounded-2xl p-5 flex flex-col justify-between transition-all duration-200 hover:-translate-y-1 shadow-lg group">
                            <div>
                                <div class="w-full aspect-video bg-slate-950 rounded-xl flex items-center justify-center mb-4 border border-slate-800/80 group-hover:border-rose-500/30 transition">
                                    <svg class="w-10 h-10 text-slate-700 group-hover:text-rose-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <span class="inline-block px-2 py-0.5 bg-slate-800 text-[10px] font-medium text-slate-400 rounded-md mb-2">
                                    {{ $item->category->name ?? 'Công nghệ' }}
                                </span>
                                <h3 class="font-bold text-sm text-white line-clamp-2 mb-1 group-hover:text-rose-400 transition">
                                    {{ $item->name }}
                                </h3>
                                <p class="text-[11px] text-slate-400 line-clamp-2 mb-3">
                                    {{ $item->description }}
                                </p>
                            </div>

                            <div class="pt-3 border-t border-slate-800 flex items-center justify-between">
                                <div>
                                    <div class="text-[10px] text-slate-500">Giá bán</div>
                                    <div class="text-base font-extrabold text-rose-500 font-mono">
                                        ${{ number_format($item->price, 2) }}
                                    </div>
                                </div>
                                <span class="text-[11px] font-medium text-emerald-400 bg-emerald-950/40 border border-emerald-800/30 px-2 py-0.5 rounded-lg">
                                    Còn {{ $item->stock }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </main>

        <!-- Footer -->
        <footer class="bg-slate-950 border-t border-slate-900 py-8 text-center text-xs text-slate-500">
            <div class="max-w-7xl mx-auto px-4">
                TECHZONE E-Commerce System © 2026 • Laravel v{{ app()->version() }} • Docker Sail
            </div>
        </footer>

    </body>
</html>