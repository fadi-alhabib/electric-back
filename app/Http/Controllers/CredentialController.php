<?php

namespace App\Http\Controllers;

use App\Models\Credential;
use Illuminate\Http\Request;

class CredentialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $credentials = Credential::all();
        return response()->json($credentials);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'pdf_file' => 'file|mimes:pdf|max:10240', // or  for actual file uploads
        ]);
        $pdfFile = $request->file('pdf_file')->store('pdfs', 'public');
        $credential = new Credential();
        $credential->title = $request->title;
        $credential->pdf_file = $pdfFile;
        $credential->save();
        return response()->json($credential, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Credential $credential)
    {
        return response()->json($credential);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Credential $credential)
    {
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'pdf_file' => 'sometimes|string|max:255', // or 'file|mimes:pdf|max:10240'
        ]);

        $credential->update($request->all());
        return response()->json($credential);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Credential $credential)
    {
        $credential->delete();
        return response()->json(null, 204);
    }
}
