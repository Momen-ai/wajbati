<?php

namespace App\Http\Controllers\Front;

use App\Models\Contact;
use App\Http\Controllers\Controller;
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
            'email' => 'required|email',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        // Mapping subject to title if we want to stick to model, 
        // OR we can update the model/migration. 
        // Let's stick to title for now as its standard in the model I saw.
        Contact::create([
            'user_id' => Auth::id(),
            'title' => $validated['subject'] ?? 'No Subject',
            'message' => $validated['message'],
        ]);

        return back()->with('success', 'Your message has been sent successfully! We will get back to you soon.');
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        // In a real app, you'd save this to a newsletter_subscribers table
        // For now, we'll use the Contact model or just a success message
        Contact::create([
            'user_id' => Auth::id(),
            'title' => 'Newsletter Subscription',
            'message' => 'New subscription from: ' . $request->email,
        ]);

        return back()->with('success', 'Thank you for subscribing to our newsletter!');
    }
}
