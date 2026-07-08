@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Pengaturan Pengiriman</h1>
            <p class="text-gray-600 mt-2">Atur kota asal pengiriman dan informasi warehouse</p>
        </div>
        <div>
            <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors" onclick="testShipping()">
                <i class="fas fa-calculator mr-2"></i> Test Ongkir
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center mb-2">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <span class="font-medium">Terjadi kesalahan:</span>
            </div>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Current Origin Info -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-map-marker-alt text-blue-600 mr-2"></i> Kota Asal Saat Ini
                </h2>
                
                @if($setting)
                    <div class="text-center mb-4">
                        <div class="bg-blue-600 text-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-warehouse text-2xl"></i>
                        </div>
                        
                        <h3 class="text-lg font-bold text-blue-600">{{ $setting->warehouse_name }}</h3>
                        <p class="text-gray-600 mb-1">{{ $setting->origin_city_name }}</p>
                        <p class="text-gray-600 mb-2">{{ $setting->origin_province }}</p>
                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">{{ $setting->origin_postal_code }}</span>
                    </div>

                    <div class="border-t pt-4">
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex justify-between">
                                <span>City ID:</span>
                                <span class="font-medium">{{ $setting->origin_city_id }}</span>
                            </div>
                            @if($setting->contact_phone)
                            <div class="flex justify-between">
                                <span>Telepon:</span>
                                <span class="font-medium">{{ $setting->contact_phone }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between">
                                <span>Status:</span>
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Aktif</span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center text-gray-500 py-8">
                        <i class="fas fa-exclamation-triangle text-4xl mb-3"></i>
                        <p>Belum ada pengaturan pengiriman</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Settings Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">
                    <i class="fas fa-cog text-blue-600 mr-2"></i> Pengaturan Kota Asal
                </h2>
                
                <form method="POST" action="{{ route('admin.shipping.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="origin_city_id" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-map-marker-alt text-blue-600 mr-1"></i> Kota Asal Pengiriman
                            </label>
                            <select name="origin_city_id" id="origin_city_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">Pilih Kota Asal</option>
                                @foreach($cities->groupBy('province') as $province => $provinceCities)
                                    <optgroup label="{{ $province }}">
                                        @foreach($provinceCities as $city)
                                            <option value="{{ $city->city_id }}" 
                                                    data-postal="{{ $city->postal_code }}"
                                                    {{ ($setting && $setting->origin_city_id == $city->city_id) ? 'selected' : '' }}>
                                                {{ $city->type }} {{ $city->city_name }} ({{ $city->postal_code }})
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            <p class="text-sm text-gray-500 mt-1">Pilih kota dari mana produk akan dikirim</p>
                        </div>

                        <div>
                            <label for="warehouse_name" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-warehouse text-blue-600 mr-1"></i> Nama Warehouse/Toko
                            </label>
                            <input type="text" name="warehouse_name" id="warehouse_name" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   value="{{ $setting->warehouse_name ?? 'Picia Bakery Warehouse' }}" 
                                   required>
                            <p class="text-sm text-gray-500 mt-1">Nama toko atau warehouse</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="warehouse_address" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-map text-blue-600 mr-1"></i> Alamat Lengkap Warehouse
                        </label>
                        <textarea name="warehouse_address" id="warehouse_address" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                  rows="3" 
                                  placeholder="Masukkan alamat lengkap warehouse (opsional)">{{ $setting->warehouse_address ?? '' }}</textarea>
                        <p class="text-sm text-gray-500 mt-1">Alamat lengkap untuk informasi pelanggan</p>
                    </div>

                    <div class="mt-6">
                        <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-phone text-blue-600 mr-1"></i> Nomor Telepon Warehouse
                        </label>
                        <input type="text" name="contact_phone" id="contact_phone" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               value="{{ $setting->contact_phone ?? '' }}" 
                               placeholder="Contoh: 0812-3456-7890">
                        <p class="text-sm text-gray-500 mt-1">Nomor telepon yang bisa dihubungi (opsional)</p>
                    </div>

                    <div class="mt-8 flex space-x-3">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                            <i class="fas fa-save mr-2"></i> Simpan Pengaturan
                        </button>
                        <button type="button" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition-colors" onclick="resetForm()">
                            <i class="fas fa-undo mr-2"></i> Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Test Shipping Modal -->
<div id="testShippingModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4 max-h-screen overflow-y-auto">
        <div class="p-6 border-b">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-calculator text-blue-600 mr-2"></i> Test Perhitungan Ongkir
                </h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeTestModal()">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        
        <div class="p-6">
            <form id="testShippingForm">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kota Tujuan</label>
                        <select name="test_destination_city_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Pilih Kota Tujuan</option>
                            @foreach($cities->groupBy('province') as $province => $provinceCities)
                                <optgroup label="{{ $province }}">
                                    @foreach($provinceCities as $city)
                                        <option value="{{ $city->city_id }}">
                                            {{ $city->type }} {{ $city->city_name }} ({{ $city->postal_code }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Berat (gram)</label>
                        <input type="number" name="test_weight" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               value="500" min="100" max="30000" required>
                        <p class="text-sm text-gray-500 mt-1">Minimal 100 gram, maksimal 30 kg</p>
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-calculator mr-2"></i> Hitung Ongkir
                    </button>
                </div>
            </form>

            <div id="testResults" class="mt-6 hidden">
                <div class="border-t pt-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-truck text-blue-600 mr-2"></i> Hasil Perhitungan Ongkir
                    </h4>
                    <div id="testResultsContent"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function testShipping() {
    document.getElementById('testShippingModal').classList.remove('hidden');
    document.getElementById('testResults').classList.add('hidden');
}

function closeTestModal() {
    document.getElementById('testShippingModal').classList.add('hidden');
}

function resetForm() {
    if (confirm('Reset form ke pengaturan awal?')) {
        location.reload();
    }
}

// Handle test shipping form
document.getElementById('testShippingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menghitung...';
    submitBtn.disabled = true;
    
    fetch('{{ route("admin.shipping.test") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayTestResults(data.data);
            document.getElementById('testResults').classList.remove('hidden');
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('Error: Terjadi kesalahan');
        console.error('Error:', error);
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

function displayTestResults(data) {
    let html = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                <h5 class="font-semibold text-blue-800 mb-2">Asal</h5>
                <p class="font-medium">${data.origin.city}</p>
                <p class="text-sm text-gray-600">${data.origin.province}</p>
                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">${data.origin.postal_code}</span>
            </div>
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                <h5 class="font-semibold text-green-800 mb-2">Tujuan</h5>
                <p class="font-medium">${data.destination.city}</p>
                <p class="text-sm text-gray-600">${data.destination.province}</p>
                <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm">${data.destination.postal_code}</span>
            </div>
        </div>
        
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 mb-6">
            <p class="text-amber-800"><i class="fas fa-weight mr-2"></i> <strong>Berat:</strong> ${data.weight} gram</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kurir</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Layanan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Biaya</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estimasi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
    `;
    
    data.shipping_options.forEach(function(option) {
        html += `
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="font-medium text-gray-900">${option.courier_name}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">${option.service_name}</div>
                    <div class="text-sm text-gray-500">(${option.service_code})</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-blue-600">Rp ${new Intl.NumberFormat('id-ID').format(option.cost)}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${option.etd} hari
                </td>
            </tr>
        `;
    });
    
    html += `
                </tbody>
            </table>
        </div>
    `;
    
    document.getElementById('testResultsContent').innerHTML = html;
}

// Close modal when clicking outside
document.getElementById('testShippingModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeTestModal();
    }
});
</script>
@endpush