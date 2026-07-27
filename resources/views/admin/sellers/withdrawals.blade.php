@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl lg:rounded-3xl shadow-xl border border-white/20 p-6 lg:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <h1 class="text-2xl font-bold text-gray-900"><i class="fas fa-money-bill-wave mr-2 text-orange-600"></i>Penarikan Seller</h1>
                <form method="GET" class="flex gap-2">
                    <select name="status" class="px-3 py-2 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-green-500">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                    <button class="bg-green-600 text-white px-4 py-2 rounded-xl text-sm hover:bg-green-700"><i class="fas fa-search"></i></button>
                </form>
            </div>

            @if($withdrawals->isEmpty())
                <div class="text-center py-12 text-gray-500">
                    <i class="fas fa-money-bill-wave text-5xl text-gray-300 mb-4"></i>
                    <p>Belum ada permintaan penarikan</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-2 font-semibold text-gray-600">Tanggal</th>
                                <th class="text-left py-3 px-2 font-semibold text-gray-600">Toko</th>
                                <th class="text-right py-3 px-2 font-semibold text-gray-600">Jumlah</th>
                                <th class="text-left py-3 px-2 font-semibold text-gray-600">Bank</th>
                                <th class="text-center py-3 px-2 font-semibold text-gray-600">Status</th>
                                <th class="text-center py-3 px-2 font-semibold text-gray-600">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($withdrawals as $w)
                            <tr class="border-b border-gray-50 hover:bg-gray-50">
                                <td class="py-3 px-2 text-gray-600">{{ $w->created_at->format('d M Y H:i') }}</td>
                                <td class="py-3 px-2">
                                    <span class="font-semibold text-gray-900">{{ $w->seller->shop_name }}</span>
                                    <br><span class="text-xs text-gray-500">{{ $w->seller->user->name }}</span>
                                </td>
                                <td class="py-3 px-2 text-right font-bold text-gray-900">{{ $w->formatted_amount }}</td>
                                <td class="py-3 px-2 text-gray-700">{{ $w->bank_name }} {{ $w->bank_account_number }}</td>
                                <td class="py-3 px-2 text-center">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $w->status_badge }}">
                                        {{ ucfirst($w->status) }}
                                    </span>
                                </td>
                                <td class="py-3 px-2 text-center">
                                    <a href="{{ route('admin.withdrawals.show', $w) }}"
                                       class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1 rounded-lg text-xs font-semibold">
                                        {{ $w->status === 'pending' ? 'Proses' : 'Detail' }}
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">{{ $withdrawals->withQueryString()->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
