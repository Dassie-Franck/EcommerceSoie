<?php

namespace App\Services\Admin;

use App\Contracts\Admin\ProductServiceInterface;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService implements ProductServiceInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Product::with('category')
            ->withCount('variants')
            ->latest()
            ->paginate($perPage);
    }

    public function store(StoreProductRequest $request): Product
    {
        return DB::transaction(function () use ($request) {

            $product = Product::create([
                'name'              => $request->name,
                'slug'              => $this->uniqueSlug($request->name),
                'category_id'       => $request->category_id,
                'description'       => $request->description,
                'base_price'        => $request->base_price,
                'compare_price'     => $request->compare_price,
                'fabric_type'       => $request->fabric_type,
                'origin'            => $request->origin,
                'care_instructions' => $request->care_instructions,
                'is_active'         => $request->boolean('is_active'),
                'is_featured'       => $request->boolean('is_featured'),
                'meta_title'        => $request->meta_title,
                'meta_description'  => $request->meta_description,
            ]);

            // Stocker les images avec gestion de l'image principale
            $this->storeImages($request, $product);

            // Créer les variantes si présentes
            if ($request->has('variants')) {
                $this->storeVariants($request, $product);
            }

            return $product;
        });
    }

    public function update(UpdateProductRequest $request, Product $product): Product
    {
        return DB::transaction(function () use ($request, $product) {

            // === 1. SUPPRIMER LES IMAGES COCHÉES ===
            if ($request->filled('delete_images_ids')) {
                $deleteIds = explode(',', $request->delete_images_ids);
                ProductImage::whereIn('id', $deleteIds)
                    ->where('product_id', $product->id)
                    ->each(function (ProductImage $img) {
                        Storage::disk('public')->delete($img->path);
                        $img->delete();
                    });
            }

            // === 2. GARDER UNIQUEMENT CERTAINES IMAGES ===
            if ($request->filled('keep_images_ids')) {
                $keepIds = json_decode($request->keep_images_ids, true);
                ProductImage::where('product_id', $product->id)
                    ->whereNotIn('id', $keepIds)
                    ->each(function (ProductImage $img) {
                        Storage::disk('public')->delete($img->path);
                        $img->delete();
                    });
            }

            // === 3. AJOUTER LES NOUVELLES IMAGES ===
            if ($request->hasFile('new_images')) {
                $this->storeNewImages($request, $product);
            }

            // === 4. METTRE À JOUR L'IMAGE PRINCIPALE PARMI LES IMAGES EXISTANTES ===
            if ($request->filled('primary_image_id')) {
                ProductImage::where('product_id', $product->id)->update(['is_primary' => false]);
                ProductImage::where('product_id', $product->id)
                    ->where('id', $request->primary_image_id)
                    ->update(['is_primary' => true]);
            }

            // === 5. METTRE À JOUR LE PRODUIT ===
            $product->update([
                'name'              => $request->name              ?? $product->name,
                'category_id'       => $request->category_id       ?? $product->category_id,
                'description'       => $request->description       ?? $product->description,
                'base_price'        => $request->base_price        ?? $product->base_price,
                'compare_price'     => $request->compare_price,
                'fabric_type'       => $request->fabric_type       ?? $product->fabric_type,
                'origin'            => $request->origin,
                'care_instructions' => $request->care_instructions,
                'is_active'         => $request->boolean('is_active'),
                'is_featured'       => $request->boolean('is_featured'),
                'meta_title'        => $request->meta_title,
                'meta_description'  => $request->meta_description,
            ]);

            // === 6. METTRE À JOUR LES VARIANTES ===
            if ($request->has('variants')) {
                $this->updateVariants($request, $product);
            }

            return $product->fresh();
        });
    }

    public function delete(Product $product): void
    {
        DB::transaction(function () use ($product) {
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->path);
            }
            $product->delete();
        });
    }

    // ── Helpers privés ────────────────────────────────────────

    private function uniqueSlug(string $name): string
    {
        $base  = Str::slug($name);
        $slug  = $base;
        $count = 1;

        while (Product::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $count++;
        }

        return $slug;
    }

    /**
     * Stocke les images lors de la création d'un produit
     * ✅ CORRIGÉ : Prend en compte l'indicateur d'image principale
     */
    private function storeImages(FormRequest $request, Product $product, int $offset = 0): void
    {
        if (! $request->hasFile('images')) {
            return;
        }

        $categorySlug = $product->category->slug ?? 'general';
        $primarySet = false;

        // Récupérer l'index de l'image principale depuis le formulaire
        $primaryIndex = $request->input('primary_image_index', 0);
        $primaryIndex = is_numeric($primaryIndex) ? (int)$primaryIndex : 0;

        foreach ($request->file('images') as $index => $file) {
            // Générer un nom lisible
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $safeName = Str::slug($originalName) . '-' . time() . '-' . rand(100, 999) . '.' . $extension;

            // Chemin organisé par catégorie
            $path = 'products/' . $categorySlug . '/' . $safeName;

            // Stocker l'image
            Storage::disk('public')->put($path, file_get_contents($file));

            // ✅ L'image principale est celle dont l'index correspond à primary_index
            $isPrimary = ($index === $primaryIndex) && !$primarySet;
            if ($isPrimary) $primarySet = true;

            ProductImage::create([
                'product_id' => $product->id,
                'path'       => $path,
                'alt'        => $product->name,
                'is_primary' => $isPrimary,
                'sort_order' => $offset + $index + 1,
            ]);
        }

        // Si aucune image principale n'a été définie, mettre la première comme principale
        if (!$primarySet && $product->images()->count() > 0) {
            $firstImage = $product->images()->first();
            $firstImage->is_primary = true;
            $firstImage->save();
        }
    }

    /**
     * Stocke les nouvelles images lors de la mise à jour
     * ✅ CORRIGÉ : Prend en compte l'indicateur d'image principale
     */
    private function storeNewImages(UpdateProductRequest $request, Product $product): void
    {
        $categorySlug = $product->category->slug ?? 'general';
        $currentCount = $product->images()->count();

        // Récupérer les indices des images principales
        $primaryIndices = $request->input('new_images_primary', []);

        // Compter combien d'images existantes sont déjà principales
        $hasExistingPrimary = $product->images()->where('is_primary', true)->exists();

        foreach ($request->file('new_images') as $index => $file) {
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $safeName = Str::slug($originalName) . '-' . time() . '-' . rand(100, 999) . '.' . $extension;
            $path = 'products/' . $categorySlug . '/' . $safeName;

            Storage::disk('public')->put($path, file_get_contents($file));

            // ✅ Vérifier si cette image doit être principale
            $isPrimary = false;

            // Si l'utilisateur a spécifié cette image comme principale
            if (isset($primaryIndices[$index]) && $primaryIndices[$index] == '1') {
                $isPrimary = true;
                // Si on définit une nouvelle image principale, déclasser l'ancienne
                if ($hasExistingPrimary) {
                    $product->images()->where('is_primary', true)->update(['is_primary' => false]);
                    $hasExistingPrimary = false;
                }
            }

            // Si aucune image principale n'existe, la première devient principale
            if (!$hasExistingPrimary && !$isPrimary && $index === 0 && $product->images()->count() === 0) {
                $isPrimary = true;
                $hasExistingPrimary = true;
            }

            ProductImage::create([
                'product_id' => $product->id,
                'path'       => $path,
                'alt'        => $product->name,
                'is_primary' => $isPrimary,
                'sort_order' => $currentCount + $index + 1,
            ]);
        }
    }

    /**
     * Stocke les variantes lors de la création
     */
    private function storeVariants(StoreProductRequest $request, Product $product): void
    {
        foreach ($request->variants as $variantData) {
            if (isset($variantData['stock_quantity']) && $variantData['stock_quantity'] > 0) {
                $sku = $this->generateSku($product, $variantData);

                ProductVariant::create([
                    'product_id'     => $product->id,
                    'size'           => $variantData['size'],
                    'color'          => $variantData['color'],
                    'color_hex'      => $variantData['color_hex'] ?? '#9D8E1C',
                    'sku'            => $sku,
                    'stock_quantity' => $variantData['stock_quantity'],
                    'price_modifier' => $variantData['price_modifier'] ?? 0,
                    'is_active'      => true,
                ]);
            }
        }
    }

    /**
     * Met à jour les variantes
     */
    private function updateVariants(UpdateProductRequest $request, Product $product): void
    {
        // Récupérer les IDs des variantes existantes dans la requête
        $existingVariantIds = collect($request->variants)
            ->filter(fn($v) => isset($v['id']) && !empty($v['id']))
            ->pluck('id')
            ->toArray();

        // Supprimer les variantes qui ne sont plus dans la liste
        $product->variants()
            ->whereNotIn('id', $existingVariantIds)
            ->delete();

        // Mettre à jour ou créer les variantes
        foreach ($request->variants as $variantData) {
            if (isset($variantData['stock_quantity']) && $variantData['stock_quantity'] > 0) {
                $variant = $product->variants()->updateOrCreate(
                    ['id' => $variantData['id'] ?? null],
                    [
                        'size'           => $variantData['size'],
                        'color'          => $variantData['color'],
                        'color_hex'      => $variantData['color_hex'] ?? '#9D8E1C',
                        'stock_quantity' => $variantData['stock_quantity'],
                        'price_modifier' => $variantData['price_modifier'] ?? 0,
                        'is_active'      => true,
                    ]
                );

                // Générer un SKU si le variant est nouveau
                if (!$variant->sku) {
                    $variant->sku = $this->generateSku($product, $variantData);
                    $variant->save();
                }
            }
        }
    }

    /**
     * Génère un SKU unique pour une variante
     */
    private function generateSku(Product $product, array $variantData): string
    {
        $categoryPrefix = strtoupper(substr($product->category->slug ?? 'PRD', 0, 3));
        $colorPrefix = strtoupper(substr(Str::slug($variantData['color']), 0, 3));
        $size = $variantData['size'];
        $random = rand(1000, 9999);

        return "{$categoryPrefix}-{$colorPrefix}-{$size}-{$random}";
    }
}
