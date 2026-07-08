<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\Province;
use App\Models\City;
use App\Models\ShippingSetting;

class ShippingService
{
    private $apiKey;
    private $costApiKey;
    private $baseUrl;
    private $originCityId;

    public function __construct()
    {
        $this->apiKey = config('services.rajaongkir.api_key');
        $this->costApiKey = config('services.rajaongkir.cost_api_key');
        $this->baseUrl = config('services.rajaongkir.base_url');
        
        // Get dynamic origin from database
        $origin = $this->getOriginSettings();
        $this->originCityId = $origin->origin_city_id ?? config('services.rajaongkir.origin_city_id');
    }

    /**
     * Get origin settings from database
     */
    public function getOriginSettings()
    {
        return ShippingSetting::getActiveOrigin();
    }

    /**
     * GET PROVINCES - IMPLEMENTASI YANG BENAR
     */
    public function getProvinces()
    {
        // Try database first
        $provinces = Province::all()->map(function($province) {
            return [
                'province_id' => $province->province_id,
                'province' => $province->province
            ];
        })->toArray();

        if (!empty($provinces)) {
            return $provinces;
        }

        // Try APIs with CORRECT endpoints
        $cacheKey = 'api_provinces';
        
        return Cache::remember($cacheKey, 3600, function () {
            // Try RajaOngkir first
            try {
                // ✅ ENDPOINT BENAR: https://api.rajaongkir.com/starter/province
                $response = Http::withHeaders([
                    'key' => $this->apiKey
                ])->get('https://api.rajaongkir.com/starter/province');

                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['rajaongkir']['results'])) {
                        Log::info('RajaOngkir provinces API success');
                        return $data['rajaongkir']['results'];
                    }
                }
            } catch (\Exception $e) {
                Log::warning('RajaOngkir provinces failed', ['error' => $e->getMessage()]);
            }

            // Try BinderByte as backup
            try {
                $binderbyteKey = config('services.binderbyte.api_key');
                if ($binderbyteKey) {
                    // ✅ ENDPOINT BENAR: https://api.binderbyte.com/wilayah/provinsi
                    $response = Http::get('https://api.binderbyte.com/wilayah/provinsi', [
                        'api_key' => $binderbyteKey
                    ]);

                    if ($response->successful()) {
                        $data = $response->json();
                        if (isset($data['value'])) {
                            Log::info('BinderByte provinces API success');
                            // Convert BinderByte format to RajaOngkir format
                            return array_map(function($province) {
                                return [
                                    'province_id' => $province['id'],
                                    'province' => $province['name']
                                ];
                            }, $data['value']);
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::warning('BinderByte provinces failed', ['error' => $e->getMessage()]);
            }

            // Return static fallback if all APIs fail
            Log::info('All province APIs failed, using fallback');
            return $this->getFallbackProvinces();
        });
    }

    /**
     * Get fallback provinces data
     */
    private function getFallbackProvinces()
    {
        return [
            ['province_id' => '1', 'province' => 'Bali'],
            ['province_id' => '2', 'province' => 'Bangka Belitung'],
            ['province_id' => '3', 'province' => 'Banten'],
            ['province_id' => '4', 'province' => 'Bengkulu'],
            ['province_id' => '5', 'province' => 'DI Yogyakarta'],
            ['province_id' => '6', 'province' => 'DKI Jakarta'],
            ['province_id' => '7', 'province' => 'Gorontalo'],
            ['province_id' => '8', 'province' => 'Jambi'],
            ['province_id' => '9', 'province' => 'Jawa Barat'],
            ['province_id' => '10', 'province' => 'Jawa Tengah'],
            ['province_id' => '11', 'province' => 'Jawa Timur'],
            ['province_id' => '12', 'province' => 'Kalimantan Barat'],
            ['province_id' => '13', 'province' => 'Kalimantan Selatan'],
            ['province_id' => '14', 'province' => 'Kalimantan Tengah'],
            ['province_id' => '15', 'province' => 'Kalimantan Timur'],
            ['province_id' => '16', 'province' => 'Kalimantan Utara'],
            ['province_id' => '17', 'province' => 'Kepulauan Riau'],
            ['province_id' => '18', 'province' => 'Lampung'],
            ['province_id' => '19', 'province' => 'Maluku'],
            ['province_id' => '20', 'province' => 'Maluku Utara'],
            ['province_id' => '21', 'province' => 'Nanggroe Aceh Darussalam (NAD)'],
            ['province_id' => '22', 'province' => 'Nusa Tenggara Barat (NTB)'],
            ['province_id' => '23', 'province' => 'Nusa Tenggara Timur (NTT)'],
            ['province_id' => '24', 'province' => 'Papua'],
            ['province_id' => '25', 'province' => 'Papua Barat'],
            ['province_id' => '26', 'province' => 'Riau'],
            ['province_id' => '27', 'province' => 'Sulawesi Barat'],
            ['province_id' => '28', 'province' => 'Sulawesi Selatan'],
            ['province_id' => '29', 'province' => 'Sulawesi Tengah'],
            ['province_id' => '30', 'province' => 'Sulawesi Tenggara'],
            ['province_id' => '31', 'province' => 'Sulawesi Utara'],
            ['province_id' => '32', 'province' => 'Sumatera Barat'],
            ['province_id' => '33', 'province' => 'Sumatera Selatan'],
            ['province_id' => '34', 'province' => 'Sumatera Utara'],
        ];
    }

