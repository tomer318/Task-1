<x-store-layout>
    <div class="space-y-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-10 shadow-2xl">
            <!-- Gallery -->
            <div class="lg:col-span-6 space-y-4" x-data="{ mainImage: '{{ $product->images->first() ? asset('storage/' . $product->images->first()->image_path) : '' }}' }">
                <div class="w-full aspect-square bg-slate-950 rounded-2xl border border-slate-800 flex items-center justify-center overflow-hidden">
                    <template x-if="mainImage">
                        <img :src="mainImage" class="w-full h-full object-contain p-4">
                    </template>
                    <template x-if="!mainImage">
                        <span class="text-slate-600 text-6xl">📦</span>
                    </template>
                </div>

                @if($product->images->count() > 1)
                    <div class="flex gap-3 overflow-x-auto pb-2">
                        @foreach($product->images as $img)
                            <button @click="mainImage = '{{ asset('storage/' . $img->image_path) }}'" 
                                    class="w-16 h-16 rounded-xl border border-slate-800 bg-slate-950 shrink-0 overflow-hidden hover:border-rose-500 transition">
                                <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Detail -->
            <div class="lg:col-span-6 flex flex-col justify-between space-y-6">
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 bg-rose-500/10 text-rose-400 text-xs font-bold rounded-lg">{{ $product->brand->name ?? 'Brand' }}</span>
                        <span class="text-xs text-slate-400">• {{ $product->category->name }}</span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-extrabold text-white leading-snug">{{ $product->name }}</h1>
                    <div class="text-2xl font-mono font-extrabold text-rose-500 pt-2">${{ number_format($product->price, 2) }}</div>
                    <div class="text-xs text-slate-400 leading-relaxed pt-3 border-t border-slate-800">{{ $product->description }}</div>
                </div>

                <div class="pt-6 border-t border-slate-800 flex items-center gap-4" x-data="{ qty: 1 }">
                    <div class="flex items-center bg-slate-950 border border-slate-800 rounded-xl p-1">
                        <button @click="if(qty > 1) qty--" class="px-3 py-2 text-slate-300 hover:text-white">-</button>
                        <span class="px-3 text-xs font-mono font-bold text-white" x-text="qty"></span>
                        <button @click="qty++" class="px-3 py-2 text-slate-300 hover:text-white">+</button>
                    </div>

                    <button @click="addToCart({{ $product->id }}, qty)" class="flex-1 py-3 bg-gradient-to-r from-rose-600 to-red-500 hover:from-rose-500 hover:to-red-400 text-white text-xs font-bold uppercase tracking-wider rounded-xl shadow-lg transition active:scale-95">
                        Thêm Vào Giỏ Hàng (Redis)
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-store-layout>