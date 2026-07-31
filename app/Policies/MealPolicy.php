<?php

namespace App\Policies;

use App\Models\Meal;
use App\Models\User;

class MealPolicy
{
    /**
     * Determine whether the user can view the meal.
     * Admins can view any meal; chefs can only view their own.
     */
    public function view(User $user, Meal $meal): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->id === $meal->chef_id;
    }

    /**
     * Determine whether the user can update the meal.
     * Only the owning chef may update their meal.
     */
    public function update(User $user, Meal $meal): bool
    {
        return $user->id === $meal->chef_id;
    }

    /**
     * Determine whether the user can delete the meal.
     * Only the owning chef may delete their meal.
     */
    public function delete(User $user, Meal $meal): bool
    {
        return $user->id === $meal->chef_id;
    }
}
