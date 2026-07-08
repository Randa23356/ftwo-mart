@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-green-50/30 to-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <div class="max-w-7xl mx-auto">
            <!-- Enhanced Header -->
            <div class="mb-6 sm:mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">
                            <i class="fas fa-shopping-cart mr-3 text-green-600"></i>
                            Manajemen Pesanan
                        </h1>
                        <p class="text-sm sm:text-base text-gray-600">Kelola semua pesanan pelanggan</p>
                    </div>
                </div>
            </div>

            <!-- Filter Tabs -->
            <div class="bg-white/90 backdrop-blur-lg rounded-2xl md:rounded-3xl shadow-xl border border-white/20 p-4 md:p-6 mb-6 md:mb-8 overflow-hidden">
                <div class="flex flex-wrap gap-2 md:gap-3">
                    <a href="{{ route('admin.orders') }}" 
                       class="inline-flex items-center px-4 py-2 rounded-xl font-medium transition-all duration-300 transform hover:scale-105
                              {{ request()->is('admin/orders') && !request()->is('admin/orders/*') ? 
                                  'bg-gradient-to-r from-green-500 to-emerald-600 text-white shadow-lg' : 
                                  'bg-gray-100 text-gray-700 hover:bg-gray-200 border border-gray-300' }}">
                        <i class="fas fa-list mr-2 text-sm"></i>
                        <span class="hidden sm:inline">Semua Pesanan</span>
                        <span class="sm:hidden">Semua</span>
                    </a>
                    
                    <a href="{{ route('admin.orders.pending') }}" 
                       class="inline-flex items-center px-4 py-2 rounded-xl font-medium transition-all duration-300 transform hover:scale-105
                              {{ request()->is('admin/orders/pending*') ? 
                                  'bg-gradient-to-r from-yellow-500 to-orange-600 text-white shadow-lg' : 
                                  'bg-gray-100 text-gray-700 hover:bg-gray-200 border border-gray-300' }}">
                        <i class="fas fa-clock mr-2 text-sm"></i>
                        <span class="hidden sm:inline">Pending</span>
                        <span class="sm:hidden">Pending</span>
                    </a>
                    
                    <a href="{{ route('admin.orders.processing') }}" 
                       class="inline-flex items-center px-4 py-2 rounded-xl font-medium transition-all duration-300 transform hover:scale-105
                              {{ request()->is('admin/orders/processing*') ? 
                                  'bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-lg' : 
                                  'bg-gray-100 text-gray-700 hover:bg-gray-200 border border-gray-300' }}">
                        <i class="fas fa-spinner mr-2 text-sm"></i>
                        <span class="hidden sm:inline">Processing</span>
                        <span class="sm:hidden">Process</span>
                    </a>
                    
                    <a href="{{ route('admin.orders.ready') }}" 
                       class="inline-flex items-center px-4 py-2 rounded-xl font-medium transition-all duration-300 transform hover:scale-105
                              {{ request()->is('admin/orders/ready*') ? 
                                  'bg-gradient-to-r from-purple-500 to-pink-600 text-white shadow-lg' : 
                                  'bg-gray-100 text-gray-700 hover:bg-gray-200 border border-gray-300' }}">
                        <i class="fas fa-box mr-2 text-sm"></i>
                        <span class="hidden sm:inline">Ready</span>
                        <span class="sm:hidden">Ready</span>
                    </a>
                    
                    <a href="{{ route('admin.orders.shipped') }}" 
                       class="inline-flex items-center px-4 py-2 rounded-xl font-medium transition-all duration-300 transform hover:scale-105
                              {{ request()->is('admin/orders/shipped*') ? 
                                  'bg-gradient-to-r from-cyan-500 to-blue-600 text-white shadow-lg' : 
                                  'bg-gray-100 text-gray-700 hover:bg-gray-200 border border-gray-300' }}">
                        <i class="fas fa-shipping-fast mr-2 text-sm"></i>
                        <span class="hidden sm:inline">Shipped</span>
                        <span class="sm:hidden">Shipped</span>
                    </a>
                    
                    <a href="{{ route('admin.orders.delivered') }}" 
                       class="inline-flex items-center px-4 py-2 rounded-xl font-medium transition-all duration-300 transform hover:scale-105
                              {{ request()->is('admin/orders/delivered*') ? 
                                  'bg-gradient-to-r from-green-500 to-emerald-600 text-white shadow-lg' : 
                                  'bg-gray-100 text-gray-700 hover:bg-gray-200 border border-gray-300' }}">
                        <i class="fas fa-check-circle mr-2 text-sm"></i>
                        <span class="hidden sm:inline">Delivered</span>
                        <span class="sm:hidden">Delivered</span>
                    </a>
                    
                    <a href="{{ route('admin.orders.cancelled') }}" 
                       class="inline-flex items-center px-4 py-2 rounded-xl font-medium transition-all duration-300 transform hover:scale-105
                              {{ request()->is('admin/orders/cancelled*') ? 
                                  'bg-gradient-to-r from-red-500 to-pink-600 text-white shadow-lg' : 
                                  'bg-gray-100 text-gray-700 hover:bg-gray-200 border border-gray-300' }}">
                        <i class="fas fa-ban mr-2 text-sm"></i>
                        <span class="hidden sm:inline">Dibatalkan</span>
                        <span class="sm:hidden">Batal</span>
                    </a>
                    
                    <a href="{{ route('admin.orders.trash') }}" 
                       class="inline-flex items-center px-4 py-2 rounded-xl font-medium transition-all duration-300 transform hover:scale-105
                              bg-gradient-to-r from-red-100 to-pink-100 text-red-700 hover:from-red-200 hover:to-pink-200 border border-red-300">
                        <i class="fas fa-trash mr-2 text-sm"></i>
                        <span class="hidden sm:inline">Trash</span>
                        <span class="sm:hidden">Trash</span>
                    </a>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="bg-white/90 backdrop-blur-lg rounded-2xl md:rounded-3xl shadow-xl border border-white/20 overflow-hidden">
                <!-- Table Header -->
                <div class="bg-green-50 px-4 md:px-6 py-4 md:py-5 border-b border-gray-100">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-600 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                            <i class="fas fa-receipt text-white text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-base md:text-lg font-bold text-gray-900">Daftar Pesanan</h2>
                            <p class="text-xs text-gray-600">{{ $orders->total() }} pesanan</p>
                        </div>
                    </div>
                </div>
                
                <!-- Responsive Table Container -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 md:px-6 py-3 md:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Pesanan</th>
                                <th class="px-4 md:px-6 py-3 md:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider hidden lg:table-cell">Pelanggan</th>
                                <th class="px-4 md:px-6 py-3 md:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider hidden md:table-cell">Total</th>
                                <th class="px-4 md:px-6 py-3 md:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                                <th class="px-4 md:px-6 py-3 md:py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($orders as $order)
                                <tr class="hover:bg-green-50/30 transition-colors duration-200">
                                    <td class="px-4 md:px-6 py-4 md:py-5">
                                        <div class="flex flex-col">
                                            <a href="{{ route('admin.orders.detail', $order) }}" 
                                               class="font-bold text-green-600 hover:text-green-800 transition-colors text-sm md:text-base">
                                                {{ $order->order_number }}
                                            </a>
                                            <span class="text-xs text-gray-500 mt-1">{{ $order->created_at->format('d M Y H:i') }}</span>
                                            <span class="text-xs text-gray-600 mt-1 lg:hidden">{{ $order->user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 md:px-6 py-4 md:py-5 hidden lg:table-cell">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center text-white font-bold text-xs shadow">
                                                {{ strtoupper(substr($order->user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900 text-sm">{{ $order->user->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $order->user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 md:px-6 py-4 md:py-5 hidden md:table-cell">
                                        <span class="font-bold text-green-600 text-sm md:text-base">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-4 md:px-6 py-4 md:py-5">
                                        @if($order->order_status === 'pending')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i>Pending
                                            </span>
                                        @elseif($order->order_status === 'processing')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-spinner mr-1"></i>Processing
                                            </span>
                                        @elseif($order->order_status === 'ready')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                <i class="fas fa-box mr-1"></i>Ready
                                            </span>
                                        @elseif($order->order_status === 'shipped')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">
                                                <i class="fas fa-shipping-fast mr-1"></i>Shipped
                                            </span>
                                        @elseif($order->order_status === 'delivered')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>Delivered
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-ban mr-1"></i>Cancelled
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 md:px-6 py-4 md:py-5">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="{{ route('admin.orders.detail', $order) }}" 
                                               class="inline-flex items-center px-3 py-2 bg-green-700 hover:bg-green-800 text-white text-xs md:text-sm font-medium rounded-lg transition-all duration-300 transform hover:scale-105 shadow">
                                                <i class="fas fa-eye mr-1"></i>
                                                <span class="hidden sm:inline">Detail</span>
                                            </a>
                                            
                                            <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" 
                                                        onclick="handleDeleteOrder(this, '{{ $order->order_number }}', {{ $order->id }})" 
                                                        class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-red-500 to-pink-600 text-white text-xs md:text-sm font-medium rounded-lg hover:from-red-600 hover:to-pink-700 transition-all duration-300 transform hover:scale-105 shadow">
                                                    <i class="fas fa-trash mr-1"></i>
                                                    <span class="hidden sm:inline">Hapus</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center space-y-4">
                                            <div class="bg-gradient-to-r from-gray-100 to-gray-200 w-16 h-16 rounded-full flex items-center justify-center">
                                                <i class="fas fa-inbox text-gray-400 text-xl"></i>
                                            </div>
                                            <div>
                                                <div class="text-gray-600 font-medium text-lg">Belum ada pesanan</div>
                                                <div class="text-gray-500 text-sm mt-1">Pesanan akan muncul di sini</div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($orders->hasPages())
                <div class="px-4 md:px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $orders->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
async function handleDeleteOrder(button, orderNumber, orderId) {
    const confirmed = await Swal.fire({
        title: 'Hapus Pesanan?',
        html: `Apakah Anda yakin ingin menghapus pesanan "<strong>${orderNumber}</strong>"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    });
    
    if (confirmed.isConfirmed) {
        const form = button.closest('form');
        loadingOverlay.show('Menghapus pesanan...');
        form.submit();
    }
}
</script>
@endpush
