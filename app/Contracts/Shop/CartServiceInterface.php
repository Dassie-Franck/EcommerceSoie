<?php

namespace App\Contracts\Shop;

use App\Models\Cart;
use App\Models\CartItem;
use App\Http\Requests\Shop\AddToCartRequest;
use App\Http\Requests\Shop\UpdateCartRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
interface CartServiceInterface
{
    public function getCart(): Cart;
    public function mergeSessionCart(): void;
    public function add(AddToCartRequest $request): array;
    public function update(UpdateCartRequest $request, CartItem $item): void;
    public function remove(CartItem $item): void;
    public function clear(): void;
    public function authorizeItem(CartItem $item): void;
}