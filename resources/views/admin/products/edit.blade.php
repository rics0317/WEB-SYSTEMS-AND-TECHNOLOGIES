@extends('layouts.app')

@section('content')
<div class="video-background">
    <video autoplay muted loop id="background-video">
        <source src="{{ asset('assets/mp4/M2.mp4') }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>
</div>
<div class="outer-container">
    <div class="form-container">
        <h1>Edit Product</h1>
        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="image">Product Image</label>
                <input type="file" name="image" class="form-control-file" id="image">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" class="img-thumbnail mt-2 small-img">
                @endif
            </div>
            
            <div class="form-group">
                <label for="product_name">Product Name</label>
                <input type="text" name="product_name" class="form-control" id="product_name" value="{{ old('product_name', $product->product_name) }}" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" class="form-control" id="description" required>{{ old('description', $product->description) }}</textarea>
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" name="price" class="form-control" id="price" value="{{ old('price', $product->price) }}" step="0.01" max="200000000000000" required>
            </div>
            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="number" name="stock" class="form-control" id="stock" value="{{ old('stock', $product->stock) }}" max="200000000000000" required>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>
<link rel="stylesheet" href="{{ asset('css/editblade.css') }}">
@endsection