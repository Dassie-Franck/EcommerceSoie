<?php

namespace App\Services\Shop;

use App\Models\User;
use App\Models\Wishlist;

class WishlistService
{
    public function getForUser(User $user): ?Wishlist
    {
        return $user->wishlist()
            ->with(['items.productVariant.product.images'])
            ->first();
    }

    public function toggle(User $user, int $productVariantId): array
    {
        // S'assurer que la wishlist existe
        $wishlist = $user->wishlist;

        if (!$wishlist) {
            $wishlist = Wishlist::create(['user_id' => $user->id]);
        }

        if ($wishlist->hasProductVariant($productVariantId)) {
            $wishlist->removeProductVariant($productVariantId);

            return [
                'added'   => false,
                'message' => 'Produit retiré de vos favoris.',
                'count'   => $wishlist->items()->count(),
            ];
        }

        $wishlist->addProductVariant($productVariantId);

        return [
            'added'   => true,
            'message' => 'Produit ajouté à vos favoris.',
            'count'   => $wishlist->items()->count(),
        ];
    }

    public function count(User $user): int
    {
        $wishlist = $user->wishlist;
        return $wishlist ? $wishlist->items()->count() : 0;
    }
}
