<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderHistories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query();

        // Filter by order status
        if ($request->has('order_status') && $request->input('order_status') !== '') {
            $query->where('order_status', $request->input('order_status'));
        }

        // Filter by payment status
        if ($request->has('payment_status') && $request->input('payment_status') !== '') {
            $query->where('payment_status', $request->input('payment_status'));
        }

        // Search by Order ID or Customer Name
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                  ->orWhere('full_name', 'LIKE', "%{$search}%");
            });
        }

        $orders = $query->latest()->paginate(10);
        return view('admin.orders.all-orders', compact('orders'));
    }

    public function view($id)
    {
        $order = Order::findOrFail($id);
        return view('admin.orders.view', compact('order'));
    }

    public function edit($id)
    {
        try {
            $order = Order::findOrFail($id);
            return view('admin.orders.edit', compact('order'));
        } catch (\Exception $e) {
            return redirect()->route('admin.orders.all')
                           ->with('error', 'Order not found.');
        }
    }

    public function update(Request $request, $id)
{
    try {
        DB::beginTransaction();

        $order = Order::findOrFail($id);

        // Validate the request
        $request->validate([
            'order_status' => 'required|in:pending,shipped,delivered',
        ]);

        // Get the old status
        $oldStatus = $order->order_status;

        // Update the order
        $order->update($request->only(['order_status']));

        // Log the change in OrderHistories
        OrderHistories::create([
            'order_id' => $order->id,
            'field_name' => 'order_status',
            'old_value' => $oldStatus,
            'new_value' => $order->order_status,
        ]);

        DB::commit();

        // Handle AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Order updated successfully',
                'order' => $order
            ]);
        }

        return redirect()->route('admin.orders.all')
                       ->with('success', 'Order updated successfully');

    } catch (\Exception $e) {
        DB::rollBack();

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating order: ' . $e->getMessage()
            ], 500);
        }

        return redirect()->back()
                       ->with('error', 'Error updating order: ' . $e->getMessage())
                       ->withInput();
    }
}

    public function orderHistory()
    {
        $orderHistories = OrderHistories::with('order')->get();
        return view('admin.orders.order-history', compact('orderHistories'));
    }
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $order = Order::findOrFail($id);
            $order->delete();

            DB::commit();

            return redirect()->route('admin.orders.all')
                           ->with('success', 'Order deleted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->with('error', 'Error deleting order: ' . $e->getMessage());
        }
    }

    public static function getValidOrderStatuses()
{
    return [
        'pending' => 'Pending',
        'shipped' => 'Shipped',
        'delivered' => 'Delivered',
    ];
}


    public static function getValidPaymentStatuses()
    {
        return [
            'paid' => 'Paid',
            'unpaid' => 'Unpaid',
        ];
    }
}
