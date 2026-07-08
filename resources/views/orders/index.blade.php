@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6 py-6">
    <!-- Header with Gradient Background -->
    <div class="relative bg-green-700 rounded-2xl sm:rounded-3xl p-6 sm:p-8 mb-8 overflow-hidden shadow-xl text-white">
        <div class="absolute inset-0 opacity-10 pattern-dots"></div>
        <div class="relative z-10">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="bg-white/20 p-2 rounded-lg backdrop-blur-sm">
                            <i class="fas fa-shopping-bag text-xl"></i>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold">Pesanan Saya</h1>
                    </div>
                    <p class="text-green-100 text-sm sm:text-base">Daftar lengkap riwayat pesanan Anda</p>
                </div>
                <a href="{{ route('products') }}" class="inline-flex items-center px-5 py-2.5 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-full text-sm font-medium text-white border border-white/30 transition-all">
                    <i class="fas fa-plus mr-2"></i> Pesan Baru
                </a>
            </div>
        </div>
    </div>

    @if($orders->count())
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xl border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <div class="p-4 sm:p-6 border-b border-gray-100">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-lg font-bold text-gray-900 truncate">
                                        #{{ $order->order_number }}
                                    </h3>
                                    <span class="hidden sm:inline-block text-gray-400">•</span>
                                    <span class="text-sm text-gray-500 whitespace-nowrap">
                                        {{ $order->created_at->format('d M Y, H:i') }} WIB
                                    </span>
                                </div>
                                <div class="flex flex-wrap items-center gap-2 mt-1">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium
                                        {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 
                                           ($order->payment_status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                    <span class="px-3 py-1 rounded-full text-xs font-medium
                                        {{ $order->order_status === 'delivered' ? 'bg-green-100 text-green-800' : 
                                           ($order->order_status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-green-50 text-green-700') }}">
                                        {{ ucfirst($order->order_status) }}
                                    </span>
                                    <span class="text-sm text-gray-500">
                                        • {{ strtolower($order->payment_method) === 'midtrans' ? 'Transfer' : ucfirst($order->payment_method) }}
                                    </span>
                                </div>
                            </div>
                            <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center px-4 py-2 border border-gray-200 rounded-full text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-green-500 hover:text-green-600 transition-colors">
                                <span>Lihat Detail</span>
                                <i class="fas fa-chevron-right ml-2 text-xs"></i>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Order Items Preview -->
                    <div class="p-4 sm:p-6 bg-gray-50/50">
                        <div class="flex items-center -space-x-2">
                            @foreach($order->orderItems->take(3) as $item)
                                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg border-2 border-white overflow-hidden bg-white shadow-sm">
                                    <img src="{{ $item->product->image_url }}" 
                                         alt="{{ $item->product->name }}" 
                                         class="w-full h-full object-cover">
                                </div>
                            @endforeach
                            @if($order->orderItems->count() > 3)
                                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gray-100 border-2 border-white flex items-center justify-center text-xs font-bold text-gray-500">
                                    +{{ $order->orderItems->count() - 3 }}
                                </div>
                            @endif
                        </div>
                        
                        <div class="mt-4 flex justify-between items-center">
                            <div>
                                <p class="text-xs text-gray-500">Total Belanja</p>
                                <p class="text-lg font-bold text-gray-900">{{ $order->formatted_total_with_shipping }}</p>
                            </div>
                            @if($order->payment_status === 'pending' && $order->order_status !== 'cancelled' && !$order->isExpired())
                                <a href="{{ route('orders.pay', $order) }}" class="inline-flex items-center px-4 py-2 bg-green-700 hover:bg-green-800 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                                    <i class="fas fa-credit-card mr-2"></i> Bayar Sekarang
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $orders->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xl border border-gray-100 p-8 sm:p-12 text-center">
            <div class="max-w-md mx-auto">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6 text-green-600">
                    <i class="fas fa-shopping-bag text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Pesanan</h3>
                <p class="text-gray-600 mb-6">Anda belum memiliki pesanan. Ayo mulai berbelanja produk berkualitas kami!</p>
                <a href="{{ route('products') }}" class="inline-flex items-center px-6 py-3 bg-green-700 hover:bg-green-800 text-white font-medium rounded-xl shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                    <i class="fas fa-store mr-2"></i> Mulai Belanja
                </a>
            </div>
        </div>
    @endif
</div>

@push('styles')
<style>
/* Custom pagination styles to match the design */
.pagination {
    @apply flex justify-center space-x-1;
}

.pagination .page-item {
    @apply inline-flex;
}

.pagination .page-link {
    @apply px-3 py-1.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 border border-transparent;
}

.pagination .active .page-link {
    @apply bg-green-100 text-green-700 border-green-200;
}

.pagination .disabled .page-link {
    @apply text-gray-400 cursor-not-allowed hover:bg-transparent;
}
</style>
@endpush

@push('scripts')
<script>
// Any additional JavaScript can go here
document.addEventListener('DOMContentLoaded', function() {
    // Initialize any components if needed
});
</script>
@endpush
@endsection
