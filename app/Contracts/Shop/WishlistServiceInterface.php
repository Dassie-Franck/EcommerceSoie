<?php

namespace App\Contracts\Shop;

use App\Models\User;
use App\Models\Wishlist;

interface WishlistServiceInterface
{
    public function getForUser(User $user): ?Wishlist;
    public function toggle(User $user, int $productId): array;
}