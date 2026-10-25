<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\HandlesImageUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
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
            'email' => 'required|email|max:255|unique:users',
            'password' => ['required', \Illuminate\Validation\Rules\Password::defaults()],
            'phone' => 'required|string|max:25|unique:users,phone',
            'role' => 'required|in:admin,chef,user',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = new User(Arr::except($validated, ['image', 'role']));
        $user->role = $validated['role'];
        $user->save();

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
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'sometimes|required|string|max:25|unique:users,phone,'.$user->id,
            'password' => ['nullable', \Illuminate\Validation\Rules\Password::defaults()],
            'role' => 'sometimes|in:admin,chef,user',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $this->replaceImage($user, $request->file('image'), 'users');
        }

        if ($request->has('remove_image')) {
            $this->deleteImage($user);
        }

        if (isset($validated['role'])) {
            if ($user->role === 'admin' && $validated['role'] !== 'admin') {
                $adminCount = User::where('role', 'admin')->count();
                if ($adminCount <= 1) {
                    return redirect()->route('dashboard.users.index')
                        ->with('error', __('Cannot demote the last remaining administrator account.'));
                }
            }
            $user->role = $validated['role'];
        }

        $user->fill(Arr::except($validated, ['role', 'image']));
        $user->save();

        return redirect()->route('dashboard.users.index')
            ->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return redirect()->route('dashboard.users.index')
                    ->with('error', __('Cannot delete the last remaining administrator account.'));
            }
        }

        $user->delete();

        return redirect()->route('dashboard.users.index')
            ->with('success', 'User deleted successfully');
    }
}

//
