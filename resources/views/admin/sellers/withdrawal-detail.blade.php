@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl lg:rounded-3xl shadow-xl border border-white/20 p-6 lg:p-8">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900"><i class="fas fa-money-bill-wave mr-2 text-orange-600"></i>Detail Penarikan</h1>
                <a href="{{ route('admin.withdrawals') }}" class="text-gray-500 hover:text-gray-700"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
            </div>

            <!-- Withdrawal Info -->
            <div class="bg-gray-50 rounded-xl p-5 mb-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500">Toko</p>
                        <p class="font-semibold text-gray-900">{{ $withdrawal->seller->shop_name }}</p>
                        <p class="text-sm text-gray-600">{{ $withdrawal->seller->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Jumlah</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $withdrawal->formatted_amount }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Bank</p>
                        <p class="font-semibold text-gray-900">{{ $withdrawal->bank_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">No. Rekening</p>
                        <p class="font-semibold text-gray-900">{{ $withdrawal->bank_account_number }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Atas Nama</p>
                        <p class="font-semibold text-gray-900">{{ $withdrawal->bank_account_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Status</p>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $withdrawal->status_badge }}">{{ ucfirst($withdrawal->status) }}</span>
                    </div>
                </div>
                @if($withdrawal->notes)
                <div class="mt-4 border-t border-gray-200 pt-4">
                    <p class="text-xs text-gray-500">Catatan Seller</p>
                    <p class="text-sm text-gray-700">{{ $withdrawal->notes }}</p>
                </div>
                @endif
            </div>

            <!-- Process (only for pending) -->
            @if($withdrawal->status === 'pending')
            <div class="border-t border-gray-100 pt-6">
                <h2 class="font-bold text-gray-800 mb-4">Proses Penarikan</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Catatan Admin</label>
                        <textarea name="admin_notes" id="admin_notes" rows="3"
                                  class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                  placeholder="Catatan untuk penarikan ini (opsional)"></textarea>
                    </div>

                    <div class="flex gap-3">
                        <form method="POST" action="{{ route('admin.withdrawals.process', $withdrawal) }}" class="flex-1" id="rejectForm">
                            @csrf @method('PUT')
                            <input type="hidden" name="action" value="reject">
                            <input type="hidden" name="admin_notes" id="reject_notes">
                            <button type="submit" onclick="document.getElementById('reject_notes').value = document.getElementById('admin_notes').value; return confirm('Tolak penarikan ini?')"
                                    class="w-full bg-red-100 hover:bg-red-200 text-red-700 font-bold py-3 rounded-xl transition-all">
                                <i class="fas fa-times mr-1"></i> Tolak
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.withdrawals.process', $withdrawal) }}" class="flex-1" id="completeForm">
                            @csrf @method('PUT')
                            <input type="hidden" name="action" value="complete">
                            <input type="hidden" name="admin_notes" id="complete_notes">
                            <button type="submit" onclick="document.getElementById('complete_notes').value = document.getElementById('admin_notes').value; return confirm('Proses penarikan ini? Saldo akan dikurangi.')"
                                    class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-bold py-3 rounded-xl transition-all shadow-lg">
                                <i class="fas fa-check mr-1"></i> Setujui & Proses
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @else
                @if($withdrawal->admin_notes)
                <div class="border-t border-gray-100 pt-6">
                    <h2 class="font-bold text-gray-800 mb-2">Catatan Admin</h2>
                    <p class="text-gray-700">{{ $withdrawal->admin_notes }}</p>
                </div>
                @endif
                @if($withdrawal->processed_at)
                <div class="mt-4 text-sm text-gray-500">
                    Diproses: {{ $withdrawal->processed_at->format('d M Y H:i') }}
                    @if($withdrawal->processedBy)
                        oleh {{ $withdrawal->processedBy->name }}
                    @endif
                </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
