<?php

namespace App\Services\Admin;

use App\Contracts\Admin\OrderServiceInterface;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderService implements OrderServiceInterface
{
    public function paginate(Request $request, int $perPage = 15): LengthAwarePaginator
    {
        $query = Order::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return $query->paginate($perPage);
    }

    public function updateStatus(Order $order, string $status): Order
    {
        $data = ['status' => $status];

        // Timestamps automatiques selon statut
        $data = array_merge($data, match($status) {
            'shipped'   => ['shipped_at'   => now()],
            'completed' => ['delivered_at' => now()],
            'cancelled' => ['cancelled_at' => now()],
            default     => [],
        });

        $order->update($data);

        return $order->fresh();
    }
}