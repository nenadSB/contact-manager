<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    // GET: Fetch all contacts
    public function index()
    {
        return response()->json(Contact::all(), 200);
    }

    // POST: Store a new contact
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:contacts',
            'phone_number' => 'required|string|max:15',
        ]);

        $contact = Contact::create($validatedData);
        return response()->json($contact, 201);
    }

    // GET: Fetch a single contact
    public function show($id)
    {
        $contact = Contact::find($id);

        if (!$contact) {
            return response()->json(['message' => 'Contact not found'], 404);
        }

        return response()->json($contact, 200);
    }

    // PUT: Update an existing contact
    public function update(Request $request, $id)
    {
        $contact = Contact::find($id);

        if (!$contact) {
            return response()->json(['message' => 'Contact not found'], 404);
        }

        $validatedData = $request->validate([
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:contacts,email,' . $contact->id,
            'phone_number' => 'sometimes|required|string|max:15',
        ]);

        $contact->update($validatedData);

        return response()->json($contact, 200);
    }

    // DELETE: Delete a contact
    public function destroy($id)
    {
        $contact = Contact::find($id);

        if (!$contact) {
            return response()->json(['message' => 'Contact not found'], 404);
        }

        $contact->delete();
        return response()->json(null, 204);
    }
}
