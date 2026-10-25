<?php

namespace App\Services;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class OrderStats
{
    /**
     * Compute total verified revenue: paid orders in delivered or completed status.
     */
    public static function totalRevenue(?int $chefId = null): float
    {
        $query = Order::where('payment_status', 'paid')
            ->whereIn('status', ['delivered', 'completed']);

        if ($chefId !== null) {
            $query->where('chef_id', $chefId);
        }

        return (float) $query->sum('total');
    }

    /**
     * Compute monthly revenue series for the last N months in a single portable query.
     */
    public static function monthlyRevenueSeries(int $months = 6, ?int $chefId = null): array
    {
        $driver = DB::connection()->getDriverName();
        $dateExpr = $driver === 'sqlite'
            ? "strftime('%Y-%m', created_at)"
            : "DATE_FORMAT(created_at, '%Y-%m')";

        $startDate = Carbon::now()->subMonths($months - 1)->startOfMonth();

        $rows = Order::where('payment_status', 'paid')
            ->whereIn('status', ['delivered', 'completed'])
            ->when($chefId !== null, fn ($q) => $q->where('chef_id', $chefId))
            ->where('created_at', '>=', $startDate)
            ->selectRaw("{$dateExpr} as ym, SUM(total) as total")
            ->groupBy('ym')
            ->pluck('total', 'ym');

        $labels = [];
        $values = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $ymKey = $month->format('Y-m');
            $labels[] = $month->format('M');
            $values[] = (float) ($rows[$ymKey] ?? 0.0);
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    /**
     * Invalidate dashboard stats caches.
     */
    public static function clearCaches(): void
    {
        Cache::forget('admin_dashboard_stats');
        Cache::forget('chef_dashboard_stats');
    }
}
