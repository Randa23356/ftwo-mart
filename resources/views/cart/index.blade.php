@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="cartManager()">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Keranjang Belanja</h1>
        <p class="text-gray-600">Kelola produk yang ingin Anda beli</p>
    </div>

    @if($cartItems->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 sm:gap-0">
                            <h2 class="text-lg font-semibold text-gray-900">Produk di Keranjang ({{ $cartItems->count() }})</h2>
                            <div class="flex items-center space-x-4">
                                <label class="flex items-center text-sm text-gray-600">
                                    <input type="checkbox"
                                           @change="toggleAllSelection($event.target.checked)"
                                           :checked="isAllSelected"
                                           class="mr-2 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                    <span x-text="isAllSelected ? 'Batalkan Semua' : 'Pilih Semua'"></span>
                                </label>
                                <span class="text-sm text-gray-500">
                                    <span x-text="selectedCount"></span> dari {{ $cartItems->count() }} dipilih
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @foreach($cartItems as $cartItem)
                            <div class="p-4 bg-white rounded-lg shadow transition-shadow hover:shadow-md border border-transparent hover:border-gray-100">
                                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                                    <!-- Selection Checkbox -->
                                    <div class="flex-shrink-0">
                                        <input type="checkbox"
                                               @change="toggleItemSelection({{ $cartItem->id }}, $event.target.checked)"
                                               :checked="selectedItems.includes({{ $cartItem->id }})"
                                               class="w-5 h-5 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                    </div>

                                    <!-- Product Image -->
                                    <div class="flex-shrink-0 overflow-hidden rounded-lg">
                                        <img src="{{ $cartItem->product->image_url }}" alt="{{ $cartItem->product->name }}"
                                             class="w-20 h-20 md:w-24 md:h-24 object-cover">
                                    </div>

                                    <!-- Product Info -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                                            <div class="min-w-0">
                                                <h3 class="text-lg md:text-xl font-semibold text-gray-900 truncate leading-tight">{{ $cartItem->product->name }}</h3>
                                                <p class="text-xs text-green-600 font-medium mt-1">{{ $cartItem->product->category->name }}</p>
                                                <p class="text-sm text-gray-500 mt-2 hidden sm:block truncate">{{ $cartItem->product->description }}</p>
                                                <p class="text-sm text-gray-500 mt-2 block sm:hidden truncate">{{ Str::limit($cartItem->product->description, 80) }}</p>
                                            </div>

                                            <div class="flex-shrink-0 ml-3 sm:ml-0 text-right sm:text-right mt-1 sm:mt-0 sm:min-w-[110px]">
                                                <div class="inline-flex items-baseline space-x-2 justify-end">
                                                    <span class="text-lg md:text-2xl font-extrabold text-green-700 truncate sm:whitespace-nowrap">{{ $cartItem->product->formatted_price }}</span>
                                                </div>
                                                <p class="text-xs text-gray-500 mt-1 whitespace-nowrap">per item</p>
                                            </div>
                                        </div>

                                        <!-- Quantity Controls -->
                                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mt-4 space-y-3 sm:space-y-0 sm:space-x-4"
                                             x-data="{
                                                 id: {{ $cartItem->id }},
                                                 price: {{ $cartItem->product->price }},
                                                 quantity: {{ $cartItem->quantity }},
                                                 initialQuantity: {{ $cartItem->quantity }},
                                                 updating: false,
                                                 updateLocal() {
                                                     try {
                                                         updateItemData(this.id, this.quantity, this.price);
                                                     } catch (e) {
                                                         console.error(e);
                                                     }
                                                 },
                                                 async updateQuantity() {
                                                     if (!(this.quantity && !isNaN(this.quantity) && this.quantity > 0)) return;

                                                     const prev = this.initialQuantity;

                                                     console.log('updateQuantity start', this.id, this.quantity);
                                                     // optimistic update: reflect immediately in UI
                                                     updateItemData(this.id, this.quantity, this.price);
                                                     this.updating = true;

                                                     try {
                                                         const requestUrl = '{{ url("cart/".$cartItem->id."/update") }}';
                                                         console.log('sending update to', requestUrl, 'quantity', this.quantity);
                                                         const response = await fetch(requestUrl, {
                                                             method: 'POST',
                                                             headers: {
                                                                 'Content-Type': 'application/json',
                                                                 'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                                 'X-HTTP-Method-Override': 'PUT'
                                                             },
                                                             body: JSON.stringify({ quantity: this.quantity })
                                                         });

                                                         console.log('updateQuantity response status', response.status);
                                                         if (response.ok) {
                                                             // commit the new baseline so Update button disables
                                                             this.initialQuantity = this.quantity;
                                                             // re-apply confirmed update to global state
                                                             try { updateItemData(this.id, this.quantity, this.price); } catch (e) { console.error(e); }
                                                            // inform global manager in case direct access isn't available
                                                            try {
                                                                window.dispatchEvent(new CustomEvent('cart-updated', { detail: { id: this.id, quantity: this.quantity, price: this.price } }));
                                                            } catch (e) { /* ignore */ }
                                                         } else {
                                                             // rollback on failure
                                                             console.warn('updateQuantity failed, rolling back');
                                                             updateItemData(this.id, prev, this.price);
                                                             this.quantity = prev;
                                                         }
                                                     } catch (error) {
                                                         console.error('Error updating quantity:', error);
                                                         updateItemData(this.id, prev, this.price);
                                                         this.quantity = prev;
                                                     } finally {
                                                         this.updating = false;
                                                     }
                                                 }
                                             }">
                                            <div class="flex items-center space-x-3 w-full sm:flex-1">
                                                <div class="flex items-center space-x-3">
                                                    <label for="quantity-{{ $cartItem->id }}" class="text-sm font-medium text-gray-700">Jumlah:</label>
                                                    <div class="flex items-center border border-gray-300 rounded-lg">
                                                        <button type="button"
                                                                @click="quantity = Math.max(1, quantity - 1); updateLocal()"
                                                            class="px-3 py-1 text-gray-600 hover:bg-gray-100 transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">
                                                            <i class="fas fa-minus text-xs"></i>
                                                        </button>
                                                        <input type="number"
                                                               id="quantity-{{ $cartItem->id }}"
                                                               name="quantity"
                                                               x-model="quantity"
                                                               @input="updateLocal()"
                                                               min="1"
                                                               max="{{ $cartItem->product->stock }}"
                                                               class="w-16 text-center border-0 focus:ring-0 text-sm bg-transparent">
                                                        <button type="button"
                                                                @click="quantity = Math.min({{ $cartItem->product->stock }}, quantity + 1); updateLocal()"
                                                            class="px-3 py-1 text-gray-600 hover:bg-gray-100 transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">
                                                            <i class="fas fa-plus text-xs"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="w-full sm:w-auto ml-0 sm:ml-3">
                                                    <button type="button" @click="updateLocal(); updateQuantity()"
                                                        :disabled="updating || quantity === initialQuantity"
                                                        :class="(updating || quantity === initialQuantity) ? 'bg-gray-400 cursor-not-allowed' : 'bg-green-600 hover:bg-green-700'"
                                                        class="text-white px-3 py-2 rounded text-sm font-medium transition-colors w-full sm:w-auto">
                                                        <span x-show="!updating">Update</span>
                                                        <span x-show="updating">Updating...</span>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="mt-2 sm:mt-0 sm:text-right">
                                                <p class="text-lg font-bold text-green-700 whitespace-nowrap" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(quantity * price)"></p>
                                                <p class="text-sm text-gray-500 mt-1 text-right whitespace-normal sm:whitespace-nowrap">
                                                    <span class="hidden sm:inline">Total: </span><span x-text="quantity"></span> × {{ $cartItem->product->formatted_price }}
                                                </p>
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
                                    <div class="flex-shrink-0 mt-0 sm:mt-0">
                                        <form method="POST" action="{{ route('cart.remove', $cartItem) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    onclick="confirmAndSubmitForm(this.closest('form'), {{ Illuminate\Support\Js::from('Apakah Anda yakin ingin menghapus ' . $cartItem->product->name . ' dari keranjang?') }})"
                                                    class="text-red-600 hover:text-red-800 transition-colors p-2 rounded-full hover:bg-red-50">
                                                <i class="fas fa-trash text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Cart Actions -->
                    <div class="px-4 py-4 bg-white">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                            <form method="POST" action="{{ route('cart.clear') }}" class="w-full sm:w-auto">
                                @csrf
                                @method('DELETE')
                                <x-button type="button"
                                          variant="danger"
                                          size="sm"
                                          class="w-full sm:w-auto justify-center"
                                          onclick="confirmAndSubmitForm(this.closest('form'), {{ Illuminate\Support\Js::from('Apakah Anda yakin ingin mengosongkan semua produk dari keranjang?') }})">
                                    <i class="fas fa-trash mr-2"></i> Kosongkan Keranjang
                                </x-button>
                            </form>

                            <x-button href="{{ route('products') }}" variant="outline" size="sm" class="w-full sm:w-auto justify-center">
                                <i class="fas fa-plus mr-2"></i> Tambah Produk
                            </x-button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 lg:sticky lg:top-4">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Pesanan</h2>

                    <!-- Selected Items Warning -->
                    <div x-show="selectedCount === 0" class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-green-500 mr-2"></i>
                            <p class="text-green-700 text-sm">Pilih produk yang ingin di-checkout</p>
                        </div>
                    </div>

                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">
                                Subtotal (<span x-text="selectedQuantity"></span> item):
                            </span>
                            <span class="font-medium" x-text="formatPrice(selectedTotal)"></span>
                        </div>

                        <div class="flex justify-between text-sm" x-show="selectedCount > 0">
                            <span class="text-gray-600">Biaya Pengiriman:</span>
                            <span class="font-medium">Rp 5.000</span>
                        </div>

                        <div class="border-t pt-3" x-show="selectedCount > 0">
                            <div class="flex justify-between text-lg font-bold">
                                <span>Total:</span>
                                <span class="text-green-600" x-text="formatPrice(selectedTotal)"></span>
                            </div>
                        </div>
                    </div>

                    @auth
                        @if(!Auth::user()->hasVerifiedEmail())
                            <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-triangle text-yellow-500 mr-2"></i>
                                    <div>
                                        <p class="text-yellow-800 text-sm font-medium">Email Belum Diverifikasi</p>
                                        <p class="text-yellow-700 text-xs mt-1">Verifikasi email diperlukan untuk checkout</p>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <a href="{{ route('verification.notice') }}"
                                       class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-yellow-800 bg-yellow-100 hover:bg-yellow-200">
                                        <i class="fas fa-envelope-open mr-1"></i>
                                        Verifikasi Sekarang
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endauth

                    <div class="space-y-3">
                        <button @click="proceedToCheckout()"
                                @auth
                                    @if(!Auth::user()->hasVerifiedEmail())
                                        :disabled="true"
                                        class="w-full px-6 py-3 text-white font-semibold rounded-lg bg-gray-400 cursor-not-allowed"
                                    @else
                                        :disabled="selectedCount === 0"
                                        :class="selectedCount === 0 ? 'bg-gray-400 cursor-not-allowed' : 'bg-green-600 hover:bg-green-700'"
                                        class="w-full px-6 py-3 text-white font-semibold rounded-lg transition-colors"
                                    @endif
                                @else
                                    :disabled="selectedCount === 0"
                                    :class="selectedCount === 0 ? 'bg-gray-400 cursor-not-allowed' : 'bg-green-600 hover:bg-green-700'"
                                    class="w-full px-6 py-3 text-white font-semibold rounded-lg transition-colors"
                                @endauth>
                            <i class="fas fa-credit-card mr-2"></i>
                            @auth
                                @if(!Auth::user()->hasVerifiedEmail())
                                    Email Belum Diverifikasi
                                @else
                                    <span x-show="selectedCount === 0">Pilih Produk Dulu</span>
                                    <span x-show="selectedCount > 0">Lanjut ke Checkout</span>
                                @endif
                            @else
                                <span x-show="selectedCount === 0">Pilih Produk Dulu</span>
                                <span x-show="selectedCount > 0">Lanjut ke Checkout</span>
                            @endauth
                        </button>

                        <x-button href="{{ route('products') }}" variant="outline" class="w-full">
                            <i class="fas fa-shopping-bag mr-2"></i> Lanjut Belanja
                        </x-button>
                    </div>

                    <!-- Payment Methods Info -->
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-sm font-semibold text-gray-900 mb-2">Metode Pembayaran:</h3>
                        <div class="space-y-1 text-xs text-gray-600">
                            <div class="flex items-center">
                                <i class="fas fa-wallet mr-2 text-green-500"></i> E-wallet (GoPay, OVO, DANA)
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-qrcode mr-2 text-blue-500"></i> QRIS
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-university mr-2 text-purple-500"></i> Transfer Bank
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-credit-card mr-2 text-orange-500"></i> Midtrans
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Empty Cart -->
        <div class="text-center py-16">
            <div class="bg-white rounded-lg shadow-md p-12 max-w-md mx-auto">
                <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-6"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Keranjang Belanja Kosong</h3>
                <p class="text-gray-600 mb-6">Belum ada produk yang ditambahkan ke keranjang belanja Anda.</p>
                <x-button href="{{ route('products') }}" variant="primary">
                    <i class="fas fa-shopping-bag mr-2"></i> Mulai Belanja
                </x-button>
            </div>
        </div>
    @endif
                    <!-- Mobile checkout bar (visible on small screens when items selected) -->
                    <div x-cloak x-show="selectedCount > 0" class="fixed inset-x-0 bottom-0 z-50 lg:hidden">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                            <div class="bg-white border-t shadow-xl rounded-t-lg p-3 flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="text-sm text-gray-600">Dipilih: <span class="font-medium text-gray-900" x-text="selectedCount"></span></div>
                                    <div class="text-sm text-gray-600">Total: <span class="font-semibold text-green-600" x-text="formatPrice(selectedTotal)"></span></div>
                                </div>
                                <button @click="proceedToCheckout()" :disabled="selectedCount === 0" :class="selectedCount === 0 ? 'bg-gray-400 cursor-not-allowed' : 'bg-green-600 hover:bg-green-700'" class="text-white px-4 py-2 rounded-md font-medium">
                                    Checkout
                                </button>
                            </div>
                        </div>
                    </div>
