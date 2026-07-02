<?php

namespace App\Services\Shop;

use App\Contracts\Shop\ProductServiceInterface;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductService implements ProductServiceInterface
{
    public function paginate(Request $request, int $perPage = 16): LengthAwarePaginator
    {
        $query = Product::with(['category', 'images', 'variants'])
            ->where('is_active', true);

        // Filtre recherche
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtre catégorie
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filtre prix
        if ($request->filled('min_price')) {
            $query->where('base_price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('base_price', '<=', $request->max_price);
        }

        // Tri
        $sort = $request->get('sort', 'latest');
        switch($sort) {
            case 'price_asc':
                $query->orderBy('base_price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('base_price', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        return $query->paginate($perPage);
    }

    public function findBySlug(string $slug): Product
    {
        return Product::with(['category', 'images', 'variants', 'reviews.user'])
            ->where('is_active', true)
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function getCategories(): Collection
    {
        return Category::where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();
    }

    public function getFeatured(int $limit = 8): Collection
    {
        return Product::with(['category', 'images', 'variants'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getNewArrivals(int $limit = 8): Collection
    {
        return Product::with(['category', 'images', 'variants'])
            ->where('is_active', true)
            ->where('is_new', true)
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getBestSellers(int $limit = 8): Collection
    {
        return Product::with(['category', 'images', 'variants'])
            ->where('is_active', true)
            ->withCount('orderItems')
            ->orderByDesc('order_items_count')
            ->limit($limit)
            ->get();
    }

    public function search(string $query, array $filters = []): LengthAwarePaginator
    {
        $q = Product::with(['category', 'images', 'variants'])
            ->where('is_active', true)
            ->where(function ($builder) use ($query) {
                $builder->where('name', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%");
            });

        if (! empty($filters['category_id'])) {
            $q->where('category_id', $filters['category_id']);
        }

        if (! empty($filters['min_price'])) {
            $q->where('base_price', '>=', $filters['min_price']);
        }

        if (! empty($filters['max_price'])) {
            $q->where('base_price', '<=', $filters['max_price']);
        }

        $sort = $filters['sort'] ?? 'latest';
        switch($sort) {
            case 'price_asc':
                $q->orderBy('base_price', 'asc');
                break;
            case 'price_desc':
                $q->orderBy('base_price', 'desc');
                break;
            default:
                $q->latest();
                break;
        }

        return $q->paginate(16);
    }

    // ============================================
    // MÉTHODES POUR L'API
    // ============================================
    public function getLatest(string $tab = 'for-you', int $limit = 12): Collection
    {
        $query = Product::with(['variants', 'images' => function($q) {
                $q->orderBy('is_primary', 'desc')->orderBy('sort_order');
            }])
            ->where('is_active', true);

        // Filtres selon l'onglet
        switch($tab) {
            case 'new-in':
                $query->where('is_new', true)->latest();
                break;
            case 'eclatdeals':
                $query->whereNotNull('compare_price')
                      ->where('compare_price', '>', 0)
                      ->whereRaw('compare_price > base_price');
                break;
            case 'dresses':
                $query->whereHas('category', fn($q) => $q->where('slug', 'dresses'));
                break;
            case 'pants':
                $query->whereHas('category', fn($q) => $q->where('slug', 'pants'));
                break;
            case 'kimonos':
                $query->whereHas('category', fn($q) => $q->where('slug', 'kimonos'));
                break;
            case 'sets':
                $query->whereHas('category', fn($q) => $q->where('slug', 'matchingsets'));
                break;
            case 'shorts':
                $query->whereHas('category', fn($q) => $q->where('slug', 'shorts'));
                break;
            default:
                $query->where('is_featured', true);
                break;
        }

        return $query->limit($limit)->get();
    }

    // ============================================
    // FORMATER UN PRODUIT POUR L'API
    // ============================================
    public function formatForApi(Product $product): array
    {
        // Récupérer toutes les couleurs UNIQUES des variantes
        $colors = collect();
        if ($product->variants && $product->variants->count() > 0) {
            $colors = $product->variants
                ->groupBy('color')
                ->map(function($group, $color) {
                    return [
                        'name' => $color ?: 'Standard',
                        'hex' => $group->first()->color_hex ?? '#9D8E1C'
                    ];
                })
                ->values();
        }

        // Calcul de la réduction
        $discount = null;
        $hasDiscount = false;
        if ($product->compare_price && $product->compare_price > 0 && $product->base_price < $product->compare_price) {
            $hasDiscount = true;
            $discount = round((1 - $product->base_price / $product->compare_price) * 100);
        }

        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'url' => route('shop.product', $product->slug),
            'base_price' => number_format($product->base_price, 0, ',', ' '),
            'compare_price' => $product->compare_price ? number_format($product->compare_price, 0, ',', ' ') : null,
            'has_discount' => $hasDiscount,
            'discount' => $discount,
            'is_featured' => $product->is_featured,
            'is_new' => $product->is_new ?? false,
            'image_url' => $product->images->first() ? Storage::url($product->images->first()->path) : null,
            'image_alt' => $product->name,
            'colors' => $colors,
            'fabric_type' => $product->fabric_type,
            'category_name' => $product->category->name ?? null,
        ];
    }
}
