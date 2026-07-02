<?php

/**
 * ============================================================
 *  AFRISOIE SHOP — Models + Migrations
 *  Placer ce fichier à la RACINE du projet Laravel
 *  Exécuter : php setup-models-migrations.php
 *  Supprimer le fichier après exécution
 * ============================================================
 */

$root    = __DIR__;
$created = [];
$skipped = [];

// ─────────────────────────────────────────────
// Fonctions utilitaires
// ─────────────────────────────────────────────

function makeDir(string $path): void {
    if (!is_dir($path)) mkdir($path, 0755, true);
}

function makeFile(string $path, string $content): void {
    global $created, $skipped;
    if (!file_exists($path)) {
        file_put_contents($path, $content);
        $created[] = "FILE " . str_replace(__DIR__ . '/', '', $path);
    } else {
        $skipped[] = "SKIP " . str_replace(__DIR__ . '/', '', $path);
    }
}

function overwriteFile(string $path, string $content): void {
    global $created;
    file_put_contents($path, $content);
    $created[] = "EDIT " . str_replace(__DIR__ . '/', '', $path);
}

// ─────────────────────────────────────────────
// DOSSIERS
// ─────────────────────────────────────────────

makeDir("$root/app/Models");
makeDir("$root/database/migrations");
makeDir("$root/database/seeders");
makeDir("$root/database/factories");

// ════════════════════════════════════════════════════════════
//  MODELS
// ════════════════════════════════════════════════════════════

// ── User ────────────────────────────────────────────────────
overwriteFile("$root/app/Models/User.php", <<<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',         // 'admin' | 'client'
        'phone',
        'avatar',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ── Relations ──────────────────────────────
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }

    public function wishlist(): HasOne
    {
        return $this->hasOne(Wishlist::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // ── Helpers ────────────────────────────────
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
PHP);

// ── Category ────────────────────────────────────────────────
makeFile("$root/app/Models/Category.php", <<<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'parent_id',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ── Slug auto ──────────────────────────────
    protected static function booted(): void
    {
        static::creating(function (Category $category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    // ── Relations ──────────────────────────────
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
PHP);

// ── Product ─────────────────────────────────────────────────
makeFile("$root/app/Models/Product.php", <<<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'base_price',
        'compare_price',     // Prix barré pour les promotions
        'fabric_type',       // ex: "Soie Ghanéenne", "Bogolan", "Kente"
        'origin',            // ex: "Ghana", "Mali"
        'care_instructions',
        'is_active',
        'is_featured',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'base_price'    => 'decimal:2',
        'compare_price' => 'decimal:2',
        'is_active'     => 'boolean',
        'is_featured'   => 'boolean',
    ];

    // ── Slug auto ──────────────────────────────
    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    // ── Relations ──────────────────────────────
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    // ── Accessors ──────────────────────────────
    public function primaryImage(): ?ProductImage
    {
        return $this->images->where('is_primary', true)->first()
            ?? $this->images->first();
    }

    public function getAverageRatingAttribute(): float
    {
        return round($this->reviews->avg('rating') ?? 0, 1);
    }

    public function isOnSale(): bool
    {
        return $this->compare_price && $this->compare_price > $this->base_price;
    }
}
PHP);

// ── ProductImage ─────────────────────────────────────────────
makeFile("$root/app/Models/ProductImage.php", <<<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'path',
        'alt',
        'is_primary',
        'sort_order',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    // ── Relations ──────────────────────────────
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // ── Accessors ──────────────────────────────
    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->path);
    }
}
PHP);

// ── ProductVariant ──────────────────────────────────────────
makeFile("$root/app/Models/ProductVariant.php", <<<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'size',          // ex: XS, S, M, L, XL, XXL, ou mesure en cm
        'color',         // ex: "Indigo", "Ocre", "Blanc cassé"
        'color_hex',     // ex: "#4B0082"
        'sku',
        'price_modifier', // Ajout/soustraction au base_price (+10.00, -5.00)
        'stock_quantity',
        'is_active',
    ];

    protected $casts = [
        'price_modifier'  => 'decimal:2',
        'stock_quantity'  => 'integer',
        'is_active'       => 'boolean',
    ];

    // ── Relations ──────────────────────────────
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function wishlistItems(): HasMany
    {
        return $this->hasMany(WishlistItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // ── Helpers ────────────────────────────────
    public function getPrice(): float
    {
        return (float) ($this->product->base_price + $this->price_modifier);
    }

    public function inStock(): bool
    {
        return $this->stock_quantity > 0;
    }

    public function decrementStock(int $qty = 1): void
    {
        $this->decrement('stock_quantity', $qty);
    }

    public function getLabel(): string
    {
        return trim($this->size . ' / ' . $this->color, ' /');
    }
}
PHP);

