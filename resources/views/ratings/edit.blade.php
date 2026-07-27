@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 md:py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-2xl mb-4 shadow-sm">
                <i class="fas fa-edit text-green-600 text-2xl"></i>
            </div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-1">Edit Ulasan</h1>
            <p class="text-gray-500 text-sm">Perbarui rating dan ulasan Anda</p>
        </div>

        {{-- Product Info --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-5">
            <div class="p-5 flex items-center gap-4">
                @if($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-16 h-16 rounded-xl object-cover border border-gray-200 flex-shrink-0">
                @else
                    <div class="w-16 h-16 rounded-xl bg-gray-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-image text-gray-400"></i>
                    </div>
                @endif
                <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-gray-900 truncate">{{ $product->name }}</h3>
                    <div class="flex items-center gap-3 mt-1">
                        <span class="text-xs text-gray-500">
                            <i class="fas fa-receipt mr-1 text-green-500"></i>#{{ $order->order_number }}
                        </span>
                        <span class="text-xs text-gray-400">•</span>
                        <span class="text-xs text-gray-500 capitalize">{{ $order->order_status }}</span>
                    </div>
                </div>
                <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full flex-shrink-0">Edit</span>
            </div>
        </div>

        {{-- Form --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                <div class="w-8 h-8 bg-amber-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-pen text-amber-500 text-sm"></i>
                </div>
                <h2 class="font-semibold text-gray-900 text-sm">Edit Ulasan</h2>
            </div>

            <form action="{{ route('ratings.update', [$order, $product]) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                {{-- Star Rating --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Rating</label>
                    <p class="text-xs text-gray-500 mb-3">Rating saat ini: <span class="font-bold text-amber-500">{{ $rating->rating }}</span>/5</p>
                    <div class="flex items-center gap-2" id="rating-stars">
                        @for ($i = 1; $i <= 5; $i++)
                            <button type="button"
                                    class="star-btn text-3xl md:text-4xl {{ $i <= $rating->rating ? 'text-amber-400' : 'text-gray-300' }} hover:text-amber-400 transition-all duration-200 focus:outline-none p-0.5"
                                    data-rating="{{ $i }}"
                                    onclick="setRating({{ $i }})">
                                ★
                            </button>
                        @endfor
                        <span id="rating-label" class="text-sm text-gray-500 ml-2 font-medium"></span>
                    </div>
                    <input type="hidden" name="rating" id="rating-input" value="{{ $rating->rating }}" required>
                    @error('rating')
                        <p class="mt-2 text-xs text-red-600 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Review Text --}}
                <div>
                    <label for="review_text" class="block text-sm font-semibold text-gray-700 mb-2">
                        Ulasan <span class="text-gray-400 font-normal">(opsional)</span>
                    </label>
                    <textarea name="review_text"
                              id="review_text"
                              rows="4"
                              class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-500/10 transition-all duration-200 resize-none"
                              placeholder="Ceritakan kembali pengalaman Anda..."
                              maxlength="1000">{{ old('review_text', $rating->review_text) }}</textarea>
                    <div class="flex justify-between items-center mt-1.5">
                        @error('review_text')
                            <p class="text-xs text-red-600 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                        @else
                            <span></span>
                        @enderror
                        <span class="text-xs text-gray-400"><span id="char-count">{{ strlen(old('review_text', $rating->review_text)) }}</span>/1000</span>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 pt-2">
                    <a href="{{ route('products.detail', $product->slug) }}"
                       class="flex-1 sm:flex-none px-5 py-2.5 text-center text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                            class="flex-1 sm:flex-none px-6 py-2.5 text-sm font-semibold text-white bg-green-700 hover:bg-green-800 rounded-xl transition-colors shadow-sm">
                        <i class="fas fa-save mr-1.5"></i> Update Ulasan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const ratingLabels = ['', 'Sangat Buruk', 'Buruk', 'Cukup', 'Bagus', 'Sangat Bagus'];

function setRating(rating) {
    const stars = document.querySelectorAll('.star-btn');
    const input = document.getElementById('rating-input');
    const label = document.getElementById('rating-label');

    input.value = rating;
    label.textContent = ratingLabels[rating];

    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.remove('text-gray-300');
            star.classList.add('text-amber-400');
            star.style.transform = 'scale(1.15)';
            setTimeout(() => { star.style.transform = 'scale(1)'; }, 150);
        } else {
            star.classList.remove('text-amber-400');
            star.classList.add('text-gray-300');
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('review_text');
    const charCount = document.getElementById('char-count');
    const rating = parseInt(document.getElementById('rating-input').value) || 0;

    if (rating > 0) {
        setRating(rating);
    }

    textarea.addEventListener('input', function() {
        charCount.textContent = this.value.length;
    });
});
</script>
@endsection