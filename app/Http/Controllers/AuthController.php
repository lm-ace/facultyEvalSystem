<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validation
        $request->validate([
            'user_id' => 'required',
            'password' => 'required',
            'role' => 'required'
        ]);

        // 2. I-set ang credentials (user_id ang gamit natin base sa screenshot mo)
        $credentials = [
            'user_id' => $request->user_id,
            'password' => $request->password,
            'role' => $request->role, 
        ];

        // 3. Attempt Login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('redirect.dashboard');
        }

        // 4. Return with Error kung mali ang password/ID
        return back()->withErrors([
            'password' => 'The credentials provided do not match our records.',
        ])->onlyInput('user_id');
    }

    public function redirectToDashboard()
    {
        $role = Auth::user()->role;

        return match($role) {
            'admin'   => redirect()->route('admin.dashboard'),
            'faculty' => redirect()->route('faculty.dashboard'),
            'student' => redirect()->route('student.dashboard'),
            default   => redirect('/'),
        };
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}