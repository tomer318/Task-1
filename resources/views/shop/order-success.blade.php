<x-store-layout>
    <div class="max-w-3xl mx-auto py-16 px-4 text-white text-center space-y-6">
        <div class="w-20 h-20 bg-emerald-500/10 border-2 border-emerald-500 rounded-full flex items-center justify-center text-4xl mx-auto text-emerald-400 shadow-xl shadow-emerald-500/20">
            ✓
        </div>

        <div class="space-y-2">
            <h1 class="text-2xl font-black uppercase text-white">Đặt hàng thành công!</h1>
            <p class="text-xs text-slate-400">Cảm ơn bạn đã mua sắm tại <strong class="text-rose-500">TECHZONE</strong>. Đơn hàng của bạn đã được ghi nhận và đang chờ xử lý.</p>
        </div>

        @if(session('order_code'))
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 max-w-md mx-auto text-xs space-y-1">
                <span class="text-slate-400 block">Mã đơn hàng của bạn:</span>
                <span class="font-mono font-bold text-rose-400 text-sm">#{{ session('order_code') }}</span>
            </div>
        @endif

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
            <a href="{{ route('home') }}" class="w-full sm:w-auto px-6 py-3.5 bg-slate-900 hover:bg-slate-800 border border-slate-700 text-white font-bold rounded-xl text-xs transition">
                &larr; Tiếp tục mua sắm
            </a>
            <a href="{{ route('profile.orders') }}" class="w-full sm:w-auto px-6 py-3.5 bg-gradient-to-r from-rose-600 to-red-500 hover:from-rose-500 hover:to-red-400 text-white font-bold rounded-xl text-xs shadow-lg shadow-rose-600/30 transition">
                Xem lịch sử đơn hàng &rarr;
            </a>
        </div>
    </div>
</x-store-layout>