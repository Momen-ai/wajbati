<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Favourite;
use App\Models\Meal;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FavouriteController extends Controller
{
    /**
     * Display customer's favourite meals and chefs.
     */
    public function index()
    {
        $userId = Auth::id();

        $favouriteMeals = Favourite::where('user_id', $userId)
            ->whereNotNull('meal_id')
            ->with(['meal.chef', 'meal.category', 'meal.image'])
            ->latest()
            ->get()
            ->pluck('meal')
            ->filter();

        $favouriteChefs = Favourite::where('user_id', $userId)
            ->whereNull('meal_id')
            ->with(['chef.image', 'chef.meals'])
            ->latest()
            ->get()
            ->pluck('chef')
            ->filter();

        return view('front.favourites.index', compact('favouriteMeals', 'favouriteChefs'));
    }

    /**
     * Toggle favourite status for a meal.
     */
    public function toggleMeal(Meal $meal)
    {
        $userId = Auth::id();

        $fav = Favourite::where('user_id', $userId)
            ->where('meal_id', $meal->id)
            ->first();

        if ($fav) {
            $fav->delete();
            $status = 'removed';
            $message = __('Meal removed from favourites.');
        } else {
            Favourite::create([
                'user_id' => $userId,
                'chef_id' => $meal->chef_id,
                'meal_id' => $meal->id,
            ]);
            $status = 'added';
            $message = __('Meal added to favourites.');
        }

        if (request()->wantsJson()) {
            return response()->json(['status' => $status, 'message' => $message]);
        }

        return back()->with('success', $message);
    }

    /**
     * Toggle favourite status for a chef.
     */
    public function toggleChef(User $chef)
    {
        if ($chef->role !== 'chef') {
            abort(404);
        }

        $userId = Auth::id();

        $fav = Favourite::where('user_id', $userId)
            ->where('chef_id', $chef->id)
            ->whereNull('meal_id')
            ->first();

        if ($fav) {
            $fav->delete();
            $status = 'removed';
            $message = __('Chef removed from favourites.');
        } else {
            Favourite::create([
                'user_id' => $userId,
                'chef_id' => $chef->id,
                'meal_id' => null,
            ]);
            $status = 'added';
            $message = __('Chef added to favourites.');
        }

        if (request()->wantsJson()) {
            return response()->json(['status' => $status, 'message' => $message]);
        }

        return back()->with('success', $message);
    }
}
