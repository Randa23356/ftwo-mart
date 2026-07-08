@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Animated Background Pattern -->
    <div class="fixed inset-0 opacity-30">
        <div class="absolute inset-0" style="background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%236366f1' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");"></div>
    </div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
        <!-- Modern Header -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl lg:rounded-3xl shadow-xl border border-white/20 p-6 lg:p-8 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="flex items-center space-x-4">
                    <div class="bg-green-700 p-3 lg:p-4 rounded-2xl shadow-lg">
                        <i class="fas fa-chart-line text-white text-xl lg:text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl lg:text-4xl font-bold text-gray-900">
                            Admin Dashboard
                        </h1>
                        <p class="text-gray-600 mt-1 text-sm lg:text-base">Kelola marketplace FtwoMart dengan mudah</p>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('admin.products.create') }}" 
                       class="group relative inline-flex items-center justify-center px-6 py-3 bg-green-700 hover:bg-green-800 text-white font-semibold rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <span class="absolute inset-0 rounded-xl bg-white opacity-0 group-hover:opacity-20 transition-opacity"></span>
                        <i class="fas fa-plus mr-2 relative z-10"></i> 
                        <span class="relative z-10">Tambah Produk</span>
                    </a>
                    <a href="{{ route('admin.settings') }}" 
                       class="group relative inline-flex items-center justify-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <span class="absolute inset-0 rounded-xl bg-white opacity-0 group-hover:opacity-20 transition-opacity"></span>
                        <i class="fas fa-cog mr-2 relative z-10"></i>
                        <span class="relative z-10">Pengaturan</span>
                    </a> 
                </div>
            </div>
        </div>
 
        <!-- Quick Actions dengan Vristo Style -->
        <div class="bg-white/70 backdrop-blur-lg rounded-2xl lg:rounded-3xl shadow-xl border border-white/20 p-6 lg:p-8 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl lg:text-2xl font-bold text-gray-800">Aksi Cepat</h2>
                <div class="h-1 w-20 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full"></div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 lg:gap-6">
                <a href="{{ route('chat.index') }}" 
                   class="group relative bg-green-50 p-6 rounded-2xl border border-green-100 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                    <div class="absolute top-0 right-0 w-20 h-20 bg-green-400/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative z-10">
                        <div class="bg-green-600 p-3 rounded-xl shadow-lg mb-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-comments text-white text-lg"></i>
                        </div>
                        <div class="space-y-1">
                            <div class="font-bold text-gray-800 text-base">Pesan & Chat</div>
                            <div class="text-sm text-gray-600">Lihat semua percakapan</div>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('admin.orders') }}" 
                   class="group relative bg-gradient-to-br from-emerald-50 to-green-100 p-6 rounded-2xl border border-emerald-100/50 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                    <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-emerald-400/20 to-green-400/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative z-10">
                        <div class="bg-gradient-to-br from-emerald-500 to-green-600 p-3 rounded-xl shadow-lg mb-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-shopping-cart text-white text-lg"></i>
                        </div>
                        <div class="space-y-1">
                            <div class="font-bold text-gray-800 text-base">Kelola Pesanan</div>
                            <div class="text-sm text-gray-600">Update status pesanan</div>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('admin.products.index') }}" 
                   class="group relative bg-gradient-to-br from-purple-50 to-pink-100 p-6 rounded-2xl border border-purple-100/50 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                    <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-purple-400/20 to-pink-400/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative z-10">
                        <div class="bg-gradient-to-br from-purple-500 to-pink-600 p-3 rounded-xl shadow-lg mb-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-tshirt text-white text-lg"></i>
                        </div>
                        <div class="space-y-1">
                            <div class="font-bold text-gray-800 text-base">Kelola Produk</div>
                            <div class="text-sm text-gray-600">Tambah & edit produk</div>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('admin.categories.index') }}" 
                   class="group relative bg-gradient-to-br from-blue-50 to-indigo-100 p-6 rounded-2xl border border-blue-100/50 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                    <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-blue-400/20 to-indigo-400/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative z-10">
                        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-3 rounded-xl shadow-lg mb-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-tags text-white text-lg"></i>
                        </div>
                        <div class="space-y-1">
                            <div class="font-bold text-gray-800 text-base">Kelola Kategori</div>
                            <div class="text-sm text-gray-600">Atur kategori produk</div>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('admin.users') }}" 
                   class="group relative bg-gradient-to-br from-orange-50 to-amber-100 p-6 rounded-2xl border border-orange-100/50 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                    <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-orange-400/20 to-amber-400/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative z-10">
                        <div class="bg-gradient-to-br from-orange-500 to-amber-600 p-3 rounded-xl shadow-lg mb-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-users text-white text-lg"></i>
                        </div>
                        <div class="space-y-1">
                            <div class="font-bold text-gray-800 text-base">Kelola User</div>
                            <div class="text-sm text-gray-600">Lihat data pelanggan</div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.services.index') }}" 
                   class="group relative bg-gradient-to-br from-teal-50 to-cyan-100 p-6 rounded-2xl border border-teal-100/50 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                    <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-teal-400/20 to-cyan-400/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative z-10">
                        <div class="bg-gradient-to-br from-teal-500 to-cyan-600 p-3 rounded-xl shadow-lg mb-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-concierge-bell text-white text-lg"></i>
                        </div>
                        <div class="space-y-1">
                            <div class="font-bold text-gray-800 text-base">Kelola Layanan</div>
                            <div class="text-sm text-gray-600">Atur layanan website</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Modern Stats Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
            <div class="group relative bg-white/80 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 p-6 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="absolute top-0 right-0 w-24 h-24 bg-green-400/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-green-600 p-3 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-shopping-cart text-white text-lg"></i>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl lg:text-3xl font-bold text-green-600">{{ $totalOrders }}</div>
                        </div>
                    </div>
                    <div class="text-sm font-medium text-gray-700">Total Pesanan</div>
                    <div class="text-xs text-gray-500 mt-1">+12% dari bulan lalu</div>
                </div>
            </div>
            
            <div class="group relative bg-white/80 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 p-6 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-purple-400/20 to-pink-400/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-gradient-to-br from-purple-500 to-pink-600 p-3 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-tshirt text-white text-lg"></i>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl lg:text-3xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">{{ $totalProducts }}</div>
                        </div>
                    </div>
                    <div class="text-sm font-medium text-gray-700">Koleksi Produk</div>
                    <div class="text-xs text-gray-500 mt-1">+8% dari bulan lalu</div>
                </div>
            </div>
            
            <div class="group relative bg-white/80 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 p-6 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-emerald-400/20 to-green-400/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-gradient-to-br from-emerald-500 to-green-600 p-3 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-users text-white text-lg"></i>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl lg:text-3xl font-bold bg-gradient-to-r from-emerald-600 to-green-600 bg-clip-text text-transparent">{{ $totalUsers }}</div>
                        </div>
                    </div>
                    <div class="text-sm font-medium text-gray-700">Total Pelanggan</div>
                    <div class="text-xs text-gray-500 mt-1">+15% dari bulan lalu</div>
                </div>
            </div>
            
            <div class="group relative bg-white/80 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 p-6 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-amber-400/20 to-orange-400/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-gradient-to-br from-amber-500 to-orange-600 p-3 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-tags text-white text-lg"></i>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl lg:text-3xl font-bold bg-gradient-to-r from-amber-600 to-orange-600 bg-clip-text text-transparent">{{ $totalCategories }}</div>
                        </div>
                    </div>
                    <div class="text-sm font-medium text-gray-700">Kategori Produk</div>
                    <div class="text-xs text-gray-500 mt-1">+2 kategori baru</div>
                </div>
            </div>
        </div>

        <!-- Messages Stats dengan Modern Design -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6 mb-8">
            <div class="group relative bg-green-50 rounded-2xl shadow-xl border border-green-100 p-6 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="absolute top-0 right-0 w-24 h-24 bg-green-400/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-green-600 p-3 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-comments text-white text-lg"></i>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl lg:text-3xl font-bold text-green-600">{{ $totalConversations }}</div>
                        </div>
                    </div>
                    <div class="text-sm font-medium text-gray-700">Total Percakapan</div>
                    <div class="text-xs text-gray-500 mt-1">Aktif hari ini</div>
                </div>
            </div>
            
            <div class="group relative bg-gradient-to-br from-orange-50 to-amber-100 rounded-2xl shadow-xl border border-orange-100/50 p-6 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-orange-400/20 to-amber-400/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-gradient-to-br from-orange-500 to-amber-600 p-3 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-user-secret text-white text-lg"></i>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl lg:text-3xl font-bold bg-gradient-to-r from-orange-600 to-amber-600 bg-clip-text text-transparent">{{ $guestMessages }}</div>
                        </div>
                    </div>
                    <div class="text-sm font-medium text-gray-700">Pesan dari Guest</div>
                    <div class="text-xs text-gray-500 mt-1">Perlu follow-up</div>
                </div>
            </div>
            
            <div class="group relative bg-gradient-to-br from-purple-50 to-pink-100 rounded-2xl shadow-xl border border-purple-100/50 p-6 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-purple-400/20 to-pink-400/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-gradient-to-br from-purple-500 to-pink-600 p-3 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-arrow-right text-white text-lg"></i>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Quick Action</div>
                        </div>
                    </div>
                    <div class="text-sm font-medium text-gray-700">Aksi Cepat</div>
                    <a href="{{ route('chat.index') }}" class="inline-flex items-center mt-2 text-sm font-medium text-purple-600 hover:text-purple-800 transition-colors">
                        <span>Lihat Semua Pesan</span>
                        <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Orders & Top Products dengan Vristo Style -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 lg:gap-8">
            <!-- Recent Orders -->
            <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                <div class="relative bg-gradient-to-r from-indigo-50 to-blue-50 px-6 lg:px-8 py-4 lg:py-6 border-b border-indigo-100/50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="bg-gradient-to-br from-indigo-500 to-blue-600 p-2 rounded-xl shadow-lg">
                                <i class="fas fa-receipt text-white text-sm"></i>
                            </div>
                            <h2 class="text-lg lg:text-xl font-bold bg-gradient-to-r from-indigo-600 to-blue-600 bg-clip-text text-transparent">
                                Pesanan Terbaru
                            </h2>
                        </div>
                        <div class="h-1 w-16 bg-gradient-to-r from-indigo-500 to-blue-500 rounded-full"></div>
                    </div>
                </div>
                <div class="divide-y divide-gray-100/50">
                    @forelse($recentOrders as $order)
                        <div class="px-6 lg:px-8 py-4 lg:py-5 hover:bg-gradient-to-r hover:from-indigo-50/30 hover:to-blue-50/30 transition-all duration-300 group">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <div class="bg-gradient-to-br from-indigo-100 to-blue-100 p-2 rounded-lg group-hover:scale-110 transition-transform duration-300">
                                            <i class="fas fa-hashtag text-indigo-600 text-xs font-bold"></i>
                                        </div>
                                        <div class="font-bold text-gray-900 text-sm lg:text-base truncate">{{ $order->order_number }}</div>
                                    </div>
                                    <div class="flex items-center space-x-2 text-xs sm:text-sm text-gray-600">
                                        <span class="font-medium">{{ $order->user->name }}</span>
                                        <span class="text-gray-400">•</span>
                                        <span>{{ $order->created_at->format('d M Y H:i') }}</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-xs text-gray-600 mb-1">Total</div>
                                    <div class="font-bold bg-gradient-to-r from-indigo-600 to-blue-600 bg-clip-text text-transparent text-sm lg:text-base">
                                        {{ 'Rp ' . number_format($order->total_amount, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 lg:px-8 py-8 lg:py-12 text-center">
                            <div class="bg-gradient-to-br from-gray-100 to-gray-200 w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center">
                                <i class="fas fa-inbox text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-gray-600 text-sm lg:text-base">Belum ada pesanan</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Top Products -->
            <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                <div class="relative bg-gradient-to-r from-purple-50 to-pink-50 px-6 lg:px-8 py-4 lg:py-6 border-b border-purple-100/50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="bg-gradient-to-br from-purple-500 to-pink-600 p-2 rounded-xl shadow-lg">
                                <i class="fas fa-fire text-white text-sm"></i>
                            </div>
                            <h2 class="text-lg lg:text-xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                                Produk Terlaris
                            </h2>
                        </div>
                        <div class="h-1 w-16 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full"></div>
                    </div>
                </div>
                <div class="divide-y divide-gray-100/50">
                    @forelse($topProducts as $index => $p)
                        <div class="px-6 lg:px-8 py-4 lg:py-5 hover:bg-gradient-to-r hover:from-purple-50/30 hover:to-pink-50/30 transition-all duration-300 group">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3 flex-1 min-w-0">
                                    <div class="relative">
                                        <div class="bg-gradient-to-br from-purple-100 to-pink-100 w-10 h-10 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                            <span class="text-purple-600 font-bold text-sm">{{ $index + 1 }}</span>
                                        </div>
                                        @if($index === 0)
                                            <div class="absolute -top-1 -right-1 bg-gradient-to-br from-yellow-400 to-orange-500 w-4 h-4 rounded-full flex items-center justify-center">
                                                <i class="fas fa-crown text-white text-xs"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2">
                                            <div class="font-bold text-gray-900 text-sm lg:text-base truncate">{{ $p->name }}</div>
                                            @if($p->trashed())
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-700">
                                                    <i class="fas fa-trash text-xs mr-1"></i>
                                                    Dihapus
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-600 mt-1">{{ $p->category->name ?? 'Uncategorized' }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="bg-gradient-to-r from-purple-100 to-pink-100 px-3 py-1 rounded-full">
                                        <span class="text-purple-700 font-bold text-sm">{{ $p->order_items_count }}</span>
                                        <span class="text-purple-600 text-xs"> terjual</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 lg:px-8 py-8 lg:py-12 text-center">
                            <div class="bg-gradient-to-br from-gray-100 to-gray-200 w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center">
                                <i class="fas fa-chart-line text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-gray-600 text-sm lg:text-base">Belum ada data</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
