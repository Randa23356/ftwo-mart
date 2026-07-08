@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
        <!-- Header dengan Glassmorphism -->
        <div class="relative bg-white/80 backdrop-blur-lg rounded-2xl md:rounded-3xl shadow-xl border border-white/20 p-6 md:p-8 mb-6 md:mb-8 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-red-500/10 to-orange-600/10"></div>
            <div class="relative z-10">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 md:gap-6">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 md:space-x-4 mb-3 md:mb-4">
                            <div class="bg-gradient-to-r from-red-500 to-orange-600 p-2 md:p-3 rounded-xl md:rounded-2xl shadow-lg">
                                <i class="fas fa-trash text-white text-lg md:text-xl"></i>
                            </div>
                            <div> 
                                <h1 class="text-2xl md:text-4xl font-bold bg-gradient-to-r from-red-600 to-orange-600 bg-clip-text text-transparent">
                                    Pesanan di Trash
                                </h1>
                                <p class="text-gray-600 text-sm md:text-base mt-1">Kelola pesanan yang telah dihapus</p>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('operator.orders') }}"
                       class="inline-flex items-center px-4 md:px-6 py-2.5 md:py-3 bg-gradient-to-r from-gray-600 to-gray-700 text-white font-medium rounded-xl hover:from-gray-700 hover:to-gray-800 transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-arrow-left mr-2 text-sm"></i>
                        <span class="hidden sm:inline">Kembali</span>
                        <span class="sm:hidden">Kembali</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Trash Orders Table dengan Modern Design -->
        <div class="bg-white/90 backdrop-blur-lg rounded-2xl md:rounded-3xl shadow-xl border border-white/20 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gradient-to-r from-red-50 to-orange-50 border-b border-red-100/50">
                        <tr>
                            <th class="px-4 md:px-6 py-3 md:py-4 text-left">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-hashtag text-red-600 text-sm"></i>
                                    <span class="text-xs md:text-sm font-semibold text-gray-700 uppercase tracking-wider">No. Pesanan</span>
                                </div>
                            </th>
                            <th class="px-4 md:px-6 py-3 md:py-4 text-left">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-user text-red-600 text-sm"></i>
                                    <span class="text-xs md:text-sm font-semibold text-gray-700 uppercase tracking-wider">Pelanggan</span>
                                </div>
                            </th>
                            <th class="px-4 md:px-6 py-3 md:py-4 text-left">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-money-bill-wave text-red-600 text-sm"></i>
                                    <span class="text-xs md:text-sm font-semibold text-gray-700 uppercase tracking-wider">Total</span>
                                </div>
                            </th>
                            <th class="px-4 md:px-6 py-3 md:py-4 text-left">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-calendar-times text-red-600 text-sm"></i>
                                    <span class="text-xs md:text-sm font-semibold text-gray-700 uppercase tracking-wider">Dihapus Pada</span>
                                </div>
                            </th>
                            <th class="px-4 md:px-6 py-3 md:py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <i class="fas fa-cogs text-red-600 text-sm"></i>
                                    <span class="text-xs md:text-sm font-semibold text-gray-700 uppercase tracking-wider">Aksi</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100/50">
                        @forelse($orders as $order)
                            <tr class="hover:bg-gradient-to-r hover:from-red-50/50 hover:to-orange-50/50 transition-colors duration-300">
                                <td class="px-4 md:px-6 py-4 md:py-5">
                                    <div class="space-y-1">
                                        <div class="font-bold text-gray-900 text-sm md:text-base">{{ $order->order_number }}</div>
                                        <div class="text-xs md:text-sm text-gray-500">
                                            <span class="line-through">Pesanan dihapus</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 md:px-6 py-4 md:py-5">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 md:w-10 md:h-10 bg-gradient-to-r from-red-100 to-orange-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user-slash text-red-600 text-xs md:text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900 text-sm md:text-base line-through">{{ $order->user->name ?? '-' }}</div>
                                            <div class="text-xs text-gray-500">Dihapus</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 md:px-6 py-4 md:py-5">
                                    <div class="font-bold text-gray-500 line-through text-sm md:text-base">{{ $order->formatted_total_amount }}</div>
                                </td>
                                <td class="px-4 md:px-6 py-4 md:py-5">
                                    <div class="space-y-1">
                                        <div class="flex items-center space-x-2 text-sm text-red-600 font-medium">
                                            <i class="fas fa-trash text-xs"></i>
                                            <span>{{ $order->deleted_at->format('d M Y H:i') }}</span>
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $order->deleted_at->diffForHumans() }}</div>
                                    </div>
                                </td>
                                <td class="px-4 md:px-6 py-4 md:py-5">
                                    <div class="flex items-center justify-end space-x-2">
                                        <!-- Restore Button -->
                                        <form action="{{ route('operator.orders.restore', $order->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit"
                                                    class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white text-xs md:text-sm font-medium rounded-lg hover:from-green-600 hover:to-emerald-700 transition-all duration-300 transform hover:scale-105 shadow">
                                                <i class="fas fa-undo mr-1"></i>
                                                <span class="hidden sm:inline">Pulihkan</span>
                                                <span class="sm:hidden">↩️</span>
                                            </button>
                                        </form>

                                        <!-- Force Delete Button -->
                                        <form action="{{ route('operator.orders.force-delete', $order->id) }}"
                                              method="POST"
                                              class="inline-block"
                                              onsubmit="return confirm('Yakin ingin menghapus permanen? Tindakan ini tidak dapat dibatalkan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-red-600 to-pink-600 text-white text-xs md:text-sm font-medium rounded-lg hover:from-red-700 hover:to-pink-700 transition-all duration-300 transform hover:scale-105 shadow">
                                                <i class="fas fa-trash-alt mr-1"></i>
                                                <span class="hidden sm:inline">Hapus Permanen</span>
                                                <span class="sm:hidden">🗑️</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center space-y-4">
                                        <div class="bg-gradient-to-r from-red-100 to-orange-100 w-16 h-16 rounded-full flex items-center justify-center">
                                            <i class="fas fa-trash-alt text-red-400 text-xl"></i>
                                        </div>
                                        <div>
                                            <div class="text-gray-600 font-medium text-lg">Trash Kosong</div>
                                            <div class="text-gray-500 text-sm mt-1">Belum ada pesanan yang dihapus</div>
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
                <div class="bg-gradient-to-r from-red-50 to-orange-50 px-6 md:px-8 py-4 md:py-6 border-t border-red-100/50">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-sm text-gray-600">
                            <span class="font-medium">Trash:</span>
                            <span class="ml-2">{{ $orders->firstItem() }} - {{ $orders->lastItem() }} dari {{ $orders->total() }} pesanan</span>
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
