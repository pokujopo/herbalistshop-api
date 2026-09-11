<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Admin dashboard analytics.
     */
    public function analytics(Request $request)
    {
        $period = $request->get('period', 'monthly');

        $stats = [
            'orders' => Order::count(),

            'products' => Product::count(),

            'customers' => User::where('usertype', '!=', 'admin')->count(),

            'revenue' => (float) Order::where('payment_status', 'paid')
                ->sum('total'),

            'today_orders' => Order::whereDate('created_at', today())
                ->count(),

            'today_revenue' => (float) Order::whereDate('created_at', today())
                ->where('payment_status', 'paid')
                ->sum('total'),
        ];

        $recentOrders = Order::with([
            'address',
            'user',
        ])
            ->latest()
            ->take(5)
            ->get();

        $lowStockProducts = Product::with('category')
            ->where('stock', '<=', 5)
            ->orderBy('stock')
            ->take(8)
            ->get();

        $salesTrend = $this->getSalesTrend($period);

        $categorySales = $this->getCategorySales($period);

        return response()->json([
            'success' => true,

            'stats' => $stats,

            'recent_orders' => $recentOrders,

            'low_stock_products' => $lowStockProducts,

            'sales_trend' => $salesTrend,

            'category_sales' => $categorySales,
        ]);
    }

    /**
     * Determine sales trend based on requested period.
     */
    private function getSalesTrend(string $period): array
    {
        return match ($period) {
            'daily' => $this->getDailySalesTrend(),

            'weekly' => $this->getWeeklySalesTrend(),

            'yearly' => $this->getYearlySalesTrend(),

            default => $this->getMonthlySalesTrend(),
        };
    }

    /**
     * Last 7 days sales.
     */
    private function getDailySalesTrend(): array
    {
        $start = now()
            ->subDays(6)
            ->startOfDay();

        $end = now()->endOfDay();

        /*
         * We intentionally do not use TO_CHAR(), DATE_FORMAT()
         * or EXTRACT() here.
         *
         * The query gets the paid orders and we group them
         * using Carbon in PHP. This works with SQLite,
         * MySQL and PostgreSQL.
         */
        $orders = Order::query()
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$start, $end])
            ->get([
                'created_at',
                'total',
            ]);

        $data = [];

        $period = CarbonPeriod::create(
            $start->copy()->startOfDay(),
            '1 day',
            $end->copy()->startOfDay()
        );

        foreach ($period as $date) {
            $dateKey = $date->format('Y-m-d');

            $total = $orders
                ->filter(function ($order) use ($dateKey) {
                    return $order->created_at
                        ->format('Y-m-d') === $dateKey;
                })
                ->sum('total');

            $data[] = [
                'label' => $date->format('D'),

                'total' => (float) $total,
            ];
        }

        return $data;
    }

    /**
     * Last 6 weeks sales including current week.
     */
    private function getWeeklySalesTrend(): array
    {
        $currentWeekStart = now()
            ->startOfWeek()
            ->startOfDay();

        $start = $currentWeekStart
            ->copy()
            ->subWeeks(5);

        $end = now()
            ->endOfWeek()
            ->endOfDay();

        $orders = Order::query()
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$start, $end])
            ->get([
                'created_at',
                'total',
            ]);

        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $weekStart = $currentWeekStart
                ->copy()
                ->subWeeks($i)
                ->startOfWeek();

            $weekEnd = $weekStart
                ->copy()
                ->endOfWeek();

            $total = $orders
                ->filter(function ($order) use ($weekStart, $weekEnd) {
                    return $order->created_at->between(
                        $weekStart,
                        $weekEnd
                    );
                })
                ->sum('total');

            $data[] = [
                'label' => 'W' . $weekStart->format('W'),

                'total' => (float) $total,
            ];
        }

        return $data;
    }

    /**
     * Monthly sales for the current year.
     */
    private function getMonthlySalesTrend(): array
    {
        $year = now()->year;

        $start = Carbon::create(
            $year,
            1,
            1
        )->startOfYear();

        $end = Carbon::create(
            $year,
            12,
            31
        )->endOfYear();

        $orders = Order::query()
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$start, $end])
            ->get([
                'created_at',
                'total',
            ]);

        /*
         * Group in PHP instead of using:
         *
         * EXTRACT(MONTH FROM created_at)
         *
         * This makes the endpoint compatible with SQLite,
         * MySQL and PostgreSQL.
         */
        $monthlyTotals = [];

        foreach ($orders as $order) {
            $month = $order->created_at->month;

            if (!isset($monthlyTotals[$month])) {
                $monthlyTotals[$month] = 0;
            }

            $monthlyTotals[$month] += (float) $order->total;
        }

        $data = [];

        for ($month = 1; $month <= 12; $month++) {
            $data[] = [
                'label' => Carbon::create(
                    $year,
                    $month,
                    1
                )->format('M'),

                'total' => (float) (
                    $monthlyTotals[$month] ?? 0
                ),
            ];
        }

        return $data;
    }

    /**
     * Sales for the last 5 years including current year.
     */
    private function getYearlySalesTrend(): array
    {
        $currentYear = now()->year;

        $startYear = $currentYear - 4;

        $start = Carbon::create(
            $startYear,
            1,
            1
        )->startOfYear();

        $end = Carbon::create(
            $currentYear,
            12,
            31
        )->endOfYear();

        $orders = Order::query()
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$start, $end])
            ->get([
                'created_at',
                'total',
            ]);

        /*
         * Avoid:
         *
         * EXTRACT(YEAR FROM created_at)
         *
         * because SQLite does not support it.
         */
        $yearlyTotals = [];

        foreach ($orders as $order) {
            $year = $order->created_at->year;

            if (!isset($yearlyTotals[$year])) {
                $yearlyTotals[$year] = 0;
            }

            $yearlyTotals[$year] += (float) $order->total;
        }

        $data = [];

        for ($year = $startYear; $year <= $currentYear; $year++) {
            $data[] = [
                'label' => (string) $year,

                'total' => (float) (
                    $yearlyTotals[$year] ?? 0
                ),
            ];
        }

        return $data;
    }

    /**
     * Sales grouped by product category.
     */
    private function getCategorySales(string $period): array
    {
        [$start, $end] = $this->getPeriodRange($period);

        $rows = OrderItem::query()
            ->join(
                'orders',
                'order_items.order_id',
                '=',
                'orders.id'
            )
            ->join(
                'products',
                'order_items.product_id',
                '=',
                'products.id'
            )
            ->join(
                'categories',
                'products.category_id',
                '=',
                'categories.id'
            )
            ->where(
                'orders.payment_status',
                'paid'
            )
            ->whereBetween(
                'orders.created_at',
                [$start, $end]
            )
            ->select([
                'categories.id',
                'categories.name',
            ])
            ->selectRaw(
                'SUM(order_items.quantity) as total'
            )
            ->groupBy(
                'categories.id',
                'categories.name'
            )
            ->orderByDesc('total')
            ->get();

        $grandTotal = (int) $rows->sum('total');

        return $rows
            ->map(function ($row) use ($grandTotal) {
                $total = (int) $row->total;

                $percentage = $grandTotal > 0
                    ? round(
                        ($total / $grandTotal) * 100
                    )
                    : 0;

                return [
                    'name' => $row->name,

                    'total' => $total,

                    'percentage' => $percentage,
                ];
            })
            ->values()
            ->toArray();
    }

    /**
     * Get date range for category sales.
     */
    private function getPeriodRange(string $period): array
    {
        return match ($period) {
            'daily' => [
                now()
                    ->subDays(6)
                    ->startOfDay(),

                now()->endOfDay(),
            ],

            'weekly' => [
                now()
                    ->startOfWeek()
                    ->subWeeks(5)
                    ->startOfDay(),

                now()->endOfWeek()->endOfDay(),
            ],

            'yearly' => [
                now()
                    ->subYears(4)
                    ->startOfYear(),

                now()->endOfYear(),
            ],

            default => [
                now()->startOfYear(),

                now()->endOfYear(),
            ],
        };
    }
}
