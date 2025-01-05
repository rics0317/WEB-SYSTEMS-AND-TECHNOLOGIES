@extends('layouts.seller')

@section('content')
<!-- Basic Information Section -->
<div class="product-form">
    <div class="form-section basic-info">
        <h2>Edit Product</h2>
        <p class="subtitle">Please update the product details.</p>
        <form id="productForm" action="{{ route('seller.update-product', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Product Name<span class="required-asterisk">*</span></label>
                <div class="input-container">
                    <input type="text" placeholder="Input" maxlength="100" id="productName" name="name" value="{{ $product->name }}" required>
                    <span class="char-count">{{ strlen($product->name) }}/100</span>
                </div>
            </div>

            <div class="form-group">
                <label>Product Description<span class="required-asterisk">*</span></label>
                <div class="input-container">
                    <textarea id="productDescription" placeholder="Enter product description" rows="4" name="description" maxlength="1000" required>{{ $product->description }}</textarea>
                    <span class="char-count">{{ strlen($product->description) }}/1000</span>
                </div>
            </div>

            <div class="form-container">
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <div class="category-path subtle-text">
                        {{ $product->category->name }} > {{ $product->subCategory->name }} > {{ $product->item->name }}
                        <span class="edit-icon">✎</span>
                    </div>
                    <input type="hidden" name="category_id" value="{{ $product->category_id }}">
                    <input type="hidden" name="sub_category_id" value="{{ $product->sub_category_id }}">
                    <input type="hidden" name="item_id" value="{{ $product->item_id }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Brand</label>
                    <select class="brand-select" name="brand_id">
                        <option value="">Set no brand</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
    </div>
</div>

<!-- Sales Information Section -->
<div class="product-form">
    <div class="form-section sales-info">
        <h2>Sales Information</h2>
        <div class="form-group">
            <label for="price">Price <span class="required-asterisk">*</span></label>
            <div class="input-container">
                <span class="currency-symbol">₱</span>
                <input type="number" id="price" placeholder="Input" step="0.01" name="price" value="{{ $product->price }}" required>
            </div>
        </div>

        <div class="form-group">
            <label for="stock">Stock <span class="required-asterisk">*</span></label>
            <div class="input-container">
                <input type="number" id="stock" placeholder="0" name="stock" value="{{ $product->stock }}" required readonly>
            </div>
        </div>

        <div class="form-group">
            <label for="discount">Discount Percentage</label>
            <div class="input-container">
                <input type="number" id="discount" placeholder="0" min="0" max="100" name="discount_percentage" value="{{ $product->discount_percentage }}">
                <span class="percentage-symbol">%</span>
            </div>
        </div>
    </div>
</div>

<!-- Variations Section -->
<div class="product-form">
    <div class="form-section variations-info">
        <h2>Variations</h2>
        <div class="form-group">
            <label>Variations</label>
            <button type="button" class="variations-toggle">Enable Variations</button>
        </div>

        <div id="variations-container" style="display: none;">
            @foreach ($product->variations as $index => $variation)
                <div class="variation">
                    <div class="variation-header">
                        <h3>Variation {{ $index + 1 }}</h3>
                        <button type="button" class="remove-variation">&times;</button>
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <div class="input-container">
                            <input type="text" name="variations[{{ $index }}][name]" placeholder="Enter Variation Name, eg: colour, etc." maxlength="14" value="{{ $variation->name }}">
                            <span class="char-count">{{ strlen($variation->name) }}/14</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Options</label>
                        <div class="options-container">
                            @foreach (json_decode($variation->options, true) as $optionIndex => $option)
                                <div class="input-container">
                                    <input type="text" name="variations[{{ $index }}][options][]" placeholder="Enter Variation Options, eg: Red, etc." maxlength="20" value="{{ $option }}">
                                    <span class="char-count">{{ strlen($option) }}/20</span>
                                    <button type="button" class="add-option">+</button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <button type="button" class="add-options">Add Options ({{ count(json_decode($variation->options, true)) }}/20)</button>
                </div>
            @endforeach
            <button type="button" class="add-variation">Add</button>
        </div>
    </div>
