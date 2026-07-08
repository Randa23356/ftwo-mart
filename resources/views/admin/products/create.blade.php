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
                        <i class="fas fa-plus text-white text-xl sm:text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Tambah Produk</h1>
                        <p class="text-sm sm:text-base text-gray-500 mt-0.5">Lengkapi informasi produk baru</p>
                    </div>
                </div>
                <!-- Breadcrumb -->
                <nav class="flex items-center space-x-2 text-sm text-gray-500">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-green-600 transition-colors font-medium">
                        <i class="fas fa-home mr-1"></i> Admin
                    </a>
                    <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
                    <a href="{{ route('admin.products.index') }}" class="hover:text-green-600 transition-colors font-medium">Produk</a>
                    <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
                    <span class="text-green-600 font-semibold">Tambah</span>
                </nav>
            </div>
        </div>

        <!-- ===== FORM CARD ===== -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">

            <!-- Form Header -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-green-100">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 bg-gradient-to-br from-green-600 to-emerald-700 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-info-circle text-white text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Informasi Produk</h2>
                        <p class="text-xs text-gray-500">Isi semua informasi dengan benar</p>
                    </div>
                </div>
            </div>

            <div class="p-6 sm:p-8">
                <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-8">
                    @csrf

                    <!-- ===== SECTION 1: BASIC INFO ===== -->
                    <div>
                        <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <span class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center text-green-700 text-xs font-bold">1</span>
                            Informasi Dasar
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- Nama Produk -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nama Produk <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                       placeholder="Contoh: Batik Motif Parang"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm bg-gray-50 focus:bg-white">
                                @error('name')
                                    <p class="text-xs text-red-600 mt-1.5 flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Kategori -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Kategori <span class="text-red-500">*</span>
                                </label>
                                <select name="category_id"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm bg-gray-50 focus:bg-white">
                                    <option value="">— Pilih Kategori —</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="text-xs text-red-600 mt-1.5 flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mt-5">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Produk</label>
                            <textarea name="description" rows="4"
                                      placeholder="Jelaskan detail produk, bahan, ukuran, keunikan, dll..."
                                      class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm bg-gray-50 focus:bg-white resize-none">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-xs text-red-600 mt-1.5 flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    <!-- ===== SECTION 2: PRICING & STOCK ===== -->
                    <div>
                        <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <span class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center text-green-700 text-xs font-bold">2</span>
                            Harga & Stok
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                            <!-- Harga -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Harga <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm font-semibold text-gray-500">Rp</span>
                                    <input type="number" name="price" value="{{ old('price') }}" min="0" step="1000"
                                           placeholder="0"
                                           class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm bg-gray-50 focus:bg-white">
                                </div>
                                @error('price')
                                    <p class="text-xs text-red-600 mt-1.5 flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Stok -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Stok <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="stock" value="{{ old('stock') }}" min="0"
                                       placeholder="0"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm bg-gray-50 focus:bg-white">
                                @error('stock')
                                    <p class="text-xs text-red-600 mt-1.5 flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Berat -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Berat (gram)</label>
                                <div class="relative">
                                    <input type="number" name="weight" value="{{ old('weight', 500) }}" min="1" max="50000"
                                           placeholder="500"
                                           class="w-full px-4 py-3 pr-14 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm bg-gray-50 focus:bg-white">
                                    <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-xs text-gray-400 font-medium">gram</span>
                                </div>
                                <p class="text-xs text-gray-400 mt-1.5 flex items-center gap-1">
                                    <i class="fas fa-info-circle text-green-400"></i> Untuk perhitungan ongkir
                                </p>
                                @error('weight')
                                    <p class="text-xs text-red-600 mt-1 flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    <!-- ===== SECTION 3: IMAGES ===== -->
                    <div>
                        <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <span class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center text-green-700 text-xs font-bold">3</span>
                            Foto Produk
                        </h3>

                        <!-- Upload Area -->
                        <div class="border-2 border-dashed border-green-200 rounded-2xl p-6 text-center bg-green-50/30 hover:bg-green-50 hover:border-green-400 transition-all duration-200 cursor-pointer"
                             onclick="document.getElementById('images').click()">
                            <div id="upload-placeholder">
                                <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-cloud-upload-alt text-green-500 text-2xl"></i>
                                </div>
                                <p class="text-sm font-semibold text-gray-700">Klik untuk upload foto</p>
                                <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF — max 2MB per file, hingga 10 foto</p>
                                <p class="text-xs text-green-600 mt-2 font-medium">
                                    <i class="fas fa-info-circle mr-1"></i> Foto pertama akan dijadikan foto utama
                                </p>
                            </div>

                            <input type="file" id="images" name="images[]" multiple accept="image/*"
                                   class="hidden"
                                   onchange="showImagePreview(this)">
                        </div>

                        <!-- Preview Area -->
                        <div id="image-preview" class="hidden mt-4">
                            <div class="flex items-center justify-between mb-3">
                                <p class="text-sm font-semibold text-gray-700">
                                    <i class="fas fa-images mr-1.5 text-green-500"></i>
                                    <span id="image-count"></span>
                                </p>
                                <button type="button" onclick="clearImages()"
                                        class="text-xs text-red-500 hover:text-red-700 font-medium transition-colors">
                                    <i class="fas fa-times mr-1"></i> Hapus semua
                                </button>
                            </div>
                            <div id="preview-container" class="grid grid-cols-2 sm:grid-cols-4 gap-3"></div>
                        </div>

                        @error('images')
                            <p class="text-xs text-red-600 mt-2 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                        @error('images.*')
                            <p class="text-xs text-red-600 mt-2 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <hr class="border-gray-100">

                    <!-- ===== SECTION 4: STATUS ===== -->
                    <div>
                        <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <span class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center text-green-700 text-xs font-bold">4</span>
                            Visibilitas
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Aktif Toggle -->
                            <label class="flex items-start gap-4 p-4 border-2 rounded-xl cursor-pointer hover:border-green-400 transition-all duration-200
                                {{ old('is_active', true) ? 'border-green-300 bg-green-50' : 'border-gray-200 bg-white' }}"
                                   id="active-label">
                                <input type="checkbox" name="is_active" value="1"
                                       @checked(old('is_active', true))
                                       class="mt-0.5 w-4 h-4 rounded border-gray-300 text-green-600 focus:ring-green-500"
                                       onchange="toggleLabelStyle('active-label', this)">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">Produk Aktif</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Produk akan tampil di katalog dan bisa dibeli pelanggan</p>
                                </div>
                            </label>

                            <!-- Featured Toggle -->
                            <label class="flex items-start gap-4 p-4 border-2 rounded-xl cursor-pointer hover:border-amber-400 transition-all duration-200
                                {{ old('is_featured') ? 'border-amber-300 bg-amber-50' : 'border-gray-200 bg-white' }}"
                                   id="featured-label">
                                <input type="checkbox" name="is_featured" value="1"
                                       @checked(old('is_featured'))
                                       class="mt-0.5 w-4 h-4 rounded border-gray-300 text-amber-500 focus:ring-amber-400"
                                       onchange="toggleLabelStyle('featured-label', this, 'amber')">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">
                                        <i class="fas fa-star text-amber-400 mr-1"></i> Produk Unggulan
                                    </p>
                                    <p class="text-xs text-gray-500 mt-0.5">Produk akan ditampilkan di seksi unggulan halaman utama</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- ===== SUBMIT BUTTONS ===== -->
                    <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-6 border-t border-gray-100">
                        <a href="{{ route('admin.products.index') }}"
                           class="w-full sm:w-auto text-center px-6 py-3 border-2 border-gray-200 rounded-xl text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 text-sm font-semibold">
                            <i class="fas fa-times mr-2"></i> Batal
                        </a>
                        <button type="submit"
                                class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-xl transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5 text-sm font-semibold shadow-md">
                            <i class="fas fa-save mr-2"></i> Simpan Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
