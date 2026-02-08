<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Cart;
use App\Models\User;
use App\Models\Meal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::with(['user', 'meal'])->latest()->paginate(10);
        return view('admin.cart.index', compact('carts'));
    }

    public function create()
    {
        $users = User::all();
        $meals = Meal::all();

        return view('admin.cart.create', compact('users', 'meals'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'meal_id' => 'required|exists:meals,id',
            'quantity' => 'required|integer|min:1',
        ]);

        Cart::create($validated);

        return redirect()
            ->route('dashboard.cart.index')
            ->with('success', 'Item added to cart successfully');
    }

    public function edit(Cart $cart)
    {
        $users = User::all();
        $meals = Meal::all();

        return view('admin.cart.edit', compact('cart', 'users', 'meals'));
    }

    public function update(Request $request, Cart $cart)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'meal_id' => 'required|exists:meals,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart->update($validated);

        return redirect()
            ->route('dashboard.cart.index')
            ->with('success', 'Cart updated successfully');
    }

    public function destroy(Cart $cart)
    {
        $cart->delete();

        return redirect()
            ->route('dashboard.cart.index')
            ->with('success', 'Cart item deleted successfully');
    }
}

