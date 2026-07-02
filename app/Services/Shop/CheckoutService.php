<?php

namespace App\Services\Shop;

use App\Contracts\Shop\CheckoutServiceInterface;
use App\Http\Requests\Shop\CheckoutRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ShippingZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutService implements CheckoutServiceInterface
{
    /**
     * Récupérer les données pour la page checkout
     */
    public function getCheckoutData(int $userId): array
    {
        $cart = Cart::where('user_id', $userId)
            ->with('items.productVariant.product')
            ->first();

        if (! $cart || $cart->items->isEmpty()) {
            throw new \DomainException('cart_empty');
        }

        $user = \App\Models\User::findOrFail($userId);

        return [
            'cart'          => $cart,
            'addresses'     => $user->addresses()->get(),
            'shippingZones' => ShippingZone::where('is_active', true)->get(),
        ];
    }

    /**
     * Traiter la commande
     */
    public function process(CheckoutRequest $request, int $userId): Order
    {
        $cart = Cart::where('user_id', $userId)
            ->with('items.productVariant.product')
            ->first();

        if (! $cart || $cart->items->isEmpty()) {
            throw new \DomainException('cart_empty');
        }

        // 🔐 Vérification stock avant transaction
        foreach ($cart->items as $item) {
            $variant = $item->productVariant;

            if (! $variant) {
                throw new \DomainException('variant_unavailable');
            }

            if ($variant->stock_quantity < $item->quantity) {
                throw new \DomainException(
                    "stock_insufficient:{$variant->product->name}:{$variant->stock_quantity}:{$item->quantity}"
                );
            }
        }

        $zone = ShippingZone::findOrFail($request->shipping_zone_id);

        $subtotal = $cart->items->sum(
            fn($item) => $item->quantity * $item->productVariant->finalPrice()
        );

        $shipping = ($zone->free_above && $subtotal >= $zone->free_above)
            ? 0
            : $zone->price;

        $total = $subtotal + $shipping;

        return DB::transaction(function () use ($cart, $request, $zone, $subtotal, $shipping, $total, $userId) {

            // Order number unique garanti
            do {
                $orderNumber = 'AS-' . strtoupper(Str::random(10));
            } while (Order::where('order_number', $orderNumber)->exists());

            $order = Order::create([
                'user_id'          => $userId,
                'address_id'       => $request->address_id,
                'shipping_zone_id' => $zone->id,
                'order_number'     => $orderNumber,
                'status'           => 'pending',
                'subtotal'         => $subtotal,
                'shipping_cost'    => $shipping,
                'discount'         => 0,
                'total'            => $total,
                'notes'            => $request->notes,
            ]);

            foreach ($cart->items as $item) {
                $variant = $item->productVariant;

                OrderItem::create([
                    'order_id'           => $order->id,
                    'product_variant_id' => $variant->id,
                    'product_name'       => $variant->product->name,
                    'variant_label'      => $variant->label(),
                    'unit_price'         => $variant->finalPrice(),
                    'quantity'           => $item->quantity,
                ]);

                $variant->decrement('stock_quantity', $item->quantity);
            }

            $cart->items()->delete();

            return $order;
        });
    }

    /**
     * Confirmer le paiement
     */
    public function confirmPayment(Order $order, Request $request): void
    {
        DB::transaction(function () use ($order, $request) {
            $order->update(['status' => 'processing']);

            Payment::create([
                'order_id'       => $order->id,
                'provider'       => 'paypal',
                'transaction_id' => $request->get('token', 'PAYPAL-' . Str::uuid()),
                'status'         => 'completed',
                'amount'         => $order->total,
                'currency'       => 'EUR',
                'paid_at'        => now(),
            ]);
        });
    }

    /**
     * Annuler une commande
     */
    public function cancelOrder(int $orderId, int $userId): void
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', $userId) // 🔐 ownership
            ->first();

        if (! $order) return;

        if ($order->status === 'pending') {
            $order->update([
                'status'       => 'cancelled',
                'cancelled_at' => now(),
            ]);
        }
    }
}