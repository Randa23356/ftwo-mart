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
        'variant_options',
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
        'category_id',
        'seller_id',
        'pricing_type',
        'uploaded_by',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'custom_price_per_piece' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_custom_available' => 'boolean',
        'variant_options' => 'array',
    ];

    public function setVariantOptionsAttribute($value): void
    {
        $normalized = self::normalizeVariantOptions($value);
        $this->attributes['variant_options'] = $normalized ? json_encode($normalized) : null;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getIsUploadedByStaffAttribute(): bool
    {
        if (!$this->uploaded_by || !$this->relationLoaded('uploadedBy')) {
            $this->load('uploadedBy');
        }
        return $this->uploadedBy && ($this->uploadedBy->isAdmin() || $this->uploadedBy->isOperator());
    }

    /**
     * Check if a user is blocked from purchasing this product.
     * Staff-uploaded products can't be bought by any staff (admin/operator).
     * Seller-uploaded products can't be bought by the same seller.
     */
    public function isBlockedForUser($userId): bool
    {
        $buyer = \App\Models\User::find($userId);
        if (!$buyer) return false;

        $buyerIsStaff = $buyer->isAdmin() || $buyer->isOperator();

        // Check uploaded_by (new products)
        if ($this->uploaded_by) {
            $uploader = $this->uploadedBy()->first();
            if ($uploader) {
                $uploaderIsStaff = $uploader->isAdmin() || $uploader->isOperator();

                // Staff-uploaded products can't be bought by any staff
                if ($uploaderIsStaff && $buyerIsStaff) {
                    return true;
                }

                // Seller-uploaded products can't be bought by the same seller
                if (!$uploaderIsStaff && (int) $uploader->id === (int) $userId) {
                    return true;
                }
            }

            return false;
        }

        // Fallback for old products without uploaded_by
        // If product has no seller_id, it was likely created by staff → block staff
        if (!$this->seller_id && $buyerIsStaff) {
            return true;
        }

        // If product has a seller_id, check if buyer is that seller
        if ($this->seller_id && $buyer->seller && (int) $this->seller_id === (int) $buyer->seller->id) {
            return true;
        }

        return false;
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

    public function variantCombinations(): HasMany
    {
        return $this->hasMany(ProductVariantCombination::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function getImageUrlAttribute()
    {
        $primaryImage = $this->primaryImage;
        if ($primaryImage) {
            return $primaryImage->image_url;
        }

        $firstImage = $this->images()->first();
        if ($firstImage) {
            return $firstImage->image_url;
        }

        if ($this->image) {
            return asset('storage/' . $this->image);
        }

        return asset('images/default-product.jpg');
    }

    public function getAllImagesAttribute()
    {
        $images = $this->images;

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
        if ($this->pricing_type === 'variant' && $this->has_variants) {
            return $this->formatted_price_range;
        }
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getIsPricingVariantAttribute(): bool
    {
        return $this->pricing_type === 'variant' && $this->has_variants;
    }

    public function getFormattedPriceRangeAttribute(): string
    {
        $prices = $this->variantCombinations->pluck('price')->filter()->toArray();
        if (empty($prices)) {
            return 'Rp ' . number_format($this->price, 0, ',', '.');
        }
        $min = min($prices);
        $max = max($prices);
        if ($min === $max) {
            return 'Rp ' . number_format($min, 0, ',', '.');
        }
        return 'Rp ' . number_format($min, 0, ',', '.') . ' - Rp ' . number_format($max, 0, ',', '.');
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

    public static function normalizeVariantOptions($value): ?array
    {
        if (empty($value)) return null;
        $decoded = is_string($value) ? json_decode($value, true) : $value;
        if (!is_array($decoded) || empty($decoded)) return null;
        // If stored as indexed array of single-key objects, merge into one object
        if (array_is_list($decoded)) {
            $merged = [];
            foreach ($decoded as $item) {
                if (is_array($item)) {
                    foreach ($item as $k => $v) {
                        $merged[$k] = $v;
                    }
                }
            }
            return !empty($merged) ? $merged : null;
        }
        return $decoded;
    }

    /**
     * Check if product has any variant options configured.
     */
    public function getHasVariantsAttribute(): bool
    {
        return is_array($this->variant_options) && count($this->variant_options) > 0;
    }

    public function getTotalStockAttribute(): int
    {
        if ($this->is_pricing_variant) {
            return (int) $this->variantCombinations->sum('stock');
        }
        return (int) $this->stock;
    }

    /**
     * Get all variant labels (e.g. ['Ukuran', 'Warna']).
     */
    public function getVariantLabels(): array
    {
        if (!$this->has_variants) return [];
        return array_keys($this->variant_options);
    }

    /**
     * Get available options for a specific variant label.
     */
    public function getVariantOptions(string $label): array
    {
        if (!$this->has_variants) return [];
        return $this->variant_options[$label] ?? [];
    }

    /**
     * Normalize variant key order for consistent JSON encoding.
     */
    public static function normalizeVariantKey(array $variants): array
    {
        ksort($variants);
        return $variants;
    }

    /**
     * Find the variant combination for the given selected variants.
     * Normalizes key order to handle both stored and incoming key orderings.
     */
    public function findCombination(array $selectedVariants): ?ProductVariantCombination
    {
        $normalized = self::normalizeVariantKey($selectedVariants);
        $normalizedJson = json_encode($normalized);

        // Try exact match first
        $combination = $this->variantCombinations()
            ->where('variant_key', $normalizedJson)
            ->first();

        if ($combination) return $combination;

        // Fallback: compare decoded JSON — handles older data with different key order
        foreach ($this->variantCombinations as $combo) {
            $stored = $combo->variant_key;
            if (is_array($stored) && self::normalizeVariantKey($stored) === $normalized) {
                return $combo;
            }
        }

        return null;
    }

    /**
     * Get price for the given selected variants. Falls back to product price.
     */
    public function getPriceForVariants(array $selectedVariants): float
    {
        if (empty($selectedVariants)) {
            return (float) $this->price;
        }

        $combination = $this->findCombination($selectedVariants);
        return $combination ? (float) $combination->price : (float) $this->price;
    }

    /**
     * Get stock for the given selected variants. Falls back to product stock.
     */
    public function getStockForVariants(array $selectedVariants): int
    {
        if (empty($selectedVariants)) {
            return (int) $this->stock;
        }

        $combination = $this->findCombination($selectedVariants);
        return $combination ? (int) $combination->stock : (int) $this->stock;
    }

    /**
     * Generate all possible variant combinations from variant_options.
     */
    public static function generateCombinations(array $variantOptions): array
    {
        if (empty($variantOptions)) return [];

        $labels = array_keys($variantOptions);
        $options = array_values($variantOptions);

        $result = [[]];
        foreach ($options as $i => $opts) {
            $tmp = [];
            foreach ($result as $combo) {
                foreach ($opts as $opt) {
                    $combo[$labels[$i]] = $opt;
                    $tmp[] = $combo;
                }
            }
            $result = $tmp;
        }

        return $result;
    }
}
