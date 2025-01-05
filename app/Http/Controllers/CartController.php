<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Address;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    // Show Cart Items
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $cartItems = Cart::with(['product', 'product.images', 'variationOption', 'variationOptionSize'])->where('user_id', $user->id)->get()->map(function ($item) {
                $item->product->name = Str::limit($item->product->name, 50); // Limit to 50 characters
                return $item;
            });
        } else {
            $cartItems = $this->getSessionCartItems()->map(function ($item) {
                $item->product->name = Str::limit($item->product->name, 50); // Limit to 50 characters
                return $item;
            });
        }

        // Debugging statement
        Log::info('Cart Items:', ['cartItems' => $cartItems]);

        return view('users.cart', compact('cartItems'));
    }

    // Add Item to Cart
    public function addToCart(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'variations' => 'nullable|json',
            'variation_option_id' => 'nullable|exists:variation_options,id',
            'variation_option_size_id' => 'nullable|exists:variation_option_sizes,id',
        ]);

        $user = Auth::user();
        $product = Product::findOrFail($productId);
        $variations = $request->input('variations', '{}');
        $variationOptionId = $request->input('variation_option_id');
        $variationOptionSizeId = $request->input('variation_option_size_id');

        if ($user) {
            $cartItem = Cart::where('user_id', $user->id)
                            ->where('product_id', $product->id)
                            ->where('variation_option_id', $variationOptionId)
                            ->where('variation_option_size_id', $variationOptionSizeId)
                            ->first();

            if ($cartItem) {
                $cartItem->quantity += $request->quantity;
                $cartItem->save();
            } else {
                Cart::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'quantity' => $request->quantity,
                    'variations' => $variations,
                    'variation_option_id' => $variationOptionId,
                    'variation_option_size_id' => $variationOptionSizeId,
                ]);
            }
        } else {
            $this->addToSessionCart($productId, $request->quantity, $variations, $variationOptionId, $variationOptionSizeId);
        }

        return redirect()->route('users.cart')->with('success', 'Product added to cart successfully');
    }

    // Update Item Quantity in Cart
    public function updateCartItem(Request $request, $cartItemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        if ($user) {
            $cartItem = Cart::findOrFail($cartItemId);
            $cartItem->quantity = $request->quantity;
            $cartItem->save();
        } else {
            $this->updateSessionCartItem($cartItemId, $request->quantity);
        }

        return response()->json(['success' => true]);
    }

    // Remove Item from Cart
    public function removeFromCart(Request $request, $cartItemId)
    {
        $user = Auth::user();
        if ($user) {
            $cartItem = Cart::findOrFail($cartItemId);
            $cartItem->delete();
        } else {
            $this->removeFromSessionCart($cartItemId);
        }

        return back()->with('success', 'Product removed from cart successfully');
    }

    public function checkout(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'You need to log in to proceed to checkout');
        }

        $itemIds = explode(',', $request->query('items'));
        $orderItems = Cart::with(['product', 'product.images', 'variationOption', 'variationOptionSize'])->where('user_id', $user->id)->whereIn('id', $itemIds)->get()->map(function ($item) {
            $item->product->name = Str::limit($item->product->name, 40); // Limit to 40 characters
            return $item;
        });

        // Fetch the default address
        $defaultAddress = $user->addresses()->where('is_default', 1)->first();

        // Store selected items in session
        session(['selected_items' => $itemIds]);

        return view('users.order', compact('orderItems', 'defaultAddress', 'user'));
    }

    // Set Default Address
    public function setDefaultAddress(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
        ]);

        $user = Auth::user();
        $address = $user->addresses()->findOrFail($request->address_id);

        // Set all addresses to not default
        $user->addresses()->update(['is_default' => false]);

        // Set the selected address as default
        $address->is_default = true;
        $address->save();

        return response()->json(['success' => 'Default address updated successfully']);
    }

    // Helper methods for session-based cart
    private function getSessionCartItems($itemIds = null)
    {
        $cartItems = session('cart', []);
        if ($itemIds) {
            $cartItems = array_filter($cartItems, function ($item) use ($itemIds) {
                return in_array($item['id'], $itemIds);
            });
        }
        return collect($cartItems)->map(function ($item) {
            $product = Product::with('images')->find($item['product_id']);
            $product->name = Str::limit($product->name, 40); // Limit to 40 characters
            return (object) [
                'id' => $item['id'],
                'product' => $product,
                'quantity' => $item['quantity'],
                'variations' => $item['variations'],
                'variation_option_id' => $item['variation_option_id'],
                'variation_option_size_id' => $item['variation_option_size_id'],
            ];
        });
    }

    private function addToSessionCart($productId, $quantity, $variations, $variationOptionId, $variationOptionSizeId)
    {
        $cartItems = session('cart', []);
        $product = Product::findOrFail($productId);
        $cartItemId = Str::uuid()->toString();

        $existingItem = collect($cartItems)->firstWhere(function ($item) use ($productId, $variations, $variationOptionId, $variationOptionSizeId) {
            return $item['product_id'] == $productId &&
                   $item['variations'] == $variations &&
                   $item['variation_option_id'] == $variationOptionId &&
                   $item['variation_option_size_id'] == $variationOptionSizeId;
        });

        if ($existingItem) {
            $existingItem['quantity'] += $quantity;
        } else {
            $cartItems[] = [
                'id' => $cartItemId,
                'product_id' => $productId,
                'quantity' => $quantity,
                'variations' => $variations,
                'variation_option_id' => $variationOptionId,
                'variation_option_size_id' => $variationOptionSizeId,
            ];
        }

        session(['cart' => $cartItems]);
    }

    private function updateSessionCartItem($cartItemId, $quantity)
    {
        $cartItems = session('cart', []);
        $cartItems = array_map(function ($item) use ($cartItemId, $quantity) {
            if ($item['id'] == $cartItemId) {
                $item['quantity'] = $quantity;
            }
            return $item;
        });
        session(['cart' => $cartItems]);
    }

    private function removeFromSessionCart($cartItemId)
    {
        $cartItems = session('cart', []);
        $cartItems = array_filter($cartItems, function ($item) use ($cartItemId) {
            return $item['id'] != $cartItemId;
        });
        session(['cart' => $cartItems]);
    }
}
