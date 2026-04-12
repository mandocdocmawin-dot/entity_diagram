@extends('layouts.app')

@section('title', 'Customer Details')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
            <h4 class="mb-0">Customer Details</h4>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <p><strong>Customer ID:</strong> {{ $customer->customer_id }}</p>
                    <p><strong>Name:</strong> {{ $customer->name }}</p>
                    <p><strong>Email:</strong> {{ $customer->email }}</p>
                </div>
            </div>

            <hr>

            <h5 class="mb-3">Order History</h5>
            @if($customer->orders->isEmpty())
                <div class="alert alert-light text-muted">No orders found for this customer.</div>
            @else
                <ul class="list-group">
                    @foreach($customer->orders as $order)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Order ID: {{ $order->id }}
                            <span class="badge bg-primary rounded-pill">Date: {{ $order->order_date }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif

            <div class="mt-4">
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>
    </div>
</div>
@endsection