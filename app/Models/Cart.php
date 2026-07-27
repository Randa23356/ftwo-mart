<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart';

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'selected_variants',
        'unit_price',
    ];

    protected $casts = [
        'selected_variants' => 'array',
        'unit_price' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function getSubtotalAttribute()
    {
        $price = $this->unit_price ?? ($this->product ? $this->product->price : 0);
        return (float) $price * $this->quantity;
    }

    public function getFormattedSubtotalAttribute()
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    /**
     * Get formatted variant summary string (e.g. "Ukuran: M, Warna: Biru").
     */
    public function getStockAttribute(): int
    {
        if ($this->product) {
            return $this->product->getStockForVariants($this->selected_variants ?? []);
        }
        return 0;
    }

    public function getVariantSummaryAttribute(): string
    {
        if (!$this->selected_variants || count($this->selected_variants) === 0) {
            return '';
        }

        $parts = [];
        foreach ($this->selected_variants as $label => $value) {
            $parts[] = "{$label}: {$value}";
        }

        return implode(', ', $parts);
    }
}
