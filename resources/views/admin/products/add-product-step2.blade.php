@extends('layouts.seller')

@section('content')
<!-- Basic Information Section -->
<div class="product-form">
    <div class="form-section basic-info">
        <h2>Basic Information</h2>
        <p class="subtitle">Please fill in the product details.</p>
        <form id="productForm" action="{{ route('seller.store-product') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="productName">Product Name<span class="required-asterisk">*</span></label>
                <div class="input-container">
                    <input type="text" placeholder="Input" maxlength="100" id="productName" name="name" value="{{ request('name') }}" required>
                    <span class="char-count">0/100</span>
                </div>
            </div>

            <div class="form-group">
                <label for="productDescription">Product Description<span class="required-asterisk">*</span></label>
                <div class="input-container">
                    <textarea id="productDescription" placeholder="Enter product description" rows="4" name="description" maxlength="2000" required>{{ old('description') }}</textarea>
                    <span class="char-count">0/2000</span>
                </div>
            </div>

            <div class="form-container">
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <div class="category-path subtle-text">
                        {{ $categoryName }} > {{ $subCategoryName }} > {{ $itemName }}
                        <span class="edit-icon">✎</span>
                    </div>
                    <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                    <input type="hidden" name="sub_category_id" value="{{ request('sub_category_id') }}">
                    <input type="hidden" name="item_id" value="{{ request('item_id') }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="brand_id">Brand</label>
                    <select class="brand-select" name="brand_id" id="brand_id">
                        <option value="">Set no brand</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
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
                <input type="number" id="price" placeholder="Input" step="0.01" name="price" value="{{ old('price') }}" required>
            </div>
        </div>

        <div class="form-group">
            <label for="stock">Total Stock <span class="required-asterisk">*</span></label>
            <div class="input-container">
                <input type="number" id="stock" placeholder="0" name="stocks" value="{{ old('stocks') }}">
                <small class="help-text">Total stock is automatically calculated from variations</small>
            </div>
        </div>

        <div class="form-group">
            <label for="discount">Discount Percentage</label>
            <div class="input-container">
                <input type="number" id="discount" placeholder="0" min="0" max="100" name="discount_percentage" value="{{ old('discount_percentage') }}">
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
            <div class="variation" data-index="0">
                <div class="variation-header">
                    <h3>Variation 1</h3>
                    <button type="button" class="remove-variation">&times;</button>
                </div>
                <div class="form-group">
                    <label>Name</label>
                    <div class="input-container">
                        <input type="text" name="variations[0][name]" placeholder="Enter Variation Name, eg: Color" maxlength="14">
                        <span class="char-count">0/14</span>
                    </div>
                </div>
                <div class="form-group">
                    <label>Options</label>
                    <div class="options-container">
                        <div class="option-group" data-option-index="0">
                            <div class="input-container">
                                <input type="text" name="variations[0][options][0][name]" placeholder="Enter Option Name, eg: Red" maxlength="20">
                                <span class="char-count">0/20</span>
                            </div>
                            <div class="stock-input-container">
                                <label>Stock</label>
                                <input type="number" name="variations[0][options][0][stock]" placeholder="0" min="0" class="stock-input">
                            </div>
                            <div class="sizes-container">
                                <div class="size-toggle">
                                    <label>
                                        <input type="checkbox" class="enable-sizes"> Enable Sizes
                                    </label>
                                </div>
                                <div class="size-groups" style="display: none;">
                                    <div class="size-group">
                                        <input type="text" name="variations[0][options][0][sizes][0][name]" placeholder="Size (Optional)" maxlength="10">
                                        <input type="number" name="variations[0][options][0][sizes][0][stock]" placeholder="Stock" min="0" class="size-stock-input">
                                        <button type="button" class="add-size">+</button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="add-option">+ Add Option</button>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="add-variation">Add Variation</button>
        </div>
    </div>
</div>

<!-- Media Management Section -->
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

<!-- Button Container -->
<div class="product-form">
    <div class="button-container2">
        <button type="button" class="btn-cancel">Cancel</button>
        <button type="submit" class="btn-save-publish">Save and Publish</button>
    </div>
</div>

</form>

<link rel="stylesheet" href="{{ asset('css/sellerproducts.css') }}">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

.variations-toggle {
    background: #f0f0f0;
    border: 1px solid #ddd;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
}

.variation {
    border: 1px solid #ddd;
    padding: 15px;
    margin-bottom: 15px;
    border-radius: 4px;
}

