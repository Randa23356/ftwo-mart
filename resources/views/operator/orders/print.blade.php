<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Pesanan #{{ $order->order_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-white text-gray-900">
    <div class="no-print max-w-2xl mx-auto px-4 py-6">
        <button onclick="window.print()" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-xl transition-colors">
            <i class="fas fa-print mr-2"></i> Cetak Struk
        </button>
        <button onclick="window.close()" class="w-full mt-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 rounded-xl transition-colors">
            Tutup
        </button>
    </div>

    <div class="max-w-2xl mx-auto px-4 py-6">
        {{-- Header --}}
        <div class="text-center mb-6">
            <h1 class="text-xl font-bold">FTWO Mart</h1>
            <p class="text-sm text-gray-500">Struk Pesanan</p>
        </div>

        {{-- Order Info --}}
        <div class="border-t border-b border-gray-200 py-3 mb-4 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-500">No. Pesanan</span>
                <span class="font-semibold">#{{ $order->order_number }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Tanggal</span>
                <span>{{ $order->created_at->format('d M Y H:i') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Pembeli</span>
                <span>{{ $order->user->name ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Status Bayar</span>
                <span class="font-semibold">{{ ucfirst($order->payment_status) }}</span>
            </div>
        </div>

        {{-- Items --}}
        <div class="mb-4">
            <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-2">Item Pesanan</h2>
            @foreach($order->orderItems as $item)
                <div class="flex justify-between text-sm py-1.5 border-b border-gray-100">
                    <div class="flex-1 min-w-0">
                        <p class="font-medium truncate">{{ $item->product->name ?? 'Produk dihapus' }}</p>
                        <p class="text-gray-500 text-xs">{{ $item->quantity }} x Rp {{ number_format($item->unit_price ?? $item->price, 0, ',', '.') }}</p>
                    </div>
                    <span class="font-semibold ml-4 whitespace-nowrap">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
            @endforeach
        </div>

        {{-- Totals --}}
        <div class="border-t border-gray-200 pt-3 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-500">Subtotal</span>
                <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Ongkir</span>
                <span>Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-base font-bold mt-2 pt-2 border-t border-gray-200">
                <span>Total</span>
                <span>Rp {{ number_format(($order->total_amount ?? 0) + ($order->shipping_cost ?? 0), 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Shipping --}}
        @if($order->shipping_courier)
        <div class="mt-4 text-sm">
            <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-2">Pengiriman</h2>
            <p class="text-gray-600">{{ strtoupper($order->shipping_courier) }} - {{ $order->shipping_service }}</p>
            @if($order->tracking_number)
                <p class="text-gray-600">Resi: <span class="font-mono font-semibold">{{ $order->tracking_number }}</span></p>
            @endif
        </div>
        @endif

        {{-- Footer --}}
        <div class="mt-8 text-center text-xs text-gray-400 border-t border-gray-100 pt-4">
            <p>Terima kasih atas pesanan Anda</p>
            <p>mart.ftwodev.id</p>
        </div>
    </div>
</body>
</html>
