<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
     // Get all products
    public function index()
    {
        $products = Product::with('categories')->get();

        return Inertia::render('Products/Index', [
            'products' => $products,
            'totalProducts' => $products->count(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Products/Create');
    }

    // Store a new product
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'categories' => 'array',
            'categories.*' => 'exists:categories,id',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        $product = Product::create($validated);

        if (isset($validated['categories'])) {
            $product->categories()->sync($validated['categories']);
        }

        return response()->json($product->load('categories'), 201);
    }


    // Show single product
    public function show(Product $product)
    {
        return response()->json($product->load('categories'));
    }

    // Update product
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric',
            'description' => 'nullable|string',
        ]);

        $product->update($validated);

        return response()->json($product);
    }

    // Delete product
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }

    // Attach category to product
    public function attachCategory(Product $product, Category $category)
    {
        $product->categories()->attach($category->id);
        return response()->json(['message' => 'Category attached successfully']);
    }

    // Detach category from product
    public function detachCategory(Product $product, Category $category)
    {
        $product->categories()->detach($category->id);
        return response()->json(['message' => 'Category detached successfully']);
    }

    // Get all categories of a product
    public function categories(Product $product)
    {
        return response()->json($product->categories);
    }
}
