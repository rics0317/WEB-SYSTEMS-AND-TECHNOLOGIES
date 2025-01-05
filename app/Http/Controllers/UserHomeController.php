<?php

// app/Http/Controllers/UserHomeController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Address;
use App\Models\Notification;
use App\Models\Brand;
use App\Models\SubCategory;
use App\Models\Order;
use App\Models\Rating;

class UserHomeController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $products = Product::with(['category', 'images'])->get();
        return view('users.home', compact('categories', 'products'));
    }

    public function showProduct($id)
    {
        $product = Product::with(['category', 'subCategory', 'item', 'images', 'variations', 'brand'])->findOrFail($id);
        $relatedProducts = Product::where('category_id', $product->category_id)
                                   ->where('id', '<>', $product->id)
                                   ->with(['category', 'images'])
                                   ->get();
        return view('users.product-details', compact('product', 'relatedProducts'));
    }

    public function showCategory(Request $request, $id)
    {
        $category = Category::with(['products.images', 'subCategories'])->findOrFail($id);
        $query = $category->products();

        // Filter by subcategory if provided
        if ($request->has('subcategory')) {
            $query->where('sub_category_id', $request->subcategory);
        }

        // Apply sorting
        switch ($request->get('sort')) {
            case 'latest':
                $query->latest();
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'top_sales':
                $query->orderBy('sales_count', 'desc');
                break;
            default:
                $query->latest(); // Default sorting by latest
        }

        $products = $query->get();
        $brands = Brand::where('category_id', $id)->get();

        return view('users.category', compact('category', 'brands', 'products'));
    }

    public function getProductDetails($id)
    {
        $product = Product::with(['category', 'images', 'brand'])->findOrFail($id);
        return response()->json($product);
    }

    public function profile()
    {
        return view('users.profile');
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'profile_image' => 'nullable|image|mimes:jpeg,png|max:1024',
            'birthdate' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female,Other',
            'contact' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile-images', 'public');
            $user->profile_image = Storage::url($path);
        }

        if ($request->filled('first_name')) {
            $user->first_name = $request->first_name;
        }

        if ($request->filled('last_name')) {
            $user->last_name = $request->last_name;
        }

        if ($request->filled('email')) {
            $user->email = $request->email;
        }

        if ($request->filled('birthdate')) {
            $user->birthdate = $request->birthdate;
        }

        if ($request->filled('gender')) {
            $user->gender = $request->gender;
        }

        if ($request->filled('contact')) {
            $user->contact = $request->contact;
        }

        $user->save();

        Notification::create([
            'user_id' => $user->id,
            'message' => 'Your profile has been updated successfully.',
            'read' => false,
        ]);

        return back()->with('success', 'Profile updated successfully');
    }

    public function changePassword()
    {
        return view('users.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The provided current password is incorrect.'])->withInput();
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        Notification::create([
            'user_id' => $user->id,
            'message' => 'Your password has been changed successfully.',
            'read' => false,
        ]);

        return back()->with('success', 'Password updated successfully');
    }

    public function editProfile()
    {
        $user = Auth::user();
        $addresses = $user->addresses()->orderBy('is_default', 'desc')->get();
        return view('users.profile.edit-profile', compact('user', 'addresses'));
    }

    public function editPhone()
    {
        return view('users.profile.edit-phone');
    }

    public function updatePhone(Request $request)
    {
        $request->validate([
            'contact' => 'required|string|max:20',
        ]);

        $user = Auth::user();
        $user->contact = $request->contact;
        $user->save();

        return redirect()->route('profile')->with('success', 'Phone number updated successfully');
    }

    public function createAddress(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'region' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'street_address' => 'required|string|max:255',
            'label' => 'required|string|in:Home,Work',
        ]);

        $address = new Address([
            'user_id' => Auth::id(),
            'full_name' => $request->full_name,
            'phone_number' => $request->phone_number,
            'region' => $request->region,
            'province' => $request->province,
            'city' => $request->city,
            'barangay' => $request->barangay,
            'postal_code' => $request->postal_code,
            'street_address' => $request->street_address,
            'label' => $request->label,
            'is_default' => Address::where('user_id', Auth::id())->count() === 0,
        ]);

        $address->save();

        return redirect()->route('profile.edit')->with('success', 'Address added successfully');
    }

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

    public function deleteAddress(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
        ]);

        $address = Address::findOrFail($request->address_id);
        $address->delete();

        return response()->json(['success' => 'Address deleted successfully']);
    }

    public function getAddress(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
        ]);

        $address = Address::findOrFail($request->address_id);

        return response()->json(['address' => $address]);
    }

    public function updateAddress(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'region' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'street_address' => 'required|string|max:255',
            'label' => 'required|string|in:Home,Work',
        ]);

        $address = Address::findOrFail($request->address_id);
        $address->full_name = $request->full_name;
        $address->phone_number = $request->phone_number;
        $address->region = $request->region;
        $address->province = $request->province;
        $address->city = $request->city;
        $address->barangay = $request->barangay;
        $address->postal_code = $request->postal_code;
        $address->street_address = $request->street_address;
        $address->label = $request->label;
        $address->save();

        return redirect()->route('profile.edit')->with('success', 'Address updated successfully');
    }

    public function myPurchases(Request $request)
    {
        $user = Auth::user();
        $query = Order::where('user_id', $user->id);

        // Filter by status if provided
        if ($request->status && $request->status !== 'all') {
            $query->where('order_status', strtoupper($request->status));
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        // Get ratings for the orders
        $ratings = Rating::whereIn('order_id', $orders->pluck('id'))->get()->keyBy('order_id');

        return view('users.profile.my-purchases', compact('orders', 'ratings'));
    }

    public function rateOrder(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        Rating::create([
            'order_id' => $request->order_id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Thank you for your rating!');
    }

    public function buyAgain($orderId)
    {
        $order = Order::findOrFail($orderId);
        $productId = $order->products[0]['product_id']; // Assuming the first product in the order

        return redirect()->route('product.details', ['id' => $productId]);
    }

    public function addToCart(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $variations = json_decode($request->variations, true);
        $quantity = $request->quantity;

        // Check if the product has sizes
        $hasSizes = $product->variations->flatMap->options->flatMap->sizes->isNotEmpty();

        // If the product has sizes, ensure a size is selected
        if ($hasSizes && (!isset($variations['size']) || empty($variations['size']))) {
            return back()->with('error', 'Please select a size before adding to cart.');
        }

        // Add the item to the cart
        $cart = session()->get('cart', []);
        if(isset($cart[$productId])){
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'product_id' => $productId,
                'name' => $product->name,
                'quantity' => $quantity,
                'price' => $product->price,
                'variations' => $variations,
            ];
        }
        session()->put('cart', $cart);

        return back()->with('success', 'Item added to cart successfully.');
    }
}
