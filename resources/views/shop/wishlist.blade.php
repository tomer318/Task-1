<x-store-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 text-white min-h-screen space-y-6">
        
        <!-- Header -->
        <div class="flex items-center justify-between border-b border-slate-800 pb-4">
            <div>
                <h1 class="text-xl font-bold flex items-center gap-2">
                    <span class="text-rose-500">❤️</span> Sản Phẩm Yêu Thích Của Bạn
                </h1>
                <p class="text-xs text-slate-400 mt-1">Danh sách các sản phẩm công nghệ bạn đã lưu lại để theo dõi</p>
            </div>
            <span class="px-3 py-1 bg-rose-500/10 border border-rose-500/20 text-rose-400 font-mono text-xs font-bold rounded-xl">
                {{ $wishlists->total() }} sản phẩm
            </span>
        </div>

        @if($wishlists->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($wishlists as $item)
                    @php $product = $item->product; @endphp
                    @if($product)
                        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-4 flex flex-col justify-between hover:border-rose-500/50 transition shadow-xl group relative">
                            
                            <!-- Nút xóa nhanh khỏi Wishlist -->
                            <form action="{{ route('wishlist.destroy', $product->id) }}" method="POST" class="absolute top-3 right-3 z-10">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Bỏ thích" class="w-8 h-8 rounded-full bg-slate-950/80 hover:bg-rose-600 text-slate-400 hover:text-white flex items-center justify-center transition border border-slate-800 cursor-pointer">
                                    &times;
                                </button>
                            </form>

                            <div>
                                <!-- Ảnh sản phẩm -->
                                <a href="{{ route('shop.product', $product->slug ?? $product->id) }}" class="block aspect-square bg-slate-950 rounded-2xl p-4 overflow-hidden mb-3 border border-slate-800/80">
                                    <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=400' }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-full object-contain group-hover:scale-105 transition duration-300">
                                </a>

                                <div class="space-y-1.5">
                                    <span class="text-[10px] font-mono text-rose-400 uppercase tracking-wider">{{ $product->brand->name ?? 'TECHZONE' }}</span>
                                    <h3 class="font-bold text-xs line-clamp-2 hover:text-rose-400 transition">
                                        <a href="{{ route('shop.product', $product->slug ?? $product->id) }}">{{ $product->name }}</a>
                                    </h3>
                                    <div class="font-mono font-black text-rose-500 text-sm">
                                        {{ number_format($product->price ?? $product->regular_price ?? 0, 0, ',', '.') }}₫
                                    </div>
                                </div>
                            </div>

                            <!-- Nút Xem chi tiết -->
                            <div class="pt-4 mt-3 border-t border-slate-800">
                                <a href="{{ route('shop.product', $product->slug ?? $product->id) }}" 
                                   class="block w-full py-2.5 bg-slate-950 hover:bg-rose-600 text-slate-300 hover:text-white border border-slate-800 hover:border-rose-500 rounded-xl text-center text-xs font-bold transition">
                                    Xem Chi Tiết & Mua
                                </a>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Phân trang -->
            @if($wishlists->hasPages())
                <div class="pt-6 flex justify-center">
                    {{ $wishlists->links() }}
                </div>
            @endif
        @else
            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-12 text-center space-y-4">
                <div class="text-4xl">💔</div>
                <h3 class="text-base font-bold text-slate-300">Danh sách yêu thích trống</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">Bạn chưa lưu sản phẩm nào. Hãy khám phá các sản phẩm công nghệ hot và bấm biểu tượng trái tim để lưu lại nhé!</p>
                <a href="{{ route('shop.index') }}" class="inline-block px-6 py-3 bg-gradient-to-r from-rose-600 to-red-500 text-white font-bold rounded-xl text-xs shadow-lg">
                    Khám phá sản phẩm ngay
                </a>
            </div>
        @endif

    </div>
</x-store-layout>