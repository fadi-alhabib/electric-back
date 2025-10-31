<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;

class ProductImagesController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            "product_id" => "required|exists:products,id",
            "image" => "required|file",
        ]);
        $imagePath = $request->file("image")->store("product_images", "public");
        
        // Get the next order position for this product
        $maxOrder = ProductImage::where('product_id', $request->product_id)
            ->max('order_position');
        $nextOrder = $maxOrder !== null ? $maxOrder + 1 : 0;
        
        $productImage = new ProductImage();
        $productImage->product_id = $request->product_id;
        $productImage->image_path = $imagePath;
        $productImage->order_position = $nextOrder;
        $productImage->save();
        return response()->json($productImage, 201);
    }

    public function destroy(Request $request, $id)
    {
        $productImage = ProductImage::find($id);
        if (!$productImage) {
            return response()->json([
                'message' => 'Product not found.'
            ], 404);
        }
        Storage::disk('public')->delete($productImage->image_path);
        $productImage->delete();

        return response()->json([
            'message' => 'Product Image deleted successfully.'
        ], 200);
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'image_orders' => 'required|array',
            'image_orders.*.id' => 'required|exists:product_images,id',
            'image_orders.*.order_position' => 'required|integer|min:0'
        ]);

        $productId = $request->product_id;
        $imageOrders = $request->image_orders;

        // Verify all images belong to the specified product
        $imageIds = collect($imageOrders)->pluck('id');
        $productImages = ProductImage::where('product_id', $productId)
            ->whereIn('id', $imageIds)
            ->get();

        if ($productImages->count() !== count($imageIds)) {
            return response()->json([
                'message' => 'Some images do not belong to the specified product.'
            ], 400);
        }

        // Update the order positions
        foreach ($imageOrders as $imageOrder) {
            ProductImage::where('id', $imageOrder['id'])
                ->update(['order_position' => $imageOrder['order_position']]);
        }

        // Return updated product images
        $updatedImages = ProductImage::where('product_id', $productId)
            ->orderBy('order_position')
            ->get();

        return response()->json([
            'message' => 'Images reordered successfully.',
            'images' => $updatedImages
        ], 200);
    }
}
