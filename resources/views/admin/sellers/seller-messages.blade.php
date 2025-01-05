@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">
    <div class="row">
        <!-- Member Cards Grid -->
        <div class="col-md-12">
            <div class="row">
                @foreach($sellerRegistrations as $registration)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card store-card">
                        <div class="card-header text-white bg-primary text-center">
                            <h5 class="mb-0">{{ $registration->shop_name }}</h5>
                            <small>{{ $registration->seller_type }}</small>
                        </div>
                        <div class="card-body text-center">
                            <div class="logo-box mb-3">
                                <img src="{{ asset('' . $registration->user->profile_image) }}" alt="{{ $registration->shop_name }}" class="rounded-circle" width="100" height="100" />
                            </div>
                            <div class="row mt-3">
                                <div class="col-4">
                                    <small class="d-block text-muted">Status</small>
                                    {{ $registration->status }}
                                </div>
                                <div class="col-4">
                                    <small class="d-block text-muted">User ID</small>
                                    {{ $registration->user_id }}
                                </div>
                                <div class="col-4">
                                    <small class="d-block text-muted">View</small>
                                    <a href="#" class="text-primary view-details" data-toggle="modal" data-target="#detailsModal{{ $registration->id }}">
                                        <i class="fas fa-eye" aria-hidden="true"></i>
                                        <span class="sr-only">View details for {{ $registration->shop_name }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal for detailed view -->
                <div class="modal fade" id="detailsModal{{ $registration->id }}" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel{{ $registration->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="detailsModalLabel{{ $registration->id }}">Seller Registration Details </h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                @if($registration->user)
                                    <div class="row">
                                        <div class="col-md-4 text-center mb-4">
                                        <img src="{{ asset('storage/' . $registration->business_logo) }}"
                                        alt="Profile picture of {{ $registration->shop_name }}"
                                                 class="rounded-circle mb-3"
                                                 width="150"
                                                 height="150" />
                                            <h4>{{ $registration->shop_name }}</h4>
                                            <p class="text-muted">{{ $registration->seller_type }}</p>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <h6 class="text-muted">User ID</h6>
                                                    <p>{{ $registration->user_id }}</p>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <h6 class="text-muted">Email</h6>
                                                    <p>{{ $registration->email }}</p>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <h6 class="text-muted">Registered Name</h6>
                                                    <p>{{ $registration->registered_name }}</p>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <h6 class="text-muted">General Location</h6>
                                                    <p>{{ $registration->general_location }}</p>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <h6 class="text-muted">Registered Address</h6>
                                                    <p>{{ $registration->registered_address }}</p>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <h6 class="text-muted">Zip Code</h6>
                                                    <p>{{ $registration->zip_code }}</p>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <h6 class="text-muted">TIN</h6>
                                                    <p>{{ $registration->tin }}</p>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <h6 class="text-muted">VAT Status</h6>
                                                    <p>{{ $registration->vat_status }}</p>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <h6 class="text-muted">BIR Certificate</h6>
                                                    @if($registration->bir_certificate)
                                                        <a href="{{ asset('storage/' . $registration->bir_certificate) }}"
                                                           target="_blank"
                                                           class="btn btn-sm btn-primary">
                                                            View Certificate
                                                        </a>
                                                    @else
                                                        <p>No certificate uploaded</p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <h6 class="text-muted">Status</h6>
                                                    <p>{{ $registration->status }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-danger">Error: User information not found.</p>
                                @endif
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <form action="{{ route('admin.seller-messages.approve', $registration->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success">Approve</button>
                                </form>
                                <form action="{{ route('admin.seller-messages.delete', $registration->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this registration?')">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('css')
<style>
    .card-header h5 {
        margin-bottom: 0.25rem;
    }
    .card-header small {
        opacity: 0.8;
    }

    .modal-body h6 {
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }
    .modal-body p {
        font-size: 1rem;
        font-weight: 500;
    }
    .store-card {
        border: 1px solid #ddd;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s;
    }
    .store-card:hover {
        transform: translateY(-5px);
    }
</style>
@endpush
@endsection
