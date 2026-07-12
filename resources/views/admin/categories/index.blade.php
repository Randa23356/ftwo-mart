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
                        <i class="fas fa-tags text-white text-xl sm:text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Kelola Kategori</h1>
                        <p class="text-sm sm:text-base text-gray-500 mt-0.5">Manajemen kategori produk Batik Sasambo</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.categories.trash') }}"
                       class="inline-flex items-center px-4 py-2.5 bg-red-50 text-red-700 border border-red-200 rounded-xl hover:bg-red-100 transition-all duration-200 text-sm font-medium">
                        <i class="fas fa-trash mr-2"></i>
                        <span class="hidden sm:inline">Trash</span>
                    </a>
                    <a href="{{ route('admin.categories.create') }}"
                       class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-xl transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5 text-sm font-semibold shadow-md">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Kategori
                    </a>
                </div>
            </div>
        </div>

        <!-- ===== STATS CARDS ===== -->
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6 mb-6 sm:mb-8">
            <div class="group relative bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 p-5 sm:p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-green-400/15 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-2.5 rounded-xl shadow-md group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-tags text-white text-base"></i>
                        </div>
                        <div class="text-2xl sm:text-3xl font-bold text-green-600">{{ $categories->total() }}</div>
                    </div>
                    <div class="text-xs sm:text-sm font-semibold text-gray-600">Total Kategori</div>
                    <div class="text-xs text-gray-400 mt-0.5">Semua kategori</div>
                </div>
            </div>

            <div class="group relative bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 p-5 sm:p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-emerald-400/15 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <div class="bg-gradient-to-br from-emerald-500 to-teal-600 p-2.5 rounded-xl shadow-md group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-check-circle text-white text-base"></i>
                        </div>
                        <div class="text-2xl sm:text-3xl font-bold text-emerald-600">{{ $categories->where('is_active', true)->count() }}</div>
                    </div>
                    <div class="text-xs sm:text-sm font-semibold text-gray-600">Kategori Aktif</div>
                    <div class="text-xs text-gray-400 mt-0.5">Tampil di katalog</div>
                </div>
            </div>

            <div class="group relative bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 p-5 sm:p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 overflow-hidden col-span-2 lg:col-span-1">
                <div class="absolute top-0 right-0 w-20 h-20 bg-teal-400/15 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <div class="bg-gradient-to-br from-teal-500 to-cyan-600 p-2.5 rounded-xl shadow-md group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-box text-white text-base"></i>
                        </div>
                        <div class="text-2xl sm:text-3xl font-bold text-teal-600">
                            {{ $categories->sum(fn($cat) => $cat->products()->count()) }}
                        </div>
                    </div>
                    <div class="text-xs sm:text-sm font-semibold text-gray-600">Total Produk</div>
                    <div class="text-xs text-gray-400 mt-0.5">Di semua kategori</div>
                </div>
            </div>
        </div>

        <!-- ===== CATEGORIES TABLE ===== -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-green-100">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-600 to-emerald-700 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-list text-white text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Daftar Kategori</h2>
                        <p class="text-xs text-gray-500">{{ $categories->total() }} kategori tersedia</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden md:table-cell">Slug</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden lg:table-cell">Produk</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($categories as $category)
                            <tr class="hover:bg-green-50/40 transition-colors duration-150 group">
                                <td class="px-5 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 rounded-xl overflow-hidden bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center text-white font-bold shadow-sm flex-shrink-0 group-hover:scale-105 transition-transform">
                                            @if($category->image)
                                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                                            @else
                                                {{ strtoupper(substr($category->name, 0, 1)) }}
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-semibold text-gray-900 text-sm">{{ $category->name }}</p>
                                            @if($category->description)
                                                <p class="text-xs text-gray-400 mt-0.5 line-clamp-1">{{ Str::limit($category->description, 50) }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 hidden md:table-cell">
                                    <span class="text-xs text-gray-600 font-mono bg-gray-50 px-2 py-1 rounded-lg border border-gray-100">{{ $category->slug }}</span>
                                </td>
                                <td class="px-5 py-4 hidden lg:table-cell">
                                    <span class="inline-flex items-center px-3 py-1.5 bg-green-50 text-green-700 border border-green-100 rounded-lg text-xs font-semibold">
                                        <i class="fas fa-box mr-1.5 text-green-500" style="font-size:9px"></i>
                                        {{ $category->products()->count() }} produk
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                        {{ $category->is_active
                                            ? 'bg-green-100 text-green-800 border border-green-200'
                                            : 'bg-red-100 text-red-800 border border-red-200' }}">
                                        <i class="fas fa-circle mr-1.5" style="font-size:6px"></i>
                                        {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.categories.edit', $category) }}"
                                           class="inline-flex items-center px-3 py-2 bg-green-50 text-green-700 border border-green-200 rounded-lg hover:bg-green-100 hover:border-green-300 transition-all duration-200 text-xs font-semibold">
                                            <i class="fas fa-edit mr-1.5"></i>
                                            <span class="hidden sm:inline">Edit</span>
                                        </a>
                                        <button type="button" onclick="toggleStatus({{ $category->id }})"
                                                class="inline-flex items-center px-3 py-2 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg hover:bg-emerald-100 transition-all duration-200 text-xs font-semibold"
                                                title="Toggle Status">
                                            <i class="fas fa-toggle-{{ $category->is_active ? 'on' : 'off' }}"></i>
                                        </button>
                                        <button type="button" onclick="confirmDeleteCategory({{ $category->id }}, @js($category->name), {{ $category->products()->count() }})"
                                                class="inline-flex items-center px-3 py-2 bg-red-50 text-red-700 border border-red-200 rounded-lg hover:bg-red-100 hover:border-red-300 transition-all duration-200 text-xs font-semibold">
                                            <i class="fas fa-trash mr-1.5"></i>
                                            <span class="hidden sm:inline">Hapus</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 bg-gradient-to-br from-green-50 to-emerald-100 rounded-full flex items-center justify-center mb-4 shadow-inner">
                                            <i class="fas fa-tags text-green-500 text-3xl"></i>
                                        </div>
                                        <p class="text-lg font-semibold text-gray-700 mb-1">Belum ada kategori</p>
                                        <p class="text-sm text-gray-400 mb-5">Mulai dengan menambahkan kategori pertama</p>
                                        <a href="{{ route('admin.categories.create') }}"
                                           class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 text-sm font-semibold shadow-md hover:shadow-lg hover:-translate-y-0.5">
                                            <i class="fas fa-plus mr-2"></i>
                                            Tambah Kategori Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($categories->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $categories->links() }}
            </div>
            @endif
        </div>

    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl p-6 max-w-md w-full shadow-2xl border border-gray-100">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-red-100">
                <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Hapus Kategori?</h3>
            <p id="deleteMessage" class="text-gray-600 text-sm"></p>
        </div>

        <div id="moveProductsSection" class="hidden mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Pindahkan produk ke kategori:</label>
            <select id="targetCategoryId" class="w-full rounded-xl border-gray-200 focus:ring-green-500 focus:border-green-500 text-sm py-2.5">
                <option value="">Pilih kategori...</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-3">
            <button type="button" onclick="closeDeleteModal()"
                    class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-semibold text-sm">
                Batal
            </button>
            <button type="button" id="confirmDeleteBtn" onclick="executeDelete()"
                    class="flex-1 px-4 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors font-semibold text-sm">
                Hapus
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const loadingOverlay = {
    show: function(message = 'Loading...') {
        const overlay = document.createElement('div');
        overlay.id = 'loading-overlay';
        overlay.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-[9999]';
        overlay.innerHTML = `
            <div class="bg-white rounded-2xl p-6 shadow-2xl flex items-center space-x-4">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-green-600"></div>
                <span class="text-gray-700 font-medium">${message}</span>
            </div>
        `;
        document.body.appendChild(overlay);
    },
    hide: function() {
        const overlay = document.getElementById('loading-overlay');
        if (overlay) overlay.remove();
    }
};

let currentCategoryId = null;
let currentProductCount = 0;

async function toggleStatus(categoryId) {
    try {
        const response = await fetch(`/admin/categories/${categoryId}/toggle-status`, {
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

function confirmDeleteCategory(categoryId, categoryName, productCount) {
    currentCategoryId = categoryId;
    currentProductCount = productCount;

    const modal = document.getElementById('deleteModal');
    const message = document.getElementById('deleteMessage');
    const moveSection = document.getElementById('moveProductsSection');
    const targetSelect = document.getElementById('targetCategoryId');

    if (productCount > 0) {
        message.textContent = `Kategori "${categoryName}" memiliki ${productCount} produk. Pindahkan produk ke kategori lain terlebih dahulu.`;
        moveSection.classList.remove('hidden');
        Array.from(targetSelect.options).forEach(option => {
            if (option.value == categoryId) {
                option.disabled = true;
                option.hidden = true;
            } else {
                option.disabled = false;
                option.hidden = false;
            }
        });
    } else {
        message.textContent = `Apakah Anda yakin ingin menghapus kategori "${categoryName}"?`;
        moveSection.classList.add('hidden');
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    currentCategoryId = null;
    currentProductCount = 0;
}

async function executeDelete() {
    if (currentProductCount > 0) {
        const targetCategoryId = document.getElementById('targetCategoryId').value;
        if (!targetCategoryId) {
            Swal.fire('Peringatan', 'Pilih kategori tujuan untuk memindahkan produk', 'warning');
            return;
        }
        try {
            const response = await fetch(`/admin/categories/${currentCategoryId}/move-products`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ target_category_id: targetCategoryId })
            });
            const data = await response.json();
            if (!data.success) {
                Swal.fire('Error', data.message || 'Gagal memindahkan produk', 'error');
                return;
            }
        } catch (error) {
            Swal.fire('Error', 'Terjadi kesalahan saat memindahkan produk', 'error');
            return;
        }
    }

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/admin/categories/${currentCategoryId}`;
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'DELETE';
    form.appendChild(csrfInput);
    form.appendChild(methodInput);
    document.body.appendChild(form);
    closeDeleteModal();
    loadingOverlay.show('Menghapus kategori...');
    form.submit();
}
</script>
@endpush
