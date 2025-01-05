@extends('layouts.seller')

@section('content')
<style>
    /* Modern Variables */
    :root {
        --primary-color: #EE4D2D;
        --primary-light: #f36d57;
        --secondary-color: #555555;
        --success-color: #26aa99;
        --danger-color: #ee4d2d;
        --background-color: #f6f6f6;
        --card-background: #ffffff;
        --border-color: #efefef;
        --text-primary: #222222;
        --text-secondary: #555555;
        --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
        --shadow-md: 0 1px 3px rgba(0,0,0,0.1);
    }

    /* Main Container */
    .profile-container {
        max-width: 1200px;
        margin: 1rem auto;
        padding: 0 1rem;
        background: var(--background-color);
    }

    /* Profile Header */
    .profile-header {
        background: var(--card-background);
        padding: 1.5rem;
        box-shadow: var(--shadow-sm);
        margin-bottom: 1rem;
        border: 1px solid var(--border-color);
    }

    .profile-content {
        display: flex;
        gap: 2rem;
        align-items: center;
    }

    .profile-image {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 1px solid var(--border-color);
    }

    .profile-info-header {
        flex: 1;
    }

    .profile-name {
        font-size: 1.5rem;
        font-weight: 500;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .profile-role {
        display: inline-block;
        background: var(--primary-color);
        color: white;
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
        margin-right: 0.5rem;
    }

    .profile-status {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
    }

    .status-active {
        background: #e6f6f4;
        color: var(--success-color);
    }

    .status-inactive {
        background: #fde2e2;
        color: var(--danger-color);
    }

    /* Profile Details Grid */
    .profile-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .detail-card {
        background: var(--card-background);
        padding: 1.25rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
    }

    .detail-card h3 {
        font-size: 1.125rem;
        font-weight: 500;
        color: var(--text-primary);
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--border-color);
    }

    .detail-item {
        display: flex;
        margin-bottom: 0.75rem;
        padding: 0.5rem;
        background: var(--background-color);
    }

    .detail-label {
        width: 120px;
        color: var(--text-secondary);
    }

    .detail-value {
        flex: 1;
        color: var(--text-primary);
    }

    /* Addresses Section */
    .addresses-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1rem;
    }

    .address-card {
        background: var(--card-background);
        padding: 1.25rem;
        border: 1px solid var(--border-color);
    }

    .address-type {
        font-size: 0.875rem;
        color: var(--primary-color);
        margin-bottom: 0.75rem;
    }

    /* Navigation Tabs */
    .profile-tabs {
        display: flex;
        margin-bottom: 1rem;
        background: white;
        border-bottom: 1px solid var(--border-color);
    }

    .profile-tab {
        padding: 1rem 1.5rem;
        color: var(--text-secondary);
        cursor: pointer;
        position: relative;
    }

    .profile-tab.active {
        color: var(--primary-color);
    }

    .profile-tab.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
        height: 2px;
        background: var(--primary-color);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .profile-content {
            flex-direction: column;
            text-align: center;
        }

        .profile-image {
            width: 100px;
            height: 100px;
        }

        .detail-item {
            flex-direction: column;
        }

        .detail-label {
            width: 100%;
            margin-bottom: 0.25rem;
        }
    }
</style>

<div class="profile-container">
    <div class="profile-header">
        <div class="profile-content">
            <img src="{{ asset('' . $user->profile_image) }}" alt="Profile Image" class="profile-image">
            <div class="profile-info-header">
                <h1 class="profile-name">{{ $user->first_name }} {{ $user->last_name }}</h1>
                <span class="profile-role">{{ $user->role->role_name }}</span>
                <span class="profile-status {{ $user->status == 1 ? 'status-active' : 'status-inactive' }}">
                    {{ $user->status == 1 ? 'Active' : 'Offline' }}
                </span>
            </div>
        </div>
    </div>

    <div class="profile-tabs">
        <div class="profile-tab active">Profile Information</div>
        <div class="profile-tab">Addresses</div>
    </div>

    <div class="profile-details">
        <div class="detail-card">
            <h3>Personal Information</h3>
            <div class="detail-item">
                <span class="detail-label">Email</span>
                <span class="detail-value">{{ $user->email }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Contact</span>
                <span class="detail-value">{{ $user->contact }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Birthdate</span>
                <span class="detail-value">{{ $user->birthdate }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Age</span>
                <span class="detail-value">{{ $user->age }} years</span>
            </div>
        </div>

        <div class="detail-card">
            <h3>Additional Details</h3>
            <div class="detail-item">
                <span class="detail-label">Gender</span>
                <span class="detail-value">{{ $user->gender }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Civil Status</span>
                <span class="detail-value">{{ $user->civil_status }}</span>
            </div>
        </div>
    </div>

    <div class="detail-card">
        <h3>Addresses</h3>
        <div class="addresses-grid">
            @foreach($user->addresses as $index => $address)
                <div class="address-card">
                    <div class="address-type">Address {{ $index + 1 }} {{ $address->is_default ? '(Default)' : '' }}</div>
                    <div class="detail-item">
                        <span class="detail-label">Full Name</span>
                        <span class="detail-value">{{ $address->full_name ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Phone Number</span>
                        <span class="detail-value">{{ $address->phone_number ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Region</span>
                        <span class="detail-value">{{ $address->region ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Province</span>
                        <span class="detail-value">{{ $address->province ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">City</span>
                        <span class="detail-value">{{ $address->city ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Barangay</span>
                        <span class="detail-value">{{ $address->barangay ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Postal Code</span>
                        <span class="detail-value">{{ $address->postal_code ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Street Address</span>
                        <span class="detail-value">{{ $address->street_address ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Label</span>
                        <span class="detail-value">{{ $address->label ?? 'N/A' }}</span>
                    </div>
                </div>
            @endforeach

            @if(count($user->addresses) < 2)
                <div class="address-card">
                    <div class="address-type">Address 2</div>
                    <div class="detail-item">
                        <span class="detail-label">Full Name</span>
                        <span class="detail-value">N/A</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Phone Number</span>
                        <span class="detail-value">N/A</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Region</span>
                        <span class="detail-value">N/A</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Province</span>
                        <span class="detail-value">N/A</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">City</span>
                        <span class="detail-value">N/A</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Barangay</span>
                        <span class="detail-value">N/A</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Postal Code</span>
                        <span class="detail-value">N/A</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Street Address</span>
                        <span class="detail-value">N/A</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Label</span>
                        <span class="detail-value">N/A</span>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
