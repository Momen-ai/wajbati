<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contacts = Contact::with(['user', 'chef'])
            ->latest()
            ->paginate(10);

        return view('admin.contacts.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.contacts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'message' => 'required|string',
            'type'    => 'required|in:contact,complaint,support',
        ]);

        Contact::create([
            'user_id' => Auth::id(),
            'title'   => $validated['title'],
            'message' => $validated['message'],
            'type'    => $validated['type'],
        ]);

        return redirect()
            ->route('dashboard.contacts.index')
            ->with('success', 'Message sent successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        // تعليم الرسالة كمقروءة
        if (! $contact->is_read) {
            $contact->update(['is_read' => true]);
        }

        return view('admin.contacts.show', compact('contact'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()
            ->route('dashboard.contacts.index')
            ->with('success', 'Message deleted successfully');
    }
}