function showImagePreview(input) {
    const previewArea = document.getElementById('image-preview');
    const previewContainer = document.getElementById('preview-container');
    const imageCount = document.getElementById('image-count');
    const placeholder = document.getElementById('upload-placeholder');

    if (input.files.length === 0) {
        previewArea.classList.add('hidden');
        placeholder.innerHTML = `
            <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-cloud-upload-alt text-green-500 text-2xl"></i>
            </div>
            <p class="text-sm font-semibold text-gray-700">Klik untuk upload foto</p>
            <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF — max 2MB per file, hingga 10 foto</p>
        `;
        return;
    }

    // Update placeholder to show selected
    placeholder.innerHTML = `
        <div class="w-14 h-14 bg-green-200 rounded-full flex items-center justify-center mx-auto mb-3">
            <i class="fas fa-check-circle text-green-600 text-2xl"></i>
        </div>
        <p class="text-sm font-semibold text-green-700">${input.files.length} foto terpilih</p>
        <p class="text-xs text-gray-500 mt-1">Klik untuk mengganti pilihan</p>
    `;

    previewArea.classList.remove('hidden');
    previewContainer.innerHTML = '';
    imageCount.textContent = `${input.files.length} foto terpilih`;

    Array.from(input.files).forEach((file, index) => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative group';
                div.innerHTML = `
                    <div class="relative overflow-hidden rounded-xl border-2 ${index === 0 ? 'border-green-400' : 'border-gray-200'}">
                        <img src="${e.target.result}"
                             class="w-full h-24 object-cover"
                             alt="Preview ${index + 1}">
                        ${index === 0 ? '<div class="absolute top-1.5 left-1.5 bg-green-600 text-white text-xs px-2 py-0.5 rounded-full font-semibold shadow">Utama</div>' : ''}
                        <div class="absolute bottom-1.5 right-1.5 bg-black/50 text-white text-xs px-1.5 py-0.5 rounded-full">${index + 1}</div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 truncate">${file.name}</p>
                `;
                previewContainer.appendChild(div);
            };
            reader.readAsDataURL(file);
        }
    });
}

function clearImages() {
    const input = document.getElementById('images');
    input.value = '';
    showImagePreview(input);
}

function toggleLabelStyle(labelId, checkbox, color = 'green') {
    const label = document.getElementById(labelId);
    if (checkbox.checked) {
        label.classList.remove('border-gray-200', 'bg-white');
        label.classList.add(`border-${color}-300`, `bg-${color}-50`);
    } else {
        label.classList.remove(`border-${color}-300`, `bg-${color}-50`);
        label.classList.add('border-gray-200', 'bg-white');
    }
}
</script>
@endpush