.variation-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.remove-variation,
.remove-option,
.remove-size {
    background: #ff4444;
    color: white;
    border: none;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.option-group {
    border: 1px solid #eee;
    padding: 15px;
    margin-bottom: 10px;
    border-radius: 4px;
}

.stock-input-container {
    margin: 10px 0;
}

.stock-input-container label {
    display: block;
    margin-bottom: 5px;
}

.sizes-container {
    margin-top: 10px;
}

.size-toggle {
    margin-bottom: 10px;
}

.size-groups {
    margin-left: 15px;
}

.size-group {
    display: flex;
    gap: 10px;
    margin-bottom: 8px;
    align-items: center;
}

.size-group input[type="text"] {
    width: 120px;
}

.size-group input[type="number"] {
    width: 80px;
}

.add-size {
    background: #4CAF50;
    color: white;
    border: none;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.add-option {
    background: #2196F3;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
    margin-top: 10px;
    width: 100%;
}

.add-variation {
    background: #4CAF50;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
    margin-top: 10px;
    width: 100%;
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
    const variationsToggle = document.querySelector('.variations-toggle');
    const variationsContainer = document.getElementById('variations-container');
    const addVariationButton = document.querySelector('.add-variation');
    const totalStockInput = document.getElementById('stock');
    let variationCount = 1;

    // Function to update total stock
    function updateTotalStock() {
        let totalStock = 0;
        
        // Add stock from variation options
        document.querySelectorAll('.stock-input').forEach(input => {
            if (!input.closest('.option-group').querySelector('.enable-sizes').checked) {
                totalStock += parseInt(input.value) || 0;
            }
        });

        // Add stock from sizes if enabled
        document.querySelectorAll('.size-stock-input').forEach(input => {
            if (input.closest('.sizes-container').querySelector('.enable-sizes').checked) {
                totalStock += parseInt(input.value) || 0;
            }
        });

        totalStockInput.value = totalStock;

        // Disable the total stock input if variations are enabled and stock is inputted
        const hasVariations = document.querySelectorAll('.variation').length > 0;
        const hasStockValue = totalStock > 0;

        if (hasVariations && hasStockValue) {
            totalStockInput.setAttribute('readonly', true);
        } else {
            totalStockInput.removeAttribute('readonly');
        }
    }

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

    // Variations toggle
    variationsToggle.addEventListener('click', function() {
        if (variationsContainer.style.display === 'none') {
            variationsContainer.style.display = 'block';
            this.textContent = 'Disable Variations';
        } else {
            variationsContainer.style.display = 'none';
            this.textContent = 'Enable Variations';
        }
        updateTotalStock();
    });

    // Add variation
    addVariationButton.addEventListener('click', function() {
        const newVariation = document.createElement('div');
        newVariation.classList.add('variation');
        newVariation.dataset.index = variationCount;

        newVariation.innerHTML = `
            <div class="variation-header">
                <h3>Variation ${variationCount + 1}</h3>
                <button type="button" class="remove-variation">&times;</button>
            </div>
            <div class="form-group">
                <label>Name</label>
                <div class="input-container">
                    <input type="text" name="variations[${variationCount}][name]" placeholder="Enter Variation Name, eg: Color" maxlength="14">
                    <span class="char-count">0/14</span>
                </div>
            </div>
            <div class="form-group">
                <label>Options</label>
                <div class="options-container">
                    <div class="option-group" data-option-index="0">
                        <div class="input-container">
                            <input type="text" name="variations[${variationCount}][options][0][name]" placeholder="Enter Option Name, eg: Red" maxlength="20">
                            <span class="char-count">0/20</span>
                        </div>
                        <div class="stock-input-container">
                            <label>Stock</label>
                            <input type="number" name="variations[${variationCount}][options][0][stock]" placeholder="0" min="0" class="stock-input">
                        </div>
                        <div class="sizes-container">
                            <div class="size-toggle">
                                <label>
                                    <input type="checkbox" class="enable-sizes"> Enable Sizes
                                </label>
                            </div>
                            <div class="size-groups" style="display: none;">
                                <div class="size-group">
                                    <input type="text" name="variations[${variationCount}][options][0][sizes][0][name]" placeholder="Size (Optional)" maxlength="10">
                                    <input type="number" name="variations[${variationCount}][options][0][sizes][0][stock]" placeholder="Stock" min="0" class="size-stock-input">
                                    <button type="button" class="add-size">+</button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="add-option">+ Add Option</button>
                    </div>
                </div>
            </div>
        `;

        variationsContainer.insertBefore(newVariation, addVariationButton);
        variationCount++;

        // Add event listeners for new inputs
        newVariation.querySelectorAll('.stock-input, .size-stock-input').forEach(input => {
            input.addEventListener('input', updateTotalStock);
        });

        // Add event listener for size toggle
        newVariation.querySelectorAll('.enable-sizes').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const sizeGroups = this.closest('.sizes-container').querySelector('.size-groups');
                const stockInput = this.closest('.option-group').querySelector('.stock-input');
                
                sizeGroups.style.display = this.checked ? 'block' : 'none';
                stockInput.disabled = this.checked;
                if (this.checked) {
                    stockInput.value = '';
                }
                updateTotalStock();
            });
        });

        updateTotalStock();
    });

    // Remove variation
    variationsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-variation')) {
            e.target.closest('.variation').remove();
            updateVariationIndexes();
            updateTotalStock();
        }
    });

    // Add option
    variationsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-option')) {
            const variation = e.target.closest('.variation');
            const optionsContainer = variation.querySelector('.options-container');
            const variationIndex = variation.dataset.index;
            const optionIndex = optionsContainer.children.length;

            const newOptionGroup = document.createElement('div');
            newOptionGroup.classList.add('option-group');
            newOptionGroup.dataset.optionIndex = optionIndex;

            newOptionGroup.innerHTML = `
                <div class="input-container">
                    <input type="text" name="variations[${variationIndex}][options][${optionIndex}][name]" placeholder="Enter Option Name, eg: Red" maxlength="20">
                    <span class="char-count">0/20</span>
                </div>
                <div class="stock-input-container">
                    <label>Stock</label>
                    <input type="number" name="variations[${variationIndex}][options][${optionIndex}][stock]" placeholder="0" min="0" class="stock-input">
                </div>
                <div class="sizes-container">
                    <div class="size-toggle">
                        <label>
                            <input type="checkbox" class="enable-sizes"> Enable Sizes
                        </label>
                    </div>
                    <div class="size-groups" style="display: none;">
                        <div class="size-group">
                            <input type="text" name="variations[${variationIndex}][options][${optionIndex}][sizes][0][name]" placeholder="Size (Optional)" maxlength="10">
                            <input type="number" name="variations[${variationIndex}][options][${optionIndex}][sizes][0][stock]" placeholder="Stock" min="0" class="size-stock-input">
                            <button type="button" class="add-size">+</button>
                        </div>
                    </div>
                </div>
                <button type="button" class="remove-option">&times;</button>
            `;

            optionsContainer.appendChild(newOptionGroup);

            // Add event listeners for new inputs
            newOptionGroup.querySelectorAll('.stock-input, .size-stock-input').forEach(input => {
                input.addEventListener('input', updateTotalStock);
            });

            // Add event listener for size toggle
            newOptionGroup.querySelector('.enable-sizes').addEventListener('change', function() {
                const sizeGroups = this.closest('.sizes-container').querySelector('.size-groups');
                const stockInput = this.closest('.option-group').querySelector('.stock-input');
                
                sizeGroups.style.display = this.checked ? 'block' : 'none';
                stockInput.disabled = this.checked;
                if (this.checked) {
                    stockInput.value = '';
                }
                updateTotalStock();
            });

            updateTotalStock();
        }
    });

    // Add size
    variationsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-size')) {
            const optionGroup = e.target.closest('.option-group');
            const sizesContainer = optionGroup.querySelector('.size-groups');
            const variation = optionGroup.closest('.variation');
            const variationIndex = variation.dataset.index;
            const optionIndex = optionGroup.dataset.optionIndex;
            const sizeIndex = sizesContainer.children.length;

            const newSizeGroup = document.createElement('div');
            newSizeGroup.classList.add('size-group');
            newSizeGroup.innerHTML = `
                <input type="text" name="variations[${variationIndex}][options][${optionIndex}][sizes][${sizeIndex}][name]" placeholder="Size (Optional)" maxlength="10">
                <input type="number" name="variations[${variationIndex}][options][${optionIndex}][sizes][${sizeIndex}][stock]" placeholder="Stock" min="0" class="size-stock-input">
                <button type="button" class="remove-size">&times;</button>
            `;

            sizesContainer.appendChild(newSizeGroup);

            // Add event listener for new stock input
            newSizeGroup.querySelector('.size-stock-input').addEventListener('input', updateTotalStock);

            updateTotalStock();
        }
    });

    // Remove size
    variationsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-size')) {
            e.target.closest('.size-group').remove();
            updateTotalStock();
        }
    });

    // Remove option
    variationsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-option')) {
            e.target.closest('.option-group').remove();
            updateOptionIndexes(e.target.closest('.variation'));
            updateTotalStock();
        }
    });

    // Update variation indexes
    function updateVariationIndexes() {
        document.querySelectorAll('.variation').forEach((variation, index) => {
            variation.dataset.index = index;
            variation.querySelector('h3').textContent = `Variation ${index + 1}`;
            updateInputNames(variation, index);
        });
        variationCount = document.querySelectorAll('.variation').length;
    }

    // Update option indexes
    function updateOptionIndexes(variation) {
        const variationIndex = variation.dataset.index;
        variation.querySelectorAll('.option-group').forEach((option, optionIndex) => {
            option.dataset.optionIndex = optionIndex;
            updateOptionInputNames(option, variationIndex, optionIndex);
        });
    }

    // Update input names
    function updateInputNames(variation, variationIndex) {
        variation.querySelector('input[name^="variations"][name$="[name]"]').name = `variations[${variationIndex}][name]`;
        variation.querySelectorAll('.option-group').forEach((option, optionIndex) => {
            updateOptionInputNames(option, variationIndex, optionIndex);
        });
    }

    // Update option input names
    function updateOptionInputNames(option, variationIndex, optionIndex) {
        option.querySelector('input[name$="[name]"]').name = `variations[${variationIndex}][options][${optionIndex}][name]`;
        option.querySelector('.stock-input').name = `variations[${variationIndex}][options][${optionIndex}][stock]`;
        option.querySelectorAll('.size-group').forEach((size, sizeIndex) => {
            const inputs = size.querySelectorAll('input');
            inputs[0].name = `variations[${variationIndex}][options][${optionIndex}][sizes][${sizeIndex}][name]`;
            inputs[1].name = `variations[${variationIndex}][options][${optionIndex}][sizes][${sizeIndex}][stock]`;
        });
    }

    // Add event listeners for initial inputs
    document.querySelectorAll('.stock-input, .size-stock-input').forEach(input => {
        input.addEventListener('input', updateTotalStock);
    });

    // Add event listeners for initial size toggles
    document.querySelectorAll('.enable-sizes').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const sizeGroups = this.closest('.sizes-container').querySelector('.size-groups');
            const stockInput = this.closest('.option-group').querySelector('.stock-input');
            
            sizeGroups.style.display = this.checked ? 'block' : 'none';
            stockInput.disabled = this.checked;
            if (this.checked) {
                stockInput.value = '';
            }
            updateTotalStock();
        });
    });

    // Character count for inputs
    document.addEventListener('input', function(e) {
        if (e.target.maxLength) {
            const charCount = e.target.nextElementSibling;
            if (charCount && charCount.classList.contains('char-count')) {
                charCount.textContent = `${e.target.value.length}/${e.target.maxLength}`;
            }
        }
    });

    // Form submission
    document.getElementById('productForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);

        // Prepare variations data
        const variations = [];
        document.querySelectorAll('.variation').forEach((variation, varIndex) => {
            const variationData = {
                name: variation.querySelector(`input[name="variations[${varIndex}][name]"]`).value,
                options: []
            };

            variation.querySelectorAll('.option-group').forEach((optionGroup, optIndex) => {
                const option = {
                    name: optionGroup.querySelector(`input[name="variations[${varIndex}][options][${optIndex}][name]"]`).value,
                };

                const enableSizes = optionGroup.querySelector('.enable-sizes').checked;
                
                if (enableSizes) {
                    option.sizes = [];
                    optionGroup.querySelectorAll('.size-group').forEach((sizeGroup) => {
                        const nameInput = sizeGroup.querySelector('input[type="text"]');
                        const stockInput = sizeGroup.querySelector('input[type="number"]');

                        if (nameInput.value.trim() !== '') {
                            option.sizes.push({
                                name: nameInput.value,
                                stock: parseInt(stockInput.value) || 0
                            });
                        }
                    });
                } else {
                    option.stock = parseInt(optionGroup.querySelector('.stock-input').value) || 0;
                }

                if (option.name && (option.stock > 0 || (option.sizes && option.sizes.length > 0))) {
                    variationData.options.push(option);
                }
            });

            if (variationData.name && variationData.options.length > 0) {
                variations.push(variationData);
            }
        });

        formData.set('variations', JSON.stringify(variations));

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
                console.error('Error:', error);
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