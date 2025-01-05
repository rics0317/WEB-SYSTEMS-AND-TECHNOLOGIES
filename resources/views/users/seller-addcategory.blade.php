@extends('layouts.seller')

@section('content')
    <div class="add-category-form">
        <h2>Add New Category</h2>
        <form action="{{ route('seller.store-category') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="categoryName">Category Name</label>
                <input type="text" id="categoryName" name="categoryName" required>
            </div>
            <div class="form-group">
                <label for="categoryDescription">Category Description</label>
                <textarea id="categoryDescription" name="categoryDescription" required></textarea>
            </div>
            <button type="submit" class="submit-button">Add Category</button>
        </form>
    </div>
@endsection
