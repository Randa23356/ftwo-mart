@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Subtle Background Pattern -->
    <div class="fixed inset-0 opacity-20 pointer-events-none">
        <div class="absolute inset-0" style="background-image: url(\"data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2316a34a' fill-opacity='0.06'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E\");"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

        <!-- ===== PAGE HEADER ===== -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-6 sm:p-8 mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-br from-green-600 to-emerald-700 p-3 sm:p-4 rounded-2xl shadow-lg flex-shrink-0">
                        <i class="fas fa-users text-white text-xl sm:text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Manajemen Pengguna</h1>
                        <p class="text-sm sm:text-base text-gray-500 mt-0.5">Kelola semua pengguna sistem Batik Sasambo</p>
                    </div>
                </div>
                @can('user-create')
                <a href="{{ route('admin.users.create') }}"
                   class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-xl transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5 text-sm font-semibold shadow-md">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Pengguna
                </a>
                @endcan
            </div>
        </div>

        <!-- ===== STATS ===== -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-6 sm:mb-8">
            <div class="group relative bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 p-5 sm:p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-green-400/15 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-2.5 rounded-xl shadow-md">
                            <i class="fas fa-users text-white text-base"></i>
                        </div>
                        <div class="text-2xl sm:text-3xl font-bold text-green-600">{{ $userStats['total'] }}</div>
                    </div>
                    <div class="text-xs sm:text-sm font-semibold text-gray-600">Total Pengguna</div>
                </div>
            </div>
            <div class="group relative bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 p-5 sm:p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-emerald-400/15 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <div class="bg-gradient-to-br from-emerald-500 to-teal-600 p-2.5 rounded-xl shadow-md">
                            <i class="fas fa-user text-white text-base"></i>
                        </div>
                        <div class="text-2xl sm:text-3xl font-bold text-emerald-600">{{ $userStats['customers'] }}</div>
                    </div>
                    <div class="text-xs sm:text-sm font-semibold text-gray-600">Pelanggan</div>
                </div>
            </div>
            <div class="group relative bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 p-5 sm:p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-blue-400/15 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-2.5 rounded-xl shadow-md">
                            <i class="fas fa-user-tie text-white text-base"></i>
                        </div>
                        <div class="text-2xl sm:text-3xl font-bold text-blue-600">{{ $userStats['operators'] }}</div>
                    </div>
                    <div class="text-xs sm:text-sm font-semibold text-gray-600">Operator</div>
                </div>
            </div>
            <div class="group relative bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 p-5 sm:p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-purple-400/15 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <div class="bg-gradient-to-br from-purple-500 to-violet-600 p-2.5 rounded-xl shadow-md">
                            <i class="fas fa-user-shield text-white text-base"></i>
                        </div>
                        <div class="text-2xl sm:text-3xl font-bold text-purple-600">{{ $userStats['admins'] }}</div>
                    </div>
                    <div class="text-xs sm:text-sm font-semibold text-gray-600">Admin</div>
                </div>
            </div>
        </div>

        <!-- ===== FILTER TABS ===== -->
        <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 p-4 mb-6">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.users') }}"
                   class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200
                          {{ !request('role')
                              ? 'bg-gradient-to-r from-green-600 to-emerald-600 text-white shadow-md'
                              : 'bg-gray-50 text-gray-600 border border-gray-200 hover:bg-gray-100' }}">
                    <i class="fas fa-users mr-2 text-xs"></i>
                    Semua Pengguna
                </a>
                <a href="{{ route('admin.users', ['role' => 'user']) }}"
                   class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200
                          {{ request('role') === 'user'
                              ? 'bg-gradient-to-r from-green-600 to-emerald-600 text-white shadow-md'
                              : 'bg-gray-50 text-gray-600 border border-gray-200 hover:bg-gray-100' }}">
                    <i class="fas fa-user mr-2 text-xs"></i>
                    Pelanggan
                </a>
                <a href="{{ route('admin.users', ['role' => 'operator']) }}"
                   class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200
                          {{ request('role') === 'operator'
                              ? 'bg-gradient-to-r from-green-600 to-emerald-600 text-white shadow-md'
                              : 'bg-gray-50 text-gray-600 border border-gray-200 hover:bg-gray-100' }}">
                    <i class="fas fa-user-tie mr-2 text-xs"></i>
                    Operator
                </a>
                <a href="{{ route('admin.users', ['role' => 'admin']) }}"
                   class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200
                          {{ request('role') === 'admin'
                              ? 'bg-gradient-to-r from-green-600 to-emerald-600 text-white shadow-md'
                              : 'bg-gray-50 text-gray-600 border border-gray-200 hover:bg-gray-100' }}">
                    <i class="fas fa-user-shield mr-2 text-xs"></i>
                    Admin
                </a>
            </div>
        </div>

        <!-- ===== USERS TABLE ===== -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-green-100">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-600 to-emerald-700 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-list text-white text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">
                            @if(request('role'))
                                {{ ucfirst(request('role') === 'user' ? 'Pelanggan' : request('role')) }}
                            @else
                                Semua Pengguna
                            @endif
                        </h2>
                        <p class="text-xs text-gray-500">{{ $users->total() }} pengguna terdaftar</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Pengguna</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden sm:table-cell">Peran</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden md:table-cell">Status</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden lg:table-cell">Bergabung</th>
                            <th class="px-5 py-3.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-green-50/40 transition-colors duration-150 group">
                                <td class="px-5 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center text-white font-bold shadow-sm flex-shrink-0 group-hover:scale-105 transition-transform">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-semibold text-gray-900 text-sm">{{ $user->name }}</p>
                                            <p class="text-xs text-gray-400 mt-0.5 truncate">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 hidden sm:table-cell">
                                    @foreach($user->roles as $role)
                                        @if($role->name === 'admin')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800 border border-purple-200">
                                                <i class="fas fa-user-shield mr-1.5" style="font-size:9px"></i>{{ ucfirst($role->name) }}
                                            </span>
                                        @elseif($role->name === 'operator')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                                                <i class="fas fa-user-tie mr-1.5" style="font-size:9px"></i>{{ ucfirst($role->name) }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                                <i class="fas fa-user mr-1.5" style="font-size:9px"></i>Pelanggan
                                            </span>
                                        @endif
                                    @endforeach
                                </td>
                                <td class="px-5 py-4 hidden md:table-cell">
                                    @if($user->is_active)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                            <i class="fas fa-circle mr-1.5" style="font-size:6px"></i>Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200">
                                            <i class="fas fa-circle mr-1.5" style="font-size:6px"></i>Tidak Aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 hidden lg:table-cell">
                                    <span class="text-sm text-gray-600">{{ $user->created_at->format('d M Y') }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-center">
                                        @can('user-edit')
                                        <a href="{{ route('admin.users.detail', $user) }}"
                                           class="inline-flex items-center px-3 py-2 bg-green-50 text-green-700 border border-green-200 rounded-lg hover:bg-green-100 hover:border-green-300 transition-all duration-200 text-xs font-semibold">
                                            <i class="fas fa-eye mr-1.5"></i>
                                            <span class="hidden sm:inline">Detail</span>
                                        </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 bg-gradient-to-br from-green-50 to-emerald-100 rounded-full flex items-center justify-center mb-4 shadow-inner">
                                            <i class="fas fa-users text-green-500 text-3xl"></i>
                                        </div>
                                        <p class="text-lg font-semibold text-gray-700 mb-1">Belum ada pengguna</p>
                                        <p class="text-sm text-gray-400">Pengguna akan muncul di sini</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $users->links() }}
            </div>
            @endif
        </div>

    </div>
</div>
@endsection
