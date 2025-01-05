@extends('layouts.seller')

@section('content')
<div class="back-button-container">
    <a href="{{ route('admin.orders.all') }}" class="back-button">Back to Orders</a>
</div>

<div class="order-view">
    <header class="order-header">
        <h1>Order #{{ $order->id }}</h1>
        <div class="order-status-group">
            <div class="status-item">
                <small class="status-text">Order Status</small>
                <span class="status-badge order-status">{{ ucfirst($order->order_status) }}</span>
            </div>
            <div class="status-item">
                <small class="status-text">Payment Status</small>
                <span class="status-badge payment-status">{{ ucfirst($order->payment_status) }}</span>
            </div>
        </div>
    </header>

    <div class="order-summary">
        <div class="summary-item">
            <span class="summary-label">Order Date</span>
            <span class="summary-value">{{ $order->created_at->format('M d, Y H:i') }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Total Amount</span>
            <span class="summary-value">₱{{ number_format($order->total_price, 2) }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Payment ID</span>
            <span class="summary-value">{{ $order->payment_id ?? 'N/A' }}</span>
        </div>
    </div>

    <div class="order-details-grid">
        <section class="order-section customer-info">
            <h2>Customer Information</h2>
            <div class="info-content">
                <p><strong>Name:</strong> {{ $order->full_name }}</p>
                <p><strong>Phone:</strong> {{ $order->phone_number }}</p>
                <p><strong>User ID:</strong> {{ $order->user_id }}</p>
            </div>
            <div class="view-button-container">
                <a href="{{ route('admin.manage.view-user', $order->user_id) }}" class="view-button">View</a>
            </div>
        </section>

        <section class="order-section shipping-info">
            <h2>Shipping Information</h2>
            <div class="info-content">
                <p><strong>Address:</strong> {{ $order->street_address }}</p>
                <p><strong>Barangay:</strong> {{ $order->barangay }}</p>
                <p><strong>City:</strong> {{ $order->city }}</p>
                <p><strong>Province:</strong> {{ $order->province }}</p>
                <p><strong>Region:</strong> {{ $order->region }}</p>
                <p><strong>Postal Code:</strong> {{ $order->postal_code }}</p>
                <p><strong>Label:</strong> {{ $order->label }}</p>
            </div>
        </section>

        <section class="order-section payment-info">
            <h2>Payment Details</h2>
            <div class="info-content">
                <p><strong>Status:</strong> {{ ucfirst($order->payment_status) }}</p>
                <p><strong>Payment Method:</strong> {{ $order->payment_method ?? 'N/A' }}</p>
                <p><strong>Payment ID:</strong> {{ $order->payment_id ?? 'N/A' }}</p>
            </div>
        </section>
    </div>

    <section class="order-items">
        <h2>Order Items</h2>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Variations</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->products as $product)
                <tr>
                    <td>
                        <div class="product-info">
                            <img src="{{ $product['image'] }}" alt="{{ $product['product_name'] }}" class="product-image">
                            <span>{{ $product['product_name'] }}</span>
                        </div>
                    </td>
                    <td>{{ $product['quantity'] }}</td>
                    <td>
                        @if (!empty($product['variations']))
                            @php
                                $variations = is_string($product['variations']) ? json_decode($product['variations'], true) : $product['variations'];
                            @endphp
                            @if (is_array($variations))
                                {{ implode(', ', $variations) }}
                            @else
                                {{ $variations }}
                            @endif
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                    ₱{{ number_format($order->total_price, 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </section>
</div>

<style>
    .back-button-container {
        text-align: right;
        margin-bottom: 1rem;
    }
    .order-view {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        color: #222222;
        background-color: #f5f5f5;
    }
    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding: 1.5rem;
        background-color: #ffffff;
        border-radius: 0px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }
    .order-header h1 {
        font-size: 1.25rem;
        font-weight: 500;
        color: #ee4d2d;
    }
    .order-status-group {
        display: flex;
        flex-direction: row;
        align-items: flex-start;
        gap: 1rem;
    }
    .status-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.25rem;
    }
    .status-text {
        font-size: 0.75rem;
        color: #757575;
    }
    .status-badge {
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 500;
    }
    .order-status {
        background-color:rgb(248, 60, 22);
    }
    .payment-status {
        background-color:rgb(20, 190, 91);
    }
    .order-summary {
        display: flex;
        justify-content: space-between;
        background-color: #ffffff;
        padding: 1.5rem;
        border-radius: 0px;
        margin-bottom: 1rem;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }
    .summary-item {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    .summary-label {
        font-size: 0.875rem;
        color: #757575;
    }
    .summary-value {
        font-size: 1rem;
        font-weight: 500;
        color: #222222;
    }
    .order-details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .order-section {
        background-color: #ffffff;
        border-radius: 0px;
        padding: 1.5rem;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        position: relative;
    }
    .order-section h2 {
        font-size: 1rem;
        margin-bottom: 1rem;
        color: #222222;
        font-weight: 500;
    }
    .info-content p {
        margin: 0.5rem 0;
        font-size: 0.875rem;
        color: #222222;
    }
    .info-content p strong {
        color: #757575;
        font-weight: normal;
        margin-right: 0.5rem;
    }
    .items-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: #ffffff;
        border-radius: 0px;
        overflow: hidden;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }
    .items-table th,
    .items-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #efefef;
    }
    .items-table th {
        background-color: #fafafa;
        font-weight: 500;
        font-size: 0.875rem;
        color: #757575;
    }
    .product-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .product-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border: 1px solid #efefef;
        border-radius: 0px;
    }
    .product-info span {
        font-size: 0.875rem;
        color: #222222;
    }
    .items-table td {
        font-size: 0.875rem;
        color: #222222;
    }
    .back-button {
        background-color: #ee4d2d;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
    }
    .view-button-container {
        display: flex;
        justify-content: center;
        margin-top: 1rem;
    }
    .view-button {
        background-color: #ee4d2d;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
    }
    @media (max-width: 768px) {
        .order-view {
            padding: 1rem;
        }
        .order-summary {
            flex-direction: column;
            gap: 1rem;
        }
        .order-details-grid {
            grid-template-columns: 1fr;
        }
        .items-table {
            font-size: 0.813rem;
        }
        .product-image {
            width: 60px;
            height: 60px;
        }
        .order-header {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }
        .order-status-group {
            flex-direction: column;
            align-items: flex-start;
        }
        .status-item {
            align-items: flex-start;
        }
    }
</style>
@endsection

