<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function contact()
    {
        return view('front.pages.contact');
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:25',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        Contact::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'title' => $validated['subject'] ?? 'No Subject',
            'message' => $validated['message'],
        ]);

        return back()->with('success', __('Your message has been sent successfully! We will get back to you soon.'));
    }

    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
        ]);

        \App\Models\NewsletterSubscriber::firstOrCreate(
            ['email' => $validated['email']],
            ['confirmed_at' => now()]
        );

        return back()->with('success', __('Thank you for subscribing to our newsletter!'));
    }
}
