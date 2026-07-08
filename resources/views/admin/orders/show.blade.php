@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Animated Background Pattern (Matching Dashboard) -->
    <div class="fixed inset-0 opacity-30 pointer-events-none">
        <div class="absolute inset-0" style="background-image: url(&quot;data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%236366f1' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E&quot;);"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
        <!-- Header -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl lg:rounded-3xl shadow-xl border border-white/20 p-6 lg:p-8 mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.orders') }}" 
                       class="group relative inline-flex items-center justify-center p-3 bg-white border border-gray-200 rounded-xl hover:border-green-500 hover:text-green-600 transition-all duration-300 shadow-sm hover:shadow-md">
                        <i class="fas fa-arrow-left text-lg"></i>
                    </a>
                    <div>
                        <div class="flex items-center space-x-3 mb-1">
                            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Detail Pesanan</h1>
                            <span class="px-3 py-1 rounded-full text-xs font-bold
                                {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-700 border border-green-200' : 
                                   ($order->payment_status === 'failed' ? 'bg-red-100 text-red-700 border border-red-200' : 
                                   'bg-yellow-100 text-yellow-700 border border-yellow-200') }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                        <p class="text-gray-600 flex items-center space-x-2 text-sm lg:text-base">
                            <span class="font-medium">#{{ $order->order_number }}</span>
                            <span class="w-1 h-1 rounded-full bg-gray-400"></span>
                            <span>{{ $order->created_at->format('d M Y H:i') }}</span>
                        </p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                     <div class="px-4 py-2 bg-white/50 rounded-xl border border-gray-200 text-gray-700 text-sm font-semibold shadow-sm backdrop-blur-sm">
                        Status: {{ ucfirst($order->order_status) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Order Items & Details -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Order Items (Responsive: Table on Desktop, Cards on Mobile) -->
                <div class="bg-white/90 backdrop-blur-lg rounded-2xl md:rounded-3xl shadow-xl border border-white/20 overflow-hidden">
                    <div class="px-6 md:px-8 py-4 md:py-6 border-b border-gray-100/50 flex justify-between items-center bg-gray-50/50">
                        <div class="flex items-center space-x-3">
                            <div class="bg-green-600 p-2 rounded-lg shadow-lg">
                                <i class="fas fa-shopping-bag text-white text-sm"></i>
                            </div>
                            <h2 class="text-lg md:text-xl font-bold text-green-800">Item Pesanan</h2>
                        </div>
                        <span class="text-sm font-medium text-gray-600 bg-white px-3 py-1 rounded-lg border border-gray-100 shadow-sm">{{ $order->orderItems->count() }} Item</span>
                    </div>
                    
                    <!-- Desktop Table View (Hidden on Mobile) -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-gray-50/50 text-gray-600 text-sm border-b border-gray-100">
                                    <th class="px-6 py-4 font-semibold rounded-tl-lg">Produk</th>
                                    <th class="px-6 py-4 font-semibold text-center">Qty</th>
                                    <th class="px-6 py-4 font-semibold text-right">Harga</th>
                                    <th class="px-6 py-4 font-semibold text-right rounded-tr-lg">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($order->orderItems as $item)
                                <tr class="hover:bg-green-50/30 transition-colors duration-200">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-4">
                                            <div class="h-14 w-14 rounded-xl bg-gray-100 overflow-hidden flex-shrink-0 border border-gray-200 shadow-sm group-hover:scale-105 transition-transform">
                                                <img src="{{ $item->product->image_url }}" 
                                                     alt="{{ $item->product->name }}"
                                                     class="h-full w-full object-cover">
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-900 line-clamp-1 text-sm md:text-base">{{ $item->product->name }}</div>
                                                <div class="text-xs text-gray-500 mt-0.5">{{ $item->product->category->name ?? 'Uncategorized' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-md bg-gray-100 text-gray-700 font-medium text-sm">
                                            {{ $item->quantity }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right text-gray-600 font-medium">
                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-gray-900">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                                <!-- Shipping Row -->
                                @if($order->shipping_cost > 0)
                                <tr class="bg-blue-50/30 hover:bg-blue-50/50 transition-colors duration-200">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="bg-blue-100 p-2 rounded-lg">
                                                <i class="fas fa-truck text-blue-600 text-sm"></i>
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-900 text-sm md:text-base">Biaya Pengiriman</div>
                                                <div class="text-xs text-gray-500 mt-0.5">{{ strtoupper($order->courier_name ?? 'Courier') }} - {{ $order->shipping_service ?? 'Service' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-md bg-blue-100 text-blue-700 font-medium text-sm">
                                            1
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right text-gray-600 font-medium">
                                        {{ $order->formatted_shipping_cost }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-gray-900">
                                        {{ $order->formatted_shipping_cost }}
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                            <tfoot class="bg-gray-50/50 border-t border-gray-100">
                                <tr>
                                    <td colspan="3" class="px-6 py-5 text-right font-bold text-gray-600">Total Pembayaran</td>
                                    <td class="px-6 py-5 text-right font-bold text-xl text-green-700">
                                        {{ $order->formatted_total_with_shipping }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Mobile Card View (Visible on Mobile) -->
                    <div class="md:hidden">
                        <div class="divide-y divide-gray-100">
                            @foreach($order->orderItems as $item)
                            <div class="p-4 hover:bg-green-50/30 transition-colors duration-200">
                                <div class="flex gap-4">
                                    <!-- Product Image -->
                                    <div class="h-20 w-20 rounded-xl bg-gray-100 overflow-hidden flex-shrink-0 border border-gray-200 shadow-sm">
                                        <img src="{{ $item->product->image_url }}" 
                                             alt="{{ $item->product->name }}"
                                             class="h-full w-full object-cover">
                                    </div>
                                    
                                    <!-- Details -->
                                    <div class="flex-1 min-w-0">
                                        <div class="font-bold text-gray-900 line-clamp-2 text-sm mb-1">{{ $item->product->name }}</div>
                                        <div class="text-xs text-gray-500 mb-2">{{ $item->product->category->name ?? 'Uncategorized' }}</div>
                                        
                                        <div class="flex items-center justify-between">
                                            <div class="text-xs text-gray-500">
                                                {{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}
                                            </div>
                                            <div class="font-bold text-green-700 text-sm">
                                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            
                            <!-- Mobile Shipping Item -->
                            @if($order->shipping_cost > 0)
                            <div class="p-4 bg-blue-50/30 hover:bg-blue-50/50 transition-colors duration-200">
                                <div class="flex gap-4">
                                    <!-- Shipping Icon -->
                                    <div class="h-20 w-20 rounded-xl bg-blue-100 overflow-hidden flex-shrink-0 border border-blue-200 shadow-sm flex items-center justify-center">
                                        <i class="fas fa-truck text-blue-600 text-xl"></i>
                                    </div>
                                    
                                    <!-- Details -->
                                    <div class="flex-1 min-w-0">
                                        <div class="font-bold text-gray-900 line-clamp-2 text-sm mb-1">Biaya Pengiriman</div>
                                        <div class="text-xs text-gray-500 mb-2">{{ strtoupper($order->courier_name ?? 'Courier') }} - {{ $order->shipping_service ?? 'Service' }}</div>
                                        
                                        <div class="flex items-center justify-between">
                                            <div class="text-xs text-gray-500">
                                                1 x {{ $order->formatted_shipping_cost }}
                                            </div>
                                            <div class="font-bold text-blue-700 text-sm">
                                                {{ $order->formatted_shipping_cost }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        <!-- Mobile Total Footer -->
                        <div class="bg-gray-50/50 p-4 border-t border-gray-100 flex justify-between items-center">
                            <span class="font-bold text-gray-600 text-sm">Total Pembayaran</span>
                            <span class="font-bold text-lg text-green-700">{{ $order->formatted_total_with_shipping }}</span>
                        </div>
                    </div>
                    
                    @if($order->payment_method === 'cod' && $order->order_status === 'shipped' && $order->payment_status !== 'paid')
                        @php($codUrl = URL::signedRoute('cod.confirm.show', ['order' => $order->id]))
                        <div class="bg-green-50/50 px-6 py-4 border-t border-green-100">
                             <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="bg-white p-2 rounded-xl shadow-sm border border-green-100">
                                         <canvas id="cod-qr" class="h-16 w-16"></canvas>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-green-800">Konfirmasi COD</h4>
                                        <p class="text-sm text-green-700">Scan QR atau gunakan link ini</p>
                                    </div>
                                </div>
                                <a href="{{ $codUrl }}" target="_blank" 
                                   class="px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-xl shadow-lg shadow-green-200 transition-all hover:-translate-y-0.5">
                                    Buka Link Konfirmasi
                                </a>
                            </div>
                            <script>
                                (function() {
                                    const url = "{{ $codUrl }}";
                                    function tryRender() {
                                        if (window.renderQrCode) {
                                            window.renderQrCode('#cod-qr', url);
                                        } else {
                                            window.addEventListener('load', function() {
                                                if (window.renderQrCode) window.renderQrCode('#cod-qr', url);
                                            });
                                        }
                                    }
                                    tryRender();
                                })();
                            </script>
                        </div>
                    @endif
                </div>

                <!-- Informations Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Customer Info -->
                    <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 p-6 hover:shadow-2xl transition-shadow duration-300">
                        <div class="flex items-center space-x-3 mb-5">
                            <div class="bg-blue-100 p-2.5 rounded-xl text-blue-600">
                                <i class="fas fa-user text-lg"></i>
                            </div>
                            <h3 class="font-bold text-gray-900 text-lg">Informasi Pelanggan</h3>
                        </div>
                        <div class="space-y-4">
                            <div class="group p-3 rounded-xl bg-gray-50 border border-gray-100 hover:border-blue-200 hover:bg-blue-50/30 transition-colors">
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 block">Nama</label>
                                <p class="text-gray-900 font-semibold">{{ $order->user->name ?? '-' }}</p>
                            </div>
                            <div class="group p-3 rounded-xl bg-gray-50 border border-gray-100 hover:border-blue-200 hover:bg-blue-50/30 transition-colors">
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 block">Email</label>
                                <p class="text-gray-900">{{ $order->user->email ?? '-' }}</p>
                            </div>
                            <div class="group p-3 rounded-xl bg-gray-50 border border-gray-100 hover:border-blue-200 hover:bg-blue-50/30 transition-colors">
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 block">No. Telepon</label>
                                <p class="text-gray-900 font-mono">{{ $order->delivery_phone ?? $order->user->phone ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Info -->
                    <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 p-6 hover:shadow-2xl transition-shadow duration-300">
                        <div class="flex items-center space-x-3 mb-5">
                            <div class="bg-orange-100 p-2.5 rounded-xl text-orange-600">
                                <i class="fas fa-truck text-lg"></i>
                            </div>
                            <h3 class="font-bold text-gray-900 text-lg">Pengiriman</h3>
                        </div>
                        <div class="space-y-4">
                             <div class="group p-3 rounded-xl bg-gray-50 border border-gray-100 hover:border-orange-200 hover:bg-orange-50/30 transition-colors">
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 block">Ekspedisi</label>
                                <div class="flex items-center space-x-2">
                                    <p class="text-gray-900 font-bold">{{ strtoupper($order->courier_name ?? '-') }}</p>
                                    <span class="px-2 py-0.5 rounded text-xs bg-gray-200 text-gray-700 font-medium">{{ $order->shipping_service ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="group p-3 rounded-xl bg-gray-50 border border-gray-100 hover:border-orange-200 hover:bg-orange-50/30 transition-colors">
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 block">Biaya Pengiriman</label>
                                <p class="text-gray-900 font-bold">{{ $order->formatted_shipping_cost }}</p>
                            </div>
                            <div class="group p-3 rounded-xl bg-gray-50 border border-gray-100 hover:border-orange-200 hover:bg-orange-50/30 transition-colors">
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 block">Alamat</label>
                                <p class="text-gray-900 leading-relaxed text-sm">{{ $order->delivery_address ?? $order->user->address ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Actions & Status -->
            <div class="space-y-6">
                
                <!-- Status Management -->
                <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 p-6">
                    <div class="flex items-center space-x-3 mb-5 border-b border-gray-100 pb-4">
                        <div class="bg-purple-100 p-2 rounded-xl text-purple-600">
                            <i class="fas fa-cog"></i>
                        </div>
                        <h3 class="font-bold text-gray-900 text-lg">Update Order</h3>
                    </div>
                    
                    <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="space-y-4">
                        @csrf
                        @method('PUT')
                        
                        @if($order->order_status === 'cancelled')
                             <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-center">
                                <i class="fas fa-ban text-red-500 text-3xl mb-3 block"></i>
                                <p class="font-bold text-red-800">Pesanan Dibatalkan</p>
                                <p class="text-xs text-red-600 mt-1">Status tidak dapat diubah lagi</p>
                            </div>
                        @elseif($order->order_status === 'delivered')
                             <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-center">
                                <i class="fas fa-check-circle text-green-500 text-3xl mb-3 block"></i>
                                <p class="font-bold text-green-800">Pesanan Selesai</p>
                                <p class="text-xs text-green-600 mt-1">Transaksi telah selesai</p>
                            </div>
                        @else
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Status</label>
                                <div class="relative">
                                    <select name="order_status" class="w-full pl-4 pr-10 py-3 rounded-xl border-gray-200 focus:ring-green-500 focus:border-green-500 text-gray-800 font-medium bg-gray-50 transition-shadow">
                                        @foreach(['pending','processing','ready','shipped','delivered','cancelled'] as $status)
                                            <option value="{{ $status }}" @selected($order->order_status === $status)>
                                                {{ ucfirst($status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <button type="submit" class="w-full flex justify-center items-center px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                                <i class="fas fa-save mr-2"></i> Simpan Status
                            </button>

                            <button type="button" onclick="handleCancelOrder(this)" 
                                class="w-full flex justify-center items-center px-4 py-3 bg-white border-2 border-red-100 text-red-600 hover:bg-red-50 hover:border-red-200 font-bold rounded-xl transition-all">
                                <i class="fas fa-times-circle mr-2"></i> Batalkan Pesanan
                            </button>
                        @endif
                    </form>
                </div>

                <!-- Tracking Number -->
                <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 p-6">
                    <div class="flex items-center space-x-3 mb-5 border-b border-gray-100 pb-4">
                        <div class="bg-teal-100 p-2 rounded-xl text-teal-600">
                            <i class="fas fa-barcode"></i>
                        </div>
                        <h3 class="font-bold text-gray-900 text-lg">Resi Pengiriman</h3>
                    </div>
                    
                    @if($order->tracking_number)
                        <div class="bg-gray-50 rounded-xl p-5 border-2 border-dashed border-gray-200 text-center hover:border-teal-200 transition-colors">
                            <p class="text-xs text-gray-500 uppercase tracking-widest mb-2">Nomor Resi</p>
                            <p class="text-2xl font-mono font-bold text-gray-800 tracking-wider select-all">{{ $order->tracking_number }}</p>
                            
                            <x-order-tracking-button :order="$order" />
                        </div>
                    @else
                        <form id="tracking-form" class="space-y-4">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-keyboard text-gray-400"></i>
                                </div>
                                <input type="text" id="tracking_number" name="tracking_number" 
                                    class="pl-10 w-full rounded-xl border-gray-200 focus:ring-green-500 focus:border-green-500 text-gray-800 py-3 bg-gray-50 placeholder-gray-400 font-medium"
                                    placeholder="Input No. Resi" required>
                            </div>
                            <button type="submit" class="w-full px-4 py-3 bg-gray-800 hover:bg-gray-900 text-white font-bold rounded-xl transition-all shadow-lg hover:shadow-xl">
                                Input Resi
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Payment Summary Card -->
                <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 p-6">
                    <h3 class="font-bold text-gray-900 mb-4 border-b border-gray-100 pb-3">Ringkasan Pembayaran</h3>
                    <div class="space-y-3">
                         <div class="flex justify-between text-sm group">
                            <span class="text-gray-500 font-medium">Metode</span>
                            <span class="font-bold text-gray-900 group-hover:text-green-600 transition-colors">{{ strtolower($order->payment_method) === 'midtrans' ? 'Transfer' : ucfirst($order->payment_method) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 font-medium">Subtotal</span>
                            <span class="font-bold text-gray-900">{{ $order->formatted_subtotal }}</span>
                        </div>
                         <div class="flex justify-between text-sm">
                            <span class="text-gray-500 font-medium">Ongkir</span>
                            <span class="font-bold text-gray-900">{{ $order->formatted_shipping_cost }}</span>
                        </div>
                        <div class="border-t border-dashed border-gray-200 pt-4 mt-2 flex justify-between items-center">
                            <span class="font-bold text-gray-900 text-lg">Total</span>
                            <span class="font-extrabold text-xl text-green-600">{{ $order->formatted_total_with_shipping }}</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const trackingForm = document.getElementById('tracking-form');
    if (trackingForm) {
        trackingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const trackingNumber = document.getElementById('tracking_number').value.trim();
            
            if (!trackingNumber) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Nomor resi tidak boleh kosong!',
                    confirmButtonColor: '#10b981'
                });
                return;
            }
            
            Swal.fire({
                title: 'Menyimpan...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            
            fetch(`/admin/orders/{{ $order->id }}/tracking`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ tracking_number: trackingNumber })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Resi disimpan',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => location.reload());
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: err.message || 'Terjadi kesalahan',
                    confirmButtonColor: '#ef4444'
                });
            });
        });
    }
});

async function handleCancelOrder(btn) {
    const isConfirmed = await Swal.fire({
        title: 'Batalkan Pesanan?',
        text: "Tindakan ini tidak dapat dibatalkan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#9ca3af',
        confirmButtonText: 'Ya, Batalkan',
        cancelButtonText: 'Batal',
        background: '#fff',
        customClass: {
            popup: 'rounded-2xl',
            confirmButton: 'rounded-xl',
            cancelButton: 'rounded-xl'
        }
    });

    if (isConfirmed.isConfirmed) {
        const form = btn.closest('form');
        const input = document.createElement('input');
        input.type = 'hidden'; 
        input.name = 'order_status'; 
        input.value = 'cancelled';
        form.appendChild(input);
        form.submit();
    }
}
</script>
@endpush
