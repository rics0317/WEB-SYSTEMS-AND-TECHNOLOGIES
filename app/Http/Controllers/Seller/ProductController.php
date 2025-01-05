<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariation;
use App\Models\VariationOption;
use App\Models\VariationOptionSize;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Item;
use App\Models\Brand;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{

    public function myProducts(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            return redirect()->route('users.home')->with('error', 'You do not have permission to access this page.');
        }

        $query = Product::query();

        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('productName')) {
            $query->where('name', 'like', "%{$request->productName}%");
        }

        if ($request->has('stockMin') && $request->has('stockMax')) {
            $query->whereBetween('stock', [$request->stockMin, $request->stockMax]);
        }

        if ($request->has('salesMin') && $request->has('salesMax')) {
            $query->whereBetween('sales', [$request->salesMin, $request->salesMax]);
        }

        if ($request->has('variation')) {
            $query->whereHas('variations', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->variation}%");
            });
        }

        $products = $query->with(['variations.options.sizes', 'category', 'variations.options.sizes.option.variation'])->get();
        $categories = Category::all();
        $variations = ProductVariation::all();

        return view('admin.myproducts', compact('user', 'products', 'categories', 'variations'));
    }

    public function storeProduct(Request $request)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validate([
                'name' => 'required|string|max:100',
                'description' => 'required|string|max:2000',
                'category_id' => 'required|exists:categories,id',
                'sub_category_id' => 'required|exists:sub_categories,id',
                'item_id' => 'required|exists:items,id',
                'price' => 'required|numeric|min:0',
                'discount_percentage' => 'nullable|integer|min:0|max:100',
                'images' => 'required|array',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'variations' => 'nullable|json',
                'brand_id' => 'nullable|exists:brands,id',
                'stocks' => 'nullable|integer',
            ]);

            $user = Auth::user();

            if (!$user->hasRole('admin')) {
                return response()->json(['message' => 'You do not have permission to create a product.'], 403);
            }

            // Generate SKU
            $category = Category::findOrFail($request->category_id);
            $categoryPrefix = strtoupper(substr($category->name, 0, 2));
            $lastProduct = Product::where('sku', 'like', $categoryPrefix . '%')
                                ->orderBy('id', 'desc')
                                ->first();
            $lastSkuNumber = $lastProduct ? (int) substr($lastProduct->sku, 2) : 0;
            $newSkuNumber = str_pad($lastSkuNumber + 1, 4, '0', STR_PAD_LEFT);
            $newSku = $categoryPrefix . $newSkuNumber;

            // Calculate total stock including both direct stock and variations
            $totalStock = $request->stocks ?? 0;
            if ($request->has('variations')) {
                $variations = json_decode($request->variations, true);
                if (is_array($variations)) {
                    foreach ($variations as $variation) {
                        foreach ($variation['options'] as $option) {
                            // Add stock from variation options
                            if (isset($option['stock'])) {
                                $totalStock += $option['stock'];
                            }
                            // Add stock from sizes if they exist
                            if (isset($option['sizes']) && is_array($option['sizes'])) {
                                foreach ($option['sizes'] as $size) {
                                    $totalStock += $size['stock'] ?? 0;
                                }
                            }
                        }
                    }
                }
            }

            // Create product
            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'sub_category_id' => $request->sub_category_id,
                'item_id' => $request->item_id,
                'price' => $request->price,
                'stock' => $totalStock,
                'discount_percentage' => $request->discount_percentage,
                'sku' => $newSku,
                'brand_id' => $request->brand_id,
            ]);

            // Handle image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    if ($image->isValid()) {
                        $imagePath = $image->store('product_images', 'public');
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image_path' => $imagePath,
                        ]);
                    } else {
                        throw new \Exception('Invalid image file.');
                    }
                }
            }

            // Handle variations with updated logic
            if ($request->has('variations')) {
                $variations = json_decode($request->variations, true);

                if (!is_array($variations)) {
                    throw new \Exception('The variations field must be an array.');
                }

                foreach ($variations as $variationData) {
                    $variation = ProductVariation::create([
                        'product_id' => $product->id,
                        'name' => $variationData['name']
                    ]);

                    foreach ($variationData['options'] as $optionData) {
                        // Create variation option with stock if no sizes
                        $option = VariationOption::create([
                            'variation_id' => $variation->id,
                            'name' => $optionData['name'],
                            'stock' => isset($optionData['stock']) ? $optionData['stock'] : 0
                        ]);

                        // Handle sizes if they exist
                        if (isset($optionData['sizes']) && is_array($optionData['sizes'])) {
                            foreach ($optionData['sizes'] as $sizeData) {
                                if (!empty($sizeData['name'])) {
                                    VariationOptionSize::create([
                                        'option_id' => $option->id,
                                        'name' => $sizeData['name'],
                                        'stock' => $sizeData['stock'] ?? 0
                                    ]);
                                }
                            }
                        }
                    }
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Product created successfully',
                'product' => $product
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating product: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error creating product: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateProduct(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validate([
                'name' => 'required|string|max:100',
                'description' => 'required|string|max:2000',
                'category_id' => 'required|exists:categories,id',
                'sub_category_id' => 'required|exists:sub_categories,id',
                'item_id' => 'required|exists:items,id',
                'price' => 'required|numeric|min:0',
                'discount_percentage' => 'nullable|integer|min:0|max:100',
                'images' => 'nullable|array',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'variations' => 'nullable|json',
                'brand_id' => 'nullable|exists:brands,id',
                'stocks' => 'nullable|integer',
            ]);

            $user = Auth::user();

            if (!$user->hasRole('admin')) {
                return response()->json([
                    'message' => 'You do not have permission to update this product.'
                ], 403);
            }

            $product = Product::findOrFail($id);

            // Calculate total stock including both direct stock and variations
            $totalStock = $request->stocks ?? 0;
            if ($request->has('variations')) {
                $variations = json_decode($request->variations, true);
                if (is_array($variations)) {
                    foreach ($variations as $variation) {
                        foreach ($variation['options'] as $option) {
                            // Add stock from variation options
                            if (isset($option['stock'])) {
                                $totalStock += $option['stock'];
                            }
                            // Add stock from sizes if they exist
                            if (isset($option['sizes']) && is_array($option['sizes'])) {
                                foreach ($option['sizes'] as $size) {
                                    $totalStock += $size['stock'] ?? 0;
                                }
                            }
                        }
                    }
                }
            }

            // Update product
            $product->update([
                'name' => $request->name,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'sub_category_id' => $request->sub_category_id,
                'item_id' => $request->item_id,
                'price' => $request->price,
                'stock' => $totalStock,
                'discount_percentage' => $request->discount_percentage,
                'brand_id' => $request->brand_id,
            ]);

            // Handle image updates
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    if ($image->isValid()) {
                        $imagePath = $image->store('product_images', 'public');

                        $existingImage = ProductImage::where('product_id', $product->id)
                                                   ->skip($index)
                                                   ->first();

                        if ($existingImage) {
                            Storage::disk('public')->delete($existingImage->image_path);
                            $existingImage->update(['image_path' => $imagePath]);
                        } else {
                            ProductImage::create([
                                'product_id' => $product->id,
                                'image_path' => $imagePath,
                            ]);
                        }
                    } else {
                        throw new \Exception('Invalid image file.');
                    }
                }
            }

            // Handle variations update with updated logic
            if ($request->has('variations')) {
                // Delete existing variations (cascade will handle related records)
                ProductVariation::where('product_id', $product->id)->delete();

                $variations = json_decode($request->variations, true);

                if (!is_array($variations)) {
                    throw new \Exception('The variations field must be an array.');
                }

                foreach ($variations as $variationData) {
                    $variation = ProductVariation::create([
                        'product_id' => $product->id,
                        'name' => $variationData['name']
                    ]);

                    foreach ($variationData['options'] as $optionData) {
                        // Create variation option with stock if no sizes
                        $option = VariationOption::create([
                            'variation_id' => $variation->id,
                            'name' => $optionData['name'],
                            'stock' => isset($optionData['stock']) ? $optionData['stock'] : 0
                        ]);

                        // Handle sizes if they exist
                        if (isset($optionData['sizes']) && is_array($optionData['sizes'])) {
                            foreach ($optionData['sizes'] as $sizeData) {
                                if (!empty($sizeData['name'])) {
                                    VariationOptionSize::create([
                                        'option_id' => $option->id,
                                        'name' => $sizeData['name'],
                                        'stock' => $sizeData['stock'] ?? 0
                                    ]);
                                }
                            }
                        }
                    }
                }
            }

            DB::commit();

            return response()->json(['message' => 'Product updated successfully.']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating product: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error updating product: ' . $e->getMessage()
            ], 500);
        }
    }
    public function search(Request $request)
    {
        $query = $request->get('query');
        $words = explode(' ', $query);
        
        $products = Product::where(function($q) use ($words) {
            foreach ($words as $word) {
                $q->orWhere('name', 'like', '%' . $word . '%');
            }
        })
        ->with(['images' => function($query) {
            $query->select('product_id', 'image_path')->limit(1);
        }])
        ->select('id', 'name')
        ->limit(10)
        ->get();
        
        return response()->json($products);
    }
    public function searchCategories(Request $request)
    {
        $query = $request->input('query');
        $categories = Category::where('name', 'like', "%$query%")->get();
        return response()->json(['categories' => $categories]);
    }

    public function editProduct($id)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            return redirect()->route('home')->with('error', 'You do not have permission to access this page.');
        }

        $product = Product::with(['variations.options.sizes'])->findOrFail($id);
        $categories = Category::all();
        $subcategories = SubCategory::where('category_id', $product->category_id)->get();
        $items = Item::where('sub_category_id', $product->sub_category_id)->get();
        $brands = Brand::where('category_id', $product->category_id)->get();
        $images = ProductImage::where('product_id', $product->id)->get();

        return view('admin.products.editproduct', compact(
            'user',
            'product',
            'categories',
            'subcategories',
            'items',
            'brands',
            'images'
        ));
    }

    public function searchSubCategories(Request $request)
    {
        $categoryId = $request->input('category_id');
        $subcategories = SubCategory::where('category_id', $categoryId)->get();
        return response()->json(['subcategories' => $subcategories]);
    }

    public function searchItems(Request $request)
    {
        $subCategoryId = $request->input('sub_category_id');
        $items = Item::where('sub_category_id', $subCategoryId)->get();
        return response()->json(['items' => $items]);
    }

    public function deleteProduct($id)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            return redirect()->route('home')->with('error', 'You do not have permission to delete this product.');
        }

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($id);

            // Delete associated images from storage
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }

            // Delete the product (cascading deletes will handle related records)
            $product->delete();

            DB::commit();

            return redirect()->route('admin.myproducts')->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting product: ' . $e->getMessage());
            return redirect()->route('admin.myproducts')->with('error', 'Error deleting product.');
        }
    }

    public function addProductStep1()
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            return redirect()->route('users.home')->with('error', 'You do not have permission to access this page.');
        }

        $categories = Category::all();
        return view('admin.products.add-product-step1', compact('user', 'categories'));
    }

    public function addProductStep2(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            return redirect()->route('users.home')->with('error', 'You do not have permission to access this page.');
        }

        $categoryId = $request->input('category_id');
        $subCategoryId = $request->input('sub_category_id');
        $itemId = $request->input('item_id');
        $categoryName = $categoryId ? Category::find($categoryId)->name : '';
        $subCategoryName = $subCategoryId ? SubCategory::find($subCategoryId)->name : '';
        $itemName = $itemId ? Item::find($itemId)->name : '';
        $brands = Brand::where('category_id', $categoryId)->get();

        return view('admin.products.add-product-step2', compact(
            'user',
            'categoryName',
            'subCategoryName',
            'itemName',
            'brands'
        ));
    }

    public function addBrand(Request $request)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validate([
                'name' => 'required|string|max:100|unique:brands,name',
            ]);

            $brand = Brand::create([
                'name' => $request->name,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Brand added successfully',
                'brand' => $brand
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error adding brand: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error adding brand: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteImage($imageId)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            return response()->json([
                'message' => 'You do not have permission to delete this image.'
            ], 403);
        }

        try {
            DB::beginTransaction();

            $image = ProductImage::findOrFail($imageId);
            Storage::disk('public')->delete($image->image_path);
            $image->delete();

            DB::commit();

            return response()->json(['message' => 'Image deleted successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting image: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error deleting image: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeVariationOptions(Request $request)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validate([
                'variation_id' => 'required|exists:product_variations,id',
                'options' => 'required|array',
                'options.*.name' => 'required|string|max:20',
                'options.*.stock' => 'required|integer|min:0'
            ]);

            $variation = ProductVariation::findOrFail($request->variation_id);
            
            // Get the product to update its total stock
            $product = $variation->product;
            $totalStockToAdd = 0;

            foreach ($request->options as $optionData) {
                $option = VariationOption::create([
                    'variation_id' => $variation->id,
                    'name' => $optionData['name'],
                    'stock' => $optionData['stock']
                ]);

                $totalStockToAdd += $optionData['stock'];
            }

            // Update product's total stock
            $product->increment('stock', $totalStockToAdd);

            DB::commit();

            return response()->json([
                'message' => 'Variation options stored successfully',
                'new_total_stock' => $product->stock
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing variation options: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error storing variation options: ' . $e->getMessage()
            ], 500);
        }
    }
}