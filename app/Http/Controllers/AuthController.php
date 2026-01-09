<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    //
      public function login(){
       $fields = request()->validate([
           'email' => 'required|email',
           'password' => 'required|string',
           'remember' => 'boolean'
       ]);
    
       $credentials =[
           'email' => $fields['email'],
           'password' => $fields['password'],
       ];

       if(!Auth::attempt(($credentials), $fields['remember'])){
         throw ValidationException::withMessages([
             'email' => ['The provided credentials are incorrect'],
         ]);
       }
       session()->regenerate();

       return response()->json([
           'message' => 'successfully logged in',
           'user' => Auth::user()
       ]);
    }

    public function logout(){
        Auth::guard('web')->logout();

        session()->invalidate();
        session()->regenerate();

        return response('logged out', 204);
    }
    
    public function forgetPassword(Request $request){
        $request->validate([
            'email' => 'required|email',
        ]);
    
        $status = Password::sendResetLink(
            $request->only('email')
        );
    
        return $status === Password::RESET_LINK_SENT
                    ? back()->with(['status' => __($status)])
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
    
    public function resetPassword(Request $request){
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'message' => __($status),
            ]);
        }

        throw ValidationException::withMessages([
            'email' => [__($status)],
        ]);
    }
}
