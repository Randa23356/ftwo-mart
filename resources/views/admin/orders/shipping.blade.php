@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-4 sm:p-6 mb-6 sm:mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Kelola Pengiriman</h1>
                <p class="text-sm text-gray-600 mt-1">Order #{{ $order->order_number }}</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-3 py-1 rounded-full text-xs sm:text-sm font-medium {{ $order->status_badge }}">
                    {{ ucfirst($order->order_status) }}
                </span>
                <a href="{{ route('admin.orders.detail', $order) }}" class="text-green-600 hover:text-green-700 text-sm font-semibold">
                    <i class="fas fa-arrow-left mr-1"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
            <!-- Order Information -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 sm:p-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Informasi Pesanan</h2>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600 text-sm">Customer:</span>
                        <span class="font-medium text-sm sm:text-base">{{ $order->user->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 text-sm">Total Produk:</span>
                        <span class="font-medium text-sm sm:text-base">{{ $order->formatted_total_amount }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 text-sm">Berat Total:</span>
                        <span class="font-medium text-sm sm:text-base">{{ number_format($order->total_weight) }} gram</span>
                    </div>
                    @if($order->shipping_cost > 0)
                    <div class="flex justify-between">
                        <span class="text-gray-600 text-sm">Ongkos Kirim:</span>
                        <span class="font-medium text-sm sm:text-base">{{ $order->formatted_shipping_cost }}</span>
                    </div>
                    <div class="flex justify-between border-t pt-2">
                        <span class="text-gray-900 font-semibold text-sm sm:text-base">Total Keseluruhan:</span>
                        <span class="font-bold text-green-600 text-sm sm:text-base">{{ $order->formatted_total_with_shipping }}</span>
                    </div>
                    @endif
                </div>

                <!-- Delivery Address -->
                <div class="mt-6 pt-6 border-t">
                    <h3 class="font-medium text-gray-900 mb-2 text-sm sm:text-base">Alamat Pengiriman</h3>
                    <div class="text-sm text-gray-600">
                        <p class="font-medium">{{ $order->user->name }}</p>
                        <p>{{ $order->delivery_phone }}</p>
                        <p class="mt-1">{{ $order->delivery_address }}</p>
                        @if($order->destination_city && $order->destination_province)
                        <p>{{ $order->destination_city }}, {{ $order->destination_province }}</p>
                        @endif
                        @if($order->destination_postal_code)
                        <p>{{ $order->destination_postal_code }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Shipping Management -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 sm:p-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Pengaturan Pengiriman</h2>

                @if($order->shipping_courier)
                <!-- Current Shipping Info -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-4">
                    <h3 class="font-medium text-blue-900 mb-2 text-sm">Pengiriman Saat Ini</h3>
                    <div class="text-sm text-blue-800 space-y-1">
                        <p><strong>Kurir:</strong> {{ $order->courier_name }}</p>
                        <p><strong>Layanan:</strong> {{ $order->shipping_service }}</p>
                        <p><strong>Biaya:</strong> {{ $order->formatted_shipping_cost }}</p>
                        @if($order->shipping_etd)
                        <p><strong>Estimasi:</strong> {{ $order->shipping_etd }} hari</p>
                        @endif
                    </div>
                </div>
                @endif

                @if(!$order->tracking_number)
                <!-- Add Tracking Number -->
                <div class="mt-4 pt-4 border-t">
                    <h3 class="font-medium text-gray-900 mb-3 text-sm">Tambah Nomor Resi</h3>
                    <form id="tracking-form">
                        @csrf
                        <div class="flex flex-col sm:flex-row sm:space-x-3 space-y-2 sm:space-y-0">
                            <input type="text" id="tracking_number" name="tracking_number" 
                                   class="w-full sm:flex-1 px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm bg-gray-50 focus:bg-white transition"
                                   placeholder="Masukkan nomor resi" required>
                            <button type="submit" class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-5 rounded-xl transition-all text-sm shadow-sm">
                                <i class="fas fa-plus mr-1"></i>
                                Tambah
                            </button>
                        </div>
                    </form>
                </div>
                @endif

                @if($order->tracking_number)
                <!-- Tracking Information -->
                <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                    <h3 class="font-medium text-green-900 mb-2 text-sm">
                        <i class="fas fa-check-circle mr-2"></i>
                        Paket Telah Dikirim
                    </h3>
                    <div class="text-sm text-green-800 space-y-1">
                        <p><strong>Nomor Resi:</strong> {{ $order->tracking_number }}</p>
                        <p><strong>Tanggal Kirim:</strong> {{ $order->shipped_at->format('d M Y H:i') }}</p>
                        <p><strong>Kurir:</strong> {{ $order->courier_name }} - {{ $order->shipping_service }}</p>
                    </div>
                    
                    <div class="mt-3">
                        <x-order-tracking-button :order="$order" variant="link" />
                    </div>
                </div>
                @endif
            </div>
                    </h3>
                    <div class="text-sm text-green-800">
                        <p><strong>Nomor Resi:</strong> {{ $order->tracking_number }}</p>
                        <p><strong>Tanggal Kirim:</strong> {{ $order->shipped_at->format('d M Y H:i') }}</p>
                        <p><strong>Kurir:</strong> {{ $order->courier_name }} - {{ $order->shipping_service }}</p>
                    </div>
                    
                    <div class="mt-3">
                        <x-order-tracking-button :order="$order" variant="link" />
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const updateForm = document.getElementById('update-shipping-form');
    const trackingForm = document.getElementById('tracking-form');
    
    // Listen for shipping selection
    document.addEventListener('shippingSelected', function(e) {
        const shippingData = e.detail;
        document.getElementById('shipping-data').value = JSON.stringify(shippingData);
        updateForm.classList.remove('hidden');
    });

    // Listen for shipping reset
    document.addEventListener('shippingReset', function() {
        updateForm.classList.add('hidden');
    });

    // Handle shipping update
    if (updateForm) {
        updateForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const shippingData = JSON.parse(document.getElementById('shipping-data').value);
            
            fetch(`/orders/{{ $order->id }}/shipping`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    shipping_courier: shippingData.courier,
                    shipping_service: shippingData.service,
                    shipping_cost: shippingData.cost,
                    shipping_etd: shippingData.etd,
                    destination_city_id: shippingData.city_id,
                    destination_province: shippingData.province,
                    destination_city: shippingData.city
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Pengiriman berhasil diperbarui!');
                    location.reload();
                } else {
                    alert('Gagal memperbarui pengiriman: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memperbarui pengiriman');
            });
        });
    }

    // Handle tracking number submission
    if (trackingForm) {
        trackingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const trackingNumber = document.getElementById('tracking_number').value;
            
            fetch(`/orders/{{ $order->id }}/tracking`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    tracking_number: trackingNumber
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Nomor resi berhasil ditambahkan!');
                    location.reload();
                } else {
                    alert('Gagal menambahkan nomor resi: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menambahkan nomor resi');
            });
        });
    }
});
</script>
@endpush
@endsection