@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Enhanced Header -->
            <div class="mb-6 sm:mb-8">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-12 h-12 bg-green-700 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-plus text-white text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                            Tambah Produk Baru
                        </h1>
                        <p class="text-sm sm:text-base text-gray-600">Tambahkan produk FtwoMart baru</p>
                    </div>
                </div>
                
                <!-- Breadcrumb -->
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-2 sm:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('operator.dashboard') }}" class="inline-flex items-center text-xs sm:text-sm font-medium text-gray-600 hover:text-green-600 transition-all duration-200 hover:scale-105">
                                <i class="fas fa-home mr-1.5 sm:mr-2 text-green-600"></i>
                                <span class="hidden sm:inline">Operator</span>
                                <span class="sm:hidden">Home</span>
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs sm:text-sm animate-pulse"></i>
                                <a href="{{ route('operator.products.index') }}" class="inline-flex items-center text-xs sm:text-sm font-medium text-gray-600 hover:text-green-600 transition-all duration-200 hover:scale-105">
                                    <i class="fas fa-store mr-1.5 sm:mr-2 text-green-600"></i>
                                    <span class="hidden sm:inline">Produk</span>
                                    <span class="sm:hidden">Produk</span>
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs sm:text-sm animate-pulse"></i>
                                <span class="ml-1 text-xs sm:text-sm font-medium text-gray-700 sm:ml-2 bg-green-50 px-3 py-1 rounded-full">Tambah Produk</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>

            <!-- Enhanced Form Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <!-- Form Header -->
                <div class="bg-green-50 px-6 sm:px-8 py-4 border-b border-gray-100">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-600 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                            <i class="fas fa-edit text-white text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Informasi Produk</h2>
                            <p class="text-xs text-gray-600">Lengkapi data produk dengan benar</p>
                        </div>
                    </div>
                </div>
                
                <!-- Form Content -->
                <div class="p-6 sm:p-8">
                    <form method="POST" action="{{ route('operator.products.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Basic Information -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center">
                                <i class="fas fa-info-circle mr-2 text-green-600"></i>
                                Informasi Dasar
                            </h3>
                            
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">
                                        <i class="fas fa-tag mr-1 text-green-600"></i>
                                        Nama Produk
                                    </label>
                                    <input type="text" name="name" value="{{ old('name') }}" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm"
                                           placeholder="Masukkan nama produk">
                                    @error('name')
                                        <p class="text-sm text-red-600 mt-2 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">
                                        <i class="fas fa-list mr-1 text-green-600"></i>
                                        Kategori
                                    </label>
                                    <select name="category_id" 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm">
                                        <option value="">Pilih Kategori</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <p class="text-sm text-red-600 mt-2 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    <i class="fas fa-align-left mr-1 text-green-600"></i>
                                    Deskripsi Produk
                                </label>
                                <textarea name="description" rows="4" 
                                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm resize-vertical"
                                          placeholder="Jelaskan produk secara detail">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-sm text-red-600 mt-2 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Pricing & Inventory -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center">
                                <i class="fas fa-dollar-sign mr-2 text-green-600"></i>
                                Harga & Stok
                            </h3>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">
                                        <i class="fas fa-money-bill mr-1 text-green-600"></i>
                                        Harga (Rp)
                                    </label>
                                    <input type="number" name="price" value="{{ old('price') }}" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm"
                                           placeholder="0" min="0">
                                    @error('price')
                                        <p class="text-sm text-red-600 mt-2 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">
                                        <i class="fas fa-boxes mr-1 text-green-600"></i>
                                        Stok
                                    </label>
                                    <input type="number" name="stock" value="{{ old('stock') }}" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm"
                                           placeholder="0" min="0">
                                    @error('stock')
                                        <p class="text-sm text-red-600 mt-2 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">
                                        <i class="fas fa-weight mr-1 text-green-600"></i>
                                        Berat (gram)
                                    </label>
                                    <input type="number" name="weight" value="{{ old('weight', 500) }}" min="1" max="50000" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm"
                                           placeholder="500">
                                    @error('weight')
                                        <p class="text-sm text-red-600 mt-2 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                    <p class="text-xs text-gray-500 mt-2 flex items-center">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Berat untuk perhitungan ongkir
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Product Images -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center">
                                <i class="fas fa-images mr-2 text-green-600"></i>
                                Gambar Produk
                            </h3>
                            
                            <div class="space-y-6">
                                <!-- Multiple Images Upload -->
                                <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                                    <div class="flex items-center mb-4">
                                        <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-layer-group text-white text-sm"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-900">Upload Multiple Gambar</h4>
                                            <p class="text-sm text-gray-600">Rekomendasi untuk tampilan terbaik</p>
                                        </div>
                                    </div>
                                    
                                    <input type="file" name="images[]" multiple accept="image/*" 
                                           class="w-full text-sm text-gray-600 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-green-100 file:text-green-700 hover:file:bg-green-200 transition-all"
                                           onchange="showSelectedFiles(this)">
                                    <p class="text-xs text-gray-500 mt-2">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Maksimal 10 gambar, masing-masing max 2MB. Format: JPG, JPEG, PNG
                                    </p>
                                    <div id="selected-files" class="mt-3 text-sm text-gray-700"></div>
                                </div>
                                
                                <!-- Separator -->
                                <div class="flex items-center">
                                    <div class="flex-1 border-t border-gray-300"></div>
                                    <span class="px-4 text-sm text-gray-500 font-medium">atau</span>
                                    <div class="flex-1 border-t border-gray-300"></div>
                                </div>
                                
                                <!-- Single Image Upload -->
                                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                                    <div class="flex items-center mb-4">
                                        <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-image text-white text-sm"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-900">Upload Satu Gambar</h4>
                                            <p class="text-sm text-gray-600">Untuk gambar utama produk</p>
                                        </div>
                                    </div>
                                    
                                    <input type="file" name="image" accept="image/*" 
                                           class="w-full text-sm text-gray-600 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 transition-all">
                                    <p class="text-xs text-gray-500 mt-2">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Maksimal 2MB. Format: JPG, JPEG, PNG
                                    </p>
                                </div>
                            </div>
                            
                            @error('image')
                                <p class="text-sm text-red-600 mt-2 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                            @error('images')
                                <p class="text-sm text-red-600 mt-2 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                            @error('images.*')
                                <p class="text-sm text-red-600 mt-2 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Form Actions -->
                        <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-6 border-t border-gray-200">
                            <a href="{{ route('operator.products.index') }}" 
                               class="w-full sm:w-auto px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-200 text-sm font-medium text-center">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali
                            </a>
                            <button type="submit" 
                                    class="w-full sm:w-auto px-8 py-3 bg-green-700 text-white rounded-xl hover:bg-green-800 transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5 text-sm font-bold shadow-lg">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Produk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
function showSelectedFiles(input) {
    const fileList = document.getElementById('selected-files');
    if (input.files.length === 0) {
        fileList.innerHTML = '';
        return;
    }
    
    let html = '<div class="bg-green-100 border border-green-300 rounded-lg p-3"><strong class="text-green-800">File terpilih (' + input.files.length + '):</strong><ul class="list-disc list-inside mt-2 text-green-700">';
    for (let i = 0; i < input.files.length; i++) {
        const file = input.files[i];
        const sizeKB = Math.round(file.size / 1024);
        const validSize = sizeKB <= 2048;
        const sizeColor = validSize ? 'text-green-600' : 'text-red-600';
        const sizeIcon = validSize ? '✅' : '❌';
        html += '<li class="flex items-center justify-between"><span>' + file.name + '</span><span class="' + sizeColor + '">' + sizeIcon + ' ' + sizeKB + ' KB</span></li>';
    }
    html += '</ul></div>';
    fileList.innerHTML = html;
}
</script>
@endpush
