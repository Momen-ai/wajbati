<?php

use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\ContactController;
use App\Http\Controllers\Dashboard\MealController;
use App\Http\Controllers\Dashboard\OrderController;
use App\Http\Controllers\Dashboard\RatingController;
use App\Http\Controllers\Dashboard\UsersController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::resource('category', CategoryController::class)->except(['show']);
    Route::resource('users', UsersController::class);
    Route::resource('meals', MealController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('ratings', RatingController::class)->only(['index', 'destroy']);
    Route::resource('contacts', ContactController::class)->only(['index', 'show', 'destroy']);
    Route::patch('contacts/{contact}/toggle-read', [ContactController::class, 'toggleRead'])->name('contacts.toggle-read');
});
