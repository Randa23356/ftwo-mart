<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pengiriman - {{ $order->order_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="max-w-lg mx-auto px-4 py-8">

        @if(session('success'))
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">

            <div class="text-center mb-6">
                <div class="w-14 h-14 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </div>
                <h1 class="text-xl font-bold text-gray-900">Konfirmasi Pengiriman</h1>
                <p class="text-sm text-gray-500 mt-1">Ftowo Mart</p>
            </div>

            <div class="bg-gray-50 rounded-lg p-4 mb-5">
                <div class="text-xs text-gray-500 uppercase tracking-wide mb-2 font-semibold">Nomor Pesanan</div>
                <div class="text-lg font-bold text-gray-900">{{ $order->order_number }}</div>
            </div>

            <div class="space-y-3 mb-5">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Status Saat Ini</span>
                    <span class="font-semibold
                        @if($order->order_status === 'shipped') text-purple-700 bg-purple-100 px-2 py-0.5 rounded-full
                        @elseif($order->order_status === 'delivered') text-green-700 bg-green-100 px-2 py-0.5 rounded-full
                        @else text-gray-700 @endif
                    ">
                        @if($order->order_status === 'shipped') Dalam Perjalanan
                        @elseif($order->order_status === 'delivered') Sudah Diterima
                        @else {{ ucfirst($order->order_status) }} @endif
                    </span>
                </div>

                @if($order->payment_method)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Pembayaran</span>
                    <span class="font-semibold text-gray-900 uppercase">{{ $order->payment_method }}</span>
                </div>
                @endif

                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Total Bayar</span>
                    <span class="font-bold text-gray-900">{{ $order->formatted_total_with_shipping }}</span>
                </div>

                @if($order->tracking_number)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">No. Resi</span>
                    <span class="font-mono text-sm text-gray-900">{{ $order->tracking_number }}</span>
                </div>
                @endif
            </div>

            @if($order->order_status === 'shipped' && !$order->courier_confirmed_at)
                @if($order->payment_method === 'cod')
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-5">
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-yellow-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-yellow-800">Pembayaran COD</p>
                                <p class="text-xs text-yellow-700 mt-1">Pembayaran <strong>Rp {{ number_format($order->total_with_shipping, 0, ',', '.') }}</strong> akan dilunasi setelah pembeli mengkonfirmasi penerimaan.</p>
                            </div>
                        </div>
                    </div>
                @endif

                <button type="button"
                        onclick="document.getElementById('confirmModal').classList.remove('hidden')"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg text-center transition-colors">
                    Konfirmasi Sudah Diterima
                </button>
                <form id="confirmForm" method="POST" action="{{ route('courier.scan.confirm', $token) }}">
                    @csrf
                </form>
            @elseif($order->courier_confirmed_at)
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-green-800">Sudah Dikonfirmasi</p>
                    <p class="text-xs text-green-600 mt-1">{{ $order->courier_confirmed_at->format('d M Y H:i') }}</p>
                    <p class="text-xs text-gray-500 mt-2">Menunggu konfirmasi dari pembeli.</p>
                </div>
            @else
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
                    <p class="text-sm text-gray-500">Pesanan ini tidak dapat dikonfirmasi.</p>
                </div>
            @endif

        </div>

        <p class="text-center text-xs text-gray-400 mt-6">Ftowo Mart Courier Confirmation</p>

    </div>

    <!-- Confirm Modal -->
    <div id="confirmModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,0,0.4); backdrop-filter: blur(4px);">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">Konfirmasi Penerimaan</h3>
                <p class="text-sm text-gray-500">Pastikan paket sudah diterima oleh penerima yang benar.</p>
                @if($order->payment_method === 'cod')
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mt-4 text-left">
                    <p class="text-xs font-semibold text-yellow-800">COD - Rp {{ number_format($order->total_with_shipping, 0, ',', '.') }}</p>
                    <p class="text-xs text-yellow-600 mt-1">Pembayaran akan dilunasi setelah pembeli mengkonfirmasi.</p>
                </div>
                @endif
            </div>
            <div class="flex border-t border-gray-100">
                <button onclick="document.getElementById('confirmModal').classList.add('hidden')"
                        class="flex-1 py-3 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button onclick="document.getElementById('confirmForm').submit()"
                        class="flex-1 py-3 text-sm font-bold text-green-600 hover:bg-green-50 transition-colors border-l border-gray-100">
                    Ya, Konfirmasi
                </button>
            </div>
        </div>
    </div>

</body>
</html>