</div>

<!-- Media Management Section -->
<div class="product-form">
    <div class="form-section media-management">
        <h2>Media Management</h2>
        <div class="image-upload-grid">
            @foreach($images as $index => $image)
                <div class="image-upload-boxes">
                    <div class="image-container">
                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="Product Image {{ $index + 1 }}" class="product-image">
                        <div class="image-overlay">
                            <i class="fas fa-pencil-alt edit-icon upload-icon"></i>
                        </div>
                        <input type="file" id="image{{ $index }}" accept="image/*" hidden name="images[{{ $index }}]">
                        <button type="button" class="remove-image-btn" data-image-id="{{ $image->id }}">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endforeach
            @for ($i = count($images); $i < 9; $i++)
                <div class="image-upload-boxes empty">
                    <div class="image-container">
                        <input type="file" id="image{{ $i }}" accept="image/*" hidden name="images[{{ $i }}]">
                        <label for="image{{ $i }}" class="upload-placeholder">
                            <i class="fas fa-plus"></i>
                        </label>
                    </div>
                </div>
            @endfor
        </div>
    </div>
</div>

<!-- Button Container -->
<div class="product-form">
    <div class="button-container2">
        <button type="button" class="btn-cancel">Cancel</button>
        <button type="submit" class="btn-save-publish">Update Product</button>
    </div>
</div>

</form>

