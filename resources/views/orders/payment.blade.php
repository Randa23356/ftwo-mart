@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Pembayaran</h1>
        <p class="text-gray-600">Selesaikan pembayaran untuk pesanan Anda</p>
    </div> 

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Order Details -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Detail Pesanan</h2>

            <div class="space-y-4">
                <div>
                    <span class="text-sm font-medium text-gray-700">Order ID:</span>
                    <p class="text-lg font-semibold text-gray-900">{{ $order->order_number }}</p>
                </div>

                <div>
                    <span class="text-sm font-medium text-gray-700">Total Pembayaran:</span>
                    <p class="text-2xl font-bold text-green-600">{{ $order->formatted_total_with_shipping }}</p>
                </div>

                <div>
                    <span class="text-sm font-medium text-gray-700">Metode Pembayaran:</span>
                    <p class="text-gray-900">{{ $order->payment_method === 'midtrans' ? 'Transfer' : ucfirst($order->payment_method) }}</p>
                </div>

                <div>
                    <span class="text-sm font-medium text-gray-700">Status:</span>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </div>
            </div>

            <!-- Order Items -->
            <div class="mt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Item Pesanan:</h3>
                <div class="space-y-2">
                    @foreach($order->orderItems as $item)
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <div>
                            <p class="font-medium text-gray-900">{{ $item->product->name }}</p>
                            <p class="text-sm text-gray-500">{{ $item->quantity }} × {{ $item->product->formatted_price }}</p>
                        </div>
                        <span class="font-medium text-gray-900">{{ $item->formatted_subtotal }}</span>
                    </div>
                    @endforeach

                    <!-- Shipping Cost -->
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <div>
                            <p class="font-medium text-gray-900">Biaya Pengiriman</p>
                        </div>
                        <span class="font-medium text-gray-900">{{ $order->formatted_shipping_cost }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Gateway -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Pembayaran Transfer</h2>

            <div class="mb-6">
                <p class="text-gray-600 mb-4">
                    Pilih metode pembayaran yang tersedia. Pembayaran aman dan terpercaya.
                </p>

                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div class="flex items-center p-2 bg-green-50 rounded border">
                        <i class="fas fa-wallet text-green-600 mr-2"></i>
                        <span>E-wallet</span>
                    </div>
                    <div class="flex items-center p-2 bg-blue-50 rounded border">
                        <i class="fas fa-qrcode text-blue-600 mr-2"></i>
                        <span>QRIS</span>
                    </div>
                    <div class="flex items-center p-2 bg-purple-50 rounded border">
                        <i class="fas fa-university text-purple-600 mr-2"></i>
                        <span>Bank Transfer</span>
                    </div>
                    <div class="flex items-center p-2 bg-orange-50 rounded border">
                        <i class="fas fa-credit-card text-orange-600 mr-2"></i>
                        <span>Credit Card</span>
                    </div>
                </div>
            </div>

            <!-- Payment Button -->
            <div id="snap-container" class="text-center">
                <button id="pay-button" class="w-full bg-green-700 hover:bg-green-800 text-white py-3 px-6 rounded-lg font-semibold text-lg transition-colors">
                    <i class="fas fa-credit-card mr-2"></i> Bayar Sekarang
                </button>
            </div>

            <!-- Payment Instructions -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <h4 class="font-medium text-gray-900 mb-2">Instruksi Pembayaran:</h4>
                <ol class="text-sm text-gray-600 space-y-1 list-decimal list-inside">
                    <li>Klik tombol "Bayar Sekarang" di atas</li>
                    <li>Pilih metode pembayaran yang diinginkan</li>
                    <li>Ikuti instruksi pembayaran yang muncul</li>
                    <li>Setelah pembayaran berhasil, status pesanan akan berubah</li>
                </ol>
            </div>

            <!-- Back to Order -->
            <div class="mt-6 text-center">
                <a href="{{ route('orders.show', $order) }}" class="text-green-600 hover:text-green-700 font-medium">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Detail Pesanan
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Payment Script -->
<script src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

<script>
document.getElementById('pay-button').addEventListener('click', function() {
    // Disable button to prevent double click
    this.disabled = true;
    this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';

    // Trigger payment
    snap.pay('{{ $snapToken }}', {
        onSuccess: function(result) {
            // Payment successful - allow navigation
            paymentInProgress = false;
            window.isRedirecting = true;
            console.log('Payment successful, redirecting...');

            // Show success message briefly before redirect
            notify.success('Pembayaran berhasil! Mengalihkan ke detail pesanan...', {
                title: 'Pembayaran Berhasil',
                duration: 2000
            });

            // Redirect after brief delay
            setTimeout(function() {
                window.location.href = '{{ route("orders.show", ["order" => $order]) }}?payment=success';
            }, 1000);
        },
        onPending: function(result) {
            // Payment pending - allow navigation
            paymentInProgress = false;
            window.isRedirecting = true;
            console.log('Payment pending, redirecting...');

            // Show pending message briefly before redirect
            notify.info('Pembayaran sedang diproses. Mengalihkan ke detail pesanan...', {
                title: 'Pembayaran Pending',
                duration: 2000
            });

            // Redirect after brief delay
            setTimeout(function() {
                window.location.href = '{{ route("orders.show", ["order" => $order]) }}?payment=pending';
            }, 1000);
        },
        onError: function(result) {
            // Payment failed - reset state
            paymentInProgress = false;
            // Handle error
            notify.error('Pembayaran gagal. Silakan coba lagi.', {
                title: 'Pembayaran Gagal',
                duration: 7000
            });
            document.getElementById('pay-button').disabled = false;
            document.getElementById('pay-button').innerHTML = '<i class="fas fa-credit-card mr-2"></i> Bayar Sekarang';
            loadingOverlay.hide();
        },
        onClose: function() {
            // Payment closed - reset state
            paymentInProgress = false;
            // Handle close
            document.getElementById('pay-button').disabled = false;
            document.getElementById('pay-button').innerHTML = '<i class="fas fa-credit-card mr-2"></i> Bayar Sekarang';
        }
    });
});

// Prevent accidental refresh during payment
let paymentInProgress = false;

// Set payment in progress when button is clicked
document.getElementById('pay-button').addEventListener('click', function() {
    // Set to true when payment popup opens
    paymentInProgress = true;

    // Auto-reset after 5 minutes (safety fallback)
    setTimeout(function() {
        if (paymentInProgress) {
            paymentInProgress = false;
        }
    }, 300000); // 5 minutes
});

// Warn user before leaving page during payment (but allow programmatic redirects)
window.addEventListener('beforeunload', function(e) {
    // Only prevent if payment is in progress AND it's not a programmatic redirect
    if (paymentInProgress && !window.isRedirecting) {
        e.preventDefault();
        e.returnValue = 'Pembayaran sedang diproses. Yakin ingin meninggalkan halaman?';
        return e.returnValue;
    }
});

// Handle browser back button
window.addEventListener('popstate', function(e) {
    if (paymentInProgress) {
        if (!confirm('Pembayaran sedang diproses. Yakin ingin kembali?')) {
            history.pushState(null, null, window.location.href);
        }
    }
});
</script>
@endsection
