<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use App\Models\Order;
use App\Models\User;
use App\Services\OrderStats;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = Cache::remember('admin_dashboard_stats', 300, function () {
            $now = Carbon::now();
            $startOfCurrentMonth = $now->copy()->startOfMonth();
            $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
            $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

            $totalRevenue = OrderStats::totalRevenue();

            // Revenue Growth based on verified paid orders
            $currMonthRevenue = Order::where('payment_status', 'paid')
                ->whereIn('status', ['delivered', 'completed'])
                ->where('created_at', '>=', $startOfCurrentMonth)
                ->sum('total');

            $lastMonthRevenue = Order::where('payment_status', 'paid')
                ->whereIn('status', ['delivered', 'completed'])
                ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
                ->sum('total');

            $revGrowth = $lastMonthRevenue > 0
                ? (($currMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100
                : ($currMonthRevenue > 0 ? 100 : 0);

            // Orders Growth
            $currMonthOrders = Order::where('created_at', '>=', $startOfCurrentMonth)->count();
            $lastMonthOrders = Order::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();
            $ordGrowth = $lastMonthOrders > 0
                ? (($currMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100
                : ($currMonthOrders > 0 ? 100 : 0);

            // Users Growth
            $currMonthUsers = User::where('created_at', '>=', $startOfCurrentMonth)->count();
            $lastMonthUsers = User::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();
            $usrGrowth = $lastMonthUsers > 0
                ? (($currMonthUsers - $lastMonthUsers) / $lastMonthUsers) * 100
                : ($currMonthUsers > 0 ? 100 : 0);

            return [
                'usersCount' => User::count(),
                'chefsCount' => User::where('role', 'chef')->count(),
                'mealsCount' => Meal::count(),
                'ordersCount' => Order::count(),
                'totalRevenue' => $totalRevenue,
                'pendingOrdersCount' => Order::where('status', 'pending')->count(),
                'growth' => [
                    'revenue' => ($revGrowth >= 0 ? '+' : '').number_format($revGrowth, 1).'%',
                    'orders' => ($ordGrowth >= 0 ? '+' : '').number_format($ordGrowth, 1).'%',
                    'users' => ($usrGrowth >= 0 ? '+' : '').number_format($usrGrowth, 1).'%',
                ],
                'chartData' => OrderStats::monthlyRevenueSeries(6),
            ];
        });

        $latestUsers = User::latest()->take(6)->get();
        $latestOrders = Order::with(['user', 'chef'])->latest()->take(6)->get();

        return view('admin.dashboard', array_merge($stats, [
            'latestUsers' => $latestUsers,
            'latestOrders' => $latestOrders,
        ]));
    }
}
