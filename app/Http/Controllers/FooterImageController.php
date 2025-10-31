<?php

namespace App\Http\Controllers;

use App\Models\FooterImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FooterImageController extends Controller
{
    public function index()
    {
        return FooterImage::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'alt_text' => 'nullable|string',
        ]);

        $imagePath = $request->file('image')->store('footer_images', 'public');
        $fullUrl = url('storage/' . $imagePath);

        $footerImage = FooterImage::create([
            'image_path' => $fullUrl,
            'alt_text' => $request->input('alt_text'),
        ]);

        return response()->json($footerImage, 201);
    }

    // Add the delete function
    public function destroy($id)
    {
        // Find the footer image by ID
        $footerImage = FooterImage::find($id);

        if (!$footerImage) {
            return response()->json(['message' => 'Footer image not found'], 404);
        }

        // Extract the image path from the full URL
        $imagePath = str_replace(url('storage/'), '', $footerImage->image_path);

        // Delete the image from storage
        if (Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }

        // Delete the database record
        $footerImage->delete();

        return response()->json(['message' => 'Footer image deleted successfully'], 200);
    }
}