<link rel="stylesheet" href="{{ asset('css/editproducts.css') }}">
<link rel="stylesheet" href="{{ asset('css/sellerproducts.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const variationsToggle = document.querySelector('.variations-toggle');
    const variationsContainer = document.getElementById('variations-container');
    const addVariationButton = document.querySelector('.add-variation');
    let variationCount = {{ count($product->variations) }};

    // Image upload preview
    document.querySelectorAll('.image-upload-boxes input[type="file"]').forEach(input => {
        input.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = event.target.parentNode.querySelector('.product-image');
                    img.src = e.target.result;
                    img.style.width = '100%';
                    img.style.height = '100%';
                    img.style.objectFit = 'cover';
                }
                reader.readAsDataURL(file);
            }
        });
    });

    // Trigger file input on pencil icon click
    document.querySelectorAll('.upload-icon').forEach(icon => {
        icon.addEventListener('click', function() {
            const fileInput = this.closest('.image-container').querySelector('input[type="file"]');
            fileInput.click();
        });
    });

    // Variations toggle
    variationsToggle.addEventListener('click', function() {
        if (variationsContainer.style.display === 'none') {
            variationsContainer.style.display = 'block';
            this.textContent = 'Disable Variations';
        } else {
            variationsContainer.style.display = 'none';
            this.textContent = 'Enable Variations';
        }
    });

    // Add variation
    addVariationButton.addEventListener('click', function() {
        variationCount++;
        const newVariation = document.createElement('div');
        newVariation.classList.add('variation');
        newVariation.innerHTML = `
            <div class="variation-header">
                <h3>Variation ${variationCount}</h3>
                <button type="button" class="remove-variation">&times;</button>
            </div>
            <div class="form-group">
                <label>Name</label>
                <div class="input-container">
                    <input type="text" name="variations[${variationCount - 1}][name]" placeholder="Enter Variation Name, eg: colour, etc." maxlength="14">
                    <span class="char-count">0/14</span>
                </div>
            </div>
            <div class="form-group">
                <label>Options</label>
                <div class="options-container">
                    <div class="input-container">
                        <input type="text" name="variations[${variationCount - 1}][options][]" placeholder="Enter Variation Options, eg: Red, etc." maxlength="20">
                        <span class="char-count">0/20</span>
                        <button type="button" class="add-option">+</button>
                    </div>
                </div>
            </div>
            <button type="button" class="add-options">Add Options (1/20)</button>
        `;
        variationsContainer.insertBefore(newVariation, addVariationButton);
    });

    // Remove variation
    variationsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-variation')) {
            e.target.closest('.variation').remove();
        }
    });

    // Add option
    variationsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-option') || e.target.classList.contains('add-options')) {
            const variation = e.target.closest('.variation');
            const optionsContainer = variation.querySelector('.options-container');
            const optionCount = optionsContainer.querySelectorAll('.input-container').length;

            if (optionCount < 20) {
                const newOption = document.createElement('div');
                newOption.classList.add('input-container');
                newOption.innerHTML = `
                    <input type="text" name="variations[${variationCount - 1}][options][]" placeholder="Enter Variation Options, eg: Red, etc." maxlength="20">
                    <span class="char-count">0/20</span>
                    <button type="button" class="add-option">+</button>
                `;
                optionsContainer.appendChild(newOption);
                variation.querySelector('.add-options').textContent = `Add Options (${optionCount + 1}/20)`;
            }
        }
    });

    // Character count for variation inputs
    variationsContainer.addEventListener('input', function(e) {
        if (e.target.tagName === 'INPUT') {
            const charCount = e.target.nextElementSibling;
            charCount.textContent = `${e.target.value.length}/${e.target.maxLength}`;
        }
    });

    // Character count for product name and description
    const productName = document.getElementById('productName');
    const productNameCharCount = productName.nextElementSibling;
    productNameCharCount.textContent = `${productName.value.length}/${productName.maxLength}`;

    productName.addEventListener('input', function(e) {
        productNameCharCount.textContent = `${e.target.value.length}/${e.target.maxLength}`;
    });

    const productDescription = document.getElementById('productDescription');
    const productDescriptionCharCount = productDescription.nextElementSibling;
    productDescriptionCharCount.textContent = `${productDescription.value.length}/1000`;

    productDescription.addEventListener('input', function(e) {
        if (e.target.value.length > 1000) {
            e.target.value = e.target.value.slice(0, 1000);
        }
        productDescriptionCharCount.textContent = `${e.target.value.length}/1000`;
    });

    // Form submission
    document.getElementById('productForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);

        // Prepare variations data
        const variations = [];
        document.querySelectorAll('.variation').forEach((variation, index) => {
            const name = variation.querySelector('input[name^="variations"][name$="[name]"]').value;
            const options = Array.from(variation.querySelectorAll('input[name^="variations"][name$="[options][]"]'))
                                 .map(input => input.value)
                                 .filter(value => value.trim() !== '');

            if (name && options.length > 0) {
                variations.push({ name, options });
            }
        });

        formData.append('variations', JSON.stringify(variations));

        console.log('FormData:', Object.fromEntries(formData));

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '{{ route('seller.update-product', $product->id) }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log('Success response:', response);
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Product updated successfully.',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '/seller/myproducts';
                    }
                });
            },
            error: function(error) {
                console.error('Error updating product:', error);
                let errorMessage = 'Something went wrong!';
                if (error.responseJSON && error.responseJSON.message) {
                    errorMessage = error.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: errorMessage,
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    // Delete image
    document.querySelectorAll('.remove-image-btn').forEach(button => {
        button.addEventListener('click', function() {
            const imageId = this.getAttribute('data-image-id');
            const imageElement = this.closest('.image-container').querySelector('.product-image');
            const fileInput = this.closest('.image-container').querySelector('input[type="file"]');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/seller/delete-image/${imageId}`,
                        method: 'DELETE',
                        success: function(response) {
                            console.log('Success response:', response);
                            Swal.fire(
                                'Deleted!',
                                'Your image has been deleted.',
                                'success'
                            ).then(() => {
                                // Remove the image element and reset the file input
                                imageElement.src = '';
                                fileInput.value = '';
                            });
                        },
                        error: function(error) {
                            console.error('Error deleting image:', error);
                            let errorMessage = 'Something went wrong!';
                            if (error.responseJSON && error.responseJSON.message) {
                                errorMessage = error.responseJSON.message;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: errorMessage,
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });
    });
});
</script>
@endsection
