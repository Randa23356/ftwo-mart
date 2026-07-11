@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6">
    <!-- Modern Hero Header -->
    <div class="relative bg-green-700 rounded-2xl sm:rounded-3xl p-5 sm:p-6 md:p-8 lg:p-10 mb-6 sm:mb-8 md:mb-10 overflow-hidden shadow-xl text-white">
        <div class="absolute inset-0 opacity-10 pattern-dots"></div>
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <div class="bg-white/20 p-2 rounded-lg backdrop-blur-sm">
                        <i class="fas fa-file-invoice text-xl"></i>
                    </div>
                    <span class="font-medium text-green-100 uppercase tracking-wider text-sm">Detail Pesanan</span>
                </div>
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold mb-1 break-all">
                    {{ $order->order_number }}
                </h1>
                <p class="text-green-100 text-sm flex items-center">
                    <i class="far fa-calendar-alt mr-2"></i>
                    {{ $order->created_at->format('d M Y, H:i') }} WIB
                </p>
            </div>
            
            <div class="flex items-center gap-3 w-full md:w-auto mt-4 md:mt-0">
                <div class="text-right w-full md:w-auto">
                    <p class="text-xs text-green-100 uppercase tracking-wider mb-1">Status Pesanan</p>
                    @if($order->isExpired() && $order->payment_status === 'pending' && $order->order_status !== 'cancelled')
                    <span class="inline-block px-3 sm:px-4 py-1 sm:py-1.5 text-sm sm:text-base bg-red-500/90 text-white backdrop-blur-md rounded-full font-bold border border-red-300">
                        Expired
                    </span>
                    @else
                    <span class="inline-block px-3 sm:px-4 py-1 sm:py-1.5 text-sm sm:text-base bg-white/20 backdrop-blur-md rounded-full font-bold border border-white/30">
                        {{ ucfirst($order->order_status) }}
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 md:gap-8 mb-10 sm:mb-12 md:mb-16">
        <!-- Main Content (Items & Details) -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Order Status Steps (Optional Visual) -->
            <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xl border border-gray-100 p-4 sm:p-5 md:p-6 lg:p-8">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-3 text-green-600">
                        <i class="fas fa-clipboard-list"></i>
                    </span>
                    Item Pesanan
                </h3>
                
                <div class="space-y-6" id="order-items">
                    @foreach($order->orderItems as $item)
                        <div class="flex items-start md:items-center gap-4 p-4 rounded-xl hover:bg-gray-50 transition-colors border border-gray-100">
                            <!-- Product Image -->
                            <div class="w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 bg-gray-100 rounded-lg flex-shrink-0 overflow-hidden border border-gray-200">
                                <img src="{{ $item->product_image_url }}" 
                                     alt="{{ $item->product_name }}" 
                                     class="w-full h-full object-cover"
                                     onerror="this.src='{{ asset('images/default-product.jpg') }}'">
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <h4 class="font-bold text-gray-900 text-base sm:text-lg truncate">{{ $item->product_name }}</h4>
                                <p class="text-xs sm:text-sm text-gray-500 mb-1 sm:mb-2 truncate">Kode: {{ $item->product_code }}</p>
                                <div class="flex flex-col sm:flex-row sm:flex-wrap sm:items-center gap-2 text-xs sm:text-sm">
                                    <span class="bg-green-50 text-green-700 px-2 py-0.5 sm:px-2.5 sm:py-0.5 rounded-md font-medium border border-green-100 w-fit">
                                        {{ $item->quantity }} x {{ 'Rp ' . number_format($item->price, 0, ',', '.') }}
                                    </span>
                                    
                                    <!-- Rating Button - Only show if order is delivered and product exists -->
                                    @if($order->order_status === 'delivered' && $item->product)
                                        @php
                                            $existingRating = Auth::user()->ratings()->where('product_id', $item->product->id)->where('order_id', $order->id)->first();
                                        @endphp
                                        
                                        @if(!$existingRating)
                                            <a href="{{ route('ratings.create', [$order, $item->product]) }}" 
                                               class="inline-flex items-center text-xs font-medium text-green-600 hover:text-green-700 bg-green-50 hover:bg-green-100 px-2 py-1 rounded-md transition-colors">
                                                <i class="fas fa-star mr-1"></i>
                                                Beri Rating
                                            </a>
                                        @else
                                            <div class="flex items-center space-x-2">
                                                <span class="inline-flex items-center text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded-md">
                                                    <i class="fas fa-check mr-1"></i>
                                                    Sudah Diberi Rating
                                                </span>
                                                <a href="{{ route('ratings.edit', [$order, $item->product]) }}" 
                                                   class="inline-flex items-center text-xs font-medium text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 px-2 py-1 rounded-md transition-colors">
                                                    <i class="fas fa-edit mr-1"></i>
                                                    Edit
                                                </a>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            
                            <div class="text-right ml-2 sm:ml-0">
                                <p class="font-bold text-gray-900 text-base sm:text-lg whitespace-nowrap">{{ 'Rp ' . number_format($item->subtotal, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>



            <!-- Shipping Info -->
            @if($order->shipping_courier)
            <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xl border border-gray-100 p-4 sm:p-5 md:p-6 lg:p-8">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-3 text-green-600">
                        <i class="fas fa-truck"></i>
                    </span>
                    Informasi Pengiriman
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-500 uppercase tracking-wider">Kurir & Layanan</span>
                            <span class="font-bold text-gray-900 text-lg">{{ strtoupper($order->courier_name) }} - {{ $order->shipping_service }}</span>
                        </div>
                        @if($order->shipping_etd)
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-500 uppercase tracking-wider">Estimasi Tiba</span>
                            <span class="font-medium text-gray-900">{{ $order->shipping_etd }} Hari</span>
                        </div>
                        @endif
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-500 uppercase tracking-wider">Nomor Resi</span>
                            @if($order->tracking_number)
                                <span class="font-mono font-bold text-blue-600 text-lg">{{ $order->tracking_number }}</span>
                            @else
                                <span class="font-medium text-gray-400 italic">Belum tersedia</span>
                                <span class="text-xs text-gray-400">Resi akan muncul setelah pesanan dikirim</span>
                            @endif
                        </div>
                        
                        @if($order->tracking_number)
                        <div>
                             <x-order-tracking-button :order="$order" variant="link" />
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar Summary -->
        <div class="lg:col-span-1 space-y-8">
            <!-- Payment Countdown & Expired Status -->
            @if($order->isExpired() && $order->payment_status === 'pending' && $order->order_status !== 'cancelled')
                @if($order->expires_at && $order->expires_at->isFuture())
                <!-- Show countdown if not yet expired -->
                <div class="bg-red-50 rounded-2xl sm:rounded-3xl p-4 sm:p-5 md:p-6 border border-red-100 shadow-inner">
                    <div class="text-center">
                        <h4 class="text-red-800 font-bold mb-2 uppercase tracking-wide text-xs">Sisa Waktu Pembayaran</h4>
                        <div id="countdown-timer" class="text-3xl font-extrabold text-red-600 font-mono mb-2" 
                             data-expires="{{ $order->expires_at->toISOString() }}">
                            <span id="countdown-display">--:--:--</span>
                        </div>
                        <p class="text-xs text-red-600/80">Segera selesaikan pembayaran agar pesanan tidak dibatalkan otomatis.</p>
                    </div>
                </div>
                @else
                <!-- Show expired message -->
                <div class="bg-gray-100 rounded-3xl p-6 border border-gray-200 text-center">
                    <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
                        <i class="fas fa-clock text-xl"></i>
                    </div>
                    <h4 class="text-gray-900 font-bold">Pesanan Kedaluwarsa</h4>
                    <p class="text-sm text-gray-500 mt-1">Batas waktu pembayaran telah habis.</p>
                    
                    @if($order->order_status !== 'cancelled')
                    <div class="mt-4">
                        <form action="{{ route('orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-xs bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg transition-colors">
                                Batalkan Pesanan
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
                @endif
            @elseif($order->expires_at && $order->expires_at->isFuture() && $order->payment_status === 'pending' && $order->order_status !== 'cancelled')
                <!-- Show countdown for non-expired orders -->
                <div class="bg-red-50 rounded-2xl sm:rounded-3xl p-4 sm:p-5 md:p-6 border border-red-100 shadow-inner">
                    <div class="text-center">
                        <h4 class="text-red-800 font-bold mb-2 uppercase tracking-wide text-xs">Sisa Waktu Pembayaran</h4>
                        <div id="countdown-timer" class="text-3xl font-extrabold text-red-600 font-mono mb-2" 
                             data-expires="{{ $order->expires_at->toISOString() }}">
                            <span id="countdown-display">--:--:--</span>
                        </div>
                        <p class="text-xs text-red-600/80">Segera selesaikan pembayaran agar pesanan tidak dibatalkan otomatis.</p>
                    </div>
                </div>
            @endif

            <!-- Order Summary Card -->
            <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="p-6 bg-gray-50 border-b border-gray-100">
                    <h3 class="font-bold text-gray-900">Ringkasan Biaya</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-bold text-gray-900">{{ $order->formatted_subtotal }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600">Biaya Pengiriman</span>
                        <span class="font-bold text-gray-900">{{ $order->formatted_shipping_cost }}</span>
                    </div>
                    <div class="pt-4 border-t border-gray-100 mt-4">
                        <div class="flex justify-between items-center">
                            <span class="text-base font-bold text-gray-900">Total Tagihan</span>
                            <span class="text-xl font-extrabold text-green-600">{{ $order->formatted_total_with_shipping }}</span>
                        </div>
                        
                        @if($order->payment_status === 'pending' && $order->payment_method === 'midtrans' && !$order->isExpired() && $order->order_status !== 'cancelled')
                        <div class="mt-4">
                            <form action="{{ route('orders.pay', $order) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full px-4 py-3 bg-green-700 hover:bg-green-800 text-white font-bold rounded-xl shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                    <span>Bayar Sekarang</span>
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Status & Address -->
                <div class="bg-gray-50/50 p-6 border-t border-gray-100 space-y-5">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Metode Pembayaran</p>
                        <div class="font-bold text-gray-900 flex items-center gap-2">
                             <i class="fas fa-credit-card text-gray-400"></i>
                             @php
                                $pm = strtolower($order->payment_method);
                                $displayPm = $order->payment_method;
                                
if ($pm === 'midtrans') {
                                    $displayPm = 'Transfer'; // Default to Transfer for midtrans
                                    if (isset($paymentType) && !empty($paymentType)) {
                                        $pt = strtolower($paymentType);
                                        if ($pt === 'gopay') $displayPm = 'GoPay';
                                        else if ($pt === 'shopeepay') $displayPm = 'ShopeePay';
                                        else if ($pt === 'qris') $displayPm = 'QRIS';
                                        else if ($pt === 'bca_klikbca') $displayPm = 'KlikBCA';
                                        else if (Str::contains($pt, 'bca')) $displayPm = 'Bank BCA';
                                        else if (Str::contains($pt, 'bni')) $displayPm = 'Bank BNI';
                                        else if (Str::contains($pt, 'bri')) $displayPm = 'Bank BRI';
                                        else if (Str::contains($pt, 'permata')) $displayPm = 'Bank Permata';
                                        else if (Str::contains($pt, 'cimb')) $displayPm = 'Bank CIMB';
                                        else if (Str::contains($pt, 'danamon')) $displayPm = 'Bank Danamon';
                                        else if (Str::contains($pt, 'mandiri')) $displayPm = 'Bank Mandiri';
                                        else if (Str::contains($pt, 'bukopin')) $displayPm = 'Bank Bukopin';
                                        else if (Str::contains($pt, 'echannel')) $displayPm = 'Mandiri Bill';
                                        else if (Str::contains($pt, 'cstore')) $displayPm = 'Indomaret / Alfamart';
                                        else {
                                            $displayPm = ucwords(str_replace('_', ' ', $pt));
                                        }
                                    } else if (isset($otherPayments) && !empty($otherPayments)) {
                                        $keys = array_keys($otherPayments);
                                        $displayPm = !empty($keys) ? $keys[0] : 'E-Wallet';
                                    }
                                } else {
                                    if(Str::contains($pm, 'bca')) $displayPm = 'Bank BCA';
                                    elseif(Str::contains($pm, 'mandiri')) $displayPm = 'Bank Mandiri';
                                    elseif(Str::contains($pm, 'bri')) $displayPm = 'Bank BRI';
                                    elseif(Str::contains($pm, 'bni')) $displayPm = 'Bank BNI';
                                    elseif(Str::contains($pm, ['gopay', 'qris', 'shopeepay'])) $displayPm = 'QRIS / E-Wallet';
                                }
                             @endphp
                             {{ strtoupper($displayPm) }}
                        </div>
                    </div>
                    
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Status Pembayaran</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold
                            {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-700' : ($order->payment_status === 'failed' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                            <span class="w-2 h-2 rounded-full mr-2 {{ $order->payment_status === 'paid' ? 'bg-green-500' : ($order->payment_status === 'failed' ? 'bg-red-500' : 'bg-yellow-500') }}"></span>
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Alamat Pengiriman</p>
                        <p class="text-sm font-medium text-gray-900 leading-relaxed">
                            {{ $order->delivery_address }}
                            <br>
                            @if($order->destination_city)
                                {{ $order->destination_city }}, {{ $order->destination_province }}
                            @endif
                        </p>
                        <p class="text-sm text-gray-500 mt-1 flex items-center">
                            <i class="fas fa-phone-alt text-xs mr-2"></i> {{ $order->delivery_phone }}
                        </p>
                    </div>
                </div>
            </div>
            
            <a href="{{ route('orders.index') }}" class="block w-full py-3 sm:py-4 text-center text-sm sm:text-base text-gray-500 font-bold hover:text-green-600 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

<!-- Rating Notification Shopee Style -->
@if($order->order_status === 'delivered')
    @php
        $unratedItems = $order->orderItems()->whereHas('product', function($query) {
            $query->where('is_active', true);
        })->get()->filter(function($item) {
            return !Auth::user()->ratings()->where('product_id', $item->product->id)->where('order_id', $item->order_id)->exists();
        });
    @endphp
    
    @if($unratedItems->count() > 0)
        <div class="fixed bottom-4 right-4 max-w-sm bg-white rounded-lg shadow-2xl border border-gray-200 p-4 z-50" id="rating-notification">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-star text-green-600"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-gray-900 mb-1">Beri Rating Produk</h4>
                    <p class="text-xs text-gray-600 mb-3">
                        Pesanan Anda sudah sampai! Beri rating untuk {{ $unratedItems->count() }} produk yang belum Anda rating.
                    </p>
                    <div class="flex space-x-2">
                        <button onclick="document.getElementById('rating-notification').style.display='none'" 
                                class="text-xs text-gray-500 hover:text-gray-700">
                            Nanti Saja
                        </button>
                        <a href="#order-items" 
                           onclick="document.getElementById('rating-notification').style.display='none'"
                           class="text-xs font-medium text-green-600 hover:text-green-700 bg-green-50 px-2 py-1 rounded">
                            Beri Rating Sekarang
                        </a>
                    </div>
                </div>
                <button onclick="document.getElementById('rating-notification').style.display='none'" 
                        class="flex-shrink-0 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
        </div>
    @endif
@endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const countdownElement = document.getElementById('countdown-timer');
    if (!countdownElement) return;

    const expiresAt = new Date(countdownElement.dataset.expires);
    const displayElement = document.getElementById('countdown-display');

    function updateCountdown() {
        const now = new Date();
        const timeLeft = expiresAt - now;

        if (timeLeft <= 0) {
            displayElement.textContent = 'EXPIRED';
            // Optional: location reload
            return;
        }

        const hours = Math.floor(timeLeft / (1000 * 60 * 60));
        const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

        displayElement.textContent = 
            `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }

    updateCountdown();
    setInterval(updateCountdown, 1000);
});
</script>
@endpush
