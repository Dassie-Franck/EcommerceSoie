<?php

// ═══════════════════════════════════════════════════════════════
// App\Http\Controllers\Admin\CategoryController.php
// ═══════════════════════════════════════════════════════════════

namespace App\Http\Controllers\Admin;

use App\Contracts\Admin\CategoryServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryServiceInterface $categoryService
    ) {
        $this->middleware(['auth', 'admin']);
    }

public function index(): View
{
    return view('admin.categories.index', [
        'categories' => $this->categoryService->paginate(17),
        'parents'    => Category::whereNull('parent_id')
                                ->where('is_active', true)
                                ->withoutTrashed()  // ← AJOUTE CETTE LIGNE
                                ->orderBy('name')
                                ->get(),
    ]);
}

 public function create(): View
{
    return view('admin.categories.create', [
        'parents' => Category::whereNull('parent_id')
                            ->where('is_active', true)
                            ->withoutTrashed()  // ← AJOUTE CETTE LIGNE
                            ->get(),
    ]);
}

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $this->categoryService->store($request);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie créée avec succès.');
    }

   public function edit(Category $category): View
{
    return view('admin.categories.edit', [
        'category' => $category,
        'parents'  => Category::whereNull('parent_id')
                            ->where('is_active', true)
                            ->where('id', '!=', $category->id)
                            ->withoutTrashed()  // ← AJOUTE CETTE LIGNE
                            ->get(),
    ]);
}

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $this->categoryService->update($request, $category);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie mise à jour.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        try {
            $this->categoryService->delete($category);

            return redirect()->route('admin.categories.index')
                ->with('success', 'Catégorie supprimée.');

        } catch (\DomainException $e) {
            return back()->with('error',
                'Impossible de supprimer une catégorie contenant des produits actifs.'
            );
        }
    }
}
