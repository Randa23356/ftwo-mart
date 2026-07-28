@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Subtle Background Pattern -->
    <div class="fixed inset-0 opacity-20 pointer-events-none">
        <div class="absolute inset-0" style="background-image: url(\"data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2316a34a' fill-opacity='0.06'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E\");"></div>
    </div>

    <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

        <!-- ===== PAGE HEADER ===== -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-6 sm:p-8 mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-br from-green-600 to-emerald-700 p-3 sm:p-4 rounded-2xl shadow-lg flex-shrink-0">
                        <i class="fas fa-user text-white text-xl sm:text-2xl"></i>
                    </div>
                    <div class="min-w-0">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 truncate">{{ $user->name }}</h1>
                        <p class="text-sm sm:text-base text-gray-500 mt-0.5 truncate">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                    <nav class="flex items-center space-x-2 text-sm text-gray-500 order-2 sm:order-1">
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-green-600 transition-colors font-medium">
                            <i class="fas fa-home mr-1"></i> Admin
                        </a>
                        <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
                        <a href="{{ route('admin.users') }}" class="hover:text-green-600 transition-colors font-medium">Pengguna</a>
                        <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
                        <span class="text-green-600 font-semibold truncate max-w-[120px]">{{ $user->name }}</span>
                    </nav>
                    @can('user-edit')
                    <a href="{{ route('admin.users.edit', $user) }}"
                       class="order-1 sm:order-2 inline-flex items-center justify-center px-4 py-2.5 bg-green-50 text-green-700 border border-green-200 rounded-xl hover:bg-green-100 transition-all text-sm font-semibold">
                        <i class="fas fa-pen mr-2"></i> Edit
                    </a>
                    @endcan
                </div>
            </div>
        </div>

        <!-- ===== USER PROFILE CARD ===== -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden mb-6 sm:mb-8">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-green-100">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 bg-gradient-to-br from-green-600 to-emerald-700 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-id-card text-white text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Profil Pengguna</h2>
                        <p class="text-xs text-gray-500">Informasi dasar pengguna</p>
                    </div>
                </div>
            </div>

            <div class="p-6 sm:p-8">
                <!-- Avatar & Name -->
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 sm:gap-6 mb-6 pb-6 border-b border-gray-100">
                    <img class="h-20 w-20 sm:h-24 sm:w-24 rounded-2xl object-cover border-2 border-green-200 shadow-lg flex-shrink-0"
                         src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                    <div class="text-center sm:text-left min-w-0">
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 truncate">{{ $user->name }}</h3>
                        <p class="text-sm text-gray-500 mt-0.5 truncate">{{ $user->email }}</p>
                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mt-3">
                            @foreach($user->roles as $role)
                                @if($role->name === 'admin')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800 border border-purple-200">
                                        <i class="fas fa-user-shield mr-1.5" style="font-size:9px"></i>{{ ucfirst($role->name) }}
                                    </span>
                                @elseif($role->name === 'operator')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                                        <i class="fas fa-user-tie mr-1.5" style="font-size:9px"></i>{{ ucfirst($role->name) }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                        <i class="fas fa-user mr-1.5" style="font-size:9px"></i>Pelanggan
                                    </span>
                                @endif
                            @endforeach

                            @if($user->is_active)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                    <i class="fas fa-circle mr-1.5" style="font-size:6px"></i>Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200">
                                    <i class="fas fa-circle mr-1.5" style="font-size:6px"></i>Tidak Aktif
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Telepon</p>
                        <p class="text-sm font-medium text-gray-900">{{ $user->phone ?? '-' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Bergabung</p>
                        <p class="text-sm font-medium text-gray-900">{{ $user->created_at->format('d F Y') }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 sm:col-span-2">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Alamat</p>
                        <p class="text-sm font-medium text-gray-900">{{ $user->address ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            @can('user-edit')
            <div class="px-6 sm:px-8 py-4 bg-gray-50 border-t border-gray-100">
                <div class="flex flex-col sm:flex-row gap-2">
                    <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="flex-1">
                        @csrf
                        @method('PUT')
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-sm
                                       {{ $user->is_active
                                           ? 'bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-100 hover:border-amber-300'
                                           : 'bg-green-50 text-green-700 border border-green-200 hover:bg-green-100 hover:border-green-300' }}">
                            <i class="fas {{ $user->is_active ? 'fa-pause' : 'fa-play' }} mr-2"></i>
                            {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                    <a href="{{ route('admin.users.edit', $user) }}"
                       class="flex-1 inline-flex items-center justify-center px-5 py-2.5 bg-green-50 text-green-700 border border-green-200 rounded-xl hover:bg-green-100 hover:border-green-300 transition-all text-sm font-semibold shadow-sm">
                        <i class="fas fa-pen mr-2"></i> Edit Pengguna
                    </a>
                </div>
            </div>
            @endcan
        </div>

        <!-- ===== ORDER HISTORY ===== -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-green-100">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 bg-gradient-to-br from-green-600 to-emerald-700 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-receipt text-white text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Riwayat Pesanan</h2>
                        <p class="text-xs text-gray-500">{{ $user->orders->count() }} pesanan</p>
                    </div>
                </div>
            </div>

            @if($user->orders->isEmpty())
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-receipt text-gray-400 text-2xl"></i>
                    </div>
                    <p class="text-sm text-gray-500 font-medium">Belum ada riwayat pesanan</p>
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($user->orders as $order)
                        <a href="{{ route('admin.orders.detail', $order) }}"
                           class="block px-6 py-4 hover:bg-green-50/40 transition-colors duration-150">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">Order #{{ $order->order_number }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        <i class="fas fa-calendar-alt mr-1"></i>{{ $order->created_at->format('d F Y H:i') }}
                                    </p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                        {{ match($order->order_status) {
                                            'pending' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                                            'processing' => 'bg-blue-100 text-blue-800 border border-blue-200',
                                            'ready' => 'bg-purple-100 text-purple-800 border border-purple-200',
                                            'shipped' => 'bg-indigo-100 text-indigo-800 border border-indigo-200',
                                            'delivered' => 'bg-green-100 text-green-800 border border-green-200',
                                            'cancelled' => 'bg-red-100 text-red-800 border border-red-200',
                                            default => 'bg-gray-100 text-gray-800 border border-gray-200',
                                        }}">
                                        {{ ucfirst($order->order_status) }}
                                    </span>
                                    <p class="text-sm font-semibold text-green-600 whitespace-nowrap">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
