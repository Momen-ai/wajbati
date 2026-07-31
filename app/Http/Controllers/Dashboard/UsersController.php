<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\HandlesImageUploads;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    use HandlesImageUploads;


    public function index(Request $request)
    {
        $role = $request->query('role');

        $users = User::when($role, function ($q) use ($role) {
            $q->where('role', $role);
        })->orderBy('id', 'desc')->paginate(15);

        return view('admin.users.index', compact('users', 'role'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'phone' => 'required|string|unique:users,phone',
            'role' => 'required|in:admin,chef,user',
            'image'       => 'required|image|mimes:jpg,jpeg,png,webp',
        ]);

        $validated['password'] = Hash::make($validated['password']);


        $user = User::create(Arr::except($validated, ['image']));

        if ($request->hasFile('image')) {
            $path = $this->uploadImage($request->file('image'), 'users');
            $user->image()->create(['image_path' => $path]);
        }

        return redirect()->route('dashboard.users.index')
            ->with('success', 'User created successfully');
    }



    public function show(User $user)
    {
        $user->load('image');
        return view('admin.users.show', compact('user'));
    }


    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'sometimes',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'phone' => 'sometimes|string|unique:users,phone,' . $user->id,
            'role' => 'sometimes|in:admin,chef,user',
        ]);

        if ($request->hasFile('image')) {
            $this->replaceImage($user, $request->file('image'), 'users');
        }

        if ($request->has('remove_image')) {
            $this->deleteImage($user);
        }

        $user->update($validated);

        // $user->update($request->only('name', 'email', 'role'));

        return redirect()->route('dashboard.users.index')
            ->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            abort(403);
        }

        $user->delete();

        return redirect()->route('dashboard.users.index')
            ->with('success', 'User deleted successfully');
    }
}

    //
