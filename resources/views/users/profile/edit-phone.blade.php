<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Phone Number</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/usersprofile.css') }}">
    <link rel="icon" href="{{ asset('images/s.png') }}" type="image/png">
</head>
<body>
    @include('users.partials.top-nav')
    @include('users.partials.header')

    <div class="profile-wrapper">
        <div class="container">
            <div class="profile-container">
                <div class="sidebar">
                    <div class="user-info">
                        <img src="{{ Auth::user()->profile_image }}" alt="Profile" class="user-avatar" onerror="this.onerror=null;this.src='{{ asset('images/user.png') }}';">
                        <div>
                            <div class="user-name">{{ Auth::user()->last_name }}, {{ Auth::user()->first_name }}</div>
                            <a href="{{ route('profile.edit') }}" class="edit-link"><i class='bx bx-pencil'></i> Edit Profile</a>
                        </div>
                    </div>
                    <div class="sidebar-menu">
                        <a href="{{ route('profile') }}" class="active">My Account</a>
                        <a href="{{ route('profile.change') }}">Change Password</a>
                        <a href="{{ route('my-purchases') }}">My Purchase</a>
                        <a href="{{ route('notifications.viewAll') }}" class="notification-link">Notifications</a>
                    </div>
                </div>

                <div class="main-content">
                    <div class="profile-header">
                        <h1>Edit Phone Number</h1>
                        <p>Update your phone number</p>
                    </div>

                    <form class="profile-form" method="POST" action="{{ route('profile.update.phone') }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>New Phone Number</label>
                            <input type="text" name="contact" value="{{ Auth::user()->contact }}" placeholder="Enter your new phone number">
                            <button type="submit" class="save-button2">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="notification-dropdown">
        <ul id="notification-list">
            <!-- Notifications will be dynamically added here -->
        </ul>
    </div>

    <audio id="notification-sound" src="{{ asset('tone/notif.mp3') }}"></audio>

    <script src="{{ asset('js/home.js') }}"></script>
</body>
</html>
