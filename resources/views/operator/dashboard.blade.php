@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Animated Background Pattern -->
    <div class="fixed inset-0 opacity-30">
        <div class="absolute inset-0" style="background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%236366f1' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");"></div>
    </div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
        <!-- Modern Header Vristo Style -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl lg:rounded-3xl shadow-xl border border-white/20 p-6 lg:p-8 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="flex items-center space-x-4">
                    <div class="bg-green-600 p-3 lg:p-4 rounded-2xl shadow-lg">
                        <i class="fas fa-tachometer-alt text-white text-xl lg:text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl lg:text-4xl font-bold text-gray-900">
                            Operator Dashboard
                        </h1>
                        <p class="text-gray-600 mt-1 text-sm lg:text-base">Kelola produk dan pesanan FtwoMart</p>
                    </div>
                </div> 
                
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('operator.products.create') }}" 
                       class="group relative inline-flex items-center justify-center px-6 py-3 bg-green-700 hover:bg-green-800 text-white font-semibold rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <span class="absolute inset-0 rounded-xl bg-white opacity-0 group-hover:opacity-20 transition-opacity"></span>
                        <i class="fas fa-plus mr-2 relative z-10"></i>
                        <span class="relative z-10">Tambah Produk</span>
                    </a>
                    <a href="{{ route('operator.orders') }}" 
                       class="group relative inline-flex items-center justify-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <span class="absolute inset-0 rounded-xl bg-white opacity-0 group-hover:opacity-20 transition-opacity"></span>
                        <i class="fas fa-list mr-2 relative z-10"></i>
                        <span class="relative z-10">Kelola Pesanan</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Modern Status Counters Vristo Style -->
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 lg:gap-6 mb-8">
            <!-- Pending -->
            <div class="group relative bg-white/80 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 p-6 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="absolute top-0 right-0 w-24 h-24 bg-yellow-400/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex flex-col items-center text-center space-y-4">
                        <div class="bg-yellow-500 p-4 rounded-2xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-clock text-white text-xl"></i>
                        </div>
                        <div class="space-y-1">
                            <div class="text-2xl lg:text-3xl font-bold text-yellow-600">{{ $pendingOrders }}</div>
                            <div class="text-sm font-medium text-gray-700">Pending</div>
                            <div class="text-xs text-gray-500">Menunggu konfirmasi</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Processing -->
            <div class="group relative bg-white/80 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 p-6 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="absolute top-0 right-0 w-24 h-24 bg-green-400/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex flex-col items-center text-center space-y-4">
                        <div class="bg-green-600 p-4 rounded-2xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-cog text-white text-xl"></i>
                        </div>
                        <div class="space-y-1">
                            <div class="text-2xl lg:text-3xl font-bold text-green-600">{{ $processingOrders }}</div>
                            <div class="text-sm font-medium text-gray-700">Processing</div>
                            <div class="text-xs text-gray-500">Sedang diproses</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Ready -->
            <div class="group relative bg-white/80 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 p-6 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="absolute top-0 right-0 w-24 h-24 bg-green-500/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex flex-col items-center text-center space-y-4">
                        <div class="bg-green-500 p-4 rounded-2xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-box text-white text-xl"></i>
                        </div>
                        <div class="space-y-1">
                            <div class="text-2xl lg:text-3xl font-bold text-green-600">{{ $readyOrders }}</div>
                            <div class="text-sm font-medium text-gray-700">Ready</div>
                            <div class="text-xs text-gray-500">Siap dikirim</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Shipped -->
            <div class="group relative bg-white/80 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 p-6 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="absolute top-0 right-0 w-24 h-24 bg-green-400/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex flex-col items-center text-center space-y-4">
                        <div class="bg-green-600 p-4 rounded-2xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-truck text-white text-xl"></i>
                        </div>
                        <div class="space-y-1">
                            <div class="text-2xl lg:text-3xl font-bold text-green-700">{{ $shippedOrders }}</div>
                            <div class="text-sm font-medium text-gray-700">Shipped</div>
                            <div class="text-xs text-gray-500">Sedang dikirim</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Delivered -->
            <div class="group relative bg-white/80 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 p-6 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="absolute top-0 right-0 w-24 h-24 bg-green-500/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex flex-col items-center text-center space-y-4">
                        <div class="bg-green-500 p-4 rounded-2xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-check-circle text-white text-xl"></i>
                        </div>
                        <div class="space-y-1">
                            <div class="text-2xl lg:text-3xl font-bold text-green-700">{{ $deliveredOrders }}</div>
                            <div class="text-sm font-medium text-gray-700">Delivered</div>
                            <div class="text-xs text-gray-500">Terkirim</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders dengan Modern Design -->
        <div class="bg-white/90 backdrop-blur-lg rounded-2xl md:rounded-3xl shadow-xl border border-white/20 overflow-hidden">
            <div class="relative bg-green-50 px-6 md:px-8 py-4 md:py-6 border-b border-gray-100/50">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center space-x-3">
                        <div class="bg-green-600 p-2 rounded-lg shadow-lg">
                            <i class="fas fa-shopping-bag text-white text-sm"></i>
                        </div>
                        <h2 class="text-lg md:text-xl font-bold text-green-700">
                            Pesanan Terbaru
                        </h2>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                        <a href="{{ route('operator.products.index') }}" 
                           class="inline-flex items-center px-3 md:px-4 py-2 bg-green-700 hover:bg-green-800 text-white text-sm font-medium rounded-lg transition-all duration-300 transform hover:scale-105 shadow">
                            <i class="fas fa-store mr-2 text-xs"></i>
                            <span class="hidden sm:inline">Kelola Produk</span>
                            <span class="sm:hidden">Produk</span>
                        </a>
                        <a href="{{ route('operator.orders') }}" 
                           class="inline-flex items-center px-3 md:px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all duration-300 transform hover:scale-105 shadow">
                            <i class="fas fa-list mr-2 text-xs"></i>
                            <span class="hidden sm:inline">Lihat Semua</span>
                            <span class="sm:hidden">Semua</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="divide-y divide-gray-100/50">
                @forelse($recentOrders as $order)
                    <div class="px-4 md:px-6 lg:px-8 py-4 md:py-5 hover:bg-green-50/50 transition-colors duration-300">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                                    <div>
                                        <div class="font-bold text-gray-900 text-sm md:text-base truncate">{{ $order->order_number }}</div>
                                        <div class="text-xs md:text-sm text-gray-600 mt-1">
                                            <span class="font-medium">{{ $order->user->name }}</span>
                                            <span class="text-gray-400 mx-2">•</span>
                                            <span>{{ $order->created_at->format('d M Y H:i') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="px-2.5 md:px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $order->order_status === 'delivered' ? 'bg-green-100 text-green-800 border border-green-200' : 
                                       ($order->order_status === 'cancelled' ? 'bg-red-100 text-red-800 border border-red-200' : 
                                       'bg-green-50 text-green-700 border border-green-200') }}">
                                    {{ ucfirst($order->order_status) }}
                                </span>
                                <a href="{{ route('operator.orders.detail', $order) }}" 
                                   class="text-green-600 hover:text-green-800 transition-colors">
                                    <i class="fas fa-chevron-right text-sm"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <div class="bg-gradient-to-r from-gray-100 to-gray-200 w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <i class="fas fa-inbox text-gray-400 text-xl"></i>
                        </div>
                        <div class="text-gray-600 font-medium">Belum ada pesanan</div>
                        <div class="text-gray-500 text-sm mt-1">Pesanan baru akan muncul di sini</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
