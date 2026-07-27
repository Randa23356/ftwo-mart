<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class RefundRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'reason',
        'notes',
        'evidence_image',
        'status',
        'admin_notes',
        'reviewed_at',
        'return_tracking_number',
        'return_evidence_image',
        'buyer_returned_at',
        'seller_returned_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'buyer_returned_at' => 'datetime',
        'seller_returned_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getEvidenceImageUrlAttribute(): ?string
    {
        if (!$this->evidence_image) return null;
        return Storage::disk('public')->url($this->evidence_image);
    }

    public function getReturnEvidenceImageUrlAttribute(): ?string
    {
        if (!$this->return_evidence_image) return null;
        return Storage::disk('public')->url($this->return_evidence_image);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-blue-100 text-blue-800',
            'return_shipped' => 'bg-purple-100 text-purple-800',
            'completed' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu Review',
            'approved' => 'Disetujui - Menunggu Pengembalian',
            'return_shipped' => 'Barang Dikirim Balik',
            'completed' => 'Selesai',
            'rejected' => 'Ditolak',
            default => ucfirst($this->status),
        };
    }

    public function getReasonLabelAttribute(): string
    {
        return match($this->reason) {
            'changed_mind' => 'Berubah pikiran / tidak jadi beli',
            'wrong_item' => 'Produk yang dikirim salah',
            'damaged' => 'Produk rusak / cacat',
            'not_as_described' => 'Produk tidak sesuai deskripsi',
            'late_delivery' => 'Pengiriman terlambat',
            'other' => 'Lainnya',
            default => $this->reason,
        };
    }
}
