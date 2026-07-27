@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
        <!-- Stats -->
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-5">
                <p class="text-sm text-gray-500">Total Penghasilan</p>
                <p class="text-xl font-bold text-gray-900">Rp {{ number_format($stats['total_earnings'], 0, ',', '.') }}</p>
            </div>
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-5">
                <p class="text-sm text-gray-500">Saldo Tersedia</p>
                <p class="text-xl font-bold text-green-600">Rp {{ number_format($stats['balance'], 0, ',', '.') }}</p>
            </div>
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-5">
                <p class="text-sm text-gray-500">Total Ditarik</p>
                <p class="text-xl font-bold text-blue-600">Rp {{ number_format($stats['total_withdrawn'], 0, ',', '.') }}</p>
            </div>
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-5">
                <p class="text-sm text-gray-500">Transaksi Selesai</p>
                <p class="text-xl font-bold text-gray-900">{{ $stats['settled_count'] }}</p>
            </div>
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-5">
                <p class="text-sm text-gray-500">Pending</p>
                <p class="text-xl font-bold text-yellow-600">{{ $stats['pending_count'] }}</p>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl lg:rounded-3xl shadow-xl border border-white/20 p-6 lg:p-8">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900"><i class="fas fa-coins mr-2 text-purple-600"></i>Riwayat Penghasilan</h1>
                <a href="{{ route('seller.dashboard') }}" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>

            @if($transactions->isEmpty())
                <div class="text-center py-12">
                    <i class="fas fa-coins text-5xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">Belum ada penghasilan</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-2 font-semibold text-gray-600">Tanggal</th>
                                <th class="text-left py-3 px-2 font-semibold text-gray-600">Produk</th>
                                <th class="text-right py-3 px-2 font-semibold text-gray-600">Harga Jual</th>
                                <th class="text-right py-3 px-2 font-semibold text-gray-600">Komisi</th>
                                <th class="text-right py-3 px-2 font-semibold text-gray-600">Bersih</th>
                                <th class="text-center py-3 px-2 font-semibold text-gray-600">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $tx)
                            <tr class="border-b border-gray-50 hover:bg-gray-50">
                                <td class="py-3 px-2 text-gray-600">{{ $tx->created_at->format('d M Y') }}</td>
                                <td class="py-3 px-2">
                                    <span class="font-medium text-gray-900">{{ $tx->product->name ?? '(produk dihapus)' }}</span>
                                    <br><span class="text-xs text-gray-500">Order #{{ $tx->order->order_number ?? '-' }}</span>
                                </td>
                                <td class="py-3 px-2 text-right text-gray-700">{{ $tx->formatted_gross_amount }}</td>
                                <td class="py-3 px-2 text-right text-red-500">-{{ $tx->formatted_commission }}</td>
                                <td class="py-3 px-2 text-right font-bold text-green-600">{{ $tx->formatted_net_amount }}</td>
                                <td class="py-3 px-2 text-center">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $tx->status === 'settled' ? 'bg-green-100 text-green-800' : ($tx->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ $tx->status === 'settled' ? 'Selesai' : ($tx->status === 'cancelled' ? 'Dibatalkan' : 'Pending') }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">{{ $transactions->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
