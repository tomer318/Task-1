<x-guest-layout>
    <!-- Header Form -->
    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold text-white tracking-tight">Tạo tài khoản mới</h2>
        <p class="text-xs text-slate-400 mt-1">Đăng ký tài khoản để trải nghiệm hệ thống</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Họ và tên</label>
            <input id="name" 
                   type="text" 
                   name="name" 
                   value="{{ old('name') }}" 
                   required 
                   autofocus 
                   autocomplete="name" 
                   placeholder="Nguyễn Văn A"
                   class="w-full bg-slate-950/80 border border-slate-800 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 rounded-xl text-sm text-white placeholder-slate-600 px-4 py-2.5 transition outline-none" />
            <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs text-rose-400" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Địa chỉ Email</label>
            <input id="email" 
                   type="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required 
                   autocomplete="username" 
                   placeholder="name@example.com"
                   class="w-full bg-slate-950/80 border border-slate-800 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 rounded-xl text-sm text-white placeholder-slate-600 px-4 py-2.5 transition outline-none" />
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-rose-400" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Mật khẩu</label>
            <input id="password" 
                   type="password" 
                   name="password" 
                   required 
                   autocomplete="new-password" 
                   placeholder="••••••••"
                   class="w-full bg-slate-950/80 border border-slate-800 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 rounded-xl text-sm text-white placeholder-slate-600 px-4 py-2.5 transition outline-none" />
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-rose-400" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Xác nhận mật khẩu</label>
            <input id="password_confirmation" 
                   type="password" 
                   name="password_confirmation" 
                   required 
                   autocomplete="new-password" 
                   placeholder="••••••••"
                   class="w-full bg-slate-950/80 border border-slate-800 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 rounded-xl text-sm text-white placeholder-slate-600 px-4 py-2.5 transition outline-none" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs text-rose-400" />
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit" 
                    class="w-full py-2.5 px-4 bg-gradient-to-r from-rose-600 to-red-500 hover:from-rose-500 hover:to-red-400 text-white text-sm font-semibold rounded-xl shadow-lg shadow-rose-600/20 transition-all duration-150 active:scale-[0.98]">
                Đăng ký tài khoản
            </button>
        </div>

        <!-- Already registered link -->
        <div class="text-center pt-2">
            <a href="{{ route('login') }}" class="text-xs text-slate-400 hover:text-rose-400 transition">
                Đã có tài khoản? <span class="underline">Đăng nhập</span>
            </a>
        </div>
    </form>
</x-guest-layout>