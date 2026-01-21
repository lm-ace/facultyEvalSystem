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

    public function store(Request $request)
    {
        // 1. Validation (Ensure answers are between 1 and 5)
        $request->validate([
            'faculty_id' => 'required|exists:faculties,id',
            'answers'    => 'required|array', // Array of [question_id => score]
            'answers.*'  => 'required|integer|min:1|max:5',
        ]);

        // 2. Create the Main Evaluation Record
        // This creates the row you showed in the screenshot
        $evaluation = Evaluation::create([
            'student_id'        => auth()->id(), // Assuming logged in student
            'faculty_id'        => $request->faculty_id,
            'class_offering_id' => $request->class_offering_id, // If you track this
            'review_period_id'  => 1, // Current active period ID
            'submitted_at'      => now(),
            'completed'         => true,
            'overall_rating'    => 0, // Temporary value, we calculate it below
        ]);

        // 3. Save Individual Answers & Calculate Total
        $totalScore = 0;
        $totalQuestions = 0;

        foreach ($request->answers as $criteriaItemId => $score) {
            // Save the detailed answer (for deep analysis later)
            EvaluationResponse::create([
                'evaluation_id'    => $evaluation->id,
                'criteria_item_id' => $criteriaItemId,
                'score'            => $score,
            ]);

            $totalScore += $score;
            $totalQuestions++;
        }

        // 4. THE MAGIC PART: Calculate Average
        // Example: (5 + 4 + 5) / 3 = 4.66
        $averageRating = $totalQuestions > 0 ? ($totalScore / $totalQuestions) : 0;

        // 5. Save it to the database column you showed in the screenshot
        $evaluation->update([
            'overall_rating' => $averageRating
        ]);

        return redirect()->back()->with('success', 'Evaluation submitted successfully!');
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
