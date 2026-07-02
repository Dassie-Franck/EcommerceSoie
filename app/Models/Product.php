<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'base_price',
        'compare_price',
        'fabric_type',
        'origin',
        'care_instructions',
        'is_active',
        'is_featured',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    // ── RELATIONS ─────────────────────────────

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // ── HELPERS ──────────────────────────────

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function isFeatured(): bool
    {
        return $this->is_featured;
    }

    public function hasDiscount(): bool
    {
        return $this->compare_price !== null && $this->compare_price > $this->base_price;
    }

    public function discountPercentage(): float
    {
        if (!$this->hasDiscount()) {
            return 0;
        }

        return round(
            (($this->compare_price - $this->base_price) / $this->compare_price) * 100,
            2
        );
    }

    public function reviews()
{
    return $this->hasMany(Review::class);
}

// Helper : moyenne des avis approuvés
public function approvedReviews()
{
    return $this->hasMany(Review::class)->where('is_approved', true);
}

public function averageRating(): float
{
    return round($this->approvedReviews()->avg('rating') ?? 0, 1);
}

}