</div>

<!-- Cart Manager Script -->
<script>
// Cart data from backend
const cartData = {!! json_encode($cartItems->map(function($item) {
    return [
        'id' => $item->id,
        'quantity' => $item->quantity,
        'price' => $item->product->price,
        'subtotal' => $item->product->price * $item->quantity
    ];
})) !!};

function cartManager() {
    return {
        selectedItems: [],
        cartItems: cartData,

        init() {
            console.log('Cart Manager initialized');
            console.log('Cart Items:', this.cartItems);
            // expose for global access from per-item Alpine components
            try { window.cartManager = this; } catch (e) { /* ignore */ }
            // listen for cross-component updates (fallback)
            try {
                window.addEventListener('cart-updated', (e) => {
                    if (e && e.detail) {
                        const { id, quantity, price } = e.detail;
                        this.updateItemData(id, quantity, price);
                    }
                });
            } catch (e) { /* ignore */ }
        },

        get isAllSelected() {
            return this.selectedItems.length === this.cartItems.length && this.cartItems.length > 0;
        },

        get selectedCount() {
            return this.selectedItems.length;
        },

        get selectedTotal() {
            return this.selectedItems.reduce((total, itemId) => {
                const cartItem = this.cartItems.find(item => item.id === itemId);
                return total + (cartItem ? cartItem.subtotal : 0);
            }, 0);
        },

        get selectedQuantity() {
            return this.selectedItems.reduce((total, itemId) => {
                const cartItem = this.cartItems.find(item => item.id === itemId);
                return total + (cartItem ? cartItem.quantity : 0);
            }, 0);
        },

        toggleAllSelection(checked) {
            if (checked) {
                this.selectedItems = this.cartItems.map(item => item.id);
            } else {
                this.selectedItems = [];
            }
        },

        toggleItemSelection(itemId, checked) {
            if (checked) {
                if (!this.selectedItems.includes(itemId)) {
                    this.selectedItems.push(itemId);
                }
            } else {
                const index = this.selectedItems.indexOf(itemId);
                if (index > -1) {
                    this.selectedItems.splice(index, 1);
                }
            }
        },

        updateItemData(itemId, newQuantity, price) {
            const item = this.cartItems.find(item => item.id === itemId);
            if (item) {
                item.quantity = newQuantity;
                item.subtotal = newQuantity * price;
            }
        },

        formatPrice(amount) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
        },

        async proceedToCheckout() {
            if (this.selectedItems.length === 0) return;

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("cart.checkout.selected") }}';

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            this.selectedItems.forEach(itemId => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_items[]';
                input.value = itemId;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        }
    }
}