// ── Cart ────────────────────────────────────────────────────
makeFile("$root/app/Models/Cart.php", <<<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
    ];

    // ── Relations ──────────────────────────────
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class)->with('variant.product');
    }

    // ── Helpers ────────────────────────────────
    public function getItemsCountAttribute(): int
    {
        return $this->items->sum('quantity');
    }

    public function getSubtotalAttribute(): float
    {
        return (float) $this->items->sum(
            fn($item) => $item->quantity * $item->variant->getPrice()
        );
    }
}
PHP);

// ── CartItem ─────────────────────────────────────────────────
makeFile("$root/app/Models/CartItem.php", <<<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'product_variant_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    // ── Relations ──────────────────────────────
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id')
                    ->with('product');
    }

    // ── Helpers ────────────────────────────────
    public function getLineTotalAttribute(): float
    {
        return $this->quantity * $this->variant->getPrice();
    }
}
PHP);

// ── Wishlist ─────────────────────────────────────────────────
makeFile("$root/app/Models/Wishlist.php", <<<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wishlist extends Model
{
    protected $fillable = ['user_id'];

    // ── Relations ──────────────────────────────
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(WishlistItem::class)->with('variant.product');
    }
}
PHP);

// ── WishlistItem ─────────────────────────────────────────────
makeFile("$root/app/Models/WishlistItem.php", <<<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WishlistItem extends Model
{
    protected $fillable = [
        'wishlist_id',
        'product_variant_id',
    ];

    // ── Relations ──────────────────────────────
    public function wishlist(): BelongsTo
    {
        return $this->belongsTo(Wishlist::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id')
                    ->with('product.images');
    }
}
PHP);

// ── Address ──────────────────────────────────────────────────
makeFile("$root/app/Models/Address.php", <<<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'phone',
        'street',
        'city',
        'state',
        'postal_code',
        'country',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    // ── Relations ──────────────────────────────
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Helpers ────────────────────────────────
    public function getFullAddressAttribute(): string
    {
        return implode(', ', array_filter([
            $this->street,
            $this->city,
            $this->postal_code,
            $this->state,
            $this->country,
        ]));
    }
}
PHP);

// ── ShippingZone ─────────────────────────────────────────────
makeFile("$root/app/Models/ShippingZone.php", <<<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingZone extends Model
{
    protected $fillable = [
        'name',           // ex: "Europe", "Afrique de l'Ouest", "France métropolitaine"
        'countries',      // JSON : ["FR","BE","CH","LU"]
        'price',          // Frais de port en EUR
        'free_above',     // Montant au-delà duquel la livraison est gratuite (null = jamais)
        'estimated_days', // ex: "3-5 jours"
        'is_active',
    ];

    protected $casts = [
        'countries'  => 'array',
        'price'      => 'decimal:2',
        'free_above' => 'decimal:2',
        'is_active'  => 'boolean',
    ];

    // ── Relations ──────────────────────────────
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // ── Helpers ────────────────────────────────
    public function isFreeFor(float $orderAmount): bool
    {
        return $this->free_above !== null && $orderAmount >= $this->free_above;
    }

    public function shippingCostFor(float $orderAmount): float
    {
        return $this->isFreeFor($orderAmount) ? 0.0 : (float) $this->price;
    }
}
PHP);

