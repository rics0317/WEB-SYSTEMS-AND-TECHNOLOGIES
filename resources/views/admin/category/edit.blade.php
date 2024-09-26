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
            @error('name')
            <div class="text-danger mt-2">{{ $message }}</div>
             @enderror
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" class="form-control" required>{{ $category->description }}</textarea>
            @error('description')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
        </div>
        
        <div class="form-group">
          <label for="is_active" class="block text-gray-700">Is Active</label>
         <select id="is_active" name="is_active" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
         <option value="1" {{ $category->is_active == 1 ? 'selected' : '' }}>Yes</option>
         <option value="0" {{ $category->is_active == 0 ? 'selected' : '' }}>No</option>
        </select>
    </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ url('admin/category') }}" class="block text-gray-700">Back</a>
        </form>
    </div>
</div>
<link rel="stylesheet" href="{{ asset('css/editblade.css') }}">
@endsection