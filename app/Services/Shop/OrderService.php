<?php

namespace App\Services\Shop;

use App\Contracts\Shop\OrderServiceInterface;
use App\Models\Order;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OrderService implements OrderServiceInterface
{
    public function getForUser(User $user, int $perPage = 10): LengthAwarePaginator
    {
        return $user->orders()
            ->with('items')
            ->latest()
            ->paginate($perPage);
    }

    public function findForUser(int $orderId, int $userId): Order
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', $userId) //  ownership
            ->firstOrFail();

        return $order->load([
            'items.productVariant.product.images',
            'address',
            'shippingZone',
        ]);
    }

    public function cancel(Order $order, int $userId): void
    {
        //  Ownership check
        if ($order->user_id !== $userId) {
            abort(403);
        }

        //  Seules les commandes pending peuvent être annulées
        if ($order->status !== 'pending') {
            throw new \DomainException('order_not_cancellable');
        }

        $order->update([
            'status'       => 'cancelled',
            'cancelled_at' => now(),
        ]);
    }
}