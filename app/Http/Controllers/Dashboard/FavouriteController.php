<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Favourite;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FavouriteController extends Controller
{
    public function index()
    {
        $favourites = Favourite::with(['user', 'chef'])
            ->latest()
            ->paginate(10);

        return view('admin.favourites.index', compact('favourites'));
    }

    public function create()
    {
        $users = User::all();
        $chefs = User::where('role', 'chef')->get();

        return view('admin.favourites.create', compact('users', 'chefs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'chef_id' => 'required|exists:users,id',
        ]);

        // منع التكرار
        Favourite::firstOrCreate($validated);

        return redirect()
            ->route('dashboard.favourites.index')
            ->with('success', 'Favourite added successfully');
    }

    public function edit(Favourite $favourite)
    {
        $users = User::all();
        $chefs = User::where('role', 'chef')->get();

        return view('admin.favourites.edit', compact('favourite', 'users', 'chefs'));
    }

    public function update(Request $request, Favourite $favourite)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'chef_id' => 'required|exists:users,id',
        ]);

        $favourite->update($validated);

        return redirect()
            ->route('dashboard.favourites.index')
            ->with('success', 'Favourite updated successfully');
    }

    public function destroy(Favourite $favourite)
    {
        $favourite->delete();

        return redirect()
            ->route('dashboard.favourites.index')
            ->with('success', 'Favourite deleted successfully');
    }
}

