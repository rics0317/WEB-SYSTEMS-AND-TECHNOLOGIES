<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    // Display a listing of the products with search functionality and pagination
    public function index(Request $request)
    {
        $query = Product::query();

        // Search functionality
        if ($request->has('search')) {
            $query->where('product_name', 'like', '%' . $request->input('search') . '%');
        }

        // Paginate results
        $products = $query->paginate(10); // Adjust the number of items per page as needed

        return view('admin.products.index', compact('products'));
    }

    // Show the form for creating a new product
    public function create()
    {
        return view('admin.products.create');
    }

    // Store a newly created product in storage
    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|min:3|unique:products',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $product = new Product();
        $product->product_name = $request->input('product_name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->stock = $request->input('stock');

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $product->image = $path;
            
        }

        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    // Show the form for editing the specified product
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    // Update the specified product in storage
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'product_name' => 'required|string|min:3|unique:products,product_name,' . $product->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $product->product_name = $request->input('product_name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->stock = $request->input('stock');

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image && Storage::exists('public/' . $product->image)) {
                Storage::delete('public/' . $product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
            $product->image = $imagePath;
        }

        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    // Remove the specified product from storage
    public function destroy(Product $product)
    {
        // Delete the image if it exists
        if ($product->image && Storage::exists('public/' . $product->image)) {
            Storage::delete('public/' . $product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
