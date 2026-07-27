@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl lg:rounded-3xl shadow-xl border border-white/20 p-6 lg:p-8">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900"><i class="fas fa-receipt mr-2 text-blue-600"></i>Pesanan Saya</h1>
                <a href="{{ route('seller.dashboard') }}" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>

            @if($orders->isEmpty())
                <div class="text-center py-12">
                    <i class="fas fa-receipt text-5xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">Belum ada pesanan</p>
                    <p class="text-gray-400 text-sm mt-1">Pesanan yang berisi produk kamu akan muncul di sini</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($orders as $order)
                    @php
                        $myItems = $order->orderItems->filter(fn($item) => $item->product && $item->product->seller_id == $seller->id);
                        $myTotal = $myItems->sum('subtotal');
                        $myShare = $myTotal * (1 - $commissionRate / 100);
                    @endphp
                    <div class="bg-white border border-gray-100 rounded-xl p-4 hover:shadow-md transition-all cursor-pointer"
                         onclick="window.location='{{ route('seller.orders.show', $order) }}'">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-3">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-bold text-gray-900">#{{ $order->order_number }}</span>
                                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $order->status_badge }}">
                                        {{ ucfirst($order->order_status) }}
                                    </span>
                                    @if($order->payment_status === 'paid')
                                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">Lunas</span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500">{{ $order->user->name ?? 'User' }} &bull; {{ $order->created_at->format('d M Y H:i') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-400">Bagian kamu</p>
                                <p class="font-bold text-green-600">Rp {{ number_format($myShare, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-500">{{ ucfirst($order->payment_method) }}</p>
                            </div>
                        </div>

                        <!-- Items -->
                        <div class="bg-gray-50 rounded-xl p-3 space-y-2">
                            @foreach($order->orderItems as $item)
                            @php
                                $isMine = $item->product && $item->product->seller_id == $seller->id;
                            @endphp
                            <div class="flex items-center gap-3 text-sm {{ $isMine ? '' : 'opacity-50' }}">
                                <!-- Image -->
                                <div class="w-10 h-10 rounded-lg overflow-hidden bg-gray-200 flex-shrink-0">
                                    @if($item->product)
                                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                    @elseif($item->product_image)
                                        <img src="{{ asset('storage/' . $item->product_image) }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400 text-xs"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Info -->
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-gray-800 truncate">{{ $item->product_name ?? $item->product->name ?? 'Produk' }}</p>
                                    <p class="text-xs text-gray-400">x{{ $item->quantity }} &bull; Rp {{ number_format($item->price, 0, ',', '.') }}/pcs</p>
                                </div>

                                <!-- Price + Badge -->
                                <div class="text-right flex-shrink-0">
                                    @if($isMine)
                                        <span class="inline-block px-1.5 py-0.5 bg-green-100 text-green-700 text-[10px] font-bold rounded mb-0.5">PRODUK KAMU</span>
                                    @endif
                                    <p class="font-semibold {{ $isMine ? 'text-green-600' : 'text-gray-400' }}">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Buyer Info -->
                        <div class="mt-3 flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-400">
                            <span><i class="fas fa-user mr-1"></i>{{ $order->delivery_name ?? $order->user->name ?? '-' }}</span>
                            <span><i class="fas fa-phone mr-1"></i>{{ $order->delivery_phone ?? '-' }}</span>
                            @if($order->tracking_number)
                                <span><i class="fas fa-truck mr-1"></i>{{ $order->tracking_number }}</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-6">{{ $orders->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
