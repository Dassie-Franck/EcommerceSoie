<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
    ];

    // ── RELATION USER ───────────────────────
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── RELATION ITEMS ──────────────────────
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    // ── HELPERS ─────────────────────────────

    public function isGuest(): bool
    {
        return $this->user_id === null;
    }

    public function isAuthenticated(): bool
    {
        return $this->user_id !== null;
    }

    // Total du panier
    public function total(): float
    {
        return $this->items->sum(
            fn($item) => $item->quantity * $item->productVariant->finalPrice()
        );
    }

    // Nombre d'articles
    public function count(): int
    {
        return $this->items->sum('quantity');
    }

    // app/Models/Cart.php
protected $withCount = []; // pas de withCount global (trop lourd)

// Helper statique pour la navbar
public static function getCartCount(): int
{
    if (auth()->check()) {
        return static::where('user_id', auth()->id())
            ->withSum('items', 'quantity')
            ->first()?->items_sum_quantity ?? 0;
    }

    return static::where('session_id', session()->getId())
        ->withSum('items', 'quantity')
        ->first()?->items_sum_quantity ?? 0;
}
}
