@extends('layouts.app')

@section('title', 'Orders List')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>List of Orders</h2>

        @if(auth()->user()->role === 'user')
            <a href="{{ route('orders.create') }}" class="btn btn-primary">Add New Order</a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover table-bordered mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Customer Name</th>
                        <th>Order Date</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->customer->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($order->order_date)->format('F d, Y') }}</td>
                            <td class="text-center">

                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-info text-white">View</a>

                                @if(auth()->user()->role === 'user')
                                    <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-sm btn-warning text-dark">Edit</a>
                                    
                                    <form action="{{ route('orders.destroy', $order->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this order?')">Delete</button>
                                    </form>
                                @endif

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection