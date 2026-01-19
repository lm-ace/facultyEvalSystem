<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Faculty;
use App\Models\Student;
use App\Models\Evaluation;
use App\Models\Department;
use App\Models\CriteriaSection;
use App\Models\CriteriaItem;
use App\Models\ClassOffering; 
use App\Models\Course;
use App\Models\Subject;       
use App\Models\ClassSection;  
use App\Models\ReviewPeriod;  
use App\Models\Enrollment;    

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalFaculty = Faculty::count();
        $totalStudents = Student::count();

        $activePeriod = ReviewPeriod::where('status', 'active')->first();
        $activeSemester = $activePeriod ? $activePeriod->name : 'No Active Semester';
        
        $totalEvaluations = 0;
        if ($activePeriod) {
            $totalEvaluations = Evaluation::where('review_period_id', $activePeriod->id)->count();
        }

        $recentEvaluations = collect([]);

        if ($activePeriod) {
            $recentEvaluations = Evaluation::with('student')
                ->where('review_period_id', $activePeriod->id)
                ->latest('submitted_at')
                ->get()
                ->unique('student_id')
                ->take(5)
                ->map(function ($eval) use ($activePeriod) {
                    $student = $eval->student;

                    $totalSubjects = Enrollment::where('student_id', $student->id)
                        ->whereHas('classOffering', function($q) use ($activePeriod) {
                            $q->where('semester_id', $activePeriod->id);
                        })->count();

                    $completed = Evaluation::where('student_id', $student->id)
                        ->where('review_period_id', $activePeriod->id)
                        ->count();

                    $totalSubjects = $totalSubjects > 0 ? $totalSubjects : 1; 

                    return (object) [
                        'student_name'    => $student->first_name . ' ' . $student->last_name,
                        'student_id'      => $student->student_number,
                        'completed_count' => $completed,
                        'total_count'     => $totalSubjects,
                        'status'          => ($completed >= $totalSubjects) ? 'COMPLETED' : 'IN PROGRESS'
                    ];
                });
        }

        return view('admin.dashboard', [
            'totalFaculty'      => $totalFaculty,
            'totalStudents'     => $totalStudents,
            'totalEvaluations'  => $totalEvaluations,
            'activeSemester'    => $activeSemester,
            'recentEvaluations' => $recentEvaluations
        ]);
    }

    public function departments()
    {
        $institutions = Department::select('id', 'code', 'name')->get();

        $subjects = Subject::select('code', 'name')->get(); 
        
        $sections = ClassSection::with('classOfferings.subject')->get()->map(function($section) {
             return [
                 'name'     => $section->name,
                 'subjects' => $section->classOfferings->map(function($offering) {
                     return $offering->subject ? $offering->subject->code : 'Unknown';
                 })->toArray()
             ];
        });

        $faculty = Faculty::with('classOfferings.classSection')->get()->map(function($f) {
            
            $handledSections = $f->classOfferings
                ->map(fn($offering) => $offering->classSection ? $offering->classSection->name : null)
                ->filter()
                ->unique()
                ->values();

            return [
                'id'               => $f->faculty_code ?? 'N/A',
                'name'             => $f->first_name . ' ' . $f->last_name,
                'email'            => $f->email,
                'assignedSections' => $handledSections
            ];
        });

        $students = Student::with('enrollments.classOffering.classSection')->get()->map(function($s) {
            $sectionName = 'Irregular';
            $firstEnrollment = $s->enrollments->first();
            
            if ($firstEnrollment && $firstEnrollment->classOffering && $firstEnrollment->classOffering->classSection) {
                $sectionName = $firstEnrollment->classOffering->classSection->name;
            }

            return [
                'id'      => $s->student_number,
                'name'    => $s->first_name . ' ' . $s->last_name,
                'email'   => $s->user->email ?? 'N/A',
                'section' => $sectionName 
            ];
        });

        return view('admin.departments', compact('institutions', 'subjects', 'sections', 'faculty', 'students'));
    }

    public function criteria()
    {
        $sections = CriteriaSection::with(['items' => function($query) {
            $query->orderBy('position', 'asc');
        }])->orderBy('position', 'asc')->get();

        $totalQuestions = CriteriaItem::count();

        return view('admin.criteria', compact('sections', 'totalQuestions'));
    }

    public function reports(Request $request)
    {
        $departments = Department::all();
        $semesters   = ReviewPeriod::orderBy('created_at', 'desc')->get();

        $query = Faculty::with(['department', 'evaluations', 'classOfferings.classSection']);

        if ($request->has('department') && $request->department != 'all') {
            $query->whereHas('department', function($q) use ($request) {
                $q->where('code', $request->department);
            });
        }

        $facultyData = $query->get();

        $facultyReports = $facultyData->map(function($f) {
            
            $avgRating = $f->evaluations->avg('overall_rating') ?? 0;
            $responses = $f->evaluations->count();
            
            $totalStudents = Enrollment::whereHas('classOffering', function($q) use ($f) {
                $q->where('faculty_id', $f->id);
            })->count();

            $totalStudents = $totalStudents > 0 ? $totalStudents : 1;

            $status = 'PENDING';
            if ($responses == 0)     $status = 'NO DATA';
            elseif ($avgRating >= 4.50) $status = 'EXCELLENT';
            elseif ($avgRating >= 3.50) $status = 'VERY GOOD';
            elseif ($avgRating >= 2.50) $status = 'GOOD';
            elseif ($avgRating >= 1.50) $status = 'FAIR';
            else                        $status = 'POOR';

            return [
                'id'              => $f->faculty_code,
                'name'            => $f->first_name . ' ' . $f->last_name,
                'department_code' => $f->department ? $f->department->code : 'N/A',
                'rating'          => number_format($avgRating, 2),
                'responses'       => $responses,
                'total_students'  => $totalStudents,
                'status'          => $status
            ];
        });

        return view('admin.reports', [
            'departments'    => $departments,
            'semesters'      => $semesters,
            'facultyReports' => $facultyReports
        ]);
    }
}