<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\ChefController;
use App\Http\Controllers\Front\ContactController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\MealController;
use App\Http\Controllers\Front\OrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['ar', 'en'])) {
        session(['locale' => $locale]);
    }

    return back();
})->name('lang.switch');

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/meals', [MealController::class, 'index'])->name('meals.index');
Route::get('/meals/{id}', [MealController::class, 'show'])->name('meals.show');
Route::post('/meals/{id}/rate', [MealController::class, 'rate'])->name('meals.rate')->middleware(['auth', 'throttle:rate']);

Route::get('/chefs', [ChefController::class, 'index'])->name('chefs.index');
Route::get('/chefs/{id}', [ChefController::class, 'show'])->name('chefs.show');

Route::get('/contact', [ContactController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactController::class, 'submitContact'])->name('contact.submit')->middleware('throttle:contact');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add')->middleware('throttle:cart');
Route::patch('/cart/update', [CartController::class, 'update'])->name('cart.update')->middleware('throttle:cart');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove')->middleware('throttle:cart');

Route::middleware('auth')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store')->middleware('throttle:checkout');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{id}/reorder', [OrderController::class, 'reorder'])->name('orders.reorder');

    // Favourites (Phase 5)
    Route::get('/favourites', [\App\Http\Controllers\Front\FavouriteController::class, 'index'])->name('favourites.index');
    Route::post('/favourites/meals/{meal}/toggle', [\App\Http\Controllers\Front\FavouriteController::class, 'toggleMeal'])->name('favourites.meal.toggle');
    Route::post('/favourites/chefs/{chef}/toggle', [\App\Http\Controllers\Front\FavouriteController::class, 'toggleChef'])->name('favourites.chef.toggle');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'role:admin'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Address management
    Route::post('/profile/address', [ProfileController::class, 'addAddress'])->name('profile.address.store');
    Route::delete('/profile/address/{address}', [ProfileController::class, 'deleteAddress'])->name('profile.address.destroy');
});
Route::middleware(['auth', 'role:chef'])->prefix('chef')->name('chef.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Chef\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders/export', [App\Http\Controllers\Chef\DashboardController::class, 'exportOrdersCsv'])->name('orders.export');
    Route::resource('meals', App\Http\Controllers\Chef\MealController::class)->except(['show']);
    Route::get('/orders', [App\Http\Controllers\Chef\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [App\Http\Controllers\Chef\OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [App\Http\Controllers\Chef\OrderController::class, 'updateStatus'])->name('orders.update-status');
});

Route::get('/privacy-policy', function () {
    return view('front.pages.privacy');
})->name('privacy');

Route::get('/terms-of-service', function () {
    return view('front.pages.terms');
})->name('terms');

Route::post('/newsletter', [ContactController::class, 'subscribe'])->name('newsletter.subscribe')->middleware('throttle:newsletter');

Route::middleware('auth')->group(function () {
    Route::post('/notifications/mark-as-read', function () {
        auth()->user()->unreadNotifications->markAsRead();

        return back();
    })->name('notifications.mark-as-read');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
