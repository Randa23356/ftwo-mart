@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Enhanced Header -->
            <div class="mb-6 sm:mb-8">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-12 h-12 bg-green-700 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-edit text-white text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                            Edit Produk
                        </h1>
                        <p class="text-sm sm:text-base text-gray-600">Perbarui produk {{ $product->name }}</p>
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
                                <span class="ml-1 text-xs sm:text-sm font-medium text-gray-700 sm:ml-2 bg-green-50 px-3 py-1 rounded-full">Edit Produk</span>
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
                            <p class="text-xs text-gray-600">Perbarui data produk dengan benar</p>
                        </div>
                    </div>
                </div>
                
                <!-- Form Content -->
                <div class="p-6 sm:p-8">
                    <form method="POST" action="{{ route('operator.products.update', $product) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

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
                                    <input type="text" name="name" value="{{ old('name', $product->name) }}" 
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
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
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
                                          placeholder="Jelaskan produk secara detail">{{ old('description', $product->description) }}</textarea>
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
                                    <input type="number" name="price" value="{{ old('price', $product->price) }}" 
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
                                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" 
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
                                    <input type="number" name="weight" value="{{ old('weight', $product->weight ?? 500) }}" min="1" max="50000" 
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

            <!-- Current Images -->
            @if($product->images->count() > 0 || $product->image)
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-medium text-gray-700">Gambar Saat Ini</label>
                        @if($product->images->count() > 0 || $product->image)
                            <button type="button" onclick="deleteAllImages()" 
                                    class="text-red-600 hover:text-red-700 text-sm font-medium">
                                <i class="fas fa-trash mr-1"></i>Hapus Semua
                            </button>
                        @endif
                    </div>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                        @if($product->images->count() > 0)
                            @foreach($product->images as $image)
                                <div class="relative group">
                                    <img src="{{ $image->image_url }}" alt="{{ $image->formatted_alt_text }}" 
                                         class="w-full h-24 object-cover rounded border">
                                    
                                    @if($image->is_primary)
                                        <div class="absolute top-1 left-1 bg-green-600 text-white text-xs px-1 py-0.5 rounded">
                                            Utama
                                        </div>
                                    @endif
                                    
                                    <div class="absolute bottom-1 right-1 bg-black bg-opacity-75 text-white text-xs px-1 py-0.5 rounded">
                                        #{{ $image->sort_order + 1 }}
                                    </div>
                                    
                                    <!-- Delete button (appears on hover) -->
                                    <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded flex items-center justify-center">
                                        <button type="button" onclick="deleteImage({{ $image->id }})" 
                                                class="bg-red-600 text-white p-2 rounded-full hover:bg-red-700 transition-colors"
                                                title="Hapus gambar ini">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @elseif($product->image)
                            <div class="relative group">
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" 
                                     class="w-full h-24 object-cover rounded border">
                                <div class="absolute top-1 left-1 bg-blue-500 text-white text-xs px-1 py-0.5 rounded">
                                    Legacy
                                </div>
                                
                                <!-- Delete button for legacy image -->
                                <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded flex items-center justify-center">
                                    <button type="button" onclick="deleteLegacyImage()" 
                                            class="bg-red-600 text-white p-2 rounded-full hover:bg-red-700 transition-colors"
                                            title="Hapus gambar legacy">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <p class="text-sm text-gray-600 mb-4">
                        <i class="fas fa-info-circle mr-1"></i>
                        Hover pada gambar untuk menampilkan tombol hapus. Untuk pengelolaan lanjutan, gunakan panel admin.
                    </p>
                </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700">Tambah Gambar Baru</label>
                <div class="mt-1 space-y-4">
                    <!-- Multiple images upload -->
                    <div>
                        <label class="block text-sm text-gray-600 mb-2">Upload Multiple Gambar (Rekomendasi)</label>
                        <input type="file" name="images[]" multiple accept="image/*" 
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100"
                               onchange="showSelectedFiles(this)">
                        <p class="text-xs text-gray-500 mt-1">Pilih beberapa gambar sekaligus (maksimal 10 gambar, masing-masing max 2MB)</p>
                        <div id="selected-files" class="mt-2 text-sm text-gray-600"></div>
                    </div>
                    
                    <!-- Separator -->
                    <div class="flex items-center">
                        <div class="flex-1 border-t border-gray-300"></div>
                        <span class="px-3 text-sm text-gray-500">atau</span>
                        <div class="flex-1 border-t border-gray-300"></div>
                    </div>
                    
                    <!-- Single image upload (legacy support) -->
                    <div>
                        <label class="block text-sm text-gray-600 mb-2">Upload Satu Gambar</label>
                        <input type="file" name="image" accept="image/*" 
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="text-xs text-gray-500 mt-1">Pilih satu gambar saja (max 2MB)</p>
                    </div>
                </div>
                
                @error('image')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
                @error('images')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
                @error('images.*')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <script>
            function showSelectedFiles(input) {
                const fileList = document.getElementById('selected-files');
                if (input.files.length === 0) {
                    fileList.innerHTML = '';
                    return;
                }
                
                let html = '<strong>File terpilih (' + input.files.length + '):</strong><ul class="list-disc list-inside mt-1">';
                for (let i = 0; i < input.files.length; i++) {
                    const file = input.files[i];
                    const sizeKB = Math.round(file.size / 1024);
                    html += '<li>' + file.name + ' (' + sizeKB + ' KB)</li>';
                }
                html += '</ul>';
                fileList.innerHTML = html;
            }

            let currentDeleteAction = null;

            function deleteImage(imageId) {
                showDeleteModal('Apakah Anda yakin ingin menghapus gambar ini?', () => {
                    executeDeleteImage(imageId);
                });
            }

            function deleteAllImages() {
                showDeleteModal('Apakah Anda yakin ingin menghapus SEMUA gambar? Tindakan ini tidak dapat dibatalkan.', () => {
                    executeDeleteAllImages();
                });
            }

            function deleteLegacyImage() {
                showDeleteModal('Apakah Anda yakin ingin menghapus gambar legacy ini?', () => {
                    executeDeleteAllImages();
                });
            }

            function showDeleteModal(message, action) {
                document.getElementById('deleteMessage').textContent = message;
                currentDeleteAction = action;
                document.getElementById('deleteModal').classList.remove('hidden');
                document.getElementById('deleteModal').classList.add('flex');
            }

            function closeDeleteModal() {
                currentDeleteAction = null;
                document.getElementById('deleteModal').classList.add('hidden');
                document.getElementById('deleteModal').classList.remove('flex');
            }

            document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
                if (currentDeleteAction) {
                    currentDeleteAction();
                }
                closeDeleteModal();
            });

            function executeDeleteImage(imageId) {
                fetch(`{{ route('operator.products.delete-image', $product) }}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        image_id: imageId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification(data.message, 'success');
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        showNotification(data.message || 'Terjadi kesalahan', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Terjadi kesalahan saat menghapus gambar', 'error');
                });
            }

            function executeDeleteAllImages() {
                fetch(`{{ route('operator.products.delete-all-images', $product) }}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification(data.message, 'success');
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        showNotification(data.message || 'Terjadi kesalahan', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Terjadi kesalahan saat menghapus gambar', 'error');
                });
            }

            function showNotification(message, type) {
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 p-4 rounded-lg text-white z-50 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
                notification.textContent = message;
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.remove();
                }, 3000);
            }
            </script>

            <div class="flex items-center justify-end space-x-3">
                <a href="{{ route('operator.products.index') }}" class="px-4 py-2 rounded-md border border-gray-300 text-gray-700">Batal</a>
                <button type="submit" class="px-4 py-2 rounded-md bg-green-700 hover:bg-green-800 text-white">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-sm mx-4">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Konfirmasi Hapus</h3>
        <p id="deleteMessage" class="text-sm text-gray-600 mb-6"></p>
        <div class="flex justify-end space-x-3">
            <button onclick="closeDeleteModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">Batal</button>
            <button id="confirmDeleteBtn" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Hapus</button>
        </div>
    </div>
</div>


