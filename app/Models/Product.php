<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'motif_name',
        'slug',
        'description',
        'motif_meaning',
        'origin_region',
        'batik_technique',
        'material_description',
        'available_sizes',
        'available_colors',
        'pattern_category',
        'is_custom_available',
        'min_custom_quantity',
        'custom_price_per_piece',
        'price',
        'stock',
        'weight',
        'image',
        'is_active',
        'is_featured',
        'category_id'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'custom_price_per_piece' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_custom_available' => 'boolean',
        'available_sizes' => 'array',
        'available_colors' => 'array'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function getImageUrlAttribute()
    {
        // First try to get primary image from product_images table
        $primaryImage = $this->primaryImage;
        if ($primaryImage) {
            return $primaryImage->image_url;
        }

        // Fallback to first image from product_images table
        $firstImage = $this->images()->first();
        if ($firstImage) {
            return $firstImage->image_url;
        }

        // Fallback to legacy single image field
        if ($this->image) {
            return asset('storage/' . $this->image);
        }

        // Default image
        return asset('images/default-product.jpg');
    }

    public function getAllImagesAttribute()
    {
        $images = $this->images;
        
        // If no images in product_images table, use legacy image field
        if ($images->isEmpty() && $this->image) {
            return collect([
                (object) [
                    'id' => null,
                    'image_url' => asset('storage/' . $this->image),
                    'alt_text' => $this->name,
                    'is_primary' => true
                ]
            ]);
        }

        return $images;
    }

    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getSoldCountAttribute()
    {
        return $this->orderItems()
            ->whereHas('order', function ($query) {
                $query->where('payment_status', 'paid');
            })
            ->sum('quantity');
    }

    public function getAverageRatingAttribute()
    {
        return $this->ratings()->avg('rating') ?? 0;
    }

    public function getTotalRatingsAttribute()
    {
        return $this->ratings()->count();
    }
}
