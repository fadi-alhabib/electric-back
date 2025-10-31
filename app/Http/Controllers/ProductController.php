<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Fetch products with optional filters: sub_category_id and search query
    public function index(Request $request)
    {
        $subCategoryId = $request->query('sub_category_id');
        $search = $request->query('search');

        $query = Product::with('subCategory.productLine.category');

        if ($subCategoryId) {
            $query->where('sub_category_id', $subCategoryId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $products = $query->with('productImages')->get();

        // this is supposed to return only the first image of the product use it if you need. (not tested)
        // $products = $query->with(['productImages' => function ($query) {
        //     $query->limit(1);
        // }])->get();

        return response()->json($products, 200);
    }

    // Store a new product
    public function store(Request $request)
    {
        $request->validate([
            'sub_category_id' => 'required|exists:sub_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'serial_number' => 'nullable|string',
            'key_features' => 'nullable|string',
            'dimensions' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image',
        ]);

        $product = new Product();
        $product->sub_category_id = $request->sub_category_id;
        $product->title = $request->title;
        $product->description = $request->description;
        $product->serial_number = $request->serial_number;
        $product->key_features = $request->key_features;
        $product->dimensions = $request->dimensions;
        $product->save();

        if ($request->hasFile('images')) {
            $orderPosition = 0;
            foreach ($request->file('images') as $image) {
                try {
                    $imagePath = $image->store('product_images', 'public');
                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;
                    $productImage->image_path = $imagePath;
                    $productImage->order_position = $orderPosition;
                    $productImage->save();
                    $orderPosition++;
                } catch (\Exception $e) {
                    return response()->json(['error' => 'Failed to upload image'], 500);
                }
            }
        }

        return response()->json($product->load('productImages'), 201);
    }


    // Show a single product by ID
    public function show($id)
    {
        $product = Product::with('subCategory.productLine.category', 'productImages')->find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found.'
            ], 404);
        }

        return response()->json($product, 200);
    }

    // Update an existing product
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found.'
            ], 404);
        }

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
            'serial_number' => 'nullable|string',
            'key_features' => 'nullable|string',
            'dimensions' => 'nullable|string',
        ]);

        $product->update($validated);

        return response()->json($product, 200);
    }

    // Delete a product
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found.'
            ], 404);
        }

        // Delete the associated image if it exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully.'
        ], 200);
    }
}
