<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Addresses</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/usersprofile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/usersprofilemodal.css') }}">
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
                        <a href="{{ route('profile') }}" class="">My Account</a>
                        <a href="{{ route('profile.change') }}" class="">Change Password</a>
                        <a href="{{ route('my-purchases') }}">My Purchase</a>
                        <a href="{{ route('notifications.viewAll') }}" class="notification-link">Notifications</a>
                    </div>
                </div>

                <div class="main-content">
                    <div class="addresses-header">
                        <h1>My Addresses</h1>
                        <button class="add-address-btn" id="openModalBtn1">
                            <i class='bx bx-plus'></i>
                            Add New Address
                        </button>
                    </div>

                    <div class="addresses-list">
                        @foreach($addresses as $address)
                            <div class="address-item {{ $address->is_default ? 'default-address' : '' }}" data-address-id="{{ $address->id }}">
                                <div class="address-info">
                                    <div class="address-header">
                                        <div class="name-phone">
                                            <h3 class="name">{{ $address->full_name }}</h3>
                                            <span class="separator">|</span>
                                            <span class="phone">{{ $address->phone_number }}</span>
                                        </div>
                                    </div>
                                    <p class="street">{{ $address->street_address }}</p>
                                    <p class="city">{{ $address->barangay }}, {{ $address->city }}, {{ $address->province }}, {{ $address->region }}, {{ $address->postal_code }}</p>
                                    <div class="address-tags">
                                        @if($address->is_default)
                                            <span class="tag default">Default</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="address-actions">
                                    <div class="edit-delete-container">
                                        <a href="#" class="edit-btn" data-address-id="{{ $address->id }}">Edit</a>
                                        @if(!$address->is_default)
                                            <button class="delete-btn" data-address-id="{{ $address->id }}">Delete</button>
                                        @endif
                                    </div>
                                    @if(!$address->is_default)
                                        <button class="set-default-btn" data-address-id="{{ $address->id }}">Set as default</button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modalContainer1" class="modal-container1">
        <div class="modal1">
            <h2>New Address</h2>
            <form id="addressForm" action="{{ route('profile.createAddress') }}" method="POST">
                @csrf
                <div class="form-row1">
                    <div class="form-group1">
                        <label for="full_name">Full Name</label>
                        <input type="text" id="full_name" name="full_name" required>
                    </div>
                    <div class="form-group1">
                        <label for="phone_number">Phone Number</label>
                        <input type="tel" id="phone_number" name="phone_number" required maxlength="17" placeholder="+63 XXX XXX XXXX">
                    </div>
                </div>

                <div class="form-group1 margin-top1">
                    <label>Region, Province, City, Barangay</label>
                    <div class="location-selector1" id="locationSelector1">
                        <div class="selected-location1">Select location</div>
                        <div class="location-dropdown1">
                            <div class="location-tabs1">
                                <div class="location-tab1 active1" data-tab="region">Region</div>
                                <div class="location-tab1" data-tab="province">Province</div>
                                <div class="location-tab1" data-tab="city">City</div>
                                <div class="location-tab1" data-tab="barangay">Barangay</div>
                            </div>
                            <div class="location-options1"></div>
                        </div>
                    </div>
                </div>

                <input type="hidden" id="region" name="region">
                <input type="hidden" id="province" name="province">
                <input type="hidden" id="city" name="city">
                <input type="hidden" id="barangay" name="barangay">

                <div class="form-group1 margin-top1">
                    <label for="postal_code">Postal Code</label>
                    <input type="text" id="postal_code" name="postal_code" required maxlength="4">
                </div>

                <div class="form-group1 margin-top1">
                    <label for="street_address">Street Name, Building, House No.</label>
                    <input type="text" id="street_address" name="street_address" required>
                </div>

                <div class="label-section1">
                    <div class="label-title1">Label As:</div>
                    <div class="label-options1">
                        <label><input type="radio" name="label" value="Home" checked> Home</label>
                        <label><input type="radio" name="label" value="Work"> Work</label>
                    </div>
                </div>

                <div class="button-group1">
                    <button type="button" class="btn1 btn-cancel1" id="closeModalBtn1">Cancel</button>
                    <button type="submit" class="btn1 btn-submit1">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/home.js') }}"></script>
    <script src="{{ asset('js/edit-profile.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.set-default-btn').forEach(function(button) {
                button.addEventListener('click', function() {
                    var addressId = this.closest('.address-item').getAttribute('data-address-id');
                    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    fetch('{{ route('profile.setDefaultAddress') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({ address_id: addressId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    })
                    .catch(error => console.error('Error:', error));
                });
            });

            document.querySelectorAll('.delete-btn').forEach(function(button) {
                button.addEventListener('click', function() {
                    var addressId = this.getAttribute('data-address-id');
                    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    if (confirm('Are you sure you want to delete this address?')) {
                        fetch('{{ route('profile.deleteAddress') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({ address_id: addressId })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            }
                        })
                        .catch(error => console.error('Error:', error));
                    }
                });
            });

            document.querySelectorAll('.edit-btn').forEach(function(button) {
                button.addEventListener('click', function() {
                    var addressId = this.getAttribute('data-address-id');
                    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    fetch('{{ route('profile.getAddress') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({ address_id: addressId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.address) {
                            var form = document.getElementById('addressForm');
                            form.action = '{{ route('profile.updateAddress') }}';
                            form.querySelector('input[name="full_name"]').value = data.address.full_name;
                            form.querySelector('input[name="phone_number"]').value = data.address.phone_number;
                            form.querySelector('input[name="region"]').value = data.address.region;
                            form.querySelector('input[name="province"]').value = data.address.province;
                            form.querySelector('input[name="city"]').value = data.address.city;
                            form.querySelector('input[name="barangay"]').value = data.address.barangay;
                            form.querySelector('input[name="postal_code"]').value = data.address.postal_code;
                            form.querySelector('input[name="street_address"]').value = data.address.street_address;
                            form.querySelector('input[name="label"]:checked').value = data.address.label;

                            var modalContainer1 = document.getElementById('modalContainer1');
                            modalContainer1.style.display = 'block';
                        }
                    })
                    .catch(error => console.error('Error:', error));
                });
            });
        });
    </script>
</body>
</html>
