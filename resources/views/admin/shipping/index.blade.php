@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Animated Background Pattern -->
    <div class="fixed inset-0 opacity-30">
        <div class="absolute inset-0" style="background-image: url(&quot;data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2322c55e' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E&quot;);"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
        <!-- Modern Header -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl lg:rounded-3xl shadow-xl border border-white/20 p-6 lg:p-8 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="flex items-center space-x-4">
                    <div class="bg-green-700 p-3 lg:p-4 rounded-2xl shadow-lg">
                        <i class="fas fa-shipping-fast text-white text-xl lg:text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl lg:text-4xl font-bold text-gray-900">Pengaturan Pengiriman</h1>
                        <p class="text-gray-600 mt-1 text-sm lg:text-base">Atur kota asal pengiriman, tarif, dan informasi warehouse</p>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="button" onclick="testShipping()"
                        class="group relative inline-flex items-center justify-center px-6 py-3 bg-green-700 hover:bg-green-800 text-white font-semibold rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <span class="absolute inset-0 rounded-xl bg-white opacity-0 group-hover:opacity-20 transition-opacity"></span>
                        <i class="fas fa-calculator mr-2 relative z-10"></i>
                        <span class="relative z-10">Test Ongkir</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="bg-green-50/80 backdrop-blur border border-green-200 text-green-800 px-5 py-4 rounded-2xl mb-6 flex items-center shadow-sm">
                <div class="bg-green-100 p-2 rounded-xl mr-3">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50/80 backdrop-blur border border-red-200 text-red-800 px-5 py-4 rounded-2xl mb-6 shadow-sm">
                <div class="flex items-center mb-2">
                    <div class="bg-red-100 p-2 rounded-xl mr-3">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <span class="font-semibold">Terjadi kesalahan:</span>
                </div>
                <ul class="list-disc list-inside ml-10 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Main Grid: Current Info + Settings Form -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8 mb-8">
            <!-- Current Origin Info Card -->
            <div class="lg:col-span-1">
                <div class="bg-white/70 backdrop-blur-lg rounded-2xl lg:rounded-3xl shadow-xl border border-white/20 p-6 lg:p-8 h-full">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="bg-green-600 p-2.5 rounded-xl shadow-lg">
                            <i class="fas fa-map-marker-alt text-white"></i>
                        </div>
                        <h2 class="text-lg font-bold text-gray-900">Kota Asal Saat Ini</h2>
                    </div>

                    @if($setting)
                        <div class="text-center mb-6">
                            <div class="bg-gradient-to-br from-green-500 to-emerald-600 text-white rounded-2xl w-20 h-20 flex items-center justify-center mx-auto mb-4 shadow-lg">
                                <i class="fas fa-warehouse text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">{{ $setting->warehouse_name }}</h3>
                            <p class="text-gray-600 mt-1">{{ $setting->origin_city_name }}</p>
                            <p class="text-gray-500 text-sm">{{ $setting->origin_province }}</p>
                            <span class="inline-block mt-2 bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                <i class="fas fa-envelope mr-1"></i> {{ $setting->origin_postal_code }}
                            </span>
                        </div>

                        <div class="border-t border-gray-100 pt-4 space-y-3">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500">City ID</span>
                                <span class="font-semibold text-gray-800 bg-gray-100 px-2 py-0.5 rounded-lg">{{ $setting->origin_city_id }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500">Tarif Dasar</span>
                                <span class="font-semibold text-gray-800">Rp {{ number_format($setting->base_cost ?? 8000, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500">Tarif/Kg</span>
                                <span class="font-semibold text-gray-800">Rp {{ number_format($setting->cost_per_kg ?? 2500, 0, ',', '.') }}</span>
                            </div>
                            @if($setting->contact_phone)
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500">Telepon</span>
                                <span class="font-semibold text-gray-800">{{ $setting->contact_phone }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500">Status</span>
                                <span class="bg-green-100 text-green-700 px-2.5 py-0.5 rounded-full text-xs font-bold">
                                    <i class="fas fa-circle text-[6px] mr-1 animate-pulse"></i> Aktif
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="text-center text-gray-400 py-10">
                            <div class="bg-gray-100 rounded-2xl w-20 h-20 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-exclamation-triangle text-3xl text-gray-300"></i>
                            </div>
                            <p class="font-medium">Belum ada pengaturan</p>
                            <p class="text-sm mt-1">Silakan isi form di samping</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Settings Form Card -->
            <div class="lg:col-span-2">
                <div class="bg-white/70 backdrop-blur-lg rounded-2xl lg:rounded-3xl shadow-xl border border-white/20 p-6 lg:p-8">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="bg-green-600 p-2.5 rounded-xl shadow-lg">
                            <i class="fas fa-cog text-white"></i>
                        </div>
                        <h2 class="text-lg font-bold text-gray-900">Pengaturan Kota Asal</h2>
                    </div>

                    <form method="POST" action="{{ route('admin.shipping.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="origin_city_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-map-marker-alt text-green-600 mr-1"></i> Kota Asal Pengiriman
                                </label>
                                <select name="origin_city_id" id="origin_city_id"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white/80 transition-all" required>
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
                                <p class="text-xs text-gray-400 mt-1.5">Pilih kota dari mana produk akan dikirim</p>
                            </div>

                            <div>
                                <label for="warehouse_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-warehouse text-green-600 mr-1"></i> Nama Warehouse/Toko
                                </label>
                                <input type="text" name="warehouse_name" id="warehouse_name"
                                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white/80 transition-all"
                                       value="{{ $setting->warehouse_name ?? 'Ftwo Mart' }}" required>
                                <p class="text-xs text-gray-400 mt-1.5">Nama toko atau warehouse</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                            <div>
                                <label for="base_cost" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-money-bill text-green-600 mr-1"></i> Tarif Dasar (Base Cost)
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-gray-400 text-sm font-medium">Rp</span>
                                    </div>
                                    <input type="number" name="base_cost" id="base_cost"
                                           class="w-full pl-11 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white/80 transition-all"
                                           value="{{ $setting->base_cost ?? 8000 }}" required min="0">
                                </div>
                                <p class="text-xs text-gray-400 mt-1.5">Tarif dasar pengiriman</p>
                            </div>

                            <div>
                                <label for="cost_per_kg" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-weight-hanging text-green-600 mr-1"></i> Tarif per Kg
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-gray-400 text-sm font-medium">Rp</span>
                                    </div>
                                    <input type="number" name="cost_per_kg" id="cost_per_kg"
                                           class="w-full pl-11 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white/80 transition-all"
                                           value="{{ $setting->cost_per_kg ?? 2500 }}" required min="0">
                                </div>
                                <p class="text-xs text-gray-400 mt-1.5">Tarif tambahan per kilogram</p>
                            </div>
                        </div>

                        <div class="mt-5">
                            <label for="warehouse_address" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-map text-green-600 mr-1"></i> Alamat Lengkap Warehouse
                            </label>
                            <textarea name="warehouse_address" id="warehouse_address"
                                      class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white/80 transition-all"
                                      rows="3"
                                      placeholder="Masukkan alamat lengkap warehouse (opsional)">{{ $setting->warehouse_address ?? '' }}</textarea>
                        </div>

                        <div class="mt-5">
                            <label for="contact_phone" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-phone text-green-600 mr-1"></i> Nomor Telepon Warehouse
                            </label>
                            <input type="text" name="contact_phone" id="contact_phone"
                                   class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white/80 transition-all"
                                   value="{{ $setting->contact_phone ?? '' }}"
                                   placeholder="Contoh: 0812-3456-7890">
                        </div>

                        <div class="mt-8 flex flex-col sm:flex-row gap-3">
                            <button type="submit"
                                class="group relative inline-flex items-center justify-center px-6 py-3 bg-green-700 hover:bg-green-800 text-white font-semibold rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                <span class="absolute inset-0 rounded-xl bg-white opacity-0 group-hover:opacity-20 transition-opacity"></span>
                                <i class="fas fa-save mr-2 relative z-10"></i>
                                <span class="relative z-10">Simpan Pengaturan</span>
                            </button>
                            <button type="button" onclick="resetForm()"
                                class="inline-flex items-center justify-center px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition-all duration-300">
                                <i class="fas fa-undo mr-2"></i> Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Regional Multipliers -->
        <div class="bg-white/70 backdrop-blur-lg rounded-2xl lg:rounded-3xl shadow-xl border border-white/20 p-6 lg:p-8">
            <div class="flex items-center space-x-3 mb-6">
                <div class="bg-green-600 p-2.5 rounded-xl shadow-lg">
                    <i class="fas fa-map-marked-alt text-white"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Pengali Jarak per Provinsi</h2>
                    <p class="text-sm text-gray-500">Override multiplier otomatis dengan nilai kustom</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- Form -->
                <div class="lg:col-span-1">
                    <div class="bg-green-50/50 rounded-2xl border border-green-100 p-5">
                        <h3 class="text-sm font-bold text-gray-700 mb-4">
                            <i class="fas fa-plus-circle text-green-600 mr-1"></i> Tambah Pengali Baru
                        </h3>
                        <form method="POST" action="{{ route('admin.shipping.multipliers.store') }}">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Pilih Provinsi</label>
                                <select name="province_name"
                                    class="w-full px-3 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 bg-white text-sm transition-all" required>
                                    <option value="">-- Pilih Provinsi --</option>
                                    @foreach($cities->pluck('province')->unique()->sort() as $prov)
                                        <option value="{{ $prov }}">{{ $prov }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Angka Pengali (Multiplier)</label>
                                <input type="number" step="0.1" name="distance_multiplier"
                                    class="w-full px-3 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 bg-white text-sm transition-all"
                                    placeholder="Contoh: 1.5" required min="0.1">
                                <p class="text-xs text-gray-400 mt-1.5">Default: 0.8 – 3.0 tergantung jarak</p>
                            </div>
                            <button type="submit"
                                class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-xl font-semibold text-sm transition-all duration-300 transform hover:scale-[1.02] shadow-md">
                                <i class="fas fa-plus mr-2"></i> Tambah Pengali
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Table -->
                <div class="lg:col-span-2">
                    <div class="overflow-x-auto rounded-2xl border border-gray-100">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-gray-50/80">
                                    <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Provinsi</th>
                                    <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Multiplier</th>
                                    <th class="px-6 py-3.5 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($multipliers as $mult)
                                    <tr class="hover:bg-green-50/30 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="bg-green-100 p-1.5 rounded-lg mr-3">
                                                    <i class="fas fa-map-pin text-green-600 text-xs"></i>
                                                </div>
                                                <span class="text-sm font-semibold text-gray-900">{{ $mult->province_name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-bold">
                                                x {{ $mult->distance_multiplier }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <form action="{{ route('admin.shipping.multipliers.destroy', $mult->id) }}" method="POST" class="inline-block"
                                                  onsubmit="return confirm('Hapus pengali jarak untuk {{ $mult->province_name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 hover:bg-red-50 px-3 py-1.5 rounded-lg text-sm font-medium transition-all">
                                                    <i class="fas fa-trash mr-1"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-10 text-center">
                                            <div class="text-gray-400">
                                                <i class="fas fa-globe text-3xl mb-2"></i>
                                                <p class="text-sm font-medium">Belum ada pengali jarak khusus</p>
                                                <p class="text-xs mt-1">Semua provinsi menggunakan rumus otomatis</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Test Shipping Modal -->
<div id="testShippingModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden transition-all">
    <div class="bg-white/95 backdrop-blur-xl rounded-2xl lg:rounded-3xl shadow-2xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto border border-white/20">
        <div class="p-6 border-b border-gray-100">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <div class="bg-green-600 p-2.5 rounded-xl shadow-lg">
                        <i class="fas fa-calculator text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Test Perhitungan Ongkir</h3>
                </div>
                <button type="button" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-2 rounded-xl transition-all" onclick="closeTestModal()">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <div class="p-6">
            <form id="testShippingForm">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt text-green-600 mr-1"></i> Kota Tujuan
                        </label>
                        <select name="test_destination_city_id"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white/80 transition-all" required>
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
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-weight text-green-600 mr-1"></i> Berat (gram)
                        </label>
                        <input type="number" name="test_weight"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white/80 transition-all"
                            value="500" min="100" max="30000" required>
                        <p class="text-xs text-gray-400 mt-1.5">Minimal 100 gram, maksimal 30 kg</p>
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit"
                        class="group relative inline-flex items-center justify-center px-6 py-3 bg-green-700 hover:bg-green-800 text-white font-semibold rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <span class="absolute inset-0 rounded-xl bg-white opacity-0 group-hover:opacity-20 transition-opacity"></span>
                        <i class="fas fa-calculator mr-2 relative z-10"></i>
                        <span class="relative z-10">Hitung Ongkir</span>
                    </button>
                </div>
            </form>

            <div id="testResults" class="mt-6 hidden">
                <div class="border-t border-gray-100 pt-6">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="bg-green-100 p-2 rounded-xl">
                            <i class="fas fa-truck text-green-600"></i>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900">Hasil Perhitungan Ongkir</h4>
                    </div>
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
            <div class="bg-green-50 border border-green-200 rounded-2xl p-4 text-center">
                <h5 class="font-bold text-green-800 mb-2">
                    <i class="fas fa-box mr-1"></i> Asal
                </h5>
                <p class="font-semibold text-gray-900">${data.origin.city_name || data.origin.city}</p>
                <p class="text-sm text-gray-600">${data.origin.province}</p>
                <span class="inline-block mt-1 bg-green-100 text-green-800 px-2 py-0.5 rounded-full text-xs font-medium">${data.origin.postal_code || '-'}</span>
            </div>
            <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 text-center">
                <h5 class="font-bold text-emerald-800 mb-2">
                    <i class="fas fa-map-marker-alt mr-1"></i> Tujuan
                </h5>
                <p class="font-semibold text-gray-900">${data.destination.city_name || data.destination.city}</p>
                <p class="text-sm text-gray-600">${data.destination.province}</p>
                <span class="inline-block mt-1 bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-full text-xs font-medium">${data.destination.postal_code || '-'}</span>
            </div>
        </div>

        <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 mb-6 flex items-center">
            <div class="bg-amber-100 p-2 rounded-lg mr-3">
                <i class="fas fa-weight text-amber-600"></i>
            </div>
            <p class="text-amber-800 font-medium text-sm"><strong>Berat:</strong> ${data.weight} gram</p>
        </div>

        <div class="overflow-x-auto rounded-xl border border-gray-100">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-50/80">
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kurir</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Layanan</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Biaya</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Estimasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
    `;

    data.shipping_options.forEach(function(option) {
        html += `
            <tr class="hover:bg-green-50/30 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="font-semibold text-gray-900">${option.courier_name}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">${option.service_name}</div>
                    <div class="text-xs text-gray-400">(${option.service_code})</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm font-bold text-green-700">Rp ${new Intl.NumberFormat('id-ID').format(option.cost)}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm text-gray-600 bg-gray-100 px-2 py-1 rounded-lg">${option.etd} hari</span>
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