<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.auth.login');
    }

  public function login(Request $request)
{
    $request->validate([
        'login' => 'required',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->login)
        ->orWhere('username', $request->login)
        ->first();

    if (!$user) {
        return back()->withErrors(['login' => 'User not found']);
    }

    if (!Hash::check($request->password, $user->password)) {
        return back()->withErrors(['password' => 'Incorrect password']);
    }

    Auth::login($user, true); // Add 'remember me' parameter
    $request->session()->regenerate();
    

    return redirect()->route('blade.admin.dashboard');
}

   public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('blade.admin.login')->with('status', 'Logged out successfully');
    }
}
