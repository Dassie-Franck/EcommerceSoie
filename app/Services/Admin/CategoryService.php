<?php

namespace App\Services\Admin;

use App\Contracts\Admin\CategoryServiceInterface;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryService implements CategoryServiceInterface
{
     public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Category::withoutTrashed()  // ← CHANGÉ : avecTrashed() → withoutTrashed()
            ->with('parent')
            ->withCount('products')
            ->orderBy('sort_order')
            ->paginate($perPage);
    }

    public function store(StoreCategoryRequest $request): Category
    {
        return DB::transaction(function () use ($request) {

            $imagePath = $request->hasFile('image')
                ? $request->file('image')->store('categories', 'public')
                : null;

            return Category::create([
                'name'        => $request->name,
                'slug'        => $this->generateSlug($request->name),
                'parent_id'   => $request->parent_id,
                'description' => $request->description,
                'image'       => $imagePath,
                'is_active'   => $request->boolean('is_active', true),
                'sort_order'  => $request->sort_order ?? 0,
            ]);
        });
    }

    public function update(UpdateCategoryRequest $request, Category $category): Category
    {
        return DB::transaction(function () use ($request, $category) {

            if ($request->hasFile('image')) {
                // Supprimer l'ancienne image
                if ($category->image && Storage::disk('public')->exists($category->image)) {
                    Storage::disk('public')->delete($category->image);
                }
                $category->image = $request->file('image')->store('categories', 'public');
            }

            $category->update([
                'name'        => $request->name        ?? $category->name,
                'parent_id'   => $request->parent_id,
                'description' => $request->description,
                'image'       => $category->image,
                'is_active'   => $request->boolean('is_active'),
                'sort_order'  => $request->sort_order  ?? $category->sort_order,
            ]);

            return $category->fresh();
        });
    }

    public function delete(Category $category): void
    {
        //  Bloquer si produits actifs liés
        $hasActiveProducts = $category->products()
            ->where('is_active', true)
            ->exists();

        if ($hasActiveProducts) {
            throw new \DomainException('has_active_products');
        }

        DB::transaction(function () use ($category) {
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }
            $category->delete();
        });
    }

    // ── HELPER PRIVÉ ──────────────────────────────────────────

    private function generateSlug(string $name): string
    {
        $slug         = Str::slug($name);
        $originalSlug = $slug;
        $count        = 1;

        while (Category::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }
}
