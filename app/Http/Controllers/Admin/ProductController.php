<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Admin\ProductServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;  // ← AJOUTER CETTE LIGNE
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;  // ← AJOUTER CETTE LIGNE

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductServiceInterface $productService
    ) {
        $this->middleware(['auth', 'admin']);
    }

    public function index(): View
    {
        return view('admin.products.index', [
            'products'   => $this->productService->paginate(15),
            'categories' => Category::where('is_active', true)
                                    ->orderBy('name')
                                    ->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.products.create', [
            'categories' => Category::where('is_active', true)->get(),
        ]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $this->productService->store($request);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit créé avec succès.');
    }

    public function edit(Product $product): View
    {
        $product->load('images', 'variants');

        return view('admin.products.edit', [
            'product'    => $product,
            'categories' => Category::where('is_active', true)->get(),
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $this->productService->update($request, $product);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit mis à jour.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->productService->delete($product);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit supprimé.');
    }

    /**
     * Récupère les données d'un produit pour l'édition via AJAX
     */
    public function getEditData(Product $product): JsonResponse
    {
        $product->load('images', 'variants');

        // Extraire les couleurs uniques des variantes
        $colors = $product->variants
            ->groupBy('color')
            ->map(function($group, $color) {
                return [
                    'name' => $color,
                    'hex' => $group->first()->color_hex ?? '#9D8E1C'
                ];
            })
            ->values();

        // Extraire les tailles uniques
        $sizes = $product->variants->pluck('size')->unique()->values();

        // Formater les images
        $images = $product->images->map(function($img) {
            return [
                'id' => $img->id,
                'path' => Storage::url($img->path),
                'name' => $img->alt ?? 'image',
                'is_primary' => $img->is_primary
            ];
        });

        // Formater les variantes
        $variants = $product->variants->map(function($variant) {
            return [
                'id' => $variant->id,
                'size' => $variant->size,
                'color' => $variant->color,
                'stock_quantity' => $variant->stock_quantity
            ];
        });

        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'category_id' => $product->category_id,
                'description' => $product->description,
                'base_price' => $product->base_price,
                'compare_price' => $product->compare_price,
                'fabric_type' => $product->fabric_type,
                'origin' => $product->origin,
                'care_instructions' => $product->care_instructions,
                'is_active' => $product->is_active,
                'is_featured' => $product->is_featured,
            ],
            'colors' => $colors,
            'sizes' => $sizes,
            'images' => $images,
            'variants' => $variants
        ]);
    }
}
