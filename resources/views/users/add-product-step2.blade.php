@extends('layouts.seller')

@section('content')
<div class="product-form">
    <div class="form-section basic-info">
        <h2>Basic Information</h2>
        <form id="productForm" action="{{ route('seller.store-product') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Product Name<span class="required-asterisk">*</span></label>
                <input type="text" placeholder="Input" maxlength="100" id="productName" name="name" value="{{ request('name') }}" required>
            </div>
            <div class="form-group">
                <label>Product Description<span class="required-asterisk">*</span></label>
                <textarea id="productDescription" placeholder="Enter product description" rows="4" name="description" maxlength="1000" required></textarea>
            </div>
            <div class="form-container">
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <div class="category-path subtle-text">
                        {{ $categoryName }} > {{ $subCategoryName }} > {{ $itemName }}
                    </div>
                    <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                    <input type="hidden" name="sub_category_id" value="{{ request('sub_category_id') }}">
                    <input type="hidden" name="item_id" value="{{ request('item_id') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Brand</label>
                    <select class="brand-select" name="brand_id">
                        <option value="">Set no brand</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
    </div>
</div>

<div class="product-form">
    <div class="form-section sales-info">
        <h2>Sales Information</h2>
        <div class="form-group">
            <label for="price">Price <span class="required-asterisk">*</span></label>
            <input type="number" id="price" placeholder="Input" step="0.01" name="price" required>
        </div>
        <div class="form-group">
            <label for="stock">Stock <span class="required-asterisk">*</span></label>
            <input type="number" id="stock" placeholder="0" name="stock" required>
        </div>
        <div class="form-group">
            <label for="discount">Discount Percentage</label>
            <input type="number" id="discount" placeholder="0" min="0" max="100" name="discount_percentage">
        </div>
    </div>
</div>

<div class="product-form">
    <div class="form-section variations-info">
        <h2>Variations</h2>
        <div class="form-group">
            <label>Variations</label>
            <button type="button" class="variations-toggle">Enable Variations</button>
        </div>
        <div id="variations-container" style="display: none;">
            <div class="variation">
                <div class="variation-header">
                    <h3>Variation 1</h3>
                    <button type="button" class="remove-variation">&times;</button>
                </div>
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="variations[0][name]" placeholder="Enter Variation Name, eg: colour, etc." maxlength="14">
                </div>
                <div class="form-group">
                    <label>Options</label>
                    <div class="options-container">
                        <div class="input-container">
                            <input type="text" name="variations[0][options][]" placeholder="Enter Variation Options, eg: Red, etc." maxlength="20">
                            <button type="button" class="add-option">+</button>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Stocks</label>
                    <div class="stocks-container">
                        <div class="input-container">
                            <input type="number" name="variations[0][stocks][Red]" placeholder="Enter Stock for Red" min="0">
                        </div>
                    </div>
                </div>
                <button type="button" class="add-options">Add Options (1/20)</button>
            </div>
            <button type="button" class="add-variation">Add</button>
        </div>
    </div>
</div>

<div class="product-form">
    <div class="form-section media-management">
        <h2>Media Management</h2>
        <div class="image-upload-grid">
            <div class="image-upload-box">
                <input type="file" id="coverPhoto" accept="image/*" hidden name="images[]" required>
                <label for="coverPhoto" class="upload-label">
                    <span class="plus-icon">+</span>
                    <span>Cover Photo</span>
                </label>
            </div>
            @for ($i = 1; $i <= 8; $i++)
                <div class="image-upload-box">
                    <input type="file" id="image{{ $i }}" accept="image/*" hidden name="images[]">
                    <label for="image{{ $i }}" class="upload-label">
                        <span class="plus-icon">+</span>
                        <span>Image {{ $i }}</span>
                    </label>
                </div>
            @endfor
        </div>
    </div>
</div>

<div class="product-form">
    <div class="button-container2">
        <button type="button" class="btn-cancel">Cancel</button>
        <button type="submit" class="btn-save-publish">Save and Publish</button>
    </div>
</div>

</form>

<link rel="stylesheet" href="{{ asset('css/sellerproducts.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const variationsToggle = document.querySelector('.variations-toggle');
    const variationsContainer = document.getElementById('variations-container');
    const addVariationButton = document.querySelector('.add-variation');
    let variationCount = 1;

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
                    event.target.parentNode.appendChild(img);
                    event.target.parentNode.querySelector('.upload-label').style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        });
    });

    variationsToggle.addEventListener('click', function() {
        if (variationsContainer.style.display === 'none') {
            variationsContainer.style.display = 'block';
            this.textContent = 'Disable Variations';
        } else {
            variationsContainer.style.display = 'none';
            this.textContent = 'Enable Variations';
        }
    });

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
                <input type="text" name="variations[${variationCount - 1}][name]" placeholder="Enter Variation Name, eg: colour, etc." maxlength="14">
            </div>
            <div class="form-group">
                <label>Options</label>
                <div class="options-container">
                    <div class="input-container">
                        <input type="text" name="variations[${variationCount - 1}][options][]" placeholder="Enter Variation Options, eg: Red, etc." maxlength="20">
                        <button type="button" class="add-option">+</button>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Stocks</label>
                <div class="stocks-container">
                    <div class="input-container">
                        <input type="number" name="variations[${variationCount - 1}][stocks][Red]" placeholder="Enter Stock for Red" min="0">
                    </div>
                </div>
            </div>
            <button type="button" class="add-options">Add Options (1/20)</button>
        `;
        variationsContainer.insertBefore(newVariation, addVariationButton);
    });

    variationsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-variation')) {
            e.target.closest('.variation').remove();
        }
    });

    variationsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-option') || e.target.classList.contains('add-options')) {
            const variation = e.target.closest('.variation');
            const optionsContainer = variation.querySelector('.options-container');
            const stocksContainer = variation.querySelector('.stocks-container');
            const optionCount = optionsContainer.querySelectorAll('.input-container').length;

            if (optionCount < 20) {
                const newOption = document.createElement('div');
                newOption.classList.add('input-container');
                newOption.innerHTML = `
                    <input type="text" name="variations[${variationCount - 1}][options][]" placeholder="Enter Variation Options, eg: Red, etc." maxlength="20">
                    <button type="button" class="add-option">+</button>
                `;
                optionsContainer.appendChild(newOption);

                const newStock = document.createElement('div');
                newStock.classList.add('input-container');
                newStock.innerHTML = `
                    <input type="number" name="variations[${variationCount - 1}][stocks][Red]" placeholder="Enter Stock for Red" min="0">
                `;
                stocksContainer.appendChild(newStock);

                variation.querySelector('.add-options').textContent = `Add Options (${optionCount + 1}/20)`;
            }
        }
    });

    document.getElementById('productForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);

        const variations = [];
        document.querySelectorAll('.variation').forEach((variation, index) => {
            const name = variation.querySelector('input[name^="variations"][name$="[name]"]').value;
            const options = Array.from(variation.querySelectorAll('input[name^="variations"][name$="[options][]"]'))
                                 .map(input => input.value)
                                 .filter(value => value.trim() !== '');

            const stocks = {};
            variation.querySelectorAll('input[name^="variations"][name$="[stocks]"]').forEach(input => {
                const option = input.name.match(/\[stocks\]\[(.*?)\]/)[1];
                stocks[option] = input.value;
            });

            if (name && options.length > 0) {
                variations.push({ name, options, stocks });
            }
        });

        formData.append('variations', JSON.stringify(variations));

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '{{ route('seller.store-product') }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Product created successfully.',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '/admin/myproducts';
                    }
                });
            },
            error: function(error) {
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
});
</script>
@endsection
