<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - User Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/s.png') }}" type="image/png">
</head>
<body>
    <!-- Decorative elements -->
    <i class='bx bx-star decoration star-1'></i>
    <i class='bx bx-star decoration star-2'></i>
    <i class='bx bx-star decoration star-3'></i>
    <i class='bx bx-gift decoration gift-1'></i>
    <i class='bx bx-gift decoration gift-2'></i>

    <div class="login-container">
        <!-- Promotional Content -->
        <div class="promo-content">
            <div class="logo">
                <i class='bx bx-shopping-bag' style="font-size: 48px;"></i>
            </div>
            <h1 class="mega-sale">11.11 - 12.12<br>MEGA PAMASKO SALE</h1>
            <div class="promo-badges">
                <span class="promo-badge">FREE SHIPPING MIN. SPEND</span>
                <span class="promo-badge">ON-TIME PAMASKO DELIVERY</span>
                <span class="promo-badge">70% OFF HOLIDAY DEALS</span>
            </div>
        </div>

        <!-- Login Form -->
        <div class="login-form">
            <div class="form-header">
                <h2 class="form-title">Log In</h2>
                <button class="qr-button">
                    <i class='bx bx-qr'></i>
                    Log in with QR
                </button>
            </div>

            @if ($errors->has('email'))
                <div class="alert alert-danger">
                    {{ $errors->first('email') }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="form-submit">LOG IN</button>
            </form>

            <div class="form-links">
                <a href="{{ route('password.request') }}" class="form-link">Forgot Password</a>
                <a href="#" class="form-link">Log In with Phone Number</a>
            </div>

            <div class="divider">OR</div>

            <div class="social-buttons">
                <a href="{{ route('auth.redirection', 'facebook') }}" class="social-button">
                    <i class='bx bxl-facebook' style="color: #1877f2;"></i>
                    Facebook
                </a>
                <a href="{{ route('auth.google') }}" class="social-button">
                    <i class='bx bxl-google' style="color: #ea4335;"></i>
                    Google
                </a>
            </div>

            <div class="signup-link">
                New to Shopee? <a href="{{ route('register') }}">Sign Up</a>
            </div>

            @if (session('email_verification_required'))
                <div class="verify-link">
                    <a href="{{ route('verification.notice') }}" class="form-link">Verify Email</a>
                    <a href="{{ route('login') }}" class="form-link">Later</a>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
