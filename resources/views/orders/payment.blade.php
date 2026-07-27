@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 md:py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-2xl mb-4 shadow-sm">
                <i class="fas fa-credit-card text-green-600 text-2xl"></i>
            </div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-1">Pembayaran</h1>
            <p class="text-gray-500 text-sm">Selesaikan pembayaran untuk pesanan <span class="font-semibold text-gray-700">#{{ $order->order_number }}</span></p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

            {{-- ====== Left: Order Summary (2 cols) ====== --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Order Info Card --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                        <div class="w-8 h-8 bg-green-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-receipt text-green-600 text-sm"></i>
                        </div>
                        <h2 class="font-semibold text-gray-900 text-sm">Detail Pesanan</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        {{-- Order Number --}}
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Nomor Pesanan</span>
                            <span class="text-sm font-bold text-gray-900 font-mono">#{{ $order->order_number }}</span>
                        </div>

                        {{-- Payment Method --}}
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Metode Bayar</span>
                            <div class="flex items-center gap-2">
                                @if($order->payment_method === 'cod')
                                    <span class="w-5 h-5 bg-orange-100 rounded flex items-center justify-center"><i class="fas fa-money-bill-wave text-orange-600 text-[10px]"></i></span>
                                @else
                                    <span class="w-5 h-5 bg-blue-100 rounded flex items-center justify-center"><i class="fas fa-university text-blue-600 text-[10px]"></i></span>
                                @endif
                                <span class="text-sm font-medium text-gray-700">{{ $order->payment_method === 'midtrans' ? 'Transfer / Online' : ucfirst($order->payment_method) }}</span>
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Status</span>
                            @if($order->payment_status === 'paid')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Lunas
                                </span>
                            @elseif($order->payment_status === 'pending')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                    <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full animate-pulse"></span> Menunggu
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span> {{ ucfirst($order->payment_status) }}
                                </span>
                            @endif
                        </div>

                        <div class="border-t border-gray-100 pt-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Total Pembayaran</span>
                            </div>
                            <p class="text-2xl font-bold text-green-700 mt-1">{{ $order->formatted_total_with_shipping }}</p>
                        </div>
                    </div>
                </div>

                {{-- Order Items Card --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                        <div class="w-8 h-8 bg-green-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-box-open text-green-600 text-sm"></i>
                        </div>
                        <h2 class="font-semibold text-gray-900 text-sm">Item Pesanan</h2>
                        <span class="ml-auto bg-gray-100 text-gray-600 text-[11px] font-bold px-2 py-0.5 rounded-full">{{ $order->orderItems->count() }} item</span>
                    </div>
                    <div class="p-4">
                        <div class="space-y-3">
                            @foreach($order->orderItems as $item)
                                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                                    @if($item->product && $item->product->primary_image)
                                        <img src="{{ asset('storage/' . $item->product->primary_image->image_path) }}" alt="{{ $item->product->name }}" class="w-12 h-12 rounded-lg object-cover border border-gray-200 flex-shrink-0">
                                    @else
                                        <div class="w-12 h-12 rounded-lg bg-gray-200 flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-image text-gray-400 text-sm"></i>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $item->product->name ?? 'Produk' }}</p>
                                        <p class="text-xs text-gray-500">{{ $item->quantity }} × {{ $item->product->formatted_price ?? '-' }}</p>
                                    </div>
                                    <span class="text-sm font-bold text-gray-900 flex-shrink-0">{{ $item->formatted_subtotal }}</span>
                                </div>
                            @endforeach

                            {{-- Shipping --}}
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-truck text-gray-400 text-sm"></i>
                                    <span class="text-sm text-gray-600">Biaya Pengiriman</span>
                                </div>
                                <span class="text-sm font-semibold text-gray-700">{{ $order->formatted_shipping_cost }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ====== Right: Payment Action (3 cols) ====== --}}
            <div class="lg:col-span-3">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-24">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                        <div class="w-8 h-8 bg-green-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-lock text-green-600 text-sm"></i>
                        </div>
                        <div>
                            <h2 class="font-semibold text-gray-900 text-sm">Pembayaran Aman</h2>
                            <p class="text-[11px] text-gray-400">Diproses oleh Midtrans</p>
                        </div>
                    </div>

                    <div class="p-6">
                        {{-- Payment Methods Grid --}}
                        <div class="mb-6">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Metode yang Tersedia</p>
                            <div class="grid grid-cols-2 gap-2.5">
                                <div class="flex items-center gap-2.5 p-3 bg-green-50 rounded-xl border border-green-100">
                                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-wallet text-green-600 text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-800">E-wallet</p>
                                        <p class="text-[10px] text-gray-500">GoPay, OVO, DANA</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2.5 p-3 bg-blue-50 rounded-xl border border-blue-100">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-qrcode text-blue-600 text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-800">QRIS</p>
                                        <p class="text-[10px] text-gray-500">Semua bank</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2.5 p-3 bg-purple-50 rounded-xl border border-purple-100">
                                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-university text-purple-600 text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-800">Transfer Bank</p>
                                        <p class="text-[10px] text-gray-500">BCA, Mandiri, BRI</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2.5 p-3 bg-orange-50 rounded-xl border border-orange-100">
                                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-credit-card text-orange-600 text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-800">Kartu Kredit</p>
                                        <p class="text-[10px] text-gray-500">Visa, Mastercard</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Pay Button --}}
                        <div id="snap-container">
                            <button id="pay-button" class="w-full bg-green-700 hover:bg-green-800 text-white py-3.5 px-6 rounded-xl font-semibold text-[15px] transition-all active:scale-[0.98] shadow-lg shadow-green-700/20 hover:shadow-xl hover:shadow-green-700/30 flex items-center justify-center gap-2">
                                <i class="fas fa-lock text-sm"></i> Bayar Sekarang — {{ $order->formatted_total_with_shipping }}
                            </button>
                        </div>

                        {{-- Instructions --}}
                        <div class="mt-5 p-4 bg-gray-50 rounded-xl">
                            <div class="flex items-center gap-2 mb-3">
                                <i class="fas fa-info-circle text-gray-400 text-sm"></i>
                                <h4 class="text-xs font-semibold text-gray-700">Cara Pembayaran</h4>
                            </div>
                            <ol class="text-xs text-gray-500 space-y-2 list-none">
                                <li class="flex items-start gap-2.5">
                                    <span class="w-5 h-5 bg-green-100 text-green-700 rounded-full flex items-center justify-center flex-shrink-0 text-[10px] font-bold mt-0.5">1</span>
                                    <span>Klik tombol <strong class="text-gray-700">"Bayar Sekarang"</strong> di atas</span>
                                </li>
                                <li class="flex items-start gap-2.5">
                                    <span class="w-5 h-5 bg-green-100 text-green-700 rounded-full flex items-center justify-center flex-shrink-0 text-[10px] font-bold mt-0.5">2</span>
                                    <span>Pilih metode pembayaran yang diinginkan</span>
                                </li>
                                <li class="flex items-start gap-2.5">
                                    <span class="w-5 h-5 bg-green-100 text-green-700 rounded-full flex items-center justify-center flex-shrink-0 text-[10px] font-bold mt-0.5">3</span>
                                    <span>Ikuti instruksi pembayaran yang muncul</span>
                                </li>
                                <li class="flex items-start gap-2.5">
                                    <span class="w-5 h-5 bg-green-100 text-green-700 rounded-full flex items-center justify-center flex-shrink-0 text-[10px] font-bold mt-0.5">4</span>
                                    <span>Setelah berhasil, status pesanan akan otomatis berubah</span>
                                </li>
                            </ol>
                        </div>

                        {{-- Back Link --}}
                        <div class="mt-5 text-center">
                            <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-green-600 transition-colors font-medium">
                                <i class="fas fa-arrow-left text-xs"></i> Kembali ke Detail Pesanan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Payment Script --}}
