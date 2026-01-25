<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\CriteriaSection;
use App\Models\Student;       
use App\Models\ReviewPeriod;  
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log; 

class DashboardController extends Controller
{
    public function index()
    {
        $submissionValidation = session('success') ? true : false;
        $user = Auth::user();

        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            Auth::logout();
            return redirect()->route('login')->withErrors(['username' => 'Student profile not found.']);
        }

        $studentName = $student->first_name . ' ' . $student->last_name;

        $criteria = CriteriaSection::with('items')
            ->orderBy('section_number')
            ->get();

        $activePeriod = DB::table('review_periods')
            ->where('is_open', 1)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->first();

        $isEvaluationOpen = $activePeriod ? true : false;

        $enrolledSubjects = [];
        
        $targetPeriodId = $activePeriod ? $activePeriod->id : 0;

        if ($targetPeriodId) {
            $enrolledSubjects = DB::table('enrollments')
                ->join('class_offerings', 'enrollments.class_section_id', '=', 'class_offerings.class_section_id')
                ->join('subjects', 'class_offerings.subject_id', '=', 'subjects.id')
                ->join('faculties', 'class_offerings.faculty_id', '=', 'faculties.id')
                ->where('enrollments.student_id', $student->id)
                ->where('class_offerings.semester_id', $targetPeriodId)
                ->select(
                    'class_offerings.id as offering_id',
                    'subjects.name as subject_name',
                    'subjects.subject_code',
                    'faculties.first_name',
                    'faculties.last_name',
                    'faculties.profile_picture'
                )
                ->get();

            foreach ($enrolledSubjects as $subject) {
                $isDone = DB::table('evaluations')
                    ->where('student_id', $student->id)
                    ->where('class_offering_id', $subject->offering_id)
                    ->where('completed', true)
                    ->exists();

                $subject->is_evaluated = $isDone;
            }
        }

        $totalToEvaluate = collect($enrolledSubjects)->count();
        $completedCount = collect($enrolledSubjects)->where('is_evaluated', true)->count();

        $percentage = $totalToEvaluate > 0
            ? round(($completedCount / $totalToEvaluate) * 100)
            : 0;

        return view('student.index', compact(
            'studentName',
            'criteria',
            'completedCount',
            'totalToEvaluate',
            'percentage',
            'enrolledSubjects',
            'submissionValidation',
            'isEvaluationOpen',
            'activePeriod'
        ));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first(); 
        
        Log::info("Student ({$student->student_number}) is submitting evaluation.");

        $request->validate([
            'offering_id' => 'required|exists:class_offerings,id',
            'ratings' => 'required|array',
            'comments' => 'nullable|string'
        ]);

        $activePeriod = DB::table('review_periods')->where('is_open', true)->first();

        $totalScore = 0;
        $count = 0;
        foreach ($request->ratings as $score) {
            $totalScore += intval($score);
            $count++;
        }
        $overallRating = $count > 0 ? round($totalScore / $count, 2) : 0;

        DB::transaction(function () use ($request, $student, $activePeriod, $overallRating) {

            $offering = DB::table('class_offerings')->where('id', $request->offering_id)->first();

            $evaluationId = DB::table('evaluations')->insertGetId([
                'student_id' => $student->id,
                'faculty_id' => $offering->faculty_id,
                'class_offering_id' => $request->offering_id,
                'review_period_id' => $activePeriod->id,
                'submitted_at' => now(),
                'feedback_text' => $request->comments,
                'overall_rating' => $overallRating, 
                'completed' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($request->ratings as $questionId => $score) {
                DB::table('evaluation_responses')->insert([
                    'evaluation_id' => $evaluationId,
                    'criteria_item_id' => $questionId,
                    'score' => $score,
                    'created_at' => now()
                ]);
            }
        });

        Log::notice("SUCCESS: Evaluation Submitted by {$student->student_number}. Rating: {$overallRating}");

        return redirect()->route('student.index')->with('success', 'Evaluation submitted successfully!');
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();
        $studentNum = $student ? $student->student_number : 'Unknown';

        Log::info("Student ($studentNum) attempting password change.");

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password_hash)) {
            Log::warning("FAILED: Password change for $studentNum - Wrong current password.");
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->update([
            'password_hash' => Hash::make($request->new_password)
        ]);

        Log::notice("SUCCESS: Password changed for $studentNum");

        return back()->with('success', 'Password changed successfully!');
    }
}