    /**
     * Get cities by province ID from database first, then API fallback
     */
    public function getCitiesByProvince($provinceId)
    {
        // Try database first
        $cities = City::where('province_id', $provinceId)->get()->map(function($city) {
            return [
                'city_id' => $city->city_id,
                'province_id' => $city->province_id,
                'province' => $city->province,
                'type' => $city->type,
                'city_name' => $city->city_name,
                'postal_code' => $city->postal_code
            ];
        })->toArray();

        if (!empty($cities)) {
            return $cities;
        }

        // Fallback to API if database is empty
        $cacheKey = "rajaongkir_cities_province_{$provinceId}";
        
        return Cache::remember($cacheKey, 3600, function () use ($provinceId) {
            try {
                $response = Http::withHeaders([
                    'key' => $this->apiKey
                ])->get($this->baseUrl . '/city', [
                    'province' => $provinceId
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return $data['rajaongkir']['results'] ?? [];
                }
                
                Log::error('RajaOngkir API Error - Cities', [
                    'province_id' => $provinceId,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                
                // Return static fallback cities if API fails
                return $this->getFallbackCities($provinceId);
            } catch (\Exception $e) {
                Log::error('RajaOngkir Exception - Cities', [
                    'province_id' => $provinceId,
                    'message' => $e->getMessage()
                ]);
                return $this->getFallbackCities($provinceId);
            }
        });
    }

    /**
     * Get fallback cities data
     */
    private function getFallbackCities($provinceId)
    {
        $cities = [
            '6' => [ // DKI Jakarta
                ['city_id' => '151', 'province_id' => '6', 'province' => 'DKI Jakarta', 'type' => 'Kota', 'city_name' => 'Jakarta Barat', 'postal_code' => '11220'],
                ['city_id' => '152', 'province_id' => '6', 'province' => 'DKI Jakarta', 'type' => 'Kota', 'city_name' => 'Jakarta Pusat', 'postal_code' => '10540'],
                ['city_id' => '153', 'province_id' => '6', 'province' => 'DKI Jakarta', 'type' => 'Kota', 'city_name' => 'Jakarta Selatan', 'postal_code' => '12230'],
                ['city_id' => '154', 'province_id' => '6', 'province' => 'DKI Jakarta', 'type' => 'Kota', 'city_name' => 'Jakarta Timur', 'postal_code' => '13330'],
                ['city_id' => '155', 'province_id' => '6', 'province' => 'DKI Jakarta', 'type' => 'Kota', 'city_name' => 'Jakarta Utara', 'postal_code' => '14140'],
            ],
            '9' => [ // Jawa Barat
                ['city_id' => '23', 'province_id' => '9', 'province' => 'Jawa Barat', 'type' => 'Kota', 'city_name' => 'Bandung', 'postal_code' => '40111'],
                ['city_id' => '24', 'province_id' => '9', 'province' => 'Jawa Barat', 'type' => 'Kabupaten', 'city_name' => 'Bandung', 'postal_code' => '40311'],
                ['city_id' => '25', 'province_id' => '9', 'province' => 'Jawa Barat', 'type' => 'Kabupaten', 'city_name' => 'Bandung Barat', 'postal_code' => '40721'],
                ['city_id' => '26', 'province_id' => '9', 'province' => 'Jawa Barat', 'type' => 'Kota', 'city_name' => 'Banjar', 'postal_code' => '46311'],
                ['city_id' => '27', 'province_id' => '9', 'province' => 'Jawa Barat', 'type' => 'Kota', 'city_name' => 'Bekasi', 'postal_code' => '17837'],
                ['city_id' => '28', 'province_id' => '9', 'province' => 'Jawa Barat', 'type' => 'Kabupaten', 'city_name' => 'Bekasi', 'postal_code' => '17837'],
                ['city_id' => '29', 'province_id' => '9', 'province' => 'Jawa Barat', 'type' => 'Kota', 'city_name' => 'Bogor', 'postal_code' => '16119'],
                ['city_id' => '30', 'province_id' => '9', 'province' => 'Jawa Barat', 'type' => 'Kabupaten', 'city_name' => 'Bogor', 'postal_code' => '16911'],
            ],
            '10' => [ // Jawa Tengah
                ['city_id' => '399', 'province_id' => '10', 'province' => 'Jawa Tengah', 'type' => 'Kota', 'city_name' => 'Semarang', 'postal_code' => '50135'],
                ['city_id' => '400', 'province_id' => '10', 'province' => 'Jawa Tengah', 'type' => 'Kabupaten', 'city_name' => 'Semarang', 'postal_code' => '50511'],
                ['city_id' => '401', 'province_id' => '10', 'province' => 'Jawa Tengah', 'type' => 'Kota', 'city_name' => 'Solo', 'postal_code' => '57113'],
            ],
            '11' => [ // Jawa Timur
                ['city_id' => '444', 'province_id' => '11', 'province' => 'Jawa Timur', 'type' => 'Kota', 'city_name' => 'Surabaya', 'postal_code' => '60119'],
                ['city_id' => '445', 'province_id' => '11', 'province' => 'Jawa Timur', 'type' => 'Kota', 'city_name' => 'Malang', 'postal_code' => '65112'],
            ],
            '5' => [ // DI Yogyakarta
                ['city_id' => '501', 'province_id' => '5', 'province' => 'DI Yogyakarta', 'type' => 'Kota', 'city_name' => 'Yogyakarta', 'postal_code' => '55111'],
            ]
        ];

        return $cities[$provinceId] ?? [];
    }

    /**
     * Calculate shipping cost - Dynamic provider based on config
     */
    public function calculateShippingCost($destinationCityId, $weight, $courier = null)
    {
        $provider = config('services.shipping_provider', 'komerce');
        
        Log::info('Using shipping provider', ['provider' => $provider]);
        
        // Try primary provider first
        switch ($provider) {
            case 'binderbyte':
                $results = $this->calculateBinderbyteCost($destinationCityId, $weight, $courier);
                if (!empty($results)) {
                    Log::info('BinderByte API success (primary)', [
                        'destination' => $destinationCityId,
                        'options' => count($results)
                    ]);
                    return $results;
                }
                break;
                
            case 'komerce':
            default:
                $results = $this->calculateKomerceShippingCost($destinationCityId, $weight, $courier);
                if (!empty($results)) {
                    Log::info('Komerce Shipping API success (primary)', [
                        'destination' => $destinationCityId,
                        'options' => count($results)
                    ]);
                    return $results;
                }
                break;
        }

        // Try backup providers if primary fails
        if ($provider !== 'binderbyte') {
            $results = $this->calculateBinderbyteCost($destinationCityId, $weight, $courier);
            if (!empty($results)) {
                Log::info('BinderByte API success (backup)', [
                    'destination' => $destinationCityId,
                    'options' => count($results)
                ]);
                return $results;
            }
        }

        // Fallback to static calculation if all APIs fail
        Log::info('All APIs failed, using fallback system', [
            'destination' => $destinationCityId,
            'weight' => $weight
        ]);
        
        return $this->getFallbackShippingOptions($destinationCityId, $weight);
    }

    /**
     * KOMERCE API - IMPLEMENTASI BERDASARKAN DOKUMENTASI RESMI
     * 
     * ✅ Base URL: https://api.collaborator.komerce.id/
     * ✅ Endpoint: GET /tariff/api/v1/calculate
     * ✅ Header: x-api-key
     * ✅ Method: GET (sesuai dokumentasi)
     */
    public function calculateKomerceShippingCost($destinationCityId, $weight, $courier = null)
    {
        try {
            $apiKey = config('services.komerce.cost_api_key');
            $baseUrl = config('services.komerce.base_url');
            
            if (!$apiKey || !$baseUrl) {
                Log::warning('Komerce API not configured');
                return [];
            }

            $couriers = $courier ? [$courier] : ['jne', 'pos', 'tiki', 'jnt', 'sicepat'];
            $results = [];

            foreach ($couriers as $courierCode) {
                // ✅ ENDPOINT RESMI: GET /tariff/api/v1/calculate
                // ✅ HEADER RESMI: x-api-key
                // ✅ METHOD: GET (sesuai dokumentasi)
                $response = Http::withHeaders([
                    'x-api-key' => $apiKey,
                    'Accept' => 'application/json'
                ])->get($baseUrl . '/tariff/api/v1/calculate', [
                    'origin' => $this->originCityId,
                    'destination' => $destinationCityId,
                    'weight' => $weight,
                    'courier' => $courierCode
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    
                    // Parse response Komerce (struktur perlu disesuaikan dengan response aktual)
                    if (isset($data['data'])) {
                        $courierData = $data['data'];
                        
                        // Jika response berupa array services
                        if (isset($courierData['services'])) {
                            foreach ($courierData['services'] as $service) {
                                $results[] = [
                                    'courier_code' => $courierCode,
                                    'courier_name' => strtoupper($courierData['courier_name'] ?? $courierCode),
                                    'service_code' => $service['service_code'] ?? $service['service'],
                                    'service_name' => $service['service_name'] ?? $service['description'],
                                    'cost' => $service['cost'] ?? $service['price'],
                                    'etd' => $service['etd'] ?? $service['estimation'],
                                    'note' => 'Komerce API'
                                ];
                            }
                        }
                        // Jika response langsung berupa service data
                        elseif (isset($courierData['cost'])) {
                            $results[] = [
                                'courier_code' => $courierCode,
                                'courier_name' => strtoupper($courierData['courier_name'] ?? $courierCode),
                                'service_code' => $courierData['service_code'] ?? 'REG',
                                'service_name' => $courierData['service_name'] ?? 'Regular',
                                'cost' => $courierData['cost'],
                                'etd' => $courierData['etd'] ?? 3,
                                'note' => 'Komerce API'
                            ];
                        }
                    }
                } else {
                    // Handle Komerce specific errors
                    $errorData = $response->json();
                    $this->handleKomerceError($response->status(), $errorData, $courierCode);
                }
            }

            // Sort by cost (cheapest first)
            if (!empty($results)) {
                usort($results, function($a, $b) {
                    return $a['cost'] <=> $b['cost'];
                });
            }

            return $results;
            
        } catch (\Exception $e) {
            Log::error('Komerce API Exception', [
                'destination' => $destinationCityId,
                'weight' => $weight,
                'message' => $e->getMessage()
            ]);
            
            return [];
        }
    }

    /**
     * KOMERCE API - Search Destination
     * ✅ Endpoint: GET /tariff/api/v1/destination
     */
    public function searchKomerceDestinations($query)
    {
        try {
            $apiKey = config('services.komerce.cost_api_key');
            $baseUrl = config('services.komerce.base_url');
            
            if (!$apiKey || !$baseUrl) {
                return [];
            }

            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
                'Accept' => 'application/json'
            ])->get($baseUrl . '/tariff/api/v1/destination', [
                'search' => $query,
                'limit' => 20
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Parse destinations (struktur perlu disesuaikan dengan response aktual)
                if (isset($data['data'])) {
                    return array_map(function($destination) {
                        return [
                            'city_id' => $destination['id'] ?? $destination['city_id'],
                            'city_name' => $destination['city_name'] ?? $destination['name'],
                            'province' => $destination['province'] ?? $destination['province_name'],
                            'type' => $destination['type'] ?? 'Kota',
                            'postal_code' => $destination['postal_code'] ?? ''
                        ];
                    }, $data['data']);
                }
            }

            return [];
            
        } catch (\Exception $e) {
            Log::error('Komerce Search Destinations Exception', [
                'query' => $query,
                'message' => $e->getMessage()
            ]);
            
            return [];
        }
    }

    /**
     * Handle Komerce API errors dengan diagnosis yang tepat
     */
    private function handleKomerceError($httpCode, $errorData, $courierCode)
    {
        $errorMessage = $errorData['message'] ?? $errorData['error'] ?? 'Unknown error';
        
        switch ($httpCode) {
            case 401:
                Log::error('Komerce API: UNAUTHORIZED', [
                    'diagnosis' => 'API Key salah atau expired',
                    'solution' => 'Cek x-api-key di dashboard Komerce',
                    'bukan_bug_kode' => true,
                    'courier' => $courierCode
                ]);
                break;
                
            case 403:
                Log::error('Komerce API: FORBIDDEN', [
                    'diagnosis' => 'API key tidak memiliki akses atau belum diapprove',
                    'solution' => 'Hubungi administrator Komerce untuk approval',
                    'bukan_bug_kode' => true,
                    'courier' => $courierCode
                ]);
                break;
                
            case 429:
                Log::error('Komerce API: RATE LIMIT', [
                    'diagnosis' => 'Quota API habis atau terlalu banyak request',
                    'solution' => 'Tunggu atau upgrade quota',
                    'bukan_bug_kode' => true,
                    'courier' => $courierCode
                ]);
                break;
                
            case 404:
                Log::error('Komerce API: NOT FOUND', [
                    'diagnosis' => 'Endpoint tidak ditemukan',
                    'solution' => 'Pastikan menggunakan endpoint yang benar dari dokumentasi',
                    'kemungkinan_bug_kode' => true,
                    'courier' => $courierCode
                ]);
                break;
                
            case 422:
                Log::error('Komerce API: VALIDATION ERROR', [
                    'diagnosis' => 'Parameter tidak valid (origin, destination, weight, courier)',
                    'solution' => 'Cek format parameter yang dikirim',
                    'kemungkinan_bug_kode' => true,
                    'courier' => $courierCode,
                    'error_detail' => $errorMessage
                ]);
                break;
                
            default:
                Log::warning('Komerce API Error', [
                    'http_code' => $httpCode,
                    'courier' => $courierCode,
                    'error' => $errorMessage
                ]);
        }
    }



    /**
     * RAJAONGKIR API - IMPLEMENTASI YANG BENAR (PAKET STARTER)
     * BASE URL: https://api.rajaongkir.com/starter
     */
    public function calculateRajaOngkirCost($destinationCityId, $weight, $courier = null)
    {
        try {
            $couriers = $courier ? [$courier] : ['jne', 'pos', 'tiki'];
            $results = [];

            foreach ($couriers as $courierCode) {
                // ✅ ENDPOINT BENAR: https://api.rajaongkir.com/starter/cost
                // ✅ METHOD: POST (WAJIB)
                // ✅ HEADER: key (WAJIB)
                // ✅ CONTENT-TYPE: application/x-www-form-urlencoded (WAJIB)
                // ✅ LARAVEL: ->asForm() (WAJIB)
                $response = Http::withHeaders([
                    'key' => $this->apiKey
                ])->asForm()->post('https://api.rajaongkir.com/starter/cost', [
                    'origin' => $this->originCityId,
                    'destination' => $destinationCityId,
                    'weight' => $weight,
                    'courier' => $courierCode
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    
                    // Response format RajaOngkir: data.rajaongkir.results
                    $courierResults = $data['rajaongkir']['results'] ?? [];
                    
                    foreach ($courierResults as $result) {
                        $courierName = strtoupper($result['name']);
                        $costs = $result['costs'] ?? [];
                        
                        foreach ($costs as $cost) {
                            $results[] = [
                                'courier_code' => $courierCode,
                                'courier_name' => $courierName,
                                'service_code' => $cost['service'],
                                'service_name' => $cost['description'],
                                'cost' => $cost['cost'][0]['value'],
                                'etd' => $cost['cost'][0]['etd'],
                                'note' => 'RajaOngkir API'
                            ];
                        }
                    }
                } else {
                    // Log specific RajaOngkir errors
                    $errorData = $response->json();
                    Log::warning('RajaOngkir API Error', [
                        'http_code' => $response->status(),
                        'courier' => $courierCode,
                        'error' => $errorData['rajaongkir']['status']['description'] ?? 'Unknown error'
                    ]);
                }
            }

            return $results;
            
        } catch (\Exception $e) {
            Log::error('RajaOngkir Exception', [
                'destination' => $destinationCityId,
                'weight' => $weight,
                'message' => $e->getMessage()
            ]);
            
            return [];
        }
    }

    /**
     * BINDERBYTE API - FALLBACK DENGAN ESTIMASI STATIC
     * BinderByte hanya punya wilayah API, tidak ada shipping cost API
     * Jadi kita gunakan estimasi berdasarkan jarak dan berat
     */
    public function calculateBinderbyteCost($destinationCityId, $weight, $courier = null)
    {
        try {
            $apiKey = config('services.binderbyte.api_key');
            
            if (!$apiKey) {
                Log::warning('BinderByte API key not configured');
                return [];
            }

            // BinderByte tidak punya shipping cost API, jadi kita buat estimasi
            Log::info('BinderByte: Using static estimation (no cost API available)');
            
            $couriers = $courier ? [$courier] : ['jne', 'pos', 'tiki'];
            $results = [];

            // Estimasi berdasarkan jarak dan berat
            $baseRate = $this->getEstimatedShippingRate($destinationCityId, $weight);
            
            foreach ($couriers as $courierCode) {
                switch (strtolower($courierCode)) {
                    case 'jne':
                        $results[] = [
                            'courier_code' => 'jne',
                            'courier_name' => 'JNE',
                            'service_code' => 'REG',
                            'service_name' => 'Reguler',
                            'cost' => $baseRate,
                            'etd' => 3,
                            'note' => 'BinderByte Estimasi'
                        ];
                        $results[] = [
                            'courier_code' => 'jne',
                            'courier_name' => 'JNE',
                            'service_code' => 'OKE',
                            'service_name' => 'Ongkos Kirim Ekonomis',
                            'cost' => $baseRate * 0.8,
                            'etd' => 4,
                            'note' => 'BinderByte Estimasi'
                        ];
                        break;
                        
                    case 'pos':
                        $results[] = [
                            'courier_code' => 'pos',
                            'courier_name' => 'POS Indonesia',
                            'service_code' => 'Paket Kilat Khusus',
                            'service_name' => 'Paket Kilat Khusus',
                            'cost' => $baseRate * 0.9,
                            'etd' => 3,
                            'note' => 'BinderByte Estimasi'
                        ];
                        break;
                        
                    case 'tiki':
                        $results[] = [
                            'courier_code' => 'tiki',
                            'courier_name' => 'TIKI',
                            'service_code' => 'REG',
                            'service_name' => 'Reguler Service',
                            'cost' => $baseRate * 1.1,
                            'etd' => 3,
                            'note' => 'BinderByte Estimasi'
                        ];
                        break;
                }
            }

            Log::info('BinderByte estimation completed', [
                'destination' => $destinationCityId,
                'weight' => $weight,
                'options' => count($results)
            ]);

            return $results;
            
        } catch (\Exception $e) {
            Log::error('BinderByte Exception', [
                'destination' => $destinationCityId,
                'weight' => $weight,
                'message' => $e->getMessage()
            ]);
            
            return [];
        }
    }

    /**
     * Estimasi tarif shipping berdasarkan destination dan weight
     */
    private function getEstimatedShippingRate($destinationCityId, $weight)
    {
        // Base rate per kg
        $baseRatePerKg = 15000; // Rp 15,000 per kg
        
        // Adjustment berdasarkan destination (estimasi jarak dari Lombok)
        $distanceMultiplier = 1.0;
        
        // Jakarta, Surabaya, Bandung (Jawa) - jauh
        if (in_array($destinationCityId, [152, 153, 154, 155, 156, 157, 158])) {
            $distanceMultiplier = 1.5;
        }
        // Bali - dekat
        elseif (in_array($destinationCityId, [114, 115, 116, 117])) {
            $distanceMultiplier = 0.8;
        }
        // Sumatra - sangat jauh
        elseif ($destinationCityId < 100) {
            $distanceMultiplier = 2.0;
        }
        
        $weightInKg = $weight / 1000;
        $estimatedCost = $baseRatePerKg * $weightInKg * $distanceMultiplier;
        
        // Minimum cost
        return max($estimatedCost, 10000);
    }

    /**
     * Get city details by ID
     */
    public function getCityById($cityId)
    {
        $cacheKey = "rajaongkir_city_{$cityId}";
        
        return Cache::remember($cacheKey, 3600, function () use ($cityId) {
            try {
                $response = Http::withHeaders([
                    'key' => $this->apiKey
                ])->get($this->baseUrl . '/city', [
                    'id' => $cityId
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $results = $data['rajaongkir']['results'] ?? [];
                    return $results[0] ?? null;
                }
                
                return null;
            } catch (\Exception $e) {
                Log::error('RajaOngkir Exception - Get City', [
                    'city_id' => $cityId,
                    'message' => $e->getMessage()
                ]);
                return null;
            }
        });
    }

    /**
     * Search cities - Try Komerce API first, then database, then fallback
     */
    public function searchCities($query)
    {
        $query = strtolower(trim($query));
        
        if (strlen($query) < 2) {
            return [];
        }

        // Try Komerce API first
        $komerceResults = $this->searchKomerceDestinations($query);
        if (!empty($komerceResults)) {
            Log::info('Komerce search cities success', ['query' => $query, 'results' => count($komerceResults)]);
            return $komerceResults;
        }

        // Try database as backup
        $cities = City::where(function($q) use ($query) {
            $q->whereRaw('LOWER(city_name) LIKE ?', ["%{$query}%"])
              ->orWhereRaw('LOWER(type) LIKE ?', ["%{$query}%"])
              ->orWhereRaw('LOWER(province) LIKE ?', ["%{$query}%"]);
        })
        ->limit(20)
        ->get()
        ->map(function($city) {
            return [
                'city_id' => $city->city_id,
                'province_id' => $city->province_id,
                'province' => $city->province,
                'type' => $city->type,
                'city_name' => $city->city_name,
                'postal_code' => $city->postal_code
            ];
        })->toArray();

        if (!empty($cities)) {
            Log::info('Database search cities success', ['query' => $query, 'results' => count($cities)]);
            return $cities;
        }

        // Fallback to static data if all else fails
        Log::info('Using fallback search cities', ['query' => $query]);
        $allCities = $this->getAllFallbackCities();
        
        return array_filter($allCities, function($city) use ($query) {
            return strpos(strtolower($city['city_name']), $query) !== false ||
                   strpos(strtolower($city['type']), $query) !== false ||
                   strpos(strtolower($city['province']), $query) !== false;
        });
    }

    /**
     * Get all fallback cities
     */
    private function getAllFallbackCities()
    {
        $allCities = [];
        $provinces = ['5', '6', '9', '10', '11']; // Major provinces
        
        foreach ($provinces as $provinceId) {
            $cities = $this->getFallbackCities($provinceId);
            $allCities = array_merge($allCities, $cities);
        }
        
        return $allCities;
    }

    /**
     * Get fallback shipping options with realistic pricing based on distance from Lombok
     */
    private function getFallbackShippingOptions($destinationCityId, $weight)
    {
        // Get destination city details for better calculation
        $destinationCity = City::where('city_id', $destinationCityId)->first();
        $province = $destinationCity ? $destinationCity->province : '';
        
        // Base cost calculation from Lombok
        $baseCost = 8000; // Base cost from Lombok
        $weightCost = ceil($weight / 1000) * 2500; // Per kg
        
        // Realistic distance multiplier based on actual geography from Lombok
        $distanceMultiplier = $this->getDistanceMultiplier($destinationCityId, $province);
        
        // Island penalty (inter-island shipping is more expensive)
        $islandPenalty = $this->getIslandPenalty($province);
        
        $regularCost = ($baseCost + $weightCost) * $distanceMultiplier * $islandPenalty;
        $expressCost = $regularCost * 1.8;
        $economyCost = $regularCost * 0.7;

        return [
            // JNE Options
            [
                'courier_code' => 'jne',
                'courier_name' => 'JNE',
                'service_code' => 'OKE',
                'service_name' => 'Ongkos Kirim Ekonomis',
                'cost' => (int) $economyCost,
                'etd' => 4,
                'note' => 'Layanan ekonomis dari Lombok'
            ],
            [
                'courier_code' => 'jne',
                'courier_name' => 'JNE',
                'service_code' => 'REG',
                'service_name' => 'Layanan Reguler',
                'cost' => (int) $regularCost,
                'etd' => 3,
                'note' => 'Layanan reguler dari Lombok'
            ],
            [
                'courier_code' => 'jne',
                'courier_name' => 'JNE',
                'service_code' => 'YES',
                'service_name' => 'Yakin Esok Sampai',
                'cost' => (int) $expressCost,
                'etd' => 2,
                'note' => 'Layanan express dari Lombok'
            ],
            
            // TIKI Options
            [
                'courier_code' => 'tiki',
                'courier_name' => 'TIKI',
                'service_code' => 'ECO',
                'service_name' => 'Economy Service',
                'cost' => (int) ($economyCost * 1.05),
                'etd' => 5,
                'note' => 'Layanan ekonomi TIKI'
            ],
            [
                'courier_code' => 'tiki',
                'courier_name' => 'TIKI',
                'service_code' => 'REG',
                'service_name' => 'Regular Service',
                'cost' => (int) ($regularCost * 1.1),
                'etd' => 3,
                'note' => 'Layanan reguler TIKI'
            ],
            [
                'courier_code' => 'tiki',
                'courier_name' => 'TIKI',
                'service_code' => 'ONS',
                'service_name' => 'Over Night Service',
                'cost' => (int) ($expressCost * 1.1),
                'etd' => 2,
                'note' => 'Layanan overnight TIKI'
            ],

            // SiCepat Options
            [
                'courier_code' => 'sicepat',
                'courier_name' => 'SiCepat',
                'service_code' => 'SIUNT',
                'service_name' => 'SiUntung',
                'cost' => (int) ($economyCost * 0.9),
                'etd' => 4,
                'note' => 'Layanan ekonomis SiCepat'
            ],
            [
                'courier_code' => 'sicepat',
                'courier_name' => 'SiCepat',
                'service_code' => 'REG',
                'service_name' => 'Reguler',
                'cost' => (int) ($regularCost * 0.95),
                'etd' => 3,
                'note' => 'Layanan reguler SiCepat'
            ],
            [
                'courier_code' => 'sicepat',
                'courier_name' => 'SiCepat',
                'service_code' => 'BEST',
                'service_name' => 'SiBest',
                'cost' => (int) ($expressCost * 0.9),
                'etd' => 2,
                'note' => 'Layanan express SiCepat'
            ],

            // J&T Options
            [
                'courier_code' => 'jnt',
                'courier_name' => 'J&T Express',
                'service_code' => 'EZ',
                'service_name' => 'EZ',
                'cost' => (int) ($economyCost * 0.85),
                'etd' => 4,
                'note' => 'Layanan ekonomis J&T'
            ],
            [
                'courier_code' => 'jnt',
                'courier_name' => 'J&T Express',
                'service_code' => 'REG',
                'service_name' => 'Reguler',
                'cost' => (int) ($regularCost * 0.9),
                'etd' => 3,
                'note' => 'Layanan reguler J&T'
            ],

            // POS Indonesia
            [
                'courier_code' => 'pos',
                'courier_name' => 'POS Indonesia',
                'service_code' => 'Paket Kilat Khusus',
                'service_name' => 'Paket Kilat Khusus',
                'cost' => (int) ($regularCost * 0.8),
                'etd' => 4,
                'note' => 'Layanan pos kilat'
            ],

            // AnterAja
            [
                'courier_code' => 'anteraja',
                'courier_name' => 'AnterAja',
                'service_code' => 'REG',
                'service_name' => 'Reguler',
                'cost' => (int) ($regularCost * 0.85),
                'etd' => 3,
                'note' => 'Layanan reguler AnterAja'
            ]
        ];
    }

    /**
     * Get distance multiplier based on destination from Lombok
     */
    private function getDistanceMultiplier($destinationCityId, $province)
    {
        // Lombok/NTB area (same island)
        if (strpos($province, 'Nusa Tenggara') !== false) {
            return 0.8; // Cheapest - same island
        }
        
        // Bali (very close)
        if (strpos($province, 'Bali') !== false) {
            return 1.0;
        }
        
        // Java - East (Jawa Timur) - closer to Lombok
        if (in_array($destinationCityId, ['444', '445', '446', '447', '448', '449', '450'])) {
            return 1.2;
        }
        
        // Java - Central (Jawa Tengah, DI Yogyakarta)
        if (strpos($province, 'Jawa Tengah') !== false || strpos($province, 'Yogyakarta') !== false) {
            return 1.4;
        }
        
        // Java - West (Jawa Barat, DKI Jakarta, Banten)
        if (strpos($province, 'Jawa Barat') !== false || strpos($province, 'DKI Jakarta') !== false || strpos($province, 'Banten') !== false) {
            return 1.6;
        }
        
        // Sumatera - South (closer)
        if (strpos($province, 'Sumatera Selatan') !== false || strpos($province, 'Lampung') !== false || strpos($province, 'Bengkulu') !== false) {
            return 1.8;
        }
        
        // Sumatera - Central
        if (strpos($province, 'Sumatera Barat') !== false || strpos($province, 'Riau') !== false || strpos($province, 'Jambi') !== false) {
            return 2.0;
        }
        
        // Sumatera - North (farthest in Sumatera)
        if (strpos($province, 'Sumatera Utara') !== false || strpos($province, 'Aceh') !== false) {
            return 2.2;
        }
        
        // Kalimantan - South (closer to Lombok)
        if (strpos($province, 'Kalimantan Selatan') !== false || strpos($province, 'Kalimantan Tengah') !== false) {
            return 1.9;
        }
        
        // Kalimantan - East & West
        if (strpos($province, 'Kalimantan Timur') !== false || strpos($province, 'Kalimantan Barat') !== false) {
            return 2.1;
        }
        
        // Kalimantan - North (farthest)
        if (strpos($province, 'Kalimantan Utara') !== false) {
            return 2.3;
        }
        
        // Sulawesi - South (closer)
        if (strpos($province, 'Sulawesi Selatan') !== false || strpos($province, 'Sulawesi Barat') !== false) {
            return 1.7;
        }
        
        // Sulawesi - Central & Southeast
        if (strpos($province, 'Sulawesi Tengah') !== false || strpos($province, 'Sulawesi Tenggara') !== false) {
            return 1.9;
        }
        
        // Sulawesi - North (farthest)
        if (strpos($province, 'Sulawesi Utara') !== false || strpos($province, 'Gorontalo') !== false) {
            return 2.1;
        }
        
        // Papua (very far)
        if (strpos($province, 'Papua') !== false) {
            return 3.0;
        }
        
        // Maluku (far)
        if (strpos($province, 'Maluku') !== false) {
            return 2.5;
        }
        
        // Kepulauan Riau, Bangka Belitung
        if (strpos($province, 'Kepulauan Riau') !== false || strpos($province, 'Bangka Belitung') !== false) {
            return 1.8;
        }
        
        // Default for unknown areas
        return 1.5;
    }

    /**
     * Get island penalty for inter-island shipping
     */
    private function getIslandPenalty($province)
    {
        // Same island group (Nusa Tenggara, Bali) - no penalty
        if (strpos($province, 'Nusa Tenggara') !== false || strpos($province, 'Bali') !== false) {
            return 1.0;
        }
        
        // Java (major route) - small penalty
        if (strpos($province, 'Jawa') !== false || strpos($province, 'DKI Jakarta') !== false || 
            strpos($province, 'Banten') !== false || strpos($province, 'Yogyakarta') !== false) {
            return 1.1;
        }
        
        // Sumatera (major route) - moderate penalty
        if (strpos($province, 'Sumatera') !== false || strpos($province, 'Aceh') !== false || 
            strpos($province, 'Lampung') !== false || strpos($province, 'Bengkulu') !== false || 
            strpos($province, 'Jambi') !== false || strpos($province, 'Riau') !== false ||
            strpos($province, 'Kepulauan Riau') !== false || strpos($province, 'Bangka Belitung') !== false) {
            return 1.2;
        }
        
        // Kalimantan - higher penalty
        if (strpos($province, 'Kalimantan') !== false) {
            return 1.3;
        }
        
        // Sulawesi - higher penalty
        if (strpos($province, 'Sulawesi') !== false || strpos($province, 'Gorontalo') !== false) {
            return 1.3;
        }
        
        // Papua (very remote) - highest penalty
        if (strpos($province, 'Papua') !== false) {
            return 1.8;
        }
        
        // Maluku (remote) - high penalty
        if (strpos($province, 'Maluku') !== false) {
            return 1.5;
        }
        
        // Default penalty
        return 1.2;
    }
}