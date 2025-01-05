@extends('layouts.seller')

@section('content')
    <div class="tabs">
        <a href="#" class="tab active" data-target="add-subcategory">Add SubCategory</a>
    </div>

    <!-- SubCategory Tab Content -->
    <div id="add-subcategory" class="tab-content active">
        <div class="search-filters">
            <div class="filter-group">
                <div class="filter">
                    <label>SubCategory Name</label>
                    <input type="text" placeholder="Search by name" class="filter-input" id="subCategoryNameFilter">
                </div>
                <div class="filter">
                    <label>Filter ParentCategory</label>
                    <input type="text" placeholder="Search by name" class="filter-input" id="categoryNameFilter">
                </div>
            </div>

            <div class="products-header">
                <div class="products-count">
                    <h2>{{ isset($subCategories) ? $subCategories->count() : 0 }} SubCategory</h2>
                    <span class="badge">{{ isset($subCategories) ? $subCategories->count() : 0 }} / 3,000</span>
                </div>
                <div class="products-actions">
                    <button class="add-product-button no-underline" id="createSubCategoryButton">
                        <i class='bx bx-plus'></i>
                        Create New SubCategory
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
                            <th><input type="checkbox" id="selectAllSubCategories"></th>
                            <th>SubCategory Name</th>
                            <th>Parent Category</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody id="subCategoriesTableBody">
                        @if(isset($subCategories))
                            @foreach($subCategories as $subCategory)
                                <tr data-category="{{ $subCategory->category->name }}" data-subcategory="{{ $subCategory->name }}">
                                    <td><input type="checkbox" class="subcategory-checkbox" value="{{ $subCategory->id }}"></td>
                                    <td>{{ $subCategory->name }}</td>
                                    <td>{{ $subCategory->category->name }}</td>
                                    <td class="action-buttons">
                                        <button class="edit-button btn-orange"
                                                onclick="editSubCategory({{ $subCategory->id }}, '{{ $subCategory->name }}', {{ $subCategory->category_id }})">
                                            Edit
                                        </button>
                                        <button class="delete-button btn-red"
                                                onclick="confirmDeleteSubCategory({{ $subCategory->id }})">
                                            Delete
                                        </button>
                                        <form action="{{ route('admin.products.subcategories.delete', $subCategory->id) }}"
                                              method="POST"
                                              id="delete-subcategory-form-{{ $subCategory->id }}"
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

        <!-- Add SubCategory Modal Form -->
        <div id="addSubCategoryForm" class="category-form" style="display:none;">
            <form action="{{ route('admin.products.subcategories.add') }}"
                  method="POST"
                  class="category-form-container">
                @csrf
                <div class="form-group">
                    <label for="sub_category">SubCategory Name</label>
                    <input type="text"
                           name="sub_category"
                           id="sub_category"
                           class="form-control @error('sub_category') is-invalid @enderror"
                           required>
                    @error('sub_category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="category_id">Parent Category</label>
                    <select name="category_id"
                            id="category_id"
                            class="form-control @error('category_id') is-invalid @enderror"
                            required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save SubCategory</button>
                    <button type="button" class="btn btn-secondary" onclick="hideAddSubCategoryForm()">Cancel</button>
                </div>
            </form>
        </div>

        <!-- Edit SubCategory Modal Form -->
        <div id="editSubCategoryForm" class="category-form" style="display:none;">
            <form id="editSubCategoryFormElement" method="POST" class="category-form-container">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_subcategory_id" name="subcategory_id">

                <div class="form-group">
                    <label for="edit_sub_category">SubCategory Name</label>
                    <input type="text"
                           name="sub_category"
                           id="edit_sub_category"
                           class="form-control @error('sub_category') is-invalid @enderror"
                           required>
                    @error('sub_category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="edit_category_id">Parent Category</label>
                    <select name="category_id"
                            id="edit_category_id"
                            class="form-control @error('category_id') is-invalid @enderror"
                            required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Update SubCategory</button>
                    <button type="button" class="btn btn-secondary" onclick="hideEditSubCategoryForm()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // SubCategory handling
            const createSubCategoryButton = document.getElementById('createSubCategoryButton');
            const categoryNameFilter = document.getElementById('categoryNameFilter');
            const subCategoryNameFilter = document.getElementById('subCategoryNameFilter');
            const selectAllSubCategories = document.getElementById('selectAllSubCategories');

            createSubCategoryButton.addEventListener('click', function() {
                document.getElementById('addSubCategoryForm').style.display = 'block';
            });

            categoryNameFilter.addEventListener('input', function(e) {
                filterTable();
            });

            subCategoryNameFilter.addEventListener('input', function(e) {
                filterTable();
            });

            selectAllSubCategories.addEventListener('change', function() {
                document.querySelectorAll('.subcategory-checkbox').forEach(checkbox => {
                    checkbox.checked = selectAllSubCategories.checked;
                });
            });

            function filterTable() {
                const categorySearchTerm = categoryNameFilter.value.toLowerCase();
                const subCategorySearchTerm = subCategoryNameFilter.value.toLowerCase();
                const rows = document.querySelectorAll('#subCategoriesTableBody tr');
                rows.forEach(row => {
                    const categoryName = row.getAttribute('data-category').toLowerCase();
                    const subcategoryName = row.getAttribute('data-subcategory').toLowerCase();
                    const categoryMatch = categoryName.includes(categorySearchTerm);
                    const subCategoryMatch = subcategoryName.includes(subCategorySearchTerm);
                    row.style.display = categoryMatch && subCategoryMatch ? '' : 'none';
                });
            }
        });

        // SubCategory functions
        function hideAddSubCategoryForm() {
            document.getElementById('addSubCategoryForm').style.display = 'none';
            document.getElementById('sub_category').value = '';
            document.getElementById('category_id').value = '';
        }

        function hideEditSubCategoryForm() {
            document.getElementById('editSubCategoryForm').style.display = 'none';
            document.getElementById('edit_sub_category').value = '';
            document.getElementById('edit_category_id').value = '';
        }

        function editSubCategory(id, name, categoryId) {
            const editForm = document.getElementById('editSubCategoryFormElement');
            const editSubCategoryInput = document.getElementById('edit_sub_category');
            const editSubCategoryIdInput = document.getElementById('edit_subcategory_id');
            const editCategoryIdSelect = document.getElementById('edit_category_id');

            editSubCategoryIdInput.value = id;
            editSubCategoryInput.value = name;
            editCategoryIdSelect.value = categoryId;
            editForm.action = `/admin/products/subcategories/${id}`;
            document.getElementById('editSubCategoryForm').style.display = 'block';
        }

        function confirmDeleteSubCategory(id) {
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
                    document.getElementById(`delete-subcategory-form-${id}`).submit();
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
