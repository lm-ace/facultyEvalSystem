<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        //validation if username and password are filled
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        //Find the user by username 
        $user = User::where('username', $request->username)->first();

        //Checking of user's existance and if password is correct
        if ($user && Hash::check($request->password, $user->password_hash)) {

            if (!$user->is_active) {
                return back()->withErrors(['username' => 'Your account is not active']);
            }


            Auth::login($user);

            $user->update(['last_login' => now()]);

            if ($user->role == 'student') {
                return redirect('/student');
            } elseif ($user->role == 'faculty') {
                return redirect('/faculty/dashboard');
            } elseif ($user->role == 'admin') {
                return redirect('/admin/dashboard');
            }
        }
        return back()->withErrors(['username' => 'Wrong username or password']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
