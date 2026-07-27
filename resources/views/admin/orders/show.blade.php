@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.orders') }}" class="p-2.5 bg-white hover:bg-gray-100 rounded-xl border border-gray-200 transition-colors">
                    <i class="fas fa-arrow-left text-gray-500"></i>
                </a>
                <div>
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h1 class="text-xl lg:text-2xl font-bold text-gray-900">Detail Pesanan</h1>
                        @php
                            $statusBadge = match($order->order_status) {
                                'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                'processing' => 'bg-blue-100 text-blue-700 border-blue-200',
                                'ready' => 'bg-amber-100 text-amber-700 border-amber-200',
                                'shipped' => 'bg-purple-100 text-purple-700 border-purple-200',
                                'delivered' => 'bg-green-100 text-green-700 border-green-200',
                                'cancelled' => 'bg-red-100 text-red-700 border-red-200',
                                default => 'bg-gray-100 text-gray-700 border-gray-200',
                            };
                            $paymentBadge = match($order->payment_status) {
                                'paid' => 'bg-green-50 text-green-700 border-green-200',
                                'failed' => 'bg-red-50 text-red-700 border-red-200',
                                default => 'bg-amber-50 text-amber-700 border-amber-200',
                            };
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $statusBadge }}">
                            {{ ucfirst($order->order_status) }}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $paymentBadge }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                    <p class="text-gray-500 text-sm mt-0.5">
                        <span class="font-medium text-gray-700">#{{ $order->order_number }}</span>
                        <span class="mx-1.5 text-gray-300">|</span>
                        {{ $order->created_at->format('d M Y H:i') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- ========== LEFT COLUMN (7 cols) ========== -->
            <div class="lg:col-span-7 space-y-6">

                <!-- Card: Order Items -->
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-green-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-shopping-bag text-green-600 text-sm"></i>
                            </div>
                            <h2 class="font-semibold text-gray-900 text-sm">Item Pesanan</h2>
                        </div>
                        <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-3 py-1 rounded-full">{{ $order->orderItems->count() }} item</span>
                    </div>

                    {{-- Desktop Table --}}
                    <div class="hidden md:block">
                        <table class="w-full">
                            <thead>
                                <tr class="text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50">
                                    <th class="px-6 py-3 text-left">Produk</th>
                                    <th class="px-4 py-3 text-center w-20">Qty</th>
                                    <th class="px-4 py-3 text-right w-32">Harga</th>
                                    <th class="px-6 py-3 text-right w-36">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($order->orderItems as $item)
                                <tr class="hover:bg-gray-50/60 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 rounded-xl bg-gray-100 overflow-hidden flex-shrink-0 border border-gray-200">
                                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                            </div>
                                            <div class="min-w-0">
                                                <div class="font-semibold text-gray-900 text-sm line-clamp-1">{{ $item->product->name }}</div>
                                                <div class="text-xs text-gray-400 mt-0.5">{{ $item->product->category->name ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 text-gray-700 text-sm font-semibold">{{ $item->quantity }}</span>
                                    </td>
                                    <td class="px-4 py-4 text-right text-gray-500 text-sm">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-right font-bold text-gray-900 text-sm">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach

                                @if($order->shipping_cost > 0)
                                <tr class="bg-blue-50/40">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-truck text-blue-600"></i>
                                            </div>
                                            <div>
                                                <div class="font-semibold text-gray-900 text-sm">Biaya Pengiriman</div>
                                                <div class="text-xs text-gray-500">{{ strtoupper($order->courier_name ?? '-') }} - {{ $order->shipping_service ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 text-blue-700 text-sm font-semibold">1</span>
                                    </td>
                                    <td class="px-4 py-4 text-right text-gray-500 text-sm">{{ $order->formatted_shipping_cost }}</td>
                                    <td class="px-6 py-4 text-right font-bold text-gray-900 text-sm">{{ $order->formatted_shipping_cost }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                            <span class="text-sm font-semibold text-gray-500">Total Pembayaran</span>
                            <span class="text-lg font-bold text-green-600">{{ $order->formatted_total_with_shipping }}</span>
                        </div>
                    </div>

                    {{-- Mobile Cards --}}
                    <div class="md:hidden divide-y divide-gray-100">
                        @foreach($order->orderItems as $item)
                        <div class="p-4 flex gap-3">
                            <div class="w-16 h-16 rounded-xl bg-gray-100 overflow-hidden flex-shrink-0 border border-gray-200">
                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-semibold text-gray-900 text-sm line-clamp-1">{{ $item->product->name }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">{{ $item->product->category->name ?? '-' }}</div>
                                <div class="flex items-center justify-between mt-2">
                                    <span class="text-xs text-gray-500">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                    <span class="text-sm font-bold text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        @if($order->shipping_cost > 0)
                        <div class="p-4 bg-blue-50/40 flex gap-3">
                            <div class="w-16 h-16 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-truck text-blue-600"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-semibold text-gray-900 text-sm">Biaya Pengiriman</div>
                                <div class="text-xs text-gray-500">{{ strtoupper($order->courier_name ?? '-') }} - {{ $order->shipping_service ?? '-' }}</div>
                                <div class="text-sm font-bold text-gray-900 mt-2">{{ $order->formatted_shipping_cost }}</div>
                            </div>
                        </div>
                        @endif

                        <div class="p-4 bg-gray-50 flex items-center justify-between">
                            <span class="text-sm font-semibold text-gray-500">Total</span>
                            <span class="text-lg font-bold text-green-600">{{ $order->formatted_total_with_shipping }}</span>
                        </div>
                    </div>
                </div>

                <!-- Card: Customer + Shipping (2-col on md) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Customer Info -->
                    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                            <div class="w-9 h-9 bg-indigo-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-user text-indigo-500 text-sm"></i>
                            </div>
                            <h3 class="font-semibold text-gray-900 text-sm">Pelanggan</h3>
                        </div>
                        <div class="p-5 space-y-3.5">
                            <div>
                                <div class="text-[11px] text-gray-400 uppercase tracking-wider mb-0.5">Nama</div>
                                <div class="text-sm font-semibold text-gray-900">{{ $order->user->name ?? '-' }}</div>
                            </div>
                            <div>
                                <div class="text-[11px] text-gray-400 uppercase tracking-wider mb-0.5">Email</div>
                                <div class="text-sm text-gray-600">{{ $order->user->email ?? '-' }}</div>
                            </div>
                            <div>
                                <div class="text-[11px] text-gray-400 uppercase tracking-wider mb-0.5">Telepon</div>
                                <div class="text-sm font-medium text-gray-900 font-mono">{{ $order->delivery_phone ?? $order->user->phone ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Info -->
                    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                            <div class="w-9 h-9 bg-orange-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-truck text-orange-500 text-sm"></i>
                            </div>
                            <h3 class="font-semibold text-gray-900 text-sm">Pengiriman</h3>
                        </div>
                        <div class="p-5 space-y-3.5">
                            <div>
                                <div class="text-[11px] text-gray-400 uppercase tracking-wider mb-0.5">Ekspedisi</div>
                                <div class="text-sm font-semibold text-gray-900">{{ strtoupper($order->courier_name ?? '-') }} <span class="text-gray-500 font-normal">{{ $order->shipping_service ?? '-' }}</span></div>
                            </div>
                            <div>
                                <div class="text-[11px] text-gray-400 uppercase tracking-wider mb-0.5">Biaya</div>
                                <div class="text-sm font-semibold text-gray-900">{{ $order->formatted_shipping_cost }}</div>
                            </div>
                            <div>
                                <div class="text-[11px] text-gray-400 uppercase tracking-wider mb-0.5">Alamat</div>
                                <div class="text-sm text-gray-600 leading-relaxed">{{ $order->delivery_address ?? $order->user->address ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========== RIGHT COLUMN (5 cols, sticky) ========== -->
            <div class="lg:col-span-5 space-y-6 lg:sticky lg:top-6">

                <!-- Card: Status Management -->
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                        <div class="w-9 h-9 bg-purple-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-cog text-purple-500 text-sm"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 text-sm">Status Pesanan</h3>
                    </div>
                    <div class="p-5">
                        @php
                            $current = $order->order_status;
                            $transitions = [
                                'pending' => ['processing'],
                                'processing' => ['ready'],
                                'ready' => ['shipped'],
                                'shipped' => [],
                                'delivered' => [],
                                'cancelled' => [],
                            ];
                            $allowed = $transitions[$current] ?? [];
                        @endphp

                        {{-- Status: Cancelled --}}
                        @if($current === 'cancelled')
                            <div class="text-center py-4">
                                <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-ban text-red-500 text-xl"></i>
                                </div>
                                <p class="font-bold text-red-800 text-sm">Pesanan Dibatalkan</p>
                                <p class="text-xs text-red-500 mt-1">Status tidak dapat diubah lagi</p>
                            </div>

                        {{-- Status: Delivered --}}
                        @elseif($current === 'delivered')
                            <div class="text-center py-4">
                                <div class="w-14 h-14 bg-green-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                                </div>
                                <p class="font-bold text-green-800 text-sm">Pesanan Selesai</p>
                                <p class="text-xs text-green-500 mt-1">Transaksi telah selesai</p>
                            </div>

                        {{-- Status: Shipped --}}
                        @elseif($current === 'shipped')
                            @if($order->courier_confirmed_at)
                                @if($order->refund_status === 'pending')
                                    <div class="text-center py-2">
                                        <div class="w-14 h-14 bg-yellow-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                            <i class="fas fa-hourglass-half text-yellow-500 text-xl"></i>
                                        </div>
                                        <p class="font-bold text-yellow-800 text-sm">Pengembalian Diajukan</p>
                                        <p class="text-xs text-yellow-600 mt-1">Menunggu review admin</p>
                                    </div>
                                @elseif($order->refund_status === 'return_pending')
                                    <div class="text-center py-2">
                                        <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                            <i class="fas fa-box text-blue-500 text-xl"></i>
                                        </div>
                                        <p class="font-bold text-blue-800 text-sm">Menunggu Pengembalian Barang</p>
                                        <p class="text-xs text-blue-600 mt-1">Pembeli akan mengirim barang kembali</p>
                                    </div>
                                @elseif($order->refund_status === 'return_shipped')
                                    @php $refund = $order->refundRequest; @endphp
                                    <div class="text-center py-2">
                                        <div class="w-14 h-14 bg-purple-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                            <i class="fas fa-truck text-purple-500 text-xl"></i>
                                        </div>
                                        <p class="font-bold text-purple-800 text-sm">Barang Dikirim Kembali</p>
                                        @if($refund)
                                            <p class="text-xs text-purple-600 mt-1">Resi: <span class="font-bold">{{ $refund->return_tracking_number }}</span></p>
                                            @if($refund->return_evidence_image)
                                                <div class="mt-3">
                                                    <img src="{{ asset('storage/' . $refund->return_evidence_image) }}" alt="Bukti" class="rounded-xl border border-gray-200 max-w-[180px] mx-auto">
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                @elseif($order->refund_status === 'completed')
                                    <div class="text-center py-2">
                                        <div class="w-14 h-14 bg-green-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                            <i class="fas fa-check-circle text-green-500 text-xl"></i>
                                        </div>
                                        <p class="font-bold text-green-800 text-sm">Retur Selesai</p>
                                        <p class="text-xs text-green-600 mt-1">Stok dikembalikan</p>
                                    </div>
                                @elseif($order->refund_status === 'rejected')
                                    <div class="text-center py-2">
                                        <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                            <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                                        </div>
                                        <p class="font-bold text-blue-800 text-sm">Pengembalian Ditolak</p>
                                        <p class="text-xs text-blue-600 mt-1">Menunggu konfirmasi pembeli</p>
                                    </div>
                                @else
                                    <div class="text-center py-2">
                                        <div class="w-14 h-14 bg-green-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                            <i class="fas fa-clock text-green-500 text-xl"></i>
                                        </div>
                                        <p class="font-bold text-green-800 text-sm">Menunggu Konfirmasi</p>
                                        <p class="text-xs text-green-600 mt-1">Otomatis selesai dalam {{ $order->days_until_auto_complete }} hari</p>
                                    </div>
                                @endif
                            @elseif($order->courier_token)
                                {{-- QR waiting for courier scan --}}
                                <div class="text-center">
                                    <p class="text-xs text-gray-500 mb-3 font-medium">
                                        <i class="fas fa-qrcode text-purple-500 mr-1"></i> Kurir scan QR untuk konfirmasi
                                    </p>
                                    <div class="inline-block bg-gray-50 p-3 rounded-xl border border-gray-200 mb-3">
                                        <img src="{{ $order->qr_data_uri }}" alt="QR Code" class="w-44 h-44 mx-auto" id="qrImage">
                                    </div>
                                    <p class="text-[10px] text-gray-400 font-mono break-all mb-3">{{ $order->qr_url }}</p>
                                    <button onclick="printQR()" class="w-full bg-gray-900 hover:bg-gray-800 text-white font-semibold py-2.5 rounded-xl transition-all text-sm">
                                        <i class="fas fa-print mr-1.5"></i> Cetak QR
                                    </button>
                                </div>
                            @else
                                <div class="text-center py-4 text-gray-400 text-sm">
                                    <i class="fas fa-clock mr-1"></i> QR belum tersedia
                                </div>
                            @endif

                        {{-- Status: Unpaid (non-COD) --}}
                        @elseif($order->payment_method !== 'cod' && $order->payment_status !== 'paid')
                            <div class="text-center py-4">
                                <div class="w-14 h-14 bg-yellow-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
                                </div>
                                <p class="font-bold text-yellow-800 text-sm">Menunggu Pembayaran</p>
                                <p class="text-xs text-yellow-600 mt-1">Tidak dapat diproses sebelum lunas</p>
                            </div>

                        {{-- Status: Active (form available) --}}
                        @else
                            <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="space-y-4">
                                @csrf
                                @method('PUT')

                                {{-- QR Code before status form --}}
                                @if($order->courier_token)
                                    <div class="text-center pb-2 border-b border-gray-100">
                                        <p class="text-xs text-gray-500 mb-2 font-medium">
                                            <i class="fas fa-qrcode text-purple-500 mr-1"></i> Kurir scan QR untuk konfirmasi
                                        </p>
                                        <div class="inline-block bg-gray-50 p-3 rounded-xl border border-gray-200 mb-2">
                                            <img src="{{ $order->qr_data_uri }}" alt="QR Code" class="w-40 h-40 mx-auto" id="qrImage">
                                        </div>
                                        <p class="text-[10px] text-gray-400 font-mono break-all mb-2">{{ $order->qr_url }}</p>
                                        <button type="button" onclick="printQR()" class="w-full bg-gray-900 hover:bg-gray-800 text-white font-semibold py-2 rounded-xl transition-all text-xs">
                                            <i class="fas fa-print mr-1.5"></i> Cetak QR
                                        </button>
                                    </div>
                                @endif

                                {{-- Status Dropdown --}}
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Ubah Ke</label>
                                    <select name="order_status" id="order_status_select" class="w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-2 focus:ring-green-500 focus:border-green-500 text-gray-800 font-semibold text-sm bg-gray-50 transition">
                                        @foreach($allowed as $status)
                                            <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                                        @endforeach
                                    </select>
                                    @if($allowed)
                                        <p class="text-[11px] text-gray-400 mt-1.5">Transisi: {{ implode(' → ', array_map('ucfirst', $allowed)) }}</p>
                                    @endif
                                </div>

                                {{-- Tracking Number (hidden by default) --}}
                                <div id="tracking_number_field" class="hidden">
                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Nomor Resi</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                            <i class="fas fa-hashtag text-gray-400 text-sm"></i>
                                        </div>
                                        <input type="text" name="tracking_number" id="tracking_number"
                                            class="pl-10 w-full rounded-xl border-gray-200 focus:ring-2 focus:ring-green-500 focus:border-green-500 text-gray-800 text-sm py-3 bg-gray-50 placeholder-gray-400 font-medium transition"
                                            placeholder="Masukkan nomor resi"
                                            value="{{ $order->tracking_number }}">
                                    </div>
                                </div>

                                {{-- Notes (hidden by default) --}}
                                <div id="notes_field" class="hidden">
                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Catatan</label>
                                    <textarea name="notes" rows="2" class="w-full rounded-xl border-gray-200 focus:ring-2 focus:ring-green-500 focus:border-green-500 text-gray-800 text-sm py-2.5 px-3.5 bg-gray-50 placeholder-gray-400 font-medium transition" placeholder="Opsional..."></textarea>
                                </div>

                                {{-- Submit --}}
                                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition-all text-sm shadow-sm">
                                    <i class="fas fa-save"></i> Simpan Status
                                </button>

                                {{-- Cancel --}}
                                <button type="button" onclick="handleCancelOrder(this)"
                                    class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-white border-2 border-red-200 text-red-600 hover:bg-red-50 font-semibold rounded-xl transition-colors text-sm">
                                    <i class="fas fa-times-circle"></i> Batalkan Pesanan
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                {{-- Confirm Return Card --}}
                @if($order->refundRequest && $order->refundRequest->status === 'return_shipped')
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                        <div class="w-9 h-9 bg-orange-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-undo text-orange-500 text-sm"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 text-sm">Konfirmasi Retur</h3>
                    </div>
                    <div class="p-5">
                        <form id="confirmReturnFormAdmin" method="POST" action="{{ route('admin.orders.confirm-return', $order) }}">
                            @csrf
                            @method('POST')
                            <p class="text-sm text-gray-500 mb-4 leading-relaxed">Pembeli telah mengirimkan barang retur. Konfirmasi untuk membatalkan pesanan dan mengembalikan stok.</p>
                            <button type="button" onclick="handleConfirmReturn(this)"
                                class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-xl transition-all text-sm shadow-sm">
                                <i class="fas fa-check-circle"></i> Konfirmasi Retur Diterima
                            </button>
                        </form>
                    </div>
                </div>
                @endif

                {{-- Tracking Number Card --}}
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                        <div class="w-9 h-9 bg-teal-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-barcode text-teal-500 text-sm"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 text-sm">Resi Pengiriman</h3>
                    </div>
                    <div class="p-5">
                        @if($order->tracking_number)
                            <div class="bg-gray-50 rounded-xl p-5 border border-dashed border-gray-300 text-center">
                                <div class="text-[11px] text-gray-400 uppercase tracking-wider mb-1.5">Nomor Resi</div>
                                <div class="text-xl font-mono font-bold text-gray-800 tracking-wide select-all">{{ $order->tracking_number }}</div>
                                <div class="mt-3">
                                    <x-order-tracking-button :order="$order" />
                                </div>
                            </div>
                        @else
                            <form id="tracking-form" class="space-y-3">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                        <i class="fas fa-hashtag text-gray-400 text-sm"></i>
                                    </div>
                                    <input type="text" id="tracking_number_standalone" name="tracking_number"
                                        class="pl-10 w-full rounded-xl border-gray-200 focus:ring-2 focus:ring-green-500 focus:border-green-500 text-gray-800 text-sm py-3 bg-gray-50 placeholder-gray-400 font-medium transition"
                                        placeholder="Input nomor resi" required>
                                </div>
                                <button type="submit" class="w-full px-4 py-3 bg-gray-900 hover:bg-gray-800 text-white font-bold rounded-xl transition-all text-sm">
                                    <i class="fas fa-save mr-1.5"></i> Simpan Resi
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Card: Payment Summary -->
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                        <div class="w-9 h-9 bg-emerald-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-receipt text-emerald-500 text-sm"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 text-sm">Ringkasan Pembayaran</h3>
                    </div>
                    <div class="p-5 space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Metode</span>
                            <span class="font-semibold text-gray-900">{{ strtolower($order->payment_method) === 'midtrans' ? 'Transfer' : ucfirst($order->payment_method) }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Subtotal</span>
                            <span class="font-semibold text-gray-900">{{ $order->formatted_subtotal }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Ongkir</span>
                            <span class="font-semibold text-gray-900">{{ $order->formatted_shipping_cost }}</span>
                        </div>
                        <div class="border-t border-gray-200 pt-3 mt-1 flex items-center justify-between">
                            <span class="font-bold text-gray-900">Total</span>
                            <span class="text-lg font-bold text-green-600">{{ $order->formatted_total_with_shipping }}</span>
                        </div>
                    </div>
                </div>

                <!-- Card: Activity Log -->
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                        <div class="w-9 h-9 bg-sky-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-history text-sky-500 text-sm"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 text-sm">Riwayat Status</h3>
                        @if($order->histories->count())
                            <span class="ml-auto bg-gray-100 text-gray-500 text-[11px] font-bold px-2.5 py-0.5 rounded-full">{{ $order->histories->count() }}</span>
                        @endif
                    </div>

                    @if($order->histories->isEmpty())
                        <div class="p-8 text-center">
                            <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-clock text-gray-300 text-sm"></i>
                            </div>
                            <p class="text-gray-400 text-xs">Belum ada riwayat</p>
                        </div>
                    @else
                        <div class="p-4 max-h-[400px] overflow-y-auto">
                            <div class="space-y-0">
                                @foreach($order->histories->reverse() as $h)
                                    @php
                                        $colors = match($h->new_status) {
                                            'cancelled' => ['dot' => 'bg-red-100', 'icon' => 'text-red-500', 'fa' => 'fa-times', 'label' => 'text-red-600'],
                                            'delivered' => ['dot' => 'bg-green-100', 'icon' => 'text-green-500', 'fa' => 'fa-check', 'label' => 'text-green-600'],
                                            'processing' => ['dot' => 'bg-blue-100', 'icon' => 'text-blue-500', 'fa' => 'fa-cog', 'label' => 'text-blue-600'],
                                            'shipped' => ['dot' => 'bg-purple-100', 'icon' => 'text-purple-500', 'fa' => 'fa-truck', 'label' => 'text-purple-600'],
                                            'ready' => ['dot' => 'bg-amber-100', 'icon' => 'text-amber-500', 'fa' => 'fa-box', 'label' => 'text-amber-600'],
                                            default => ['dot' => 'bg-gray-100', 'icon' => 'text-gray-500', 'fa' => 'fa-clock', 'label' => 'text-gray-700'],
                                        };
                                    @endphp

                                    <div class="relative flex gap-3 {{ !$loop->last ? 'pb-4' : '' }}">
                                        @if(!$loop->last)
                                            <div class="absolute left-[11px] top-[28px] bottom-0 w-px bg-gray-200"></div>
                                        @endif

                                        <div class="relative z-10 w-6 h-6 rounded-full {{ $colors['dot'] }} flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <i class="fas {{ $colors['fa'] }} {{ $colors['icon'] }} text-[9px]"></i>
                                        </div>

                                        <div class="flex-1 min-w-0 {{ !$loop->last ? 'pb-0' : '' }}">
                                            <div class="flex items-center justify-between gap-2">
                                                <div class="text-xs">
                                                    @if($h->old_status)
                                                        <span class="text-gray-400">{{ $h->old_status_label }}</span>
                                                        <i class="fas fa-arrow-right text-gray-300 mx-1 text-[8px]"></i>
                                                    @endif
                                                    <span class="font-semibold {{ $colors['label'] }}">{{ $h->status_label }}</span>
                                                </div>
                                                <span class="text-[10px] text-gray-400 font-medium flex-shrink-0">{{ $h->created_at->format('d M, H:i') }}</span>
                                            </div>
                                            <div class="text-[11px] text-gray-400 mt-0.5">
                                                {{ $h->actor_label }}
                                                @if($h->notes) — {{ $h->notes }} @endif
                                            </div>
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
</div>
@endsection

@push('scripts')
<script>
function printQR() {
    var img = document.getElementById('qrImage');
    if (!img) return;
    var win = window.open('', '_blank', 'width=400,height=500');
    win.document.write('<html><head><title>QR - {{ $order->order_number }}</title>');
    win.document.write('<style>body{display:flex;flex-direction:column;align-items:center;justify-content:center;height:100vh;margin:0;font-family:sans-serif;} img{width:250px;height:250px;} h2{margin:10px 0 5px;font-size:14px;} p{margin:2px 0;font-size:11px;color:#666;}</style></head><body>');
    win.document.write('<h2>FTWO Mart</h2>');
    win.document.write('<p>{{ $order->order_number }}</p>');
    win.document.write('<img src="' + img.src + '">');
    win.document.write('<p style="font-size:10px;color:#999;margin-top:8px;">Scan QR untuk konfirmasi pengiriman</p>');
    win.document.write('</body></html>');
    win.document.close();
    win.focus();
    setTimeout(function(){ win.print(); }, 300);
}

document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('order_status_select');
    const trackingField = document.getElementById('tracking_number_field');
    const notesField = document.getElementById('notes_field');

    if (statusSelect && trackingField) {
        statusSelect.addEventListener('change', function() {
            const show = this.value === 'shipped';
            trackingField.classList.toggle('hidden', !show);
            notesField.classList.toggle('hidden', !show);
        });
    }

    const trackingForm = document.getElementById('tracking-form');
    if (trackingForm) {
        trackingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const val = document.getElementById('tracking_number_standalone').value.trim();
            if (!val) {
                Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Nomor resi tidak boleh kosong!', confirmButtonColor: '#10b981' });
                return;
            }
            Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            fetch(`/admin/orders/{{ $order->id }}/tracking`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: JSON.stringify({ tracking_number: val })
            })
            .then(r => r.json())
            .then(d => {
                if (d.success) {
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Resi disimpan', timer: 1500, showConfirmButton: false }).then(() => location.reload());
                } else { throw new Error(d.message); }
            })
            .catch(err => {
                Swal.fire({ icon: 'error', title: 'Gagal', text: err.message || 'Terjadi kesalahan', confirmButtonColor: '#ef4444' });
            });
        });
    }

    window.handleCancelOrder = async function(btn) {
        const result = await Swal.fire({
            title: 'Batalkan Pesanan?',
            text: "Tindakan ini tidak dapat dibatalkan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#9ca3af',
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Batal',
            customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-xl', cancelButton: 'rounded-xl' }
        });
        if (result.isConfirmed) {
            const form = btn.closest('form');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'order_status';
            input.value = 'cancelled';
            form.appendChild(input);
            form.submit();
        }
    };

    window.handleConfirmReturn = async function(btn) {
        const result = await Swal.fire({
            title: 'Konfirmasi Barang Retur?',
            text: "Pesanan akan dibatalkan dan stok dikembalikan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#9ca3af',
            confirmButtonText: 'Ya, Terima Retur',
            cancelButtonText: 'Batal',
            customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-xl', cancelButton: 'rounded-xl' }
        });
        if (result.isConfirmed) {
            Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            btn.closest('form').submit();
        }
    };
});
</script>
@endpush
