@extends('layouts.app')

@section('title', 'View Order')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0">Order Details</h4>
                </div>
                <div class="card-body">
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">Order ID</label>
                        <p class="fs-5">{{ $order->id }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">Customer Name</label>
                        <p class="fs-5 fw-bold">{{ $order->customer->name ?? 'N/A' }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">Product Ordered</label>
                        <p class="fs-5 text-primary fw-bold">
                            {{ $order->products->first()->product_name ?? 'No product selected' }}
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">Price per Unit</label>
                        <p class="fs-5 text-secondary fw-bold">
                            ₱{{ number_format($order->products->first()->price ?? 0, 2) }}
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">Total Price</label>
                        
                        @php
                            $product = $order->products->first();
                            $price = $product ? $product->price : 0;
                            $quantity = ($product && $product->pivot) ? $product->pivot->quantity : 0;
                            $totalPrice = $price * $quantity;
                        @endphp
                        
                        <p class="fs-5 text-success fw-bold">
                            ₱{{ number_format($totalPrice, 2) }}
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">Quantity</label>
                        <p class="fs-5">
                            {{ $order->products->first()->pivot->quantity ?? 0 }} pcs
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">Order Date</label>
                        <p class="fs-5">{{ \Carbon\Carbon::parse($order->order_date)->format('F d, Y') }}</p>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('orders.index') }}" class="btn btn-secondary">Back to List</a>

                        @if(auth()->user()->role === 'user')
                            <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-warning text-dark">Edit Order</a>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection