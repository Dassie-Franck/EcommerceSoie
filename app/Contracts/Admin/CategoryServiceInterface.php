<?php

namespace App\Contracts\Admin;

use App\Models\Category;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface CategoryServiceInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;
    public function store(StoreCategoryRequest $request): Category;
    public function update(UpdateCategoryRequest $request, Category $category): Category;
    public function delete(Category $category): void;
}