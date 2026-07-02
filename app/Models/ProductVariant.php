<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'size',
        'color',
        'color_hex',
        'sku',
        'price_modifier',
        'stock_quantity',
        'is_active',
    ];

    protected $casts = [
        'price_modifier' => 'decimal:2',
        'stock_quantity' => 'integer',
        'is_active' => 'boolean',
    ];

    // ── RELATION PRODUCT ─────────────────────

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // ── RELATION CART ITEMS ──────────────────

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    // ── RELATION ORDER ITEMS ─────────────────

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // ── HELPERS ──────────────────────────────

    public function isInStock(): bool
    {
        return $this->stock_quantity > 0;
    }

    public function finalPrice(): float
    {
        return $this->product->base_price + $this->price_modifier;
    }

    public function label(): string
    {
        return trim("{$this->color} {$this->size}");
    }
}
