<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/usersprofile.css') }}">
    <link rel="icon" href="{{ asset('images/s.png') }}" type="image/png">
    <style>
        .notification-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .notification-checkbox {
            margin-right: 20px;
        }
        .notification-message {
            flex-grow: 1;
        }
        .notification-time {
            margin-left: auto;
            font-size: 0.9em;
            color: #999;
        }
    </style>
</head>
<body>
    <!-- Include the existing top navigation -->
    @include('users.partials.top-nav')

    <!-- Include the existing header -->
    @include('users.partials.header')

    <!-- Wrapper container added here -->
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
                        <a href="{{ route('profile.change') }}" class="">Change Password</a>
                        <a href="{{ route('my-purchases') }}">My Purchase</a>
                        <a href="{{ route('notifications.viewAll') }}" class="active">Notifications</a>
                    </div>
                </div>

                <div class="main-content">
                    <div class="profile-header">
                        <h1>Notifications</h1>
                        <h1 class="hidden-text">This text is hidden</h1>
                        <input type="checkbox" id="select-all" onchange="toggleSelectAll()"> Select All
                        <div class="dropdown">
                            <button class="dropbtn">Options</button>
                            <div class="dropdown-content">
                                <a href="javascript:void(0)" onclick="deleteSelectedNotifications()">Delete Selected</a>
                                <a href="javascript:void(0)" onclick="markAllAsRead()">Mark All as Read</a>
                            </div>
                        </div>

                        <div class="notification-list">
                            @foreach ($notifications as $notification)
                                <div class="notification-item {{ $notification->read ? 'read' : 'unread' }}">
                                    <input type="checkbox" class="notification-checkbox" value="{{ $notification->id }}">
                                    <div class="notification-message">
                                        <p>{{ $notification->message }}</p>
                                        <span class="notification-time">{{ $notification->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
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
</body>
<script src="{{ asset('js/home.js') }}"></script>
<script>
    function markAllAsRead() {
        if (confirm('Are you sure you want to mark all notifications as read?')) {
            window.location.href = "{{ route('notifications.markAllAsRead') }}";
        }
    }

    function deleteSelectedNotifications() {
        var selectedNotifications = [];
        document.querySelectorAll('.notification-checkbox:checked').forEach(function(checkbox) {
            selectedNotifications.push(checkbox.value);
        });

        if (selectedNotifications.length > 0) {
            if (confirm('Are you sure you want to delete the selected notifications?')) {
                window.location.href = "{{ route('notifications.deleteSelected') }}?notifications=" + selectedNotifications.join(',');
            }
        } else {
            alert('No notifications selected.');
        }
    }

    function toggleSelectAll() {
        var selectAllCheckbox = document.getElementById('select-all');
        var notificationCheckboxes = document.querySelectorAll('.notification-checkbox');

        notificationCheckboxes.forEach(function(checkbox) {
            checkbox.checked = selectAllCheckbox.checked;
        });
    }
</script>
</html>
