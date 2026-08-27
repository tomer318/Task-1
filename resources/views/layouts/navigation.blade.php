<nav x-data="{ open: false }" class="bg-slate-900 border-b border-slate-800 text-slate-200">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-8">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 group">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-rose-600 to-orange-500 flex items-center justify-center font-black text-white text-base shadow-lg shadow-rose-600/30 group-hover:scale-105 transition">
                            T
                        </div>
                        <div class="flex flex-col">
                            <span class="font-black text-sm tracking-wider text-white">TECHZONE</span>
                            <span class="text-[9px] font-mono text-rose-400 font-bold uppercase tracking-widest -mt-1">Admin Panel</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-2 sm:-my-px sm:flex items-center text-xs font-semibold">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="px-3.5 py-2 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-slate-800 text-rose-400 border border-slate-700' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                        📊 Dashboard
                    </a>

                    <a href="{{ route('admin.products.index') }}" 
                       class="px-3.5 py-2 rounded-xl transition {{ request()->routeIs('admin.products.*') ? 'bg-slate-800 text-rose-400 border border-slate-700' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                        📦 Sản Phẩm
                    </a>

                    <a href="/" target="_blank" 
                       class="px-3.5 py-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/50 transition flex items-center gap-1.5">
                        🌐 Xem Storefront ↗
                    </a>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 px-3 py-1.5 border border-slate-800 text-xs font-semibold rounded-xl text-slate-300 bg-slate-950 hover:border-slate-700 focus:outline-none transition">
                            <div class="w-6 h-6 rounded-lg bg-rose-600/20 text-rose-400 flex items-center justify-center font-bold text-[11px]">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div>{{ Auth::user()->name }}</div>
                            <svg class="fill-current h-3.5 w-3.5 text-slate-500" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden py-1 text-xs">
                            <x-dropdown-link :href="route('profile.edit')" class="text-slate-300 hover:bg-slate-800 hover:text-white">
                                👤 Hồ sơ cá nhân
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="text-rose-400 hover:bg-rose-500/10">
                                    🚪 Đăng xuất
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger Button (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-slate-950 border-b border-slate-800 p-4 space-y-2 text-xs">
        <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="text-slate-300">
            📊 Dashboard
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')" class="text-slate-300">
            📦 Sản Phẩm
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route('profile.edit')" class="text-slate-300">
            👤 Hồ sơ cá nhân
        </x-responsive-nav-link>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-rose-400">
                🚪 Đăng xuất
            </x-responsive-nav-link>
        </form>
    </div>
</nav>