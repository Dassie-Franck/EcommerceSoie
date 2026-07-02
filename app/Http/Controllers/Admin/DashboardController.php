<?php

// ═══════════════════════════════════════════════════════════════
// App\Http\Controllers\Admin\DashboardController.php
// ═══════════════════════════════════════════════════════════════

namespace App\Http\Controllers\Admin;

use App\Contracts\Admin\DashboardServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardServiceInterface $dashboardService
    ) {
        $this->middleware(['auth', 'admin']);
    }

    public function index(): View
    {
        $rawStats = $this->dashboardService->getStats();

        // La vue attend $stats['total_orders'], 'total_revenue',
        // 'total_products', 'total_customers', 'recent_orders'
        // On mappe les clés du service vers ce que la vue attend.
        $stats = [
            'total_orders'    => $rawStats['orders_total'],
            'total_revenue'   => $rawStats['revenue_total'],
            'total_products'  => $rawStats['products_active'],
            'total_customers' => $rawStats['clients_total'],
            'recent_orders'   => $this->dashboardService->getRecentOrders(10),

            // Clés supplémentaires disponibles pour le dashboard étendu
            'revenue_month'     => $rawStats['revenue_month'],
            'orders_pending'    => $rawStats['orders_pending'],
            'orders_processing' => $rawStats['orders_processing'],
            'new_clients_month' => $rawStats['new_clients_month'],
            'products_inactive' => $rawStats['products_inactive'],
        ];

        return view('admin.dashboard', [
            'stats'            => $stats,
            'top_products'     => $this->dashboardService->getTopProducts(5),
            'sales_chart'      => $this->dashboardService->getSalesChart(12),
            'orders_by_status' => $this->dashboardService->getOrdersByStatus(),
        ]);
    }
}
