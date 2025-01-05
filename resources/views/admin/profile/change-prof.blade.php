@extends('layouts.seller')

@section('content')
<div class="profile-section">
    <!-- Profile Card -->
    <div class="profile-card">
        <div class="profile-info">
            <img src="{{ asset('' . auth()->user()->profile_image) }}" alt="Profile" class="profile-image">
            <div class="profile-name">{{ auth()->user()->last_name }}, {{ auth()->user()->first_name }}</div>
            <div class="profile-role">Admin</div>
        </div>
        <ul class="profile-details">
            <li>📅 Age <span style="color: #007bff; margin-left: auto;">{{ auth()->user()->age }}</span></li>
            <li>👤 Gender <span style="color: #007bff; margin-left: auto;">{{ auth()->user()->gender }}</span></li>
            <li>💍 Civil Status <span style="color: #007bff; margin-left: auto;">{{ auth()->user()->civil_status }}</span></li>
            <li>🎂 Birth Date <span style="color: #007bff; margin-left: auto;">{{ date('M d, Y', strtotime(auth()->user()->birthdate)) }}</span></li>
            <li>📞 Contact <span style="color: #007bff; margin-left: auto;">{{ auth()->user()->contact }}</span></li>
            <li>📧 Email <span style="color: #007bff; margin-left: auto;">{{ auth()->user()->email }}</span></li>
        </ul>
    </div>

    <!-- Update Form -->
    <div class="update-form">
        <h2 class="form-title">Update Admin Information</h2>
        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group full-width">
                <label for="lastName">Last Name</label>
                <input type="text" id="lastName" name="last_name" value="{{ auth()->user()->last_name }}">
            </div>

            <div class="form-group full-width">
                <label for="firstName">First Name</label>
                <input type="text" id="firstName" name="first_name" value="{{ auth()->user()->first_name }}">
            </div>

            <div class="form-group full-width">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ auth()->user()->email }}">
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="birthDate">Birth Date</label>
                    <input type="date" id="birthDate" name="birthdate" value="{{ auth()->user()->birthdate }}">
                </div>

                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender">
                        <option value="male" {{ auth()->user()->gender == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ auth()->user()->gender == 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="civilStatus">Civil Status</label>
                    <select id="civilStatus" name="civil_status">
                        <option value="single" {{ auth()->user()->civil_status == 'single' ? 'selected' : '' }}>Single</option>
                        <option value="married" {{ auth()->user()->civil_status == 'married' ? 'selected' : '' }}>Married</option>
                        <option value="divorced" {{ auth()->user()->civil_status == 'divorced' ? 'selected' : '' }}>Divorced</option>
                        <option value="widowed" {{ auth()->user()->civil_status == 'widowed' ? 'selected' : '' }}>Widowed</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="contact">Contact Number</label>
                    <input type="text" id="contact" name="contact" value="{{ auth()->user()->contact }}">
                </div>

                <div class="form-group">
                    <label for="age">Age</label>
                    <input type="number" id="age" name="age" value="{{ auth()->user()->age }}">
                </div>
            </div>

            <div class="form-group full-width">
                <label for="profilePicture">Profile Picture</label>
                <div class="file-input-group">
                    <input type="text" id="profilePicture" placeholder="Choose file" readonly>
                    <button type="button" class="browse-btn">Browse</button>
                    <input type="file" name="profile_image" style="display: none;" id="profileImageInput">
                </div>
            </div>

            <button type="submit" class="view-profile-btn">Update Profile</button>
        </form>
    </div>
</div>

<script>
    document.querySelector('.browse-btn').addEventListener('click', function() {
        document.getElementById('profileImageInput').click();
    });

    document.getElementById('profileImageInput').addEventListener('change', function(event) {
        document.getElementById('profilePicture').value = event.target.files[0].name;
    });
</script>
@endsection
