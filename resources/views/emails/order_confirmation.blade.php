<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
</head>
<body>
    <h1>Order Confirmation</h1>
    <p>Thank you for your order!</p>
    <p>Your order details are as follows:</p>
    <ul>
        <li><strong>Order ID:</strong> {{ $order->id }}</li>
        <li><strong>Full Name:</strong> {{ $order->full_name }}</li>
        <li><strong>Phone Number:</strong> {{ $order->phone_number }}</li>
        <li><strong>Address:</strong> {{ $order->street_address }}, {{ $order->barangay }}, {{ $order->city }}, {{ $order->province }}, {{ $order->region }}, {{ $order->postal_code }}</li>
        <li><strong>Total Price:</strong> {{ $order->total_price }}</li>
    </ul>
    <h2>Products:</h2>
    <ul>
        @foreach ($order->products as $product)
            <li>
                <strong>{{ $product['product_name'] }}</strong> - Quantity: {{ $product['quantity'] }}
                @if (!empty($product['variations']))
                    <br>Variations: {{ implode(', ', $product['variations']) }}
                @endif
            </li>
        @endforeach
    </ul>
</body>
</html>
