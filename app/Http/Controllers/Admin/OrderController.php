<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\DHLTrackingService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $dhlTracking;

    public function __construct(DHLTrackingService $dhlTracking)
    {
        $this->dhlTracking = $dhlTracking;
    }

    // Liste des commandes
    public function index()
    {
        $orders = Order::latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    // Détail d'une commande
    public function show(Order $order)
    {
        $trackingInfo = null;

        if ($order->tracking_number) {
            $trackingInfo = $this->dhlTracking->track($order->tracking_number);
        }

        return view('admin.orders.show', compact('order', 'trackingInfo'));
    }

    // Mettre à jour le statut
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Statut mis à jour avec succès.');
    }

    // Mettre à jour le suivi (numéro DHL)
    public function updateTracking(Request $request, Order $order)
    {
        $request->validate([
            'tracking_number' => 'nullable|string|max:50',
            'shipping_carrier' => 'nullable|string|max:50'
        ]);

        $order->update([
            'tracking_number' => $request->tracking_number,
            'shipping_carrier' => $request->shipping_carrier,
            'status' => $request->tracking_number ? 'shipped' : $order->status
        ]);

        return back()->with('success', 'Numéro de suivi mis à jour avec succès.');
    }
}
