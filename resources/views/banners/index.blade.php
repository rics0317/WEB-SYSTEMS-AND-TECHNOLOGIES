@extends('layouts.seller')

@section('content')
<div class="container py-4">
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="card-title">Banner Management</h1>
                <a href="{{ route('banners.create') }}" class="btn btn-primary">Add New Banner</a>
            </div>
            <p class="card-text">Manage your banners here. You can add, edit, or delete banners as needed.</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Image</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($banners as $banner)
                    <tr>
                        <td>{{ $banner->title }}</td>
                        <td><img src="{{ asset('images/' . $banner->image_path) }}" alt="{{ $banner->title }}" width="100" class="img-thumbnail"></td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="status-{{ $banner->id }}" {{ $banner->status ? 'checked' : '' }}
                                    onchange="updateStatus({{ $banner->id }}, this.checked)">
                                <label class="form-check-label" for="status-{{ $banner->id }}">
                                    {{ $banner->status ? 'Active' : 'Inactive' }}
                                </label>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('banners.edit', $banner->id) }}" class="btn btn-sm btn-primary">Edit</a>
                            <form action="{{ route('banners.destroy', $banner->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this banner?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function updateStatus(id, status) {
    // TODO: Implement AJAX call to update banner status
    console.log(`Updating banner ${id} status to ${status}`);
    // After successful update, you may want to update the label text
    document.querySelector(`label[for="status-${id}"]`).textContent = status ? 'Active' : 'Inactive';
}
</script>
@endsection