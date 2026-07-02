<?php

namespace App\Services\Shop;

use App\Contracts\Shop\CartServiceInterface;
use App\Http\Requests\Shop\AddToCartRequest;
use App\Http\Requests\Shop\UpdateCartRequest;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
class CartService implements CartServiceInterface
{
    public function getCart(): Cart
    {
        if (Auth::check()) {
            return Cart::firstOrCreate(['user_id' => Auth::id()]);
        }
        return Cart::firstOrCreate(['session_id' => session()->getId()]);
    }

    public function mergeSessionCart(): void
    {
        if (! Auth::check()) return;

        $sessionCart = Cart::where('session_id', session()->getId())
            ->whereNull('user_id')
            ->with('items')
            ->first();

        if (! $sessionCart || $sessionCart->items->isEmpty()) return;

        $userCart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        foreach ($sessionCart->items as $sessionItem) {
            $existing = $userCart->items()
                ->where('product_variant_id', $sessionItem->product_variant_id)
                ->first();

            if ($existing) {
                $variant = $sessionItem->productVariant;
                $newQty  = min(
                    $existing->quantity + $sessionItem->quantity,
                    $variant->stock_quantity
                );
                $existing->update(['quantity' => $newQty]);
            } else {
                $userCart->items()->create([
                    'product_variant_id' => $sessionItem->product_variant_id,
                    'quantity'           => $sessionItem->quantity,
                ]);
            }
        }

        $sessionCart->items()->delete();
        $sessionCart->delete();
    }

    public function add(AddToCartRequest $request): array
    {
        $variant = ProductVariant::findOrFail($request->variant_id);

        if ($variant->stock_quantity < $request->quantity) {
            throw new \DomainException('stock_insufficient:' . $variant->stock_quantity);
        }

        $cart = $this->getCart();
        $item = $cart->items()
            ->where('product_variant_id', $variant->id)
            ->first();

        if ($item) {
            $newQty = $item->quantity + $request->quantity;
            if ($newQty > $variant->stock_quantity) {
                throw new \DomainException('stock_cart_overflow:' . $item->quantity);
            }
            $item->update(['quantity' => $newQty]);
        } else {
            $cart->items()->create([
                'product_variant_id' => $variant->id,
                'quantity'           => $request->quantity,
            ]);
        }

        return ['cart_count' => $cart->items()->sum('quantity')];
    }

    public function update(UpdateCartRequest $request, CartItem $item): void
    {
        $this->authorizeItem($item);
        $variant = $item->productVariant;

        if ($request->quantity > $variant->stock_quantity) {
            throw new \DomainException('stock_insufficient:' . $variant->stock_quantity);
        }

        $item->update(['quantity' => $request->quantity]);
    }

    public function remove(CartItem $item): void
    {
        $this->authorizeItem($item);
        $item->delete();
    }

    public function clear(): void
    {
        $this->getCart()->items()->delete();
    }

    public function authorizeItem(CartItem $item): void
    {
        $cart = $item->cart;
        $ok   = Auth::check()
            ? $cart->user_id === Auth::id()
            : $cart->session_id === session()->getId();

        if (! $ok) abort(403);
    }
}