<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashBoard()
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            return redirect()->route('users.home')->with('error', 'You do not have permission to access this page.');
        }

        return view('admin.dashboard', compact('user'));
    }
    public function getNotificationCounts()
    {
        $newOrdersCount = Order::where('order_status', 'pending')->count();
        $lowStockProductsCount = Product::where('stock', '<=', 10)->count();
        $newUsersCount = User::where('created_at', '>=', now()->subDay())->count();

        return response()->json([
            'newOrdersCount' => $newOrdersCount,
            'lowStockProductsCount' => $lowStockProductsCount,
            'newUsersCount' => $newUsersCount,
        ]);
    }
    
    
}
