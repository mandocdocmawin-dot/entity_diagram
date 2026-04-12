<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // 1. READ: Listahan ng Products
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    // 2. CREATE: Form para sa bagong Product
    public function create()
    {
        return view('products.create');
    }

    // 3. STORE: I-save ang Product
    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')->with('success', 'Product added successfully!');
    }

    // 4. SHOW: View specific Product
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    // 5. EDIT: Form para i-edit ang Product
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    // 6. UPDATE: I-save ang changes
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    // 7. DELETE: Burahin ang Product
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
}