<?php

namespace App\Http\Controllers;

use App\Traits\HandlesImageUploads;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    use HandlesImageUploads;

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
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];

        if ($user->role === 'chef') {
            $rules['kitchen_name'] = ['nullable', 'string', 'max:255'];
            $rules['bio'] = ['nullable', 'string', 'max:1000'];
            $rules['address'] = ['nullable', 'string', 'max:255'];
        }

        $validated = $request->validate($rules);

        if ($request->hasFile('image')) {
            $folder = $user->role === 'chef' ? 'chefs' : 'users';
            $this->replaceImage($user, $request->file('image'), $folder);
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
        $userRole = $user->role ?? 'user';

        // Last Admin Guard: Cannot delete self if last admin
        if ($userRole === 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return back()->withErrors(['userDeletion' => __('Cannot delete the last remaining administrator account.')]);
            }
        }

        // Active Orders Guard for chefs
        if ($userRole === 'chef') {
            $hasActiveOrders = \App\Models\Order::where('chef_id', $user->id)
                ->whereNotIn('status', ['completed', 'delivered', 'rejected', 'cancelled'])
                ->exists();

            if ($hasActiveOrders) {
                return back()->withErrors(['userDeletion' => __('Cannot delete chef account while having active pending orders.')]);
            }

            // Soft-delete chef meals
            $user->meals()->delete();
        }

        Auth::logout();

        // Customer anonymization to preserve order history
        if ($userRole === 'user') {
            $user->update([
                'name' => 'Deleted User',
                'email' => 'deleted_'.$user->id.'_'.uniqid().'@wajbati.local',
                'phone' => null,
            ]);
        }

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
