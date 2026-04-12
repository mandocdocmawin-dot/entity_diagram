<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit($id)
    {
        $user = User::findOrFail($id);

        if (Auth::id() !== (int)$id && Auth::user()->role !== 'admin') {
            abort(403, 'Hindi mo pwedeng pakialaman ang profile ng iba.');
        }

        return view('users.edit-profile', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if (auth()->id() !== $user->id) {
            abort(403, 'Unauthorized action. You can only edit your own profile.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'shipping_address' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
        ]);

        // 1. Kunin ang lumang email BAGO i-update ang User
        $oldEmail = $user->email;

        // 2. I-update ang User table
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // 3. Hanapin ang customer gamit ang lumang email
        $customer = Customer::where('email', $oldEmail)->first();

        if ($customer) {
            // Kung nahanap, i-update ang pangalan at ang bagong email
            $customer->update([
                'name' => $user->name,
                'email' => $user->email,
            ]);
        } else {
            // Fallback: Kung walang nahanap na record, gawan ng bago
            $customer = Customer::create([
                'email' => $user->email,
                'uuid' => (string) \Illuminate\Support\Str::uuid(),
                'customer_id' => 'CUST-' . rand(1000, 9999),
                'name' => $user->name,
            ]);
        }

        // 4. I-update o I-create ang Profile details
        $customer->profile()->updateOrCreate(
            ['customer_id' => $customer->id],
            [
                'shipping_address' => $request->shipping_address,
                'phone_number' => $request->phone_number,
            ]
        );

        return redirect()->route('users.profile', $user->id)
                         ->with('success', 'Profile updated successfully!');
    }
}