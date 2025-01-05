@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">
    <div class="row">
        <!-- Detailed Profile Card -->
        <div class="col-md-4">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center mb-4">
                        <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('placeholder.svg') }}" alt="Profile" class="profile-user-img img-fluid img-circle" />
                        <h3 class="profile-username text-center">{{ $user->name }}</h3>
                        <p class="text-muted text-center">Admin</p>
                    </div>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b><i class="fas fa-calendar mr-2"></i> Age</b> <a class="float-right">{{ $user->age }}</a>
                        </li>
                        <li class="list-group-item">
                            <b><i class="fas fa-user mr-2"></i> Gender</b> <a class="float-right">{{ $user->gender }}</a>
                        </li>
                        <li class="list-group-item">
                            <b><i class="fas fa-ring mr-2"></i> Civil Status</b> <a class="float-right">{{ $user->civil_status }}</a>
                        </li>
                        <li class="list-group-item">
                            <b><i class="fas fa-birthday-cake mr-2"></i> Birth Date</b>
                            <a class="float-right">{{ $user->birthdate instanceof \Carbon\Carbon ? $user->birthdate->format('M d, Y') : 'N/A' }}</a>
                        </li>
                        <li class="list-group-item">
                            <b><i class="fas fa-phone mr-2"></i> Contact</b> <a class="float-right">{{ $user->contact }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Change Password Form -->
        <div class="col-md-8">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Change Password</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.updatePassword') }}" method="POST" id="changePasswordForm">
                        @csrf
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password" name="new_password" required>
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="new_password_confirmation">Confirm Password</label>
                            <input type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror" id="new_password_confirmation" name="new_password_confirmation" required>
                            @error('new_password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-2"></i> Change Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .card-header h5 {
        margin-bottom: 0.25rem;
    }
    .card-header small {
        opacity: 0.8;
    }
    .custom-file-input:lang(en)~.custom-file-label::after {
        content: "Browse";
    }
    .input-group-text {
        cursor: pointer;
    }
</style>
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });

        $(".input-group-text").click(function() {
            $(this).closest('.input-group').find('.custom-file-input').click();
        });

        // Handle form submission
        $('#changePasswordForm').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');

            $.ajax({
                type: "POST",
                url: url,
                data: form.serialize(),
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Password updated successfully.',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "{{ route('admin.profile') }}";
                        }
                    });
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
                    var errorMessage = '';
                    $.each(errors, function(key, value) {
                        errorMessage += value[0] + '<br>';
                    });
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        html: errorMessage,
                    });
                }
            });
        });
    });
</script>
@endpush