// ── Order ────────────────────────────────────────────────────
makeFile("$root/app/Models/Order.php", <<<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'address_id',
        'shipping_zone_id',
        'order_number',
        'status',          // pending | processing | shipped | completed | cancelled
        'subtotal',
        'shipping_cost',
        'discount',
        'total',
        'coupon_code',
        'notes',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
    ];

    protected $casts = [
        'subtotal'      => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'discount'      => 'decimal:2',
        'total'         => 'decimal:2',
        'shipped_at'    => 'datetime',
        'delivered_at'  => 'datetime',
        'cancelled_at'  => 'datetime',
    ];

    // ── Relations ──────────────────────────────
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function shippingZone(): BelongsTo
    {
        return $this->belongsTo(ShippingZone::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    // ── Helpers ────────────────────────────────
    public function isPending(): bool     { return $this->status === 'pending'; }
    public function isShipped(): bool     { return $this->status === 'shipped'; }
    public function isCompleted(): bool   { return $this->status === 'completed'; }
    public function isCancelled(): bool   { return $this->status === 'cancelled'; }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending'    => 'En attente',
            'processing' => 'En préparation',
            'shipped'    => 'Expédiée',
            'completed'  => 'Livrée',
            'cancelled'  => 'Annulée',
            default      => ucfirst($this->status),
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'pending'    => 'warning',
            'processing' => 'info',
            'shipped'    => 'info',
            'completed'  => 'success',
            'cancelled'  => 'error',
            default      => 'ghost',
        };
    }
}
PHP);

// ── OrderItem ────────────────────────────────────────────────
makeFile("$root/app/Models/OrderItem.php", <<<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_variant_id',
        'product_name',     // Snapshot au moment de la commande
        'variant_label',    // ex: "L / Indigo"
        'unit_price',
        'quantity',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'quantity'   => 'integer',
    ];

    // ── Relations ──────────────────────────────
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    // ── Helpers ────────────────────────────────
    public function getLineTotalAttribute(): float
    {
        return (float) ($this->unit_price * $this->quantity);
    }
}
PHP);

// ── Payment ──────────────────────────────────────────────────
makeFile("$root/app/Models/Payment.php", <<<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'provider',         // 'paypal' | 'stripe' | 'cinetpay'
        'transaction_id',
        'status',           // pending | completed | failed | refunded
        'amount',
        'currency',
        'raw_response',     // JSON réponse brute du provider
        'paid_at',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'raw_response' => 'array',
        'paid_at'      => 'datetime',
    ];

    protected $hidden = ['raw_response'];

    // ── Relations ──────────────────────────────
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // ── Helpers ────────────────────────────────
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
}
PHP);

// ── Review ───────────────────────────────────────────────────
makeFile("$root/app/Models/Review.php", <<<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'order_id',
        'rating',       // 1 à 5
        'title',
        'comment',
        'is_approved',
        'approved_at',
    ];

    protected $casts = [
        'rating'      => 'integer',
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
    ];

    // ── Relations ──────────────────────────────
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
PHP);


// ════════════════════════════════════════════════════════════
//  MIGRATIONS
//  Horodatage fixe pour garantir l'ordre d'exécution
// ════════════════════════════════════════════════════════════

// ── 01 users ────────────────────────────────────────────────
makeFile("$root/database/migrations/2024_01_01_000001_create_users_table.php", <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'client'])->default('client');
            $table->string('phone', 30)->nullable();
            $table->string('avatar')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
PHP);

// ── 02 addresses ────────────────────────────────────────────
makeFile("$root/database/migrations/2024_01_01_000002_create_addresses_table.php", <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('full_name');
            $table->string('phone', 30);
            $table->string('street');
            $table->string('city');
            $table->string('state', 100)->nullable();
            $table->string('postal_code', 20);
            $table->string('country', 100);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
PHP);

// ── 03 categories ────────────────────────────────────────────
makeFile("$root/database/migrations/2024_01_01_000003_create_categories_table.php", <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')
                  ->nullable()
                  ->constrained('categories')
                  ->nullOnDelete();
            $table->string('name', 100);
            $table->string('slug', 120)->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
PHP);

