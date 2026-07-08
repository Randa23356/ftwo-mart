@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header Card -->
        <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-xl p-4 md:p-8 mb-4 md:mb-6 border border-white/20">
            <div class="flex flex-col items-center justify-between mb-4 md:mb-6 md:flex-row">
                <h1 class="text-xl md:text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent text-center md:text-left mb-3 md:mb-0">
                    <i class="fas fa-star mr-2 md:mr-3 text-amber-500"></i>
                    <span class="hidden md:inline">Beri Rating & Ulasan</span>
                    <span class="md:hidden">Rating</span>
                </h1>
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-3 py-1 md:px-4 md:py-2 rounded-full text-xs md:text-sm font-semibold">
                    <i class="fas fa-plus mr-1 md:mr-2 text-xs md:text-sm"></i>
                    <span class="hidden md:inline">New Rating</span>
                    <span class="md:hidden">New</span>
                </div>
            </div>
            
            <!-- Product Info Card -->
            <div class="bg-gradient-to-r from-gray-50 to-white rounded-2xl p-3 md:p-6 border border-gray-100 shadow-lg">
                <div class="flex flex-col items-center space-y-3 md:flex-row md:space-y-0 md:space-x-4">
                    <div class="relative group">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                             class="w-14 h-14 md:w-20 md:h-20 object-cover rounded-xl border-2 border-white shadow-lg transition-all duration-300 group-hover:scale-105">
                        <div class="absolute -top-1 -right-1 w-3 h-3 md:w-4 md:h-4 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                    </div>
                    <div class="flex-1 text-center md:text-left">
                        <h3 class="font-bold text-gray-900 text-base md:text-xl mb-1">{{ $product->name }}</h3>
                        <div class="flex flex-col items-center space-y-1 md:items-start md:flex-row md:space-y-0 md:space-x-3">
                            <span class="text-xs md:text-sm text-gray-600">
                                <i class="fas fa-shopping-bag mr-1 text-blue-500"></i>
                                Order: {{ $order->order_number }}
                            </span>
                            <span class="hidden md:inline text-gray-400">•</span>
                            <span class="text-xs md:text-sm text-gray-600">
                                <i class="fas fa-truck mr-1 text-green-500"></i>
                                {{ $order->order_status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rating Form Card -->
        <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-xl p-4 md:p-8 border border-white/20">
            <form action="{{ route('ratings.store', [$order, $product]) }}" method="POST" class="space-y-4 md:space-y-8">
                @csrf

                <!-- Rating Stars Section -->
                <div class="bg-gradient-to-r from-gray-50 to-white rounded-2xl p-4 md:p-8 border border-gray-100 shadow-lg">
                    <div class="text-center mb-4 md:mb-6">
                        <label class="block text-base md:text-lg font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent mb-2 md:mb-4">
                            <i class="fas fa-star mr-2 text-amber-500"></i>
                            <span class="hidden md:inline">Berikan Rating Anda</span>
                            <span class="md:hidden">Rating</span>
                        </label>
                        <p class="text-gray-600 text-xs md:text-sm">Pilih rating untuk produk ini</p>
                    </div>
                    
                    <div class="flex justify-center space-x-1 md:space-x-4" id="rating-stars">
                        @for ($i = 1; $i <= 5; $i++)
                            <button type="button" 
                                    class="star-btn text-2xl md:text-4xl text-gray-300 hover:text-amber-400 transition-all duration-300 transform hover:scale-110 focus:outline-none focus:scale-110 p-1 md:p-0"
                                    data-rating="{{ $i }}"
                                    onclick="setRating({{ $i }})">
                                <span class="inline-block">★</span>
                            </button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="rating-input" value="0" required>
                    @error('rating')
                        <div class="mt-2 md:mt-4 p-2 md:p-3 bg-red-50 border border-red-200 rounded-xl">
                            <p class="text-xs md:text-sm text-red-600 font-medium">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ $message }}
                            </p>
                        </div>
                    @enderror
                </div>

                <!-- Review Text Section -->
                <div class="bg-gradient-to-r from-gray-50 to-white rounded-2xl p-4 md:p-8 border border-gray-100 shadow-lg">
                    <div class="mb-3 md:mb-4">
                        <label for="review_text" class="block text-base md:text-lg font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent mb-1 md:mb-2">
                            <i class="fas fa-pen mr-2 text-blue-500"></i>
                            <span class="hidden md:inline">Ulasan (Opsional)</span>
                            <span class="md:hidden">Ulasan</span>
                        </label>
                        <p class="text-gray-600 text-xs md:text-sm">Bagikan pengalaman Anda dengan produk ini</p>
                    </div>
                    
                    <div class="relative">
                        <textarea name="review_text" 
                                  id="review_text" 
                                  rows="3" 
                                  class="w-full px-3 py-2 md:px-4 md:py-3 rounded-xl border-2 border-gray-200 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-300 resize-none text-sm md:text-base"
                                  placeholder="Ceritakan pengalaman Anda..."
                                  maxlength="1000">{{ old('review_text') }}</textarea>
                        <div class="absolute bottom-2 md:bottom-3 right-2 md:right-3 text-xs text-gray-500">
                            <span id="char-count">0</span>/1000
                        </div>
                    </div>
                    @error('review_text')
                        <div class="mt-2 md:mt-4 p-2 md:p-3 bg-red-50 border border-red-200 rounded-xl">
                            <p class="text-xs md:text-sm text-red-600 font-medium">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ $message }}
                            </p>
                        </div>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col space-y-2 md:flex-row md:justify-end md:space-y-0 md:space-x-4">
                    <a href="{{ route('products.detail', $product->slug) }}" 
                       class="px-4 py-2 md:px-6 md:py-3 text-center font-semibold text-gray-700 bg-white border-2 border-gray-300 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all duration-300 transform hover:scale-105 shadow-lg text-sm md:text-base">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 md:px-6 md:py-3 font-semibold text-white bg-gradient-to-r from-amber-500 to-amber-600 rounded-xl hover:from-amber-600 hover:to-amber-700 transition-all duration-300 transform hover:scale-105 shadow-lg text-sm md:text-base">
                        <i class="fas fa-paper-plane mr-2"></i>
                        <span class="hidden md:inline">Kirim Rating</span>
                        <span class="md:hidden">Kirim</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function setRating(rating) {
    const stars = document.querySelectorAll('.star-btn');
    const input = document.getElementById('rating-input');
    
    // Set input value
    input.value = rating;
    
    // Update star colors with animation
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.remove('text-gray-300');
            star.classList.add('text-amber-400');
            star.style.transform = 'scale(1.2)';
            setTimeout(() => {
                star.style.transform = 'scale(1)';
            }, 200);
        } else {
            star.classList.remove('text-amber-400');
            star.classList.add('text-gray-300');
        }
    });
}

// Character counter
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('review_text');
    const charCount = document.getElementById('char-count');
    const rating = document.getElementById('rating-input').value || 0;
    
    if (rating > 0) {
        setRating(rating);
    }
    
    // Update character count
    textarea.addEventListener('input', function() {
        charCount.textContent = this.value.length;
        if (this.value.length > 900) {
            charCount.classList.add('text-red-500');
        } else {
            charCount.classList.remove('text-red-500');
        }
    });
    
    // Initial count
    charCount.textContent = textarea.value.length;
});
</script>
@endsection
