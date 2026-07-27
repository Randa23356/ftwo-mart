<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SellerTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'order_id',
        'order_item_id',
        'product_id',
        'gross_amount',
        'commission',
        'net_amount',
        'status',
        'settled_at',
    ];

    protected $casts = [
        'gross_amount' => 'decimal:2',
        'commission' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'settled_at' => 'datetime',
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getFormattedGrossAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->gross_amount, 0, ',', '.');
    }

    public function getFormattedCommissionAttribute(): string
    {
        return 'Rp ' . number_format($this->commission, 0, ',', '.');
    }

    public function getFormattedNetAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->net_amount, 0, ',', '.');
    }
}
