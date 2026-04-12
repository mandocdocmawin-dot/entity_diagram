@extends('layouts.app')

@section('content')
<div class="container mt-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="display-3 fw-bold text-primary mb-3">Welcome to Laravel!</h1>
            <p class="lead text-muted mb-5">Activity: Middleware + CRUD Integration</p>

            <div class="d-grid gap-3 d-sm-flex justify-content-sm-center">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4 gap-3">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-lg px-4">Register</a>
                @else
                    <a href="{{ route('orders.index') }}" class="btn btn-success btn-lg px-4">Go to Orders</a>
                @endguest
            </div>
        </div>
    </div>
</div>
@endsection