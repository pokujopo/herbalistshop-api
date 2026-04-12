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
    public function analytics(Request $request)
    {
        $period = $request->get('period', 'monthly');

        $stats = [
            'orders' => Order::count(),
            'products' => Product::count(),
            'customers' => User::where('usertype', '!=', 'admin')->count(),
            'revenue' => (float) Order::where('payment_status', 'paid')->sum('total'),
            'today_orders' => Order::whereDate('created_at', today())->count(),
            'today_revenue' => (float) Order::whereDate('created_at', today())
                ->where('payment_status', 'paid')
                ->sum('total'),
        ];

        $recentOrders = Order::with(['address', 'user'])
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

    private function getSalesTrend(string $period): array
    {
        return match ($period) {
            'daily' => $this->getDailySalesTrend(),
            'weekly' => $this->getWeeklySalesTrend(),
            'yearly' => $this->getYearlySalesTrend(),
            default => $this->getMonthlySalesTrend(),
        };
    }

    private function getDailySalesTrend(): array
    {
        $start = now()->subDays(6)->startOfDay();
        $end = now()->endOfDay();

        $rows = Order::selectRaw("TO_CHAR(created_at, 'YYYY-MM-DD') as date, SUM(total) as total")
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $period = CarbonPeriod::create($start, '1 day', $end);

        $data = [];
        foreach ($period as $date) {
            $key = $date->format('Y-m-d');
            $data[] = [
                'label' => $date->format('D'),
                'total' => (float) ($rows[$key]->total ?? 0),
            ];
        }

        return $data;
    }

    private function getWeeklySalesTrend(): array
    {
        $start = now()->startOfWeek()->subWeeks(5)->startOfDay();
        $end = now()->endOfWeek()->endOfDay();

        $orders = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$start, $end])
            ->get();

        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $weekStart = now()->startOfWeek()->subWeeks($i)->startOfDay();
            $weekEnd = now()->startOfWeek()->subWeeks($i)->endOfWeek()->endOfDay();

            $total = $orders
                ->filter(fn ($order) => $order->created_at >= $weekStart && $order->created_at <= $weekEnd)
                ->sum('total');

            $data[] = [
                'label' => 'W' . $weekStart->format('W'),
                'total' => (float) $total,
            ];
        }

        return $data;
    }

    private function getMonthlySalesTrend(): array
    {
        $year = now()->year;

        $rows = Order::selectRaw('EXTRACT(MONTH FROM created_at) as month_number, SUM(total) as total')
            ->where('payment_status', 'paid')
            ->whereYear('created_at', $year)
            ->groupBy('month_number')
            ->orderBy('month_number')
            ->get()
            ->keyBy(fn ($row) => (int) $row->month_number);

        $data = [];

        for ($month = 1; $month <= 12; $month++) {
            $data[] = [
                'label' => Carbon::create($year, $month, 1)->format('M'),
                'total' => (float) ($rows[$month]->total ?? 0),
            ];
        }

        return $data;
    }

    private function getYearlySalesTrend(): array
    {
        $startYear = now()->year - 4;
        $endYear = now()->year;

        $rows = Order::selectRaw('EXTRACT(YEAR FROM created_at) as year_number, SUM(total) as total')
            ->where('payment_status', 'paid')
            ->whereRaw('EXTRACT(YEAR FROM created_at) BETWEEN ? AND ?', [$startYear, $endYear])
            ->groupBy('year_number')
            ->orderBy('year_number')
            ->get()
            ->keyBy(fn ($row) => (int) $row->year_number);

        $data = [];

        for ($year = $startYear; $year <= $endYear; $year++) {
            $data[] = [
                'label' => (string) $year,
                'total' => (float) ($rows[$year]->total ?? 0),
            ];
        }

        return $data;
    }

    private function getCategorySales(string $period): array
    {
        [$start, $end] = $this->getPeriodRange($period);

        $rows = OrderItem::query()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.payment_status', 'paid')
            ->whereBetween('orders.created_at', [$start, $end])
            ->selectRaw('categories.name as name, SUM(order_items.quantity) as total')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total')
            ->get();

        $grandTotal = (int) $rows->sum('total');

        return $rows->map(function ($row) use ($grandTotal) {
            $percentage = $grandTotal > 0
                ? round(($row->total / $grandTotal) * 100)
                : 0;

            return [
                'name' => $row->name,
                'total' => (int) $row->total,
                'percentage' => $percentage,
            ];
        })->values()->toArray();
    }

    private function getPeriodRange(string $period): array
    {
        return match ($period) {
            'daily' => [now()->subDays(6)->startOfDay(), now()->endOfDay()],
            'weekly' => [now()->startOfWeek()->subWeeks(5)->startOfDay(), now()->endOfWeek()->endOfDay()],
            'yearly' => [now()->subYears(4)->startOfYear(), now()->endOfYear()],
            default => [now()->startOfYear(), now()->endOfYear()],
        };
    }
}
