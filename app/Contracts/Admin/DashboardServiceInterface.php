<?php

namespace App\Contracts\Admin;

interface DashboardServiceInterface
{
    public function getStats(): array;
    public function getRecentOrders(int $limit = 10): \Illuminate\Database\Eloquent\Collection;
    public function getTopProducts(int $limit = 5): \Illuminate\Support\Collection;
    public function getSalesChart(int $months = 12): \Illuminate\Support\Collection;
    public function getOrdersByStatus(): \Illuminate\Support\Collection;
}