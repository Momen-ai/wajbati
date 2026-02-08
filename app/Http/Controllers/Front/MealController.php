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
        $meals = Meal::with('image')->latest()->take(6)->get();

        return view('front.home', compact('meals'));
    }

    public function index(Request $request)
    {
        $query = Meal::with(['image', 'chef', 'category', 'ratings']);

        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('chef')) {
            $query->where('chef_id', $request->chef);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $meals = $query->latest()->paginate(9);

        return view('front.meals.index', compact('meals'));
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

