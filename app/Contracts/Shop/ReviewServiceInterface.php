<?php

namespace App\Contracts\Shop;

use App\Models\Product;
use App\Http\Requests\Shop\ReviewRequest;

interface ReviewServiceInterface
{
    public function store(ReviewRequest $request, Product $product, int $userId): void;
}