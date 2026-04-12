<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product; 
use App\Models\Customer; 
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        if (auth()->user()->role === 'admin') {
            // Gumamit ng 'products' relationship
            $orders = \App\Models\Order::with(['customer', 'products'])->get();
        } else {
            $customer = \App\Models\Customer::where('email', auth()->user()->email)->first();
            
            if ($customer) {
                // Gumamit ng 'products' relationship
                $orders = \App\Models\Order::with(['customer', 'products'])->where('customer_id', $customer->id)->get();
            } else {
                $orders = collect(); 
            }
        }

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $products = Product::all();
        return view('orders.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'order_date' => 'required|date',
        ]);

        $customer = Customer::where('email', auth()->user()->email)->first();

        // 1. I-save muna ang Order
        $order = Order::create([
            'customer_id' => $customer->id,
            'order_date' => $request->order_date,
        ]);

        // 2. I-save ang Product sa order_details (Pivot Table) na may quantity na 1
        $order->products()->attach($request->product_id, ['quantity' => $request->quantity]);

        return redirect()->route('orders.index')->with('success', 'Order created successfully!');
    }

    public function show(Order $order)
    {
        $order->load(['customer', 'products']); 
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $products = Product::all();
        $order->load('products'); // I-load ang attached products
        return view('orders.edit', compact('order', 'products'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'order_date' => 'required|date',
        ]);
        $order->update([
            'order_date' => $request->order_date,
        ]);

        $order->products()->sync([
            $request->product_id => ['quantity' => $request->quantity]
        ]);
        
        return redirect()->route('orders.index')->with('success', 'Order updated successfully!');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully!');
    }
}