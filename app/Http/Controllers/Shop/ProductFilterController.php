<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductFilterController extends Controller
{
    public function latest(Request $request): JsonResponse
    {
        try {
            $tab = $request->get('tab', 'for-you');

            $products = Product::with([
                    'images'   => fn($q) => $q->where('is_primary', true)
                                              ->orderBy('sort_order'),
                    'variants' => fn($q) => $q->where('is_active', true)
                                              ->whereNotNull('color_hex')
                                              ->select('product_id', 'color', 'color_hex'),
                ])
                ->where('is_active', true)
                ->when($tab === 'eclatdeals', fn($q) => $q->whereNotNull('compare_price'))
                ->when($tab === 'clothing',   fn($q) => $q->latest())
                ->when(
                    in_array($tab, ['dresses', 'pants', 'kimonos', 'sets', 'shorts']),
                    fn($q) => $q->whereHas('category', fn($q) => $q->where('slug', $tab))
                )
                ->latest()
                ->take(8)
                ->get();

            $result = $products->map(function ($product) {
                $image  = $product->images->first();

                // Dédoublonnage des couleurs côté collection (pas de distinct SQL)
                $colors = $product->variants
                            ->unique('color_hex')
                            ->take(4)
                            ->values();

                return [
                    'name'          => $product->name,
                    'slug'          => $product->slug,
                    'base_price'    => number_format((float) $product->base_price, 0, ',', ' '),
                    'compare_price' => $product->compare_price
                                        ? number_format((float) $product->compare_price, 0, ',', ' ')
                                        : null,
                    'discount'      => $product->hasDiscount()
                                        ? $product->discountPercentage()
                                        : null,
                    'is_featured'   => (bool) $product->is_featured,
                    'image_url'     => $image ? asset('storage/' . $image->path) : null,
                    'image_alt'     => $image?->alt ?? $product->name,
                    'colors'        => $colors->map(fn($v) => [
                                            'hex'  => $v->color_hex,
                                            'name' => $v->color ?? '',
                                        ])->toArray(),
                    'url'           => route('shop.product', $product->slug),
                ];
            });

            return response()->json($result);

        } catch (\Throwable $e) {
            // Visible dans le navigateur ET dans storage/logs/laravel.log
            return response()->json([
                'error'   => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'trace'   => collect($e->getTrace())->take(5)->toArray(),
            ], 500);
        }
    }
}
