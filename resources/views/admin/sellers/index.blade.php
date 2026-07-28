@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl lg:rounded-3xl shadow-xl border border-white/20 p-4 sm:p-6 lg:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div class="flex items-center gap-3">
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900"><i class="fas fa-store mr-2 text-green-600"></i>Kelola Seller</h1>
                    @if($pendingCount > 0)
                        <span class="bg-orange-100 text-orange-700 text-xs sm:text-sm font-bold px-3 py-1 rounded-full border border-orange-200">
                            {{ $pendingCount }} Menunggu
                        </span>
                    @endif
                </div>
                <form method="GET" class="flex flex-col sm:flex-row gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari seller..."
                           class="px-4 py-2 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-green-500 w-full sm:w-auto">
                    <div class="flex gap-2">
                        <select name="status" class="px-3 py-2 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-green-500 flex-1 sm:flex-none">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                        <button class="bg-green-600 text-white px-4 py-2 rounded-xl text-sm hover:bg-green-700"><i class="fas fa-search"></i></button>
                    </div>
                </form>
            </div>

            @if($sellers->isEmpty())
                <div class="text-center py-12 text-gray-500">
                    <i class="fas fa-store text-5xl text-gray-300 mb-4"></i>
                    <p>Belum ada seller terdaftar</p>
                </div>
            @else
                {{-- Desktop / Tablet Table --}}
                <div class="hidden sm:block overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-3 font-semibold text-gray-600">Toko</th>
                                <th class="text-left py-3 px-3 font-semibold text-gray-600">Pemilik</th>
                                <th class="text-right py-3 px-3 font-semibold text-gray-600">Produk</th>
                                <th class="text-right py-3 px-3 font-semibold text-gray-600">Saldo</th>
                                <th class="text-center py-3 px-3 font-semibold text-gray-600">Status</th>
                                <th class="text-center py-3 px-3 font-semibold text-gray-600">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sellers as $s)
                            <tr class="border-b border-gray-50 hover:bg-gray-50 {{ $s->approval_status === 'pending' ? 'bg-orange-50/50' : '' }}">
                                <td class="py-3 px-3">
                                    <a href="{{ route('admin.sellers.show', $s) }}" class="font-semibold text-gray-900 hover:text-green-600">{{ $s->shop_name }}</a>
                                </td>
                                <td class="py-3 px-3 text-gray-700">{{ $s->user->name }}</td>
                                <td class="py-3 px-3 text-right text-gray-700">{{ $s->products()->count() }}</td>
                                <td class="py-3 px-3 text-right font-semibold text-green-600">{{ $s->formatted_balance }}</td>
                                <td class="py-3 px-3 text-center">
                                    @if($s->approval_status === 'pending')
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800">Menunggu</span>
                                    @elseif($s->approval_status === 'approved')
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Disetujui</span>
                                    @else
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Ditolak</span>
                                    @endif
                                    @if($s->approval_status === 'approved' && !$s->is_active)
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 ml-1">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-center">
                                    <a href="{{ route('admin.sellers.show', $s) }}" class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1 rounded-lg text-xs font-semibold">Detail</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Card Layout --}}
                <div class="sm:hidden space-y-3">
                    @foreach($sellers as $s)
                    <a href="{{ route('admin.sellers.show', $s) }}" class="block bg-white rounded-xl border border-gray-200 p-4 hover:shadow-md transition-shadow {{ $s->approval_status === 'pending' ? 'border-orange-200 bg-orange-50/30' : '' }}">
                        <div class="flex items-start justify-between gap-3 mb-2">
                            <div class="min-w-0">
                                <h3 class="font-semibold text-gray-900 truncate">{{ $s->shop_name }}</h3>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $s->user->name }}</p>
                            </div>
                            <div class="flex flex-col items-end gap-1 flex-shrink-0">
                                @if($s->approval_status === 'pending')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-orange-100 text-orange-800">Menunggu</span>
                                @elseif($s->approval_status === 'approved')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-green-100 text-green-800">Disetujui</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-red-100 text-red-800">Ditolak</span>
                                @endif
                                @if($s->approval_status === 'approved' && !$s->is_active)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-gray-100 text-gray-800">Nonaktif</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-xs text-gray-600 pt-2 border-t border-gray-100">
                            <span><i class="fas fa-box mr-1 text-gray-400"></i>{{ $s->products()->count() }} produk</span>
                            <span class="font-semibold text-green-600">{{ $s->formatted_balance }}</span>
                        </div>
                    </a>
                    @endforeach
                </div>

                <div class="mt-6">{{ $sellers->withQueryString()->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
