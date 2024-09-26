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
        <!-- Back Icon -->
        <a href="{{ route('home') }}" class="btn btn-secondary back-icon-container">
            <img src="{{ asset('assets/img/back.png') }}" alt="Back" class="back-icon"> 
        </a>
    </div>

    <!-- Add Product Button -->
    <div class="add-product-button">
        <a href="{{ route('products.create') }}" class="btn btn-success">Add Product</a>
    </div>

    <!-- Display Products in a Table -->
    @if ($products->isEmpty())
        <div class="no-products-message">
            <p>No products found.</p>
        </div>
    @else
        <table class="table table-striped products-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                <tr>
                    <td>
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" width="100">
                        @else
                            No Image
                        @endif
                    </td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->description }}</td>
                    <td>${{ number_format($product->price, 2) }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
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
    {{ $products->links() }}
</div>

<link rel="stylesheet" href="{{ asset('css/indexblade.css') }}">
@endsection
