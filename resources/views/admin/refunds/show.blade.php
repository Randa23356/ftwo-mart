@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
    <div class="mb-6">
        <a href="{{ route('admin.refunds') }}" class="text-gray-500 hover:text-gray-700 text-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Pengembalian
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Refund Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Refund Info -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-gray-900">Detail Pengembalian</h2>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $refund->status_badge }}">
                        {{ ucfirst($refund->status) }}
                    </span>
                </div>

                <div class="space-y-4">
                    <div>
                        <span class="text-xs text-gray-500 uppercase tracking-wide">Nomor Pesanan</span>
                        <p class="font-semibold text-gray-900">#{{ $refund->order->order_number }}</p>
                    </div>
                    <div>
                        <span class="text-xs text-gray-500 uppercase tracking-wide">Alasan</span>
                        <p class="font-semibold text-gray-900">{{ $refund->reason_label }}</p>
                    </div>
                    @if($refund->notes)
                    <div>
                        <span class="text-xs text-gray-500 uppercase tracking-wide">Catatan Pembeli</span>
                        <p class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3">{{ $refund->notes }}</p>
                    </div>
                    @endif
                    @if($refund->evidence_image)
                    <div>
                        <span class="text-xs text-gray-500 uppercase tracking-wide mb-2 block">Bukti Foto</span>
                        <img src="{{ asset('storage/' . $refund->evidence_image) }}" alt="Bukti" class="rounded-xl border border-gray-200 max-w-xs">
                    </div>
                    @endif
                    <div>
                        <span class="text-xs text-gray-500 uppercase tracking-wide">Diajukan Pada</span>
                        <p class="text-sm text-gray-700">{{ $refund->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    @if($refund->admin_notes)
                    <div>
                        <span class="text-xs text-gray-500 uppercase tracking-wide">Catatan Admin</span>
                        <p class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3">{{ $refund->admin_notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 mb-4">Item Pesanan</h3>
                <div class="space-y-3">
                    @foreach($refund->order->orderItems as $item)
                    <div class="flex items-center gap-3 bg-gray-50 rounded-xl p-3">
                        <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-200 flex-shrink-0">
                            @if($item->product)
                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center"><i class="fas fa-image text-gray-400"></i></div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-800 text-sm truncate">{{ $item->product_name ?? 'Produk' }}</p>
                            <p class="text-xs text-gray-500">x{{ $item->quantity }} &bull; Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        </div>
                        <p class="font-bold text-gray-900 text-sm flex-shrink-0">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right: Actions -->
        <div class="space-y-6">
            <!-- Buyer Info -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 mb-3">Pembeli</h3>
                <div class="space-y-2 text-sm">
                    <div><span class="text-gray-500">Nama:</span> <span class="font-semibold">{{ $refund->user->name ?? '-' }}</span></div>
                    <div><span class="text-gray-500">Telepon:</span> <span class="font-semibold">{{ $refund->order->delivery_phone ?? '-' }}</span></div>
                    <div><span class="text-gray-500">Total:</span> <span class="font-bold text-gray-900">{{ $refund->order->formatted_total_with_shipping }}</span></div>
                    <div><span class="text-gray-500">Metode Bayar:</span> <span class="font-semibold uppercase">{{ $refund->order->payment_method }}</span></div>
                </div>
            </div>

            <!-- Actions -->
            @if($refund->status === 'pending')
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 mb-4">Tindakan</h3>
                <form action="{{ route('admin.refunds.approve', $refund) }}" method="POST" class="mb-3">
                    @csrf
                    <div class="mb-3">
                        <textarea name="admin_notes" rows="2" placeholder="Catatan untuk pembeli (opsional)"
                                  class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent"></textarea>
                    </div>
                    <button type="submit" onclick="return confirm('Setujui pengembalian ini? Pembeli akan diminta mengirim barang kembali.')"
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-xl transition-all">
                        <i class="fas fa-check mr-2"></i> Setujui
                    </button>
                </form>
                <form action="{{ route('admin.refunds.reject', $refund) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <textarea name="admin_notes" rows="2" placeholder="Alasan penolakan"
                                  class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent"></textarea>
                    </div>
                    <button type="submit" onclick="return confirm('Tolak pengembalian ini?')"
                            class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl transition-all">
                        <i class="fas fa-times mr-2"></i> Tolak
                    </button>
                </form>
            </div>
            @else
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 mb-3">Status</h3>
                <div class="space-y-2 text-sm">
                    <div><span class="text-gray-500">Status:</span> <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $refund->status_badge }}">{{ $refund->status_label }}</span></div>
                    <div><span class="text-gray-500">Diproses:</span> <span class="font-semibold">{{ $refund->reviewed_at?->format('d M Y, H:i') ?? '-' }}</span></div>
                    @if($refund->admin_notes)
                    <div><span class="text-gray-500">Catatan Admin:</span> <span class="text-gray-700">{{ $refund->admin_notes }}</span></div>
                    @endif
                    @if($refund->buyer_returned_at)
                    <div class="pt-2 mt-2 border-t border-gray-100">
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Pengembalian Barang</p>
                        <div><span class="text-gray-500">Resi:</span> <span class="font-bold text-purple-700">{{ $refund->return_tracking_number }}</span></div>
                        <div><span class="text-gray-500">Dikirim:</span> <span class="font-semibold">{{ $refund->buyer_returned_at->format('d M Y, H:i') }}</span></div>
                        @if($refund->return_evidence_image)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $refund->return_evidence_image) }}" alt="Bukti Return" class="rounded-lg border border-gray-200 max-w-[200px]">
                        </div>
                        @endif
                    </div>
                    @endif
                    @if($refund->seller_returned_at)
                    <div class="pt-2 mt-2 border-t border-gray-100">
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Diterima Seller</p>
                        <div><span class="text-gray-500">Tanggal:</span> <span class="font-semibold">{{ $refund->seller_returned_at->format('d M Y, H:i') }}</span></div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
