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
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

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
        $user = $request->user();

        if ($user->addresses()->count() >= 10) {
            return back()->with('error', __('You cannot add more than 10 addresses.'));
        }

        $validated = $request->validate([
            'address' => 'required|string|max:255',
            'city' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:25',
            'type' => 'required|in:home,work,other',
            'is_default' => 'nullable|boolean',
        ]);

        $isDefault = $request->boolean('is_default');
        if ($isDefault || $user->addresses()->count() === 0) {
            $user->addresses()->update(['is_default' => false]);
            $validated['is_default'] = true;
        }

        $user->addresses()->create($validated);

        return back()->with('success', __('Address added successfully!'));
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
