<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartComposer
{
    public function compose(View $view)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $cartItems = Cart::with('product')->where('user_id', $user->id)->get();
            $view->with('cartItems', $cartItems);
        } else {
            $cartItems = session('cart', []);
            $view->with('cartItems', collect($cartItems)->map(function ($item) {
                $product = \App\Models\Product::with('images')->find($item['product_id']);
                return (object) [
                    'id' => $item['id'],
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'variations' => $item['variations'],
                ];
            }));
        }
    }
}
