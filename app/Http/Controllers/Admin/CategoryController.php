<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Item;

class CategoryController extends Controller
{
    public function showAddCategoryForm()
    {
        $categories = Category::with('subCategories')->get();
        $user = auth()->user(); // Retrieve the authenticated user
        return view('admin.products.add-new-category', compact('categories', 'user'));
    }

    public function showAddSubCategoryForm()
    {
        $categories = Category::all();
        $subCategories = SubCategory::with('category')->get();
        $user = auth()->user(); // Retrieve the authenticated user
        return view('admin.products.add-new-subcategory', compact('categories', 'subCategories', 'user'));
    }

    public function showAddItemCategoryForm()
    {
        $categories = Category::all();
        $subCategories = SubCategory::with('category')->get();
        $items = Item::with('subCategory.category')->get();
        $user = auth()->user(); // Retrieve the authenticated user
        return view('admin.products.add-new-itemcategory', compact('categories', 'subCategories', 'items', 'user'));
    }

    public function addCategory(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'category_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $categoryImagePath = null;
        if ($request->hasFile('category_image')) {
            $categoryImagePath = $request->file('category_image')->store('category_images', 'public');
        }

        // Create or find the main category
        $category = Category::firstOrCreate(['name' => $request->category]);
        $category->category_image = $categoryImagePath;
        $category->save();

        return redirect()->route('admin.products.categories.add')->with('success', 'Category added successfully.');
    }

    public function addSubCategory(Request $request)
{
    $request->validate([
        'category_id' => 'required|exists:categories,id',
        'sub_category' => 'required|string|max:255',
    ]);

    $subCategoryNames = explode(',', $request->sub_category);
    $subCategoryNames = array_map('trim', $subCategoryNames); // Remove whitespace

    foreach ($subCategoryNames as $name) {
        if (!empty($name)) {
            $subCategory = new SubCategory([
                'name' => $name,
            ]);

            $category = Category::findOrFail($request->category_id);
            $category->subCategories()->save($subCategory);
        }
    }

    return redirect()->route('admin.products.subcategories.add')->with('success', 'Sub-categories added successfully.');
}


    public function addItemCategory(Request $request)
    {
        $request->validate([
            'sub_category_id' => 'required|exists:sub_categories,id',
            'item_category' => 'required|string|max:255',
        ]);

        $itemCategoryNames = explode(',', $request->item_category);
        $itemCategoryNames = array_map('trim', $itemCategoryNames); // Remove whitespace

        foreach ($itemCategoryNames as $name) {
            if (!empty($name)) {
                $item = new Item([
                    'name' => $name,
                ]);

                $subCategory = SubCategory::findOrFail($request->sub_category_id);
                $subCategory->items()->save($item);
            }
        }

        return redirect()->route('admin.products.itemcategories.add')->with('success', 'Item categories added successfully.');
    }

    public function index()
    {
        $categories = Category::with('subCategories')->get();
        return response()->json($categories);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $categories = Category::where('name', 'like', "%$query%")->with('subCategories')->get();
        return view('admin.products.add-new-category', compact('categories'));
    }

    public function delete($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.products.categories.add')->with('success', 'Category deleted successfully.');
    }

    public function deleteSubCategory($id)
    {
        $subCategory = SubCategory::findOrFail($id);
        $subCategory->delete();

        return redirect()->route('admin.products.subcategories.add')->with('success', 'Sub-category deleted successfully.');
    }

    public function deleteItemCategory($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return redirect()->route('admin.products.itemcategories.add')->with('success', 'Item category deleted successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'category_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $category = Category::findOrFail($id);
        $category->name = $request->category;

        if ($request->hasFile('category_image')) {
            $categoryImagePath = $request->file('category_image')->store('category_images', 'public');
            $category->category_image = $categoryImagePath;
        }

        $category->save();

        return redirect()->route('admin.products.categories.add')->with('success', 'Category updated successfully.');
    }

    public function updateSubCategory(Request $request, $id)
    {
        $request->validate([
            'sub_category' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);

        $subCategory = SubCategory::findOrFail($id);
        $subCategory->name = $request->sub_category;
        $subCategory->category_id = $request->category_id;
        $subCategory->save();

        return redirect()->route('admin.products.subcategories.add')->with('success', 'Sub-category updated successfully.');
    }

    public function updateItemCategory(Request $request, $id)
    {
        $request->validate([
            'item_category' => 'required|string|max:255',
            'sub_category_id' => 'required|exists:sub_categories,id',
        ]);

        $item = Item::findOrFail($id);
        $item->name = $request->item_category;
        $item->sub_category_id = $request->sub_category_id;
        $item->save();

        return redirect()->route('admin.products.itemcategories.add')->with('success', 'Item category updated successfully.');
    }
}
