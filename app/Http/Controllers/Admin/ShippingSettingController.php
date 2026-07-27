<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingSetting;
use App\Models\ShippingProvinceMultiplier;
use App\Models\City;
use App\Services\ShippingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ShippingSettingController extends Controller
{
    protected $shippingService;

    public function __construct(ShippingService $shippingService)
    {
        $this->shippingService = $shippingService;
    }

    /**
     * Display shipping settings
     */
    public function index()
    {
        $setting = ShippingSetting::getActiveOrigin();
        $cities = City::orderBy('province')->orderBy('city_name')->get();
        $multipliers = ShippingProvinceMultiplier::orderBy('province_name')->get();
        
        return view('admin.shipping.index', compact('setting', 'cities', 'multipliers'));
    }

    /**
     * Update shipping settings
     */
    public function update(Request $request)
    {
        $request->validate([
            'origin_city_id' => 'required|exists:cities,city_id',
            'warehouse_name' => 'required|string|max:255',
            'warehouse_address' => 'nullable|string|max:500',
            'contact_phone' => 'nullable|string|max:20',
            'base_cost' => 'required|integer|min:0',
            'cost_per_kg' => 'required|integer|min:0',
        ]);

        // Get city details
        $city = City::where('city_id', $request->origin_city_id)->first();
        
        if (!$city) {
            return back()->withErrors(['origin_city_id' => 'Kota tidak ditemukan']);
        }

        // Update or create shipping setting
        $setting = ShippingSetting::first();
        
        if ($setting) {
            $setting->update([
                'origin_city_id' => $city->city_id,
                'origin_city_name' => $city->type . ' ' . $city->city_name,
                'origin_province' => $city->province,
                'origin_postal_code' => $city->postal_code ?? '',
                'warehouse_name' => $request->warehouse_name,
                'warehouse_address' => $request->warehouse_address,
                'contact_phone' => $request->contact_phone,
                'base_cost' => $request->base_cost,
                'cost_per_kg' => $request->cost_per_kg,
                'is_active' => true,
            ]);
        } else {
            ShippingSetting::create([
                'origin_city_id' => $city->city_id,
                'origin_city_name' => $city->type . ' ' . $city->city_name,
                'origin_province' => $city->province,
                'origin_postal_code' => $city->postal_code ?? '',
                'warehouse_name' => $request->warehouse_name,
                'warehouse_address' => $request->warehouse_address,
                'contact_phone' => $request->contact_phone,
                'base_cost' => $request->base_cost,
                'cost_per_kg' => $request->cost_per_kg,
                'is_active' => true,
            ]);
        }

        // Clear cache
        Cache::forget('shipping_origin');

        return redirect()->route('admin.shipping.index')->with('success', 'Pengaturan pengiriman berhasil diperbarui!');
    }

    /**
     * Test shipping calculation with new origin
     */
    public function testShipping(Request $request)
    {
        $request->validate([
            'test_destination_city_id' => 'required|exists:cities,city_id',
            'test_weight' => 'required|integer|min:100|max:30000',
        ]);

        try {
            $shippingOptions = $this->shippingService->calculateShippingCost(
                $request->test_destination_city_id,
                $request->test_weight
            );

            $destinationCity = City::where('city_id', $request->test_destination_city_id)->first();
            $originSetting = ShippingSetting::getActiveOrigin();

            return response()->json([
                'success' => true,
                'data' => [
                    'origin' => [
                        'city' => $originSetting->origin_city_name,
                        'province' => $originSetting->origin_province,
                        'postal_code' => $originSetting->origin_postal_code,
                    ],
                    'destination' => [
                        'city' => $destinationCity->type . ' ' . $destinationCity->city_name,
                        'province' => $destinationCity->province,
                        'postal_code' => $destinationCity->postal_code,
                    ],
                    'weight' => $request->test_weight,
                    'shipping_options' => array_slice($shippingOptions, 0, 8), // Limit to 8 options
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghitung ongkir: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get current origin info
     */
    public function getOriginInfo()
    {
        $setting = ShippingSetting::getActiveOrigin();
        
        return response()->json([
            'success' => true,
            'data' => $setting
        ]);
    }

    /**
     * Store new province multiplier
     */
    public function storeMultiplier(Request $request)
    {
        $request->validate([
            'province_name' => 'required|string|unique:shipping_province_multipliers,province_name',
            'distance_multiplier' => 'required|numeric|min:0.1',
        ], [
            'province_name.unique' => 'Provinsi ini sudah memiliki pengaturan pengali jarak.'
        ]);

        ShippingProvinceMultiplier::create([
            'province_name' => $request->province_name,
            'distance_multiplier' => $request->distance_multiplier,
        ]);

        Cache::forget('shipping_origin'); // You might want to cache multipliers too if needed

        return redirect()->route('admin.shipping.index')->with('success', 'Pengali jarak berhasil ditambahkan!');
    }

    /**
     * Delete a province multiplier
     */
    public function destroyMultiplier($id)
    {
        $multiplier = ShippingProvinceMultiplier::findOrFail($id);
        $multiplier->delete();

        return redirect()->route('admin.shipping.index')->with('success', 'Pengali jarak berhasil dihapus!');
    }
}