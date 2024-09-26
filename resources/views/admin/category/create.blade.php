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
        <form action="{{ route('admin.category.store') }}" method="POST">
            @csrf
            <!-- Removed @method('PUT') -->
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('name') border-red-500 @enderror" required>
                @error('name')
                <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('description') border-red-500 @enderror" rows="4" required>{{ old('description') }}</textarea>
                @error('description')
                <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
            
            
            <div class="form-group">
                <label for="is_active" class="block text-gray-700">Is Active</label>
                <select id="is_active" name="is_active" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    <option value="1" {{ old('is_active') == 1 ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>No</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Create</button>
            <a href="{{ url('admin/category') }}" class="block text-gray-700">Back</a>
        </form>
    </div>
</div>
<link rel="stylesheet" href="{{ asset('css/editblade.css') }}">
@endsection
