<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
    <link href="{{ asset('css/order_confirmation.css') }}" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/s.png') }}" type="image/png">
</head>
<body>
    @include('users.partials.top-nav')
    @include('users.partials.header')

    <div class="confirmation-card">
        <div class="confirmation-content">
            <div class="payment-text">
                <svg class="checkmark" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                You paid ₱{{ number_format($order->total_price, 2) }}
            </div>
            <div class="notification-text">
            We will notify you when your order has been shipped out.
            </div>
            <div class="button-container">
                <button class="button" onclick="window.location.href='/'">Home</button>
                <button class="button" onclick="window.location.href='/users/my-purchases'">My Purchases</button>
            </div>
        </div>
    </div>
</body>
</html>

<script src="{{ asset('js/home.js') }}"></script>

