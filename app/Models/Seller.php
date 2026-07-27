<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Seller extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shop_name',
        'shop_description',
        'logo',
        'banner',
        'ktp_path',
        'nib_path',
        'npwp_path',
        'rekening_tabungan_path',
        'approval_status',
        'rejection_reason',
        'approved_at',
        'approved_by',
        'is_verified',
        'is_active',
        'commission_rate',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'balance',
        'total_earnings',
        'total_withdrawn',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
        'commission_rate' => 'decimal:2',
        'balance' => 'decimal:2',
        'total_earnings' => 'decimal:2',
        'total_withdrawn' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(SellerTransaction::class);
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(SellerWithdrawal::class);
    }

    public function orders(): HasManyThrough
    {
        return $this->hasManyThrough(Order::class, SellerTransaction::class);
    }

    public function getLogoUrlAttribute(): ?string
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        return null;
    }

    public function getBannerUrlAttribute(): ?string
    {
        if ($this->banner) {
            return asset('storage/' . $this->banner);
        }
        return null;
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getKtpUrlAttribute(): ?string
    {
        return $this->ktp_path ? asset('storage/' . $this->ktp_path) : null;
    }

    public function getNibUrlAttribute(): ?string
    {
        return $this->nib_path ? asset('storage/' . $this->nib_path) : null;
    }

    public function getNpwpUrlAttribute(): ?string
    {
        return $this->npwp_path ? asset('storage/' . $this->npwp_path) : null;
    }

    public function getRekeningTabunganUrlAttribute(): ?string
    {
        return $this->rekening_tabungan_path ? asset('storage/' . $this->rekening_tabungan_path) : null;
    }

    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->approval_status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->approval_status === 'rejected';
    }

    public function getFormattedBalanceAttribute(): string
    {
        return 'Rp ' . number_format($this->balance, 0, ',', '.');
    }

    public function getFormattedTotalEarningsAttribute(): string
    {
        return 'Rp ' . number_format($this->total_earnings, 0, ',', '.');
    }

    public function getFormattedTotalWithdrawnAttribute(): string
    {
        return 'Rp ' . number_format($this->total_withdrawn, 0, ',', '.');
    }

    public function getActiveProductCountAttribute(): int
    {
        return $this->products()->where('is_active', true)->count();
    }
}