// ── 04 products ──────────────────────────────────────────────
makeFile("$root/database/migrations/2024_01_01_000004_create_products_table.php", <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                  ->constrained()
                  ->restrictOnDelete();
            $table->string('name', 200);
            $table->string('slug', 220)->unique();
            $table->text('description');
            $table->decimal('base_price', 10, 2);
            $table->decimal('compare_price', 10, 2)->nullable();
            $table->string('fabric_type', 100);
            $table->string('origin', 100)->nullable();
            $table->text('care_instructions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->string('meta_title', 200)->nullable();
            $table->text('meta_description')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['is_active', 'is_featured']);
            $table->index(['category_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
PHP);

// ── 05 product_images ────────────────────────────────────────
makeFile("$root/database/migrations/2024_01_01_000005_create_product_images_table.php", <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('alt', 200)->nullable();
            $table->boolean('is_primary')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['product_id', 'is_primary']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
PHP);

// ── 06 product_variants ──────────────────────────────────────
makeFile("$root/database/migrations/2024_01_01_000006_create_product_variants_table.php", <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('size', 20)->nullable();
            $table->string('color', 80)->nullable();
            $table->string('color_hex', 10)->nullable();
            $table->string('sku', 80)->unique()->nullable();
            $table->decimal('price_modifier', 8, 2)->default(0.00);
            $table->unsignedInteger('stock_quantity')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['product_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
PHP);

// ── 07 carts ─────────────────────────────────────────────────
makeFile("$root/database/migrations/2024_01_01_000007_create_carts_table.php", <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('session_id')->nullable()->index();
            $table->timestamps();

            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
PHP);

// ── 08 cart_items ────────────────────────────────────────────
makeFile("$root/database/migrations/2024_01_01_000008_create_cart_items_table.php", <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('quantity')->default(1);
            $table->timestamps();

            $table->unique(['cart_id', 'product_variant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
PHP);

// ── 09 wishlists ─────────────────────────────────────────────
makeFile("$root/database/migrations/2024_01_01_000009_create_wishlists_table.php", <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};
PHP);

// ── 10 wishlist_items ────────────────────────────────────────
makeFile("$root/database/migrations/2024_01_01_000010_create_wishlist_items_table.php", <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wishlist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wishlist_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['wishlist_id', 'product_variant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wishlist_items');
    }
};
PHP);

// ── 11 shipping_zones ────────────────────────────────────────
makeFile("$root/database/migrations/2024_01_01_000011_create_shipping_zones_table.php", <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->json('countries')->nullable();
            $table->decimal('price', 8, 2)->default(0.00);
            $table->decimal('free_above', 10, 2)->nullable();
            $table->string('estimated_days', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_zones');
    }
};
PHP);

// ── 12 orders ────────────────────────────────────────────────
makeFile("$root/database/migrations/2024_01_01_000012_create_orders_table.php", <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('address_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('shipping_zone_id')->nullable()->constrained()->nullOnDelete();
            $table->string('order_number', 30)->unique();
            $table->enum('status', [
                'pending', 'processing', 'shipped', 'completed', 'cancelled'
            ])->default('pending');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('shipping_cost', 8, 2)->default(0.00);
            $table->decimal('discount', 8, 2)->default(0.00);
            $table->decimal('total', 10, 2);
            $table->string('coupon_code', 50)->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
PHP);

// ── 13 order_items ───────────────────────────────────────────
makeFile("$root/database/migrations/2024_01_01_000013_create_order_items_table.php", <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();
            $table->string('product_name', 200);   // Snapshot
            $table->string('variant_label', 100)->nullable(); // Snapshot
            $table->decimal('unit_price', 10, 2);
            $table->unsignedInteger('quantity');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
PHP);

// ── 14 payments ──────────────────────────────────────────────
makeFile("$root/database/migrations/2024_01_01_000014_create_payments_table.php", <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('provider', 30);         // paypal | stripe | cinetpay
            $table->string('transaction_id')->nullable()->unique();
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])
                  ->default('pending');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 5)->default('EUR');
            $table->json('raw_response')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['provider', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
PHP);

// ── 15 reviews ───────────────────────────────────────────────
makeFile("$root/database/migrations/2024_01_01_000015_create_reviews_table.php", <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedTinyInteger('rating');   // 1-5
            $table->string('title', 150)->nullable();
            $table->text('comment');
            $table->boolean('is_approved')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            // Un utilisateur = un seul avis par produit
            $table->unique(['user_id', 'product_id']);
            $table->index(['product_id', 'is_approved']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
PHP);


// ════════════════════════════════════════════════════════════
//  SEEDERS
// ════════════════════════════════════════════════════════════

overwriteFile("$root/database/seeders/DatabaseSeeder.php", <<<'PHP'
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            ShippingZoneSeeder::class,
            ProductSeeder::class,
        ]);
    }
}
PHP);

makeFile("$root/database/seeders/UserSeeder.php", <<<'PHP'
<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Compte administrateur
        $admin = User::firstOrCreate(
            ['email' => 'admin@afrisoie.com'],
            [
                'name'     => 'Admin AfriSoie',
                'password' => Hash::make('password'),
                'role'     => 'admin',
            ]
        );

        // Compte client de test
        $client = User::firstOrCreate(
            ['email' => 'client@afrisoie.com'],
            [
                'name'     => 'Client Test',
                'password' => Hash::make('password'),
                'role'     => 'client',
            ]
        );

        // Wishlist auto pour le client
        Wishlist::firstOrCreate(['user_id' => $client->id]);

        $this->command->info('✓ Utilisateurs créés — admin@afrisoie.com / client@afrisoie.com (password: password)');
    }
}
PHP);

