<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Registration - Shopee</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="{{ asset('css/seller-registration.css') }}" rel="stylesheet">
</head>
<body>
    <header class="header">
        <div class="header-left">
            <a href="{{ route('users.home') }}" class="logo">
                <i class='bx bxs-shopping-bag'></i>
                <span>Shopee</span>
            </a>
            <h1 class="header-title">Seller Registration</h1>
        </div>
        <div class="user-profile">
            @auth
                <img src="{{ Auth::user()->profile_image }}" alt="{{ Auth::user()->name }}">
                <span>{{ Auth::user()->name }}</span>
                <div class="dropdown">
                    <a href="{{ route('profile') }}">Profile</a>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                </div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @endauth
        </div>
    </header>

    <main class="welcome-container">
        <div class="welcome-icon">
            <i class='bx bx-edit'></i>
        </div>
        <h2 class="welcome-title">Welcome to Shopee!</h2>
        <p class="welcome-description">
            To get started, register as a seller by providing the necessary information.
        </p>
        <a href="{{ route('seller.registration.form') }}" class="start-button">
            Start Registration
        </a>
    </main>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Registration - Shopee</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="{{ asset('css/seller-registration.css') }}" rel="stylesheet">
</head>
<body>
    <header class="header">
        <div class="header-left">
            <a href="{{ route('users.home') }}" class="logo">
                <i class='bx bxs-shopping-bag'></i>
                <span>Shopee</span>
            </a>
            <h1 class="header-title">Seller Registration</h1>
        </div>
        <div class="user-profile">
            @auth
                <img src="{{ Auth::user()->profile_image }}" alt="{{ Auth::user()->name }}">
                <span>{{ Auth::user()->name }}</span>
                <div class="dropdown">
                    <a href="{{ route('profile') }}">Profile</a>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                </div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @endauth
        </div>
    </header>

    <main class="welcome-container">
        <div class="welcome-icon">
            <i class='bx bx-edit'></i>
        </div>
        <h2 class="welcome-title">Welcome to Shopee!</h2>
        <p class="welcome-description">
            To get started, register as a seller by providing the necessary information.
        </p>
        <a href="{{ route('seller.registration.form') }}" class="start-button">
            Start Registration
        </a>
    </main>
</body>
</html>
