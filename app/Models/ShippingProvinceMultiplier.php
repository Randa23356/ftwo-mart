<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingProvinceMultiplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'province_name',
        'distance_multiplier',
    ];

    protected $casts = [
        'distance_multiplier' => 'decimal:2',
    ];
}
