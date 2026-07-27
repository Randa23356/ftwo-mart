<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariantCombination extends Model
{
    protected $fillable = [
        'product_id',
        'variant_key',
        'price',
        'stock',
    ];

    protected $casts = [
        'variant_key' => 'array',
        'price' => 'decimal:2',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
