<?php 


// ═══════════════════════════════════════════════════════════════
// App\Http\Controllers\Shop\CheckoutController.php
// ═══════════════════════════════════════════════════════════════
 
namespace App\Http\Controllers\Shop;
 
use App\Contracts\Shop\CheckoutServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\CheckoutRequest;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
 
class CheckoutController extends Controller
{
    public function __construct(
        private readonly CheckoutServiceInterface $checkoutService
    ) {
        $this->middleware('auth');
    }
 
    public function index(): View|RedirectResponse
    {
        try {
            $data = $this->checkoutService->getCheckoutData(Auth::id());
            return view('shop.checkout', $data);
 
        } catch (\DomainException $e) {
            return redirect()->route('shop.cart')
                ->with('error', 'Votre panier est vide.');
        }
    }
 
    public function process(CheckoutRequest $request): RedirectResponse
    {
        try {
            $order = $this->checkoutService->process($request, Auth::id());
 
            session(['pending_order_id' => $order->id]);
 
            return redirect()->route('shop.checkout.confirmation')
                ->with('success', 'Commande passée avec succès !');
 
        } catch (\DomainException $e) {
            $msg = $e->getMessage();
 
            $error = match(true) {
                $msg === 'cart_empty'          => 'Votre panier est vide.',
                $msg === 'variant_unavailable' => "Un produit n'est plus disponible.",
                str_starts_with($msg, 'stock_insufficient:') => $this->stockError($msg),
                default => 'Une erreur est survenue. Veuillez réessayer.',
            };
 
            return back()->with('error', $error);
        }
    }
 
    public function success(Request $request): RedirectResponse
    {
        $orderId = session('pending_order_id');
 
        if (! $orderId) {
            return redirect()->route('shop.home');
        }
 
        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id()) // 🔐 ownership
            ->firstOrFail();
 
        $this->checkoutService->confirmPayment($order, $request);
 
        session()->forget('pending_order_id');
 
        return redirect()->route('shop.checkout.confirmation')
            ->with('success', 'Paiement confirmé !');
    }
 
    public function cancel(): RedirectResponse
    {
        if ($orderId = session('pending_order_id')) {
            $this->checkoutService->cancelOrder($orderId, Auth::id());
            session()->forget('pending_order_id');
        }
 
        return redirect()->route('shop.cart')
            ->with('error', 'Paiement annulé.');
    }
 
    public function confirmation(): View|RedirectResponse
    {
        if (! session('pending_order_id') && ! session('success')) {
            return redirect()->route('shop.home');
        }
 
        return view('shop.confirmation');
    }
 
    private function stockError(string $msg): string
    {
        $parts = explode(':', $msg);
        return "Stock insuffisant pour \"{$parts[1]}\". Disponible : {$parts[2]}, demandé : {$parts[3]}.";
    }
}
 