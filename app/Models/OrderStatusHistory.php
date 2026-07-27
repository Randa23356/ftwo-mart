<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderStatusHistory extends Model
{
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected $fillable = [
        'order_id',
        'old_status',
        'new_status',
        'changed_by',
        'changed_by_role',
        'notes',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    public function getActorLabelAttribute(): string
    {
        return match($this->changed_by_role) {
            'admin' => 'Admin',
            'operator' => 'Operator',
            'seller' => $this->user?->name ?? 'Seller',
            'user' => $this->user?->name ?? 'Pelanggan',
            'system' => 'Sistem',
            default => 'Unknown',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->new_status) {
            'pending' => 'Menunggu',
            'processing' => 'Diproses',
            'ready' => 'Siap Diambil',
            'shipped' => 'Dikirim',
            'delivered' => 'Diterima',
            'cancelled' => 'Dibatalkan',
            default => $this->new_status,
        };
    }

    public function getOldStatusLabelAttribute(): ?string
    {
        if (!$this->old_status) return null;

        return match($this->old_status) {
            'pending' => 'Menunggu',
            'processing' => 'Diproses',
            'ready' => 'Siap Diambil',
            'shipped' => 'Dikirim',
            'delivered' => 'Diterima',
            'cancelled' => 'Dibatalkan',
            default => $this->old_status,
        };
    }
}
