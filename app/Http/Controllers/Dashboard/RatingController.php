<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RatingController extends Controller
{
    public function index()
    {
        $ratings = Rating::with(['user', 'chef'])
            ->latest()
            ->paginate(10);

        return view('admin.ratings.index', compact('ratings'));
    }

    public function create()
    {
        $users = User::all();
        $chefs = User::where('role', 'chef')->get();

        return view('admin.ratings.create', compact('users', 'chefs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'chef_id' => 'required|exists:users,id',
            'star'    => 'required|integer|min:1|max:5',
            'body'    => 'nullable|string',
        ]);

        Rating::create($validated);

        return redirect()
            ->route('dashboard.ratings.index')
            ->with('success', 'Rating added successfully');
    }


    public function edit(Rating $rating)
    {
        $users = User::all();
        $chefs = User::where('role', 'chef')->get();

        return view('admin.ratings.edit', compact('rating', 'users', 'chefs'));
    }

    public function update(Request $request, Rating $rating)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'chef_id' => 'required|exists:users,id',
            'star'    => 'required|integer|min:1|max:5',
            'body'    => 'nullable|string',
        ]);

        $rating->update($validated);

        return redirect()
            ->route('dashboard.ratings.index')
            ->with('success', 'Rating updated successfully');
    }

    public function destroy(Rating $rating)
    {
        $rating->delete();

        return redirect()
            ->route('dashboard.ratings.index')
            ->with('success', 'Rating deleted successfully');
    }
}

