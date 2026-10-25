<?php

namespace App\Http\Controllers\Chef;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use App\Models\Order;
use App\Services\OrderStats;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $chefId = Auth::id();
        $totalMeals = Meal::where('chef_id', $chefId)->count();
        $totalOrders = Order::where('chef_id', $chefId)->count();
        $pendingOrders = Order::where('chef_id', $chefId)->where('status', 'pending')->count();
        $completedOrders = Order::where('chef_id', $chefId)->where('status', 'completed')->count();

        $recentOrders = Order::where('chef_id', $chefId)->with('user')->latest()->take(5)->get();

        // Growth Calculation
        $now = Carbon::now();
        $startOfCurrentMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        $currMonthRevenue = Order::where('chef_id', $chefId)
            ->where('payment_status', 'paid')
            ->whereIn('status', ['delivered', 'completed'])
            ->where('created_at', '>=', $startOfCurrentMonth)
            ->sum('total');

        $lastMonthRevenue = Order::where('chef_id', $chefId)
            ->where('payment_status', 'paid')
            ->whereIn('status', ['delivered', 'completed'])
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->sum('total');

        $revGrowth = $lastMonthRevenue > 0
            ? (($currMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100
            : ($currMonthRevenue > 0 ? 100 : 0);

        $growth = [
            'revenue' => ($revGrowth >= 0 ? '+' : '').number_format($revGrowth, 1).'%',
        ];

        // Chart Data (Last 6 Months) in single query
        $chartData = OrderStats::monthlyRevenueSeries(6, $chefId);

        return view('front.chef.dashboard', compact(
            'totalMeals',
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'recentOrders',
            'growth',
            'chartData'
        ));
    }
}
