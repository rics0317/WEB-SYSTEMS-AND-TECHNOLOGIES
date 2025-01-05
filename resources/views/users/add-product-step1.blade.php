@extends('layouts.seller')

@section('content')
<!-- Basic Information Section -->
<div class="product-form">
    <div class="form-section basic-info">
        <h2>Add a New Product</h2>
        <p class="subtitle">Please input the right category for your product</p>
        <form id="productForm" action="{{ route('seller.store-product') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Product Name<span class="required-asterisk">*</span></label>
                <div class="input-container">
                    <input type="text" placeholder="Input" maxlength="100" id="productName" name="name" required>
                    <span class="char-count">0/100</span>
                </div>
            </div>

            <div class="form-group">
                <div class="category-selection">
                    <input type="text" placeholder="Search Categories" class="search-input" id="categorySearch">
                    <div class="categories">
                        <div class="category-column">
                            <ul id="categoryList">
                                @foreach($categories as $category)
                                    <li data-id="{{ $category->id }}">{{ $category->name }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="subcategory-column">
                            <ul id="subCategoryList">
                                <!-- Subcategories will be populated here -->
                            </ul>
                        </div>
                        <div class="item-column">
                            <ul id="itemList">
                                <!-- Items will be populated here -->
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="selected-category">
                    The currently selected: <span id="selected-category">None</span>
                    <input type="hidden" name="category_id" id="category_id">
                    <input type="hidden" name="sub_category_id" id="sub_category_id">
                    <input type="hidden" name="item_id" id="item_id">
                </div>

                <!-- Button Container -->
                <div class="button-container">
                    <button type="button" class="btn-next disabled" id="nextButton">Next</button>
                </div>
            </div>
        </form>
    </div>
</div>

<link rel="stylesheet" href="{{ asset('css/sellerproducts.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySearch = document.getElementById('categorySearch');
    const categoryList = document.getElementById('categoryList');
    const subCategoryList = document.getElementById('subCategoryList');
    const itemList = document.getElementById('itemList');
    const selectedCategory = document.getElementById('selected-category');
    const categoryIdInput = document.getElementById('category_id');
    const subCategoryIdInput = document.getElementById('sub_category_id');
    const itemIdInput = document.getElementById('item_id');
    const productNameInput = document.getElementById('productName');
    const charCount = document.querySelector('.char-count');
    const nextButton = document.getElementById('nextButton');

    productNameInput.addEventListener('input', function() {
        updateCharCount();
        validateForm();
    });

    categorySearch.addEventListener('input', function() {
        const query = categorySearch.value;
        if (query.length >= 3) {
            fetchCategories(query);
        }
    });

    categoryList.addEventListener('click', function(e) {
        if (e.target.tagName === 'LI') {
            const categoryId = e.target.getAttribute('data-id');
            const categoryName = e.target.textContent;
            selectedCategory.textContent = categoryName;
            categoryIdInput.value = categoryId;

            // Fetch subcategories based on the selected category
            fetchSubCategories(categoryId);

            // Clear subcategory and item lists
            subCategoryList.innerHTML = '';
            itemList.innerHTML = '';
            subCategoryIdInput.value = '';
            itemIdInput.value = '';

            // Highlight the selected category
            document.querySelectorAll('#categoryList li').forEach(li => li.classList.remove('active'));
            e.target.classList.add('active');

            validateForm();
        }
    });

    subCategoryList.addEventListener('click', function(e) {
        if (e.target.tagName === 'LI') {
            const subCategoryId = e.target.getAttribute('data-id');
            const subCategoryName = e.target.textContent;
            selectedCategory.textContent = `${selectedCategory.textContent} > ${subCategoryName}`;
            subCategoryIdInput.value = subCategoryId;

            // Fetch items based on the selected subcategory
            fetchItems(subCategoryId);

            // Clear item list
            itemList.innerHTML = '';
            itemIdInput.value = '';

            // Highlight the selected subcategory
            document.querySelectorAll('#subCategoryList li').forEach(li => li.classList.remove('active'));
            e.target.classList.add('active');

            validateForm();
        }
    });

    itemList.addEventListener('click', function(e) {
        if (e.target.tagName === 'LI') {
            const itemId = e.target.getAttribute('data-id');
            const itemName = e.target.textContent;
            selectedCategory.textContent = `${selectedCategory.textContent} > ${itemName}`;
            itemIdInput.value = itemId;

            // Highlight the selected item
            document.querySelectorAll('#itemList li').forEach(li => li.classList.remove('active'));
            e.target.classList.add('active');

            validateForm();
        }
    });

    nextButton.addEventListener('click', function() {
        if (!nextButton.classList.contains('disabled')) {
            const productName = productNameInput.value;
            const categoryId = categoryIdInput.value;
            const subCategoryId = subCategoryIdInput.value;
            const itemId = itemIdInput.value;

            const url = `{{ route('seller.add-product-step2') }}?name=${encodeURIComponent(productName)}&category_id=${categoryId}&sub_category_id=${subCategoryId}&item_id=${itemId}`;
            window.location.href = url;
        }
    });

    function updateCharCount() {
        const currentLength = productNameInput.value.length;
        const maxLength = productNameInput.getAttribute('maxlength');
        charCount.textContent = `${currentLength}/${maxLength}`;
    }

    function fetchCategories(query) {
        $.ajax({
            url: '{{ route('seller.search-categories') }}',
            method: 'GET',
            data: { query: query },
            success: function(response) {
                categoryList.innerHTML = '';
                response.categories.forEach(category => {
                    const li = document.createElement('li');
                    li.setAttribute('data-id', category.id);
                    li.textContent = category.name;
                    categoryList.appendChild(li);
                });
            },
            error: function(error) {
                console.error('Error fetching categories:', error);
            }
        });
    }

    function fetchSubCategories(categoryId) {
        $.ajax({
            url: '{{ route('seller.search-subcategories') }}',
            method: 'GET',
            data: { category_id: categoryId },
            success: function(response) {
                subCategoryList.innerHTML = '';
                response.subcategories.forEach(subcategory => {
                    const li = document.createElement('li');
                    li.setAttribute('data-id', subcategory.id);
                    li.textContent = subcategory.name;
                    subCategoryList.appendChild(li);
                });
            },
            error: function(error) {
                console.error('Error fetching subcategories:', error);
            }
        });
    }

    function fetchItems(subCategoryId) {
        $.ajax({
            url: '{{ route('seller.search-items') }}',
            method: 'GET',
            data: { sub_category_id: subCategoryId },
            success: function(response) {
                itemList.innerHTML = '';
                response.items.forEach(item => {
                    const li = document.createElement('li');
                    li.setAttribute('data-id', item.id);
                    li.textContent = item.name;
                    itemList.appendChild(li);
                });
            },
            error: function(error) {
                console.error('Error fetching items:', error);
            }
        });
    }

    function validateForm() {
        const isProductNameFilled = productNameInput.value.trim() !== '';
        const isCategorySelected = categoryIdInput.value.trim() !== '';
        const isSubCategorySelected = subCategoryIdInput.value.trim() !== '';
        const isItemSelected = itemIdInput.value.trim() !== '';

        if (isProductNameFilled && isCategorySelected && isSubCategorySelected && isItemSelected) {
            nextButton.classList.remove('disabled');
        } else {
            nextButton.classList.add('disabled');
        }
    }
});
</script>
@endsection