makeFile("$root/database/seeders/CategorySeeder.php", <<<'PHP'
<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Robes',        'slug' => 'robes'],
            ['name' => 'Boubous',      'slug' => 'boubous'],
            ['name' => 'Ensembles',    'slug' => 'ensembles'],
            ['name' => 'Accessoires',  'slug' => 'accessoires'],
            ['name' => 'Tissu au mètre', 'slug' => 'tissu-au-metre'],
        ];

        foreach ($categories as $data) {
            Category::firstOrCreate(['slug' => $data['slug']], array_merge($data, ['is_active' => true]));
        }

        $this->command->info('✓ ' . count($categories) . ' catégories créées.');
    }
}
PHP);

makeFile("$root/database/seeders/ShippingZoneSeeder.php", <<<'PHP'
<?php

namespace Database\Seeders;

use App\Models\ShippingZone;
use Illuminate\Database\Seeder;

class ShippingZoneSeeder extends Seeder
{
    public function run(): void
    {
        $zones = [
            [
                'name'           => 'France métropolitaine',
                'countries'      => ['FR'],
                'price'          => 5.90,
                'free_above'     => 80.00,
                'estimated_days' => '3-5 jours',
                'is_active'      => true,
            ],
            [
                'name'           => 'Europe (UE)',
                'countries'      => ['BE','CH','LU','DE','ES','IT','NL','PT'],
                'price'          => 12.90,
                'free_above'     => 120.00,
                'estimated_days' => '5-8 jours',
                'is_active'      => true,
            ],
            [
                'name'           => 'Afrique de l\'Ouest',
                'countries'      => ['SN','CI','ML','BF','GN','TG','BJ','GH','CM'],
                'price'          => 18.00,
                'free_above'     => 150.00,
                'estimated_days' => '7-14 jours',
                'is_active'      => true,
            ],
            [
                'name'           => 'Reste du monde',
                'countries'      => [],
                'price'          => 25.00,
                'free_above'     => 200.00,
                'estimated_days' => '10-21 jours',
                'is_active'      => true,
            ],
        ];

        foreach ($zones as $zone) {
            ShippingZone::firstOrCreate(['name' => $zone['name']], $zone);
        }

        $this->command->info('✓ ' . count($zones) . ' zones de livraison créées.');
    }
}
PHP);

makeFile("$root/database/seeders/ProductSeeder.php", <<<'PHP'
<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $robes = Category::where('slug', 'robes')->first();
        $boubous = Category::where('slug', 'boubous')->first();

        if (!$robes || !$boubous) {
            $this->command->warn('Catégories introuvables — lance CategorySeeder d\'abord.');
            return;
        }

        $products = [
            [
                'category_id'  => $robes->id,
                'name'         => 'Robe Kente Royale',
                'slug'         => 'robe-kente-royale',
                'description'  => 'Magnifique robe longue en tissu Kente authentique du Ghana, tissée à la main avec des fils de soie et d\'or. Idéale pour les cérémonies et les grandes occasions.',
                'base_price'   => 189.00,
                'fabric_type'  => 'Kente',
                'origin'       => 'Ghana',
                'is_active'    => true,
                'is_featured'  => true,
                'variants' => [
                    ['size' => 'S',  'color' => 'Or & Rouge',  'color_hex' => '#FFD700', 'stock_quantity' => 5],
                    ['size' => 'M',  'color' => 'Or & Rouge',  'color_hex' => '#FFD700', 'stock_quantity' => 8],
                    ['size' => 'L',  'color' => 'Or & Rouge',  'color_hex' => '#FFD700', 'stock_quantity' => 6],
                    ['size' => 'XL', 'color' => 'Or & Rouge',  'color_hex' => '#FFD700', 'stock_quantity' => 3],
                ],
            ],
            [
                'category_id'  => $robes->id,
                'name'         => 'Robe Bogolan Sahara',
                'slug'         => 'robe-bogolan-sahara',
                'description'  => 'Robe fluide en bogolan malien aux teintes terreuses naturelles. Teinture artisanale à la boue fermentée selon la tradition séculaire du Mali.',
                'base_price'   => 145.00,
                'fabric_type'  => 'Bogolan',
                'origin'       => 'Mali',
                'is_active'    => true,
                'is_featured'  => true,
                'variants' => [
                    ['size' => 'S',  'color' => 'Terre & Ocre', 'color_hex' => '#8B4513', 'stock_quantity' => 10],
                    ['size' => 'M',  'color' => 'Terre & Ocre', 'color_hex' => '#8B4513', 'stock_quantity' => 12],
                    ['size' => 'L',  'color' => 'Terre & Ocre', 'color_hex' => '#8B4513', 'stock_quantity' => 7],
                ],
            ],
            [
                'category_id'  => $boubous->id,
                'name'         => 'Grand Boubou Bazin Prestige',
                'slug'         => 'grand-boubou-bazin-prestige',
                'description'  => 'Grand boubou en bazin riche brodé main. Un vêtement de cérémonie d\'exception pour les grandes occasions. Broderies dorées sur col et manches.',
                'base_price'   => 245.00,
                'fabric_type'  => 'Bazin',
                'origin'       => 'Sénégal',
                'is_active'    => true,
                'is_featured'  => true,
                'variants' => [
                    ['size' => 'M',   'color' => 'Bleu Indigo', 'color_hex' => '#4B0082', 'stock_quantity' => 4],
                    ['size' => 'L',   'color' => 'Bleu Indigo', 'color_hex' => '#4B0082', 'stock_quantity' => 6],
                    ['size' => 'XL',  'color' => 'Bleu Indigo', 'color_hex' => '#4B0082', 'stock_quantity' => 5],
                    ['size' => 'XXL', 'color' => 'Bleu Indigo', 'color_hex' => '#4B0082', 'stock_quantity' => 3],
                    ['size' => 'M',   'color' => 'Blanc Nacré', 'color_hex' => '#FAFAFA', 'stock_quantity' => 5, 'price_modifier' => 10.00],
                    ['size' => 'L',   'color' => 'Blanc Nacré', 'color_hex' => '#FAFAFA', 'stock_quantity' => 6, 'price_modifier' => 10.00],
                ],
            ],
            [
                'category_id'  => $boubous->id,
                'name'         => 'Boubou Wax Ankara Modern',
                'slug'         => 'boubou-wax-ankara-modern',
                'description'  => 'Boubou contemporain en wax africain aux imprimés géométriques colorés. Une fusion réussie entre tradition et modernité.',
                'base_price'   => 95.00,
                'fabric_type'  => 'Wax Ankara',
                'origin'       => 'Côte d\'Ivoire',
                'is_active'    => true,
                'is_featured'  => false,
                'variants' => [
                    ['size' => 'S',  'color' => 'Rouge & Noir', 'color_hex' => '#DC143C', 'stock_quantity' => 15],
                    ['size' => 'M',  'color' => 'Rouge & Noir', 'color_hex' => '#DC143C', 'stock_quantity' => 20],
                    ['size' => 'L',  'color' => 'Rouge & Noir', 'color_hex' => '#DC143C', 'stock_quantity' => 12],
                    ['size' => 'XL', 'color' => 'Rouge & Noir', 'color_hex' => '#DC143C', 'stock_quantity' => 8],
                ],
            ],
        ];

        foreach ($products as $data) {
            $variants = $data['variants'];
            unset($data['variants']);

            $product = Product::firstOrCreate(
                ['slug' => $data['slug']],
                $data
            );

            foreach ($variants as $v) {
                $sku = strtoupper(substr($product->slug, 0, 6)) . '-' . strtoupper(substr($v['size'], 0, 2)) . '-' . strtoupper(substr($v['color'], 0, 3));
                ProductVariant::firstOrCreate(
                    ['product_id' => $product->id, 'size' => $v['size'], 'color' => $v['color']],
                    array_merge($v, [
                        'product_id'    => $product->id,
                        'sku'           => $sku,
                        'price_modifier'=> $v['price_modifier'] ?? 0.00,
                        'is_active'     => true,
                    ])
                );
            }
        }

        $this->command->info('✓ ' . count($products) . ' produits créés avec leurs variantes.');
    }
}
PHP);


// ════════════════════════════════════════════════════════════
//  FACTORIES
// ════════════════════════════════════════════════════════════

