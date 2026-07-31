<?php

namespace App\Providers;

use App\Models\Meal;
use App\Models\Order;
use App\Policies\MealPolicy;
use App\Policies\OrderPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Meal::class, MealPolicy::class);
        Gate::policy(Order::class, OrderPolicy::class);
    }
}
