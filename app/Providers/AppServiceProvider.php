<?php

namespace App\Providers;

use App\Models\Meal;
use App\Models\Order;
use App\Policies\MealPolicy;
use App\Policies\OrderPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
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
        if ($this->app->isProduction()) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        Paginator::useBootstrapFive();
        \Illuminate\Database\Eloquent\Model::preventLazyLoading(! app()->isProduction());

        Gate::policy(Meal::class, MealPolicy::class);
        Gate::policy(Order::class, OrderPolicy::class);
        Gate::policy(\App\Models\Rating::class, \App\Policies\RatingPolicy::class);

        // Named Rate Limiters (F-16)
        RateLimiter::for('register', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        RateLimiter::for('contact', function (Request $request) {
            return Limit::perHour(5)->by($request->ip());
        });

        RateLimiter::for('newsletter', function (Request $request) {
            return Limit::perHour(5)->by($request->ip());
        });

        RateLimiter::for('cart', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('rate', function (Request $request) {
            return Limit::perHour(10)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('checkout', function (Request $request) {
            return Limit::perHour(10)->by($request->user()?->id ?: $request->ip());
        });
    }
}
