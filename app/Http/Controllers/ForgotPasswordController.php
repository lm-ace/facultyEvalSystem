<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // Make sure this matches your User model location
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    /**
     * Step 1: Validate email, generate code, and send it.
     */
    public function sendCode(Request $request)
    {
        // 1. Validate the email exists in your database
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'We cannot find a user with that email address.'
        ]);

        // 2. Find the user
        $user = User::where('email', $request->email)->first();

        // 3. Generate a 6-digit random code
        $code = rand(100000, 999999);

        // 4. Save the code to the user's record
        // NOTE: You need to add a 'verification_code' column to your users table (see below)
        $user->verification_code = $code;
        $user->save();

        // 5. SEND THE CODE
        // Option A: Log it to your system logs (Easiest for testing right now)
        Log::info("PASSWORD RESET CODE for {$user->email}: {$code}");

        // Option B: Send actual email (Uncomment when you have SMTP setup)
        /*
        Mail::raw("Your EduRate security code is: $code", function ($message) use ($user) {
            $message->to($user->email)->subject('Password Reset Code');
        });
        */

        // 6. Return back with a session flag to open the second modal
        return back()
            ->with('status', 'code-sent')
            ->with('email', $user->email);
    }

    /**
     * Step 2: Verify the code and update the password.
     */
    public function verifyCode(Request $request)
    {
        // 1. Validate input
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|numeric|digits:6',
            'password' => 'required|confirmed|min:8', // Helper matches password_confirmation field
        ]);

        // 2. Check if user exists with that specific code
        $user = User::where('email', $request->email)
                    ->where('verification_code', $request->code)
                    ->first();

        if (!$user) {
            return back()->withErrors(['code' => 'Invalid verification code.'])->with('status', 'code-sent')->with('email', $request->email);
        }

        // 3. Update Password
        $user->password = Hash::make($request->password);
        $user->verification_code = null; // Clear the code so it can't be used again
        $user->save();

        // 4. Redirect to login with success message
        return redirect()->route('home')->with('success', 'Password reset successfully! Please login.');
    }
}