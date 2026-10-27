<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\ChefApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChefApplicationController extends Controller
{
    public function create(): View|RedirectResponse
    {
        $user = auth()->user();

        if ($user->role === 'chef' || $user->role === 'admin') {
            return redirect()->route('home')->with('error', __('You already have chef access.'));
        }

        $existing = ChefApplication::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        return view('front.chef.apply', compact('existing'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();

        if ($user->role === 'chef' || $user->role === 'admin') {
            return redirect()->route('home')->with('error', __('You already have chef access.'));
        }

        $existing = ChefApplication::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return back()->with('error', __('You already have a pending chef application.'));
        }

        $validated = $request->validate([
            'kitchen_name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'phone' => 'required|string|max:25',
            'address' => 'nullable|string|max:255',
        ]);

        $validated['user_id'] = $user->id;
        $validated['status'] = 'pending';

        ChefApplication::create($validated);

        return redirect()->route('home')->with('success', __('Your chef application has been submitted successfully and is awaiting review!'));
    }
}
