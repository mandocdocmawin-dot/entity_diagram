@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-header bg-warning text-dark py-3">
                    <h5 class="mb-0">Edit Profile Information</h5>
                </div>
                <div class="card-body">
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('profile.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        @php
                            $existingAddress = $user->customer && $user->customer->profile ? $user->customer->profile->shipping_address : '';
                            $existingPhone = $user->customer && $user->customer->profile ? $user->customer->profile->phone_number : '';
                        @endphp

                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="shipping_address" class="form-label fw-bold">Shipping Address</label>
                            <input type="text" class="form-control" id="shipping_address" name="shipping_address" value="{{ old('shipping_address', $existingAddress) }}" placeholder="Enter your full shipping address">
                        </div>

                        <div class="mb-3">
                            <label for="phone_number" class="form-label fw-bold">Phone Number</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number', $existingPhone) }}" placeholder="e.g. 09123456789">
                        </div>

                        <hr>
                        <div class="text-end">
                            <a href="{{ route('users.profile', $user->id) }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection