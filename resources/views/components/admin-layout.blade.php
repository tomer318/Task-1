<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TechZone') }} - Quản trị</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Instrument Sans', sans-serif; }</style>
</head>
<body class="bg-slate-950 text-slate-100 antialiased min-h-screen flex selection:bg-rose-500 selection:text-white">

    <!-- Sidebar Cố Định Bên Trái -->
    <aside class="w-64 bg-slate-900 border-r border-slate-800 flex flex-col justify-between shrink-0 min-h-screen">
        <div>
            <!-- Brand / Logo -->
            <div class="h-16 flex items-center gap-3 px-6 border-b border-slate-800">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-rose-600 to-orange-500 flex items-center justify-center shadow-lg shadow-rose-600/20 font-bold text-white text-sm">
                    T
                </div>
                <div>
                    <div class="font-extrabold text-sm tracking-wider text-white">TECHZONE</div>
                    <div class="text-[10px] text-rose-400 font-mono">ADMIN PANEL</div>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="p-4 space-y-1.5 text-xs font-semibold">
                
                <!-- Trang chủ website -->
                <a href="/" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/60 transition">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Xem Cửa Hàng
                </a>

                <div class="pt-3 pb-1 px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Hệ Thống</div>

                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-rose-600 to-red-500 text-white shadow-md shadow-rose-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    Bảng Điều Khiển
                </a>

                <!-- Sản phẩm -->
                <a href="{{ route('admin.products.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.products.*') ? 'bg-gradient-to-r from-rose-600 to-red-500 text-white shadow-md shadow-rose-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Quản Lý Sản Phẩm
                </a>

                <!-- Danh mục -->
                <a href="{{ route('admin.categories.index') }}" 
                class="flex items-center justify-between px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.categories.*') ? 'bg-gradient-to-r from-rose-600 to-red-500 text-white shadow-md shadow-rose-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        Danh Mục
                    </div>
                </a>

                <!-- Thương hiệu -->
                <a href="{{ route('admin.brands.index') }}" 
                class="flex items-center justify-between px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.brands.*') ? 'bg-gradient-to-r from-rose-600 to-red-500 text-white shadow-md shadow-rose-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        Thương Hiệu
                    </div>
                </a>

                <!-- Đơn hàng -->
                <a href="{{ route('admin.orders.index') }}" 
                class="flex items-center justify-between px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.orders.*') ? 'bg-gradient-to-r from-rose-600 to-red-500 text-white font-bold shadow-lg shadow-rose-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        Đơn Hàng
                    </div>
                    @php
                        $pendingBadgeCount = \App\Models\Order::where('status', 'Chờ xử lý')->count();
                    @endphp
                    @if($pendingBadgeCount > 0)
                        <span class="text-[9px] px-1.5 py-0.5 rounded {{ request()->routeIs('admin.orders.*') ? 'bg-white/20 text-white' : 'bg-rose-950/60 text-rose-400 border border-rose-800/40' }}">
                            Mới ({{ $pendingBadgeCount }})
                        </span>
                    @endif
                </a>

                <!-- Ưu đãi / Mã giảm giá -->
                <a href="{{ route('admin.coupons.index') }}" 
                class="flex items-center justify-between px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.coupons.*') ? 'bg-gradient-to-r from-rose-600 to-red-500 text-white font-bold shadow-lg shadow-rose-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        Mã Giảm Giá
                    </div>
                </a>

                <div class="pt-3 pb-1 px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Phân Quyền & User</div>

                <!-- Người dùng & Phân quyền -->
                <a href="{{ route('admin.users.index') }}" 
                   class="flex items-center justify-between px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.users.*') ? 'bg-gradient-to-r from-rose-600 to-red-500 text-white font-bold shadow-lg shadow-rose-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        Người Dùng & Role
                    </div>
                </a>
            </nav>
        </div>

        <!-- User Profile Footer -->
        <div class="p-4 border-t border-slate-800 bg-slate-950/50">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full bg-rose-600/20 border border-rose-500/30 flex items-center justify-center font-bold text-rose-400 text-xs">
                        A
                    </div>
                    <div class="truncate">
                        <div class="text-xs font-semibold text-white truncate">{{ Auth::user()->name }}</div>
                        <div class="text-[10px] text-slate-400 truncate font-mono">Role: Admin</div>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Đăng xuất" class="p-1.5 text-slate-400 hover:text-rose-400 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <!-- Top Nav -->
        <header class="h-16 border-b border-slate-800 bg-slate-900/50 backdrop-blur px-8 flex items-center justify-between">
            <div class="text-xs text-slate-400 font-medium">
                Khu vực Quản trị &gt; <span class="text-slate-200">{{ $header ?? 'Bảng Điều Khiển' }}</span>
            </div>
        </header>

        <!-- Page Body -->
        <main class="p-8">
            {{ $slot }}
        </main>
    </div>

</body>
</html>