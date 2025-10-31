<?php

namespace App\Http\Controllers;

use App\Models\Whychooseus;
use Illuminate\Http\Request;

class WhyChooseUsController extends Controller
{
    // Display all records
    public function index()
    {
        return Whychooseus::all();
    }

    // Store a new record
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        return Whychooseus::create($validatedData);
    }

    // Delete a record
    public function destroy($id)
    {
        Whychooseus::findOrFail($id)->delete();
        return response()->json(['message' => 'Record deleted successfully']);
    }
}
