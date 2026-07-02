<?php

namespace App\Contracts\Shop;

use App\Models\Cart;
use App\Models\Order;
use App\Http\Requests\Shop\CheckoutRequest;
use Illuminate\Http\Request;

interface CheckoutServiceInterface
{
    public function getCheckoutData(int $userId): array;
    public function process(CheckoutRequest $request, int $userId): Order;
    public function confirmPayment(Order $order, Request $request): void;
    public function cancelOrder(int $orderId, int $userId): void;
}