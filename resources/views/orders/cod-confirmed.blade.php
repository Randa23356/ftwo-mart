@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow p-6 text-center">
        <div class="text-green-600 text-5xl mb-4"><i class="fas fa-check-circle"></i></div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Pembayaran COD Terkonfirmasi</h1>
        <p class="text-gray-600 mb-6">Order {{ $order->order_number }}</p>
        <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 bg-green-700 hover:bg-green-800 text-white rounded-md font-semibold">
            Kembali ke Beranda
        </a>
    </div>
    <div class="mt-6 text-center text-sm text-gray-500">
        <p>Status pesanan: <span class="font-medium text-gray-800">{{ ucfirst($order->order_status) }}</span></p>
        <p>Status pembayaran: <span class="font-medium text-gray-800">{{ ucfirst($order->payment_status) }}</span></p>
    </div>
    </div>
@endsection


 