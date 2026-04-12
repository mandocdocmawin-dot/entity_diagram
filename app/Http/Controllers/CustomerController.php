<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Str; 

class CustomerController extends Controller
{
    // 1. READ: Ipakita ang listahan ng lahat ng customers
    public function index()
    {
        // Kukunin natin lahat ng customers. 
        $customers = Customer::with('orders')->get();
        return view('customers.index', compact('customers'));
    }

    // 2. CREATE (Form): Ipakita ang form para makapag-add ng bagong customer
    public function create()
    {
        return view('customers.create');
    }

    // 3. CREATE (Save): I-save sa database ang sinubmit sa form
    public function store(Request $request)
    {
        // I-validate ang input ng user
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
        ]);

        // Gumawa ng bagong customer (Kasama ang auto-generated UUID at Customer ID)
        Customer::create([
            'uuid' => Str::uuid(),
            'customer_id' => 'CUST-' . strtoupper(Str::random(4)),
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Bumalik sa listahan at magpakita ng success message
        return redirect()->route('customers.index')->with('success', 'Customer added successfully!');
    }

    // 4. READ (Specific): Ipakita ang detalye ng isang customer
    public function show(Customer $customer)
    {
        // Gagamit tayo ng Eager Loading dito para kunin ang mga Orders ng customer na ito
        $customer->load('orders'); 
        return view('customers.show', compact('customer'));
    }

    // 5. UPDATE (Form): Ipakita ang form para ma-edit ang customer
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    // 6. UPDATE (Save): I-save ang mga pagbabago sa database
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id, // Ignore ang sariling email sa validation
        ]);

        $customer->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully!');
    }

    // 7. DELETE: Burahin ang customer sa database
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully!');
    }
}