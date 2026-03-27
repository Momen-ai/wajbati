<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'phone' => ['nullable', 'string', 'max:20'],
        ];

        if ($user->role === 'chef') {
            $rules['kitchen_name'] = ['nullable', 'string', 'max:255'];
            $rules['bio'] = ['nullable', 'string', 'max:1000'];
            $rules['address'] = ['nullable', 'string', 'max:255'];
            $rules['image'] = ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'];
        }

        $validated = $request->validate($rules);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($user->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->image->image_path);
                $user->image()->delete();
            }

            $path = $request->file('image')->store('chefs', 'public');
            $user->image()->create(['image_path' => $path]);
        }

        $user->fill(\Illuminate\Support\Arr::except($validated, ['image']));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function addAddress(Request $request): RedirectResponse
    {
        $request->validate([
            'address' => 'required|string|max:255',
            'city' => 'nullable|string|max:100',
            'type' => 'required|in:home,work,other',
        ]);

        $request->user()->addresses()->create($request->all());

        return back()->with('success', 'Address added successfully!');
    }

    public function deleteAddress(\App\Models\Address $address): RedirectResponse
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $address->delete();
        return back()->with('success', 'Address deleted successfully!');
    }
}
