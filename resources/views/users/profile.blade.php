@extends('layouts.app')

@section('content')
<div class="container mt-4">
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">My Account Details</h5>
                    
                    @if(auth()->user()->role === 'user')
                        <a href="{{ route('profile.edit', $user->id) }}" class="btn btn-sm btn-light">
                            Edit Profile
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <label class="col-sm-3 fw-bold">Name:</label>
                        <div class="col-sm-9">{{ $user->name }}</div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 fw-bold">Email:</label>
                        <div class="col-sm-9">{{ $user->email }}</div>
                    </div>

                    @if(auth()->user()->role === 'admin')
                        <div class="row mb-3">
                            <label class="col-sm-3 fw-bold">Account Role:</label>
                            <div class="col-sm-9">
                                <span class="badge {{ $user->role == 'admin' ? 'bg-danger' : 'bg-success' }}">
                                    {{ strtoupper($user->role) }}
                                </span>
                            </div>
                        </div>
                    @endif
                     @if(auth()->user()->role === 'user')
                        <h6 class="mt-4 mb-3 text-secondary border-bottom pb-2">Delivery Information</h6>
                        
                        <div class="row mb-3">
                            <label class="col-sm-3 fw-bold">Shipping Address:</label>
                            <div class="col-sm-9">
                                @if($user->customer && $user->customer->profile && $user->customer->profile->shipping_address)
                                    {{ $user->customer->profile->shipping_address }}
                                @else
                                    <span class="text-muted fst-italic">Not provided yet</span>
                                @endif
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 fw-bold">Phone Number:</label>
                            <div class="col-sm-9">
                                @if($user->customer && $user->customer->profile && $user->customer->profile->phone_number)
                                    {{ $user->customer->profile->phone_number }}
                                @else
                                    <span class="text-muted fst-italic">Not provided yet</span>
                                @endif
                            </div>
                        </div>
                    @endif
    
                    <hr>
                    <div class="d-flex justify-content-between mt-4">
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">Back to Users List</a>
                        @endif
                        @if(auth()->user()->id === $user->id)
                            <a href="{{ route('home', $user->id) }}" class="btn btn-secondary">Back to Dashboard</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection