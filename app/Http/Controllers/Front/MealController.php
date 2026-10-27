<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MealController extends Controller
{
    public function index(\App\Http\Requests\MealIndexRequest $request)
    {
        $query = Meal::where('is_available', true)
            ->with(['image', 'chef', 'category'])
            ->withAvg('ratings', 'star')
            ->withCount('ratings');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('chef')) {
            $query->where('chef_id', $request->chef);
        }

        if ($request->filled('search')) {
            $escaped = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $request->search);
            $query->where('name', 'like', '%'.$escaped.'%');
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('sort')) {
            if ($request->sort == 'price_low') {
                $query->orderBy('price', 'asc');
            } elseif ($request->sort == 'price_high') {
                $query->orderBy('price', 'desc');
            } elseif ($request->sort == 'top_rated') {
                $query->orderByDesc('ratings_avg_star');
            } else {
                $query->latest();
            }
        } else {
            $query->latest();
        }

        $meals = $query->paginate(9);
        $categories = \App\Models\Category::all();

        return view('front.meals.index', compact('meals', 'categories'));
    }

    public function show($id)
    {
        $meal = Meal::with(['image', 'chef', 'category', 'ratings.user'])->findOrFail($id);

        $relatedMeals = Meal::where('category_id', $meal->category_id)
            ->where('id', '!=', $meal->id)
            ->with(['image', 'category', 'chef'])
            ->take(4)
            ->get();

        return view('front.meals.show', compact('meal', 'relatedMeals'));
    }

    public function rate(Request $request, $id)
    {
        $meal = Meal::findOrFail($id);

        \Illuminate\Support\Facades\Gate::authorize('create', [Rating::class, $meal]);

        $request->validate([
            'star' => 'required|integer|min:1|max:5',
            'body' => 'nullable|string|max:1000',
        ]);

        Rating::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'meal_id' => $meal->id,
            ],
            [
                'chef_id' => $meal->chef_id,
                'star' => $request->star,
                'body' => $request->body,
            ]
        );

        return back()->with('success', __('Thank you for your rating!'));
    }
}
