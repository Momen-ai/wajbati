<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\CartController;
use App\Http\Controllers\Dashboard\MealController;
use App\Http\Controllers\Dashboard\OrderController;
use App\Http\Controllers\Dashboard\UsersController;
use App\Http\Controllers\Dashboard\RatingController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\ContactController;
use App\Http\Controllers\Dashboard\FavouriteController;

Route::middleware(['auth', 'role:admin'])->prefix('dashboard')->name('dashboard.')->group(function () {

        Route::resource('category', CategoryController::class);
        Route::resource('users', UsersController::class);
        Route::resource('meals', MealController::class);
        Route::resource('orders', OrderController::class);
        Route::resource('cart', CartController::class);
        Route::resource('favourites', FavouriteController::class);
        Route::resource('ratings', RatingController::class);
        Route::resource('contacts', ContactController::class);



    });

