<?php
// app/Http/Controllers/Shop/ProductController.php

namespace App\Http\Controllers\Shop;

use App\Contracts\Shop\ProductServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductServiceInterface $productService
    ) {}

    public function index(Request $request): View
    {
        return view('shop.catalogue', [
            'products'   => $this->productService->paginate($request, 16),
            'categories' => $this->productService->getCategories(),
        ]);
    }

    public function show(string $slug): View
    {
        $product = $this->productService->findBySlug($slug);
        return view('shop.product', compact('product'));
    }

    public function latest(Request $request): JsonResponse
    {
        $tab = $request->get('tab', 'for-you');
        $products = $this->productService->getLatest($tab, 12);

        $formattedProducts = $products->map(function($product) {
            return $this->productService->formatForApi($product);
        });

        return response()->json($formattedProducts);
    }
}
