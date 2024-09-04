<!-- resources/views/products/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="video-background">
    <video autoplay muted loop id="background-video">
        <source src="assets/mp4/Mine.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
</div>

<div class="container">
    <!-- Header Actions Container -->
    <div class="header-actions">
        <!-- Back Icon -->
        <a href="{{ route('home') }}" class="btn btn-secondary back-icon-container">
            <img src="back.png" alt="Back" class="back-icon"> 
        </a>
    </div>

    <!-- Centered Search Form -->
    <div class="heading-container">
        <!-- Search Form -->
        <form action="{{ route('products.index') }}" method="GET" class="search-form">
            <input type="text" name="search" class="form-control search-input" placeholder="SEARCH" value="{{ request('search') }}">
            <button type="submit" class="btn search-btn">
                <img src="search-image.png" alt="Search" class="search-icon"> <!-- Replace with your Minecraft icon -->
            </button>
        </form>
    </div>

    <!-- Display Products -->
    @if ($products->isEmpty())
        <div class="no-products-message">
            <p>No products found.</p>
        </div>
    @else
        <div class="products-container">
            @foreach ($products as $product)
                <div class="product-card">
                    <div class="product-image">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" width="100">
                        @else
                            No Image
                        @endif
                    </div>
                    <div class="product-details">
                        <h3>{{ $product->product_name }}</h3>
                        <p>{{ $product->description }}</p>
                        <p>Price: ${{ number_format($product->price, 2) }}</p>
                        <p>Stock: {{ $product->stock }}</p>
                        <div class="product-actions">
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Pagination -->
    {{ $products->links() }}
</div>
<link rel="stylesheet" href="{{ asset('css/indexblade.css') }}">
@endsection


