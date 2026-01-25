<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ForgotPasswordController extends Controller
{
    // 1. Send OTP
    public function sendOtp(Request $request) {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Email not found in our records.']);
        }

        // Generate 6 digit OTP
        $otp = rand(100000, 999999);

        // Store OTP in password_reset_tokens table
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $otp, 
                'created_at' => Carbon::now()
            ]
        );

        // SEND EMAIL
        try {
            Mail::raw("Your EduRate verification code is: $otp", function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('EduRate Password Reset Code');
            });
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Could not send email. Error: ' . $e->getMessage()]);
        }
    }

    // 2. Verify OTP
    public function verifyOtp(Request $request) {
        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->otp)
            ->first();

        if ($record) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid OTP']);
    }

    // 3. Reset Password 
    public function resetPassword(Request $request) {
        $user = User::where('email', $request->email)->first();
        
        if($user) {
            $user->update([
                'password_hash' => Hash::make($request->password)
            ]);

     
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'User not found.']);
    }
}