<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
{
    // Fetch categories with pagination
    $categories = Category::paginate(10); // Adjust the number of items per page
    return view('admin.category.index', compact('categories')); // Ensure this points to the correct view
}

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|string',
            'description' => 'required|max:255|string',
            'is_active' => 'sometimes|boolean', // Ensure is_active is a boolean
        ]);

        Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->is_active ? 1 : 0, // Simplified the condition
        ]);

        return redirect('admin/category/create')->with('status', 'Category Created');
    }

    public function edit(int $id)
    {
        $category = Category::findOrFail($id);
        return view('admin.category.edit', compact('category'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'name' => 'required|max:255|string',
            'description' => 'required|max:255|string',
            'is_active' => 'sometimes|boolean', // Ensure is_active is a boolean
        ]);

        Category::findOrFail($id)->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->is_active ? 1 : 0, // Simplified the condition
        ]);

        return redirect()->back()->with('status', 'Category Updated');
    }

    public function destroy($id)
{
    $category = Category::findOrFail($id);
    $category->delete();

    return redirect()->route('admin.category.index')->with('success', 'Category deleted successfully.');
}

}
