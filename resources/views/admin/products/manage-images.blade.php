@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="fixed inset-0 opacity-20 pointer-events-none">
        <div class="absolute inset-0" style="background-image: url(\"data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2316a34a' fill-opacity='0.06'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E\");"></div>
    </div>

    <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

        <!-- ===== PAGE HEADER ===== -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-6 sm:p-8 mb-6 sm:mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-br from-green-600 to-emerald-700 p-3 sm:p-4 rounded-2xl shadow-lg flex-shrink-0">
                        <i class="fas fa-images text-white text-xl sm:text-2xl"></i>
                    </div>
                    <div class="min-w-0">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Kelola Gambar Produk</h1>
                        <p class="text-sm sm:text-base text-gray-500 mt-0.5 truncate">{{ $product->name }}</p>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    <nav class="flex items-center space-x-2 text-sm text-gray-500">
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-green-600 transition-colors font-medium">
                            <i class="fas fa-home mr-1"></i> Admin
                        </a>
                        <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
                        <a href="{{ route('admin.products.index') }}" class="hover:text-green-600 transition-colors font-medium">Produk</a>
                        <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
                        <a href="{{ route('admin.products.edit', $product) }}" class="hover:text-green-600 transition-colors font-medium">Edit</a>
                        <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
                        <span class="text-green-600 font-semibold">Gambar</span>
                    </nav>
                    <a href="{{ route('admin.products.edit', $product) }}"
                       class="inline-flex items-center justify-center px-5 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-xl transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5 text-sm font-semibold shadow-md">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Edit
                    </a>
                </div>
            </div>
        </div>

        @if($product->images->count() > 0)
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden" x-data="imageManager()" x-init="init()">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-green-100">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-600 to-emerald-700 rounded-xl flex items-center justify-center shadow-md">
                                <i class="fas fa-th text-white text-sm"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">Galeri Produk</h2>
                                <p class="text-xs text-gray-500">{{ $product->images->count() }} gambar terupload</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-white/80 border border-green-200 text-xs font-semibold text-green-700">
                            <i class="fas fa-arrows-alt mr-1.5"></i> Drag & drop untuk ubah urutan
                        </span>
                    </div>
                </div>

                <div class="p-6 sm:p-8">
                    <div id="sortable-images" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                        @foreach($product->images->sortBy('sort_order') as $image)
                            <div class="image-item bg-gray-50/80 rounded-2xl p-4 border border-gray-100 cursor-move hover:border-green-200 hover:shadow-md transition-all duration-200"
                                 data-id="{{ $image->id }}">
                                <div class="relative group">
                                    <img src="{{ $image->image_url }}" alt="{{ $image->formatted_alt_text }}"
                                         class="w-full h-44 sm:h-48 object-cover rounded-xl border-2 {{ $image->is_primary ? 'border-green-400' : 'border-gray-100' }}">

                                    @if($image->is_primary)
                                        <div class="absolute top-2 left-2 bg-green-600 text-white text-xs px-2.5 py-1 rounded-full font-semibold shadow flex items-center">
                                            <i class="fas fa-star mr-1 text-[10px]"></i> Utama
                                        </div>
                                    @endif

                                    <div class="absolute top-2 right-2 bg-gray-900/70 text-white text-xs px-2 py-1 rounded-lg font-mono font-semibold sort-order-badge">
                                        #{{ $image->sort_order + 1 }}
                                    </div>

                                    <div class="absolute inset-0 bg-black/45 opacity-0 group-hover:opacity-100 transition-opacity rounded-xl flex items-center justify-center gap-3">
                                        @if(!$image->is_primary)
                                            <button type="button" onclick="setPrimaryImage({{ $image->id }})"
                                                    class="bg-green-600 text-white p-3 rounded-full hover:bg-green-700 transition-colors shadow-lg"
                                                    title="Set sebagai gambar utama">
                                                <i class="fas fa-star"></i>
                                            </button>
                                        @endif
                                        <button type="button" onclick="deleteImage({{ $image->id }})"
                                                class="bg-red-600 text-white p-3 rounded-full hover:bg-red-700 transition-colors shadow-lg"
                                                title="Hapus gambar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="mt-4 space-y-2">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 mb-1">Alt Text</label>
                                        <input type="text" value="{{ $image->alt_text }}"
                                               onchange="updateAltText({{ $image->id }}, this.value)"
                                               class="w-full text-sm px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white"
                                               placeholder="Deskripsi gambar">
                                    </div>
                                    <div class="flex items-center justify-between text-xs text-gray-400 font-medium">
                                        <span>ID: {{ $image->id }}</span>
                                        <span class="sort-order-label">Urutan: {{ $image->sort_order + 1 }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-2xl">
                        <h3 class="text-sm font-bold text-green-900 mb-2 flex items-center gap-2">
                            <i class="fas fa-lightbulb text-green-600"></i> Petunjuk
                        </h3>
                        <ul class="text-sm text-green-800 space-y-1.5">
                            <li class="flex items-start gap-2"><i class="fas fa-arrows-alt mt-0.5 text-green-600 w-4"></i> Drag & drop gambar untuk mengubah urutan</li>
                            <li class="flex items-start gap-2"><i class="fas fa-star mt-0.5 text-green-600 w-4"></i> Klik ikon bintang untuk set gambar utama</li>
                            <li class="flex items-start gap-2"><i class="fas fa-trash mt-0.5 text-green-600 w-4"></i> Klik ikon sampah untuk menghapus gambar</li>
                            <li class="flex items-start gap-2"><i class="fas fa-edit mt-0.5 text-green-600 w-4"></i> Edit alt text langsung di kolom input</li>
                        </ul>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-12 sm:p-16 text-center">
                <div class="w-20 h-20 bg-gradient-to-br from-green-50 to-emerald-100 rounded-full flex items-center justify-center mx-auto mb-5 shadow-inner">
                    <i class="fas fa-images text-green-500 text-4xl"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Gambar</h2>
                <p class="text-sm text-gray-500 mb-6 max-w-md mx-auto">Produk ini belum memiliki gambar. Tambahkan gambar dari halaman edit produk.</p>
                <a href="{{ route('admin.products.edit', $product) }}"
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-xl transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5 text-sm font-semibold shadow-md">
                    <i class="fas fa-plus mr-2"></i> Tambah Gambar
                </a>
            </div>
        @endif

    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl p-6 max-w-sm w-full shadow-2xl border border-gray-100">
        <div class="w-14 h-14 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-red-100">
            <i class="fas fa-trash text-red-600 text-xl"></i>
        </div>
        <h3 class="text-lg font-bold text-gray-900 text-center mb-2">Hapus Gambar?</h3>
        <p class="text-sm text-gray-600 text-center mb-6">Tindakan ini tidak dapat dibatalkan.</p>
        <div class="flex gap-3">
            <button type="button" onclick="closeDeleteModal()"
                    class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-semibold text-sm">
                Batal
            </button>
            <button type="button" id="confirmDelete"
                    class="flex-1 px-4 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors font-semibold text-sm">
                Hapus
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
function imageManager() {
    return {
        init() {
            const sortableElement = document.getElementById('sortable-images');
            if (sortableElement) {
                Sortable.create(sortableElement, {
                    animation: 150,
                    ghostClass: 'opacity-50',
                    chosenClass: 'ring-2 ring-green-400 ring-offset-2 rounded-2xl',
                    onEnd: function() {
                        updateImageOrder();
                    }
                });
            }
        }
    };
}

function updateImageOrder() {
    const imageItems = document.querySelectorAll('.image-item');
    const imageOrders = Array.from(imageItems).map(item => item.dataset.id);

    fetch(`{{ route('admin.products.update-image-order', $product) }}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ image_orders: imageOrders })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            imageItems.forEach((item, index) => {
                const orderBadge = item.querySelector('.sort-order-badge');
                const orderLabel = item.querySelector('.sort-order-label');
                if (orderBadge) orderBadge.textContent = `#${index + 1}`;
                if (orderLabel) orderLabel.textContent = `Urutan: ${index + 1}`;
            });
            notify.success(data.message);
        } else {
            notify.error(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        notify.error('Terjadi kesalahan saat mengupdate urutan gambar');
    });
}

