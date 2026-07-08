@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="fixed inset-0 opacity-20 pointer-events-none">
        <div class="absolute inset-0" style="background-image: url(\"data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2316a34a' fill-opacity='0.06'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E\");"></div>
    </div>

    <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

        <!-- ===== PAGE HEADER ===== -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-6 sm:p-8 mb-6 sm:mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-br from-green-600 to-emerald-700 p-3 sm:p-4 rounded-2xl shadow-lg flex-shrink-0">
                        <i class="fas fa-edit text-white text-xl sm:text-2xl"></i>
                    </div>
                    <div class="min-w-0">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 truncate">Edit Produk</h1>
                        <p class="text-sm sm:text-base text-gray-500 mt-0.5 truncate">{{ $product->name }}</p>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                    <nav class="flex items-center space-x-2 text-sm text-gray-500 order-2 sm:order-1">
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-green-600 transition-colors font-medium">
                            <i class="fas fa-home mr-1"></i> Admin
                        </a>
                        <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
                        <a href="{{ route('admin.products.index') }}" class="hover:text-green-600 transition-colors font-medium">Produk</a>
                        <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
                        <span class="text-green-600 font-semibold">Edit</span>
                    </nav>
                    <a href="{{ route('admin.products.manage-images', $product) }}"
                       class="order-1 sm:order-2 inline-flex items-center justify-center px-4 py-2.5 bg-green-50 text-green-700 border border-green-200 rounded-xl hover:bg-green-100 transition-all text-sm font-semibold">
                        <i class="fas fa-images mr-2"></i> Kelola Gambar
                    </a>
                </div>
            </div>
        </div>

        <!-- ===== FORM CARD ===== -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-green-100">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 bg-gradient-to-br from-green-600 to-emerald-700 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-tshirt text-white text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Informasi Produk</h2>
                        <p class="text-xs text-gray-500">Edit data produk #{{ $product->id }}</p>
                    </div>
                </div>
            </div>

            <div class="p-6 sm:p-8">
                <form method="POST"
                      action="{{ route('admin.products.update', $product) }}"
                      enctype="multipart/form-data"
                      class="space-y-8"
                      x-data="productForm()">
                    @csrf
                    @method('PUT')

                    @php $inp = 'w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm bg-gray-50 focus:bg-white'; @endphp

                    <!-- SECTION 1 -->
                    <div>
                        <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <span class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center text-green-700 text-xs font-bold">1</span>
                            Informasi Dasar
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nama Produk <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" value="{{ old('name', $product->name) }}"
                                       placeholder="Contoh: Batik Motif Parang"
                                       class="{{ $inp }}">
                                @error('name')
                                    <p class="text-xs text-red-600 mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Kategori <span class="text-red-500">*</span>
                                </label>
                                <select name="category_id" class="{{ $inp }}">
                                    <option value="">— Pilih Kategori —</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="text-xs text-red-600 mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-5">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Produk</label>
                            <textarea name="description" rows="4"
                                      placeholder="Jelaskan detail produk..."
                                      class="{{ $inp }} resize-none">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <p class="text-xs text-red-600 mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    <!-- SECTION 2 -->
                    <div>
                        <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <span class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center text-green-700 text-xs font-bold">2</span>
                            Harga & Stok
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Harga <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm font-semibold text-gray-500">Rp</span>
                                    <input type="number" name="price" value="{{ old('price', $product->price) }}" min="0" step="1000"
                                           class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm bg-gray-50 focus:bg-white">
                                </div>
                                @error('price')
                                    <p class="text-xs text-red-600 mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Stok <span class="text-red-500">*</span></label>
                                <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0" class="{{ $inp }}">
                                @error('stock')
                                    <p class="text-xs text-red-600 mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Berat (gram)</label>
                                <div class="relative">
                                    <input type="number" name="weight" value="{{ old('weight', $product->weight) }}" min="1" max="50000" class="w-full px-4 py-3 pr-14 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm bg-gray-50 focus:bg-white">
                                    <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-xs text-gray-400 font-medium">gram</span>
                                </div>
                                <p class="text-xs text-gray-400 mt-1.5 flex items-center gap-1"><i class="fas fa-info-circle text-green-400"></i> Untuk perhitungan ongkir</p>
                                @error('weight')
                                    <p class="text-xs text-red-600 mt-1 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    <!-- SECTION 3: IMAGES -->
                    <div>
                        <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <span class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center text-green-700 text-xs font-bold">3</span>
                            Foto Produk
                        </h3>

                        @if($product->images->count() > 0)
                            <div class="mb-5 p-4 bg-gray-50 rounded-xl border border-gray-100">
                                <div class="flex items-center justify-between mb-3">
                                    <p class="text-sm font-semibold text-gray-700">
                                        <i class="fas fa-images mr-1.5 text-green-500"></i>
                                        Gambar Saat Ini ({{ $product->images->count() }})
                                    </p>
                                    <a href="{{ route('admin.products.manage-images', $product) }}"
                                       class="text-xs font-semibold text-green-600 hover:text-green-700">
                                        Kelola <i class="fas fa-arrow-right ml-0.5"></i>
                                    </a>
                                </div>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                    @foreach($product->images as $image)
                                        <div class="relative group">
                                            <div class="relative overflow-hidden rounded-xl border-2 {{ $image->is_primary ? 'border-green-400' : 'border-gray-200' }}">
                                                <img src="{{ $image->image_url }}" alt="{{ $image->formatted_alt_text }}"
                                                     class="w-full h-24 object-cover">
                                                @if($image->is_primary)
                                                    <div class="absolute top-1.5 left-1.5 bg-green-600 text-white text-xs px-2 py-0.5 rounded-full font-semibold shadow">Utama</div>
                                                @endif
                                            </div>
                                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity rounded-xl flex items-center justify-center">
                                                <a href="{{ route('admin.products.manage-images', $product) }}"
                                                   class="bg-white text-green-700 p-2 rounded-full shadow hover:bg-green-50 transition-colors">
                                                    <i class="fas fa-edit text-xs"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <p class="text-xs text-gray-500 mt-3 flex items-start gap-1.5">
                                    <i class="fas fa-info-circle text-green-500 mt-0.5"></i>
                                    Untuk hapus, urutan, atau ubah foto utama gunakan tombol <strong>Kelola Gambar</strong>.
                                </p>
                            </div>
                        @endif

                        <p class="text-sm font-semibold text-gray-700 mb-3">Tambah Gambar Baru</p>
                        <div class="border-2 border-dashed border-green-200 rounded-2xl p-6 text-center bg-green-50/30 hover:bg-green-50 hover:border-green-400 transition-all duration-200">
                            <div class="text-center">
                                <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-cloud-upload-alt text-green-500 text-2xl"></i>
                                </div>
                                <label for="images" class="cursor-pointer text-sm font-semibold text-green-700 hover:text-green-800">
                                    Klik untuk upload gambar baru
                                </label>
                                <span class="text-sm text-gray-500"> atau drag & drop</span>
                                <input type="file" id="images" name="images[]" multiple accept="image/*"
                                       @change="handleImageUpload($event)" class="hidden">
                                <p class="text-xs text-gray-500 mt-2">PNG, JPG, GIF — max 2MB. Maks. 10 gambar total.</p>
                            </div>
                        </div>

                        <div x-show="images.length > 0" x-cloak class="mt-4">
                            <p class="text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-images mr-1.5 text-green-500"></i>
                                Preview Gambar Baru (<span x-text="images.length"></span>)
                            </p>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                <template x-for="(image, index) in images" :key="index">
                                    <div class="relative group">
                                        <div class="relative overflow-hidden rounded-xl border-2"
                                             :class="index === primaryImageIndex ? 'border-green-400' : 'border-gray-200'">
                                            <img :src="image.url" :alt="'Preview ' + (index + 1)" class="w-full h-24 object-cover">
                                            <div x-show="index === primaryImageIndex"
                                                 class="absolute top-1.5 left-1.5 bg-green-600 text-white text-xs px-2 py-0.5 rounded-full font-semibold shadow">
                                                Utama Baru
                                            </div>
                                        </div>
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity rounded-xl flex items-center justify-center gap-2">
                                            <button type="button" @click="setPrimaryImage(index)"
                                                    class="text-white p-2 rounded-full shadow transition-colors"
                                                    :class="index === primaryImageIndex ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-600 hover:bg-gray-700'">
                                                <i class="fas fa-star text-xs"></i>
                                            </button>
                                            <button type="button" @click="removeImage(index)"
                                                    class="bg-red-600 text-white p-2 rounded-full hover:bg-red-700 transition-colors shadow">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </div>
                                        <input type="text" :name="'image_alt_texts[' + index + ']'" x-model="image.altText"
                                               placeholder="Deskripsi (opsional)"
                                               class="w-full mt-2 text-xs px-2 py-1.5 border border-gray-200 rounded-lg focus:ring-green-500 focus:border-green-500">
                                    </div>
                                </template>
                            </div>
                            <input type="hidden" name="primary_image_index" :value="primaryImageIndex">
                        </div>

                        @error('images')
                            <p class="text-xs text-red-600 mt-2 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                        @enderror
                        @error('images.*')
                            <p class="text-xs text-red-600 mt-2 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <hr class="border-gray-100">

                    <!-- SECTION 4 -->
                    <div>
                        <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <span class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center text-green-700 text-xs font-bold">4</span>
                            Visibilitas
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="flex items-start gap-4 p-4 border-2 rounded-xl cursor-pointer hover:border-green-400 transition-all duration-200
                                {{ old('is_active', $product->is_active) ? 'border-green-300 bg-green-50' : 'border-gray-200 bg-white' }}"
                                   id="active-label">
                                <input type="checkbox" name="is_active" value="1"
                                       @checked(old('is_active', $product->is_active))
                                       class="mt-0.5 w-4 h-4 rounded border-gray-300 text-green-600 focus:ring-green-500"
                                       onchange="toggleLabelStyle('active-label', this)">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">Produk Aktif</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Tampil di katalog dan bisa dibeli</p>
                                </div>
                            </label>
                            <label class="flex items-start gap-4 p-4 border-2 rounded-xl cursor-pointer hover:border-amber-400 transition-all duration-200
                                {{ old('is_featured', $product->is_featured) ? 'border-amber-300 bg-amber-50' : 'border-gray-200 bg-white' }}"
                                   id="featured-label">
                                <input type="checkbox" name="is_featured" value="1"
                                       @checked(old('is_featured', $product->is_featured))
                                       class="mt-0.5 w-4 h-4 rounded border-gray-300 text-amber-500 focus:ring-amber-400"
                                       onchange="toggleLabelStyle('featured-label', this, 'amber')">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">
                                        <i class="fas fa-star text-amber-400 mr-1"></i> Produk Unggulan
                                    </p>
                                    <p class="text-xs text-gray-500 mt-0.5">Tampil di seksi unggulan</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- SUBMIT -->
                    <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-6 border-t border-gray-100">
                        <a href="{{ route('admin.products.index') }}"
                           class="w-full sm:w-auto text-center px-6 py-3 border-2 border-gray-200 rounded-xl text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 text-sm font-semibold">
                            <i class="fas fa-times mr-2"></i> Batal
                        </a>
                        <button type="submit"
                                class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-xl transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5 text-sm font-semibold shadow-md">
                            <i class="fas fa-save mr-2"></i> Update Produk
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
function productForm() {
    return {
        images: [],
        primaryImageIndex: null,

        handleImageUpload(event) {
            const files = Array.from(event.target.files);
            files.forEach((file) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.images.push({ file, url: e.target.result, altText: '' });
                        if (this.primaryImageIndex === null && this.images.length === 1) {
                            this.primaryImageIndex = 0;
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });
        },

        removeImage(index) {
            this.images.splice(index, 1);
            if (this.primaryImageIndex >= this.images.length) {
                this.primaryImageIndex = Math.max(0, this.images.length - 1);
            }
            if (this.images.length === 0) {
                this.primaryImageIndex = null;
            }
        },

        setPrimaryImage(index) {
            this.primaryImageIndex = index;
        }
    };
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
