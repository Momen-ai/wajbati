<?php

namespace App\Http\Controllers\Chef;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use App\Models\Category;
use App\Traits\HandlesImageUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class MealController extends Controller
{
    use HandlesImageUploads;

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
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image'       => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $validated['chef_id'] = Auth::id();

        $meal = Meal::create($validated);

        if ($request->hasFile('image')) {
            $path = $this->uploadImage($request->file('image'), 'meals');
            $meal->image()->create(['image_path' => $path]);
        }

        return redirect()->route('chef.meals.index')->with('success', 'Meal created successfully.');
    }

    public function edit(Meal $meal)
    {
        Gate::authorize('update', $meal);

        $categories = Category::all();
        return view('front.chef.meals.edit', compact('meal', 'categories'));
    }

    public function update(Request $request, Meal $meal)
    {
        Gate::authorize('update', $meal);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $this->replaceImage($meal, $request->file('image'), 'meals');
        }

        $meal->update($validated);

        return redirect()->route('chef.meals.index')->with('success', 'Meal updated successfully.');
    }

    public function destroy(Meal $meal)
    {
        Gate::authorize('delete', $meal);

        $this->deleteImage($meal);
        $meal->delete();

        return redirect()->route('chef.meals.index')->with('success', 'Meal deleted successfully.');
    }
}
