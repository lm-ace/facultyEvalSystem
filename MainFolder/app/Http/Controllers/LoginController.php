<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.role-selection');
    }

    public function showLoginForm($role)
    {
        if (!in_array($role, ['student', 'faculty', 'admin'])){
            abort(404); 
        }

        return view('auth.login-form', ['role' => $role]);
    }

    public function login(Request $request)
    {
        Log::info("Login attempt initiated for username: " . $request->username);

        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)->first();

        if ($user && Hash::check($request->password, $user->password_hash)) {

            if (!$user->is_active) {
                Log::warning("LOGIN BLOCKED: Inactive account tried to login - Username: {$request->username}");
                
                return back()->withErrors(['username' => 'Your account is not active']);
            }

            Auth::login($user);
            $user->update(['last_login' => now()]);

            Log::notice("LOGIN SUCCESS: User '{$user->username}' logged in as '{$user->role}'");

            if ($user->role == 'student') {
                return redirect('/student');
            } elseif ($user->role == 'faculty') {
                return redirect('/faculty/dashboard');
            } elseif ($user->role == 'admin') {
                return redirect('/admin/dashboard');
            }
        }

        Log::warning("LOGIN FAILED: Invalid credentials for username: {$request->username}");

        return back()->withErrors(['username' => 'Wrong username or password']);
    }

    public function logout()
    {
        $username = Auth::user() ? Auth::user()->username : 'Unknown';

        Auth::logout();

        Log::info("User logged out: {$username}");

        return redirect('/');
    }
}