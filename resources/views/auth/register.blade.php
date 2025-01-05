<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - User Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
    <link rel="icon" href="{{ asset('images/s.png') }}" type="image/png">
</head>
<body>
    <!-- Decorative elements -->
    <i class='bx bx-star decoration star-1'></i>
    <i class='bx bx-star decoration star-2'></i>
    <i class='bx bx-star decoration star-3'></i>
    <i class='bx bx-gift decoration gift-1'></i>
    <i class='bx bx-gift decoration gift-2'></i>

    <div class="signup-container">
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

        <!-- Sign Up Form -->
        <div class="signup-form">
            <h2 class="form-title">Sign Up</h2>
            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="form-group">
                    <input type="text" name="last_name" placeholder="Last Name" required>
                    @error('last_name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <input type="text" name="first_name" placeholder="First Name" required>
                    @error('first_name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email" required>
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Password" required>
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
                </div>
                <button type="submit" class="form-submit">Sign Up</button>
            </form>

            <div class="login-link">
                Have an account? <a href="{{ route('login') }}">Log In</a>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="modal">
        <div class="modal-content">
            <i class='bx bx-check-circle'></i>
            <h2>Registration Successful!</h2>
            <p>Please check your email to verify your account.</p>
            <button id="closeModal">Continue</button>
        </div>
    </div>

    <script>
        // Show modal if success message exists
        @if(session('success'))
            document.getElementById('successModal').style.display = 'flex';
        @endif

        // Close modal when clicking the close button
        document.getElementById('closeModal').addEventListener('click', function() {
            document.getElementById('successModal').style.display = 'none';
            window.location.href = "{{ route('verification.notice') }}";
        });
    </script>
</body>
</html>
