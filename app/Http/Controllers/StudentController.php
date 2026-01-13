<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use App\Mail\CredentialsMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    // Replace storeStudent in ProgramController (or StudentController) with this improved version.
    public function storeStudent(Request $request)
{
    $request->validate([
        'student_number' => 'required|unique:users,user_id',
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'section' => 'required',
        'password' => 'required|min:8|'
    ]);

    try {
        $student = null;

        DB::transaction(function () use ($request, &$student) {
            $student = User::create([
                'user_id' => $request->student_number,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'student',
                'section' => $request->section,
                'email_verified_at' => now(),
                'is_active' => true,
            ]);

            AuditLog::log('USER_CREATE', 'Created student account: ' . $student->name . ' (' . $student->user_id . ')');
        });

        if ($student !== null) {
            try {
                Mail::to($student->email)->send(new CredentialsMail($student, $request->password, 'student'));
            } catch (\Exception $mailEx) {
                Log::error('Failed to send credentials email (program controller) for ' . $student->user_id . ': ' . $mailEx->getMessage());
                return back()->with('warning', 'Student created but email could not be sent. Check logs.');
            }
        }

        return back()->with('success', 'Student enrolled and credentials emailed successfully!');
    } catch (\Exception $e) {
        Log::error('Error storing student (program controller): ' . $e->getMessage());
        return back()->with('error', 'Error enrolling student: ' . $e->getMessage());
    }
    }
}