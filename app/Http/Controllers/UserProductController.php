<?php

namespace App\Http\Controllers;

use App\Models\Product; // Make sure to import the Product model
use Illuminate\Http\Request;

class UserProductController extends Controller
{
    public function index(Request $request)
    {
        // Fetch products, with optional search functionality
        $search = $request->input('search');
        $products = Product::when($search, function ($query, $search) {
            return $query->where('product_name', 'like', '%' . $search . '%');
        })->get();

        // Return the view with the products
        return view('users.products.index', compact('products'));
    }
        
}
