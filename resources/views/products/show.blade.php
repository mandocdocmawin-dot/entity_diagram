@extends('layouts.app')

@section('title', 'View Product')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0">Product Details</h4>
                </div>
                <div class="card-body">
                
                    <div class="mb-3">
                        <label class="form-label text-muted">Product Name</label>
                        <p class="fs-5 fw-bold">{{ $product->product_name }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">Price</label>
                        <p class="fs-5 text-success">₱{{ number_format($product->price, 2) }}</p>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to List</a>
                        
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning text-dark">Edit Product</a>
                        @endif  
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection