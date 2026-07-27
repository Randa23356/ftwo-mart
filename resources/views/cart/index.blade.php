@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="cartManager()">
    <!-- Header -->
    <div class="mb-4 sm:mb-8">
        <h1 class="text-xl sm:text-3xl font-bold text-gray-900 mb-1 sm:mb-2">Keranjang Belanja</h1>
        <p class="text-sm sm:text-gray-600">Kelola produk yang ingin Anda beli</p>
    </div>

    @if($cartItems->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-b">
                        <div class="flex items-center justify-between gap-3">
                            <h2 class="text-sm sm:text-lg font-semibold text-gray-900">Keranjang ({{ $cartItems->count() }})</h2>
                            <div class="flex items-center gap-3">
                                <label class="flex items-center text-xs sm:text-sm text-gray-600 cursor-pointer">
                                    <input type="checkbox"
                                           @change="toggleAllSelection($event.target.checked)"
                                           :checked="isAllSelected"
                                           class="mr-1.5 sm:mr-2 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                    <span x-text="isAllSelected ? 'Batal' : 'Pilih Semua'" class="hidden sm:inline"></span>
                                    <span x-text="isAllSelected ? '✕' : '✓'" class="sm:hidden"></span>
                                </label>
                                <span class="text-xs sm:text-sm text-gray-500">
                                    <span x-text="selectedCount"></span>/{{ $cartItems->count() }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        @foreach($cartItems as $cartItem)
                            <div class="p-3 sm:p-4 bg-white rounded-lg border border-gray-100 hover:border-gray-200 transition-colors">
                                <!-- Top Row: Checkbox + Image + Name + Remove -->
                                <div class="flex items-start gap-3">
                                    <!-- Selection Checkbox -->
                                    <input type="checkbox"
                                           @change="toggleItemSelection({{ $cartItem->id }}, $event.target.checked)"
                                           :checked="selectedItems.includes({{ $cartItem->id }})"
                                           class="mt-1 w-4 h-4 sm:w-5 sm:h-5 text-green-600 focus:ring-green-500 border-gray-300 rounded flex-shrink-0">

                                    <!-- Product Image -->
                                    <img src="{{ $cartItem->product->image_url }}" alt="{{ $cartItem->product->name }}"
                                         class="w-16 h-16 sm:w-20 sm:h-20 object-cover rounded-lg flex-shrink-0">

                                    <!-- Product Info + Price -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-2">
                                            <div class="min-w-0">
                                                <h3 class="text-sm sm:text-base font-semibold text-gray-900 truncate">{{ $cartItem->product->name }}</h3>
                                                <p class="text-[10px] sm:text-xs text-green-600 font-medium mt-0.5">{{ $cartItem->product->category->name }}</p>
                                            </div>
                                            <button type="button"
                                                    onclick="confirmAndSubmitForm(document.getElementById('remove-form-{{ $cartItem->id }}'), {{ Illuminate\Support\Js::from('Apakah Anda yakin ingin menghapus ' . $cartItem->product->name . ' dari keranjang?') }})"
                                                    class="text-gray-400 hover:text-red-600 transition-colors p-1 rounded flex-shrink-0">
                                                <i class="fas fa-trash text-xs sm:text-sm"></i>
                                            </button>
                                        </div>
                                        <form id="remove-form-{{ $cartItem->id }}" method="POST" action="{{ route('cart.remove', $cartItem) }}" class="hidden">
                                            @csrf @method('DELETE')
                                        </form>

                                        <!-- Price -->
                                        <p class="text-base sm:text-lg font-bold text-green-700 mt-1">{{ $cartItem->unit_price ? 'Rp ' . number_format($cartItem->unit_price, 0, ',', '.') : $cartItem->product->formatted_price }}</p>
                                    </div>
                                </div>

                                <!-- Variant + Quantity Row -->
                                <div class="mt-3 ml-7 sm:ml-[5.5rem] space-y-2.5">
                                    @php
                                        $cartVariants = is_array($cartItem->selected_variants) ? $cartItem->selected_variants : [];
                                        $productVariants = is_array($cartItem->product->variant_options) ? $cartItem->product->variant_options : [];
                                    @endphp
                                    <script type="application/json" id="cart-variants-{{ $cartItem->id }}">@json($cartVariants)</script>
                                    <div class="space-y-2.5"
                                         x-data="{
                                             id: {{ $cartItem->id }},
                                             price: {{ $cartItem->unit_price ?? $cartItem->product->price }},
                                             quantity: {{ $cartItem->quantity }},
                                             initialQuantity: {{ $cartItem->quantity }},
                                             updating: false,
                                             selectedVariants: {},
                                             init() {
                                                 const el = document.getElementById('cart-variants-' + this.id);
                                                 if (el) {
                                                     try { this.selectedVariants = JSON.parse(el.textContent); } catch(e) { this.selectedVariants = {}; }
                                                 }
                                             },
                                             updateLocal() {
                                                 try { updateItemData(this.id, this.quantity, this.price); } catch (e) {}
                                             },
                                              async updateVariant() {
                                                  this.updating = true;
                                                  try {
                                                      const response = await fetch('{{ url("cart/".$cartItem->id."/update") }}', {
                                                          method: 'POST',
                                                          headers: {
                                                              'Content-Type': 'application/json',
                                                              'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                              'X-HTTP-Method-Override': 'PUT'
                                                          },
                                                          body: JSON.stringify({ quantity: this.quantity, selected_variants: this.selectedVariants })
                                                      });
                                                      if (response.ok) {
                                                          const data = await response.json();
                                                          if (data.unit_price) this.price = data.unit_price;
                                                          window.dispatchEvent(new CustomEvent('cart-updated', { detail: { id: this.id, quantity: this.quantity, price: this.price } }));
                                                      } else {
                                                          location.reload();
                                                      }
                                                  } catch (error) {
                                                      location.reload();
                                                  } finally {
                                                      this.updating = false;
                                                  }
                                              },
                                             async updateQuantity() {
                                                 if (!(this.quantity && !isNaN(this.quantity) && this.quantity > 0)) return;
                                                 const prev = this.initialQuantity;
                                                 updateItemData(this.id, this.quantity, this.price);
                                                 this.updating = true;
                                                 try {
                                                     const response = await fetch('{{ url("cart/".$cartItem->id."/update") }}', {
                                                         method: 'POST',
                                                         headers: {
                                                             'Content-Type': 'application/json',
                                                             'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                             'X-HTTP-Method-Override': 'PUT'
                                                         },
                                                         body: JSON.stringify({ quantity: this.quantity, selected_variants: this.selectedVariants })
                                                     });
                                                     if (response.ok) {
                                                         this.initialQuantity = this.quantity;
                                                         try { updateItemData(this.id, this.quantity, this.price); } catch (e) {}
                                                         try { window.dispatchEvent(new CustomEvent('cart-updated', { detail: { id: this.id, quantity: this.quantity, price: this.price } })); } catch (e) {}
                                                     } else {
                                                         updateItemData(this.id, prev, this.price);
                                                         this.quantity = prev;
                                                     }
                                                 } catch (error) {
                                                     updateItemData(this.id, prev, this.price);
                                                     this.quantity = prev;
                                                 } finally {
                                                     this.updating = false;
                                                 }
                                             }
                                         }">
                                        @if(count($cartVariants) > 0)
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($cartVariants as $label => $value)
                                            <div class="inline-flex items-center gap-1.5">
                                                <label class="text-[10px] sm:text-xs text-gray-500 whitespace-nowrap">{{ $label }}:</label>
                                                <select @change="selectedVariants['{{ $label }}'] = $event.target.value; updateVariant()"
                                                    class="text-xs border border-gray-200 rounded-md px-2 py-1 focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-gray-50">
                                                    @foreach($productVariants[$label] ?? [] as $opt)
                                                        <option value="{{ $opt }}" {{ $value === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @endforeach
                                        </div>
                                        @endif
                                        <!-- Quantity Controls -->
                                        <div class="flex flex-wrap items-center justify-between gap-2">
                                        <div class="flex items-center gap-2">
                                            <div class="flex items-center border border-gray-200 rounded-md bg-gray-50">
                                                <button type="button" @click="quantity = Math.max(1, quantity - 1); updateLocal()"
                                                    class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center text-gray-500 hover:bg-gray-100 rounded-l-md transition-colors">
                                                    <i class="fas fa-minus text-[10px]"></i>
                                                </button>
                                                <input type="number" x-model.number="quantity" @input="updateLocal()" min="1" max="{{ $cartItem->stock }}"
                                                    class="w-10 sm:w-12 text-center border-0 bg-transparent text-sm font-medium focus:ring-0 [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none">
                                                <button type="button" @click="quantity = Math.min({{ $cartItem->stock }}, quantity + 1); updateLocal()"
                                                    class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center text-gray-500 hover:bg-gray-100 rounded-r-md transition-colors">
                                                    <i class="fas fa-plus text-[10px]"></i>
                                                </button>
                                            </div>

                                            @if($cartItem->stock < $cartItem->quantity)
                                                <span class="text-[10px] text-red-500">Stok: {{ $cartItem->stock }}</span>
                                            @endif

                                            <button type="button" @click="updateLocal(); updateQuantity()"
                                                x-show="quantity !== initialQuantity"
                                                :disabled="updating"
                                                class="text-xs text-green-600 hover:text-green-700 font-medium underline transition-colors ml-1">
                                                Simpan
                                            </button>
                                        </div>

                                        <!-- Subtotal -->
                                        <div class="text-right">
                                            <p class="text-sm sm:text-base font-bold text-gray-900" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(quantity * price)"></p>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Cart Actions -->
                    <div class="px-3 sm:px-6 py-3 sm:py-4 bg-gray-50 border-t">
                        <div class="flex items-center justify-between gap-3">
                            <form method="POST" action="{{ route('cart.clear') }}">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                        onclick="confirmAndSubmitForm(this.closest('form'), {{ Illuminate\Support\Js::from('Apakah Anda yakin ingin mengosongkan semua produk dari keranjang?') }})"
                                        class="text-xs sm:text-sm text-red-600 hover:text-red-700 font-medium transition-colors">
                                    <i class="fas fa-trash mr-1"></i> Hapus Semua
                                </button>
                            </form>

                            <a href="{{ route('products') }}" class="text-xs sm:text-sm text-green-600 hover:text-green-700 font-medium transition-colors">
                                <i class="fas fa-plus mr-1"></i> Tambah Produk
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 lg:sticky lg:top-4">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">Ringkasan Pesanan</h2>

                    <!-- Selected Items Warning -->
                    <div x-show="selectedCount === 0" class="mb-3 sm:mb-4 p-2.5 sm:p-3 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-green-500 mr-2 text-sm"></i>
                            <p class="text-green-700 text-xs sm:text-sm">Pilih produk yang ingin di-checkout</p>
                        </div>
                    </div>

                    <div class="space-y-2 sm:space-y-3 mb-4 sm:mb-6">
                        <div class="flex justify-between text-xs sm:text-sm">
                            <span class="text-gray-600">
                                Subtotal (<span x-text="selectedQuantity"></span> item)
                            </span>
                            <span class="font-medium" x-text="formatPrice(selectedTotal)"></span>
                        </div>

                        <div class="border-t pt-2 sm:pt-3" x-show="selectedCount > 0">
                            <div class="flex justify-between text-base sm:text-lg font-bold">
                                <span>Total</span>
                                <span class="text-green-600" x-text="formatPrice(selectedTotal)"></span>
                            </div>
                        </div>
                    </div>

                    @auth
                        @if(!Auth::user()->hasVerifiedEmail())
                            <div class="mb-3 sm:mb-4 p-2.5 sm:p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-triangle text-yellow-500 mr-2 text-sm"></i>
                                    <div>
                                        <p class="text-yellow-800 text-xs sm:text-sm font-medium">Email Belum Diverifikasi</p>
                                        <p class="text-yellow-700 text-[10px] sm:text-xs mt-0.5">Verifikasi email diperlukan untuk checkout</p>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <a href="{{ route('verification.notice') }}"
                                       class="inline-flex items-center px-2 py-1 border border-transparent text-[10px] sm:text-xs font-medium rounded text-yellow-800 bg-yellow-100 hover:bg-yellow-200">
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
                    <div class="mt-4 sm:mt-6 p-3 sm:p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-xs sm:text-sm font-semibold text-gray-900 mb-2">Metode Pembayaran</h3>
                        <div class="grid grid-cols-2 gap-1.5 sm:space-y-1 text-[10px] sm:text-xs text-gray-600">
                            <div class="flex items-center">
                                <i class="fas fa-wallet mr-1.5 sm:mr-2 text-green-500"></i> E-wallet
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-qrcode mr-1.5 sm:mr-2 text-blue-500"></i> QRIS
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-university mr-1.5 sm:mr-2 text-purple-500"></i> Transfer Bank
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-credit-card mr-1.5 sm:mr-2 text-orange-500"></i> Midtrans
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Empty Cart -->
        <div class="text-center py-16 sm:py-24">
            <div class="max-w-md mx-auto">
                {{-- Illustration --}}
                <div class="relative w-40 h-40 mx-auto mb-8">
                    <div class="absolute inset-0 bg-gradient-to-br from-green-100 to-emerald-50 rounded-full animate-pulse"></div>
                    <div class="absolute inset-2 bg-white rounded-full flex items-center justify-center shadow-lg">
                        <i class="fas fa-shopping-cart text-6xl text-green-200"></i>
                    </div>
                    <div class="absolute -top-1 -right-1 w-10 h-10 bg-white rounded-full shadow-md flex items-center justify-center">
                        <i class="fas fa-plus text-green-500 text-sm"></i>
                    </div>
                </div>

                {{-- Title --}}
                <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-3">Keranjang Masih Kosong</h3>
                <p class="text-sm sm:text-base text-gray-500 mb-8 max-w-xs mx-auto leading-relaxed">
                    Yuk, mulai jelajahi koleksi produk kami dan temukan yang kamu suka!
                </p>

                {{-- CTA --}}
                <a href="{{ route('products') }}"
                   class="inline-flex items-center gap-2 px-8 py-3.5 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-xl text-sm font-semibold shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
                    <i class="fas fa-shopping-bag"></i> Mulai Belanja
                </a>

                {{-- Features --}}
                <div class="grid grid-cols-3 gap-4 mt-12 pt-8 border-t border-gray-100">
                    <div class="text-center">
                        <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-truck text-green-600 text-sm"></i>
                        </div>
                        <p class="text-xs font-medium text-gray-600">Pengiriman Cepat</p>
                    </div>
                    <div class="text-center">
                        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-shield-alt text-blue-600 text-sm"></i>
                        </div>
                        <p class="text-xs font-medium text-gray-600">Pembayaran Aman</p>
                    </div>
                    <div class="text-center">
                        <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-undo text-amber-600 text-sm"></i>
                        </div>
                        <p class="text-xs font-medium text-gray-600">Garansi Kembali</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
                    <!-- Mobile checkout bar -->
                    <div x-cloak x-show="selectedCount > 0" class="fixed inset-x-0 bottom-0 z-50 lg:hidden">
                        <div class="bg-white border-t shadow-lg px-4 py-3 pb-[env(safe-area-inset-bottom)] flex items-center justify-between gap-3">
                            <div class="flex flex-col min-w-0">
                                <span class="text-xs text-gray-500"><span x-text="selectedCount"></span> dipilih</span>
                                <span class="text-sm font-bold text-green-600 truncate" x-text="formatPrice(selectedTotal)"></span>
                            </div>
                            <button @click="proceedToCheckout()" :disabled="selectedCount === 0" :class="selectedCount === 0 ? 'bg-gray-400 cursor-not-allowed' : 'bg-green-600 hover:bg-green-700'" class="text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-colors flex-shrink-0">
                                Checkout
                            </button>
                        </div>
                    </div>
</div>

<!-- Cart Manager Script -->
<script>
// Cart data from backend
const cartData = {!! json_encode($cartItems->map(function($item) {
    $price = $item->unit_price ?? $item->product->price;
    return [
        'id' => $item->id,
        'quantity' => $item->quantity,
        'price' => (float) $price,
        'subtotal' => (float) $price * $item->quantity
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
