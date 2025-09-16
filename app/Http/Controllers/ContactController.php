<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Submit a contact form.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
'number' => 'required|digits:10',
            'message' => 'required|string|max:1000',
        ]);

        $contact = Contact::create($validated);

        return response()->json([
            'message' => 'Contact submitted successfully.',
            'data' => $contact
        ], 201);
    }

    /**
     * List all contact submissions.
     */
    public function index()
    {
        $contacts = Contact::latest()->paginate(10);

        return response()->json([
            'data' => $contacts
        ]);
    }

    /**
     * Show a specific contact submission.
     */
    public function show(Contact $contact)
    {
        return response()->json([
            'data' => $contact
        ]);
    }

    /**
     * Delete a contact submission.
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();

        return response()->json([
            'message' => 'Contact deleted successfully.'
        ]);
    }
}

