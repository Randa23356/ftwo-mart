@props(['cartItem'])

<div class="p-6"
    x-data="{
        quantity: {{ $cartItem->quantity }},
        updating: false,
        async updateQuantity() {
            this.updating = true;
            try {
                await fetch('{{ route('cart.update', $cartItem) }}', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ quantity: this.quantity })
                });
                window.location.reload();
            } catch (e) {
                notify.error('Gagal update jumlah produk');
            }
            this.updating = false;
        }
    }"
>
    <div class="flex items-center space-x-4">
        <!-- Product Image -->
        <div class="flex-shrink-0">
            <img src="{{ $cartItem->product->image_url }}" alt="{{ $cartItem->product->name }}"
                 class="w-20 h-20 object-cover rounded-lg">
        </div>

        <!-- Product Info -->
        <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $cartItem->product->name }}</h3>
                    <p class="text-sm text-amber-600 font-medium">{{ $cartItem->product->category->name }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ $cartItem->product->description }}</p>
                </div>
                <div class="text-right">
                    <p class="text-lg font-bold text-gray-900">{{ $cartItem->product->formatted_price }}</p>
                    <p class="text-sm text-gray-500">per item</p>
                </div>
            </div>

            <!-- Quantity Controls -->
            <div class="flex items-center justify-between mt-4">
                <div class="flex items-center space-x-3">
                    <label for="quantity-{{ $cartItem->id }}" class="text-sm font-medium text-gray-700">Jumlah:</label>
                    <div class="flex items-center border border-gray-300 rounded-lg">
                        <button type="button"
                                @click="quantity = Math.max(1, quantity - 1)"
                                class="px-3 py-1 text-gray-600 hover:bg-gray-100 transition-colors focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-opacity-50">
                            <i class="fas fa-minus text-xs"></i>
                        </button>
                        <input type="number"
                               id="quantity-{{ $cartItem->id }}"
                               name="quantity"
                               x-model="quantity"
                               min="1"
                               max="{{ $cartItem->product->stock }}"
                               class="w-16 text-center border-0 focus:ring-0 text-sm bg-transparent">
                        <button type="button"
                                @click="quantity = Math.min({{ $cartItem->product->stock }}, quantity + 1)"
                                class="px-3 py-1 text-gray-600 hover:bg-gray-100 transition-colors focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-opacity-50">
                            <i class="fas fa-plus text-xs"></i>
                        </button>
                    </div>

                    <button @click="updateQuantity"
                            :disabled="updating || quantity === {{ $cartItem->quantity }}"
                            class="bg-amber-600 hover:bg-amber-700 disabled:bg-gray-400 text-white px-3 py-1 rounded text-sm font-medium transition-colors">
                        <span x-show="!updating">Update</span>
                        <span x-show="updating">Updating...</span>
                    </button>
                </div>

                <div class="text-right">
                    <p class="text-lg font-bold text-gray-900">{{ $cartItem->formatted_subtotal }}</p>
                    <p class="text-sm text-gray-500">Total: {{ $cartItem->quantity }} × {{ $cartItem->product->formatted_price }}</p>
                </div>
            </div>

            <!-- Stock Warning -->
            @if($cartItem->product->stock < $cartItem->quantity)
                <div class="mt-2 p-2 bg-red-100 border border-red-300 rounded text-red-700 text-sm">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    Stok tersedia hanya {{ $cartItem->product->stock }} item
                </div>
            @endif
        </div>

        <!-- Remove Button -->
        <div class="flex-shrink-0">
            <form method="POST" action="{{ route('cart.remove', $cartItem) }}"
                  onsubmit="event.preventDefault(); handleRemoveItem(this, '{{ $cartItem->product->name }}')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-800 transition-colors">
                    <i class="fas fa-trash text-lg"></i>
                </button>
            </form>
        </div>
    </div>
</div>
