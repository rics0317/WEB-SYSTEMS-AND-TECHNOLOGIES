<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Stock;
use App\Models\VariationOption;
use App\Models\VariationOptionSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%");
        }

        $products = $query->get();
        $stockHistory = Stock::with('product')->orderBy('created_at', 'desc')->get();
        $user = Auth::user(); // Fetch the authenticated user

        return view('admin.inventory.add-new-stocks', compact('products', 'stockHistory', 'user'));
    }

    public function store(Request $request)
    {
        Log::info('Stock addition request:', $request->all());

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'variation_option_id' => 'nullable|exists:variation_options,id',
            'variation_option_size_id' => 'nullable|exists:variation_option_sizes,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $product = Product::find($request->input('product_id'));
        $quantity = $request->input('quantity');

        DB::transaction(function () use ($product, $request, $quantity) {
            if ($request->filled('variation_option_size_id')) {
                $variationOptionSize = VariationOptionSize::find($request->input('variation_option_size_id'));
                if ($variationOptionSize->stock + $quantity > 100000) {
                    return redirect()->back()->with('error', 'Stock limit exceeded.');
                }
                Log::info('Updating variation option size stock:', ['id' => $variationOptionSize->id, 'quantity' => $quantity]);
                $oldStock = $variationOptionSize->stock;
                $variationOptionSize->stock += $quantity;
                $variationOptionSize->save();

                // Update the product stock as well
                $product->stock += $quantity;
                $product->save();

                Stock::create([
                    'product_id' => $product->id,
                    'variation_option_id' => null,
                    'variation_option_size_id' => $variationOptionSize->id,
                    'quantity' => $quantity,
                    'type' => 'add',
                    'old_stock' => $oldStock,
                ]);
            } elseif ($request->filled('variation_option_id')) {
                $variationOption = VariationOption::find($request->input('variation_option_id'));
                if ($variationOption->stock + $quantity > 100000) {
                    return redirect()->back()->with('error', 'Stock limit exceeded.');
                }
                Log::info('Updating variation option stock:', ['id' => $variationOption->id, 'quantity' => $quantity]);
                $oldStock = $variationOption->stock;
                $variationOption->stock += $quantity;
                $variationOption->save();

                // Update the product stock as well
                $product->stock += $quantity;
                $product->save();

                Stock::create([
                    'product_id' => $product->id,
                    'variation_option_id' => $variationOption->id,
                    'variation_option_size_id' => null,
                    'quantity' => $quantity,
                    'type' => 'add',
                    'old_stock' => $oldStock,
                ]);
            } else {
                if ($product->stock + $quantity > 100000) {
                    return redirect()->back()->with('error', 'Stock limit exceeded.');
                }
                Log::info('Updating product stock:', ['id' => $product->id, 'quantity' => $quantity]);
                $oldStock = $product->stock;
                $product->stock += $quantity;
                $product->save();

                Stock::create([
                    'product_id' => $product->id,
                    'variation_option_id' => null,
                    'variation_option_size_id' => null,
                    'quantity' => $quantity,
                    'type' => 'add',
                    'old_stock' => $oldStock,
                ]);
            }
        });

        return redirect()->back()->with('success', 'Stock added successfully.');
    }

    public function getVariationOptions($productId)
    {
        $product = Product::find($productId);
        $variations = $product->variations()->with('options.sizes')->get();
        return response()->json($variations);
    }

    public function getVariationOptionStock($variationOptionId)
    {
        $variationOption = VariationOption::find($variationOptionId);
        if ($variationOption) {
            return response()->json(['stock' => $variationOption->stock]);
        }
        return response()->json(['stock' => 0]);
    }

    public function getVariationOptionSizeStock($variationOptionSizeId)
    {
        $variationOptionSize = VariationOptionSize::find($variationOptionSizeId);
        if ($variationOptionSize) {
            return response()->json(['stock' => $variationOptionSize->stock]);
        }
        return response()->json(['stock' => 0]);
    }

    public function getVariationOptionSizes($variationOptionId)
    {
        $variationOption = VariationOption::find($variationOptionId);
        if ($variationOption) {
            return response()->json($variationOption->sizes);
        }
        return response()->json([]);
    }
}
