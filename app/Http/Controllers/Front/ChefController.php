<?php

namespace App\Http\Controllers\Front;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChefController extends Controller
{
    public function index()
    {
        $chefs = User::where('role', 'chef')
            ->with(['image', 'meals'])
            ->latest()
            ->paginate(12);

        return view('front.chefs.index', compact('chefs'));
    }

    public function show($id)
    {
        $chef = User::where('role', 'chef')
            ->with(['image', 'meals.image', 'meals.category'])
            ->findOrFail($id);

        return view('front.chefs.show', compact('chef'));
    }
}
