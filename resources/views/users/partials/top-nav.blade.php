<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Page Title</title>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" href="{{ asset('images/s.png') }}" type="image/png">
</head>
<body>
    <nav class="top-nav">
        <div class="top-nav-left">
            <a href="#">Seller Centre</a>
            @auth
                @php
                    $userId = Auth::id();
                    $user = \App\Models\User::find($userId);
                @endphp
                @if(auth()->user() && auth()->user()->role->role_name === 'admin')
    <a href="{{ route('admin.dashboard') }}">Start Selling</a>
@endif

            @else
                <a href="#" id="start-selling-link">Start Selling</a>
            @endauth
            <a href="https://www.facebook.com/ShopeePH">Follow us on <i class='bx bxl-facebook'></i></a>
            <a href="https://www.instagram.com/shopee_ph/">Follow us on <i class='bx bxl-instagram'></i></a>
        </div>
        <div class="top-nav-right">
            @auth
                <div class="notification-link-container" style="position: relative;">
                    <a href="#" class="notification-link">
                        <i class='bx bx-bell'></i> Notifications
                        <span id="notification-count" class="notification-count" style="display: none;">0</span>
                    </a>
                    <div class="notification-dropdown">
                        <div class="notification-header">
                            Notifications
                        </div>
                        <ul id="notification-list" class="notification-list"></ul>
                        <div class="view-all-container">
                            <a href="{{ route('notifications.viewAll') }}" class="view-all-link">View All</a>
                        </div>
                    </div>
                </div>
                <a href="#"><i class='bx bx-globe'></i> English</a>
                <div class="user-dropdown">
                <button class="user-button">
                    <img src="{{ Auth::user()->profile_image }}" alt="{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}" onerror="this.onerror=null;this.src='{{ asset('images/user.png') }}';">
                    {{ Auth::user()->first_name }} {{ Auth::user()->last_name }} <i class='bx bx-chevron-down'></i>
                </button>
                <div class="dropdown-content">
                    <a href="{{ route('profile') }}">My Profile</a>
                    <a href="{{ route('my-purchases') }}">My Purchase</a>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('register') }}">Sign Up</a>
                <a href="{{ route('login') }}">Login</a>
            @endauth
        </div>
    </nav>

    <!-- Add the audio element -->
    <audio id="notification-sound" src="{{ asset('tone/notif.mp3') }}"></audio>

    <!-- Hidden elements for success and error messages -->
    @if(session('success'))
        <div class="success-message" style="display:none;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="error-message" style="display:none;">{{ session('error') }}</div>
    @endif

    <!-- Link to the external JavaScript file -->
    <script src="{{ asset('js/top-nav.js') }}"></script>

    <!-- JavaScript to handle the "Start Selling" link click -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startSellingLink = document.getElementById('start-selling-link');
            if (startSellingLink) {
                startSellingLink.addEventListener('click', function(event) {
                    event.preventDefault();
                    @auth
                        window.location.href = "{{ route('admin.products.add-product-step1') }}";
                    @else
                        window.location.href = "{{ route('admin.products.registration.form') }}";
                    @endauth
                });
            }
        });
    </script>
</body>
</html>
