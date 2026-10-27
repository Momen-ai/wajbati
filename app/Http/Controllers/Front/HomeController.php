<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Meal;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $since = \Carbon\Carbon::now()->subDays(30);

        $popularMeals = Meal::where('is_available', true)
            ->with(['image', 'chef', 'category'])
            ->withCount([
                'orderItems as recent_completed_orders_count' => function ($query) use ($since) {
                    $query->whereHas('order', function ($q) use ($since) {
                        $q->whereIn('status', ['delivered', 'completed'])
                            ->where('created_at', '>=', $since);
                    });
                },
            ])
            ->withAvg('ratings', 'star')
            ->withCount('ratings')
            ->orderByDesc('recent_completed_orders_count')
            ->orderByDesc('ratings_count')
            ->orderByDesc('ratings_avg_star')
            ->latest()
            ->take(6)
            ->get();

        $chefs = User::where('role', 'chef')
            ->with('image')
            ->withCount('meals')
            ->latest()
            ->take(4)
            ->get();

        $categories = Category::withCount('meals')->get();

        return view('front.home', compact('popularMeals', 'categories', 'chefs'));
    }
}
