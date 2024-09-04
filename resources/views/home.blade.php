<!-- resources/views/marketplace.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Minecraft Marketplace')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/homeblade.css') }}">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <!-- Larger image on the top left -->
        <img src="{{ asset('Mine1.png') }}" alt="Logo"> <!-- Update with correct path if needed -->
        <h1>@yield('header')</h1>

        <!-- Top right buttons -->
        <div class="top-right-buttons">
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg m-2">
                <i class="fas fa-store"></i> Shop
            </a>
            <a href="{{ route('products.create') }}" class="btn btn-secondary btn-lg m-2">
                <i class="fas fa-plus"></i> New Product
            </a>
        </div>
    </header>

    <!-- Background Video -->
    <video class="background-video" autoplay muted loop>
        <source src="assets/mp4/M3.mp4" type="video/mp4"> <!-- Update with correct path -->
        Your browser does not support the video tag.
    </video>

    <!-- Background Music -->
    <audio class="background-music" autoplay loop>
        <source src="assets/audio/M3.mp3" type="audio/mp3"> <!-- Update with correct path -->
        Your browser does not support the audio element.
    </audio>

    @yield('content')
</body>
</html>
