<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use App\Models\User;

class ChefController extends Controller
{
    public function index()
    {
        $query = User::where('role', 'chef')
            ->with(['image', 'meals.image', 'meals.category'])
            ->withCount('meals');

        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('kitchen_name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $chefs = $query->orderByDesc('meals_count')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $totalKitchens = User::where('role', 'chef')->count();
        $totalDishes = Meal::where('is_available', true)->count();

        return view('front.chefs.index', compact('chefs', 'totalKitchens', 'totalDishes'));
    }

    public function show($id)
    {
        $chef = User::where('role', 'chef')
            ->with(['image', 'meals.image', 'meals.category'])
            ->findOrFail($id);

        return view('front.chefs.show', compact('chef'));
    }
}
