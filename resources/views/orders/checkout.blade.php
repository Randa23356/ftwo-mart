@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Checkout</h1>

        @if(session()->has('buy_now_item'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-bolt text-green-600 mr-2"></i>
                    <p class="text-green-800 font-medium">Pemesanan Langsung</p>
                </div>
                <p class="text-green-700 text-sm mt-1">Produk akan langsung dipesan tanpa masuk ke keranjang</p>
            </div>
        @elseif(session()->has('selected_cart_items'))
            <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-square text-blue-600 mr-2"></i>
                    <p class="text-blue-800 font-medium">Checkout Produk Terpilih</p>
                </div>
                <p class="text-blue-700 text-sm mt-1">{{ count(session('selected_cart_items')) }} produk dipilih dari keranjang untuk checkout</p>
            </div>
        @else
            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-shopping-cart text-green-600 mr-2"></i>
                    <p class="text-green-800 font-medium">Checkout Semua Produk</p>
                </div>
                <p class="text-green-700 text-sm mt-1">Semua produk di keranjang akan di-checkout</p>
            </div>
        @endif

        <p class="text-gray-600">Lengkapi informasi pemesanan Anda</p>
    </div>

    @auth
        @if(!Auth::user()->hasVerifiedEmail())
            <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">
                            Email Belum Diverifikasi
                        </h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Anda perlu memverifikasi email terlebih dahulu untuk melanjutkan pemesanan. Ini diperlukan untuk mengirimkan konfirmasi pesanan dan update status pengiriman.</p>
                            <div class="mt-3">
                                <a href="{{ route('verification.notice') }}"
                                   class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-yellow-800 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                    <i class="fas fa-envelope-open mr-2"></i>
                                    Verifikasi Email Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endauth

    @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center mb-2">
                <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                <h3 class="text-red-800 font-medium">Terjadi kesalahan:</h3>
            </div>
            <ul class="text-red-700 text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fas fa-times-circle text-red-600 mr-2"></i>
                <p class="text-red-800">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('orders.store') }}" x-data="{ paymentMethod: 'midtrans' }" id="checkout-form">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Order Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Informasi Pengiriman</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-input
                            id="delivery_name"
                            name="delivery_name"
                            value="{{ auth()->user()->name }}"
                            label="Nama Penerima"
                            required
                        />

                        <x-input
                            id="delivery_phone"
                            name="delivery_phone"
                            type="tel"
                            value="{{ auth()->user()->phone }}"
                            label="No. Telepon"
                            required
                        />
                    </div>

                    <div class="mt-4">
                        <x-textarea
                            id="delivery_address"
                            name="delivery_address"
                            label="Alamat Pengiriman"
                            required
                        >{{ auth()->user()->address }}</x-textarea>
                    </div>

                    <div class="mt-4">
                        <x-textarea
                            id="notes"
                            name="notes"
                            label="Catatan (Opsional)"
                            placeholder="Contoh: Tolong dibungkus rapi, pengiriman pagi hari"
                            rows="2"
                        ></x-textarea>
                    </div>
                </div>

                <!-- Shipping Calculator - Shopee Style -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6" x-data="shippingCalculator()">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-orange-500 to-red-500 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white flex items-center">
                            <i class="fas fa-shipping-fast mr-3"></i>
                            Pilih Alamat & Metode Pengiriman
                        </h3>
                        <p class="text-orange-100 text-sm mt-1">Powered by BinderByte - Estimasi ongkir Indonesia</p>
                    </div>

                    <div class="p-6">
                        <!-- Origin Info -->
                        <div class="mb-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-store text-white"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-blue-900">Dikirim dari</p>
                                        <p class="text-blue-800 font-semibold">Mataram, Lombok (NTB)</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-blue-700">Berat Total</p>
                                    <p class="font-bold text-blue-900">{{ number_format($totalWeight ?? 500) }}g</p>
                                </div>
                            </div>
                        </div>

                        <!-- City Selection - Shopee Style -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-800 mb-3">
                                <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                                Alamat Pengiriman
                            </label>

                            <div class="relative">
                                <div class="relative">
                                    <input
                                        type="text"
                                        x-model="citySearch"
                                        @input="searchCities()"
                                        @focus="showCityDropdown = true"
                                        placeholder="Cari kota tujuan (Jakarta, Surabaya, Bandung...)"
                                        class="w-full px-4 py-4 pr-12 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 text-gray-800 placeholder-gray-500"
                                    >
                                    <div class="absolute right-4 top-1/2 transform -translate-y-1/2">
                                        <i class="fas fa-search text-gray-400"></i>
                                    </div>
                                </div>

                                <!-- City Dropdown - Enhanced -->
                                <div x-show="showCityDropdown && cities.length > 0"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-100 transform scale-100"
                                     @click.away="showCityDropdown = false"
                                     class="absolute z-20 w-full mt-2 bg-white border border-gray-200 rounded-xl shadow-xl max-h-64 overflow-y-auto">
                                    <template x-for="city in cities" :key="city.city_id">
                                        <div @click="selectCity(city)"
                                             class="px-4 py-3 hover:bg-orange-50 cursor-pointer border-b border-gray-100 last:border-b-0 transition-colors duration-150">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                                                    <i class="fas fa-map-marker-alt text-gray-500 text-sm"></i>
                                                </div>
                                                <div>
                                                    <div class="font-medium text-gray-900" x-text="city.type + ' ' + city.city_name"></div>
                                                    <div class="text-sm text-gray-500" x-text="city.province + ' • ' + city.postal_code"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Selected City Display - Shopee Style -->
                            <div x-show="selectedCity"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 transform translate-y-2"
                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                 class="mt-4 p-4 bg-green-50 border-2 border-green-200 rounded-xl">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-check text-white"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-green-800">Alamat Tujuan</p>
                                        <p class="font-semibold text-green-900" x-text="selectedCity ? selectedCity.type + ' ' + selectedCity.city_name + ', ' + selectedCity.province : ''"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Calculate Button - Shopee Style -->
                        <div class="mb-6">
                            <button
                                type="button"
                                @click="calculateShipping()"
                                :disabled="!selectedCity || calculating"
                                :class="selectedCity && !calculating ? 'bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 shadow-lg' : 'bg-gray-300 cursor-not-allowed'"
                                class="w-full text-white font-bold py-4 px-6 rounded-xl transition-all duration-200 flex items-center justify-center transform hover:scale-105"
                            >
                                <template x-if="calculating">
                                    <div class="flex items-center">
                                        <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-3"></div>
                                        <span>Menghitung Ongkir Real-time...</span>
                                    </div>
                                </template>
                                <template x-if="!calculating">
                                    <div class="flex items-center">
                                        <i class="fas fa-calculator mr-3"></i>
                                        <span>Hitung Ongkos Kirim</span>
                                    </div>
                                </template>
                            </button>
                        </div>

                        <!-- Shipping Options - Shopee Style -->
                        <div x-show="shippingOptions.length > 0"
                             x-transition:enter="transition ease-out duration-500"
                             x-transition:enter-start="opacity-0 transform translate-y-4"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             class="space-y-3">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-bold text-gray-900 text-lg">
                                    <i class="fas fa-truck text-orange-500 mr-2"></i>
                                    Pilih Kurir Pengiriman
                                </h4>
                                <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full" x-text="shippingOptions.length + ' opsi tersedia'"></span>
                            </div>

                            <template x-for="(option, index) in shippingOptions" :key="index">
                                <label class="block">
                                    <input
                                        type="radio"
                                        name="shipping_option"
                                        :value="index"
                                        @change="selectShippingOption(option)"
                                        class="sr-only"
                                    >
                                    <div :class="selectedShipping && selectedShipping.courier_code === option.courier_code && selectedShipping.service_code === option.service_code ? 'border-orange-500 bg-orange-50 shadow-md transform scale-105' : 'border-gray-200 hover:border-orange-300 hover:shadow-sm'"
                                         class="border-2 rounded-xl p-4 cursor-pointer transition-all duration-200">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <!-- Courier Logo/Icon -->
                                                <div class="w-14 h-14 rounded-xl flex items-center justify-center mr-4 shadow-sm"
                                                     :class="getCourierColor(option.courier_code)">
                                                    <span class="text-white font-bold text-sm" x-text="option.courier_code.toUpperCase()"></span>
                                                </div>
                                                <div>
                                                    <div class="font-bold text-gray-900 text-lg">
                                                        <span x-text="option.courier_name"></span>
                                                    </div>
                                                    <div class="text-sm text-gray-600 mb-1" x-text="option.service_name"></div>
                                                    <div class="flex items-center text-xs text-gray-500">
                                                        <i class="fas fa-clock mr-1"></i>
                                                        <span x-text="option.etd"></span> hari
                                                        <span class="mx-2">•</span>
                                                        <span class="text-green-600 font-medium" x-text="option.note || 'Real-time rate'"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-xl font-bold text-orange-600">
                                                    Rp<span x-text="formatNumber(option.cost)"></span>
                                                </div>
                                                <div class="text-xs text-gray-500">per {{ number_format($totalWeight ?? 500) }}g</div>
                                            </div>
                                        </div>

                                        <!-- Selected Indicator -->
                                        <div x-show="selectedShipping && selectedShipping.courier_code === option.courier_code && selectedShipping.service_code === option.service_code"
                                             class="mt-3 pt-3 border-t border-orange-200">
                                            <div class="flex items-center text-orange-600">
                                                <i class="fas fa-check-circle mr-2"></i>
                                                <span class="text-sm font-medium">Kurir terpilih</span>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </template>
                        </div>

                        <!-- No Shipping Options - Enhanced -->
                        <div x-show="calculated && shippingOptions.length === 0" class="text-center py-12">
                            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-exclamation-triangle text-3xl text-green-500"></i>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-800 mb-2">Tidak Ada Opsi Pengiriman</h4>
                            <p class="text-gray-600 mb-4">Maaf, tidak ada kurir yang melayani ke kota ini</p>
                            <button @click="selectedCity = null; citySearch = ''; calculated = false"
                                    class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                                <i class="fas fa-search mr-2"></i>Pilih Kota Lain
                            </button>
                        </div>

                        <!-- Error Message - Enhanced -->
                        <div x-show="errorMessage"
                             x-transition
                             class="mt-4 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-exclamation text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-red-800">Terjadi Kesalahan</p>
                                    <p class="text-red-700 text-sm" x-text="errorMessage"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden fields for shipping data -->
                        <input type="hidden" id="shipping_courier" name="shipping_courier" :value="selectedShipping?.courier_code || ''">
                        <input type="hidden" id="shipping_service" name="shipping_service" :value="selectedShipping?.service_code || ''">
                        <input type="hidden" id="shipping_cost" name="shipping_cost" :value="selectedShipping?.cost || 0">
                        <input type="hidden" id="shipping_etd" name="shipping_etd" :value="selectedShipping?.etd || ''">
                        <input type="hidden" id="destination_city_id" name="destination_city_id" :value="selectedCity?.city_id || ''">
                        <input type="hidden" id="destination_province" name="destination_province" :value="selectedCity?.province || ''">
                        <input type="hidden" id="destination_city" name="destination_city" :value="selectedCity ? selectedCity.type + ' ' + selectedCity.city_name : ''">
                        <input type="hidden" id="total_weight" name="total_weight" value="{{ $totalWeight ?? 500 }}">
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Metode Pembayaran</h2>

                    <div class="space-y-3">
                        <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="midtrans" x-model="paymentMethod"
                                   class="text-green-600 focus:ring-green-500" checked>
                            <div class="ml-3">
                                <div class="flex items-center">
                                    <i class="fas fa-credit-card text-green-600 mr-2"></i>
                                    <span class="font-medium text-gray-900">Transfer (E-wallet, QRIS, Bank Transfer)</span>
                                </div>
                                <p class="text-sm text-gray-500">Pembayaran aman dengan berbagai metode</p>
                            </div>
                        </label>

                        <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="cod" x-model="paymentMethod"
                                   class="text-green-600 focus:ring-green-500">
                            <div class="ml-3">
                                <div class="flex items-center">
                                    <i class="fas fa-money-bill-wave text-green-600 mr-2"></i>
                                    <span class="font-medium text-gray-900">Cash on Delivery (COD)</span>
                                </div>
                                <p class="text-sm text-gray-500">Bayar saat barang diterima</p>
                            </div>
                        </label>
                    </div>

                    <!-- Payment Info -->
                    <div x-show="paymentMethod === 'midtrans'" class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <h4 class="font-medium text-green-800 mb-2">Metode Pembayaran Transfer:</h4>
                        <div class="grid grid-cols-2 gap-2 text-sm text-green-700">
                            <div class="flex items-center">
                                <i class="fas fa-wallet mr-2 text-green-500"></i> E-wallet
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-qrcode mr-2 text-blue-500"></i> QRIS
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-university mr-2 text-purple-500"></i> Bank Transfer
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-credit-card mr-2 text-orange-500"></i> Credit Card
                            </div>
                        </div>
                    </div>

                    <div x-show="paymentMethod === 'cod'" class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <h4 class="font-medium text-green-800 mb-2">Informasi COD:</h4>
                        <p class="text-sm text-green-700">Pembayaran dilakukan saat barang diterima. Pastikan Anda memiliki uang tunai yang cukup.</p>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Pesanan</h2>

                    <!-- Cart Items -->
                    <div class="space-y-3 mb-6">
                        @if($cartItems->isEmpty())
                            <div class="text-center py-8">
                                <i class="fas fa-shopping-cart text-4xl text-gray-400 mb-4"></i>
                                <p class="text-gray-500">Tidak ada item untuk checkout</p>
                                <a href="{{ route('products') }}" class="text-green-600 hover:text-green-700 mt-2 inline-block">
                                    <i class="fas fa-arrow-left mr-1"></i> Belanja Sekarang
                                </a>
                            </div>
                        @else
                            @foreach($cartItems as $cartItem)
                            <div class="flex items-center space-x-3">
                                <img src="{{ $cartItem->product->image_url }}" alt="{{ $cartItem->product->name }}"
                                     class="w-12 h-12 object-cover rounded">
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-medium text-gray-900 truncate">{{ $cartItem->product->name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $cartItem->quantity }} × {{ $cartItem->product->formatted_price }}</p>
                                </div>
                                <span class="font-medium text-gray-900">{{ $cartItem->formatted_subtotal }}</span>
                            </div>
                            @endforeach
                        @endif

                        <!-- Shipping Cost -->
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <div>
                                <p class="font-medium text-gray-900">Biaya Pengiriman</p>
                            </div>
                            <span class="font-medium text-gray-900" id="shipping-cost-summary">Rp 0</span>
                        </div>
                    </div>

                    <!-- Price Breakdown -->
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal ({{ $cartItems->sum('quantity') }} item):</span>
                            <span class="font-medium">{{ 'Rp ' . number_format($total, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Biaya Pengiriman:</span>
                            <span class="font-medium" id="shipping-cost-display">Rp 0</span>
                        </div>

                        <div class="border-t pt-3">
                            <div class="flex justify-between text-lg font-bold">
                                <span>Total:</span>
                                <span class="text-green-600" id="total-cost-display">{{ 'Rp ' . number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Terms -->
                    <div class="mb-6">
                        <label class="flex items-start">
                            <input type="checkbox" required
                                   class="mt-1 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-600">
                                Saya setuju dengan <a href="#" class="text-green-600 hover:text-green-700">syarat dan ketentuan</a> yang berlaku
                            </span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    @if($cartItems->isEmpty())
                        <button type="button" disabled class="w-full bg-gray-400 text-white font-bold py-3 px-6 rounded-lg cursor-not-allowed flex items-center justify-center">
                            <i class="fas fa-ban mr-2"></i> Tidak Ada Item
                        </button>
                    @else
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center">
                            <i class="fas fa-lock mr-2"></i> Buat Pesanan
                        </button>
                    @endif

                    <!-- Action Buttons Based on Flow -->
                    @if(session()->has('buy_now_item'))
                        <!-- Cancel Buy Now Button -->
                        <div class="mt-6 text-center">
                            <div class="relative">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t border-gray-200"></div>
                                </div>
                                <div class="relative flex justify-center text-xs">
                                    <span class="bg-white px-3 text-gray-500">atau</span>
                                </div>
                            </div>
                            <div class="mt-4 space-y-2">
                                <button type="button" onclick="cancelBuyNow()"
                                        class="w-full px-4 py-3 text-red-600 bg-red-50 border-2 border-red-200 rounded-lg text-sm font-semibold hover:bg-red-100 hover:border-red-300 hover:text-red-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-200 focus:ring-offset-2">
                                    <i class="fas fa-times-circle mr-2"></i> Batalkan
                                </button>
                                <p class="text-xs text-gray-500">Klik batalkan jika ingin batalkan proses checkout</p>
                            </div>
                        </div>
                    @elseif(session()->has('selected_cart_items'))
                        <!-- Cancel Selected Items -->
                        <div class="mt-6 text-center">
                            <div class="relative">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t border-gray-200"></div>
                                </div>
                                <div class="relative flex justify-center text-xs">
                                    <span class="bg-white px-3 text-gray-500">atau</span>
                                </div>
                            </div>
                            <div class="mt-4 space-y-2">
                                <button type="button" onclick="cancelSelectedItems()"
                                        class="w-full px-4 py-3 text-blue-600 bg-blue-50 border-2 border-blue-200 rounded-lg text-sm font-semibold hover:bg-blue-100 hover:border-blue-300 hover:text-blue-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-200 focus:ring-offset-2">
                                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Keranjang
                                </button>
                                <p class="text-xs text-gray-500">Pilih ulang produk yang akan di-checkout</p>
                            </div>
                        </div>
                    @else
                        <!-- Back to Cart -->
                        <div class="mt-6 text-center">
                            <div class="relative">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t border-gray-200"></div>
                                </div>
                                <div class="relative flex justify-center text-xs">
                                    <span class="bg-white px-3 text-gray-500">atau</span>
                                </div>
                            </div>
                            <div class="mt-4">
                                <x-button href="{{ route('cart.index') }}" variant="outline" size="sm">
                                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Keranjang
                                </x-button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </form>

    <!-- Hidden Cancel Forms -->
    @if(session()->has('buy_now_item'))
        <form id="cancel-buy-now-form" action="{{ route('buy_now.cancel') }}" method="POST" style="display: none;">
            @csrf
        </form>
    @endif

    @if(session()->has('selected_cart_items'))
        <form id="cancel-selected-form" action="{{ route('cart.index') }}" method="GET" style="display: none;">
        </form>
    @endif

</div>

<!-- Loading Overlay -->
<div x-data="{ loading: false }" x-show="loading"
     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 text-center">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-600 mx-auto mb-4"></div>
        <p class="text-gray-700">Memproses pesanan...</p>
    </div>
</div>

<script>
// Only show loading for the main checkout form, not the cancel form
document.querySelector('form[action="{{ route('orders.store') }}"]').addEventListener('submit', function(e) {
    console.log('Form submission started');

    // Basic form validation
    const requiredFields = ['delivery_name', 'delivery_phone', 'delivery_address'];
    let isValid = true;

    requiredFields.forEach(fieldName => {
        const field = document.querySelector(`[name="${fieldName}"]`);
        if (!field || !field.value.trim()) {
            console.error(`Field ${fieldName} is empty`);
            isValid = false;
        }
    });

    if (!isValid) {
        e.preventDefault();
        alert('Mohon lengkapi semua field yang wajib diisi');
        return false;
    }

    // Show loading overlay
    const loadingElement = document.querySelector('[x-data]');
    if (loadingElement && loadingElement.__x) {
        loadingElement.__x.$data.loading = true;
    }

    console.log('Form validation passed, submitting...');
});

// Shipping Calculator Alpine.js Component
function shippingCalculator() {
    return {
        citySearch: '',
        cities: [],
        selectedCity: null,
        showCityDropdown: false,
        shippingOptions: [],
        selectedShipping: null,
        calculating: false,
        calculated: false,
        errorMessage: '',
        searchTimeout: null,

        init() {
            // Initialize shipping cost display
            this.updateTotalCost(0);
        },

        searchCities() {
            clearTimeout(this.searchTimeout);

            if (this.citySearch.length < 2) {
                this.cities = [];
                this.showCityDropdown = false;
                return;
            }

            this.searchTimeout = setTimeout(() => {
                fetch(`{{ route('shipping.search.cities') }}?q=${encodeURIComponent(this.citySearch)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.cities = data.data;
                            this.showCityDropdown = true;
                        }
                    })
                    .catch(error => {
                        console.error('Error searching cities:', error);
                    });
            }, 300);
        },

        selectCity(city) {
            this.selectedCity = city;
            this.citySearch = `${city.type} ${city.city_name}, ${city.province}`;
            this.showCityDropdown = false;
            this.shippingOptions = [];
            this.selectedShipping = null;
            this.calculated = false;
            this.errorMessage = '';
            this.updateTotalCost(0);
        },

        calculateShipping() {
            if (!this.selectedCity) return;

            this.calculating = true;
            this.errorMessage = '';

            const formData = new FormData();
            formData.append('destination_city_id', this.selectedCity.city_id);
            formData.append('weight', {{ $totalWeight ?? 500 }});
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch('{{ route('shipping.calculate') }}', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                this.calculating = false;
                this.calculated = true;

                if (data.success) {
                    this.shippingOptions = data.data.shipping_options;
                    if (this.shippingOptions.length === 0) {
                        this.errorMessage = 'Tidak ada opsi pengiriman tersedia untuk kota ini';
                    }
                } else {
                    this.errorMessage = data.message || 'Gagal menghitung ongkos kirim';
                }
            })
            .catch(error => {
                this.calculating = false;
                this.calculated = true;
                this.errorMessage = 'Terjadi kesalahan saat menghitung ongkos kirim';
                console.error('Error calculating shipping:', error);
            });
        },

        selectShippingOption(option) {
            this.selectedShipping = option;
            this.updateTotalCost(option.cost);

            // Dispatch event for other components
            document.dispatchEvent(new CustomEvent('shippingSelected', {
                detail: {
                    courier: option.courier_code,
                    service: option.service_code,
                    cost: option.cost,
                    etd: option.etd,
                    city_id: this.selectedCity.city_id,
                    province: this.selectedCity.province,
                    city: `${this.selectedCity.type} ${this.selectedCity.city_name}`
                }
            }));
        },

        updateTotalCost(shippingCost) {
            const subtotal = {{ $total }};
            const total = subtotal + shippingCost;

            // Update displays
            const shippingDisplay = document.getElementById('shipping-cost-display');
            const shippingSummary = document.getElementById('shipping-cost-summary');
            const totalDisplay = document.getElementById('total-cost-display');

            if (shippingDisplay) {
                shippingDisplay.textContent = 'Rp ' + this.formatNumber(shippingCost);
            }
            if (shippingSummary) {
                shippingSummary.textContent = 'Rp ' + this.formatNumber(shippingCost);
            }
            if (totalDisplay) {
                totalDisplay.textContent = 'Rp ' + this.formatNumber(total);
            }
        },

        formatNumber(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        },

        getCourierColor(courierCode) {
            const colors = {
                'jne': 'bg-gradient-to-br from-red-500 to-red-600',
                'pos': 'bg-gradient-to-br from-blue-500 to-blue-600',
                'tiki': 'bg-gradient-to-br from-green-500 to-green-600',
                'sicepat': 'bg-gradient-to-br from-purple-500 to-purple-600',
                'jnt': 'bg-gradient-to-br from-orange-500 to-orange-600',
                'anteraja': 'bg-gradient-to-br from-indigo-500 to-indigo-600',
                'ninja': 'bg-gradient-to-br from-gray-700 to-gray-800',
                'lion': 'bg-gradient-to-br from-yellow-500 to-yellow-600'
            };
            return colors[courierCode] || 'bg-gradient-to-br from-gray-500 to-gray-600';
        }
    }
}

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('form[action="{{ route('orders.store') }}"]').addEventListener('submit', function(e) {
        const shippingCostValue = document.getElementById('shipping_cost').value;
        const destinationCityId = document.getElementById('destination_city_id').value;

        if (!destinationCityId) {
            e.preventDefault();
            alert('Silakan pilih kota tujuan dan metode pengiriman terlebih dahulu');
            return false;
        }

        if (!shippingCostValue || shippingCostValue === '0') {
            e.preventDefault();
            alert('Silakan pilih metode pengiriman terlebih dahulu');
            return false;
        }

        console.log('Form submitted with shipping cost:', shippingCostValue);
    });
});

// Cancel Buy Now function with modern modal
@if(session()->has('buy_now_item'))
function cancelBuyNow() {
    // Create modal overlay
    const modalOverlay = document.createElement('div');
    modalOverlay.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';

    // Create modal content
    modalOverlay.innerHTML = `
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6 transform scale-95 opacity-0 transition-all duration-300" id="modal-content">
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-times-circle text-3xl text-red-600"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Batalkan checkout?</h3>
                <div class="text-sm text-gray-600 mb-6 space-y-2">
                    <p class="flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-green-500 mr-2"></i>
                        Proses checkout akan di batalkan
                    </p>
                    <p class="flex items-center justify-center">
                        <i class="fas fa-arrow-left text-blue-500 mr-2"></i>
                        Anda akan kembali ke halaman keranjang
                    </p>
                </div>
                <div class="flex space-x-3">
                    <button onclick="
                        const modal = this.closest('.fixed');
                        const modalContent = modal.querySelector('#modal-content');
                        modalContent.classList.add('scale-95', 'opacity-0');
                        modalContent.classList.remove('scale-100', 'opacity-100');
                        setTimeout(() => modal.remove(), 300);
                    "
                            class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                        <i class="fas fa-arrow-left mr-1"></i> Tetap di Checkout
                    </button>
                    <button onclick="
                        const modal = this.closest('.fixed');
                        const modalContent = modal.querySelector('#modal-content');
                        modalContent.classList.add('scale-95', 'opacity-0');
                        setTimeout(() => document.getElementById('cancel-buy-now-form').submit(), 150);
                    "
                            class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-colors">
                        <i class="fas fa-times mr-1"></i> Ya, Batalkan
                    </button>
                </div>
            </div>
        </div>
    `;

    // Add modal to page
    document.body.appendChild(modalOverlay);

    // Animate modal in
    setTimeout(() => {
        const modalContent = document.getElementById('modal-content');
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);

    // Remove modal when clicking overlay
    modalOverlay.addEventListener('click', function(e) {
        if (e.target === modalOverlay) {
            const modalContent = document.getElementById('modal-content');
            modalContent.classList.add('scale-95', 'opacity-0');
            modalContent.classList.remove('scale-100', 'opacity-100');
            setTimeout(() => modalOverlay.remove(), 300);
        }
    });
}
@endif

// Cancel Selected Items function
@if(session()->has('selected_cart_items'))
function cancelSelectedItems() {
    if (confirm('Kembali ke keranjang untuk memilih produk lain?')) {
        // Clear selected items session and go back to cart
        fetch('{{ route("cart.clear.selected.session") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(() => {
            window.location.href = '{{ route("cart.index") }}';
        }).catch(() => {
            // Fallback: just redirect to cart
            window.location.href = '{{ route("cart.index") }}';
        });
    }
}
@endif
</script>
@endsection
