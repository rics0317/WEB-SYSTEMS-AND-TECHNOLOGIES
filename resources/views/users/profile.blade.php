<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
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
                        <h1>My Profile</h1>
                        <p>Manage and protect your account</p>
                    </div>

                    <form class="profile-form" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-left">
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" name="last_name" value="{{ Auth::user()->last_name }}">
                            </div>

                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" name="first_name" value="{{ Auth::user()->first_name }}">
                            </div>

                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" value="{{ Auth::user()->email }}">
                            </div>

                            <div class="form-group">
                                <label>Phone Number</label>
                                <div class="phone-input-group">
                                    @if(Auth::user()->contact)
                                        <input type="text" name="contact" value="{{ Auth::user()->contact }}" readonly>
                                        <a href="{{ route('profile.edit.phone') }}" class="change-link">Change</a>
                                    @else
                                        <input type="text" name="contact" value="{{ Auth::user()->contact }}" placeholder="Enter your phone number">
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Gender</label>
                                <div class="radio-group">
                                    <label class="radio-option">
                                        <input type="radio" name="gender" value="Male" {{ Auth::user()->gender == 'Male' ? 'checked' : '' }}>
                                        <span>Male</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="gender" value="Female" {{ Auth::user()->gender == 'Female' ? 'checked' : '' }}>
                                        <span>Female</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="gender" value="Other" {{ Auth::user()->gender == 'Other' ? 'checked' : '' }}>
                                        <span>Other</span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Date of birth</label>
                                <div class="date-select-group">
                                    <select name="birth_day" id="birth_day">
                                        <option value="">Date</option>
                                        @for ($i = 1; $i <= 31; $i++)
                                            <option value="{{ $i }}" {{ date('d', strtotime(Auth::user()->birthdate)) == $i ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                    <select name="birth_month" id="birth_month">
                                        <option value="">Month</option>
                                        @for ($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ date('m', strtotime(Auth::user()->birthdate)) == $i ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                    <select name="birth_year" id="birth_year">
                                        <option value="">Year</option>
                                        @for ($i = date('Y'); $i >= date('Y') - 100; $i--)
                                            <option value="{{ $i }}" {{ date('Y', strtotime(Auth::user()->birthdate)) == $i ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <input type="hidden" name="birthdate" id="birthdate">

                            <button type="submit" class="save-button">Save</button>
                        </div>

                        <div class="image-upload">
                            <div class="profile-image-container">
                                <img src="{{ Auth::user()->profile_image }}" alt="Profile" class="profile-image" onerror="this.onerror=null;this.src='{{ asset('images/user.png') }}';">
                            </div>
                            <input type="file" name="profile_image" id="profile_image" hidden>
                            <label for="profile_image" class="select-image-btn">Select Image</label>
                            <div class="image-requirements">
                                <p>File size: maximum 1 MB</p>
                                <p>File extension: .JPEG, .PNG</p>
                            </div>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const birthDay = document.getElementById('birth_day');
            const birthMonth = document.getElementById('birth_month');
            const birthYear = document.getElementById('birth_year');
            const birthdate = document.getElementById('birthdate');

            function updateBirthdate() {
                const day = birthDay.value;
                const month = birthMonth.value;
                const year = birthYear.value;

                if (day && month && year) {
                    birthdate.value = `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
                }
            }

            birthDay.addEventListener('change', updateBirthdate);
            birthMonth.addEventListener('change', updateBirthdate);
            birthYear.addEventListener('change', updateBirthdate);

            // Initial call to set the birthdate if already selected
            updateBirthdate();
        });
    </script>
</body>
</html>
