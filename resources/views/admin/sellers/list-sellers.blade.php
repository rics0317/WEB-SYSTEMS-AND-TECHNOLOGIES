@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">
    <div class="row">
        <div class="col-md-12">
            <h1>All Sellers</h1>
        </div>
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <div class="row">
                        @foreach($approvedSellers as $seller)
                        <div class="col-md-4 mb-4">
                            <div class="card bg-light d-flex flex-fill">
                                <div class="card-header text-muted border-bottom-0">
                                    {{ $seller->seller_type }}
                                </div>
                                <div class="card-body pt-0">
                                    <div class="row">
                                        <div class="col-7">
                                            <h2 class="lead"><b>{{ $seller->shop_name }}</b></h2>
                                            <p class="text-muted text-sm"><b>About: </b> {{ $seller->user->email }}</p>
                                            <ul class="ml-4 mb-0 fa-ul text-muted">
                                                <li class="small"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span> Address: {{ $seller->general_location }}</li>
                                                <li class="small"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span> Phone #: {{ $seller->user->phone ?? 'N/A' }}</li>
                                            </ul>
                                        </div>
                                        <div class="col-5 text-center">
                                            <img src="{{ $seller->user->profile_image ? asset($seller->user->profile_image) : asset('placeholder.svg') }}" alt="User Avatar" class="img-circle img-fluid">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="text-right">
                                        <a href="#" class="btn btn-sm bg-teal">
                                            <i class="fas fa-comments"></i>
                                        </a>
                                        <a href="#" class="btn btn-sm btn-primary view-details" data-toggle="modal" data-target="#detailsModal{{ $seller->id }}">
                                            <i class="fas fa-user"></i> View Profile
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal for detailed view -->
                        <div class="modal fade" id="detailsModal{{ $seller->id }}" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel{{ $seller->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="detailsModalLabel{{ $seller->id }}">Seller Registration Details</h5>
                                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        @if($seller->user)
                                            <div class="row">
                                                <div class="col-md-4 text-center mb-4">
                                                    <img src="{{ $seller->business_logo ? asset('storage/' . $seller->business_logo) : asset('placeholder.svg') }}"
                                                         alt="Business logo of {{ $seller->shop_name }}"
                                                         class="rounded-circle mb-3"
                                                         width="150"
                                                         height="150" />
                                                    <h4>{{ $seller->shop_name }}</h4>
                                                    <p class="text-muted">{{ $seller->seller_type }}</p>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <h6 class="text-muted">User ID</h6>
                                                            <p>{{ $seller->user_id }}</p>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <h6 class="text-muted">Email</h6>
                                                            <p>{{ $seller->user->email }}</p>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <h6 class="text-muted">Registered Name</h6>
                                                            <p>{{ $seller->registered_name }}</p>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <h6 class="text-muted">General Location</h6>
                                                            <p>{{ $seller->general_location }}</p>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <h6 class="text-muted">Registered Address</h6>
                                                            <p>{{ $seller->registered_address }}</p>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <h6 class="text-muted">Zip Code</h6>
                                                            <p>{{ $seller->zip_code }}</p>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <h6 class="text-muted">TIN</h6>
                                                            <p>{{ $seller->tin }}</p>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <h6 class="text-muted">VAT Status</h6>
                                                            <p>{{ $seller->vat_status }}</p>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <h6 class="text-muted">BIR Certificate</h6>
                                                            @if($seller->bir_certificate)
                                                                <a href="{{ asset('storage/' . $seller->bir_certificate) }}" target="_blank" class="btn btn-sm btn-primary">View Certificate</a>
                                                            @else
                                                                <p>No certificate uploaded</p>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <h6 class="text-muted">Status</h6>
                                                            <span class="status-circle {{ $seller->user->status == 1 ? 'online' : 'offline' }}"></span>
                                                            {{ $seller->user->status == 1 ? 'Online' : 'Offline' }}
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
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .status-circle {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-right: 5px;
        vertical-align: middle;
    }
    .status-circle.online {
        background-color: #28a745;
        box-shadow: 0 0 5px #28a745;
    }
    .status-circle.offline {
        background-color: #dc3545;
        box-shadow: 0 0 5px #dc3545;
    }
    .description-block {
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 10px 0;
    }
    .description-percentage {
        margin-top: 10px;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .img-circle {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #fff;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    .widget-user-header {
        padding: 20px;
        height: auto;
    }
    .widget-user-username {
        margin-top: 0;
        margin-bottom: 5px;
        font-size: 25px;
        font-weight: 300;
        text-shadow: 0 1px 1px rgba(0,0,0,0.2);
    }
    .widget-user-desc {
        margin-top: 0;
    }
    .card-widget {
        transition: all 0.3s ease;
    }
    .card-widget:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
</style>
@endpush
