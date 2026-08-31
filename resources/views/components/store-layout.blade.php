<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'TECHZONE' }} - Siêu Thị Điện Máy & Công Nghệ</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>body { font-family: 'Instrument Sans', sans-serif; }</style>
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

            <!-- User & Cart Actions -->
            <div class="flex items-center gap-4 text-xs font-semibold">
                
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