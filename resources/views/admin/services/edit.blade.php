@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

        <!-- PAGE HEADER -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-6 sm:p-8 mb-6">
            <div class="flex items-center space-x-4">
                <div class="bg-gradient-to-br from-green-600 to-emerald-700 p-3 rounded-2xl shadow-lg flex-shrink-0">
                    <i class="fas fa-edit text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Layanan</h1>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $service->name }}</p>
                </div>
            </div>
        </div>

        <!-- FORM -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-green-100 flex items-center gap-3">
                <div class="w-9 h-9 bg-gradient-to-br from-green-600 to-emerald-700 rounded-xl flex items-center justify-center shadow-md">
                    <i class="fas fa-concierge-bell text-white text-sm"></i>
                </div>
                <h2 class="text-base font-bold text-gray-900">Detail Layanan</h2>
            </div>

            <form action="{{ route('admin.services.update', $service) }}" method="POST">
                @csrf @method('PUT')
                @php $inp = 'w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm bg-gray-50 focus:bg-white'; @endphp

                <div class="p-6 sm:p-8 space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Layanan <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $service->name) }}" class="{{ $inp }}" placeholder="Contoh: Pengiriman Express" required>
                        @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">URL <span class="text-gray-400 font-normal">(opsional)</span></label>
                        <input type="url" name="url" value="{{ old('url', $service->url) }}" class="{{ $inp }}" placeholder="https://...">
                        <p class="text-xs text-gray-400 mt-1.5"><i class="fas fa-info-circle mr-1 text-green-400"></i> Link tujuan saat layanan diklik di footer/halaman website</p>
                        @error('url') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl border border-gray-200">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                               class="h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500"
                               {{ old('is_active', $service->is_active) ? 'checked' : '' }}>
                        <div>
                            <label for="is_active" class="text-sm font-semibold text-gray-700 cursor-pointer">Aktifkan Layanan</label>
                            <p class="text-xs text-gray-400">Layanan aktif akan tampil di website</p>
                        </div>
                    </div>
                </div>

                <div class="px-6 sm:px-8 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between gap-3">
                    <a href="{{ route('admin.services.index') }}"
                       class="inline-flex items-center px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 text-sm font-semibold transition-all">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-xl text-sm font-semibold shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                        <i class="fas fa-save mr-2"></i> Update Layanan
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
