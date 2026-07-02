<?php
// app/Contracts/Shop/ProductServiceInterface.php

namespace App\Contracts\Shop;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Models\Product;

interface ProductServiceInterface
{
    public function paginate(Request $request, int $perPage = 16): LengthAwarePaginator;
    public function findBySlug(string $slug): Product;
    public function getCategories(): Collection;
    public function getFeatured(int $limit = 8): Collection;
    public function getNewArrivals(int $limit = 8): Collection;
    public function getBestSellers(int $limit = 8): Collection;
    public function search(string $query, array $filters = []): LengthAwarePaginator;
    public function getLatest(string $tab = 'for-you', int $limit = 12): Collection;
    public function formatForApi(Product $product): array;
}
