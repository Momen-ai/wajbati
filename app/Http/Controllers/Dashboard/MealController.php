<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Meal;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class MealController extends Controller
{
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
            'chef_id'     => 'required|exists:users,id',
            'category_id' => 'nullable|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric',
            'image'       => 'required|image|mimes:jpg,jpeg,png,webp',
        ]);

        // رفع الصورة
        if ($request->file('image')) {

            $image = $request->file('image');
            $path =  $image->store('meals', 'public');
        }

        $validated = Arr::except($validated, ['image']);
        $meal = Meal::create($validated);

        $meal->image()->create([
            'image_path' => $path
        ]);

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
            'chef_id'     => 'required|exists:users,id',
            'category_id' => 'nullable|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        // صورة جديدة
        if ($request->hasFile('image')) {

            $newImage = $request->file('image');
            $newPath  = $newImage->store('meals', 'public');

            if ($meal->image) {

                $oldPath = public_path('image/' . $meal->image->image_path);

                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }

                $meal->image->update([
                    'image_path' => $newPath
                ]);
            } else {
                $meal->image()->create([
                    'image_path' => $newPath
                ]);
            }
        }


        if ($request->has('remove_image') && $meal->image) {

            $oldPath = storage_path('app/public/' . $meal->image->image_path);

            if (File::exists($oldPath)) {
                File::delete($oldPath);
            }

            $meal->image->delete();
        }

        $meal->update($validated);

        return redirect()
            ->route('dashboard.meals.index')
            ->with('success', 'Meal updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meal $meal)
    {

        $meal->delete();

        return redirect()
            ->route('dashboard.meals.index')
            ->with('success', 'Meal deleted successfully');
    }
}
