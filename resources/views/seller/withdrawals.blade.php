@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
        <!-- Balance Card + Withdraw -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="lg:col-span-2 bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-wallet mr-2 text-green-600"></i>Saldo Saya</h2>
                <div class="text-3xl font-bold text-green-600 mb-2">{{ $seller->formatted_balance }}</div>
                <p class="text-gray-500 text-sm">Total ditarik: {{ $seller->formatted_total_withdrawn }}</p>
            </div>

            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-money-bill-wave mr-2 text-orange-600"></i>Tarik Uang</h2>
                @if($seller->balance >= 50000)
                <form method="POST" action="{{ route('seller.withdrawals.store') }}">
                    @csrf
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Jumlah (min Rp 50.000)</label>
                            <input type="number" name="amount" min="50000" max="{{ $seller->balance }}" required
                                   class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-green-500"
                                   placeholder="50000">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Bank</label>
                            <input type="text" name="bank_name" value="{{ $seller->bank_name }}" readonly
                                   class="w-full px-3 py-2 rounded-lg border border-gray-100 bg-gray-50 text-sm text-gray-600">
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">No. Rekening</label>
                                <input type="text" name="bank_account_number" value="{{ $seller->bank_account_number }}" readonly
                                       class="w-full px-3 py-2 rounded-lg border border-gray-100 bg-gray-50 text-sm text-gray-600">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Atas Nama</label>
                                <input type="text" name="bank_account_name" value="{{ $seller->bank_account_name }}" readonly
                                       class="w-full px-3 py-2 rounded-lg border border-gray-100 bg-gray-50 text-sm text-gray-600">
                            </div>
                        </div>
                        <button type="submit" onclick="return confirm('Ajukan penarikan?')"
                                class="w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold py-2.5 rounded-xl transition-all shadow-lg text-sm">
                            <i class="fas fa-paper-plane mr-1"></i> Ajukan Penarikan
                        </button>
                    </div>
                </form>
                @else
                <div class="text-center py-4 text-gray-500 text-sm">
                    <p>Saldo minimal Rp 50.000 untuk penarikan</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Withdrawal History -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl lg:rounded-3xl shadow-xl border border-white/20 p-6 lg:p-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-6"><i class="fas fa-history mr-2 text-blue-600"></i>Riwayat Penarikan</h1>

            @if($withdrawals->isEmpty())
                <div class="text-center py-12">
                    <i class="fas fa-money-bill-wave text-5xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">Belum ada riwayat penarikan</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-2 font-semibold text-gray-600">Tanggal</th>
                                <th class="text-right py-3 px-2 font-semibold text-gray-600">Jumlah</th>
                                <th class="text-left py-3 px-2 font-semibold text-gray-600">Bank</th>
                                <th class="text-center py-3 px-2 font-semibold text-gray-600">Status</th>
                                <th class="text-left py-3 px-2 font-semibold text-gray-600">Catatan Admin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($withdrawals as $w)
                            <tr class="border-b border-gray-50 hover:bg-gray-50">
                                <td class="py-3 px-2 text-gray-600">{{ $w->created_at->format('d M Y H:i') }}</td>
                                <td class="py-3 px-2 text-right font-bold text-gray-900">{{ $w->formatted_amount }}</td>
                                <td class="py-3 px-2 text-gray-700">{{ $w->bank_name }} - {{ $w->bank_account_number }}</td>
                                <td class="py-3 px-2 text-center">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $w->status_badge }}">
                                        {{ ucfirst($w->status) }}
                                    </span>
                                </td>
                                <td class="py-3 px-2 text-gray-500 text-xs">{{ $w->admin_notes ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">{{ $withdrawals->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
