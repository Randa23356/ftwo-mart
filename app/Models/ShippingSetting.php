<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ShippingSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'origin_city_id',
        'origin_city_name',
        'origin_province',
        'origin_postal_code',
        'origin_latitude',
        'origin_longitude',
        'warehouse_name',
        'warehouse_address',
        'contact_phone',
        'is_active',
    ];

    protected $casts = [
        'origin_latitude' => 'decimal:7',
        'origin_longitude' => 'decimal:7',
        'is_active' => 'boolean',
    ];

    /**
     * Get the active shipping origin
     */
    public static function getActiveOrigin()
    {
        return Cache::remember('shipping_origin', 3600, function () {
            return self::where('is_active', true)->first() ?? self::first();
        });
    }

    /**
     * Clear cache when updating
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            Cache::forget('shipping_origin');
        });

        static::deleted(function () {
            Cache::forget('shipping_origin');
        });
    }

    /**
     * Get formatted address
     */
    public function getFormattedAddressAttribute()
    {
        return $this->warehouse_address ?: "{$this->origin_city_name}, {$this->origin_province}";
    }

    /**
     * Get origin city relationship
     */
    public function originCity()
    {
        return $this->belongsTo(City::class, 'origin_city_id', 'city_id');
    }
}