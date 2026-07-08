@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Manajemen Berat Produk</h1>
        <p class="text-gray-600 mt-2">Kelola berat produk untuk perhitungan ongkos kirim</p>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Daftar Produk</h2>
                <div class="flex items-center space-x-3">
                    <button onclick="setAllWeights()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-weight mr-2"></i>Set Semua Berat
                    </button>
                    <button onclick="saveAllWeights()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>Simpan Semua
                    </button>
                </div>
            </div>
        </div>

        <form id="bulk-weight-form" method="POST" action="{{ route('admin.products.bulk-update-weight') }}">
            @csrf
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Produk
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kategori
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Berat Saat Ini
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Berat Baru (gram)
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($products as $product)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full object-cover" 
                                             src="{{ $product->image_url }}" 
                                             alt="{{ $product->name }}">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $product->name }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $product->category->name ?? 'Tidak ada kategori' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="font-medium">{{ number_format($product->weight ?? 500) }} gram</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="hidden" name="products[{{ $loop->index }}][id]" value="{{ $product->id }}">
                                <input type="number" 
                                       name="products[{{ $loop->index }}][weight]" 
                                       value="{{ $product->weight ?? 500 }}"
                                       min="1" 
                                       max="50000"
                                       class="weight-input w-24 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500 text-sm"
                                       data-product-id="{{ $product->id }}"
                                       data-original-weight="{{ $product->weight ?? 500 }}">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button type="button" 
                                        onclick="resetWeight({{ $product->id }})"
                                        class="text-gray-600 hover:text-gray-900 mr-3">
                                    <i class="fas fa-undo" title="Reset"></i>
                                </button>
                                <button type="button" 
                                        onclick="setCommonWeight({{ $product->id }}, 500)"
                                        class="text-blue-600 hover:text-blue-900 mr-3"
                                        title="Set 500g">
                                    500g
                                </button>
                                <button type="button" 
                                        onclick="setCommonWeight({{ $product->id }}, 1000)"
                                        class="text-green-600 hover:text-green-900"
                                        title="Set 1kg">
                                    1kg
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        Menampilkan {{ $products->count() }} dari {{ $products->total() }} produk
                    </div>
                    <div class="flex items-center space-x-3">
                        <button type="button" onclick="window.location.href='{{ route('admin.dashboard') }}'" 
                                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </button>
                        <button type="submit" 
                                class="px-6 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors">
                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $products->links() }}
    </div>
</div>

<!-- Set All Weights Modal -->
<div id="setAllModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Set Berat Semua Produk</h3>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Berat (gram)</label>
            <input type="number" id="allWeightInput" min="1" max="50000" value="500" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500">
        </div>
        <div class="flex justify-end space-x-3">
            <button onclick="closeSetAllModal()" 
                    class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                Batal
            </button>
            <button onclick="applyAllWeights()" 
                    class="px-4 py-2 bg-amber-600 text-white rounded-md hover:bg-amber-700">
                Terapkan
            </button>
        </div>
    </div>
</div>

<script>
function setAllWeights() {
    document.getElementById('setAllModal').classList.remove('hidden');
    document.getElementById('setAllModal').classList.add('flex');
}

function closeSetAllModal() {
    document.getElementById('setAllModal').classList.add('hidden');
    document.getElementById('setAllModal').classList.remove('flex');
}

function applyAllWeights() {
    const weight = document.getElementById('allWeightInput').value;
    if (weight && weight > 0) {
        document.querySelectorAll('.weight-input').forEach(input => {
            input.value = weight;
        });
        closeSetAllModal();
    }
}

function resetWeight(productId) {
    const input = document.querySelector(`input[data-product-id="${productId}"]`);
    if (input) {
        input.value = input.dataset.originalWeight;
    }
}

function setCommonWeight(productId, weight) {
    const input = document.querySelector(`input[data-product-id="${productId}"]`);
    if (input) {
        input.value = weight;
    }
}

function saveAllWeights() {
    if (confirm('Simpan semua perubahan berat produk?')) {
        document.getElementById('bulk-weight-form').submit();
    }
}

// Close modal when clicking outside
document.getElementById('setAllModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeSetAllModal();
    }
});
</script>
@endsection