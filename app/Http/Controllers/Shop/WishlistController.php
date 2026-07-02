<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Services\Shop\WishlistService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WishlistController extends Controller
{
    protected $wishlistService;

    public function __construct(WishlistService $wishlistService)
    {
        $this->middleware('auth');
        $this->wishlistService = $wishlistService;
    }

    public function index(): View
    {
        $wishlist = $this->wishlistService->getForUser(Auth::user());

        return view('account.wishlist', [
            'wishlistItems' => $wishlist?->items ?? collect(),
        ]);
    }

    public function toggle(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'product_variant_id' => ['required', 'integer', 'exists:product_variants,id'],
        ]);

        try {
            $result = $this->wishlistService->toggle(Auth::user(), $request->product_variant_id);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'added' => $result['added'],
                    'message' => $result['message'],
                    'count' => $result['count']
                ]);
            }

            return back()->with('success', $result['message']);

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Une erreur est survenue.'
                ], 500);
            }

            return back()->with('error', 'Une erreur est survenue.');
        }
    }

    public function remove(Request $request, int $itemId): RedirectResponse|JsonResponse
    {
        try {
            $wishlist = Auth::user()->wishlist;

            if ($wishlist) {
                $wishlist->items()->where('id', $itemId)->delete();

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Article retiré des favoris.',
                        'count' => $wishlist->items()->count()
                    ]);
                }
            }

            return redirect()->route('account.wishlist')->with('success', 'Article retiré des favoris.');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Une erreur est survenue.'
                ], 500);
            }

            return back()->with('error', 'Une erreur est survenue.');
        }
    }
}
