<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\EvaluationResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentsController extends Controller
{
    // Password Change Function
    public function changePassword(Request $request)
    {
        // 1. Validate the inputs
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        // 2. Get the User safely
        // We use the ID to ensure we get the Eloquent Model, not a generic auth object
        $user = \App\Models\User::find(Auth::id());

        // Safety Net: If the session expired, kick them to login instead of crashing
        if (!$user) {
            Auth::logout();
            return redirect()->route('login')->withErrors(['email' => 'Session expired. Please log in again.']);
        }

        // 3. Verify the Old Password
        // matches your database column: 'password_hash'
        if (!Hash::check($request->current_password, $user->password_hash)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        // 4. Update the Password
        // We manually set the hash and save to trigger the update
        $user->password_hash = Hash::make($request->new_password);
        $user->save();

        // 5. Logout and Redirect
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Password changed successfully! Please log in with your new password.');
    }

    // Logout Redirect Function
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
