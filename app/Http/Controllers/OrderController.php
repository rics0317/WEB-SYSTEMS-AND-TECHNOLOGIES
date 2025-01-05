<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Address;
use App\Models\Notification;
use App\Models\VariationOptionSize;
use App\Models\VariationOption;
use App\Models\Product;

class OrderController extends Controller
{
    public function saveTransaction(Request $request)
    {
        $user = Auth::user();
        $defaultAddress = $user->addresses()->where('is_default', 1)->first();

        if (!$defaultAddress) {
            return response()->json(['error' => 'No default address set'], 400);
        }

        $itemIds = session('selected_items', []);
        $cartItems = Cart::with(['product', 'product.images', 'variationOption', 'variationOptionSize'])->where('user_id', $user->id)->whereIn('id', $itemIds)->get();

        $products = $cartItems->map(function ($item) {
            return [
                'product_name' => $item->product->name,
                'image' => $item->product->images->isNotEmpty() ? asset('storage/' . $item->product->images->first()->image_path) : asset('images/placeholder.jpg'),
                'quantity' => $item->quantity,
                'variations' => $item->variations,
            ];
        });

        $totalPrice = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        // Add shipping and discounts to the total price
        $totalPrice += 60 - 4 - 15;

        $order = Order::create([
            'user_id' => $user->id,
            'full_name' => $defaultAddress->full_name,
            'phone_number' => $defaultAddress->phone_number,
            'region' => $defaultAddress->region,
            'province' => $defaultAddress->province,
            'city' => $defaultAddress->city,
            'barangay' => $defaultAddress->barangay,
            'postal_code' => $defaultAddress->postal_code,
            'street_address' => $defaultAddress->street_address,
            'label' => $defaultAddress->label,
            'total_price' => $totalPrice,
            'products' => $products,
            'payment_id' => $request->input('paymentID'),
            'payment_status' => 'paid',
            'order_status' => 'pending',
            'payment_method' => $request->input('payment_method'), // Add payment_method
        ]);

        // Decrement the stock of the selected products
        foreach ($cartItems as $item) {
            $variations = json_decode($item->variations, true);
            $variationOptionId = $item->variation_option_id;
            $variationOptionSizeId = $item->variation_option_size_id;

            if ($variationOptionSizeId) {
                // If the variation has a size, decrement the stock in VariationOptionSize
                $variationOptionSize = VariationOptionSize::find($variationOptionSizeId);
                if ($variationOptionSize) {
                    $variationOptionSize->stock -= $item->quantity;
                    $variationOptionSize->save();
                }
            } else {
                // If the variation does not have a size, decrement the stock in VariationOption
                $variationOption = VariationOption::find($variationOptionId);
                if ($variationOption) {
                    $variationOption->stock -= $item->quantity;
                    $variationOption->save();
                }
            }

            // Decrement the stock in the Product table
            $product = $item->product;
            $product->stock -= $item->quantity;
            $product->save();
        }

        // Clear the selected items from the cart after successful order
        Cart::where('user_id', $user->id)->whereIn('id', $itemIds)->delete();

        // Clear the selected items from the session
        session()->forget('selected_items');

        // Create a notification for the user
        Notification::create([
            'user_id' => $user->id,
            'type' => 'order_created',
            'message' => 'Your order has been successfully placed.',
            'read' => false,
        ]);

        return response()->json(['order_id' => $order->id]);
    }

    public function orderConfirmation($orderId)
    {
        $order = Order::findOrFail($orderId);
        return view('users.order_confirmation', compact('order'));
    }
}
