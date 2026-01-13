<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Evaluation;
use App\Models\SubjectAssignment;
use Illuminate\Support\Facades\Auth;

class FacultyController extends Controller
{
    public function dashboard()
    {
        $faculty = Auth::user();
        
        // Get evaluations for current semester
        $currentYear = '2025-2026'; // Get from settings
        $currentSemester = '1st'; // Get from settings
        
        $evaluations = Evaluation::where('faculty_id', $faculty->id)
            ->where('academic_year', $currentYear)
            ->where('semester', $currentSemester)
            ->whereNotNull('completed_at')
            ->get();
        
        // Get assigned subjects
        $assignedSubjects = SubjectAssignment::where('faculty_id', $faculty->id)
            ->with(['subject', 'section'])
            ->get();
        
        // Calculate statistics
        $averageRating = $evaluations->avg('average_rating') ?? 0;
        $totalEvaluations = $evaluations->count();
        
        return view('faculty.dashboard', compact(
            'faculty',
            'evaluations',
            'assignedSubjects',
            'averageRating',
            'totalEvaluations'
        ));
    }
    
    public function profile()
    {
        $faculty = Auth::user();
        return view('faculty.profile', compact('faculty'));
    }
    
    public function evaluations()
    {
        $faculty = Auth::user();
        $evaluations = Evaluation::where('faculty_id', $faculty->id)
            ->with(['student', 'subject'])
            ->orderBy('completed_at', 'desc')
            ->paginate(10);
            
        return view('faculty.evaluations', compact('faculty', 'evaluations'));
    }
    
    public function subjects()
    {
        $faculty = Auth::user();
        $assignedSubjects = SubjectAssignment::where('faculty_id', $faculty->id)
            ->with(['subject', 'section'])
            ->get();
            
        return view('faculty.subjects', compact('faculty', 'assignedSubjects'));
    }
    
    public function reports()
    {
        $faculty = Auth::user();
        
        // Get evaluation data for reports
        $evaluations = Evaluation::where('faculty_id', $faculty->id)
            ->whereNotNull('completed_at')
            ->get();
            
        // Calculate statistics
        $averageRating = $evaluations->avg('average_rating') ?? 0;
        $totalEvaluations = $evaluations->count();
        
        // Get ratings by category
        $categoryRatings = [];
        // ... (logic to calculate category ratings)
        
        return view('faculty.reports', compact(
            'faculty',
            'averageRating',
            'totalEvaluations',
            'categoryRatings'
        ));
    }
}