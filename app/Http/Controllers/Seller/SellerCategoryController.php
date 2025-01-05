<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Item;

class SellerCategoryController extends Controller
{
    public function index()
    {
        return response()->json(Category::all());
    }

    public function subCategories($categoryId)
    {
        $category = Category::find($categoryId);
        return response()->json($category->subCategories);
    }

    public function items($subCategoryId)
    {
        $subCategory = SubCategory::find($subCategoryId);
        return response()->json($subCategory->items);
    }
}
