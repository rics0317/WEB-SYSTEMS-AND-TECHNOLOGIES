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
    <h1>Edit Category</h1>
    <form action="{{ route('admin.category.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" class="form-control" required>{{ $category->description }}</textarea>
        </div>
        
        <div class="form-group form-check">
            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" {{ $category->is_active ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Active</label>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>
<link rel="stylesheet" href="{{ asset('css/editblade.css') }}">
@endsection