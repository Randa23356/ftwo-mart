<?php

namespace App\Http\Controllers;

use App\Services\ShippingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShippingController extends Controller
{
    protected $shippingService;

    public function __construct(ShippingService $shippingService)
    {
        $this->shippingService = $shippingService;
    }

    /**
     * Get provinces for dropdown
     */
    public function getProvinces()
    {
        try {
            $provinces = $this->shippingService->getProvinces();
            
            return response()->json([
                'success' => true,
                'data' => $provinces
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting provinces', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data provinsi'
            ], 500);
        }
    }

    /**
     * Get cities by province
     */
    public function getCities(Request $request)
    {
        $request->validate([
            'province_id' => 'required|integer'
        ]);

        try {
            $cities = $this->shippingService->getCitiesByProvince($request->province_id);
            
            return response()->json([
                'success' => true,
                'data' => $cities
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting cities', [
                'province_id' => $request->province_id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kota'
            ], 500);
        }
    }

    /**
     * Calculate shipping cost
     */
    public function calculateCost(Request $request)
    {
        $request->validate([
            'destination_city_id' => 'required|integer',
            'weight' => 'required|integer|min:1',
            'courier' => 'nullable|string|in:jne,pos,tiki'
        ]);

        try {
            $shippingOptions = $this->shippingService->calculateShippingCost(
                $request->destination_city_id,
                $request->weight,
                $request->courier
            );

            // Get city details for display
            $cityDetails = $this->shippingService->getCityById($request->destination_city_id);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'shipping_options' => $shippingOptions,
                    'destination' => $cityDetails,
                    'weight' => $request->weight
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error calculating shipping cost', [
                'destination_city_id' => $request->destination_city_id,
                'weight' => $request->weight,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghitung ongkos kirim'
            ], 500);
        }
    }

    /**
     * Search cities
     */
    public function searchCities(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2'
        ]);

        try {
            $cities = $this->shippingService->searchCities($request->q);
            
            // Limit results to 20 for performance
            $cities = array_slice($cities, 0, 20);
            
            return response()->json([
                'success' => true,
                'data' => $cities
            ]);
        } catch (\Exception $e) {
            Log::error('Error searching cities', [
                'query' => $request->q,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mencari kota'
            ], 500);
        }
    }
}