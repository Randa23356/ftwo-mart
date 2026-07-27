@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Pengembalian</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola permintaan pengembalian dari pembeli.</p>
    </div>

    @if($refunds->isEmpty())
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-undo text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-1">Belum Ada Pengembalian</h3>
            <p class="text-sm text-gray-500">Permintaan pengembalian akan muncul di sini.</p>
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Pesanan</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Pembeli</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Alasan</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($refunds as $refund)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <span class="font-semibold text-gray-900 text-sm">#{{ $refund->order->order_number }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-700">{{ $refund->user->name ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-700">{{ $refund->reason_label }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $refund->status_badge }}">
                                    {{ $refund->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs text-gray-500">{{ $refund->created_at->format('d M Y, H:i') }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.refunds.show', $refund) }}" class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1 rounded-lg text-xs font-semibold">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-6">{{ $refunds->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
