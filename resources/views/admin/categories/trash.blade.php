@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <div class="max-w-7xl mx-auto">
            <!-- Enhanced Header -->
            <div class="mb-6 sm:mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">
                            <i class="fas fa-trash mr-3 text-red-600"></i>
                            Kategori Terhapus
                        </h1>
                        <p class="text-sm sm:text-base text-gray-600">Kelola kategori yang telah dihapus</p>
                    </div>
                    <a href="{{ route('admin.categories.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-green-700 hover:bg-green-800 text-white rounded-xl transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5 text-sm font-medium shadow-lg">
                        <i class="fas fa-arrow-left mr-2"></i>
                        <span>Kembali</span>
                    </a>
                </div>
            </div>

            <!-- Enhanced Products Table -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <!-- Table Header -->
                <div class="bg-red-50 px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-red-600 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                            <i class="fas fa-trash text-white text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Kategori Terhapus</h2>
                            <p class="text-xs text-gray-600">{{ $categories->total() }} kategori di trash</p>
                        </div>
                    </div>
                </div>
                
                <!-- Responsive Table Container -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider hidden sm:table-cell">Kategori</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider hidden md:table-cell">Harga</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider hidden lg:table-cell">Dihapus</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($categories as $product)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-start space-x-3">
                                            @if($product->image)
                                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                                     class="w-12 h-12 rounded-lg object-cover border border-gray-200 shadow-sm opacity-60">
                                            @else
                                                <div class="w-12 h-12 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center opacity-60">
                                                    <i class="fas fa-image text-gray-400"></i>
                                                </div>
                                            @endif
                                            <div class="flex-1 min-w-0">
                                                <p class="font-bold text-gray-900 truncate">{{ $product->name }}</p>
                                                <p class="text-sm text-gray-500 line-clamp-2">{{ Str::limit($product->description, 80) }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 hidden sm:table-cell">
                                        <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-medium">
                                            {{ $product->category->name ?? 'Tidak ada kategori' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 hidden md:table-cell">
                                        <span class="font-bold text-gray-600">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-6 py-4 hidden lg:table-cell">
                                        <span class="text-sm text-gray-500">
                                            {{ $product->deleted_at->diffForHumans() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center space-x-2">
                                            <form action="{{ route('admin.categories.restore', $product->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="button" onclick="handleRestoreProduct(this, '{{ $product->name }}')" 
                                                        class="inline-flex items-center px-3 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors text-sm font-medium">
                                                    <i class="fas fa-undo mr-1"></i>
                                                    <span class="hidden sm:inline">Pulihkan</span>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.categories.force-delete', $product->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="handleForceDeleteProduct(this, '{{ $product->name }}')" 
                                                        class="inline-flex items-center px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-sm font-medium">
                                                    <i class="fas fa-trash-alt mr-1"></i>
                                                    <span class="hidden sm:inline">Hapus Permanen</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                                <i class="fas fa-check-circle text-green-400 text-2xl"></i>
                                            </div>
                                            <p class="text-lg font-medium text-gray-600 mb-2">Trash kosong</p>
                                            <p class="text-sm text-gray-500">Tidak ada kategori yang dihapus</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($categories->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $categories->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
async function handleRestoreProduct(button, productName) {
    const confirmed = await Swal.fire({
        title: 'Pulihkan Kategori?',
        html: `Apakah Anda yakin ingin memulihkan kategori "<strong>${productName}</strong>"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#16a34a',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Pulihkan!',
        cancelButtonText: 'Batal'
    });
    
    if (confirmed.isConfirmed) {
        const form = button.closest('form');
        loadingOverlay.show('Memulihkan kategori...');
        form.submit();
    }
}

async function handleForceDeleteProduct(button, productName) {
    const confirmed = await Swal.fire({
        title: 'Hapus Permanen?',
        html: `Apakah Anda yakin ingin menghapus permanen kategori "<strong>${productName}</strong>"?<br><br><span class="text-red-600 font-semibold">Tindakan ini tidak dapat dibatalkan!</span>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus Permanen!',
        cancelButtonText: 'Batal'
    });
    
    if (confirmed.isConfirmed) {
        const form = button.closest('form');
        loadingOverlay.show('Menghapus kategori permanen...');
        form.submit();
    }
}
</script>
@endpush