// Global function to update item data from quantity controls
function updateItemData(itemId, newQuantity, price) {
    // Prefer explicit window reference (set in cartManager.init)
    try {
        if (window.cartManager && typeof window.cartManager.updateItemData === 'function') {
            window.cartManager.updateItemData(itemId, newQuantity, price);
            return;
        }
    } catch (e) {
        // fall through to attempt legacy access
    }

    // Fallback: try to access Alpine component internals (legacy)
    const el = document.querySelector('[x-data*="cartManager"]');
    if (el && el.__x && el.__x.$data && typeof el.__x.$data.updateItemData === 'function') {
        el.__x.$data.updateItemData(itemId, newQuantity, price);
        return;
    }

    console.warn('updateItemData: cartManager not found to apply updates');
}
</script>
@endsection

@push('scripts')
<script>
function confirmAndSubmitForm(form, message) {
    if (!form) return;

    // Create modal overlay
    const modalOverlay = document.createElement('div');
    modalOverlay.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';

    // Create modal content
    modalOverlay.innerHTML = `
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6 transform scale-95 opacity-0 transition-all duration-300" id="modal-content">
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-trash-alt text-3xl text-red-600"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Konfirmasi Penghapusan</h3>
                <p class="text-sm text-gray-600 mb-6">${message}</p>
                <div class="flex space-x-3">
                    <button type="button" class="flex-1 px-4 py-2 rounded-lg border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition-colors modal-cancel">
                        Batal
                    </button>
                    <button type="button" class="flex-1 px-4 py-2 rounded-lg bg-red-600 text-white font-medium hover:bg-red-700 transition-colors modal-confirm">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    `;

    document.body.appendChild(modalOverlay);

    // Animate modal in
    setTimeout(() => {
        const modalContent = modalOverlay.querySelector('#modal-content');
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);

    // Handle cancel
    modalOverlay.querySelector('.modal-cancel').addEventListener('click', () => {
        const modalContent = modalOverlay.querySelector('#modal-content');
        modalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modalOverlay.remove(), 300);
    });

    // Handle confirm
    modalOverlay.querySelector('.modal-confirm').addEventListener('click', () => {
        const modalContent = modalOverlay.querySelector('#modal-content');
        modalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modalOverlay.remove();
            if (window.loadingOverlay && typeof window.loadingOverlay.show === 'function') {
                window.loadingOverlay.show('Memproses aksi...');
            }
            form.submit();
        }, 300);
    });

    // Close on overlay click
    modalOverlay.addEventListener('click', (e) => {
        if (e.target === modalOverlay) {
            const modalContent = modalOverlay.querySelector('#modal-content');
            modalContent.classList.add('scale-95', 'opacity-0');
            setTimeout(() => modalOverlay.remove(), 300);
        }
    });
}
</script>
@endpush
