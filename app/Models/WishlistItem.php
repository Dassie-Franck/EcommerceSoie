<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WishlistItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'wishlist_id',
        'product_variant_id',
    ];

    protected $with = ['productVariant'];

    // ── RELATIONS ─────────────────────────────
    public function wishlist(): BelongsTo
    {
        return $this->belongsTo(Wishlist::class);
    }

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    // ── ACCESSORS ─────────────────────────────
    public function getProductAttribute()
    {
        return $this->productVariant?->product;
    }

    public function getPriceAttribute(): float
    {
        return $this->productVariant?->final_price ?? 0;
    }

    public function getProductNameAttribute(): string
    {
        return $this->productVariant?->product?->name ?? 'Produit indisponible';
    }

    public function getImageUrlAttribute(): ?string
    {
        $product = $this->productVariant?->product;
        $image = $product?->images->first();
        return $image ? Storage::url($image->path) : null;
    }
}