<script src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

<script>
document.getElementById('pay-button').addEventListener('click', function() {
    this.disabled = true;
    this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';

    snap.pay('{{ $snapToken }}', {
        onSuccess: function(result) {
            paymentInProgress = false;
            window.isRedirecting = true;
            notify.success('Pembayaran berhasil! Mengalihkan ke detail pesanan...', {
                title: 'Pembayaran Berhasil',
                duration: 2000
            });
            setTimeout(function() {
                window.location.href = '{{ route("orders.show", ["order" => $order]) }}?payment=success';
            }, 1000);
        },
        onPending: function(result) {
            paymentInProgress = false;
            window.isRedirecting = true;
            notify.info('Pembayaran sedang diproses. Mengalihkan ke detail pesanan...', {
                title: 'Pembayaran Pending',
                duration: 2000
            });
            setTimeout(function() {
                window.location.href = '{{ route("orders.show", ["order" => $order]) }}?payment=pending';
            }, 1000);
        },
        onError: function(result) {
            paymentInProgress = false;
            notify.error('Pembayaran gagal. Silakan coba lagi.', {
                title: 'Pembayaran Gagal',
                duration: 7000
            });
            resetButton();
        },
        onClose: function() {
            paymentInProgress = false;
            resetButton();
        }
    });
});

function resetButton() {
    const btn = document.getElementById('pay-button');
    btn.disabled = false;
    btn.innerHTML = '<i class="fas fa-lock text-sm"></i> Bayar Sekarang — {{ $order->formatted_total_with_shipping }}';
}

let paymentInProgress = false;

document.getElementById('pay-button').addEventListener('click', function() {
    paymentInProgress = true;
    setTimeout(function() { paymentInProgress = false; }, 300000);
});

window.addEventListener('beforeunload', function(e) {
    if (paymentInProgress && !window.isRedirecting) {
        e.preventDefault();
        e.returnValue = 'Pembayaran sedang diproses. Yakin ingin meninggalkan halaman?';
        return e.returnValue;
    }
});

window.addEventListener('popstate', function(e) {
    if (paymentInProgress) {
        if (!confirm('Pembayaran sedang diproses. Yakin ingin kembali?')) {
            history.pushState(null, null, window.location.href);
        }
    }
});
</script>
@endsection