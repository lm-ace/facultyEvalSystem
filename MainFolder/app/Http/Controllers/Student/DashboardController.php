<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class DashboardController extends Controller
{
    public function index()
    {
        $submissionValidation = session('success') ? true : false;
        // 1. Get Logged in User
        $user = Auth::user();

        // Find student record linked to this user
        $student = DB::table('students')->where('user_id', $user->id)->first();

        // Safety Check: If data is missing
        if (!$student) {
            return view('student.index', [ // Changed view name to match your file 'student.index'
                'completedCount' => 0,
                'totalToEvaluate' => 0,
                'percentage' => 0,
                'enrolledSubjects' => []
            ]);
        }

        // 2. Get the Active Review Period (Semester)
        $activePeriod = DB::table('review_periods')
            ->where('is_open', true)
            ->first();

        if (!$activePeriod) {
            return view('student.index', [
                'completedCount' => 0,
                'totalToEvaluate' => 0,
                'percentage' => 0,
                'enrolledSubjects' => []
            ]);
        }

        // 3. GET ENROLLED SUBJECTS & FACULTY
        $enrolledSubjects = DB::table('enrollments')
            ->join('class_offerings', 'enrollments.class_section_id', '=', 'class_offerings.class_section_id')
            ->join('subjects', 'class_offerings.subject_id', '=', 'subjects.id')
            ->join('faculties', 'class_offerings.faculty_id', '=', 'faculties.id')
            ->where('enrollments.student_id', $student->id)
            ->where('class_offerings.semester_id', $activePeriod->id)
            ->select(
                'class_offerings.id as offering_id',
                'subjects.name as subject_name',
                'subjects.subject_code',
                'faculties.first_name',
                'faculties.last_name',
                'faculties.profile_picture'
            )
            ->get();

        // 4. CHECK COMPLETION STATUS
        foreach ($enrolledSubjects as $subject) {
            $isDone = DB::table('evaluations')
                ->where('student_id', $student->id)
                ->where('class_offering_id', $subject->offering_id)
                ->where('completed', true)
                ->exists();

            $subject->is_evaluated = $isDone;
        }

        // 5. CALCULATE TOTAL & COMPLETED
        $totalToEvaluate = $enrolledSubjects->count();
        $completedCount = $enrolledSubjects->where('is_evaluated', true)->count();

        $percentage = $totalToEvaluate > 0
            ? round(($completedCount / $totalToEvaluate) * 100)
            : 0;

        return view('student.index', compact(
            'completedCount', 
            'totalToEvaluate', 
            'percentage', 
            'enrolledSubjects', 
            'submissionValidation'));
    }

    public function store(Request $request)
    {
        // 1. Validation
        $request->validate([
            'offering_id' => 'required|exists:class_offerings,id',
            'ratings' => 'required|array', // Array of [question_id => score]
            'comments' => 'nullable|string'
        ]);

        $user = Auth::user();
        $student = DB::table('students')->where('user_id', $user->id)->first();

        // Get active period
        $activePeriod = DB::table('review_periods')->where('is_open', true)->first();

        // 2. Database Transaction
        DB::transaction(function () use ($request, $student, $activePeriod) {

            // A. Get Faculty ID from Offering
            $offering = DB::table('class_offerings')->where('id', $request->offering_id)->first();

            // B. Create Main Evaluation Record
            $evaluationId = DB::table('evaluations')->insertGetId([
                'student_id' => $student->id,
                'faculty_id' => $offering->faculty_id,
                'class_offering_id' => $request->offering_id,
                'review_period_id' => $activePeriod->id,
                'submitted_at' => now(),
                'feedback_text' => $request->comments,
                'completed' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // C. Save Answer (Loop through the ratings array)
            foreach ($request->ratings as $questionId => $score) {
                DB::table('evaluation_responses')->insert([
                    'evaluation_id' => $evaluationId,
                    'criteria_item_id' => $questionId,
                    'score' => $score,
                    'created_at' => now()
                ]);
            }
        });

        return redirect()->route('student.index')->with('success', 'Evaluation submitted successfully!');
    }

    public function changePasword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password_hash)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->update([
            'password_hash' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password changed successfully!');
    }
}
