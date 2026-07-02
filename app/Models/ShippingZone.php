<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'countries',
        'price',
        'free_above',
        'estimated_days',
        'is_active',
    ];

    protected $casts = [
        'countries' => 'array',
        'price' => 'decimal:2',
        'free_above' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // ── RELATIONS ─────────────────────────────

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // ── HELPERS ──────────────────────────────

    public function isFreeShipping(float $cartTotal): bool
    {
        if (!$this->free_above) {
            return false;
        }

        return $cartTotal >= $this->free_above;
    }

    public function calculateShipping(float $cartTotal): float
    {
        return $this->isFreeShipping($cartTotal) ? 0 : $this->price;
    }
}
