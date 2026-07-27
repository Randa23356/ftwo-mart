@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
        <!-- Header -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl lg:rounded-3xl shadow-xl border border-white/20 p-6 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <a href="{{ route('seller.orders') }}" class="text-gray-400 hover:text-gray-600"><i class="fas fa-arrow-left"></i></a>
                        <h1 class="text-xl font-bold text-gray-900">#{{ $order->order_number }}</h1>
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $order->status_badge }}">
                            {{ ucfirst(str_replace('_', ' ', $order->order_status)) }}
                        </span>
                        @if($order->payment_status === 'paid')
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">Lunas</span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-500 mt-1">{{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left: Items + Customer -->
            <div class="lg:col-span-2 space-y-6">
                <!-- My Items -->
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-6">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-box text-green-600 text-sm"></i>
                        </div>
                        Produk Kamu ({{ $myItems->count() }})
                    </h3>
                    <div class="space-y-3">
                        @foreach($myItems as $item)
                        <div class="flex items-center gap-3 bg-green-50 rounded-xl p-3">
                            <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-200 flex-shrink-0">
                                @if($item->product)
                                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center"><i class="fas fa-image text-gray-400"></i></div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-800 text-sm truncate">{{ $item->product_name ?? $item->product->name ?? 'Produk' }}</p>
                                <p class="text-xs text-gray-500">x{{ $item->quantity }} &bull; Rp {{ number_format($item->price, 0, ',', '.') }}/pcs</p>
                            </div>
                            <p class="font-bold text-green-600 text-sm flex-shrink-0">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- All Items (info) -->
                @if($order->orderItems->count() > $myItems->count())
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-6">
                    <h3 class="font-bold text-gray-900 mb-3 text-sm">Semua Item di Pesanan Ini ({{ $order->orderItems->count() }})</h3>
                    <div class="space-y-2">
                        @foreach($order->orderItems as $item)
                        @php $isMine = $item->product && $item->product->seller_id == $seller->id; @endphp
                        <div class="flex items-center gap-2 text-sm {{ $isMine ? '' : 'opacity-50' }}">
                            <span class="w-1.5 h-1.5 rounded-full flex-shrink-0 {{ $isMine ? 'bg-green-500' : 'bg-gray-300' }}"></span>
                            <span class="flex-1 truncate text-gray-700">{{ $item->product_name ?? 'Produk' }}</span>
                            <span class="text-gray-500">x{{ $item->quantity }}</span>
                            <span class="{{ $isMine ? 'text-green-600 font-semibold' : 'text-gray-400' }}">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Customer Info -->
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-6">
                    <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user text-blue-600 text-sm"></i>
                        </div>
                        Info Pembeli
                    </h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div><span class="text-gray-500">Nama:</span> <span class="font-semibold">{{ $order->delivery_name ?? $order->user->name ?? '-' }}</span></div>
                        <div><span class="text-gray-500">Telepon:</span> <span class="font-semibold">{{ $order->delivery_phone ?? '-' }}</span></div>
                        <div class="col-span-2"><span class="text-gray-500">Alamat:</span> <span class="font-semibold">{{ $order->delivery_address ?? '-' }}</span></div>
                    </div>
                </div>

                <!-- Shipping -->
                @if($order->shipping_courier)
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-6">
                    <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-truck text-purple-600 text-sm"></i>
                        </div>
                        Pengiriman
                    </h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div><span class="text-gray-500">Kurir:</span> <span class="font-semibold">{{ strtoupper($order->shipping_courier) }} {{ $order->shipping_service }}</span></div>
                        <div><span class="text-gray-500">Ongkir:</span> <span class="font-semibold">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span></div>
                        @if($order->tracking_number)
                        <div class="col-span-2"><span class="text-gray-500">No. Resi:</span> <span class="font-bold text-purple-700">{{ $order->tracking_number }}</span></div>
                        @endif
                        @if($order->shipped_at)
                        <div><span class="text-gray-500">Dikirim:</span> <span class="font-semibold">{{ $order->shipped_at->format('d M Y, H:i') }}</span></div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Right: Status + Summary -->
            <div class="space-y-6">
                <!-- Status Update -->
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-6">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-edit text-orange-600 text-sm"></i>
                        </div>
                        @if($order->order_status === 'shipped')
                            QR Pengiriman
                        @elseif($order->order_status === 'delivered' || $order->order_status === 'cancelled')
                            Status Pesanan
                        @else
                            Update Status
                        @endif
                    </h3>

                    <!-- Progress -->
                    <div class="flex items-center gap-1 mb-4">
                        @php
                        $allStatuses = ['pending', 'processing', 'ready', 'shipped', 'delivered'];
                        $currentIdx = array_search($order->order_status, $allStatuses);
                        if ($currentIdx === false) $currentIdx = -1;
                        @endphp
                        @foreach(['pending', 'processing', 'ready', 'shipped', 'delivered'] as $step)
                        @php $reached = $currentIdx >= array_search($step, $allStatuses); @endphp
                        <div class="flex-1 h-2 rounded-full {{ $reached ? 'bg-green-500' : 'bg-gray-200' }}"></div>
                        @endforeach
                    </div>
                    <div class="flex justify-between text-[10px] text-gray-400 mb-4 -mt-1">
                        <span>Proses</span>
                        <span>Siap</span>
                        <span>Kirim</span>
                        <span>Diterima</span>
                        <span>Selesai</span>
                    </div>

                    @if(in_array($order->order_status, ['pending', 'processing', 'ready']))
                    @if($order->payment_method !== 'cod' && $order->payment_status !== 'paid')
                    <div class="bg-yellow-50 rounded-xl p-4 text-center">
                        <i class="fas fa-exclamation-triangle text-yellow-500 text-xl mb-2"></i>
                        <p class="text-sm font-semibold text-yellow-800">Menunggu Pembayaran</p>
                        <p class="text-xs text-yellow-600 mt-1">Pesanan belum dibayar. Tidak dapat diproses sebelum pembayaran lunas.</p>
                    </div>
                    @else

                    @if($order->courier_token)
                    <div class="text-center mb-4">
                        <p class="text-sm text-gray-500 mb-3">
                            <i class="fas fa-qrcode text-purple-500 mr-1"></i> Kurir scan QR ini untuk konfirmasi pengiriman
                        </p>
                        <div class="inline-block bg-white p-3 rounded-xl border border-gray-200 shadow-sm mb-3">
                            <img src="{{ $order->qr_data_uri }}" alt="QR Code" class="w-48 h-48 mx-auto" id="qrImage">
                        </div>
                        <p class="text-xs text-gray-400 mb-3 font-mono break-all">{{ $order->qr_url }}</p>
                        <button onclick="printQR()" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2.5 rounded-xl transition-all text-sm">
                            <i class="fas fa-print mr-1"></i> Cetak QR
                        </button>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('seller.orders.update-status', $order) }}" id="statusForm">
                        @csrf @method('PUT')

                        @if($nextStatus === 'shipped')
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">No. Resi *</label>
                            <input type="text" name="tracking_number" value="{{ old('tracking_number') }}" required
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="Masukkan nomor resi pengiriman">
                        </div>
                        @endif

                        <input type="hidden" name="order_status" value="{{ $nextStatus }}">

                        <button type="submit" onclick="return confirmSetStatus('{{ ucfirst($nextStatus) }}')"
                                class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-bold py-3 rounded-xl transition-all shadow-md hover:shadow-lg">
                            @if($nextStatus === 'processing')
                                <i class="fas fa-cog mr-2"></i> Proses Pesanan
                            @elseif($nextStatus === 'ready')
                                <i class="fas fa-box mr-2"></i> Tandai Siap Kirim
                            @elseif($nextStatus === 'shipped')
                                <i class="fas fa-truck mr-2"></i> Kirim Sekarang
                            @endif
                        </button>
                    </form>

                    @if(in_array($order->order_status, ['pending', 'processing']))
                    <form method="POST" action="{{ route('seller.orders.update-status', $order) }}" class="mt-2" onsubmit="return confirmCancel()">
                        @csrf @method('PUT')
                        <input type="hidden" name="order_status" value="cancelled">
                        <button type="submit" class="w-full bg-red-50 hover:bg-red-100 text-red-600 font-semibold py-2.5 rounded-xl transition-all text-sm border border-red-200">
                            <i class="fas fa-times mr-1"></i> Batalkan Pesanan
                        </button>
                    </form>
                    @endif
                    @endif

                    @elseif($order->order_status === 'shipped')
                    @if($order->courier_confirmed_at)
                        <div class="text-center">
                            @if($order->refund_status === 'pending')
                            <div class="bg-yellow-50 rounded-xl p-4 mb-3">
                                <i class="fas fa-hourglass-half text-yellow-500 text-2xl mb-2"></i>
                                <p class="text-sm font-semibold text-yellow-800">Pengembalian Diajukan</p>
                                <p class="text-xs text-yellow-600 mt-1">Pembeli mengajukan pengembalian. Menunggu review admin.</p>
                            </div>
                            @elseif($order->refund_status === 'return_pending')
                            <div class="bg-blue-50 rounded-xl p-4 mb-3">
                                <i class="fas fa-box text-blue-500 text-2xl mb-2"></i>
                                <p class="text-sm font-semibold text-blue-800">Menunggu Pengembalian Barang</p>
                                <p class="text-xs text-blue-600 mt-1">Admin menyetujui. Menunggu pembeli mengirim barang kembali.</p>
                            </div>
                            @elseif($order->refund_status === 'return_shipped')
                            @php $refund = $order->refundRequest; @endphp
                            <div class="bg-purple-50 rounded-xl p-4 mb-3">
                                <i class="fas fa-truck text-purple-500 text-2xl mb-2"></i>
                                <p class="text-sm font-semibold text-purple-800">Barang Dikirim Kembali</p>
                                @if($refund)
                                <p class="text-xs text-purple-600 mt-1">Resi: <span class="font-bold">{{ $refund->return_tracking_number }}</span></p>
                                @if($refund->return_evidence_image)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $refund->return_evidence_image) }}" alt="Bukti" class="rounded-lg border border-gray-200 max-w-[200px]">
                                </div>
                                @endif
                                @endif
                            </div>
                            <form method="POST" action="{{ route('seller.orders.confirm-return', $order) }}" onsubmit="return confirm('Konfirmasi bahwa kamu sudah menerima barang retur? Pesanan akan dibatalkan dan stok dikembalikan.')">
                                @csrf
                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-xl transition-all shadow-md">
                                    <i class="fas fa-check-circle mr-2"></i> Konfirmasi Terima Barang Retur
                                </button>
                            </form>
                            @elseif($order->refund_status === 'completed')
                            <div class="bg-green-50 rounded-xl p-4 mb-3">
                                <i class="fas fa-check-circle text-green-500 text-2xl mb-2"></i>
                                <p class="text-sm font-semibold text-green-800">Retur Selesai</p>
                                <p class="text-xs text-green-600 mt-1">Barang diterima. Pesanan dibatalkan dan stok dikembalikan.</p>
                            </div>
                            @elseif($order->refund_status === 'rejected')
                            <div class="bg-blue-50 rounded-xl p-4 mb-3">
                                <i class="fas fa-info-circle text-blue-500 text-2xl mb-2"></i>
                                <p class="text-sm font-semibold text-blue-800">Pengembalian Ditolak</p>
                                <p class="text-xs text-blue-600 mt-1">Menunggu konfirmasi penerimaan dari pembeli.</p>
                            </div>
                            @else
                            <div class="bg-green-50 rounded-xl p-4 mb-3">
                                <i class="fas fa-clock text-green-500 text-2xl mb-2"></i>
                                <p class="text-sm font-semibold text-green-800">Menunggu Konfirmasi Pembeli</p>
                                <p class="text-xs text-green-600 mt-1">Kurir sudah konfirmasi. Pesanan otomatis selesai dalam {{ $order->days_until_auto_complete }} hari.</p>
                            </div>
                            @endif
                            @if(!$order->refund_status)
                            <p class="text-xs text-gray-400 mb-3">
                                <i class="fas fa-qrcode text-purple-500 mr-1"></i> QR sudah discan kurir
                            </p>
                            @endif
                        </div>
                    @elseif($order->courier_token)
                    <div class="text-center">
                        <p class="text-sm text-gray-500 mb-3">
                            <i class="fas fa-qrcode text-purple-500 mr-1"></i> Kurir scan QR ini untuk konfirmasi pengiriman
                        </p>
                        <div class="inline-block bg-white p-3 rounded-xl border border-gray-200 shadow-sm mb-3">
                            <img src="{{ $order->qr_data_uri }}" alt="QR Code" class="w-48 h-48 mx-auto" id="qrImage">
                        </div>
                        <p class="text-xs text-gray-400 mb-3 font-mono break-all">{{ $order->qr_url }}</p>
                        <button onclick="printQR()" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2.5 rounded-xl transition-all text-sm">
                            <i class="fas fa-print mr-1"></i> Cetak QR
                        </button>
                    </div>
                    @else
                    <p class="text-sm text-gray-400 text-center py-4">
                        <i class="fas fa-clock mr-1"></i> QR belum tersedia
                    </p>
                    @endif
                    @elseif($order->order_status === 'delivered')
                    <p class="text-sm text-green-600 text-center py-4">
                        <i class="fas fa-check-circle mr-1"></i> Pesanan sudah diterima
                    </p>
                    @elseif($order->order_status === 'cancelled')
                    <p class="text-sm text-red-500 text-center py-4">
                        <i class="fas fa-times-circle mr-1"></i> Pesanan dibatalkan
                    </p>
                    @endif
                </div>

                <!-- Payment Summary -->
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-6">
                    <h3 class="font-bold text-gray-900 mb-3">Ringkasan</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Metode Bayar</span>
                            <span class="font-semibold">{{ ucfirst($order->payment_method) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Status Bayar</span>
                            <span class="font-semibold {{ $order->payment_status === 'paid' ? 'text-green-600' : 'text-yellow-600' }}">{{ ucfirst($order->payment_status) }}</span>
                        </div>
                        <div class="border-t border-gray-100 pt-2 mt-2">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Total Pesanan</span>
                                <span class="font-semibold">{{ $order->formatted_total_amount }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Bagian Kamu</span>
                                <span class="font-bold text-green-600">Rp {{ number_format($myShare, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-400">Biaya Admin ({{ $commissionRate }}%)</span>
                                <span class="text-red-400">- Rp {{ number_format($myCommission, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- History -->
                @if($order->histories->count())
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-6">
                    <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                        <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-history text-gray-500 text-sm"></i>
                        </div>
                        Riwayat
                    </h3>
                    <div class="space-y-3">
                        @foreach($order->histories->take(5) as $h)
                        <div class="flex items-start gap-2 text-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-300 mt-1.5 flex-shrink-0"></span>
                            <div>
                                <p class="text-gray-700">
                                    <span class="font-semibold">{{ $h->actor_label }}</span>
                                    mengubah ke <span class="font-semibold">{{ $h->status_label }}</span>
                                </p>
                                <p class="text-gray-400">{{ $h->created_at->format('d M, H:i') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function confirmSetStatus(status) {
    return confirm('Yakin ingin mengubah status ke "' + status + '"?');
}
function confirmCancel() {
    return confirm('Yakin ingin membatalkan pesanan ini? Stok akan dikembalikan.');
}
function printQR() {
    var img = document.getElementById('qrImage');
    if (!img) return;
    var win = window.open('', '_blank', 'width=400,height=500');
    win.document.write('<html><head><title>QR - {{ $order->order_number }}</title>');
    win.document.write('<style>body{display:flex;flex-direction:column;align-items:center;justify-content:center;height:100vh;margin:0;font-family:sans-serif;} img{width:250px;height:250px;} h2{margin:10px 0 5px;font-size:14px;} p{margin:2px 0;font-size:11px;color:#666;}</style></head><body>');
    win.document.write('<h2>Ftowo Mart</h2>');
    win.document.write('<p>{{ $order->order_number }}</p>');
    win.document.write('<img src="' + img.src + '">');
    win.document.write('<p style="font-size:10px;color:#999;margin-top:8px;">Scan QR untuk konfirmasi pengiriman</p>');
    win.document.write('</body></html>');
    win.document.close();
    win.focus();
    setTimeout(function(){ win.print(); }, 300);
}
</script>
@endsection
