<?php

namespace App\Http\Controllers\Front;

use App\Models\Meal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

class MealController extends Controller
{
    public function home()
    {
        $meals = Meal::with(['image', 'category', 'chef'])
            ->withAvg('ratings', 'star')
            ->withCount('ratings')
            ->latest()
            ->take(6)
            ->get();

        return view('front.home', compact('meals'));
    }

    public function index(Request $request)
    {
        $query = Meal::with(['image', 'chef', 'category'])
            ->withAvg('ratings', 'star')
            ->withCount('ratings');

        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        if ($request->has('chef') && $request->chef != '') {
            $query->where('chef_id', $request->chef);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('min_price') && $request->min_price != '') {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price') && $request->max_price != '') {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->has('sort')) {
            if ($request->sort == 'price_low') $query->orderBy('price', 'asc');
            elseif ($request->sort == 'price_high') $query->orderBy('price', 'desc');
            elseif ($request->sort == 'top_rated') $query->orderBy('ratings_avg_star', 'desc');
            else $query->latest();
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
        $request->validate([
            'star' => 'required|integer|min:1|max:5',
            'body' => 'nullable|string|max:1000',
        ]);

        $meal = Meal::findOrFail($id);

        $meal->ratings()->create([
            'user_id' => Auth::id(),
            'chef_id' => $meal->chef_id,
            'star' => $request->star,
            'body' => $request->body,
        ]);

        return back()->with('success', 'Thank you for your rating!');
    }
}

