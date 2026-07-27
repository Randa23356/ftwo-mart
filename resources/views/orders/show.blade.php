@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6">
    <!-- Modern Hero Header -->
    <div class="relative bg-green-700 rounded-2xl sm:rounded-3xl p-5 sm:p-6 md:p-8 lg:p-10 mb-6 sm:mb-8 md:mb-10 overflow-hidden shadow-xl text-white">
        <div class="absolute inset-0 opacity-10 pattern-dots"></div>
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <div class="bg-white/20 p-2 rounded-lg backdrop-blur-sm">
                        <i class="fas fa-file-invoice text-xl"></i>
                    </div>
                    <span class="font-medium text-green-100 uppercase tracking-wider text-sm">Detail Pesanan</span>
                </div>
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold mb-1 break-all">
                    {{ $order->order_number }}
                </h1>
                <p class="text-green-100 text-sm flex items-center">
                    <i class="far fa-calendar-alt mr-2"></i>
                    {{ $order->created_at->format('d M Y, H:i') }} WIB
                </p>
            </div>
            
            <div class="flex items-center gap-3 w-full md:w-auto mt-4 md:mt-0">
                <div class="text-right w-full md:w-auto">
                    <p class="text-xs text-green-100 uppercase tracking-wider mb-1">Status Pesanan</p>
            @if($order->isExpired() && $order->payment_status === 'pending' && $order->order_status !== 'cancelled' && $order->payment_method !== 'cod')
                    <span class="inline-block px-3 sm:px-4 py-1 sm:py-1.5 text-sm sm:text-base bg-red-500/90 text-white backdrop-blur-md rounded-full font-bold border border-red-300">
                        Expired
                    </span>
                    @else
                    <span class="inline-block px-3 sm:px-4 py-1 sm:py-1.5 text-sm sm:text-base bg-white/20 backdrop-blur-md rounded-full font-bold border border-white/30">
                        {{ ucfirst($order->order_status) }}
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 md:gap-8 mb-10 sm:mb-12 md:mb-16">
        <!-- Main Content (Items & Details) -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Order Status Steps (Optional Visual) -->
            <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xl border border-gray-100 p-4 sm:p-5 md:p-6 lg:p-8">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-3 text-green-600">
                        <i class="fas fa-clipboard-list"></i>
                    </span>
                    Item Pesanan
                </h3>
                
                <div class="space-y-6" id="order-items">
                    @foreach($order->orderItems as $item)
                        <div class="flex items-start md:items-center gap-4 p-4 rounded-xl hover:bg-gray-50 transition-colors border border-gray-100">
                            <!-- Product Image -->
                            <div class="w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 bg-gray-100 rounded-lg flex-shrink-0 overflow-hidden border border-gray-200">
                                <img src="{{ $item->product_image_url }}" 
                                     alt="{{ $item->product_name }}" 
                                     class="w-full h-full object-cover"
                                     onerror="this.src='{{ asset('images/default-product.jpg') }}'">
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <h4 class="font-bold text-gray-900 text-base sm:text-lg truncate">{{ $item->product_name }}</h4>
                                <p class="text-xs sm:text-sm text-gray-500 mb-1 sm:mb-2 truncate">Kode: {{ $item->product_code }}</p>
                                @if($item->selected_variants && count($item->selected_variants) > 0)
                                    @foreach($item->selected_variants as $label => $value)
                                    <p class="text-xs text-gray-600 mb-1"><span class="font-medium">{{ $label }}:</span> {{ $value }}</p>
                                    @endforeach
                                @endif
                                <div class="flex flex-col sm:flex-row sm:flex-wrap sm:items-center gap-2 text-xs sm:text-sm">
                                    <span class="bg-green-50 text-green-700 px-2 py-0.5 sm:px-2.5 sm:py-0.5 rounded-md font-medium border border-green-100 w-fit">
                                        {{ $item->quantity }} x {{ 'Rp ' . number_format($item->price, 0, ',', '.') }}
                                    </span>
                                    
                                    <!-- Rating Button - Only show if order is delivered and product exists -->
                                    @if($order->order_status === 'delivered' && $item->product)
                                        @php
                                            $existingRating = Auth::user()->ratings()->where('product_id', $item->product->id)->where('order_id', $order->id)->first();
                                        @endphp
                                        
                                        @if(!$existingRating)
                                            <a href="{{ route('ratings.create', [$order, $item->product]) }}" 
                                               class="inline-flex items-center text-xs font-medium text-green-600 hover:text-green-700 bg-green-50 hover:bg-green-100 px-2 py-1 rounded-md transition-colors">
                                                <i class="fas fa-star mr-1"></i>
                                                Beri Rating
                                            </a>
                                        @else
                                            <div class="flex items-center space-x-2">
                                                <span class="inline-flex items-center text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded-md">
                                                    <i class="fas fa-check mr-1"></i>
                                                    Sudah Diberi Rating
                                                </span>
                                                <a href="{{ route('ratings.edit', [$order, $item->product]) }}" 
                                                   class="inline-flex items-center text-xs font-medium text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 px-2 py-1 rounded-md transition-colors">
                                                    <i class="fas fa-edit mr-1"></i>
                                                    Edit
                                                </a>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            
                            <div class="text-right ml-2 sm:ml-0">
                                <p class="font-bold text-gray-900 text-base sm:text-lg whitespace-nowrap">{{ 'Rp ' . number_format($item->subtotal, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>



            <!-- Shipping Info -->
            @if($order->shipping_courier)
            <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xl border border-gray-100 p-4 sm:p-5 md:p-6 lg:p-8">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-3 text-green-600">
                        <i class="fas fa-truck"></i>
                    </span>
                    Informasi Pengiriman
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-500 uppercase tracking-wider">Kurir & Layanan</span>
                            <span class="font-bold text-gray-900 text-lg">{{ strtoupper($order->courier_name) }} - {{ $order->shipping_service }}</span>
                        </div>
                        @if($order->shipping_etd)
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-500 uppercase tracking-wider">Estimasi Tiba</span>
                            <span class="font-medium text-gray-900">{{ $order->shipping_etd }} Hari</span>
                        </div>
                        @endif
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-500 uppercase tracking-wider">Nomor Resi</span>
                            @if($order->tracking_number)
                                <span class="font-mono font-bold text-blue-600 text-lg">{{ $order->tracking_number }}</span>
                            @else
                                <span class="font-medium text-gray-400 italic">Belum tersedia</span>
                                <span class="text-xs text-gray-400">Resi akan muncul setelah pesanan dikirim</span>
                            @endif
                        </div>
                        
                        @if($order->tracking_number)
                        <div>
                             <x-order-tracking-button :order="$order" variant="link" />
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar Summary -->
        <div class="lg:col-span-1 space-y-8">
            <!-- Payment Countdown & Expired Status -->
            @if($order->isExpired() && $order->payment_status === 'pending' && $order->order_status !== 'cancelled' && $order->payment_method !== 'cod')
                @if($order->expires_at && $order->expires_at->isFuture())
                <!-- Show countdown if not yet expired -->
                <div class="bg-red-50 rounded-2xl sm:rounded-3xl p-4 sm:p-5 md:p-6 border border-red-100 shadow-inner">
                    <div class="text-center">
                        <h4 class="text-red-800 font-bold mb-2 uppercase tracking-wide text-xs">Sisa Waktu Pembayaran</h4>
                        <div id="countdown-timer" class="text-3xl font-extrabold text-red-600 font-mono mb-2" 
                             data-expires="{{ $order->expires_at->toISOString() }}">
                            <span id="countdown-display">--:--:--</span>
                        </div>
                        <p class="text-xs text-red-600/80">Segera selesaikan pembayaran agar pesanan tidak dibatalkan otomatis.</p>
                    </div>
                </div>
                @else
                <!-- Show expired message -->
                <div class="bg-gray-100 rounded-3xl p-6 border border-gray-200 text-center">
                    <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
                        <i class="fas fa-clock text-xl"></i>
                    </div>
                    <h4 class="text-gray-900 font-bold">Pesanan Kedaluwarsa</h4>
                    <p class="text-sm text-gray-500 mt-1">Batas waktu pembayaran telah habis.</p>
                    
                    @if($order->order_status !== 'cancelled')
                    <div class="mt-4">
                        <form action="{{ route('orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-xs bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg transition-colors">
                                Batalkan Pesanan
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
                @endif
            @elseif($order->expires_at && $order->expires_at->isFuture() && $order->payment_status === 'pending' && $order->order_status !== 'cancelled' && $order->payment_method !== 'cod')
                <!-- Show countdown for non-expired orders -->
                <div class="bg-red-50 rounded-2xl sm:rounded-3xl p-4 sm:p-5 md:p-6 border border-red-100 shadow-inner">
                    <div class="text-center">
                        <h4 class="text-red-800 font-bold mb-2 uppercase tracking-wide text-xs">Sisa Waktu Pembayaran</h4>
                        <div id="countdown-timer" class="text-3xl font-extrabold text-red-600 font-mono mb-2" 
                             data-expires="{{ $order->expires_at->toISOString() }}">
                            <span id="countdown-display">--:--:--</span>
                        </div>
                        <p class="text-xs text-red-600/80">Segera selesaikan pembayaran agar pesanan tidak dibatalkan otomatis.</p>
                    </div>
                </div>
            @endif

            @if($order->order_status === 'shipped')
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl sm:rounded-3xl p-4 sm:p-5 md:p-6 border border-green-200 shadow-inner">
                <div class="text-center">
                    @if(!$order->courier_confirmed_at)
                        <div class="w-14 h-14 bg-purple-500 rounded-full flex items-center justify-center mx-auto mb-3 shadow-lg">
                            <i class="fas fa-truck text-white text-xl"></i>
                        </div>
                        <h4 class="text-green-800 font-bold mb-1">Paket Sedang Dalam Pengiriman</h4>
                        @if($order->tracking_number)
                            <p class="text-xs text-green-600 mb-3">No. Resi: <span class="font-bold">{{ $order->tracking_number }}</span></p>
                        @endif
                        <p class="text-xs text-gray-500">Menunggu konfirmasi dari kurir.</p>
                    @elseif($order->refund_status === 'pending')
                        <div class="w-14 h-14 bg-yellow-500 rounded-full flex items-center justify-center mx-auto mb-3 shadow-lg">
                            <i class="fas fa-hourglass-half text-white text-xl"></i>
                        </div>
                        <h4 class="text-yellow-800 font-bold mb-1">Pengembalian Sedang Diproses</h4>
                        <p class="text-xs text-gray-500">Permintaan pengembalian Anda sedang ditinjau oleh admin.</p>
                    @elseif($order->refund_status === 'return_pending')
                        <div class="w-14 h-14 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-3 shadow-lg">
                            <i class="fas fa-box text-white text-xl"></i>
                        </div>
                        <h4 class="text-blue-800 font-bold mb-1">Kirim Barang Kembali</h4>
                        <p class="text-xs text-gray-500 mb-4">Pengembalian disetujui. Silakan kirim barang kembali ke penjual.</p>
                        @php $refund = $order->refundRequest; @endphp
                        @if($refund && !$refund->buyer_returned_at)
                        <div class="text-left bg-white rounded-xl p-4 mb-4 border border-gray-200">
                            <form action="{{ route('orders.return', $order) }}" method="POST" enctype="multipart/form-data" id="returnForm">
                                @csrf
                                <div class="mb-3">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">No. Resi Pengembalian *</label>
                                    <input type="text" name="return_tracking_number" required
                                           class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Masukkan nomor resi pengiriman barang kembali">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Bukti Foto Pengiriman *</label>
                                    <input type="file" name="return_evidence_image" accept="image/*" required
                                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    <p class="text-xs text-gray-400 mt-1">Foto struk/resi pengiriman (Maks 2MB)</p>
                                </div>
                                <button type="submit" onclick="return confirm('Kirim bukti pengembalian barang?')"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition-all">
                                    <i class="fas fa-paper-plane mr-2"></i> Kirim Bukti Pengiriman
                                </button>
                            </form>
                        </div>
                        @elseif($refund && $refund->buyer_returned_at)
                        <div class="bg-blue-50 rounded-xl p-4 mb-3">
                            <p class="text-sm font-semibold text-blue-800"><i class="fas fa-check-circle mr-1"></i> Bukti sudah dikirim</p>
                            <p class="text-xs text-blue-600 mt-1">Resi: <span class="font-bold">{{ $refund->return_tracking_number }}</span></p>
                            <p class="text-xs text-blue-600">Menunggu seller mengkonfirmasi penerimaan barang.</p>
                        </div>
                        @endif
                    @elseif($order->refund_status === 'return_shipped')
                        <div class="w-14 h-14 bg-purple-500 rounded-full flex items-center justify-center mx-auto mb-3 shadow-lg">
                            <i class="fas fa-truck text-white text-xl"></i>
                        </div>
                        <h4 class="text-purple-800 font-bold mb-1">Barang Sedang Dikirim Kembali</h4>
                        <p class="text-xs text-gray-500">Barang sedang dalam perjalanan ke penjual. Menunggu konfirmasi dari seller.</p>
                    @elseif($order->refund_status === 'completed')
                        <div class="w-14 h-14 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-3 shadow-lg">
                            <i class="fas fa-check-circle text-white text-xl"></i>
                        </div>
                        <h4 class="text-green-800 font-bold mb-1">Pengembalian Selesai</h4>
                        <p class="text-xs text-gray-500">Barang telah diterima oleh penjual. Pesanan dibatalkan.</p>
                    @elseif($order->refund_status === 'rejected')
                        <div class="w-14 h-14 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-3 shadow-lg">
                            <i class="fas fa-box-open text-white text-xl"></i>
                        </div>
                        <h4 class="text-green-800 font-bold mb-1">Paket Sudah Diterima Kurir</h4>
                        <p class="text-xs text-gray-500 mb-4">Pengembalian ditolak. Silakan konfirmasi penerimaan.</p>
                        <button type="button" onclick="document.getElementById('confirmDeliveryModal').classList.remove('hidden')"
                                class="bg-green-700 hover:bg-green-800 text-white font-bold px-8 py-3 rounded-xl transition-all shadow-lg">
                            <i class="fas fa-check-circle mr-2"></i> Konfirmasi Terima
                        </button>
                    @else
                        <div class="w-14 h-14 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-3 shadow-lg">
                            <i class="fas fa-box-open text-white text-xl"></i>
                        </div>
                        <h4 class="text-green-800 font-bold mb-1">Paket Sudah Diterima Kurir</h4>
                        <p class="text-xs text-gray-500 mb-1">Paket telah diterima oleh penerima.</p>
                        @if($order->days_until_auto_complete !== null && $order->days_until_auto_complete > 0)
                            <p class="text-xs text-orange-600 mb-4">
                                <i class="fas fa-clock mr-1"></i>
                                Pesanan akan otomatis selesai dalam <strong>{{ $order->days_until_auto_complete }} hari</strong>.
                            </p>
                        @endif
                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <button type="button" onclick="document.getElementById('confirmDeliveryModal').classList.remove('hidden')"
                                    class="bg-green-700 hover:bg-green-800 text-white font-bold px-8 py-3 rounded-xl transition-all shadow-lg">
                                <i class="fas fa-check-circle mr-2"></i> Konfirmasi Terima
                            </button>
                            <button type="button" onclick="document.getElementById('refundModal').classList.remove('hidden')"
                                    class="bg-white hover:bg-red-50 text-red-600 font-bold px-6 py-3 rounded-xl transition-all border border-red-200 hover:border-red-300">
                                <i class="fas fa-undo mr-2"></i> Ajukan Pengembalian
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Refund Modal -->
            @if($order->isAwaitingBuyerConfirmation() && !$order->refund_status)
            <div id="refundModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Ajukan Pengembalian</h3>
                        <button type="button" onclick="document.getElementById('refundModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <form action="{{ route('orders.refund', $order) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Alasan Pengembalian *</label>
                            <select name="reason" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                <option value="">Pilih alasan...</option>
                                <option value="changed_mind">Berubah pikiran / tidak jadi beli</option>
                                <option value="wrong_item">Produk yang dikirim salah</option>
                                <option value="damaged">Produk rusak / cacat</option>
                                <option value="not_as_described">Produk tidak sesuai deskripsi</option>
                                <option value="late_delivery">Pengiriman terlambat</option>
                                <option value="other">Lainnya</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan (Opsional)</label>
                            <textarea name="notes" rows="3" maxlength="500"
                                      class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                      placeholder="Jelaskan alasan pengembalian..."></textarea>
                        </div>
                        <div class="mb-5">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Bukti Foto (Opsional)</label>
                            <input type="file" name="evidence_image" accept="image/*"
                                   class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                            <p class="text-xs text-gray-400 mt-1">Maks 2MB (JPG, PNG)</p>
                        </div>
                        <div class="flex gap-3">
                            <button type="button" onclick="document.getElementById('refundModal').classList.add('hidden')"
                                    class="flex-1 px-4 py-3 rounded-xl border border-gray-200 text-gray-700 font-semibold hover:bg-gray-50 transition-colors">
                                Batal
                            </button>
                            <button type="submit" onclick="return confirm('Ajukan pengembalian untuk pesanan ini?')"
                                    class="flex-1 px-4 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white font-bold transition-colors">
                                Kirim
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
            @endif

            <!-- Confirm Delivery Modal -->
            <div id="confirmDeliveryModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-2xl text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Konfirmasi Penerimaan</h3>
                    <p class="text-sm text-gray-500 mb-6">Apakah Anda yakin sudah menerima pesanan ini? Tindakan ini tidak dapat dibatalkan.</p>
                    <div class="flex gap-3">
                        <button type="button" onclick="document.getElementById('confirmDeliveryModal').classList.add('hidden')"
                                class="flex-1 px-4 py-3 rounded-xl border border-gray-200 text-gray-700 font-semibold hover:bg-gray-50 transition-colors text-sm">
                            Batal
                        </button>
                        <button type="button" id="confirmDeliveryBtn"
                                class="flex-1 px-4 py-3 rounded-xl bg-green-700 hover:bg-green-800 text-white font-bold transition-colors text-sm">
                            Ya, Terima
                        </button>
                    </div>
                </div>
            </div>

            <!-- Hidden form for confirm delivery -->
            <form id="confirmDeliveryForm" action="{{ route('orders.confirm-delivery', $order) }}" method="POST" class="hidden">
                @csrf
                @method('PATCH')
            </form>

            <!-- Order Summary Card -->
            <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="p-6 bg-gray-50 border-b border-gray-100">
                    <h3 class="font-bold text-gray-900">Ringkasan Biaya</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-bold text-gray-900">{{ $order->formatted_subtotal }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600">Biaya Pengiriman</span>
                        <span class="font-bold text-gray-900">{{ $order->formatted_shipping_cost }}</span>
                    </div>
                    <div class="pt-4 border-t border-gray-100 mt-4">
                        <div class="flex justify-between items-center">
                            <span class="text-base font-bold text-gray-900">Total Tagihan</span>
                            <span class="text-xl font-extrabold text-green-600">{{ $order->formatted_total_with_shipping }}</span>
                        </div>
                        
                        @if($order->payment_status === 'pending' && $order->payment_method === 'midtrans' && !$order->isExpired() && $order->order_status !== 'cancelled')
                        <div class="mt-4">
                            <form action="{{ route('orders.pay', $order) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full px-4 py-3 bg-green-700 hover:bg-green-800 text-white font-bold rounded-xl shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                    <span>Bayar Sekarang</span>
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Status & Address -->
                <div class="bg-gray-50/50 p-6 border-t border-gray-100 space-y-5">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Metode Pembayaran</p>
                        <div class="font-bold text-gray-900 flex items-center gap-2">
                             <i class="fas fa-credit-card text-gray-400"></i>
                             @php
                                $pm = strtolower($order->payment_method);
                                $displayPm = $order->payment_method;
                                
if ($pm === 'midtrans') {
                                    $displayPm = 'Transfer'; // Default to Transfer for midtrans
                                    if (isset($paymentType) && !empty($paymentType)) {
                                        $pt = strtolower($paymentType);
                                        if ($pt === 'gopay') $displayPm = 'GoPay';
                                        else if ($pt === 'shopeepay') $displayPm = 'ShopeePay';
                                        else if ($pt === 'qris') $displayPm = 'QRIS';
                                        else if ($pt === 'bca_klikbca') $displayPm = 'KlikBCA';
                                        else if (Str::contains($pt, 'bca')) $displayPm = 'Bank BCA';
                                        else if (Str::contains($pt, 'bni')) $displayPm = 'Bank BNI';
                                        else if (Str::contains($pt, 'bri')) $displayPm = 'Bank BRI';
                                        else if (Str::contains($pt, 'permata')) $displayPm = 'Bank Permata';
                                        else if (Str::contains($pt, 'cimb')) $displayPm = 'Bank CIMB';
                                        else if (Str::contains($pt, 'danamon')) $displayPm = 'Bank Danamon';
                                        else if (Str::contains($pt, 'mandiri')) $displayPm = 'Bank Mandiri';
                                        else if (Str::contains($pt, 'bukopin')) $displayPm = 'Bank Bukopin';
                                        else if (Str::contains($pt, 'echannel')) $displayPm = 'Mandiri Bill';
                                        else if (Str::contains($pt, 'cstore')) $displayPm = 'Indomaret / Alfamart';
                                        else {
                                            $displayPm = ucwords(str_replace('_', ' ', $pt));
                                        }
                                    } else if (isset($otherPayments) && !empty($otherPayments)) {
                                        $keys = array_keys($otherPayments);
                                        $displayPm = !empty($keys) ? $keys[0] : 'E-Wallet';
                                    }
                                } else {
                                    if(Str::contains($pm, 'bca')) $displayPm = 'Bank BCA';
                                    elseif(Str::contains($pm, 'mandiri')) $displayPm = 'Bank Mandiri';
                                    elseif(Str::contains($pm, 'bri')) $displayPm = 'Bank BRI';
                                    elseif(Str::contains($pm, 'bni')) $displayPm = 'Bank BNI';
                                    elseif(Str::contains($pm, ['gopay', 'qris', 'shopeepay'])) $displayPm = 'QRIS / E-Wallet';
                                }
                             @endphp
                             {{ strtoupper($displayPm) }}
                        </div>
                    </div>
                    
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Status Pembayaran</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold
                            {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-700' : ($order->payment_status === 'failed' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                            <span class="w-2 h-2 rounded-full mr-2 {{ $order->payment_status === 'paid' ? 'bg-green-500' : ($order->payment_status === 'failed' ? 'bg-red-500' : 'bg-yellow-500') }}"></span>
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Alamat Pengiriman</p>
                        <p class="text-sm font-medium text-gray-900 leading-relaxed">
                            {{ $order->delivery_address }}
                            <br>
                            @if($order->destination_city)
                                {{ $order->destination_city }}, {{ $order->destination_province }}
                            @endif
                        </p>
                        <p class="text-sm text-gray-500 mt-1 flex items-center">
                            <i class="fas fa-phone-alt text-xs mr-2"></i> {{ $order->delivery_phone }}
                        </p>
                    </div>
                </div>
            </div>
            
            <a href="{{ route('orders.index') }}" class="block w-full py-3 sm:py-4 text-center text-sm sm:text-base text-gray-500 font-bold hover:text-green-600 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

<!-- Rating Notification Shopee Style -->
@if($order->order_status === 'delivered')
    @php
        $unratedItems = $order->orderItems()->whereHas('product', function($query) {
            $query->where('is_active', true);
        })->get()->filter(function($item) {
            return !Auth::user()->ratings()->where('product_id', $item->product->id)->where('order_id', $item->order_id)->exists();
        });
    @endphp
    
    @if($unratedItems->count() > 0)
        <div class="fixed bottom-4 right-4 max-w-sm bg-white rounded-lg shadow-2xl border border-gray-200 p-4 z-50" id="rating-notification">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-star text-green-600"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-gray-900 mb-1">Beri Rating Produk</h4>
                    <p class="text-xs text-gray-600 mb-3">
                        Pesanan Anda sudah sampai! Beri rating untuk {{ $unratedItems->count() }} produk yang belum Anda rating.
                    </p>
                    <div class="flex space-x-2">
                        <button onclick="document.getElementById('rating-notification').style.display='none'" 
                                class="text-xs text-gray-500 hover:text-gray-700">
                            Nanti Saja
                        </button>
                        <a href="#order-items" 
                           onclick="document.getElementById('rating-notification').style.display='none'"
                           class="text-xs font-medium text-green-600 hover:text-green-700 bg-green-50 px-2 py-1 rounded">
                            Beri Rating Sekarang
                        </a>
                    </div>
                </div>
                <button onclick="document.getElementById('rating-notification').style.display='none'" 
                        class="flex-shrink-0 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
        </div>
    @endif
@endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Confirm Delivery Modal
    const confirmDeliveryBtn = document.getElementById('confirmDeliveryBtn');
    const confirmDeliveryModal = document.getElementById('confirmDeliveryModal');
    const confirmDeliveryForm = document.getElementById('confirmDeliveryForm');

    if (confirmDeliveryBtn && confirmDeliveryForm) {
        confirmDeliveryBtn.addEventListener('click', function() {
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...';
            confirmDeliveryForm.submit();
        });
    }

    if (confirmDeliveryModal) {
        confirmDeliveryModal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
    }

    // Refund Modal
    const refundModal = document.getElementById('refundModal');
    if (refundModal) {
        refundModal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
    }

    const countdownElement = document.getElementById('countdown-timer');
    if (!countdownElement) return;

    const expiresAt = new Date(countdownElement.dataset.expires);
    const displayElement = document.getElementById('countdown-display');

    function updateCountdown() {
        const now = new Date();
        const timeLeft = expiresAt - now;

        if (timeLeft <= 0) {
            displayElement.textContent = 'EXPIRED';
            return;
        }

        const hours = Math.floor(timeLeft / (1000 * 60 * 60));
        const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

        displayElement.textContent = 
            `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }

    updateCountdown();
    setInterval(updateCountdown, 1000);
});
</script>
@endpush
