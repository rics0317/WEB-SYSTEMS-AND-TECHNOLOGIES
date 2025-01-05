@extends('layouts.seller')

@section('content')
    <div class="tabs">
        <a href="#" class="tab active" data-target="add-brand">Add Brand</a>
    </div>

    <!-- Brand Tab Content -->
    <div id="add-brand" class="tab-content active">
        <div class="search-filters">
            <div class="filter-group">
                <div class="filter">
                    <label>Brand Name</label>
                    <input type="text" placeholder="Search by name" class="filter-input" id="brandNameFilter">
                </div>
            </div>

            <div class="products-header">
                <div class="products-count">
                    <h2>{{ isset($brands) ? $brands->count() : 0 }} Brand</h2>
                    <span class="badge">{{ isset($brands) ? $brands->count() : 0 }} / 3,000</span>
                </div>
                <div class="products-actions">
                    <button class="add-product-button no-underline" id="createBrandButton">
                        <i class='bx bx-plus'></i>
                        Create New Brand
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
                            <th><input type="checkbox" id="selectAllBrands"></th>
                            <th>Brand Name</th>
                            <th>Brand Logo ↑</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody id="brandsTableBody">
                        @if(isset($brands))
                            @foreach($brands as $brand)
                                <tr data-brand="{{ $brand->name }}">
                                    <td><input type="checkbox" class="brand-checkbox" value="{{ $brand->id }}"></td>
                                    <td>{{ $brand->name }}</td>
                                    <td>
                                        @if($brand->logo)
                                            <img src="{{ asset('storage/' . $brand->logo) }}"
                                                 alt="{{ $brand->name }}"
                                                 style="width:50px;height:50px;"
                                                 class="brand-logo">
                                        @else
                                            No Logo
                                        @endif
                                    </td>
                                    <td class="action-buttons">
                                        <button class="edit-button btn-orange"
                                                onclick="editBrand({{ $brand->id }}, '{{ $brand->name }}', {{ $brand->category_id }})">
                                            Edit
                                        </button>
                                        <button class="delete-button btn-red"
                                                onclick="confirmDelete({{ $brand->id }})">
                                            Delete
                                        </button>
                                        <form action="{{ route('admin.products.delete-brand', $brand->id) }}"
                                              method="POST"
                                              id="delete-form-{{ $brand->id }}"
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

        <!-- Add Brand Modal Form -->
        <div id="addBrandModal" class="category-form" style="display:none;">
            <div class="modal-content">
                <span class="close-button" onclick="hideAddModal()">&times;</span>
                <form action="{{ route('admin.products.store-brand') }}"
                      method="POST"
                      enctype="multipart/form-data"
                      class="brand-form-container">
                    @csrf
                    <div class="form-group">
                        <label for="brand">Brand Name</label>
                        <input type="text"
                               name="name"
                               id="brand"
                               class="form-control @error('name') is-invalid @enderror"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select name="category_id" id="category_id" class="form-control" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="logo">Brand Logo</label>
                        <input type="file"
                               name="logo"
                               id="logo"
                               class="form-control @error('logo') is-invalid @enderror"
                               accept="image/*">
                        @error('logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="logoPreview" class="mt-2"></div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Save Brand</button>
                        <button type="button" class="btn btn-secondary" onclick="hideAddModal()">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Brand Modal Form -->
        <div id="editBrandForm" class="category-form" style="display:none;">
            <form id="editBrandFormElement" method="POST" enctype="multipart/form-data" class="brand-form-container">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_brand_id" name="brand_id">
                <div class="form-group">
                    <label for="edit_brand">Brand Name</label>
                    <input type="text"
                           name="name"
                           id="edit_brand"
                           class="form-control"
                           required>
                </div>

                <div class="form-group">
                    <label for="edit_category_id">Category</label>
                    <select name="category_id" id="edit_category_id" class="form-control" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="edit_logo">Brand Logo</label>
                    <input type="file"
                           name="logo"
                           id="edit_logo"
                           class="form-control"
                           accept="image/*">
                    <div id="editLogoPreview" class="mt-2"></div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Update Brand</button>
                    <button type="button" class="btn btn-secondary" onclick="hideEditForm()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Brand handling
            const createBrandButton = document.getElementById('createBrandButton');
            const brandNameFilter = document.getElementById('brandNameFilter');
            const selectAllBrands = document.getElementById('selectAllBrands');

            createBrandButton.addEventListener('click', function() {
                document.getElementById('addBrandModal').style.display = 'block';
            });

            brandNameFilter.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const rows = document.querySelectorAll('#brandsTableBody tr');
                rows.forEach(row => {
                    const brandName = row.getAttribute('data-brand').toLowerCase();
                    row.style.display = brandName.includes(searchTerm) ? '' : 'none';
                });
            });

            selectAllBrands.addEventListener('change', function() {
                document.querySelectorAll('.brand-checkbox').forEach(checkbox => {
                    checkbox.checked = selectAllBrands.checked;
                });
            });

            // Image preview handling
            const logoInput = document.getElementById('logo');
            const logoPreview = document.getElementById('logoPreview');
            const editLogoInput = document.getElementById('edit_logo');
            const editLogoPreview = document.getElementById('editLogoPreview');

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

            logoInput.addEventListener('change', function() {
                handleImagePreview(this, logoPreview);
            });

            editLogoInput.addEventListener('change', function() {
                handleImagePreview(this, editLogoPreview);
            });
        });

        // Brand functions
        function hideAddModal() {
            document.getElementById('addBrandModal').style.display = 'none';
            document.getElementById('brand').value = '';
            document.getElementById('category_id').value = '';
            document.getElementById('logo').value = '';
            document.getElementById('logoPreview').innerHTML = '';
        }

        function hideEditForm() {
            document.getElementById('editBrandForm').style.display = 'none';
            document.getElementById('edit_brand').value = '';
            document.getElementById('edit_category_id').value = '';
            document.getElementById('edit_logo').value = '';
            document.getElementById('editLogoPreview').innerHTML = '';
        }

        function editBrand(id, name, categoryId) {
            const editForm = document.getElementById('editBrandFormElement');
            const editBrandInput = document.getElementById('edit_brand');
            const editBrandIdInput = document.getElementById('edit_brand_id');
            const editCategoryIdSelect = document.getElementById('edit_category_id');

            editBrandIdInput.value = id;
            editBrandInput.value = name;
            editCategoryIdSelect.value = categoryId;
            editForm.action = `/admin/products/brands/${id}`;
            document.getElementById('editBrandForm').style.display = 'block';
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
