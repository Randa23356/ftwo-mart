@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="fixed inset-0 opacity-20 pointer-events-none">
        <div class="absolute inset-0" style="background-image: url(\"data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2316a34a' fill-opacity='0.06'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E\");"></div>
    </div>

    <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-6 sm:p-8 mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-br from-green-600 to-emerald-700 p-3 sm:p-4 rounded-2xl shadow-lg flex-shrink-0">
                        <i class="fas fa-plus text-white text-xl sm:text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Tambah Kategori</h1>
                        <p class="text-sm sm:text-base text-gray-500 mt-0.5">Buat kategori produk baru</p>
                    </div>
                </div>
                <nav class="flex items-center space-x-2 text-sm text-gray-500">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-green-600 transition-colors font-medium">
                        <i class="fas fa-home mr-1"></i> Admin
                    </a>
                    <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
                    <a href="{{ route('admin.categories.index') }}" class="hover:text-green-600 transition-colors font-medium">Kategori</a>
                    <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
                    <span class="text-green-600 font-semibold">Tambah</span>
                </nav>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-green-100">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 bg-gradient-to-br from-green-600 to-emerald-700 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-tags text-white text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Informasi Kategori</h2>
                        <p class="text-xs text-gray-500">Lengkapi data kategori dengan benar</p>
                    </div>
                </div>
            </div>

            <div class="p-6 sm:p-8">
                @include('admin.categories._form')
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
function previewCategoryImage(event) {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('preview').src = e.target.result;
        document.getElementById('imagePreview').classList.remove('hidden');
        document.getElementById('upload-placeholder').innerHTML = `
            <div class="w-14 h-14 bg-green-200 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-check-circle text-green-600 text-2xl"></i>
            </div>
            <p class="text-sm font-semibold text-green-700">${file.name}</p>
            <p class="text-xs text-gray-500 mt-1">Klik untuk mengganti</p>
        `;
    };
    reader.readAsDataURL(file);
}

function toggleActiveLabel(checkbox) {
    const label = document.getElementById('is_active_label');
    if (checkbox.checked) {
        label.classList.remove('border-gray-200', 'bg-white');
        label.classList.add('border-green-200', 'bg-green-50');
    } else {
        label.classList.remove('border-green-200', 'bg-green-50');
        label.classList.add('border-gray-200', 'bg-white');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const cb = document.getElementById('is_active');
    if (cb) toggleActiveLabel(cb);
});
</script>
@endpush
