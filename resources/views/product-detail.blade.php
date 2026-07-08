@extends('layouts.app')

@section('content')
<div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <!-- Breadcrumbs -->
    <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-8">
        <a href="{{ route('home') }}" class="hover:text-green-600 transition-colors flex items-center">
            <i class="fas fa-home mr-2 text-xs"></i>
            Home
        </a>
        <i class="fas fa-chevron-right text-[10px] text-gray-400"></i>
        <a href="{{ route('products') }}" class="hover:text-green-600 transition-colors">Produk</a>
        <i class="fas fa-chevron-right text-[10px] text-gray-400"></i>
        <span class="font-medium text-gray-800 truncate">{{ $product->name }}</span>
    </nav> 

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-12 gap-y-10">
        <!-- Image Section -->
        <div class="space-y-4" x-data="productGallery()">
            <!-- Main Image -->
            <div class="relative aspect-[4/3] bg-gray-100 rounded-lg shadow-sm overflow-hidden group">
                <img :src="currentImage.image_url" :alt="currentImage.alt_text || '{{ $product->name }}'" class="w-full h-full object-cover transition-opacity duration-300" :class="{'opacity-50': isLoading}" @load="isLoading = false" loading="lazy">
                
                <div x-show="isLoading" class="absolute inset-0 flex items-center justify-center bg-white/50 backdrop-blur-sm">
                    <div class="w-10 h-10 border-4 border-green-600 border-t-transparent rounded-full animate-spin"></div>
                </div>

                @if($product->all_images->count() > 1)
                    <div class="hidden lg:flex absolute inset-x-4 top-1/2 -translate-y-1/2 justify-between opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <button @click="previousImage()" class="bg-white/80 backdrop-blur-sm text-gray-800 h-10 w-10 rounded-full flex items-center justify-center shadow-md hover:bg-white">
                            <i class="fas fa-chevron-left text-xs"></i>
                        </button>
                        <button @click="nextImage()" class="bg-white/80 backdrop-blur-sm text-gray-800 h-10 w-10 rounded-full flex items-center justify-center shadow-md hover:bg-white">
                            <i class="fas fa-chevron-right text-xs"></i>
                        </button>
                    </div>
                @endif

                @if($product->is_featured)
                    <div class="absolute top-3 left-3 z-10">
                        <span class="bg-green-600 text-white text-[10px] uppercase font-bold tracking-wider px-2.5 py-1 rounded-full">
                            Unggulan
                        </span>
                    </div>
                @endif
            </div>

            <!-- Thumbnails -->
            @if($product->all_images->count() > 1)
                <div class="flex gap-3 overflow-x-auto scrollbar-hide pb-2" x-ref="thumbContainer">
                    <template x-for="(image, index) in images" :key="index">
                        <button @click="setCurrentImage(index)" class="flex-shrink-0 w-16 h-16 rounded-md overflow-hidden transition-all duration-200 border-2" :class="index === currentIndex ? 'border-green-600 scale-110' : 'border-transparent hover:border-green-300'">
                            <img :src="image.image_url" :alt="image.alt_text || '{{ $product->name }}'" class="w-full h-full object-cover">
                        </button>
                    </template>
                </div>
            @endif
        </div>

        <!-- Product Info Section -->
        <div class="space-y-8">
            <!-- Header -->
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <span class="px-3 py-1 rounded-full bg-green-50 text-green-600 text-xs font-semibold uppercase tracking-wide border border-green-100">
                        {{ $product->category->name }}
                    </span>
                    @if($product->stock > 0)
                        <span class="flex items-center gap-1.5 text-emerald-600 text-xs font-semibold uppercase tracking-wide">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                            Tersedia
                        </span>
                    @else
                        <span class="text-red-600 text-xs font-semibold uppercase tracking-wide">Stok Habis</span>
                    @endif
                </div>
                <h1 class="text-3xl lg:text-4xl font-bold text-gray-800 tracking-tight">{{ $product->name }}</h1>
                <p class="text-3xl font-semibold text-gray-900 mt-2">{{ $product->formatted_price }}</p>
            </div>

            <!-- Description -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-base font-semibold text-gray-800 mb-3">Deskripsi</h3>
                <div class="prose prose-sm max-w-none text-gray-600 leading-relaxed">
                    {!! nl2br(e($product->description)) !!}
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 gap-4 text-center py-4 border-y border-gray-200">
                <div>
                    <p class="text-2xl font-semibold text-gray-800">{{ $product->stock }}</p>
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Stok</p>
                </div>
                <div>
                    <p class="text-2xl font-semibold text-gray-800">{{ $product->sold_count }}</p>
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Terjual</p>
                </div>
            </div>

            <!-- Rating Display -->
            <div class="py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <div class="flex items-center">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="text-lg {{ $i <= $product->average_rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                            @endfor
                        </div>
                        <span class="text-sm font-semibold text-gray-800">{{ number_format($product->average_rating, 1) }}</span>
                        <span class="text-sm text-gray-500">({{ $product->total_ratings }} ulasan)</span>
                    </div>
                    @auth
                        @if(Auth::user()->orders()->where('payment_status', 'paid')->where('order_status', 'delivered')->whereHas('orderItems', function($q) use ($product) {
                            $q->where('product_id', $product->id);
                        })->exists() && !Auth::user()->ratings()->where('product_id', $product->id)->exists())
                            <a href="{{ route('ratings.create', [Auth::user()->orders()->where('payment_status', 'paid')->where('order_status', 'delivered')->whereHas('orderItems', function($q) use ($product) {
                                $q->where('product_id', $product->id);
                            })->first(), $product]) }}" class="text-sm font-medium text-green-600 hover:text-green-700">
                                Beri Rating
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            @if($product->stock > 0)
                <div class="space-y-6" x-data="{ quantity: 1, maxQuantity: {{ $product->stock }} }">
                    <!-- Quantity -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                        <div class="flex items-center gap-2">
                            <div class="inline-flex items-center p-1 bg-gray-100 rounded-lg border border-gray-200">
                                <button @click="quantity = Math.max(1, quantity - 1)" class="w-8 h-8 flex items-center justify-center rounded-md bg-white shadow-sm hover:bg-gray-50 disabled:opacity-50" :disabled="quantity <= 1">
                                    <i class="fas fa-minus text-xs"></i>
                                </button>
                                <input type="number" x-model="quantity" min="1" :max="maxQuantity" class="w-12 text-center bg-transparent border-0 focus:ring-0 font-semibold text-gray-800">
                                <button @click="quantity = Math.min(maxQuantity, quantity + 1)" class="w-8 h-8 flex items-center justify-center rounded-md bg-white shadow-sm hover:bg-gray-50 disabled:opacity-50" :disabled="quantity >= maxQuantity">
                                    <i class="fas fa-plus text-xs"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500">Stok tersisa: {{ $product->stock }}</p>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    @auth
                        @if(Auth::user()->hasVerifiedEmail())
                            <div class="flex flex-col sm:flex-row gap-3">
                                <button @click="addToCart({{ $product->id }}, quantity)" class="flex-1 px-5 py-3 bg-white border-2 border-green-600 text-green-600 rounded-lg font-semibold hover:bg-green-50 transition-all flex items-center justify-center gap-2">
                                    <i class="fas fa-shopping-cart"></i>
                                    <span>Tambah Keranjang</span>
                                </button>
                                <button @click="buyNow({{ $product->id }}, quantity)" class="flex-1 px-5 py-3 bg-green-700 text-white rounded-lg font-semibold hover:bg-green-800 transition-all flex items-center justify-center gap-2">
                                    <i class="fas fa-bolt"></i>
                                    <span>Beli Sekarang</span>
                                </button>
                            </div>
                        @else
                            <div class="p-3 bg-yellow-50 rounded-lg border border-yellow-200 text-sm text-yellow-800">
                                Verifikasi email Anda untuk melanjutkan pemesanan. <a href="{{ route('verification.notice') }}" class="font-semibold underline">Kirim ulang verifikasi</a>.
                            </div>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="block w-full px-5 py-3 bg-green-700 text-white rounded-lg font-semibold hover:bg-green-800 transition-all text-center">
                            Login untuk Belanja
                        </a>
                    @endauth
                </div>
            @else
                <div class="p-4 bg-gray-100 rounded-lg text-sm text-gray-600">
                    Stok produk ini sedang habis.
                </div>
            @endif
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="mt-16 sm:mt-24 pt-12 border-t border-gray-200">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl md:text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
                <i class="fas fa-star mr-3 text-green-600"></i>
                Ulasan & Rating
            </h2>
            <div class="bg-green-700 text-white px-4 py-2 rounded-full text-sm font-semibold">
                {{ $product->total_ratings }} Review{{ $product->total_ratings > 1 ? 's' : '' }}
            </div>
        </div>
        
        @if($product->ratings->count() > 0)
            <!-- Rating Summary -->
            <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-xl p-6 md:p-8 mb-8 border border-white/20">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex flex-col items-center md:items-start space-y-4">
                        <div class="text-center md:text-left">
                            <div class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">{{ number_format($product->average_rating, 1) }}</div>
                            <div class="flex items-center justify-center md:justify-start mt-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="text-xl md:text-2xl {{ $i <= $product->average_rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                                @endfor
                            </div>
                            <div class="text-sm md:text-base text-gray-500 mt-2">{{ $product->total_ratings }} ulasan</div>
                        </div>
                    </div>
                    
                    <!-- Rating Distribution -->
                    <div class="flex-1 w-full">
                        <div class="space-y-2 md:space-y-3">
                            @for ($i = 5; $i >= 1; $i--)
                                <div class="flex items-center space-x-2 md:space-x-3">
                                    <span class="text-sm md:text-base font-semibold text-gray-600 w-4 md:w-6">{{ $i }}</span>
                                    <span class="text-yellow-400 text-sm md:text-base">★</span>
                                    <div class="flex-1 bg-gray-200 rounded-full h-2 md:h-3">
                                        <div class="bg-green-500 h-2 md:h-3 rounded-full transition-all duration-500" style="width: {{ $product->ratings->where('rating', $i)->count() / $product->total_ratings * 100 }}%"></div>
                                    </div>
                                    <span class="text-sm md:text-base text-gray-500 w-6 md:w-8 text-right font-medium">{{ $product->ratings->where('rating', $i)->count() }}</span>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews List -->
            <div class="space-y-4 md:space-y-6">
                @foreach($product->ratings()->with('user')->latest()->get() as $rating)
                    <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-gray-100 p-4 md:p-6 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-start space-x-3 md:space-x-4">
                            <div class="relative group flex-shrink-0">
                                <img src="{{ $rating->user->profile_photo_url }}" alt="{{ $rating->user->name }}" 
                                     class="w-10 h-10 md:w-12 md:h-12 rounded-full object-cover border-2 border-white shadow-lg transition-all duration-300 group-hover:scale-105">
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-bold text-gray-800 text-sm md:text-base mb-1">
                                    <a href="{{ route('users.profile', $rating->user->name) }}" class="hover:text-green-600 transition-colors">
                                        {{ $rating->user->name }}
                                    </a>
                                </h4>
                                <div class="flex items-center space-x-3 text-sm text-gray-500">
                                    <div class="flex items-center">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <span class="text-base md:text-lg {{ $i <= $rating->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                                        @endfor
                                    </div>
                                    <span class="text-gray-400">•</span>
                                    <span>{{ $rating->created_at->format('d M Y') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        @if($rating->review_text)
                            <p class="mt-3 md:mt-4 text-gray-700 leading-relaxed text-sm md:text-base">{{ $rating->review_text }}</p>
                        @endif

                        <!-- Rating Replies -->
                        @if($rating->replies->count() > 0)
                            <div class="mt-3 md:mt-4 space-y-2 md:space-y-3">
                                @foreach($rating->replies as $reply)
                                    <div class="bg-green-50 rounded-2xl p-3 md:p-4 border border-green-200 shadow-md ml-0 sm:ml-16">
                                        <div class="flex items-start space-x-2 md:space-x-3">
                                            <img src="{{ $reply->user->profile_photo_url }}" alt="{{ $reply->user->name }}" 
                                                 class="w-8 h-8 md:w-10 md:h-10 rounded-full object-cover border-2 border-green-300 shadow-sm flex-shrink-0">
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center space-x-3 mb-2">
                                                        <h5 class="font-bold text-green-800 text-sm md:text-base">
                                                            <a href="{{ route('users.profile', $reply->user->name) }}" class="hover:text-green-600 transition-colors">
                                                                {{ $reply->user->name }}
                                                            </a>
                                                        </h5>
                                                        <span class="bg-green-700 text-white px-2 py-0.5 md:px-3 md:py-1 rounded-full text-xs font-bold">
                                                            @if($reply->user->isAdmin())
                                                                Admin
                                                            @elseif($reply->user->isOperator())
                                                                Operator
                                                            @endif
                                                        </span>
                                                        <span class="text-xs md:text-sm text-gray-500">{{ $reply->created_at->format('d M Y, H:i') }}</span>
                                                        
                                                        <!-- Delete Reply Button -->
                                                        @auth
                                                            @if(auth()->user()->isAdmin() || auth()->user()->isOperator() || $reply->user_id === auth()->id())
                                                                <button type="button" class="text-xs md:text-sm text-red-600 hover:text-red-700 ml-2 delete-reply-btn" data-reply-id="{{ $reply->id }}" onclick="handleDeleteReply({{ $reply->id }})">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            @endif
                                                        @endauth
                                                    </div>
                                                <p class="text-gray-700 leading-relaxed text-sm md:text-base">{{ $reply->reply_text }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Reply Form for Admin/Operator -->
                        @auth
                            @if(auth()->user()->isAdmin() || auth()->user()->isOperator())
                                <form action="{{ route('ratings.reply.store', $rating) }}" method="POST" class="mt-3 md:mt-4 ml-0 sm:ml-16">
                                    @csrf
                                    <div class="flex items-center space-x-2">
                                        <label for="reply-text-{{ $rating->id }}" class="sr-only">Balas rating</label>
                                        <input type="text" 
                                               id="reply-text-{{ $rating->id }}"
                                               name="reply_text" 
                                               placeholder="Balas rating..." 
                                               class="flex-1 px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                               maxlength="1000"
                                               required>
                                        <button type="submit" 
                                                class="px-4 py-2 bg-green-700 hover:bg-green-800 text-white text-sm font-semibold rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                                            <i class="fas fa-paper-plane mr-2"></i>
                                            Balas
                                        </button>
                                    </div>
                                </form>
                            @endif
                        @endauth
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-xl p-8 md:p-12 text-center border border-white/20">
                <div class="text-gray-300 text-5xl md:text-6xl mb-4 md:mb-6 animate-pulse">☆</div>
                <h3 class="text-xl md:text-2xl font-bold text-gray-800 mb-2">Belum Ada Ulasan</h3>
                <p class="text-gray-600 text-base md:text-lg">Jadilah yang pertama memberikan ulasan untuk produk ini!</p>
            </div>
        @endif
    </div>

    <!-- Related Products -->
    @if(isset($relatedProducts) && $relatedProducts->count())
        <div class="mt-16 sm:mt-24 pt-12 border-t border-gray-200">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-bold text-gray-800">Produk Terkait</h2>
                <a href="{{ route('products') }}" class="text-sm font-semibold text-green-600 hover:text-green-700 flex items-center gap-1">
                    Lihat Semua <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
            <x-product-grid :products="$relatedProducts" />
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function productGallery() {
    return {
        images: @json($product->all_images->map(fn($img) => ['image_url' => $img->image_url, 'alt_text' => $img->formatted_alt_text])),
        currentIndex: 0,
        isLoading: true,
        init() {
            this.$watch('currentIndex', () => {
                this.isLoading = true;
                const thumb = this.$refs.thumbContainer.children[this.currentIndex];
                if (thumb) {
                    thumb.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            });
        },
        get currentImage() {
            return this.images[this.currentIndex] || { image_url: '{{ asset('images/default-product.jpg') }}', alt_text: 'Gambar tidak tersedia' };
        },
        previousImage() {
            this.currentIndex = this.currentIndex === 0 ? this.images.length - 1 : this.currentIndex - 1;
        },
        nextImage() {
            this.currentIndex = (this.currentIndex + 1) % this.images.length;
        },
        setCurrentImage(index) {
            this.currentIndex = index;
        }
    }
}
</script>

<script>
async function handleDeleteReply(replyId) {
    const confirmed = await confirmDelete('balasan ini');
    if (confirmed) {
        // Create and submit form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/rating-replies/' + replyId;
        form.style.display = 'none';
        
        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        
        // Add DELETE method
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        form.appendChild(csrfInput);
        form.appendChild(methodInput);
        document.body.appendChild(form);
        
        // Submit form
        form.submit();
    }
}
</script>
@endpush
