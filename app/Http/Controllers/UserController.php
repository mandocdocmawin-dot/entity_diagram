<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,admin',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:user,admin',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
            $data['password'] = bcrypt($request->password);
        }

        $oldEmail = $user->email;
        $user->update($data);
        $customer = \App\Models\Customer::where('email', $oldEmail)->first();

        if ($customer) {
            $customer->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);
        }

        return redirect()->route('users.index')->with('success', 'User and associated customer details updated successfully!');
    }

    /**
     * Display a listing of the resource.
     */
    public function profile(User $user)
    {

        if (auth()->user()->role !== 'admin' && auth()->id() !== $user->id) {
            abort(403, 'Unauthorized access. Admin privilege is required.');
        }

        return view('users.profile', compact('user'));
    }

    /**
     * Custom method para sa rule na "Users can only view their own Account"
     */
    public function myAccount()
    {
        $user = Auth::user();
        return view('users.profile', compact('user'));
    }

    public function destroy($id)
    {
        $user = \App\Models\User::findOrFail($id);
    
        $customer = \App\Models\Customer::where('email', $user->email)->first();
        if ($customer) {
            $customer->delete();
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User and associated customer profile deleted successfully!');
    }
}