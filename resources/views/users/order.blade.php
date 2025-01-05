<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check out</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
    <link href="{{ asset('css/order.css') }}" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/s.png') }}" type="image/png">
</head>
<body>
    @include('users.partials.top-nav')
    @include('users.partials.header')

    <div class="container">
        <!-- Delivery Address Section -->
        <div class="section">
            <div class="delivery-address">
                <span class="location-icon">📍</span>
                <div style="width: 100%;">
                    <h2 class="address-title">Delivery Address</h2>
                    <div class="address-details">
                        @if($defaultAddress)
                            <div>
                                <strong>
                                    {{ $defaultAddress->full_name }}
                                    @php
                                        $phoneNumber = $defaultAddress->phone_number;
                                        $formattedPhoneNumber = '';
                                        for ($i = 0; $i < strlen($phoneNumber); $i++) {
                                            $formattedPhoneNumber .= $phoneNumber[$i];
                                            if (($i + 1) % 5 == 0 && $i != strlen($phoneNumber) - 1) {
                                                $formattedPhoneNumber .= ' ';
                                            }
                                        }
                                    @endphp
                                    {{ $formattedPhoneNumber }}
                                </strong>
                                <p>{{ $defaultAddress->street_address }}, {{ $defaultAddress->barangay }}, {{ $defaultAddress->city }}, {{ $defaultAddress->province }}, {{ $defaultAddress->region }}, {{ $defaultAddress->postal_code }}</p>
                                <span class="default-tag">Default</span>
                            </div>
                        @else
                            <p>No default address set.</p>
                        @endif
                        <a href="#" class="change-link">Change</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Ordered Section -->
        <div class="section">
            <div class="product-header">
                <h2>Products Ordered</h2>
                <div>
                    <span>Unit Price</span>
                    <span style="margin-left: 100px;">Quantity</span>
                    <span style="margin-left: 100px;">Item Subtotal</span>
                </div>
            </div>
            @if($orderItems->isEmpty())
                <div class="empty-cart">
                    <p>No items selected for checkout.</p>
                </div>
            @else
                @foreach($orderItems as $item)
                <div class="product-item">
                    <img src="{{ $item->product->images->isNotEmpty() ? asset('storage/' . $item->product->images->first()->image_path) : asset('images/placeholder.jpg') }}" alt="{{ $item->product->name }}" class="product-image">
                    <div class="product-details">
                        <p class="product-name">{{ Str::limit($item->product->name, 40, '...') }}</p>
                        <p class="variation-text">Variations:
                            @php
                                $variations = json_decode($item->variations, true);
                                $variationTexts = [];
                                foreach ($variations as $value) {
                                    $variationTexts[] = $value;
                                }
                            @endphp
                            {{ implode(', ', $variationTexts) }}
                        </p>
                    </div>
                    <span class="product-price">₱{{ number_format($item->product->price, 2) }}</span>
                    <span class="product-quantity">{{ $item->quantity }}</span>
                    <span class="product-subtotal">₱{{ number_format($item->product->price * $item->quantity, 2) }}</span>
                </div>
                @endforeach
            @endif
        </div>

        <!-- Message and Shipping Section -->
        <div class="section shipping-section">
            <div>
                <p>Message for Sellers:</p>
                <input type="text" placeholder="Please leave a message..." class="message-input">
            </div>
            <div>
                <div style="display: flex; justify-content : space-between; align-items: center;">
                    <p>Shipping Option:</p>
                    <a href="#" class="change-link">Change</a>
                </div>
                <p><strong>Overseas Shipping</strong></p>
                <p>Standard International</p>
                <p>Guaranteed to get by 25 Nov - 2 Dec</p>
                <p style="color: #666;">Get a ₱50 voucher if no delivery was attempted by 2 Dec 2024</p>
            </div>
        </div>

        <!-- Payment Method Section -->
        <div class="section payment-method">
            <h2>Payment Method</h2>
            <div class="payment-method-content">
                <div class="order-summary">
                    @if(!$orderItems->isEmpty())
                        <div class="summary-row">
                            <span>Merchandise Subtotal:</span>
                            <span>₱{{ number_format($orderItems->sum(function ($item) { return $item->product->price * $item->quantity; }), 2) }}</span>
                        </div>
                        <div class="summary-row">
                            <span>Shipping Subtotal:</span>
                            <span>₱60</span>
                        </div>
                        <div class="summary-row">
                            <span>Shipping Discount Subtotal:</span>
                            <span>-₱4</span>
                        </div>
                        <div class="summary-row">
                            <span>Voucher Discount:</span>
                            <span>-₱15</span>
                        </div>
                        <div class="summary-row" style="margin-top: 10px;">
                            <span>Total Payment:</span>
                            <span class="total-payment">₱{{ number_format($orderItems->sum(function ($item) { return $item->product->price * $item->quantity; }) + 60 - 4 - 15, 2) }}</span>
                        </div>
                    @endif
                </div>
                <div class="paypal-button-wrapper">
                    <div id="paypal-button-container" class="paypal-button-container"></div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/home.js') }}"></script>
    <script src="https://www.paypal.com/sdk/js?client-id={{env('PAYPAL_SANDBOX_CLIENT_ID')}}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('PayPal script loaded');

            // Calculate the total amount as a float
            const totalAmount = {{ $orderItems->sum(function ($item) { return $item->product->price * $item->quantity; }) + 60 - 4 - 15 }};

            paypal.Buttons({
                createOrder: function(data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: totalAmount.toFixed(2) // Ensure it's a string with two decimal places
                            }
                        }]
                    });
                },
                onApprove: function(data, actions) {
                    return actions.order.capture().then(function(details) {
                        console.log('Transaction completed by', details.payer.name.given_name);
                        fetch('/save-transaction', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                orderID: data.orderID,
                                payerID: details.payer.payer_id,
                                paymentID: details.id,
                                paymentToken: details.payment_token,
                                payerEmail: details.payer.email_address,
                                payment_method: 'paypal' // Add payment_method
                            })
                        }).then(response => response.json()).then(data => {
                            console.log('Order saved:', data);
                            if (data.order_id) {
                                window.location.href = '/users/order-confirmation/' + data.order_id;
                            } else {
                                console.error('Order ID not found in response');
                            }
                        }).catch(error => {
                            console.error('Error saving order:', error);
                        });
                    }).catch(error => {
                        console.error('Error capturing order:', error);
                    });
                },
                onError: function(err) {
                    console.error('PayPal error:', err);
                }
            }).render('#paypal-button-container');
        });
    </script>
</body>
</html>
