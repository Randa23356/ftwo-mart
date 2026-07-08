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
                        <i class="fas fa-tshirt text-white text-xl sm:text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Kelola Produk</h1>
                        <p class="text-sm sm:text-base text-gray-500 mt-0.5">Manajemen produk Batik Sasambo</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.products.trash') }}"
                       class="inline-flex items-center px-4 py-2.5 bg-red-50 text-red-700 border border-red-200 rounded-xl hover:bg-red-100 transition-all duration-200 text-sm font-medium">
                        <i class="fas fa-trash mr-2"></i>
                        <span class="hidden sm:inline">Trash</span>
                    </a>
                    <a href="{{ route('admin.products.create') }}"
                       class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-xl transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5 text-sm font-semibold shadow-md">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Produk
                    </a>
                </div>
            </div>
        </div>

        <!-- ===== STATS CARDS ===== -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-6 sm:mb-8">
            <!-- Total Produk -->
            <div class="group relative bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 p-5 sm:p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-green-400/15 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-2.5 rounded-xl shadow-md group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-box text-white text-base"></i>
                        </div>
                        <div class="text-2xl sm:text-3xl font-bold text-green-600">{{ $products->total() }}</div>
                    </div>
                    <div class="text-xs sm:text-sm font-semibold text-gray-600">Total Produk</div>
                    <div class="text-xs text-gray-400 mt-0.5">Semua produk</div>
                </div>
            </div>

            <!-- Produk Aktif -->
            <div class="group relative bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 p-5 sm:p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-emerald-400/15 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <div class="bg-gradient-to-br from-emerald-500 to-teal-600 p-2.5 rounded-xl shadow-md group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-check-circle text-white text-base"></i>
                        </div>
                        <div class="text-2xl sm:text-3xl font-bold text-emerald-600">{{ $products->where('is_active', true)->count() }}</div>
                    </div>
                    <div class="text-xs sm:text-sm font-semibold text-gray-600">Produk Aktif</div>
                    <div class="text-xs text-gray-400 mt-0.5">Tampil di katalog</div>
                </div>
            </div>

            <!-- Featured -->
            <div class="group relative bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 p-5 sm:p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-amber-400/15 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <div class="bg-gradient-to-br from-amber-500 to-orange-500 p-2.5 rounded-xl shadow-md group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-star text-white text-base"></i>
                        </div>
                        <div class="text-2xl sm:text-3xl font-bold text-amber-600">{{ $products->where('is_featured', true)->count() }}</div>
                    </div>
                    <div class="text-xs sm:text-sm font-semibold text-gray-600">Featured</div>
                    <div class="text-xs text-gray-400 mt-0.5">Produk unggulan</div>
                </div>
            </div>

            <!-- Stok Rendah -->
            <div class="group relative bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 p-5 sm:p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-red-400/15 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <div class="bg-gradient-to-br from-red-500 to-rose-600 p-2.5 rounded-xl shadow-md group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-exclamation-triangle text-white text-base"></i>
                        </div>
                        <div class="text-2xl sm:text-3xl font-bold text-red-600">{{ $products->where('stock', '<=', 5)->count() }}</div>
                    </div>
                    <div class="text-xs sm:text-sm font-semibold text-gray-600">Stok Rendah</div>
                    <div class="text-xs text-gray-400 mt-0.5">Perlu restock</div>
                </div>
            </div>
        </div>

        <!-- ===== PRODUCTS TABLE ===== -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <!-- Table Header -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-green-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-600 to-emerald-700 rounded-xl flex items-center justify-center shadow-md">
                            <i class="fas fa-list text-white text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Daftar Produk</h2>
                            <p class="text-xs text-gray-500">{{ $products->total() }} produk tersedia</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-green-100 text-green-700 font-medium">
                            <i class="fas fa-circle text-green-500 mr-1" style="font-size:6px"></i> Aktif
                        </span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-red-100 text-red-700 font-medium">
                            <i class="fas fa-circle text-red-400 mr-1" style="font-size:6px"></i> Nonaktif
                        </span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-amber-100 text-amber-700 font-medium">
                            <i class="fas fa-star mr-1 text-amber-500" style="font-size:9px"></i> Featured
                        </span>
                    </div>
                </div>
            </div>

            <!-- Responsive Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Produk</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden sm:table-cell">Kategori</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden md:table-cell">Harga</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden lg:table-cell">Stok</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($products as $product)
                            <tr class="hover:bg-green-50/40 transition-colors duration-150 group">
                                <!-- Product Name + Thumbnail -->
                                <td class="px-5 py-4">
                                    <div class="flex items-center space-x-3">
                                        @if($product->image || $product->images->isNotEmpty())
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                                 class="w-12 h-12 rounded-xl object-cover border-2 border-gray-100 shadow-sm group-hover:border-green-200 transition-colors flex-shrink-0">
                                        @else
                                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 border-2 border-gray-100 flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-image text-gray-400 text-sm"></i>
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-gray-900 truncate text-sm">{{ $product->name }}</p>
                                            <p class="text-xs text-gray-400 mt-0.5 line-clamp-1">{{ Str::limit($product->description, 60) }}</p>
                                            @if($product->trashed())
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700 mt-1">
                                                    <i class="fas fa-trash text-xs mr-1"></i> Dihapus
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Category -->
                                <td class="px-5 py-4 hidden sm:table-cell">
                                    <span class="inline-flex items-center px-3 py-1.5 bg-green-50 text-green-700 border border-green-100 rounded-lg text-xs font-semibold">
                                        <i class="fas fa-tag mr-1.5 text-green-500" style="font-size:9px"></i>
                                        {{ $product->category->name ?? '—' }}
                                    </span>
                                </td>

                                <!-- Price -->
                                <td class="px-5 py-4 hidden md:table-cell">
                                    <div class="font-bold text-green-700 text-sm">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                </td>

                                <!-- Stock -->
                                <td class="px-5 py-4 hidden lg:table-cell">
                                    @php
                                        $stockClass = $product->stock > 10 ? 'text-green-600 bg-green-50 border-green-200' : ($product->stock > 0 ? 'text-amber-600 bg-amber-50 border-amber-200' : 'text-red-600 bg-red-50 border-red-200');
                                        $stockLabel = $product->stock > 10 ? 'Tersedia' : ($product->stock > 0 ? 'Terbatas' : 'Habis');
                                    @endphp
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg border text-xs font-semibold {{ $stockClass }}">
                                        <span class="font-bold text-sm">{{ $product->stock }}</span>
                                        <span class="font-normal opacity-70">· {{ $stockLabel }}</span>
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="px-5 py-4">
                                    <div class="flex flex-col gap-1.5">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                            {{ $product->is_active
                                                ? 'bg-green-100 text-green-800 border border-green-200'
                                                : 'bg-red-100 text-red-800 border border-red-200' }}">
                                            <i class="fas fa-circle mr-1.5" style="font-size:6px"></i>
                                            {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                        @if($product->is_featured)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                                <i class="fas fa-star mr-1.5" style="font-size:9px"></i> Featured
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Actions -->
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Edit -->
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                           class="inline-flex items-center px-3 py-2 bg-green-50 text-green-700 border border-green-200 rounded-lg hover:bg-green-100 hover:border-green-300 transition-all duration-200 text-xs font-semibold"
                                           title="Edit Produk">
                                            <i class="fas fa-edit mr-1.5"></i>
                                            <span class="hidden sm:inline">Edit</span>
                                        </a>

                                        <!-- Toggle Status -->
                                        <button onclick="toggleStatus({{ $product->id }})"
                                                class="inline-flex items-center px-3 py-2 rounded-lg transition-all duration-200 text-xs font-semibold border
                                                    {{ $product->is_active
                                                        ? 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100'
                                                        : 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100' }}"
                                                title="{{ $product->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <i class="fas fa-toggle-{{ $product->is_active ? 'on text-green-500' : 'off text-gray-400' }}"></i>
                                        </button>

                                        <!-- Toggle Featured -->
                                        <button onclick="toggleFeatured({{ $product->id }})"
                                                class="inline-flex items-center px-3 py-2 rounded-lg transition-all duration-200 text-xs font-semibold border
                                                    {{ $product->is_featured
                                                        ? 'bg-amber-50 text-amber-700 border-amber-200 hover:bg-amber-100'
                                                        : 'bg-gray-50 text-gray-500 border-gray-200 hover:bg-gray-100' }}"
                                                title="{{ $product->is_featured ? 'Hapus Featured' : 'Jadikan Featured' }}">
                                            <i class="fas fa-star {{ $product->is_featured ? 'text-amber-500' : 'text-gray-400' }}"></i>
                                        </button>

                                        <!-- Delete -->
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    onclick="handleDeleteProduct(this, '{{ $product->name }}', {{ $product->id }})"
                                                    class="inline-flex items-center px-3 py-2 bg-red-50 text-red-600 border border-red-200 rounded-lg hover:bg-red-100 hover:border-red-300 transition-all duration-200 text-xs font-semibold"
                                                    title="Hapus Produk">
                                                <i class="fas fa-trash mr-1.5"></i>
                                                <span class="hidden sm:inline">Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 bg-gradient-to-br from-green-50 to-emerald-100 rounded-full flex items-center justify-center mb-4 shadow-inner">
                                            <i class="fas fa-box-open text-green-400 text-3xl"></i>
                                        </div>
                                        <p class="text-lg font-semibold text-gray-700 mb-1">Belum ada produk</p>
                                        <p class="text-sm text-gray-400 mb-5">Mulai dengan menambahkan produk pertama Anda</p>
                                        <a href="{{ route('admin.products.create') }}"
                                           class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 text-sm font-semibold shadow-md hover:shadow-lg hover:-translate-y-0.5">
                                            <i class="fas fa-plus mr-2"></i>
                                            Tambah Produk Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $products->links() }}
            </div>
            @endif
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
async function toggleStatus(productId) {
    try {
        const response = await fetch(`/admin/products/${productId}/toggle-status`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success) {
            location.reload();
        } else {
            Swal.fire('Error', data.message || 'Gagal mengubah status', 'error');
        }
    } catch (error) {
        Swal.fire('Error', 'Terjadi kesalahan', 'error');
    }
}

async function toggleFeatured(productId) {
    try {
        const response = await fetch(`/admin/products/${productId}/toggle-featured`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success) {
            location.reload();
        } else {
            Swal.fire('Error', data.message || 'Gagal mengubah featured', 'error');
        }
    } catch (error) {
        Swal.fire('Error', 'Terjadi kesalahan', 'error');
    }
}

async function handleDeleteProduct(button, productName, productId) {
    const confirmed = await Swal.fire({
        title: 'Hapus Produk?',
        html: `Apakah Anda yakin ingin menghapus produk "<strong>${productName}</strong>"?<br><span class="text-sm text-gray-500">Produk akan dipindahkan ke trash.</span>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    });

    if (confirmed.isConfirmed) {
        const form = button.closest('form');
        loadingOverlay.show('Menghapus produk...');
        form.submit();
    }
}
</script>
@endpush
