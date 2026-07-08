@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
        <!-- Header dengan Glassmorphism -->
        <div class="relative bg-white/80 backdrop-blur-lg rounded-2xl md:rounded-3xl shadow-xl border border-white/20 p-6 md:p-8 mb-6 md:mb-8 overflow-hidden">
            <div class="absolute inset-0 bg-green-500/10"></div>
            <div class="relative z-10">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 md:gap-6">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 md:space-x-4 mb-3 md:mb-4">
                            <div class="bg-green-600 p-2 md:p-3 rounded-xl md:rounded-2xl shadow-lg">
                                <i class="fas fa-list-alt text-white text-lg md:text-xl"></i>
                            </div> 
                            <div>
                                <h1 class="text-2xl md:text-4xl font-bold text-green-700">
                                    Semua Pesanan
                                </h1>
                                <p class="text-gray-600 text-sm md:text-base mt-1">Kelola dan pantau semua pesanan FtwoMart</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Filter Tabs -->
        <div class="bg-white/90 backdrop-blur-lg rounded-2xl md:rounded-3xl shadow-xl border border-white/20 p-4 md:p-6 mb-6 md:mb-8 overflow-hidden">
            <div class="flex flex-wrap gap-2 md:gap-3">
                <a href="{{ route('operator.orders') }}"
                   class="inline-flex items-center px-4 py-2 rounded-xl font-medium transition-all duration-300 transform hover:scale-105
                          {{ request()->is('operator/orders') ?
                              'bg-green-700 text-white shadow-lg' :
                              'bg-gray-100 text-gray-700 hover:bg-gray-200 border border-gray-300' }}">
                    <i class="fas fa-th-large mr-2 text-sm"></i>
                    <span class="hidden sm:inline">Semua</span>
                    <span class="sm:hidden">All</span>
                </a>

                <a href="{{ route('operator.orders.pending') }}"
                   class="inline-flex items-center px-4 py-2 rounded-xl font-medium transition-all duration-300 transform hover:scale-105
                          {{ request()->is('operator/orders/pending*') ?
                              'bg-gradient-to-r from-yellow-500 to-orange-600 text-white shadow-lg' :
                              'bg-gray-100 text-gray-700 hover:bg-gray-200 border border-gray-300' }}">
                    <i class="fas fa-clock mr-2 text-sm"></i>
                    <span class="hidden sm:inline">Pending</span>
                </a>

                <a href="{{ route('operator.orders.processing') }}"
                   class="inline-flex items-center px-4 py-2 rounded-xl font-medium transition-all duration-300 transform hover:scale-105
                          {{ request()->is('operator/orders/processing*') ?
                              'bg-gradient-to-r from-blue-500 to-cyan-600 text-white shadow-lg' :
                              'bg-gray-100 text-gray-700 hover:bg-gray-200 border border-gray-300' }}">
                    <i class="fas fa-cog mr-2 text-sm"></i>
                    <span class="hidden sm:inline">Processing</span>
                </a>

                <a href="{{ route('operator.orders.ready') }}"
                   class="inline-flex items-center px-4 py-2 rounded-xl font-medium transition-all duration-300 transform hover:scale-105
                          {{ request()->is('operator/orders/ready*') ?
                              'bg-green-600 text-white shadow-lg' :
                              'bg-gray-100 text-gray-700 hover:bg-gray-200 border border-gray-300' }}">
                    <i class="fas fa-check-circle mr-2 text-sm"></i>
                    <span class="hidden sm:inline">Siap</span>
                </a>

                <a href="{{ route('operator.orders.shipped') }}"
                   class="inline-flex items-center px-4 py-2 rounded-xl font-medium transition-all duration-300 transform hover:scale-105
                          {{ request()->is('operator/orders/shipped*') ?
                              'bg-green-700 text-white shadow-lg' :
                              'bg-gray-100 text-gray-700 hover:bg-gray-200 border border-gray-300' }}">
                    <i class="fas fa-truck mr-2 text-sm"></i>
                    <span class="hidden sm:inline">Dikirim</span>
                </a>

                <a href="{{ route('operator.orders.delivered') }}"
                   class="inline-flex items-center px-4 py-2 rounded-xl font-medium transition-all duration-300 transform hover:scale-105
                          {{ request()->is('operator/orders/delivered*') ?
                              'bg-green-500 text-white shadow-lg' :
                              'bg-gray-100 text-gray-700 hover:bg-gray-200 border border-gray-300' }}">
                    <i class="fas fa-star mr-2 text-sm"></i>
                    <span class="hidden sm:inline">Terkirim</span>
                </a>

                <a href="{{ route('operator.orders.cancelled') }}"
                   class="inline-flex items-center px-4 py-2 rounded-xl font-medium transition-all duration-300 transform hover:scale-105
                          {{ request()->is('operator/orders/cancelled*') ?
                              'bg-gradient-to-r from-red-500 to-pink-600 text-white shadow-lg' :
                              'bg-gray-100 text-gray-700 hover:bg-gray-200 border border-gray-300' }}">
                    <i class="fas fa-ban mr-2 text-sm"></i>
                    <span class="hidden sm:inline">Dibatalkan</span>
                </a>

                @can('order-delete')
                <a href="{{ route('operator.orders.trash') }}"
                   class="inline-flex items-center px-4 py-2 rounded-xl font-medium transition-all duration-300 transform hover:scale-105
                          bg-gradient-to-r from-red-100 to-pink-100 text-red-700 hover:from-red-200 hover:to-pink-200 border border-red-300">
                    <i class="fas fa-trash mr-2 text-sm"></i>
                    <span class="hidden sm:inline">Trash</span>
                </a>
                @endcan
            </div>
        </div>

        <!-- Orders Table dengan Modern Design -->
        <div class="bg-white/90 backdrop-blur-lg rounded-2xl md:rounded-3xl shadow-xl border border-white/20 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-green-50 border-b border-gray-100/50">
                        <tr>
                            <th class="px-4 md:px-6 py-3 md:py-4 text-left">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-hashtag text-green-600 text-sm"></i>
                                    <span class="text-xs md:text-sm font-semibold text-gray-700 uppercase tracking-wider">No. Pesanan</span>
                                </div>
                            </th>
                            <th class="px-4 md:px-6 py-3 md:py-4 text-left">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-user text-green-600 text-sm"></i>
                                    <span class="text-xs md:text-sm font-semibold text-gray-700 uppercase tracking-wider">Pelanggan</span>
                                </div>
                            </th>
                            <th class="px-4 md:px-6 py-3 md:py-4 text-left">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-money-bill-wave text-green-600 text-sm"></i>
                                    <span class="text-xs md:text-sm font-semibold text-gray-700 uppercase tracking-wider">Total</span>
                                </div>
                            </th>
                            <th class="px-4 md:px-6 py-3 md:py-4 text-left">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-info-circle text-green-600 text-sm"></i>
                                    <span class="text-xs md:text-sm font-semibold text-gray-700 uppercase tracking-wider">Status</span>
                                </div>
                            </th>
                            <th class="px-4 md:px-6 py-3 md:py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <i class="fas fa-cogs text-green-600 text-sm"></i>
                                    <span class="text-xs md:text-sm font-semibold text-gray-700 uppercase tracking-wider">Aksi</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100/50">
                        @forelse($orders as $order)
                            <tr class="hover:bg-green-50/50 transition-colors duration-300">
                                <td class="px-4 md:px-6 py-4 md:py-5">
                                    <div class="space-y-1">
                                        <div class="font-bold text-gray-900 text-sm md:text-base">{{ $order->order_number }}</div>
                                        <div class="text-xs md:text-sm text-gray-500 flex items-center space-x-2">
                                            <i class="fas fa-calendar-alt text-gray-400"></i>
                                            <span>{{ $order->created_at->format('d M Y H:i') }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 md:px-6 py-4 md:py-5">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 md:w-10 md:h-10 bg-green-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user text-green-600 text-xs md:text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900 text-sm md:text-base">{{ $order->user->name ?? '-' }}</div>
                                            <div class="text-xs text-gray-500">{{ strtolower($order->payment_method) === 'midtrans' ? 'Transfer' : ucfirst($order->payment_method) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 md:px-6 py-4 md:py-5">
                                    <div class="font-bold text-green-600 text-sm md:text-base">
                                        {{ $order->formatted_total_amount }}
                                    </div>
                                </td>
                                <td class="px-4 md:px-6 py-4 md:py-5">
                                    <span class="inline-flex px-3 py-1.5 text-xs leading-5 font-semibold rounded-full border
                                        {{ $order->order_status === 'delivered' ? 'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border-green-200' :
                                           ($order->order_status === 'cancelled' ? 'bg-gradient-to-r from-red-100 to-pink-100 text-red-800 border-red-200' :
                                           ($order->order_status === 'shipped' ? 'bg-green-50 text-green-700 border-green-200' :
                                           ($order->order_status === 'ready' ? 'bg-gradient-to-r from-green-100 to-teal-100 text-green-800 border-green-200' :
                                           ($order->order_status === 'processing' ? 'bg-gradient-to-r from-blue-100 to-cyan-100 text-blue-800 border-blue-200' :
                                           'bg-gradient-to-r from-yellow-100 to-orange-100 text-yellow-800 border-yellow-200')))) }}">
                                        {{ ucfirst($order->order_status) }}
                                    </span>
                                </td>
                                <td class="px-4 md:px-6 py-4 md:py-5">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('operator.orders.detail', $order) }}"
                                           class="inline-flex items-center px-3 py-2 bg-green-700 hover:bg-green-800 text-white text-xs md:text-sm font-medium rounded-lg transition-all duration-300 transform hover:scale-105 shadow">
                                            <i class="fas fa-eye mr-1"></i>
                                            <span class="hidden sm:inline">Detail</span>
                                        </a>

                                        @can('order-delete')
                                        <form action="{{ route('operator.orders.destroy', $order) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    onclick="handleDeleteOrder(this, '{{ $order->order_number }}', {{ $order->id }})"
                                                    class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-red-500 to-pink-600 text-white text-xs md:text-sm font-medium rounded-lg hover:from-red-600 hover:to-pink-700 transition-all duration-300 transform hover:scale-105 shadow">
                                                <i class="fas fa-trash mr-1"></i>
                                                <span class="hidden sm:inline">Hapus</span>
                                            </button>
                                        </form>
                                        @endcan
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
                                            <div class="text-gray-500 text-sm mt-1">Pesanan baru akan muncul di sini</div>
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
                <div class="bg-green-50 px-6 md:px-8 py-4 md:py-6 border-t border-gray-100/50">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-sm text-gray-600">
                            Menampilkan {{ $orders->firstItem() }} - {{ $orders->lastItem() }} dari {{ $orders->total() }} pesanan
                        </div>
                        <div class="flex items-center space-x-1">
                            {{ $orders->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
async function handleDeleteOrder(button, orderNumber, orderId) {
    const confirmed = await confirmDialog.show({
        title: 'Hapus Pesanan',
        message: `Apakah Anda yakin ingin menghapus pesanan "${orderNumber}"? Tindakan ini tidak dapat dibatalkan.`,
        confirmText: 'Ya, Hapus',
        cancelText: 'Tidak',
        icon: 'fas fa-trash text-red-500',
        danger: true
    });

    if (confirmed) {
        const form = button.closest('form');
        loadingOverlay.show('Menghapus pesanan...');
        form.submit();
    }
}
</script>
@endpush
