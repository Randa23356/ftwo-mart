@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

        <!-- PAGE HEADER -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-6 sm:p-8 mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-br from-green-600 to-emerald-700 p-3 sm:p-4 rounded-2xl shadow-lg flex-shrink-0">
                        <i class="fas fa-concierge-bell text-white text-xl sm:text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Kelola Layanan</h1>
                        <p class="text-sm text-gray-500 mt-0.5">Manajemen layanan yang ditampilkan di website</p>
                    </div>
                </div>
                <a href="{{ route('admin.services.create') }}"
                   class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-xl transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5 text-sm font-semibold shadow-md">
                    <i class="fas fa-plus mr-2"></i> Tambah Layanan
                </a>
            </div>
        </div>

        <!-- STATS -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-2.5 rounded-xl shadow-md">
                        <i class="fas fa-concierge-bell text-white text-base"></i>
                    </div>
                    <div class="text-2xl font-bold text-green-600">{{ $services->total() }}</div>
                </div>
                <div class="text-xs font-semibold text-gray-600">Total Layanan</div>
            </div>
            <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="bg-gradient-to-br from-emerald-500 to-teal-600 p-2.5 rounded-xl shadow-md">
                        <i class="fas fa-check-circle text-white text-base"></i>
                    </div>
                    <div class="text-2xl font-bold text-emerald-600">{{ $services->where('is_active', true)->count() }}</div>
                </div>
                <div class="text-xs font-semibold text-gray-600">Layanan Aktif</div>
            </div>
        </div>

        <!-- SUCCESS/ERROR ALERT -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-5 py-4 rounded-2xl mb-6 flex items-center gap-3">
                <i class="fas fa-check-circle text-green-600"></i>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <!-- TABLE -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-green-100">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-600 to-emerald-700 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-list text-white text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Daftar Layanan</h2>
                        <p class="text-xs text-gray-500">{{ $services->total() }} layanan tersedia</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Layanan</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden sm:table-cell">URL</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($services as $service)
                            <tr class="hover:bg-green-50/40 transition-colors duration-150 group">
                                <td class="px-5 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center text-white shadow-sm flex-shrink-0 group-hover:scale-105 transition-transform">
                                            <i class="fas fa-{{ $service->icon ?? 'concierge-bell' }} text-sm"></i>
                                        </div>
                                        <p class="font-semibold text-gray-900 text-sm">{{ $service->name }}</p>
                                    </div>
                                </td>
                                <td class="px-5 py-4 hidden sm:table-cell">
                                    @if($service->url)
                                        <a href="{{ $service->url }}" target="_blank" class="text-xs text-green-600 hover:underline font-mono truncate max-w-[200px] block">
                                            {{ $service->url }}
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                        {{ $service->is_active ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
                                        <i class="fas fa-circle mr-1.5" style="font-size:6px"></i>
                                        {{ $service->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.services.edit', $service) }}"
                                           class="inline-flex items-center px-3 py-2 bg-green-50 text-green-700 border border-green-200 rounded-lg hover:bg-green-100 transition-all text-xs font-semibold">
                                            <i class="fas fa-edit mr-1.5"></i>
                                            <span class="hidden sm:inline">Edit</span>
                                        </a>
                                        <form action="{{ route('admin.services.destroy', $service) }}" method="POST"
                                              onsubmit="return confirm('Hapus layanan {{ $service->name }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center px-3 py-2 bg-red-50 text-red-700 border border-red-200 rounded-lg hover:bg-red-100 transition-all text-xs font-semibold">
                                                <i class="fas fa-trash mr-1.5"></i>
                                                <span class="hidden sm:inline">Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 bg-gradient-to-br from-green-50 to-emerald-100 rounded-full flex items-center justify-center mb-4 shadow-inner">
                                            <i class="fas fa-concierge-bell text-green-500 text-3xl"></i>
                                        </div>
                                        <p class="text-lg font-semibold text-gray-700 mb-1">Belum ada layanan</p>
                                        <p class="text-sm text-gray-400 mb-5">Mulai dengan menambahkan layanan pertama</p>
                                        <a href="{{ route('admin.services.create') }}"
                                           class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all text-sm font-semibold shadow-md">
                                            <i class="fas fa-plus mr-2"></i> Tambah Layanan Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($services->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $services->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
