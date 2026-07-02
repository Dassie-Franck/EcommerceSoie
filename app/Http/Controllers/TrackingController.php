<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\DHLTrackingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackingController extends Controller
{
    protected $dhlTracking;

    public function __construct(DHLTrackingService $dhlTracking)
    {
        $this->dhlTracking = $dhlTracking;
    }

    /**
     * Formulaire de suivi (public)
     */
    public function form()
    {
        return view('tracking.form');
    }

    /**
     * Rechercher une commande par référence et email
     */
    public function lookup(Request $request)
    {
        $request->validate([
            'order_reference' => 'required|string',
            'email' => 'required|email'
        ]);

        $order = Order::where('reference', $request->order_reference)
            ->where(function ($query) use ($request) {
                $query->where('email', $request->email)
                    ->orWhereHas('user', function ($q) use ($request) {
                        $q->where('email', $request->email);
                    });
            })
            ->first();

        if (!$order) {
            return back()->withErrors(['error' => 'Commande introuvable. Vérifiez vos informations.'])
                ->withInput();
        }

        if (!$order->tracking_number) {
            return back()->withErrors(['error' => 'Cette commande n\'a pas encore été expédiée.']);
        }

        return redirect()->route('tracking.result', [
            'reference' => $order->reference,
            'email' => $order->email ?? $order->user->email
        ]);
    }

    /**
     * Afficher le résultat du suivi
     */
    public function result($reference, $email)
    {
        $order = Order::where('reference', $reference)
            ->where(function ($query) use ($email) {
                $query->where('email', $email)
                    ->orWhereHas('user', function ($q) use ($email) {
                        $q->where('email', $email);
                    });
            })
            ->firstOrFail();

        $trackingInfo = $this->dhlTracking->track($order->tracking_number);

        return view('tracking.result', compact('order', 'trackingInfo'));
    }

    /**
     * Espace client - Liste des commandes avec suivi
     */
    public function index()
    {
        $orders = Auth::user()->orders()
            ->whereNotNull('tracking_number')
            ->latest()
            ->get();

        return view('tracking.account-index', compact('orders'));
    }

    /**
     * Espace client - Suivi d'une commande spécifique
     */
    public function trackOrder(Order $order)
    {
        // Vérifier que la commande appartient à l'utilisateur connecté
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à suivre cette commande.');
        }

        if (!$order->tracking_number) {
            return redirect()->route('account.tracking')
                ->withErrors(['error' => 'Aucun numéro de suivi pour cette commande.']);
        }

        $trackingInfo = $this->dhlTracking->track($order->tracking_number);

        return view('tracking.account-result', compact('order', 'trackingInfo'));
    }

    /**
     * API - Suivi AJAX (pour appels asynchrones)
     */
    public function apiTrack(Request $request)
    {
        $request->validate([
            'tracking_number' => 'required|string'
        ]);

        $trackingInfo = $this->dhlTracking->track($request->tracking_number);

        if (!$trackingInfo) {
            return response()->json([
                'success' => false,
                'message' => 'Numéro de suivi introuvable'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $trackingInfo
        ]);
    }
}
