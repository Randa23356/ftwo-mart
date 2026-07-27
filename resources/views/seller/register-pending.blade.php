@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-2xl mx-auto px-4 py-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl lg:rounded-3xl shadow-xl border border-white/20 p-6 lg:p-8 text-center">
            @if($seller->approval_status === 'pending')
                <div class="bg-yellow-100 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Menunggu Persetujuan</h1>
                <p class="text-gray-600 mb-6">Registrasi seller kamu sedang diverifikasi oleh admin. Proses ini biasanya memakan waktu 1-2 hari kerja.</p>

                <div class="bg-gray-50 rounded-xl p-4 text-left space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Nama Toko</span>
                        <span class="font-semibold text-gray-900">{{ $seller->shop_name }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Status</span>
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Menunggu Verifikasi</span>
                    </div>
                </div>
            @elseif($seller->approval_status === 'rejected')
                <div class="bg-red-100 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-times-circle text-red-600 text-2xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Registrasi Ditolak</h1>
                <p class="text-gray-600 mb-4">Registrasi seller kamu ditolak oleh admin.</p>

                @if($seller->rejection_reason)
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 text-left">
                    <p class="text-sm font-semibold text-red-800 mb-1">Alasan Penolakan:</p>
                    <p class="text-sm text-red-700">{{ $seller->rejection_reason }}</p>
                </div>
                @endif

                <a href="{{ route('seller.register') }}" class="inline-block bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-bold px-6 py-3 rounded-xl transition-all shadow-lg">
                    <i class="fas fa-redo mr-1"></i> Daftar Ulang
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
