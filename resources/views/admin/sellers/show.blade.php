@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
        <!-- Header -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl lg:rounded-3xl shadow-xl border border-white/20 p-6 lg:p-8 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h1 class="text-2xl font-bold text-gray-900">{{ $seller->shop_name }}</h1>
                        @if($seller->approval_status === 'pending')
                            <span class="px-2 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-800">Menunggu</span>
                        @elseif($seller->approval_status === 'approved')
                            <span class="px-2 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">Disetujui</span>
                        @else
                            <span class="px-2 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800">Ditolak</span>
                        @endif
                    </div>
                    <p class="text-gray-600 text-sm mt-1">Pemilik: {{ $seller->user->name }} ({{ $seller->user->email }})</p>
                    <p class="text-gray-400 text-xs mt-0.5">Terdaftar: {{ $seller->created_at->format('d M Y, H:i') }}</p>
                    @if($seller->rejection_reason)
                        <p class="text-red-600 text-sm mt-2 bg-red-50 px-3 py-2 rounded-lg">Alasan penolakan: {{ $seller->rejection_reason }}</p>
                    @endif
                </div>
                <div class="flex gap-2 flex-wrap">
                    @if($seller->approval_status === 'pending')
                        <form method="POST" action="{{ route('admin.sellers.approve', $seller) }}">
                            @csrf @method('PUT')
                            <button class="px-4 py-2 rounded-xl text-sm font-semibold transition-all bg-green-600 text-white hover:bg-green-700 shadow-md">
                                <i class="fas fa-check mr-1"></i> Setujui
                            </button>
                        </form>
                        <button onclick="document.getElementById('rejectModal').classList.remove('hidden')" class="px-4 py-2 rounded-xl text-sm font-semibold transition-all bg-red-600 text-white hover:bg-red-700 shadow-md">
                            <i class="fas fa-times mr-1"></i> Tolak
                        </button>
                    @endif
                    <form method="POST" action="{{ route('admin.sellers.toggle-verification', $seller) }}">
                        @csrf @method('PUT')
                        <button class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ $seller->is_verified ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' }}">
                            <i class="fas fa-{{ $seller->is_verified ? 'check-circle' : 'clock' }} mr-1"></i>
                            {{ $seller->is_verified ? 'Terverifikasi' : 'Verifikasi' }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.sellers.toggle-status', $seller) }}">
                        @csrf @method('PUT')
                        <button class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ $seller->is_active ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' : 'bg-red-100 text-red-700 hover:bg-red-200' }}">
                            <i class="fas fa-{{ $seller->is_active ? 'pause' : 'play' }} mr-1"></i>
                            {{ $seller->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <div class="bg-white/80 backdrop-blur-xl rounded-xl shadow-xl border border-white/20 p-4">
                <p class="text-xs text-gray-500">Produk</p>
                <p class="text-xl font-bold text-gray-900">{{ $stats['total_products'] }}</p>
            </div>
            <div class="bg-white/80 backdrop-blur-xl rounded-xl shadow-xl border border-white/20 p-4">
                <p class="text-xs text-gray-500">Transaksi</p>
                <p class="text-xl font-bold text-gray-900">{{ $stats['total_orders'] }}</p>
            </div>
            <div class="bg-white/80 backdrop-blur-xl rounded-xl shadow-xl border border-white/20 p-4">
                <p class="text-xs text-gray-500">Penghasilan</p>
                <p class="text-xl font-bold text-gray-900">Rp {{ number_format($stats['total_earnings'], 0, ',', '.') }}</p>
            </div>
            <div class="bg-white/80 backdrop-blur-xl rounded-xl shadow-xl border border-white/20 p-4">
                <p class="text-xs text-gray-500">Saldo</p>
                <p class="text-xl font-bold text-green-600">Rp {{ number_format($stats['balance'], 0, ',', '.') }}</p>
            </div>
            <div class="bg-white/80 backdrop-blur-xl rounded-xl shadow-xl border border-white/20 p-4">
                <p class="text-xs text-gray-500">Ditarik</p>
                <p class="text-xl font-bold text-blue-600">Rp {{ number_format($stats['total_withdrawn'], 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Documents -->
        @if($seller->ktp_path || $seller->nib_path || $seller->npwp_path || $seller->rekening_tabungan_path)
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-6 mb-6">
            <h2 class="font-bold text-gray-800 mb-4"><i class="fas fa-file-alt mr-1"></i> Dokumen Seller</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @if($seller->ktp_path)
                <a href="{{ Storage::url($seller->ktp_path) }}" target="_blank" class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition-all group">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mb-2">
                        <i class="fas fa-id-card text-blue-600"></i>
                    </div>
                    <p class="text-sm font-semibold text-gray-800">KTP</p>
                    <p class="text-xs text-gray-500 group-hover:text-green-600 mt-1">Lihat dokumen →</p>
                </a>
                @endif
                @if($seller->nib_path)
                <a href="{{ Storage::url($seller->nib_path) }}" target="_blank" class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition-all group">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mb-2">
                        <i class="fas fa-file-contract text-purple-600"></i>
                    </div>
                    <p class="text-sm font-semibold text-gray-800">NIB</p>
                    <p class="text-xs text-gray-500 group-hover:text-green-600 mt-1">Lihat dokumen →</p>
                </a>
                @endif
                @if($seller->npwp_path)
                <a href="{{ Storage::url($seller->npwp_path) }}" target="_blank" class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition-all group">
                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mb-2">
                        <i class="fas fa-file-invoice text-yellow-600"></i>
                    </div>
                    <p class="text-sm font-semibold text-gray-800">NPWP</p>
                    <p class="text-xs text-gray-500 group-hover:text-green-600 mt-1">Lihat dokumen →</p>
                </a>
                @endif
                @if($seller->rekening_tabungan_path)
                <a href="{{ Storage::url($seller->rekening_tabungan_path) }}" target="_blank" class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition-all group">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mb-2">
                        <i class="fas fa-university text-green-600"></i>
                    </div>
                    <p class="text-sm font-semibold text-gray-800">Rekening Tabungan</p>
                    <p class="text-xs text-gray-500 group-hover:text-green-600 mt-1">Lihat dokumen →</p>
                </a>
                @endif
            </div>
        </div>
        @endif

        <!-- Bank Info -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-6 mb-6">
            <h2 class="font-bold text-gray-800 mb-3"><i class="fas fa-university mr-1"></i> Rekening Bank</h2>
            <div class="grid grid-cols-3 gap-4 text-sm">
                <div><span class="text-gray-500">Bank:</span> <span class="font-semibold">{{ $seller->bank_name }}</span></div>
                <div><span class="text-gray-500">No. Rekening:</span> <span class="font-semibold">{{ $seller->bank_account_number }}</span></div>
                <div><span class="text-gray-500">Atas Nama:</span> <span class="font-semibold">{{ $seller->bank_account_name }}</span></div>
            </div>
        </div>

        <!-- Recent Withdrawals -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-6">
            <h2 class="font-bold text-gray-800 mb-3"><i class="fas fa-money-bill-wave mr-1"></i> Riwayat Penarikan</h2>
            @if($seller->withdrawals->isEmpty())
                <p class="text-gray-500 text-sm">Belum ada riwayat penarikan</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-2 font-semibold text-gray-600">Tanggal</th>
                                <th class="text-right py-2 font-semibold text-gray-600">Jumlah</th>
                                <th class="text-center py-2 font-semibold text-gray-600">Status</th>
                                <th class="text-left py-2 font-semibold text-gray-600">Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($seller->withdrawals->take(10) as $w)
                            <tr class="border-b border-gray-50">
                                <td class="py-2 text-gray-600">{{ $w->created_at->format('d M Y') }}</td>
                                <td class="py-2 text-right font-semibold">{{ $w->formatted_amount }}</td>
                                <td class="py-2 text-center"><span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $w->status_badge }}">{{ ucfirst($w->status) }}</span></td>
                                <td class="py-2 text-gray-500 text-xs">{{ $w->admin_notes ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-2">Tolak Registrasi Seller</h3>
        <p class="text-gray-500 text-sm mb-4">Alasannya akan dikirim ke seller sebagai notifikasi.</p>
        <form method="POST" action="{{ route('admin.sellers.reject', $seller) }}">
            @csrf @method('POST')
            <textarea name="rejection_reason" rows="3" required placeholder="Contoh: Dokumen tidak terbaca dengan jelas..."
                      class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-red-500 mb-4"></textarea>
            <div class="flex gap-2 justify-end">
                <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" class="px-4 py-2 rounded-xl text-sm font-semibold bg-gray-100 hover:bg-gray-200 transition-all">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl text-sm font-semibold bg-red-600 text-white hover:bg-red-700 transition-all">Tolak</button>
            </div>
        </form>
    </div>
</div>
@endsection
