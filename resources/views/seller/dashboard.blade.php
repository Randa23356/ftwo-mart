@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
        <!-- Header -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl lg:rounded-3xl shadow-xl border border-white/20 p-6 lg:p-8 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-3 lg:p-4 rounded-2xl shadow-lg">
                        <i class="fas fa-store text-white text-xl lg:text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">{{ $seller->shop_name }}</h1>
                        <p class="text-gray-600 text-sm">Seller Dashboard</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('seller.products.create') }}"
                       class="bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold px-5 py-2.5 rounded-xl transition-all shadow-lg">
                        <i class="fas fa-plus mr-1"></i> Produk Baru
                    </a>
                    <a href="{{ route('seller.products.index') }}"
                       class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-5 py-2.5 rounded-xl transition-all">
                        <i class="fas fa-box mr-1"></i> Produk
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Saldo</p>
                        <p class="text-xl lg:text-2xl font-bold text-green-600">{{ $seller->formatted_balance }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-xl">
                        <i class="fas fa-wallet text-green-600 text-lg"></i>
                    </div>
                </div>
                <a href="{{ route('seller.withdrawals') }}" class="text-green-600 text-xs font-semibold mt-2 inline-block hover:underline">Tarik Saldo →</a>
            </div>

            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Penghasilan</p>
                        <p class="text-xl lg:text-2xl font-bold text-gray-900">{{ $seller->formatted_total_earnings }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-xl">
                        <i class="fas fa-chart-line text-blue-600 text-lg"></i>
                    </div>
                </div>
                <a href="{{ route('seller.earnings') }}" class="text-blue-600 text-xs font-semibold mt-2 inline-block hover:underline">Lihat Detail →</a>
            </div>

            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Produk</p>
                        <p class="text-xl lg:text-2xl font-bold text-gray-900">{{ $totalProducts }}</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-xl">
                        <i class="fas fa-box text-purple-600 text-lg"></i>
                    </div>
                </div>
                <span class="text-xs text-gray-500">{{ $activeProducts }} aktif</span>
            </div>

            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Transaksi</p>
                        <p class="text-xl lg:text-2xl font-bold text-gray-900">{{ $totalOrders }}</p>
                    </div>
                    <div class="bg-orange-100 p-3 rounded-xl">
                        <i class="fas fa-shopping-cart text-orange-600 text-lg"></i>
                    </div>
                </div>
                @if($pendingWithdrawals > 0)
                    <span class="text-xs text-yellow-600 font-semibold">{{ $pendingWithdrawals }} penarikan pending</span>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl lg:rounded-3xl shadow-xl border border-white/20 p-6 lg:p-8 mb-8">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Aksi Cepat</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <a href="{{ route('seller.products.create') }}" class="flex flex-col items-center p-4 bg-green-50 hover:bg-green-100 rounded-xl transition-all">
                    <i class="fas fa-plus-circle text-green-600 text-2xl mb-2"></i>
                    <span class="text-sm font-semibold text-green-800">Tambah Produk</span>
                </a>
                <a href="{{ route('seller.orders') }}" class="flex flex-col items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-xl transition-all">
                    <i class="fas fa-receipt text-blue-600 text-2xl mb-2"></i>
                    <span class="text-sm font-semibold text-blue-800">Pesanan</span>
                </a>
                <a href="{{ route('seller.earnings') }}" class="flex flex-col items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-xl transition-all">
                    <i class="fas fa-coins text-purple-600 text-2xl mb-2"></i>
                    <span class="text-sm font-semibold text-purple-800">Penghasilan</span>
                </a>
                <a href="{{ route('seller.withdrawals') }}" class="flex flex-col items-center p-4 bg-orange-50 hover:bg-orange-100 rounded-xl transition-all">
                    <i class="fas fa-money-bill-wave text-orange-600 text-2xl mb-2"></i>
                    <span class="text-sm font-semibold text-orange-800">Tarik Uang</span>
                </a>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl lg:rounded-3xl shadow-xl border border-white/20 p-6 lg:p-8">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Transaksi Terbaru</h2>
            @if($recentTransactions->isEmpty())
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-receipt text-4xl text-gray-300 mb-3"></i>
                    <p>Belum ada transaksi</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-100">
                                <th class="text-left py-3 px-2 font-semibold text-gray-600">Produk</th>
                                <th class="text-right py-3 px-2 font-semibold text-gray-600">Pendapatan</th>
                                <th class="text-right py-3 px-2 font-semibold text-gray-600">Biaya Admin</th>
                                <th class="text-right py-3 px-2 font-semibold text-gray-600">Bersih</th>
                                <th class="text-center py-3 px-2 font-semibold text-gray-600">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentTransactions as $tx)
                            <tr class="border-b border-gray-50 hover:bg-gray-50">
                                <td class="py-3 px-2">
                                    <span class="font-medium text-gray-900">{{ $tx->product->name ?? '-' }}</span>
                                </td>
                                <td class="py-3 px-2 text-right text-gray-700">{{ $tx->formatted_gross_amount }}</td>
                                <td class="py-3 px-2 text-right text-red-500">-{{ $tx->formatted_commission }}</td>
                                <td class="py-3 px-2 text-right font-semibold text-green-600">{{ $tx->formatted_net_amount }}</td>
                                <td class="py-3 px-2 text-center">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $tx->status === 'settled' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $tx->status === 'settled' ? 'Selesai' : 'Pending' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
