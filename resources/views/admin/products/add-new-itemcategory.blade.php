@extends('layouts.seller')

@section('content')
    <div class="tabs">
        <a href="#" class="tab active" data-target="add-itemcategory">Add Item Category</a>
    </div>

    <!-- Item Category Tab Content -->
    <div id="add-itemcategory" class="tab-content active">
        <div class="search-filters">
            <div class="filter-group">
                <div class="filter">
                    <label>Item Category Name</label>
                    <input type="text" placeholder="Search by name" class="filter-input" id="itemCategoryNameFilter">
                </div>
                <div class="filter">
                    <label>Filter SubCategory</label>
                    <input type="text" placeholder="Search by name" class="filter-input" id="categoryNameFilter">
                </div>
            </div>

            <div class="products-header">
                <div class="products-count">
                    <h2>{{ isset($items) ? $items->count() : 0 }} Item Category</h2>
                    <span class="badge">{{ isset($items) ? $items->count() : 0 }} / 3,000</span>
                </div>
                <div class="products-actions">
                    <button class="add-product-button no-underline" id="createItemCategoryButton">
                        <i class='bx bx-plus'></i>
                        Create New Item Category
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
                            <th><input type="checkbox" id="selectAllItemCategories"></th>
                            <th>Item Category Name</th>
                            <th>SubCategory</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody id="itemCategoriesTableBody">
                        @if(isset($items))
                            @foreach($items as $item)
                                <tr data-itemcategory="{{ $item->name }}" data-subcategory="{{ $item->subCategory->name }}" data-category="{{ $item->subCategory->category->name }}">
                                    <td><input type="checkbox" class="itemcategory-checkbox" value="{{ $item->id }}"></td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->subCategory->name }}</td>
                                    <td class="action-buttons">
                                        <button class="edit-button btn-orange"
                                                onclick="editItemCategory({{ $item->id }}, '{{ $item->name }}', {{ $item->sub_category_id }})">
                                            Edit
                                        </button>
                                        <button class="delete-button btn-red"
                                                onclick="confirmDeleteItemCategory({{ $item->id }})">
                                            Delete
                                        </button>
                                        <form action="{{ route('admin.products.itemcategories.delete', $item->id) }}"
                                              method="POST"
                                              id="delete-itemcategory-form-{{ $item->id }}"
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

        <!-- Add Item Category Modal Form -->
        <div id="addItemCategoryForm" class="category-form" style="display:none;">
            <form action="{{ route('admin.products.itemcategories.add') }}"
                  method="POST"
                  class="category-form-container">
                @csrf
                <div class="form-group">
                    <label for="item_category">Item Category Name</label>
                    <input type="text"
                           name="item_category"
                           id="item_category"
                           class="form-control @error('item_category') is-invalid @enderror"
                           required>
                    @error('item_category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="sub_category_id">SubCategory</label>
                    <select name="sub_category_id"
                            id="sub_category_id"
                            class="form-control @error('sub_category_id') is-invalid @enderror"
                            required>
                        <option value="">Select SubCategory</option>
                        @foreach($subCategories as $subCategory)
                            <option value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                        @endforeach
                    </select>
                    @error('sub_category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save Item Category</button>
                    <button type="button" class="btn btn-secondary" onclick="hideAddItemCategoryForm()">Cancel</button>
                </div>
            </form>
        </div>

        <!-- Edit Item Category Modal Form -->
        <div id="editItemCategoryForm" class="category-form" style="display:none;">
            <form id="editItemCategoryFormElement" method="POST" class="category-form-container">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_itemcategory_id" name="itemcategory_id">

                <div class="form-group">
                    <label for="edit_item_category">Item Category Name</label>
                    <input type="text"
                           name="item_category"
                           id="edit_item_category"
                           class="form-control @error('item_category') is-invalid @enderror"
                           required>
                    @error('item_category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="edit_sub_category_id">SubCategory</label>
                    <select name="sub_category_id"
                            id="edit_sub_category_id"
                            class="form-control @error('sub_category_id') is-invalid @enderror"
                            required>
                        <option value="">Select SubCategory</option>
                        @foreach($subCategories as $subCategory)
                            <option value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                        @endforeach
                    </select>
                    @error('sub_category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Update Item Category</button>
                    <button type="button" class="btn btn-secondary" onclick="hideEditItemCategoryForm()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Item Category handling
            const createItemCategoryButton = document.getElementById('createItemCategoryButton');
            const categoryNameFilter = document.getElementById('categoryNameFilter');
            const itemCategoryNameFilter = document.getElementById('itemCategoryNameFilter');
            const selectAllItemCategories = document.getElementById('selectAllItemCategories');

            createItemCategoryButton.addEventListener('click', function() {
                document.getElementById('addItemCategoryForm').style.display = 'block';
            });

            categoryNameFilter.addEventListener('input', function(e) {
                filterTable();
            });

            itemCategoryNameFilter.addEventListener('input', function(e) {
                filterTable();
            });

            selectAllItemCategories.addEventListener('change', function() {
                document.querySelectorAll('.itemcategory-checkbox').forEach(checkbox => {
                    checkbox.checked = selectAllItemCategories.checked;
                });
            });

            function filterTable() {
                const categorySearchTerm = categoryNameFilter.value.toLowerCase();
                const itemCategorySearchTerm = itemCategoryNameFilter.value.toLowerCase();
                const rows = document.querySelectorAll('#itemCategoriesTableBody tr');
                rows.forEach(row => {
                    const subCategoryName = row.getAttribute('data-subcategory').toLowerCase();
                    const itemCategoryName = row.getAttribute('data-itemcategory').toLowerCase();
                    const subCategoryMatch = subCategoryName.includes(categorySearchTerm);
                    const itemCategoryMatch = itemCategoryName.includes(itemCategorySearchTerm);
                    row.style.display = subCategoryMatch && itemCategoryMatch ? '' : 'none';
                });
            }
        });

        // Item Category functions
        function hideAddItemCategoryForm() {
            document.getElementById('addItemCategoryForm').style.display = 'none';
            document.getElementById('item_category').value = '';
            document.getElementById('sub_category_id').value = '';
        }

        function hideEditItemCategoryForm() {
            document.getElementById('editItemCategoryForm').style.display = 'none';
            document.getElementById('edit_item_category').value = '';
            document.getElementById('edit_sub_category_id').value = '';
        }

        function editItemCategory(id, name, subCategoryId) {
            const editForm = document.getElementById('editItemCategoryFormElement');
            const editItemCategoryInput = document.getElementById('edit_item_category');
            const editItemCategoryIdInput = document.getElementById('edit_itemcategory_id');
            const editSubCategoryIdSelect = document.getElementById('edit_sub_category_id');

            editItemCategoryIdInput.value = id;
            editItemCategoryInput.value = name;
            editSubCategoryIdSelect.value = subCategoryId;
            editForm.action = `/admin/products/itemcategories/${id}`;
            document.getElementById('editItemCategoryForm').style.display = 'block';
        }

        function confirmDeleteItemCategory(id) {
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
                    document.getElementById(`delete-itemcategory-form-${id}`).submit();
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
