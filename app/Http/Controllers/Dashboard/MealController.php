<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Meal;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\HandlesImageUploads;

class MealController extends Controller
{
    use HandlesImageUploads;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $meals = Meal::with(['chef', 'category'])->orderBy('id', 'desc')->paginate(15);
        return view('admin.meals.index', compact('meals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $chefs = User::where('role', 'chef')->get();
        $categories = Category::all();

        return view('admin.meals.create', compact('chefs', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'chef_id'     => 'required|exists:users,id,role,chef',
            'category_id' => 'nullable|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric',
            'image'       => 'required|image|mimes:jpg,jpeg,png,webp',
        ]);

        $meal = Meal::create(Arr::except($validated, ['image']));

        if ($request->hasFile('image')) {
            $path = $this->uploadImage($request->file('image'), 'meals');
            $meal->image()->create(['image_path' => $path]);
        }

        return redirect()
            ->route('dashboard.meals.index')
            ->with('success', 'Meal created successfully');
    }


    /**
     * Display the specified resource.
     */
    public function show(Meal $meal)
    {
        $meal->load('image', 'chef', 'category');
        return view('admin.meals.show', compact('meal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Meal $meal)
    {
        $chefs = User::where('role', 'chef')->get();
        $categories = Category::all();

        return view('admin.meals.edit', compact('meal', 'chefs', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Meal $meal)
    {
        $validated = $request->validate([
            'chef_id'     => 'required|exists:users,id,role,chef',
            'category_id' => 'nullable|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        // Bug Fix: previously used public_path('image/...') which resolves to the wrong path.
        // Now uses Storage::disk('public') via HandlesImageUploads::replaceImage() consistently.
        if ($request->hasFile('image')) {
            $this->replaceImage($meal, $request->file('image'), 'meals');
        }

        if ($request->has('remove_image')) {
            $this->deleteImage($meal);
        }

        $meal->update(Arr::except($validated, ['image']));

        return redirect()
            ->route('dashboard.meals.index')
            ->with('success', 'Meal updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meal $meal)
    {
        $this->deleteImage($meal);
        $meal->delete();

        return redirect()
            ->route('dashboard.meals.index')
            ->with('success', 'Meal deleted successfully');
    }
}
