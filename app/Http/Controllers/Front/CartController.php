<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\RemoveFromCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService
    ) {}

    public function index(): View
    {
        $details = $this->cartService->detailed();

        return view('front.cart.index', [
            'byChef' => $details['by_chef'],
            'subtotal' => $details['subtotal'],
            'totalDeliveryFee' => $details['total_delivery_fee'],
            'total' => $details['grand_total'],
            'count' => $details['count'],
            'cart' => $this->cartService->get(),
        ]);
    }

    public function add(AddToCartRequest $request): JsonResponse|RedirectResponse
    {
        $cart = $this->cartService->add(
            (int) $request->validated('meal_id'),
            (int) $request->validated('quantity')
        );

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'count' => count($cart),
                'message' => __('Meal added to cart successfully.'),
            ]);
        }

        return redirect()->back()->with('success', __('Meal added to cart successfully.'));
    }

    public function update(UpdateCartRequest $request): JsonResponse|RedirectResponse
    {
        $cart = $this->cartService->update(
            (int) $request->validated('id'),
            (int) $request->validated('quantity')
        );

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('Cart updated successfully.'),
            ]);
        }

        return redirect()->back()->with('success', __('Cart updated successfully.'));
    }

    public function remove(RemoveFromCartRequest $request): JsonResponse|RedirectResponse
    {
        $this->cartService->remove((int) $request->validated('id'));

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('Meal removed from cart.'),
            ]);
        }

        return redirect()->back()->with('success', __('Meal removed from cart.'));
    }
}
