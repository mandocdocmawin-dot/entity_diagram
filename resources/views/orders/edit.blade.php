@extends('layouts.app')

@section('title', 'Edit Order')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">Edit Order</h4>
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

                    <form action="{{ route('orders.update', $order->id) }}" method="POST">
                        @csrf 
                        @method('PUT') 
                        
                        @php
                            // Kunin ang current product ID
                            $currentProductId = $order->products->first() ? $order->products->first()->id : null;
                            // Kunin ang current quantity mula sa pivot table
                            $currentQuantity = ($order->products->first() && $order->products->first()->pivot) ? $order->products->first()->pivot->quantity : 1;
                        @endphp

                        <div class="mb-3">
                            <label for="product_id" class="form-label">Select Product to Order</label>
                            <select class="form-select" id="product_id" name="product_id" required>
                                <option value="" disabled>-- Choose a Product --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ old('product_id', $currentProductId) == $product->id ? 'selected' : '' }}>
                                        {{ $product->product_name }} - ₱{{ number_format($product->price, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" name="quantity" id="quantity" class="form-control" value="{{ old('quantity', $currentQuantity) }}" min="1" required>
                        </div>

                        <div class="mb-3">
                            <label for="order_date" class="form-label">Order Date</label>
                            <input type="date" class="form-control" id="order_date" name="order_date" value="{{ old('order_date', \Carbon\Carbon::parse($order->order_date)->format('Y-m-d')) }}" required>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('orders.index') }}" class="btn btn-secondary">Back to List</a>
                            <button type="submit" class="btn btn-success">Update Order</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection