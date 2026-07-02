<?php
// ═══════════════════════════════════════════════════════════════
// App\Http\Controllers\Shop\OrderController.php
// ═══════════════════════════════════════════════════════════════
 
namespace App\Http\Controllers\Shop;
 
use App\Contracts\Shop\OrderServiceInterface;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
 
class OrderController extends Controller
{
    public function __construct(
        private readonly OrderServiceInterface $orderService
    ) {
        $this->middleware('auth');
    }
 
    public function index(): View
    {
        return view('account.orders', [
            'orders' => $this->orderService->getForUser(Auth::user()),
        ]);
    }
 
    public function show(Order $order): View
    {
        return view('account.order-detail', [
            'order' => $this->orderService->findForUser($order->id, Auth::id()),
        ]);
    }
 
    public function cancel(Order $order): RedirectResponse
    {
        try {
            $this->orderService->cancel($order, Auth::id());
 
            return back()->with('success', 'Commande annulée avec succès.');
 
        } catch (\DomainException $e) {
            return back()->with('error', 'Cette commande ne peut plus être annulée.');
        }
    }
}
 