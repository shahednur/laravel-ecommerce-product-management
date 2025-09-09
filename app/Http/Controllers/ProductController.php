<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;


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
        // Load relationships if needed
        $product->load(['categories']);
        
        return Inertia::render('Products/Show', [
            'product' => $product
        ]);
    }

    public function edit(Product $product)
    {
        return Inertia::render('Products/Edit', [
            'product' => $product,
            'categories' => Category::all(['id', 'name']),
        ]);
    }

    // Update product
     public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:50', Rule::unique('products')->ignore($product->id)],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'active' => ['boolean'],
            'categories' => ['array'],
            'categories.*' => ['exists:categories,id'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }
            
            $image = $request->file('image');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->extension();
            $image->move(public_path('images/products'), $imageName);
            $validated['image'] = '/images/products/' . $imageName;
        }

        // Generate slug if name changed
        if ($product->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(5);
        }

        // Update product
        $product->update($validated);

        // Sync categories
        if (isset($validated['categories'])) {
            $product->categories()->sync($validated['categories']);
        }

        return redirect()
            ->route('products.show', $product)
            ->with('success', 'Product updated successfully!');
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
