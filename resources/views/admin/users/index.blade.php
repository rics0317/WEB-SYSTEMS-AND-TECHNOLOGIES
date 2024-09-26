@extends('layouts.app')

@section('content')
<div class="video-background">
    <video autoplay muted loop id="background-video">
        <source src="{{ asset('assets/mp4/Mine.mp4') }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>
</div>

<div class="container">
    <!-- Header Actions Container -->
    <div class="header-actions d-flex justify-content-between mb-3">
        <!-- Add User Button -->
        <a href="{{ route('admin.users.create') }}" class="btn btn-success">
            Add User
        </a>

        <!-- Back Icon -->
        <a href="{{ route('home') }}" class="btn btn-secondary back-icon-container">
            <img src="{{ asset('assets/img/back.png') }}" alt="Back" class="back-icon"> 
        </a>
    </div>

    <!-- Display Users in a Table -->
    @if ($users->isEmpty())
        <div class="alert alert-warning text-center">
            <p>No users found.</p>
        </div>
    @else
        <table class="table table-striped products-table">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <form action="{{ route('admin.users.updateRole', $user->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <select name="role" id="role-{{ $user->id }}" class="form-select">
                                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            <!-- Update Button -->
                            <button type="submit" class="btn btn-primary">Update Role</button>
                        </form>
                    </td>
                    <td>
                        <!-- Delete Button -->
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Pagination -->
    {{ $users->links() }}
</div>

<link rel="stylesheet" href="{{ asset('css/indexblade.css') }}">
@endsection