function setPrimaryImage(imageId) {
    fetch(`{{ route('admin.products.set-primary-image', $product) }}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ image_id: imageId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            notify.error(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        notify.error('Terjadi kesalahan saat mengset gambar utama');
    });
}

let imageToDelete = null;

function deleteImage(imageId) {
    imageToDelete = imageId;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
}

function closeDeleteModal() {
    imageToDelete = null;
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteModal').classList.remove('flex');
}

document.getElementById('confirmDelete').addEventListener('click', function() {
    if (!imageToDelete) return;

    fetch(`{{ route('admin.products.delete-image', $product) }}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ image_id: imageToDelete })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            notify.error(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        notify.error('Terjadi kesalahan saat menghapus gambar');
    });

    closeDeleteModal();
});

function updateAltText(imageId, altText) {
    console.log('Update alt text for image', imageId, 'to', altText);
}

const notify = {
    success: function(message) { this.show(message, 'success'); },
    error: function(message) { this.show(message, 'error'); },
    show: function(message, type) {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-5 py-3 rounded-xl text-white text-sm font-semibold shadow-lg z-[9999] flex items-center gap-2 ${type === 'success' ? 'bg-green-600' : 'bg-red-600'}`;
        notification.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> ${message}`;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }
};
</script>
@endpush
