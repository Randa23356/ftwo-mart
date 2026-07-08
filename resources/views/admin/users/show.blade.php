@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6">
            <div class="flex items-center space-x-5">
                <div class="flex-shrink-0">
                    <img class="h-20 w-20 rounded-full" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                    <p class="text-sm font-medium text-gray-500">{{ $user->email }}</p>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-200 px-6 py-5">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Peran</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        @foreach($user->roles as $role)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">{{ $role->name }}</span>
                        @endforeach
                    </dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        @if($user->is_active)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Tidak Aktif</span>
                        @endif
                    </dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Telepon</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $user->phone ?? '-' }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Bergabung pada</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('d F Y') }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Alamat</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $user->address ?? '-' }}</dd>
                </div>
            </dl>
        </div>
        @can('user-edit')
        <div class="border-t border-gray-200 px-6 py-4 bg-gray-50">
            <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST">
                @csrf
                @method('PUT')
                <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white {{ $user->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }}">
                    {{ $user->is_active ? 'Nonaktifkan Pengguna' : 'Aktifkan Pengguna' }}
                </button>
            </form>
        </div>
        @endcan
    </div>

    <div class="mt-8">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Riwayat Pesanan</h2>
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <ul class="divide-y divide-gray-200">
                @forelse($user->orders as $order)
                    <li>
                        <a href="{{ route('admin.orders.detail', $order) }}" class="block hover:bg-gray-50">
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-indigo-600 truncate">Order #{{ $order->order_number }}</p>
                                    <div class="ml-2 flex-shrink-0 flex">
                                        <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $order->status_class }}">
                                            {{ $order->status_label }}
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-2 sm:flex sm:justify-between">
                                    <div class="sm:flex">
                                        <p class="flex items-center text-sm text-gray-500">
                                            <i class="fas fa-calendar-alt mr-2"></i> {{ $order->created_at->format('d F Y') }}
                                        </p>
                                    </div>
                                    <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                        <p>Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                @empty
                    <li class="px-6 py-8 text-center text-gray-600">Pengguna ini belum memiliki riwayat pesanan.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
