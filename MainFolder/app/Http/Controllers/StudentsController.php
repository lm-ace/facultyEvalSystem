<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\EvaluationResponse;
use App\Models\User;
use App\Models\ClassOffering;
use App\Models\ReviewPeriod;
use App\Models\EvaluationCriteriaSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; // Needed for Str::limit

class StudentsController extends Controller
{
    public function index()
    {
        $student = Auth::user();

        // --- 1. CHECK IF EVALUATION IS OPEN ---
        // We check if 'is_open' is 1 AND if today is within the start/end dates.
        // Based on your screenshot:
        // DB End Date: 2025-05-01
        // Current Date: 2026-01-25
        // Result: This will return NULL (Closed) because 2026 is past 2025.
        $activePeriod = DB::table('review_periods')
            ->where('is_open', 1)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->first();

        $isEvaluationOpen = $activePeriod ? true : false;

        // --- 2. GET ENROLLED SUBJECTS ---
        $enrolledSubjects = DB::table('enrollments')
            ->join('class_offerings', 'enrollments.class_offering_id', '=', 'class_offerings.id')
            ->join('subjects', 'class_offerings.subject_id', '=', 'subjects.id')
            ->join('faculties', 'class_offerings.faculty_id', '=', 'faculties.id')
            ->join('users as faculty_user', 'faculties.user_id', '=', 'faculty_user.id')
            ->leftJoin('evaluations', function($join) use ($student) {
                $join->on('evaluations.class_offering_id', '=', 'class_offerings.id')
                     ->where('evaluations.student_id', '=', $student->id);
            })
            ->select(
                'class_offerings.id as offering_id',
                'subjects.code as subject_code',
                'subjects.name as subject_name',
                'faculty_user.first_name',
                'faculty_user.last_name',
                'faculty_user.profile_picture',
                'evaluations.id as evaluation_id'
            )
            ->where('enrollments.student_id', $student->id)
            ->get();

        foreach($enrolledSubjects as $subject) {
            $subject->is_evaluated = !is_null($subject->evaluation_id);
        }

        // --- 3. CALCULATE PROGRESS ---
        $totalToEvaluate = $enrolledSubjects->count();
        $completedCount = $enrolledSubjects->where('is_evaluated', true)->count();
        $percentage = $totalToEvaluate > 0 ? round(($completedCount / $totalToEvaluate) * 100) : 0;

        // --- 4. GET QUESTIONS ---
        $criteria = EvaluationCriteriaSection::with('items')->get();

        // --- 5. RETURN VIEW WITH ALL VARIABLES ---
        return view('student.index', [
            'studentName' => $student->first_name,
            'isEvaluationOpen' => $isEvaluationOpen, // <--- THIS WAS MISSING
            'enrolledSubjects' => $enrolledSubjects,
            'totalToEvaluate' => $totalToEvaluate,
            'completedCount' => $completedCount,
            'percentage' => $percentage,
            'criteria' => $criteria,
            'submissionValidation' => session('success') ? true : false
        ]);
    }

    // ... (Keep your existing changePassword, store, and logout methods below) ...
    
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = User::find(Auth::id());

        if (!$user) {
            Auth::logout();
            return redirect()->route('login')->withErrors(['email' => 'Session expired. Please log in again.']);
        }

        if (!Hash::check($request->current_password, $user->password_hash)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $user->password_hash = Hash::make($request->new_password);
        $user->save();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Password changed successfully! Please log in with your new password.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'offering_id' => 'required|exists:class_offerings,id',
            'ratings'     => 'required|array',
            'ratings.*'   => 'required|integer|min:1|max:5',
        ]);

        $offering = DB::table('class_offerings')->where('id', $request->offering_id)->first();

        $evaluation = Evaluation::create([
            'student_id'        => Auth::id(),
            'faculty_id'        => $offering->faculty_id,
            'class_offering_id' => $request->offering_id,
            'review_period_id'  => 1, 
            'comments'          => $request->comments,
            'submitted_at'      => now(),
            'status'            => 'completed',
            'overall_rating'    => 0, 
        ]);

        $totalScore = 0;
        $totalQuestions = 0;

        foreach ($request->ratings as $criteriaItemId => $score) {
            EvaluationResponse::create([
                'evaluation_id'    => $evaluation->id,
                'criteria_item_id' => $criteriaItemId,
                'rating'           => $score,
            ]);

            $totalScore += $score;
            $totalQuestions++;
        }

        $averageRating = $totalQuestions > 0 ? ($totalScore / $totalQuestions) : 0;

        $evaluation->update([
            'overall_rating' => $averageRating
        ]);

        return redirect()->back()->with('success', 'Evaluation submitted successfully!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}