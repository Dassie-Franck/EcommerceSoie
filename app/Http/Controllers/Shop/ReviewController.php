<?php

// ═══════════════════════════════════════════════════════════════
// App\Http\Controllers\Shop\ReviewController.php
// ═══════════════════════════════════════════════════════════════
 
namespace App\Http\Controllers\Shop;
 
use App\Contracts\Shop\ReviewServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\ReviewRequest;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
 
class ReviewController extends Controller
{
    public function __construct(
        private readonly ReviewServiceInterface $reviewService
    ) {
        $this->middleware('auth');
    }
 
    public function store(ReviewRequest $request, Product $product): RedirectResponse
    {
        try {
            $this->reviewService->store($request, $product, Auth::id());
 
            return back()->with(
                'success',
                'Votre avis a été soumis et sera publié après modération.'
            );
 
        } catch (\DomainException $e) {
            $error = match($e->getMessage()) {
                'not_purchased'   => 'Vous devez avoir acheté ce produit pour laisser un avis.',
                'already_reviewed'=> 'Vous avez déjà laissé un avis pour ce produit.',
                default           => 'Une erreur est survenue.',
            };
 
            return back()->with('error', $error);
        }
    }
}
 