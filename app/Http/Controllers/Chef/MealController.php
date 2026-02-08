<?php

namespace App\Http\Controllers\Chef;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class MealController extends Controller
{
    public function index()
    {
        $meals = Meal::where('chef_id', Auth::id())->with('category')->orderBy('id', 'desc')->paginate(10);
        return view('front.chef.meals.index', compact('meals'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('front.chef.meals.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $validated['chef_id'] = Auth::id();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('meals', 'public');
        }

        $meal = Meal::create($validated);

        if (isset($path)) {
            $meal->image()->create([
                'image_path' => $path
            ]);
        }

        return redirect()->route('chef.meals.index')->with('success', 'Meal created successfully.');
    }

    public function edit(Meal $meal)
    {
        if ($meal->chef_id !== Auth::id()) {
            abort(403);
        }

        $categories = Category::all();
        return view('front.chef.meals.edit', compact('meal', 'categories'));
    }

    public function update(Request $request, Meal $meal)
    {
        if ($meal->chef_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($meal->image) {
                Storage::disk('public')->delete($meal->image->image_path);
                $meal->image()->delete();
            }

            $image = $request->file('image');
            $path = $image->store('meals', 'public');

            $meal->image()->create([
                'image_path' => $path
            ]);
        }

        $meal->update($validated);

        return redirect()->route('chef.meals.index')->with('success', 'Meal updated successfully.');
    }

    public function destroy(Meal $meal)
    {
        if ($meal->chef_id !== Auth::id()) {
            abort(403);
        }

        if ($meal->image) {
            Storage::disk('public')->delete($meal->image->image_path);
            $meal->image()->delete();
        }

        $meal->delete();

        return redirect()->route('chef.meals.index')->with('success', 'Meal deleted successfully.');
    }
}
