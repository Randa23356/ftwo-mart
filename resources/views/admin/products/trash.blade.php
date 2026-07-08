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
                    <div class="bg-gradient-to-br from-red-500 to-rose-600 p-3 sm:p-4 rounded-2xl shadow-lg flex-shrink-0">
                        <i class="fas fa-trash text-white text-xl sm:text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Produk Terhapus</h1>
                        <p class="text-sm sm:text-base text-gray-500 mt-0.5">Kelola produk yang dipindahkan ke trash</p>
                    </div>
                </div>
                <a href="{{ route('admin.products.index') }}"
                   class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-xl transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5 text-sm font-semibold shadow-md">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Produk
                </a>
            </div>
        </div>

        <!-- ===== INFO BANNER ===== -->
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-6 flex items-start gap-3">
            <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                <i class="fas fa-info-circle text-amber-600 text-sm"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-amber-800">Produk di Trash</p>
                <p class="text-xs text-amber-700 mt-0.5">Produk yang dihapus akan tersimpan di sini. Anda bisa memulihkan atau menghapus secara permanen.</p>
            </div>
        </div>

        <!-- ===== TRASH TABLE ===== -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <!-- Table Header -->
            <div class="bg-gradient-to-r from-red-50 to-rose-50 px-6 py-4 border-b border-red-100">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-rose-600 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-trash text-white text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Daftar Produk Terhapus</h2>
                        <p class="text-xs text-gray-500">{{ $products->total() }} produk di trash</p>
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
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden lg:table-cell">Dihapus</th>
                            <th class="px-5 py-3.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($products as $product)
                            <tr class="hover:bg-red-50/30 transition-colors duration-150 group">
                                <!-- Product Name + Thumbnail -->
                                <td class="px-5 py-4">
                                    <div class="flex items-center space-x-3">
                                        @if($product->image || $product->images->isNotEmpty())
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                                 class="w-12 h-12 rounded-xl object-cover border-2 border-gray-100 shadow-sm opacity-60 flex-shrink-0">
                                        @else
                                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 border-2 border-gray-100 flex items-center justify-center opacity-60 flex-shrink-0">
                                                <i class="fas fa-image text-gray-400 text-sm"></i>
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-gray-700 truncate text-sm line-through decoration-red-300">{{ $product->name }}</p>
                                            <p class="text-xs text-gray-400 mt-0.5 line-clamp-1">{{ Str::limit($product->description, 60) }}</p>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700 border border-red-200 mt-1">
                                                <i class="fas fa-trash text-xs mr-1"></i> Di Trash
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Category -->
                                <td class="px-5 py-4 hidden sm:table-cell">
                                    <span class="inline-flex items-center px-3 py-1.5 bg-gray-50 text-gray-600 border border-gray-200 rounded-lg text-xs font-semibold">
                                        <i class="fas fa-tag mr-1.5 text-gray-400" style="font-size:9px"></i>
                                        {{ $product->category->name ?? '—' }}
                                    </span>
                                </td>

                                <!-- Price -->
                                <td class="px-5 py-4 hidden md:table-cell">
                                    <div class="font-semibold text-gray-500 text-sm line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                </td>

                                <!-- Deleted At -->
                                <td class="px-5 py-4 hidden lg:table-cell">
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-red-50 border border-red-200 text-xs font-medium text-red-600">
                                        <i class="fas fa-clock text-xs"></i>
                                        {{ $product->deleted_at->diffForHumans() }}
                                    </div>
                                </td>

                                <!-- Actions -->
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Restore -->
                                        <form action="{{ route('admin.products.restore', $product->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="button"
                                                    onclick="handleRestoreProduct(this, '{{ $product->name }}')"
                                                    class="inline-flex items-center px-3 py-2 bg-green-50 text-green-700 border border-green-200 rounded-lg hover:bg-green-100 hover:border-green-300 transition-all duration-200 text-xs font-semibold"
                                                    title="Pulihkan Produk">
                                                <i class="fas fa-undo mr-1.5"></i>
                                                <span class="hidden sm:inline">Pulihkan</span>
                                            </button>
                                        </form>

                                        <!-- Force Delete -->
                                        <form action="{{ route('admin.products.force-delete', $product->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    onclick="handleForceDeleteProduct(this, '{{ $product->name }}')"
                                                    class="inline-flex items-center px-3 py-2 bg-red-50 text-red-700 border border-red-200 rounded-lg hover:bg-red-100 hover:border-red-300 transition-all duration-200 text-xs font-semibold"
                                                    title="Hapus Permanen">
                                                <i class="fas fa-trash-alt mr-1.5"></i>
                                                <span class="hidden sm:inline">Hapus Permanen</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 bg-gradient-to-br from-green-50 to-emerald-100 rounded-full flex items-center justify-center mb-4 shadow-inner">
                                            <i class="fas fa-check-circle text-green-500 text-3xl"></i>
                                        </div>
                                        <p class="text-lg font-semibold text-gray-700 mb-1">Trash Kosong</p>
                                        <p class="text-sm text-gray-400 mb-5">Tidak ada produk yang dihapus saat ini</p>
                                        <a href="{{ route('admin.products.index') }}"
                                           class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 text-sm font-semibold shadow-md hover:shadow-lg hover:-translate-y-0.5">
                                            <i class="fas fa-arrow-left mr-2"></i>
                                            Kembali ke Produk
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
async function handleRestoreProduct(button, productName) {
    const confirmed = await Swal.fire({
        title: 'Pulihkan Produk?',
        html: `Apakah Anda yakin ingin memulihkan produk "<strong>${productName}</strong>"?<br><span class="text-sm text-gray-500">Produk akan aktif kembali di daftar produk.</span>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#16a34a',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Pulihkan!',
        cancelButtonText: 'Batal'
    });

    if (confirmed.isConfirmed) {
        const form = button.closest('form');
        loadingOverlay.show('Memulihkan produk...');
        form.submit();
    }
}

async function handleForceDeleteProduct(button, productName) {
    const confirmed = await Swal.fire({
        title: 'Hapus Permanen?',
        html: `Apakah Anda yakin ingin menghapus permanen produk "<strong>${productName}</strong>"?<br><br><span class="text-red-600 font-semibold text-sm">⚠️ Tindakan ini tidak dapat dibatalkan!</span>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus Permanen!',
        cancelButtonText: 'Batal'
    });

    if (confirmed.isConfirmed) {
        const form = button.closest('form');
        loadingOverlay.show('Menghapus produk secara permanen...');
        form.submit();
    }
}
</script>
@endpush
