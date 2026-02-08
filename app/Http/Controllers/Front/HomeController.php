<?php

namespace App\Http\Controllers\Front;

use App\Models\Meal;
use App\Models\User;
use App\Models\Category;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $popularMeals = Meal::with(['image', 'chef', 'category', 'ratings'])
            ->withCount('ratings')
            ->latest()
            ->take(6)
            ->get();

        $chefs = User::where('role', 'chef')->with('image')->latest()->take(4)->get();

        $categories = Category::withCount('meals')->get();


        return view('front.home', compact('popularMeals','categories', 'chefs'));
    }
}

