<?php

namespace App\Http\Controllers\Chef;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use App\Models\Order;
use Illuminate\Http\Request;
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

        return view('front.chef.dashboard', compact('totalMeals', 'totalOrders', 'pendingOrders', 'completedOrders', 'recentOrders'));
    }
}
