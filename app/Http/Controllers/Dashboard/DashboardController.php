<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Meal;
use App\Models\Order;
use App\Models\Contact;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $usersCount = User::count();
        $chefsCount = User::where('role', 'chef')->count();
        $mealsCount = Meal::count();
        $ordersCount = Order::count();
        
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total');
        $pendingOrdersCount = Order::where('status', 'pending')->count();
        
        $latestUsers = User::latest()->take(6)->get();
        $latestOrders = Order::with(['user', 'chef'])->latest()->take(6)->get();

        // Growth Calculation (Current Month vs Last Month)
        $now = \Carbon\Carbon::now();
        $startOfCurrentMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        // Revenue Growth
        $currMonthRevenue = Order::where('payment_status', 'paid')->where('created_at', '>=', $startOfCurrentMonth)->sum('total');
        $lastMonthRevenue = Order::where('payment_status', 'paid')->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->sum('total');
        $revGrowth = $lastMonthRevenue > 0 ? (($currMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : ($currMonthRevenue > 0 ? 100 : 0);

        // Orders Growth
        $currMonthOrders = Order::where('created_at', '>=', $startOfCurrentMonth)->count();
        $lastMonthOrders = Order::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();
        $ordGrowth = $lastMonthOrders > 0 ? (($currMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100 : ($currMonthOrders > 0 ? 100 : 0);

        // Users Growth
        $currMonthUsers = User::where('created_at', '>=', $startOfCurrentMonth)->count();
        $lastMonthUsers = User::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();
        $usrGrowth = $lastMonthUsers > 0 ? (($currMonthUsers - $lastMonthUsers) / $lastMonthUsers) * 100 : ($currMonthUsers > 0 ? 100 : 0);

        $growth = [
            'revenue' => ($revGrowth >= 0 ? '+' : '') . number_format($revGrowth, 1) . '%',
            'orders' => ($ordGrowth >= 0 ? '+' : '') . number_format($ordGrowth, 1) . '%',
            'users' => ($usrGrowth >= 0 ? '+' : '') . number_format($usrGrowth, 1) . '%'
        ];

        // Chart Data (Last 6 Months)
        $chartData = [
            'labels' => [],
            'values' => []
        ];
        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $chartData['labels'][] = $month->format('M');
            $chartData['values'][] = Order::where('payment_status', 'paid')
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->sum('total');
        }

        return view('admin.dashboard', compact(
            'usersCount',
            'chefsCount',
            'mealsCount',
            'ordersCount',
            'totalRevenue',
            'pendingOrdersCount',
            'latestUsers',
            'latestOrders',
            'growth',
            'chartData'
        ));
    }
}
