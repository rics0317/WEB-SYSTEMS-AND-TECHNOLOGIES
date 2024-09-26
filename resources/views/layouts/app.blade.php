<!-- resources/views/marketplace.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Minecraft Marketplace')</title>
    <link rel="stylesheet" href="{{ asset('css/appblade.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
</head>
<body>

    <header>
        <!-- Larger image on the top left -->
        <img src="{{ asset('Mine1.png') }}" alt="Logo"> <!-- Update with correct path if needed -->
        <h1>@yield('header')</h1>
    </header>

    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>