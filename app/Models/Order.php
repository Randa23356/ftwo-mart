<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'order_number',
        'user_id',
        'total_amount',
        'payment_method',
        'payment_status',
        'order_status',
        'delivery_name',
        'delivery_address',
        'delivery_phone',
        'notes',
        'paid_at',
        'expires_at',
        'payment_va',
        'payment_qris',
        'snap_token_created_at',
        // Shipping fields
        'shipping_courier',
        'shipping_service',
        'shipping_cost',
        'shipping_etd',
        'tracking_number',
        'shipped_at',
        'origin_city_id',
        'destination_city_id',
        'destination_province',
        'destination_city',
        'destination_postal_code',
        'total_weight',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'paid_at',
        'expires_at',
        'shipped_at',
        'snap_token_created_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'paid_at' => 'datetime',
        'expires_at' => 'datetime',
        'snap_token_created_at' => 'datetime',
        'shipped_at' => 'datetime',
        'payment_va' => 'array',
        'payment_qris' => 'array',
    ];

    // Relasi ke User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke OrderItem
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relasi ke PaymentTransaction (1 order = 1 payment transaction)
    public function paymentTransaction(): HasOne
    {
        return $this->hasOne(PaymentTransaction::class);
    }

    // Relasi ke Rating
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    // Format total_amount (subtotal only)
    public function getFormattedTotalAmountAttribute()
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    // Get subtotal (alias for total_amount for clarity)
    public function getSubtotalAttribute()
    {
        return $this->total_amount;
    }

    // Get formatted subtotal
    public function getFormattedSubtotalAttribute()
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    // Badge status order
    public function getStatusBadgeAttribute()
    {
        $statuses = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'processing' => 'bg-blue-100 text-blue-800',
            'shipped' => 'bg-purple-100 text-purple-800',
            'ready' => 'bg-indigo-100 text-indigo-800',
            'delivered' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800'
        ];

        return $statuses[$this->order_status] ?? 'bg-gray-100 text-gray-800';
    }

    // Badge status payment
    public function getPaymentStatusBadgeAttribute()
    {
        $statuses = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'paid' => 'bg-green-100 text-green-800',
            'failed' => 'bg-red-100 text-red-800'
        ];

        return $statuses[$this->payment_status] ?? 'bg-gray-100 text-gray-800';
    }

    // Cek apakah pesanan sudah expired
    public function isExpired()
    {
        return $this->expires_at && now()->isAfter($this->expires_at);
    }

    // Set waktu expired (default 24 jam dari pembuatan)
    public function setExpirationTime($hours = 24)
    {
        $this->expires_at = $this->created_at->addHours($hours);
        $this->save();
    }

    // Scope untuk pesanan yang expired dan belum dibayar
    public function scopeExpiredUnpaid($query)
    {
        return $query->where('payment_status', 'pending')
                    ->where('order_status', '!=', 'cancelled')
                    ->whereNotNull('expires_at')
                    ->where('expires_at', '<', now());
    }

    // Get formatted shipping cost
    public function getFormattedShippingCostAttribute()
    {
        return 'Rp ' . number_format($this->shipping_cost, 0, ',', '.');
    }

    // Get total amount including shipping
    public function getTotalWithShippingAttribute()
    {
        return $this->total_amount + $this->shipping_cost;
    }

    // Get formatted total with shipping
    public function getFormattedTotalWithShippingAttribute()
    {
        return 'Rp ' . number_format($this->total_with_shipping, 0, ',', '.');
    }

    // Get shipping status
    public function getShippingStatusAttribute()
    {
        if ($this->tracking_number && $this->shipped_at) {
            return 'shipped';
        } elseif ($this->shipping_courier && $this->shipping_service) {
            return 'ready_to_ship';
        } else {
            return 'pending';
        }
    }

    // Get courier name in readable format
    public function getCourierNameAttribute()
    {
        $couriers = [
            'jne' => 'JNE',
            'pos' => 'POS Indonesia',
            'tiki' => 'TIKI',
            'sicepat' => 'SiCepat',
            'jnt' => 'J&T Express',
            'anteraja' => 'AnterAja',
            'ninja' => 'Ninja Xpress',
            'binderbyte' => 'BinderByte',
        ];

        return $couriers[$this->shipping_courier] ?? strtoupper($this->shipping_courier);
    }

    public function getTrackingUrlAttribute(): ?string
    {
        if (! $this->tracking_number) {
            return null;
        }

        $awb = rawurlencode($this->tracking_number);
        $courier = strtolower(trim($this->shipping_courier ?? ''));

        return match ($courier) {
            'jne' => "https://www.jne.co.id/id/tracking/trace/{$awb}",
            'jnt', 'jet' => "https://www.jet.co.id/track?awb={$awb}",
            'sicepat' => "https://www.sicepat.com/check/awb?awb={$awb}",
            'anteraja' => "https://anteraja.id/tracking?awb={$awb}",
            'ninja' => "https://www.ninjaxpress.co/id-id/tracking?id={$awb}",
            'pos' => "https://www.posindonesia.co.id/id/tracking/result?q={$awb}",
            'tiki' => "https://www.tiki.id/id/tracking?q={$awb}",
            default => "https://cekresi.com/?noresi={$awb}",
        };
    }

    public function getTrackingButtonLabelAttribute(): string
    {
        return $this->courier_name ? "Cek Resi {$this->courier_name}" : 'Cek Resi';
    }

    /**
     * Cancel order and restore stock
     */
    public function cancelOrder($reason = 'Manual cancellation')
    {
        if ($this->order_status === 'cancelled') {
            return false; // Already cancelled
        }

        if ($this->payment_status === 'paid') {
            return false; // Cannot cancel paid orders
        }

        try {
            \DB::beginTransaction();

            // Restore stock untuk semua item dalam order
            $restoredItems = [];
            foreach ($this->orderItems as $orderItem) {
                $product = $orderItem->product;
                $oldStock = $product->stock;
                
                // Kembalikan stock
                $product->increment('stock', $orderItem->quantity);
                
                $restoredItems[] = [
                    'product_name' => $product->name,
                    'quantity_restored' => $orderItem->quantity,
                    'old_stock' => $oldStock,
                    'new_stock' => $product->fresh()->stock
                ];
            }

            // Update status pesanan menjadi cancelled
            $this->update([
                'order_status' => 'cancelled',
                'payment_status' => 'failed'
            ]);

            \DB::commit();

            // Log pembatalan
            \Log::info("Order {$this->order_number} dibatalkan: {$reason}", [
                'order_id' => $this->id,
                'user_id' => $this->user_id,
                'reason' => $reason,
                'cancelled_at' => now(),
                'stock_restored' => $restoredItems
            ]);

            return true;

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error("Gagal membatalkan order {$this->order_number}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if order can be cancelled
     */
    public function canBeCancelled()
    {
        return $this->order_status !== 'cancelled' 
            && $this->payment_status !== 'paid'
            && !in_array($this->order_status, ['shipped', 'delivered']);
    }
}
