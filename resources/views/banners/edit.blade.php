@extends('layouts.seller')

@section('content')
<div class="container">
    <h1>Edit Banner</h1>
    <form action="{{ route('banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" class="form-control" value="{{ $banner->title }}" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" class="form-control">{{ $banner->description }}</textarea>
        </div>
        <div class="form-group">
            <label for="image">Image</label>
            <input type="file" name="image" class="form-control">
            <img src="{{ asset('images/' . $banner->image_path) }}" alt="{{ $banner->title }}" width="100">
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <input type="checkbox" name="status" {{ $banner->status ? 'checked' : '' }}>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
