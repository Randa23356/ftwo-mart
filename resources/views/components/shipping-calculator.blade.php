@props(['weight' => 1000, 'showTitle' => true])

<div class="shipping-calculator bg-white rounded-lg shadow-md p-6">
    @if($showTitle)
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-shipping-fast mr-2 text-indigo-600"></i>
            Pilih Metode Pengiriman
        </h3>
    @endif

    <!-- Province Selection -->
    <div class="mb-4">
        <label for="province" class="block text-sm font-medium text-gray-700 mb-2">Provinsi</label>
        <select id="province" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            <option value="">Pilih Provinsi</option>
        </select>
    </div>

    <!-- City Selection -->
    <div class="mb-4">
        <label for="city" class="block text-sm font-medium text-gray-700 mb-2">Kota/Kabupaten</label>
        <select id="city" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" disabled>
            <option value="">Pilih Kota/Kabupaten</option>
        </select>
    </div>

    <!-- Weight Display -->
    <div class="mb-4 p-3 bg-gray-50 rounded-md">
        <div class="flex justify-between items-center">
            <span class="text-sm text-gray-600">Berat Total:</span>
            <span class="font-medium text-gray-900" id="total-weight">{{ number_format($weight) }} gram</span>
        </div>
    </div>

    <!-- Calculate Button -->
    <button id="calculate-shipping" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed" disabled>
        <i class="fas fa-calculator mr-2"></i>
        Hitung Ongkos Kirim
    </button>

    <!-- Loading State -->
    <div id="shipping-loading" class="hidden mt-4 text-center">
        <div class="inline-flex items-center">
            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-indigo-600 mr-2"></div>
            <span class="text-sm text-gray-600">Menghitung ongkos kirim...</span>
        </div>
    </div>

    <!-- Shipping Options -->
    <div id="shipping-options" class="mt-4 space-y-3 hidden">
        <h4 class="font-medium text-gray-900 mb-3">Pilih Layanan Pengiriman:</h4>
        <!-- Options will be populated by JavaScript -->
    </div>

    <!-- Selected Shipping Info -->
    <div id="selected-shipping" class="hidden mt-4 p-4 bg-green-50 border border-green-200 rounded-md">
        <div class="flex items-center justify-between">
            <div>
                <h5 class="font-medium text-green-800">Pengiriman Dipilih:</h5>
                <p class="text-sm text-green-600" id="selected-shipping-info"></p>
            </div>
            <div class="text-right">
                <p class="font-bold text-green-800" id="selected-shipping-cost"></p>
                <p class="text-xs text-green-600" id="selected-shipping-etd"></p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const provinceSelect = document.getElementById('province');
    const citySelect = document.getElementById('city');
    const calculateBtn = document.getElementById('calculate-shipping');
    const loadingDiv = document.getElementById('shipping-loading');
    const optionsDiv = document.getElementById('shipping-options');
    const selectedDiv = document.getElementById('selected-shipping');
    
    let selectedShipping = null;
    const weight = {{ $weight }};

    // Load provinces on page load
    loadProvinces();

    // Province change handler
    provinceSelect.addEventListener('change', function() {
        const provinceId = this.value;
        if (provinceId) {
            loadCities(provinceId);
        } else {
            citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
            citySelect.disabled = true;
            calculateBtn.disabled = true;
        }
        resetShippingOptions();
    });

    // City change handler
    citySelect.addEventListener('change', function() {
        calculateBtn.disabled = !this.value;
        resetShippingOptions();
    });

    // Calculate shipping handler
    calculateBtn.addEventListener('click', function() {
        const cityId = citySelect.value;
        if (cityId) {
            calculateShipping(cityId);
        }
    });

    function loadProvinces() {
        fetch('/api/shipping/provinces')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data) {
                    provinceSelect.innerHTML = '<option value="">Pilih Provinsi</option>';
                    data.data.forEach(province => {
                        provinceSelect.innerHTML += `<option value="${province.province_id}">${province.province}</option>`;
                    });
                }
            })
            .catch(error => {
                console.error('Error loading provinces:', error);
                showError('Gagal memuat data provinsi');
            });
    }

    function loadCities(provinceId) {
        citySelect.disabled = true;
        citySelect.innerHTML = '<option value="">Loading...</option>';
        
        fetch(`/api/shipping/cities?province_id=${provinceId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data) {
                    citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                    data.data.forEach(city => {
                        citySelect.innerHTML += `<option value="${city.city_id}" data-postal="${city.postal_code}">${city.type} ${city.city_name}</option>`;
                    });
                    citySelect.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error loading cities:', error);
                showError('Gagal memuat data kota');
                citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                citySelect.disabled = false;
            });
    }

    function calculateShipping(cityId) {
        loadingDiv.classList.remove('hidden');
        optionsDiv.classList.add('hidden');
        calculateBtn.disabled = true;

        fetch('/api/shipping/multiple-costs', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                destination_city_id: parseInt(cityId),
                weight: weight
            })
        })
        .then(response => response.json())
        .then(data => {
            loadingDiv.classList.add('hidden');
            calculateBtn.disabled = false;
            
            if (data.success && data.data.length > 0) {
                displayShippingOptions(data.data);
            } else {
                showError('Tidak ada layanan pengiriman tersedia untuk tujuan ini');
            }
        })
        .catch(error => {
            console.error('Error calculating shipping:', error);
            loadingDiv.classList.add('hidden');
            calculateBtn.disabled = false;
            showError('Gagal menghitung ongkos kirim');
        });
    }

    function displayShippingOptions(couriers) {
        let optionsHtml = '';
        
        couriers.forEach(courier => {
            if (courier.costs && courier.costs.length > 0) {
                courier.costs.forEach(cost => {
                    const costValue = cost.cost[0].value;
                    const etd = cost.cost[0].etd;
                    const courierName = courier.name.toUpperCase();
                    const serviceName = cost.service;
                    const description = cost.description;
                    
                    optionsHtml += `
                        <div class="shipping-option border border-gray-200 rounded-md p-3 cursor-pointer hover:border-indigo-500 hover:bg-indigo-50 transition-colors" 
                             data-courier="${courier.code}" 
                             data-service="${serviceName}" 
                             data-cost="${costValue}" 
                             data-etd="${etd}"
                             data-description="${description}">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h5 class="font-medium text-gray-900">${courierName} - ${serviceName}</h5>
                                    <p class="text-sm text-gray-600">${description}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-gray-900">Rp ${new Intl.NumberFormat('id-ID').format(costValue)}</p>
                                    <p class="text-xs text-gray-500">${etd} hari</p>
                                </div>
                            </div>
                        </div>
                    `;
                });
            }
        });

        if (optionsHtml) {
            optionsDiv.innerHTML = '<h4 class="font-medium text-gray-900 mb-3">Pilih Layanan Pengiriman:</h4>' + optionsHtml;
            optionsDiv.classList.remove('hidden');
            
            // Add click handlers to shipping options
            document.querySelectorAll('.shipping-option').forEach(option => {
                option.addEventListener('click', function() {
                    // Remove previous selection
                    document.querySelectorAll('.shipping-option').forEach(opt => {
                        opt.classList.remove('border-indigo-500', 'bg-indigo-50');
                        opt.classList.add('border-gray-200');
                    });
                    
                    // Add selection to clicked option
                    this.classList.remove('border-gray-200');
                    this.classList.add('border-indigo-500', 'bg-indigo-50');
                    
                    // Store selected shipping data
                    selectedShipping = {
                        courier: this.dataset.courier,
                        service: this.dataset.service,
                        cost: parseInt(this.dataset.cost),
                        etd: this.dataset.etd,
                        description: this.dataset.description,
                        city_id: cityId,
                        province: provinceSelect.options[provinceSelect.selectedIndex].text,
                        city: citySelect.options[citySelect.selectedIndex].text
                    };
                    
                    showSelectedShipping();
                    
                    // Trigger custom event for parent components
                    document.dispatchEvent(new CustomEvent('shippingSelected', {
                        detail: selectedShipping
                    }));
                });
            });
        }
    }

    function showSelectedShipping() {
        if (selectedShipping) {
            document.getElementById('selected-shipping-info').textContent = 
                `${selectedShipping.courier.toUpperCase()} - ${selectedShipping.service} (${selectedShipping.description})`;
            document.getElementById('selected-shipping-cost').textContent = 
                `Rp ${new Intl.NumberFormat('id-ID').format(selectedShipping.cost)}`;
            document.getElementById('selected-shipping-etd').textContent = 
                `Estimasi ${selectedShipping.etd} hari`;
            selectedDiv.classList.remove('hidden');
        }
    }

    function resetShippingOptions() {
        optionsDiv.classList.add('hidden');
        selectedDiv.classList.add('hidden');
        selectedShipping = null;
        
        // Trigger event to notify parent
        document.dispatchEvent(new CustomEvent('shippingReset'));
    }

    function showError(message) {
        // You can customize this to show errors in your preferred way
        alert(message);
    }

    // Expose selected shipping data globally
    window.getSelectedShipping = function() {
        return selectedShipping;
    };
});
</script>
@endpush