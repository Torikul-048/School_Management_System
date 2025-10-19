<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::latest()->paginate(20);
        return view('contacts.index', compact('contacts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);

        Contact::create($validated);

        return redirect('/#contact')->with('success', 'Thank you for contacting us! We will get back to you soon.');
    }

    public function show(Contact $contact)
    {
        // Mark as read when viewing
        if ($contact->status === 'new') {
            $contact->update(['status' => 'read']);
        }

        return view('contacts.show', compact('contact'));
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return redirect()->route('contacts.index')->with('success', 'Contact message deleted successfully.');
    }

    public function markAsRead(Contact $contact)
    {
        $contact->update(['status' => 'read']);
        return back()->with('success', 'Message marked as read.');
    }

    public function markAsReplied(Contact $contact)
    {
        $contact->update(['status' => 'replied']);
        return back()->with('success', 'Message marked as replied.');
    }
}
