@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Product Details</h1>
    <p><strong>Name:</strong> {{ $product->product_name }}</p>
    <p><strong>Description:</strong> {{ $product->description }}</p>
    <p><strong>Price:</strong> {{ $product->price }}</p>
    <p><strong>Stock:</strong> {{ $product->stock }}</p>
    <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">Edit</a>
    <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this product?');">Delete</button>
    </form>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to Products</a>
</div>
@endsection
