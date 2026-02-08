<?php

namespace App\Http\Controllers\Front;

use App\Models\Meal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;


class CartController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        $total = 0;

        foreach ($cart as $id => $details) {
            $total += $details['price'] * $details['quantity'];
        }

        return view('front.cart.index', compact('cart', 'total'));
    }

    public function add(Request $request)
    {

        // Fetch the meal along with its related image in a single query
        $meal = Meal::with('image')->findOrFail($request->meal_id);

        
        $cart = Session::get('cart', []);
        $id = $meal->id;
        $quantity = $request->input('quantity', 1);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $cart[$id] = [
                "name" => $meal->name,
                "quantity" => $quantity,
                "price" => $meal->price,
                "image" => $meal->image ? $meal->image->image_path : null,
                "chef" => $meal->chef->name ?? 'Chef'
            ];
        }

        Session::put('cart', $cart);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'count' => count($cart), 'message' => 'Added to cart']);
        }

        return redirect()->back()->with('success', 'Meal added to cart successfully!');
    }

    public function update(Request $request)
    {
        if ($request->id && $request->quantity) {
            $cart = Session::get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            Session::put('cart', $cart);
            return response()->json(['success' => true]);
        }
    }

    public function remove(Request $request)
    {
        if ($request->id) {
            $cart = Session::get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                Session::put('cart', $cart);
            }
            return response()->json(['success' => true]);
        }
    }
}
