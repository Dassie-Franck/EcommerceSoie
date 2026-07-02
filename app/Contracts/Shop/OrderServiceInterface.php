<?php

namespace App\Contracts\Shop;

use App\Models\Order;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface OrderServiceInterface
{
    public function getForUser(User $user, int $perPage = 10): LengthAwarePaginator;
    public function findForUser(int $orderId, int $userId): Order;
    public function cancel(Order $order, int $userId): void;
}