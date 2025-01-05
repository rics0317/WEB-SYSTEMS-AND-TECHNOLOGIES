@extends('layouts.seller')

@section('content')
    <div class="tabs">
        <a href="#" class="tab active" data-target="add-category">Add Category</a>
    </div>

    <!-- Category Tab Content -->
    <div id="add-category" class="tab-content active">
        <div class="search-filters">
            <div class="filter-group">
                <div class="filter">
                    <label>Category Name</label>
                    <input type="text" placeholder="Search by name" class="filter-input" id="categoryNameFilter">
                </div>
            </div>

            <div class="products-header">
                <div class="products-count">
                    <h2>{{ isset($categories) ? $categories->count() : 0 }} Category</h2>
                    <span class="badge">{{ isset($categories) ? $categories->count() : 0 }} / 3,000</span>
                </div>
                <div class="products-actions">
                    <button class="add-product-button no-underline" id="createCategoryButton">
                        <i class='bx bx-plus'></i>
                        Create New Category
                    </button>
                    <button class="batch-button">
                        Batch Tools
                        <i class='bx bx-chevron-down'></i>
                    </button>
                    <button class="view-button">
                        <i class='bx bx-list-ul'></i>
                    </button>
                    <button class="grid-button">
                        <i class='bx bx-grid-alt'></i>
                    </button>
                </div>
            </div>

            <div class="products-table">
                <table>
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAllCategories"></th>
                            <th>Category Name</th>
                            <th>Category Image ↑</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody id="categoriesTableBody">
                        @if(isset($categories))
                            @foreach($categories as $category)
                                <tr data-category="{{ $category->name }}">
                                    <td><input type="checkbox" class="category-checkbox" value="{{ $category->id }}"></td>
                                    <td>{{ $category->name }}</td>
                                    <td>
                                        @if($category->category_image)
                                            <img src="{{ asset('storage/' . $category->category_image) }}"
                                                 alt="{{ $category->name }}"
                                                 style="width:50px;height:50px;"
                                                 class="category-image">
                                        @else
                                            No Image
                                        @endif
                                    </td>
                                    <td class="action-buttons">
                                        <button class="edit-button btn-orange"
                                                onclick="editCategory({{ $category->id }}, '{{ $category->name }}')">
                                            Edit
                                        </button>
                                        <button class="delete-button btn-red"
                                                onclick="confirmDelete({{ $category->id }})">
                                            Delete
                                        </button>
                                        <form action="{{ route('admin.products.categories.delete', $category->id) }}"
                                              method="POST"
                                              id="delete-form-{{ $category->id }}"
                                              class="delete-form"
                                              style="display:none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Category Modal Form -->
        <div id="addCategoryForm" class="category-form" style="display:none;">
            <form action="{{ route('admin.products.categories.add') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="category-form-container">
                @csrf
                <div class="form-group">
                    <label for="category">Category Name</label>
                    <input type="text"
                           name="category"
                           id="category"
                           class="form-control @error('category') is-invalid @enderror"
                           required>
                    @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="category_image">Category Image</label>
                    <input type="file"
                           name="category_image"
                           id="category_image"
                           class="form-control @error('category_image') is-invalid @enderror"
                           accept="image/*">
                    @error('category_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div id="imagePreview" class="mt-2"></div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save Category</button>
                    <button type="button" class="btn btn-secondary" onclick="hideAddForm()">Cancel</button>
                </div>
            </form>
        </div>

        <!-- Edit Category Modal Form -->
        <div id="editCategoryForm" class="category-form" style="display:none;">
            <form id="editCategoryFormElement" method="POST" enctype="multipart/form-data" class="category-form-container">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_category_id" name="category_id">
                <div class="form-group">
                    <label for="edit_category">Category Name</label>
                    <input type="text"
                           name="category"
                           id="edit_category"
                           class="form-control"
                           required>
                </div>

                <div class="form-group">
                    <label for="edit_category_image">Category Image</label>
                    <input type="file"
                           name="category_image"
                           id="edit_category_image"
                           class="form-control"
                           accept="image/*">
                    <div id="editImagePreview" class="mt-2"></div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Update Category</button>
                    <button type="button" class="btn btn-secondary" onclick="hideEditForm()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Category handling
            const createCategoryButton = document.getElementById('createCategoryButton');
            const categoryNameFilter = document.getElementById('categoryNameFilter');
            const selectAllCategories = document.getElementById('selectAllCategories');

            createCategoryButton.addEventListener('click', function() {
                document.getElementById('addCategoryForm').style.display = 'block';
            });

            categoryNameFilter.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const rows = document.querySelectorAll('#categoriesTableBody tr');
                rows.forEach(row => {
                    const categoryName = row.getAttribute('data-category').toLowerCase();
                    row.style.display = categoryName.includes(searchTerm) ? '' : 'none';
                });
            });

            selectAllCategories.addEventListener('change', function() {
                document.querySelectorAll('.category-checkbox').forEach(checkbox => {
                    checkbox.checked = selectAllCategories.checked;
                });
            });

            // Image preview handling
            const categoryImageInput = document.getElementById('category_image');
            const imagePreview = document.getElementById('imagePreview');
            const editCategoryImageInput = document.getElementById('edit_category_image');
            const editImagePreview = document.getElementById('editImagePreview');

            function handleImagePreview(input, previewElement) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewElement.innerHTML = `
                            <img src="${e.target.result}"
                                 alt="Preview"
                                 style="max-width: 200px; max-height: 200px; object-fit: cover; border-radius: 4px;">
                        `;
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            categoryImageInput.addEventListener('change', function() {
                handleImagePreview(this, imagePreview);
            });

            editCategoryImageInput.addEventListener('change', function() {
                handleImagePreview(this, editImagePreview);
            });
        });

        // Category functions
        function hideAddForm() {
            document.getElementById('addCategoryForm').style.display = 'none';
            document.getElementById('category').value = '';
            document.getElementById('category_image').value = '';
            document.getElementById('imagePreview').innerHTML = '';
        }

        function hideEditForm() {
            document.getElementById('editCategoryForm').style.display = 'none';
            document.getElementById('edit_category').value = '';
            document.getElementById('edit_category_image').value = '';
            document.getElementById('editImagePreview').innerHTML = '';
        }

        function editCategory(id, name) {
            const editForm = document.getElementById('editCategoryFormElement');
            const editCategoryInput = document.getElementById('edit_category');
            const editCategoryIdInput = document.getElementById('edit_category_id');

            editCategoryIdInput.value = id;
            editCategoryInput.value = name;
            editForm.action = `/admin/products/categories/${id}`;
            document.getElementById('editCategoryForm').style.display = 'block';
        }

        function confirmDelete(id) {
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
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        }
    </script>

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
            });
        </script>
    @endif

    @if($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                html: '<ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
            });
        </script>
    @endif
@endsection
