@extends('layouts.seller')

@section('content')
    <div class="tabs">
        <a href="#" class="tab active" data-target="manage-users">Manage Users</a>
    </div>

    <!-- User Management Tab Content -->
    <div id="manage-users" class="tab-content active">
        <div class="search-filters">
            <div class="filter-group">
                <div class="filter">
                    <label>User Name</label>
                    <input type="text" placeholder="Search by name" class="filter-input" id="userNameFilter">
                </div>
            </div>

            <div class="products-header">
                <div class="products-count">
                    <h2>{{ isset($users) ? $users->count() : 0 }} Users</h2>
                    <span class="badge">{{ isset($users) ? $users->count() : 0 }} / 3,000</span>
                </div>
                <div class="products-actions">
                    <button class="add-product-button no-underline" id="createUserButton">
                        <i class='bx bx-plus'></i>
                        Create New User
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
                            <th><input type="checkbox" id="selectAllUsers"></th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                        @if(isset($users))
                            @foreach($users as $user)
                                <tr data-user="{{ $user->name }}">
                                    <td><input type="checkbox" class="user-checkbox" value="{{ $user->id }}"></td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->role->role_name }}</td>
                                    <td class="action-buttons">
                                    <button class="edit-button btn-orange" onclick="editUser({{ $user->id }}, {{ $user->role_id }})">Edit</button>
                                        <a href="{{ route('admin.manage.view-user', $user->id) }}" class="view-button btn-blue">View</a>
                                        <button class="delete-button btn-red" onclick="confirmDelete({{ $user->id }})">Delete</button>

                                        <form action="{{ route('admin.manage.delete-user', $user->id) }}" method="POST" id="delete-form-{{ $user->id }}" class="delete-form" style="display:none;">
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

        <!-- Add User Modal Form -->
        <div id="addUserModal" class="category-form" style="display:none;">
            <div class="modal-content">
                <span class="close-button" onclick="hideAddModal()">&times;</span>
                <form action="{{ route('admin.manage.store-user') }}" method="POST" class="user-form-container">
                    @csrf
                    <div class="form-group">
                        <label for="name">User Name</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="role_id">Role</label>
                        <select name="role_id" id="role_id" class="form-control @error('role_id') is-invalid @enderror" required>
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Save User</button>
                        <button type="button" class="btn btn-secondary" onclick="hideAddModal()">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit User Modal Form -->
        <div id="editUserForm" class="category-form" style="display:none;">
            <form id="editUserFormElement" method="POST" class="user-form-container">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_user_id" name="user_id">

                <div class="form-group">
                    <label for="edit_role_id">Role</label>
                    <select name="role_id" id="edit_role_id" class="form-control" required>
                        <option value="">Select Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Update User Role</button>
                    <button type="button" class="btn btn-secondary" onclick="hideEditForm()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const createUserButton = document.getElementById('createUserButton');
            const userNameFilter = document.getElementById('userNameFilter');
            const selectAllUsers = document.getElementById('selectAllUsers');

            createUserButton.addEventListener('click', function() {
                document.getElementById('addUserModal').style.display = 'block';
            });

            userNameFilter.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const rows = document.querySelectorAll('#usersTableBody tr');
                rows.forEach(row => {
                    const userName = row.getAttribute('data-user').toLowerCase();
                    row.style.display = userName.includes(searchTerm) ? '' : 'none';
                });
            });

            selectAllUsers.addEventListener('change', function() {
                document.querySelectorAll('.user-checkbox').forEach(checkbox => {
                    checkbox.checked = selectAllUsers.checked;
                });
            });
        });

        function hideAddModal() {
            document.getElementById('addUserModal').style.display = 'none';
            document.getElementById('name').value = '';
            document.getElementById('email').value = '';
            document.getElementById('role_id').value = '';
        }

        function hideEditForm() {
            document.getElementById('editUserForm').style.display = 'none';
            document.getElementById('edit_role_id').value = '';
        }

        function editUser(id, roleId) {
            const editForm = document.getElementById('editUserFormElement');
            const editUserIdInput = document.getElementById('edit_user_id');
            const editRoleIdInput = document.getElementById('edit_role_id');

            editUserIdInput.value = id;
            editRoleIdInput.value = roleId;
            editForm.action = `/admin/manage/user-management/${id}`;
            document.getElementById('editUserForm').style.display = 'block';
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