overwriteFile("$root/database/factories/UserFactory.php", <<<'PHP'
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'              => fake()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => Hash::make('password'),
            'role'              => 'client',
            'phone'             => fake()->phoneNumber(),
            'remember_token'    => Str::random(10),
        ];
    }

    public function admin(): static
    {
        return $this->state(['role' => 'admin']);
    }

    public function unverified(): static
    {
        return $this->state(['email_verified_at' => null]);
    }
}
PHP);

makeFile("$root/database/factories/ProductFactory.php", <<<'PHP'
<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    private static array $fabrics = ['Kente', 'Bogolan', 'Bazin', 'Wax Ankara', 'Adire', 'Dashiki'];
    private static array $origins = ['Ghana', 'Mali', 'Sénégal', 'Côte d\'Ivoire', 'Nigeria', 'Cameroun'];

    public function definition(): array
    {
        $name = fake()->words(3, true);
        return [
            'category_id'  => Category::inRandomOrder()->value('id') ?? 1,
            'name'         => ucwords($name),
            'slug'         => Str::slug($name) . '-' . fake()->unique()->numberBetween(100, 999),
            'description'  => fake()->paragraphs(2, true),
            'base_price'   => fake()->randomFloat(2, 60, 350),
            'fabric_type'  => fake()->randomElement(self::$fabrics),
            'origin'       => fake()->randomElement(self::$origins),
            'is_active'    => true,
            'is_featured'  => fake()->boolean(20),
        ];
    }
}
PHP);


// ════════════════════════════════════════════════════════════
//  ENREGISTREMENT DES MIDDLEWARES
//  (Laravel 11 : bootstrap/app.php)
// ════════════════════════════════════════════════════════════

$bootstrapPath = "$root/bootstrap/app.php";
if (file_exists($bootstrapPath)) {
    $content = file_get_contents($bootstrapPath);

    // Injecter l'alias middleware si pas déjà présent
    if (strpos($content, "'admin'") === false) {
        $needle  = '->withMiddleware(function (Middleware $middleware) {';
        $inject  = '->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            \'admin\'    => \App\Http\Middleware\IsAdmin::class,
            \'verified\' => \App\Http\Middleware\IsVerified::class,
        ]);';
        $content = str_replace($needle, $inject, $content);
        file_put_contents($bootstrapPath, $content);
        $created[] = "EDIT bootstrap/app.php (middlewares enregistrés)";
    } else {
        $skipped[] = "SKIP bootstrap/app.php (middlewares déjà présents)";
    }
}


// ════════════════════════════════════════════════════════════
//  ROUTE ADMIN (ajout du middleware admin)
// ════════════════════════════════════════════════════════════

$webPath = "$root/routes/web.php";
if (file_exists($webPath)) {
    $content = file_get_contents($webPath);
    if (strpos($content, "Route::prefix('admin')") === false
        && strpos($content, "admin.php") === false) {
        $content .= <<<'PHP'


// ── Admin (chargement du fichier routes/admin.php) ───────────────
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(base_path('routes/admin.php'));
PHP;
        file_put_contents($webPath, $content);
        $created[] = "EDIT routes/web.php (groupe admin ajouté)";
    }
}


// ─────────────────────────────────────────────
// RAPPORT FINAL
// ─────────────────────────────────────────────

echo "\n";
echo "╔══════════════════════════════════════════════════════════╗\n";
echo "║    AFRISOIE — Models & Migrations — Setup terminé !     ║\n";
echo "╠══════════════════════════════════════════════════════════╣\n";
echo "║  Fichiers créés / modifiés : " . str_pad(count($created), 24) . "║\n";
echo "║  Fichiers ignorés          : " . str_pad(count($skipped), 24) . "║\n";
echo "╠══════════════════════════════════════════════════════════╣\n";
echo "║  PROCHAINES ÉTAPES :                                     ║\n";
echo "║  1. php artisan migrate                                  ║\n";
echo "║  2. php artisan db:seed                                  ║\n";
echo "║  3. php artisan storage:link                             ║\n";
echo "║  4. Supprimer ce fichier setup-models-migrations.php     ║\n";
echo "╚══════════════════════════════════════════════════════════╝\n\n";

echo "Détail :\n";
foreach ($created as $f) echo "  ✓ $f\n";
if ($skipped) {
    echo "\nIgnorés :\n";
    foreach ($skipped as $f) echo "  - $f\n";
}
echo "\n";
