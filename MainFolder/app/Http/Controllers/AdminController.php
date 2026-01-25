<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faculty;
use App\Models\Student;
use App\Models\Evaluation;
use App\Models\Department;
use App\Models\ReviewPeriod;

class AdminController extends Controller
{
    // --- 1. DASHBOARD LOGIC ---
    public function dashboard()
    {
        $totalFaculty = Faculty::count();
        $totalStudents = Student::count();
        $totalEvaluations = Evaluation::count();
        $departmentCount = Department::count();

        $reviewPeriods = ReviewPeriod::orderBy('id', 'desc')->get();

        $activePeriod = ReviewPeriod::where('is_open', 1)->first();
        $isEvalOpen = $activePeriod ? true : false;

        $recentEvaluations = Evaluation::with('student')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalFaculty',
            'totalStudents',
            'totalEvaluations',
            'departmentCount',
            'reviewPeriods',
            'isEvalOpen',
            'recentEvaluations'
        ));
    }

    public function toggleStatus(Request $request)
    {
        $activePeriod = ReviewPeriod::where('is_open', 1)->first();

        if ($activePeriod) {
            $activePeriod->update(['is_open' => 0]);
        } else {
            $latestPeriod = ReviewPeriod::latest()->first();
            if ($latestPeriod) {
                $latestPeriod->update(['is_open' => 1]);
            } else {
                return back()->with('error', 'No review period found to open.');
            }
        }
        return redirect()->route('admin.dashboard');
    }

    // --- 2. REPORTS PAGE LOGIC ---
    public function reports(Request $request)
    {
        $departments = Department::all();
        $semesters   = ReviewPeriod::orderBy('created_at', 'desc')->get();

        $query = Faculty::with(['department', 'evaluations']);

        if ($request->has('department') && $request->department != 'all') {
            $query->where('department_id', $request->department);
        }

        $faculties = $query->get();

        // Calculate ratings for each faculty member
        foreach ($faculties as $faculty) {
            $avgRating = $faculty->evaluations->avg('overall_rating');
            $faculty->overall_rating = $avgRating ?? 0;
        }

        return view('admin.reports', compact('departments', 'semesters', 'faculties'));
    }
}
