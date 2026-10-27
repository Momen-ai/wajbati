<?php

namespace App\Policies;

use App\Models\Meal;
use App\Models\Order;
use App\Models\User;

class RatingPolicy
{
    /**
     * Determine whether the user can rate the meal.
     */
    public function create(User $user, Meal $meal): bool
    {
        // Chefs cannot rate their own meals
        if ($user->id === $meal->chef_id) {
            return false;
        }

        // Must have completed or delivered order containing the meal
        return Order::where('user_id', $user->id)
            ->whereIn('status', ['delivered', 'completed'])
            ->whereHas('items', function ($query) use ($meal) {
                $query->where('meal_id', $meal->id);
            })
            ->exists();
    }
}
