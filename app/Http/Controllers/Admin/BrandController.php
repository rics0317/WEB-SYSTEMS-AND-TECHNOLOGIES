<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Category;

class BrandController extends Controller
{
    public function showAddBrandForm()
{
    $categories = Category::all();
    $brands = Brand::all(); // Fetch all brands
    $user = auth()->user();
    return view('admin.products.add-newbrand', compact('categories', 'brands', 'user'));
}
public function destroy($id)
{
    $brand = Brand::findOrFail($id);
    $brand->delete();

    return redirect()->route('admin.products.add-newbrand')->with('success', 'Brand deleted successfully.');
}

// BrandController.php
public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:brands,name,' . $id,
        'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        'category_id' => 'required|exists:categories,id',
    ], [
        'name.unique' => 'The Brand name has already been taken.',
    ]);

    $brand = Brand::findOrFail($id);

    if ($request->hasFile('logo')) {
        $logoPath = $request->file('logo')->store('logos', 'public');
        $brand->logo = $logoPath;
    }

    $brand->name = $request->name;
    $brand->category_id = $request->category_id;
    $brand->save();

    return redirect()->route('admin.products.add-newbrand')->with('success', 'Brand updated successfully.');
}

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'category_id' => 'required|exists:categories,id',
        ], [
            'name.unique' => 'The Brand name has already been taken.',
        ]);

        $logoPath = $request->file('logo')->store('logos', 'public');

        Brand::create([
            'name' => $request->name,
            'logo' => $logoPath,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('admin.products.add-newbrand')->with('success', 'Brand added successfully.');
    }
}
