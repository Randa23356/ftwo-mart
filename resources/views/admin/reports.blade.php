@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="fixed inset-0 opacity-20 pointer-events-none">
        <div class="absolute inset-0" style="background-image: url(\"data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2316a34a' fill-opacity='0.06'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E\");"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

        {{-- Header --}}
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-6 sm:p-8 mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-br from-green-600 to-emerald-700 p-3 sm:p-4 rounded-2xl shadow-lg flex-shrink-0">
                        <i class="fas fa-chart-bar text-white text-xl sm:text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Laporan</h1>
                        <p class="text-sm sm:text-base text-gray-500 mt-0.5">Ringkasan data penjualan tahun {{ date('Y') }}</p>
                    </div>
                </div>
                <nav class="flex items-center space-x-2 text-sm text-gray-500">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-green-600 transition-colors font-medium">
                        <i class="fas fa-home mr-1"></i> Admin
                    </a>
                    <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
                    <span class="text-green-600 font-semibold">Laporan</span>
                </nav>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Monthly Orders --}}
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-green-100">
                    <h2 class="text-base font-bold text-gray-900"><i class="fas fa-shopping-cart mr-2 text-green-600"></i> Pesanan per Bulan</h2>
                </div>
                <div class="p-6">
                    @if(count($monthlyOrders) > 0)
                        <div class="space-y-3">
                            @foreach($monthlyOrders as $month => $count)
                                @php
                                    $monthName = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$month] ?? $month;
                                    $maxCount = max($monthlyOrders);
                                    $width = $maxCount > 0 ? ($count / $maxCount * 100) : 0;
                                @endphp
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="font-medium text-gray-700">{{ $monthName }}</span>
                                        <span class="text-gray-500">{{ $count }} pesanan</span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-2.5">
                                        <div class="bg-gradient-to-r from-green-500 to-emerald-500 h-2.5 rounded-full transition-all" style="width: {{ $width }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-400 text-center py-4">Belum ada data pesanan</p>
                    @endif
                </div>
            </div>

            {{-- Monthly Revenue --}}
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-green-100">
                    <h2 class="text-base font-bold text-gray-900"><i class="fas fa-money-bill-wave mr-2 text-green-600"></i> Pendapatan per Bulan</h2>
                </div>
                <div class="p-6">
                    @if(count($monthlyRevenue) > 0)
                        <div class="space-y-3">
                            @foreach($monthlyRevenue as $month => $revenue)
                                @php
                                    $monthName = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$month] ?? $month;
                                    $maxRevenue = max($monthlyRevenue);
                                    $width = $maxRevenue > 0 ? ($revenue / $maxRevenue * 100) : 0;
                                @endphp
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="font-medium text-gray-700">{{ $monthName }}</span>
                                        <span class="text-gray-500">Rp {{ number_format($revenue, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-2.5">
                                        <div class="bg-gradient-to-r from-green-500 to-emerald-500 h-2.5 rounded-full transition-all" style="width: {{ $width }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-400 text-center py-4">Belum ada data pendapatan</p>
                    @endif
                </div>
            </div>

            {{-- Top Selling Products --}}
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-green-100">
                    <h2 class="text-base font-bold text-gray-900"><i class="fas fa-trophy mr-2 text-green-600"></i> Produk Terlaris (Top 10)</h2>
                </div>
                <div class="p-6">
                    @if($topSellingProducts->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-100">
                                        <th class="text-left py-2 text-xs font-bold text-gray-500 uppercase">#</th>
                                        <th class="text-left py-2 text-xs font-bold text-gray-500 uppercase">Produk</th>
                                        <th class="text-right py-2 text-xs font-bold text-gray-500 uppercase">Terjual</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topSellingProducts as $product)
                                        <tr class="border-b border-gray-50">
                                            <td class="py-2.5 text-gray-400 font-medium">{{ $loop->iteration }}</td>
                                            <td class="py-2.5 font-medium text-gray-800">{{ $product->name }}</td>
                                            <td class="py-2.5 text-right">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                                    {{ $product->order_items_count }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-sm text-gray-400 text-center py-4">Belum ada data penjualan</p>
                    @endif
                </div>
            </div>

            {{-- Payment Method Stats --}}
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-green-100">
                    <h2 class="text-base font-bold text-gray-900"><i class="fas fa-credit-card mr-2 text-green-600"></i> Metode Pembayaran</h2>
                </div>
                <div class="p-6">
                    @if(count($paymentMethodStats) > 0)
                        @php $totalPayments = array_sum($paymentMethodStats); @endphp
                        <div class="space-y-4">
                            @foreach($paymentMethodStats as $method => $count)
                                @php $percentage = $totalPayments > 0 ? round($count / $totalPayments * 100) : 0; @endphp
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="font-medium text-gray-700 uppercase">{{ $method ?? '-' }}</span>
                                        <span class="text-gray-500">{{ $count }} transaksi ({{ $percentage }}%)</span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-2.5">
                                        <div class="bg-gradient-to-r from-green-500 to-emerald-500 h-2.5 rounded-full transition-all" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-400 text-center py-4">Belum ada data pembayaran</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
