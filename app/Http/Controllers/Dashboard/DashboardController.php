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
        
        // Mocking some growth data for UI presentation
        $growth = [
            'revenue' => '+12.5%',
            'orders' => '+8.2%',
            'users' => '+15.3%'
        ];

        return view('admin.dashboard', compact(
            'usersCount',
            'chefsCount',
            'mealsCount',
            'ordersCount',
            'totalRevenue',
            'pendingOrdersCount',
            'latestUsers',
            'latestOrders',
            'growth'
        ));
    }
}
