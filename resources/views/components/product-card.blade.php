@props(['product'])

<div class="group relative flex h-full flex-col overflow-hidden rounded-lg border border-gray-200/80 bg-white shadow-sm transition-all duration-300 hover:shadow-lg">
    <!-- Image -->
    <a href="{{ route('products.detail', $product->slug) }}" class="block aspect-[4/3] overflow-hidden">
        <img src="{{ $product->image_url }}" 
             alt="{{ $product->name }}" 
             class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
    </a>

    <!-- Badges -->
    <div class="absolute top-3 left-3 flex flex-col gap-1.5">
        @if($product->is_featured)
            <span class="inline-block rounded-full bg-green-600 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider text-white shadow-sm">
                Unggulan
            </span>
        @endif
        @if($product->stock <= 0)
            <span class="inline-block rounded-full bg-red-500/90 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider text-white shadow-sm">
                Habis
            </span>
        @endif
    </div>

    <!-- Content -->
    <div class="flex flex-1 flex-col p-3 sm:p-4">
        <div class="flex-1">
            <p class="text-xs font-semibold uppercase tracking-wide text-green-600">{{ $product->category->name }}</p>
            <h3 class="mt-1 text-sm font-semibold text-gray-800 transition-colors group-hover:text-green-700 sm:text-base sm:font-bold">
                <a href="{{ route('products.detail', $product->slug) }}">
                    <span class="absolute inset-0" aria-hidden="true"></span>
                    {{ $product->name }}
                </a>
            </h3>
        </div>
        <p class="mt-1 text-base font-semibold text-gray-900 sm:mt-2 sm:text-lg">{{ $product->formatted_price }}</p>

        <!-- Actions (appear on hover) -->
        <div class="mt-4 transition-all duration-300 lg:translate-y-4 lg:opacity-0 lg:group-hover:translate-y-0 lg:group-hover:opacity-100">
            @if($product->stock > 0)
                <div class="flex flex-col xs:flex-row gap-2">
                    <button 
                        @click.prevent.stop="addToCart({{ $product->id }}, 1)"
                        class="flex-1 rounded-lg bg-gray-200/80 px-3 py-2 text-xs font-semibold text-gray-800 shadow-sm transition-all hover:bg-gray-300/60">
                        + Keranjang
                    </button>
                    <button 
                        @click.prevent.stop="buyNow({{ $product->id }}, 1)"
                        class="flex-1 rounded-lg bg-green-700 px-3 py-2 text-xs font-semibold text-white shadow-sm transition-all hover:bg-green-800">
                        Beli Sekarang
                    </button>
                </div>
            @else
                <button 
                    disabled
                    class="w-full cursor-not-allowed rounded-lg bg-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-500">
                    Stok Habis
                </button>
            @endif
        </div>
    </div>
</div>
