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

            @if(session('order_id') || session('order_code'))
                <a href="{{ route('orders.invoice', session('order_id') ?? session('order_code')) }}" 
                   target="_blank"
                   class="w-full sm:w-auto px-6 py-3.5 bg-slate-900 hover:bg-slate-800 border border-rose-500/50 text-rose-400 font-bold rounded-xl text-xs transition flex items-center justify-center gap-2 shadow">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>In / Tải Hóa Đơn PDF</span>
                </a>
            @endif

            <a href="{{ route('profile.orders') }}" class="w-full sm:w-auto px-6 py-3.5 bg-gradient-to-r from-rose-600 to-red-500 hover:from-rose-500 hover:to-red-400 text-white font-bold rounded-xl text-xs shadow-lg shadow-rose-600/30 transition">
                Xem lịch sử đơn hàng &rarr;
            </a>
        </div>
    </div>
</x-store-layout>