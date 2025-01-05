<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/userspassword.css') }}">
    <link rel="icon" href="{{ asset('images/s.png') }}" type="image/png">
    <style>
        .profile-form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .form-group {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .form-label {
            width: 150px; /* Adjust width as needed */
            font-weight: 405;
            font-size: 0.875rem; /* Smaller font size for label */
        }

        .form-group input {
            width: 300px; /* Adjust width as needed */
            padding: 0.5rem;
            font-size: 1rem;
            text-align: left; /* Align text to the left */
        }

        /* Style placeholder text */
        .form-group input::placeholder {
            font-size: 0.875rem; /* Smaller font size for placeholder */
            color: #888; /* Adjust color for placeholder text */
            text-align: left; /* Align placeholder text to the left */
        }

        .change-pass {
            align-self: flex-start;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            cursor: pointer;
            background-color: #ee4d2d; /* Change button color */
            color: #fff;
            border: none;
            border-radius: 4px;
        }

        .change-pass:hover {
            background-color: #d94426; /* Slightly darker shade on hover */
        }
    </style>
</head>
<body>
    <!-- Include the existing top navigation -->
    @include('users.partials.top-nav')

    <!-- Include the existing header -->
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
                        <a href="{{ route('profile') }}" class="">My Account</a>
                        <a href="{{ route('profile.change') }}" class="active">Change Password</a>
                        <a href="{{ route('my-purchases') }}">My Purchase</a>
                        <a href="{{ route('notifications.viewAll') }}" class="notification-link">Notifications</a>
                    </div>
                </div>

                <div class="main-content">
                    <div class="profile-header">
                        <h1>Change Password</h1>
                        <p>Update your password to secure your account</p>
                    </div>

                    <form class="profile-form" method="POST" action="{{ route('profile.change.password') }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" placeholder="Enter your current password" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">New Password</label>
                            <input type="password" name="new_password" placeholder="Enter your new password" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation" placeholder="Confirm your new password" required>
                        </div>

                        <button type="submit" class="change-pass">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Dropdown -->
    <div class="notification-dropdown">
        <ul id="notification-list">
            <!-- Notifications will be dynamically added here -->
        </ul>
    </div>

    <!-- Audio element for notification sound -->
    <audio id="notification-sound" src="{{ asset('tone/notif.mp3') }}"></audio>

    <!-- Hidden elements for success and error messages -->
    @if(session('success'))
        <div class="success-message" style="display:none;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="error-message" style="display:none;">{{ session('error') }}</div>
    @endif

    <!-- SweetAlert Library -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
<script src="{{ asset('js/home.js') }}"></script>
</html>
