<?php

// ═══════════════════════════════════════════════════════════════
// App\Http\Controllers\Shop\CartController.php
// ═══════════════════════════════════════════════════════════════
 
namespace App\Http\Controllers\Shop;
 
use App\Contracts\Shop\CartServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\AddToCartRequest;
use App\Http\Requests\Shop\UpdateCartRequest;
use App\Models\CartItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
 
class CartController extends Controller
{
    public function __construct(
        private readonly CartServiceInterface $cartService
    ) {}
 
    // Méthode statique conservée pour compatibilité avec AuthService
    public static function mergeSessionCart(): void
    {
        app(CartServiceInterface::class)->mergeSessionCart();
    }
 
    public function index(): View
    {
        $cart = $this->cartService->getCart()
            ->load('items.productVariant.product.images');
 
        return view('shop.cart', compact('cart'));
    }
 
    public function add(AddToCartRequest $request): RedirectResponse|JsonResponse
    {
        try {
            $result  = $this->cartService->add($request);
            $message = 'Produit ajouté au panier.';
 
            return $request->expectsJson()
                ? response()->json(['success' => true, 'message' => $message, ...$result])
                : back()->with('success', $message);
 
        } catch (\DomainException $e) {
            $msg = $e->getMessage();
            $message = match(true) {
                str_starts_with($msg, 'stock_insufficient:')  => 'Stock insuffisant. Disponible : ' . explode(':', $msg)[1],
                str_starts_with($msg, 'stock_cart_overflow:') => 'Vous en avez déjà ' . explode(':', $msg)[1] . ' dans votre panier.',
                default => 'Erreur lors de l\'ajout au panier.',
            };
 
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $message], 422)
                : back()->with('error', $message);
        }
    }
 
    public function update(UpdateCartRequest $request, int $itemId): RedirectResponse|JsonResponse
    {
        $item = CartItem::findOrFail($itemId);
 
        try {
            $this->cartService->update($request, $item);
            $message = 'Panier mis à jour.';
 
            return $request->expectsJson()
                ? response()->json(['success' => true, 'message' => $message])
                : back()->with('success', $message);
 
        } catch (\DomainException $e) {
            $message = 'Stock insuffisant. Disponible : ' . explode(':', $e->getMessage())[1];
 
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $message], 422)
                : back()->with('error', $message);
        }
    }
 
    public function remove(int $itemId): RedirectResponse|JsonResponse
    {
        $item    = CartItem::findOrFail($itemId);
        $message = 'Article retiré du panier.';
 
        $this->cartService->remove($item);
 
        return request()->expectsJson()
            ? response()->json(['success' => true, 'message' => $message])
            : back()->with('success', $message);
    }
 
    public function clear(): RedirectResponse|JsonResponse
    {
        $this->cartService->clear();
        $message = 'Panier vidé.';
 
        return request()->expectsJson()
            ? response()->json(['success' => true, 'message' => $message])
            : back()->with('success', $message);
    }
}