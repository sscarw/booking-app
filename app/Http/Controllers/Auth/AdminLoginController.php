<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.admin_login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $credentials['is_admin'] = true;

        if (Auth::attempt($credentials)) {
            return redirect()->intended('/admin/dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/admin/login');
    }
}
