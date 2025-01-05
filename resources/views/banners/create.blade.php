@extends('layouts.seller')

@section('content')
<div class="product-form">
    <div class="form-section basic-info">
        <h1>Create Banner</h1>
        <form action="{{ route('banners.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="title">Title</label>
                <div class="input-container">
                    <input type="text" name="title" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <div class="input-container">
                    <textarea name="description" class="form-control"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label>Media</label>
                <div class="image-upload-grid">
                    <div class="image-upload-box">
                        <input type="file" id="coverPhoto" accept="image/*" hidden name="images[]" required>
                        <label for="coverPhoto" class="upload-label">
                            <span class="plus-icon">+</span>
                            <span>Image 1</span>
                        </label>
                    </div>
                    <div class="image-upload-box">
                        <input type="file" id="image1" accept="image/*" hidden name="images[]">
                        <label for="image1" class="upload-label">
                            <span class="plus-icon">+</span>
                            <span>Image 2</span>
                        </label>
                    </div>
                    <div class="image-upload-box">
                        <input type="file" id="image2" accept="image/*" hidden name="images[]">
                        <label for="image2" class="upload-label">
                            <span class="plus-icon">+</span>
                            <span>Image 3</span>
                        </label>
                    </div>
                    <div class="image-upload-box">
                        <input type="file" id="image3" accept="image/*" hidden name="images[]">
                        <label for="image3" class="upload-label">
                            <span class="plus-icon">+</span>
                            <span>Image 4</span>
                        </label>
                    </div>
                    <div class="image-upload-box">
                        <input type="file" id="image3" accept="image/*" hidden name="images[]">
                        <label for="image3" class="upload-label">
                            <span class="plus-icon">+</span>
                            <span>Image 5</span>
                        </label>
                    </div>
                </div>
                <small class="form-text text-muted">You can upload up to 3-5 images banner.</small>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <div class="input-container">
                    <input type="checkbox" name="status" checked>
                </div>
            </div>
            <div class="button-container2">
                <button type="submit" class="btn btn-primary">Create</button>
            </div>
        </form>
    </div>
</div>

<link rel="stylesheet" href="{{ asset('css/sellerproducts.css') }}">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
.form-section {
    margin-bottom: 30px;
}

.form-group {
    margin-bottom: 20px;
}

.input-container {
    position: relative;
    margin-top: 8px;
}

.help-text {
    color: #666;
    font-size: 12px;
    margin-top: 4px;
    display: block;
}

input[readonly] {
    background-color: #f5f5f5;
    cursor: not-allowed;
}

.char-count {
    position: absolute;
    right: 10px;
    bottom: 10px;
    font-size: 12px;
    color: #666;
}

.image-upload-grid {
    display: flex;
    gap: 15px;
}

.image-upload-box {
    position: relative;
    width: 100px;
    height: 100px;
    border: 2px dashed #ddd;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    overflow: hidden;
}

.upload-label {
    cursor: pointer;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.plus-icon {
    font-size: 24px;
    margin-bottom: 8px;
}

.button-container2 {
    display: flex;
    justify-content: flex-end;
    gap: 15px;
    margin-top: 20px;
}

.required-asterisk {
    color: red;
    margin-left: 4px;
}

.currency-symbol,
.percentage-symbol {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #666;
}

.percentage-symbol {
    left: auto;
    right: 10px;
}

input[type="number"] {
    padding-left: 25px;
}

input[name$="[stock]"] {
    padding-left: 10px;
}

.subtle-text {
    color: #666;
    font-size: 14px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image upload preview
    document.querySelectorAll('.image-upload-box input[type="file"]').forEach(input => {
        input.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.width = '100%';
                    img.style.height = '100%';
                    img.style.objectFit = 'cover';
                    const label = event.target.parentNode.querySelector('.upload-label');
                    if (label) {
                        label.style.display = 'none';
                    }
                    event.target.parentNode.appendChild(img);
                }
                reader.readAsDataURL(file);
            }
        });
    });
});
</script>
@endsection
