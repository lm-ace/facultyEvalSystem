<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    // STEP 1: Send Email
    public function sendCode(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $code = rand(100000, 999999);

        DB::table('verification_codes')->updateOrInsert(
            ['email' => $request->email],
            ['code' => $code, 'created_at' => Carbon::now()]
        );

        try {
            Mail::raw("Your EduRate code is: $code", function ($message) use ($request) {
                $message->to($request->email)->subject('Reset Password Code');
            });
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Failed to send email.']);
        }

        // Return with session flag 'step=2'
        return back()->with('step', 2)->with('email', $request->email);
    }

    // STEP 2: Verify Code
    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|numeric'
        ]);

        $record = DB::table('verification_codes')
            ->where('email', $request->email)
            ->where('code', $request->code)
            ->where('created_at', '>=', Carbon::now()->subMinutes(15))
            ->first();

        if (!$record) {
            // If failed, go back to step 2
            return back()->with('step', 2)->with('email', $request->email)->withErrors(['code' => 'Invalid or expired code.']);
        }

        // If success, go to step 3 (Password Reset)
        return back()->with('step', 3)->with('email', $request->email)->with('code_verified', true);
    }

    // STEP 3: Reset Password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        DB::table('verification_codes')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('status', 'Password changed successfully! Please login.');
    }
}