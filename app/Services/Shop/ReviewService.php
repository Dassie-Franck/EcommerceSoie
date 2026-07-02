<?php

namespace App\Services\Shop;

use App\Contracts\Shop\ReviewServiceInterface;
use App\Http\Requests\Shop\ReviewRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;

class ReviewService implements ReviewServiceInterface
{
    public function store(ReviewRequest $request, Product $product, int $userId): void
    {
        //  Produit actif
        abort_if(! $product->is_active, 404);

        //  A bien acheté le produit
        $hasPurchased = Order::where('user_id', $userId)
            ->whereIn('status', ['completed', 'delivered'])
            ->whereHas('items', function ($query) use ($product) {
                $query->whereHas('productVariant', function ($q) use ($product) {
                    $q->where('product_id', $product->id);
                });
            })
            ->exists();

        if (! $hasPurchased) {
            throw new \DomainException('not_purchased');
        }

        //  Un seul avis par produit
        $alreadyReviewed = Review::where('user_id', $userId)
            ->where('product_id', $product->id)
            ->exists();

        if ($alreadyReviewed) {
            throw new \DomainException('already_reviewed');
        }

        // Récupérer la commande liée la plus récente
        $order = Order::where('user_id', $userId)
            ->whereIn('status', ['completed', 'delivered'])
            ->whereHas('items', function ($query) use ($product) {
                $query->whereHas('productVariant', function ($q) use ($product) {
                    $q->where('product_id', $product->id);
                });
            })
            ->latest()
            ->first();

        Review::create([
            'user_id'     => $userId,
            'product_id'  => $product->id,
            'order_id'    => $order?->id,
            'rating'      => $request->rating,
            'title'       => $request->title,
            'comment'     => $request->comment,
            'is_approved' => false,
        ]);
    }
}
