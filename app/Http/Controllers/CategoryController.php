<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Get all categories
    public function index()
    {
        return response()->json(Category::with('products')->get());
    }

    // Store category
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
        ]);

        $category = Category::create($validated);

        return response()->json($category, 201);
    }

    // Show category
    public function show(Category $category)
    {
        return response()->json($category->load('products'));
    }

    // Update category
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255|unique:categories,name,' . $category->id,
        ]);

        $category->update($validated);

        return response()->json($category);
    }

    // Delete category
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(['message' => 'Category deleted successfully']);
    }

    // Get all products in a category
    public function products(Category $category)
    {
        return response()->json($category->products);
    }
}
