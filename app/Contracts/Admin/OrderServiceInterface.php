<?php

namespace App\Contracts\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrderServiceInterface
{
    public function paginate(Request $request, int $perPage = 15): LengthAwarePaginator;
    public function updateStatus(Order $order, string $status): Order;
}