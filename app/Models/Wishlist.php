<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    // ── RELATION USER ───────────────────────
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── RELATION ITEMS ───────────────────────
    public function items(): HasMany
    {
        return $this->hasMany(WishlistItem::class);
    }

    // ── HELPERS ─────────────────────────────
    public function hasProductVariant(int $productVariantId): bool
    {
        return $this->items()->where('product_variant_id', $productVariantId)->exists();
    }

    public function addProductVariant(int $productVariantId): void
    {
        if (!$this->hasProductVariant($productVariantId)) {
            $this->items()->create([
                'product_variant_id' => $productVariantId,
            ]);
        }
    }

    public function removeProductVariant(int $productVariantId): void
    {
        $this->items()->where('product_variant_id', $productVariantId)->delete();
    }
}
