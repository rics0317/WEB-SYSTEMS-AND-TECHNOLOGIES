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
        <!-- Add Category Button -->
        <div class="add-category-button">
            <a href="{{ route('admin.category.create') }}" class="btn btn-success">Add Category</a>
        </div>
        
        <!-- Back Icon -->
        <a href="{{ route('home') }}" class="btn btn-secondary back-icon-container">
            <img src="{{ asset('assets/img/back.png') }}" alt="Back" class="back-icon"> 
        </a>
    </div>

    @if ($categories->isEmpty())
        <div class="no-products-message">
            <p>No category found.</p>
        </div>
    @else
        <table class="table table-striped products-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->description }}</td>
                        <td>{{ $item->is_active ? 'Active' : 'Inactive' }}</td>
                        <td>
                            <a href="{{ route('category.edit', $item->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('category.destroy', $item->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this category?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Pagination -->
    {{ $categories->links() }} <!-- Use $categories for pagination -->
</div>

<link rel="stylesheet" href="{{ asset('css/indexblade.css') }}">
@endsection
