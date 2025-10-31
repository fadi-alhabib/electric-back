<?php

namespace App\Http\Controllers;

use App\Models\Contactus;
use Illuminate\Http\Request;

class ContactusController extends Controller
{
    /**
     * Get all contactus entries.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $contacts = Contactus::all();
        return response()->json($contacts);
    }

    /**
     * Create a new contactus entry.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'attachment' => 'nullable'
        ]);

        if($request->hasFile('attachment')){
            $new_attachment_path = $request->file('attachment')->store('contactus_attachments', 'public'); 
        }

        // Create the new Contactus entry
        $contact = Contactus::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'subject' => $validatedData['subject'],
            'message' => $validatedData['message'],
            'attachment_path' => $new_attachment_path
        ]);

        // Return a success response
        return response()->json(['message' => 'Contactus entry created successfully!', 'data' => $contact], 201);
    }
}
