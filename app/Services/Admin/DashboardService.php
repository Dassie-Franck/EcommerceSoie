<?php

namespace App\Services\Admin;

use App\Contracts\Admin\DashboardServiceInterface;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class DashboardService implements DashboardServiceInterface
{
    public function getStats(): array
    {
        return [
            'revenue_total'      => Order::where('status', 'completed')->sum('total'),
            'revenue_month'      => Order::where('status', 'completed')
                                        ->whereMonth('created_at', now()->month)
                                        ->whereYear('created_at', now()->year)
                                        ->sum('total'),
            'orders_total'       => Order::count(),
            'orders_pending'     => Order::where('status', 'pending')->count(),
            'orders_processing'  => Order::where('status', 'processing')->count(),
            'new_clients_month'  => User::where('role', 'client')
                                        ->whereMonth('created_at', now()->month)
                                        ->whereYear('created_at', now()->year)
                                        ->count(),
            'clients_total'      => User::where('role', 'client')->count(),
            'products_active'    => Product::where('is_active', true)->count(),
            'products_inactive'  => Product::where('is_active', false)->count(),
        ];
    }

    public function getRecentOrders(int $limit = 10): Collection
    {
        return Order::with(['user', 'items'])
            ->latest()
            ->take($limit)
            ->get();
    }

    public function getTopProducts(int $limit = 5): \Illuminate\Support\Collection
    {
        return DB::table('order_items')
            ->select(
                'product_name',
                DB::raw('SUM(quantity) as total_sold'),
                DB::raw('SUM(unit_price * quantity) as revenue')
            )
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->groupBy('product_name')
            ->orderByDesc('total_sold')
            ->take($limit)
            ->get();
    }

    public function getSalesChart(int $months = 12): \Illuminate\Support\Collection
    {
        return Order::where('status', 'completed')
            ->where('created_at', '>=', now()->subMonths($months))
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(fn($row) => [
                'label' => \Carbon\Carbon::create($row->year, $row->month)->translatedFormat('M Y'),
                'total' => $row->total,
                'count' => $row->count,
            ]);
    }

    public function getOrdersByStatus(): \Illuminate\Support\Collection
    {
        return Order::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');
    }
}