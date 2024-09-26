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
    <div class="header-actions">
        <a href="{{ route('home') }}" class="btn btn-secondary back-icon-container">
            <img src="{{ asset('assets/img/back.png') }}" alt="Back" class="back-icon"> 
        </a>
    </div>

    <!-- Centered Search Form -->
    <div class="heading-container">
        <form action="{{ route('users.products.index') }}" method="GET" class="search-form">
            <input type="text" name="search" class="form-control search-input" placeholder="SEARCH" value="{{ request('search') }}">
            <button type="submit" class="btn search-btn">
                <img src="{{ asset('assets/img/search-image.png') }}" alt="Search" class="search-icon">
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
        <img src="{{ asset('storage/products/' . $product->image) }}" alt="Product Image" width="100">
    @else
        <p>No Image</p>
    @endif
</div>
                    <div class="product-details">
                        <h3>{{ $product->product_name }}</h3>
                        <p>{{ $product->description }}</p>
                        <p>Price: ${{ number_format($product->price, 2) }}</p>
                        <p>Stock: {{ $product->stock }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
<link rel="stylesheet" href="{{ asset('css/indexblade.css') }}">
@endsection
