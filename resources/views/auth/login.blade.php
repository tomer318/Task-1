<x-guest-layout>
    <!-- Header Form -->
    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold text-white tracking-tight">Đăng nhập tài khoản</h2>
        <p class="text-xs text-slate-400 mt-1">Truy cập bảng điều khiển quản trị hoặc cửa hàng</p>
    </div>

    <!-- Quick Credentials Hint -->
    <div class="mb-6 p-3.5 bg-slate-950/70 border border-slate-800 rounded-xl text-xs space-y-1.5">
        <div class="text-slate-400 flex items-center justify-between">
            <span>🔑 Admin: <strong class="text-rose-400 font-mono">admin@gmail.com</strong></span>
            <span class="font-mono text-slate-500">12345678</span>
        </div>
        <div class="text-slate-400 flex items-center justify-between">
            <span>👤 User: <strong class="text-blue-400 font-mono">user@gmail.com</strong></span>
            <span class="font-mono text-slate-500">12345678</span>
        </div>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Địa chỉ Email</label>
            <input id="email" 
                   type="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required 
                   autofocus 
                   autocomplete="username" 
                   placeholder="name@example.com"
                   class="w-full bg-slate-950/90 border border-slate-800 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 rounded-xl text-sm text-white placeholder-slate-600 px-4 py-2.5 transition outline-none" />
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-rose-400" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider">Mật khẩu</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs text-slate-400 hover:text-rose-400 transition">
                        Quên mật khẩu?
                    </a>
                @endif
            </div>
            <input id="password" 
                   type="password" 
                   name="password" 
                   required 
                   autocomplete="current-password" 
                   placeholder="••••••••"
                   class="w-full bg-slate-950/90 border border-slate-800 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 rounded-xl text-sm text-white placeholder-slate-600 px-4 py-2.5 transition outline-none" />
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-rose-400" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" 
                       type="checkbox" 
                       name="remember" 
                       class="rounded border-slate-800 bg-slate-950 text-rose-600 focus:ring-rose-500 focus:ring-offset-slate-900 cursor-pointer">
                <span class="ms-2 text-xs text-slate-400">Ghi nhớ đăng nhập</span>
            </label>
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit" 
                    class="w-full py-2.5 px-4 bg-gradient-to-r from-rose-600 to-red-500 hover:from-rose-500 hover:to-red-400 text-white text-sm font-semibold rounded-xl shadow-lg shadow-rose-600/20 transition-all duration-150 active:scale-[0.98]">
                Đăng nhập ngay
            </button>
        </div>

        <!-- Register Link -->
        @if (Route::has('register'))
            <div class="text-center pt-2">
                <span class="text-xs text-slate-400">Chưa có tài khoản?</span>
                <a href="{{ route('register') }}" class="text-xs text-rose-400 hover:text-rose-300 font-medium ml-1 underline underline-offset-2 transition">
                    Đăng ký ngay
                </a>
            </div>
        @endif

        <!-- Back to Home -->
        <div class="text-center pt-1 border-t border-slate-800/80 mt-4">
            <a href="/" class="text-xs text-slate-500 hover:text-slate-300 transition">
                ← Quay lại trang chủ
            </a>
        </div>
    </form>
</x-guest-layout>