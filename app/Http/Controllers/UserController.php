<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * (Para sa Admin: Makikita nila dito ang listahan ng lahat ng users)
     */
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    // ... (Pwede mong iwanan na blangko muna ang create, store, edit, update, destroy kung hindi pa kailangan i-code) ...

    /**
     * Display the specified resource.
     * (Para sa Admin: Tignan ang specific na user account)
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
        // Kukunin ang data ng kasalukuyang naka-login na user
        $user = Auth::user();

        // Ipapakita ang profile page ng user (gagamitin natin ang same 'users.show' view)
        return view('users.profile', compact('user'));
    }

    public function destroy($id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }
}