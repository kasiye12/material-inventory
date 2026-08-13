<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ActivityLogger;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            
            ActivityLogger::log(
                'LOGIN',
                'User logged in successfully',
                null, null, null,
                'Authentication'
            );
            
            return redirect()->intended('/dashboard');
        }

        ActivityLogger::log(
            'LOGIN_FAILED',
            'Failed login attempt for email: ' . $request->email,
            null, null, $request->email,
            'Authentication'
        );

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        ActivityLogger::log(
            'LOGOUT',
            'User logged out',
            null, null, null,
            'Authentication'
        );
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
