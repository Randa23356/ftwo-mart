@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Modern Hero Header -->
    <div class="relative bg-gradient-to-br from-green-800 via-green-700 to-emerald-600 rounded-3xl p-10 md:p-14 mb-12 overflow-hidden shadow-xl text-center">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-green-500 opacity-10 rounded-full blur-3xl"></div>
        </div>
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-full px-5 py-2 mb-8">
                <i class="fas fa-box text-green-200 text-xs"></i>
                <span class="text-green-100 text-sm font-medium tracking-wide">Koleksi Kami</span>
            </div>
            <h1 class="text-3xl md:text-5xl font-extrabold text-white mb-4 tracking-tight">
                Jelajahi Produk
            </h1>
            <p class="text-green-100/90 text-lg md:text-xl max-w-2xl mx-auto font-medium leading-relaxed">
                Temukan berbagai produk berkualitas dengan harga terbaik. Marketplace terpercaya untuk semua kebutuhan Anda.
            </p>
        </div>
    </div> 

    <!-- Search & Filter Section -->
    <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8 mb-12 -mt-20 relative z-20 mx-auto max-w-5xl border border-gray-100">
        <form method="GET" action="{{ route('products') }}" class="flex flex-col md:flex-row gap-4 items-center">
            <div class="w-full flex-1 relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400 group-focus-within:text-green-500 transition-colors"></i>
                </div>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 placeholder-gray-400"
                       placeholder="Cari produk...">
            </div>

            <div class="w-full md:w-72 relative" 
                 x-data="{ 
                     open: false, 
                     selected: '{{ request('category') }}',
                     selectedName: '{{ request('category') ? ($categories->firstWhere('slug', request('category'))->name ?? 'Semua Kategori') : 'Semua Kategori' }}' 
                 }"
                 @click.away="open = false">
                
                <!-- Hidden Input for Form Submission -->
                <input type="hidden" name="category" :value="selected">

                <!-- Dropdown Trigger -->
                <button type="button" 
                        @click="open = !open"
                        class="w-full pl-11 pr-10 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200 flex items-center justify-between group hover:bg-white hover:shadow-md">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-filter text-gray-400 group-hover:text-green-500 transition-colors"></i>
                    </div>
                    <span class="text-gray-700 font-medium truncate" x-text="selectedName"></span>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                    </div>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-2"
                     class="absolute z-50 w-full mt-2 bg-white border border-gray-100 rounded-xl shadow-xl max-h-64 overflow-y-auto custom-scrollbar"
                     style="display: none;">
                    
                    <div class="p-1">
                        <!-- 'All Categories' Option -->
                        <div @click="selected = ''; selectedName = 'Semua Kategori'; open = false"
                             class="px-4 py-2.5 rounded-lg cursor-pointer flex items-center justify-between group transition-colors"
                             :class="{ 'bg-green-50 text-green-700': selected === '', 'hover:bg-gray-50 text-gray-700': selected !== '' }">
                            <span class="font-medium">Semua Kategori</span>
                            <i class="fas fa-check text-green-500" x-show="selected === ''"></i>
                        </div>

                        <!-- Category Options -->
                        @foreach($categories as $category)
                            <div @click="selected = '{{ $category->slug }}'; selectedName = '{{ $category->name }}'; open = false"
                                 class="px-4 py-2.5 rounded-lg cursor-pointer flex items-center justify-between group transition-colors mt-1"
                                 :class="{ 'bg-green-50 text-green-700': selected === '{{ $category->slug }}', 'hover:bg-gray-50 text-gray-700': selected !== '{{ $category->slug }}' }">
                                <span class="font-medium">{{ $category->name }}</span>
                                <i class="fas fa-check text-green-500" x-show="selected === '{{ $category->slug }}'"></i>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <button type="submit" 
                    class="w-full md:w-auto px-8 py-3 bg-green-700 hover:bg-green-800 text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center">
                <span>Cari</span>
            </button>
        </form>
    </div>

    <!-- Products Grid -->
    <div class="mb-12">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-gray-900">
                @if(request('search'))
                    Hasil Pencarian "{{ request('search') }}"
                @elseif(request('category'))
                    Kategori: {{ $categories->firstWhere('slug', request('category'))->name ?? request('category') }}
                @else
                    Terbaru Ditambahkan
                @endif
            </h2>
            <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                {{ $products->count() }} Produk
            </span>
        </div>

        @if($products->count() > 0)
            <x-product-grid :products="$products" />
        @else
            <div class="text-center py-16 bg-white rounded-3xl shadow-sm border border-gray-100">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-search text-4xl text-gray-300"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Tidak ditemukan</h3>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">
                    Maaf, kami tidak dapat menemukan produk yang Anda cari. Coba gunakan kata kunci lain.
                </p>
                <a href="{{ route('products') }}" 
                   class="inline-flex items-center px-6 py-3 bg-green-700 hover:bg-green-800 text-white font-bold rounded-xl transition-colors shadow-lg hover:shadow-xl">
                    <i class="fas fa-redo-alt mr-2"></i> Reset Pencarian
                </a>
            </div>
        @endif
    </div>
</div>
</div>

<!-- Add to Cart Script -->
@auth
<script>
function addToCart(productId, quantity) {
    if (quantity && !isNaN(quantity) && quantity > 0) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("cart.add") }}';

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';

        const productIdInput = document.createElement('input');
        productIdInput.type = 'hidden';
        productIdInput.name = 'product_id';
        productIdInput.value = productId;

        const quantityInput = document.createElement('input');
        quantityInput.type = 'hidden';
        quantityInput.name = 'quantity';
        quantityInput.value = quantity;

        form.appendChild(csrfToken);
        form.appendChild(productIdInput);
        form.appendChild(quantityInput);
        document.body.appendChild(form);
        form.submit();
    }
}

async function buyNow(productId, quantity) {
    if (!quantity || isNaN(quantity) || quantity <= 0) {
        alert('Jumlah produk tidak valid');
        return;
    }

    try {
        const button = event.target;
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...';

        const response = await fetch('{{ route("buy_now") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity
            })
        });

        const data = await response.json();

        if (response.ok) {
            // Redirect to checkout
            window.location.href = '{{ route("orders.checkout") }}';
        } else {
            alert(data.message || 'Gagal memproses pesanan');
            button.disabled = false;
            button.innerHTML = originalText;
        }
    } catch (error) {
        console.error('Error in buy now:', error);
        alert('Terjadi kesalahan saat memproses pesanan');
        const button = event.target;
        button.disabled = false;
        button.innerHTML = originalText;
    }
}
</script>
@endauth
@endsection
