@php
    $isEdit = isset($category);
    $inp = 'w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm bg-gray-50 focus:bg-white';
@endphp

<form action="{{ $isEdit ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
      method="POST"
      enctype="multipart/form-data"
      class="space-y-8">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <!-- Informasi Dasar -->
    <div>
        <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-4 flex items-center gap-2">
            <span class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center text-green-700 text-xs font-bold">1</span>
            Informasi Dasar
        </h3>
        <div class="space-y-5">
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                    Nama Kategori <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="name"
                       id="name"
                       value="{{ old('name', $category->name ?? '') }}"
                       class="{{ $inp }} @error('name') border-red-400 ring-red-200 @enderror"
                       placeholder="Contoh: Batik Tulis"
                       required>
                @error('name')
                    <p class="text-xs text-red-600 mt-1.5 flex items-center gap-1">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                <textarea name="description"
                          id="description"
                          rows="4"
                          class="{{ $inp }} resize-none @error('description') border-red-400 ring-red-200 @enderror"
                          placeholder="Deskripsi kategori (opsional)">{{ old('description', $category->description ?? '') }}</textarea>
                @error('description')
                    <p class="text-xs text-red-600 mt-1.5 flex items-center gap-1">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Gambar -->
    <div>
        <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-4 flex items-center gap-2">
            <span class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center text-green-700 text-xs font-bold">2</span>
            Gambar Kategori
        </h3>

        @if($isEdit && $category->image)
            <div class="mb-4 p-4 bg-gray-50 rounded-xl border border-gray-100">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Gambar Saat Ini</p>
                <div class="flex items-center gap-4">
                    <img src="{{ $category->image_url }}"
                         alt="{{ $category->name }}"
                         class="h-24 w-24 object-cover rounded-xl border-2 border-green-100 shadow-sm">
                    <p class="text-sm text-gray-600">Upload gambar baru di bawah untuk mengganti.</p>
                </div>
            </div>
        @endif

        <label for="image"
               class="flex flex-col items-center justify-center px-6 py-8 border-2 border-dashed border-gray-200 rounded-2xl cursor-pointer hover:border-green-400 hover:bg-green-50/30 transition-all duration-200 group">
            <div id="upload-placeholder" class="text-center">
                <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-105 transition-transform">
                    <i class="fas fa-cloud-upload-alt text-green-500 text-2xl"></i>
                </div>
                <p class="text-sm font-semibold text-gray-700">
                    {{ $isEdit && $category->image ? 'Klik untuk ganti gambar' : 'Klik untuk upload gambar' }}
                </p>
                <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF — maks. 2MB</p>
            </div>
            <input id="image" name="image" type="file" class="sr-only" accept="image/*" onchange="previewCategoryImage(event)">
        </label>

        <div id="imagePreview" class="mt-4 hidden">
            <p class="text-xs font-semibold text-gray-500 mb-2">Preview</p>
            <img id="preview" class="h-32 w-32 object-cover rounded-xl border-2 border-green-200 shadow-sm" alt="Preview">
        </div>

        @error('image')
            <p class="text-xs text-red-600 mt-2 flex items-center gap-1">
                <i class="fas fa-exclamation-circle"></i> {{ $message }}
            </p>
        @enderror
    </div>

    <!-- Status -->
    <div>
        <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-4 flex items-center gap-2">
            <span class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center text-green-700 text-xs font-bold">3</span>
            Status
        </h3>
        <label for="is_active"
               id="is_active_label"
               class="flex items-center gap-4 p-4 rounded-xl border-2 border-green-200 bg-green-50 cursor-pointer transition-all duration-200 hover:border-green-300">
            <input type="checkbox"
                   name="is_active"
                   id="is_active"
                   value="1"
                   class="h-5 w-5 text-green-600 border-gray-300 rounded focus:ring-green-500"
                   {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}
                   onchange="toggleActiveLabel(this)">
            <div>
                <span class="text-sm font-bold text-gray-900 block">Aktifkan Kategori</span>
                <span class="text-xs text-gray-500">Kategori akan ditampilkan di website</span>
            </div>
        </label>
    </div>

    <!-- Actions -->
    <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3 pt-6 border-t border-gray-100">
        <a href="{{ route('admin.categories.index') }}"
           class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
        <button type="submit"
                class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-xl transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5 text-sm font-semibold shadow-md">
            <i class="fas fa-save mr-2"></i>
            {{ $isEdit ? 'Update Kategori' : 'Simpan Kategori' }}
        </button>
    </div>
</form>
