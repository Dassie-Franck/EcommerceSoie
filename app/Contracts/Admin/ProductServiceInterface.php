<?php

namespace App\Contracts\Admin;

use App\Models\Product;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProductServiceInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;
    public function store(StoreProductRequest $request): Product;
    public function update(UpdateProductRequest $request, Product $product): Product;
    public function delete(Product $product): void;
}