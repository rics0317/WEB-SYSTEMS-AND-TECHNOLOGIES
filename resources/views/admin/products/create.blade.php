@extends('layouts.app')

@section('content')
<div class="video-background">
    <video autoplay muted loop id="background-video">
        <source src="{{ asset('assets/mp4/M2.mp4') }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>
</div>

<!-- Centered Form with Top Heading -->
<div class="outer-container">
    <div class="form-container">
        <h1>Create Product</h1>

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="image">Product Image</label>
                <input type="file" name="image" class="form-control-file" id="image">
            </div>
            <div class="form-group">
                <label for="product_name">Product Name</label>
                <input type="text" name="product_name" class="form-control" id="product_name" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" class="form-control" id="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" name="price" class="form-control" id="price" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="number" name="stock" class="form-control" id="stock" required>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</div>
 </div>
</div>
<link rel="stylesheet" href="{{ asset('css/createblade.css') }}">
@endsection