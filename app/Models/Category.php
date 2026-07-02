<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description',
        'image',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ── RELATION PARENT ──────────────────────
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // ── RELATION ENFANTS ─────────────────────
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // ── PRODUITS ─────────────────────────────
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    // ── HELPERS ──────────────────────────────

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function hasChildren(): bool
    {
        return $this->children()->count() > 0;
    }
}
