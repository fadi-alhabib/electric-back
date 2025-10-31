<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ProductLine;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('category_images', 'public');
        }

        $category = Category::create([
            'category_name' => $request->input('category_name'),
            'description' => $request->input('description'),
            'image' => $imagePath,
        ]);

        return response()->json($category, 201);
    }

    public function show($id)
    {
        $category = Category::with('productLines.subCategories.products')->find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Category not found.'
            ], 404);
        }

        return response()->json($category, 200);
    }

    public function showProductLine($id)
    {
        $productLine = ProductLine::find($id);
        return response()->json($productLine, 200);
    }

    public function showSubCategory($id)
    {
        $subCategory = SubCategory::find($id);
        return response()->json($subCategory, 200);
    }

    public function addProductLine(Request $request, $categoryId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $category = Category::find($categoryId);

        if (!$category) {
            return response()->json([
                'message' => 'Category not found.'
            ], 404);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('product_line_images', 'public');
        }

        $productLine = new ProductLine([
            'name' => $request->input('name'),
            'image' => $imagePath,
        ]);

        $category->productLines()->save($productLine);

        return response()->json($productLine, 201);
    }

    public function editProductLine(Request $request, $productLineId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $productLine = ProductLine::find($productLineId);

        if (!$productLine) {
            return response()->json([
                'message' => 'Product line not found.'
            ], 404);
        }

        // Check if a new image is uploaded
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($productLine->image) {
                Storage::disk('public')->delete($productLine->image);
            }

            // Store the new image
            $imagePath = $request->file('image')->store('product_line_images', 'public');
            $productLine->image = $imagePath;
        }

        // Update the product line's name
        $productLine->name = $request->input('name');

        // Save the updated product line
        $productLine->save();

        return response()->json($productLine, 200);
    }

    public function getProductLines($categoryId)
    {
        $category = Category::with('productLines')->find($categoryId);

        if (!$category) {
            return response()->json([
                'message' => 'Category not found.'
            ], 404);
        }

        return response()->json($category->productLines, 200);
    }
    public function deleteProductLine($productLineId)
    {
        $productLine = ProductLine::find($productLineId);

        if (!$productLine) {
            return response()->json([
                'message' => 'Product line not found.'
            ], 404);
        }

        // Optionally delete the associated image
        if ($productLine->image) {
            Storage::disk('public')->delete($productLine->image);
        }

        $productLine->delete();

        return response()->json([
            'message' => 'Product line deleted successfully.'
        ], 200);
    }

    public function addSubCategory(Request $request, $categoryId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $productLine = ProductLine::find($categoryId);

        if (!$productLine) {
            return response()->json([
                'message' => 'Category not found.'
            ], 404);
        }

        $subCategory = new SubCategory([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);

        $productLine->subCategories()->save($subCategory);

        return response()->json($subCategory, 201);
    }


    public function editSubCategory(Request $request, $subCategoryId)
    {
        $request->validate([
            'name' => 'required|string|max:255',

        ]);

        $subCategory = SubCategory::find($subCategoryId);

        if (!$subCategory) {
            return response()->json([
                'message' => 'Subcategory not found.'
            ], 404);
        }

        $subCategory->name = $request->input('name');

        $subCategory->save();

        return response()->json($subCategory, 200);
    }

    public function deleteSubCategory($subCategoryId)
    {
        $subCategory = SubCategory::find($subCategoryId);

        if (!$subCategory) {
            return response()->json([
                'message' => 'Subcategory not found.'
            ], 404);
        }

        $subCategory->delete();

        return response()->json([
            'message' => 'Subcategory deleted successfully.'
        ], 200);
    }


    public function getSubcategories($categoryId)
    {
        $productLine = ProductLine::with('subCategories.products')->find($categoryId);

        if (!$productLine) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        $subCategories = SubCategory::with('products.productImages')->where('product_line_id', $productLine->id)->get();
        return response()->json($subCategories);
    }
}
