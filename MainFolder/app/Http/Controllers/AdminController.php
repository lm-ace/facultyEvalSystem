<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Faculty;
use App\Models\Student;
use App\Models\Evaluation;
use App\Models\Department;
use App\Models\ReviewPeriod;
use App\Models\Subject;
use App\Models\ClassSection;

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

    public function departments()
    {
        $departments = Department::all();

        // A. Fetch Subjects
        $subjects = Subject::all()->map(function ($s) {
            return [
                'id' => $s->id,
                'code' => $s->subject_code,
                'name' => $s->description,
                'assignedProf' => ''
            ];
        });

        // B. Fetch Sections (Requires 'subjects' relationship in ClassSection Model)
        $sections = ClassSection::with('subjects')->get()->map(function ($s) {
            return [
                'id' => $s->id,
                'name' => $s->name,
                'subjects' => $s->subjects->pluck('subject_code')->toArray()
            ];
        });

        // C. Fetch Faculty (Requires 'sections' relationship in Faculty Model)
        $faculty = Faculty::with('sections')->get()->map(function ($f) {
            return [
                'id' => $f->id,
                'faculty_id' => $f->faculty_code ?? 'N/A',
                'name' => $f->first_name . ' ' . $f->last_name,
                'email' => $f->email,
                'assignedSections' => $f->sections->pluck('name')->toArray()
            ];
        });

        // D. Fetch Students
        $students = Student::with('section')->get()->map(function ($std) {
            return [
                'id' => $std->id,
                'student_number' => $std->student_number,
                'name' => $std->first_name . ' ' . $std->last_name,
                'section' => $std->section ? $std->section->name : 'N/A',
                'email' => $std->email
            ];
        });

        return view('admin.departments', compact('departments', 'subjects', 'sections', 'faculty', 'students'));
    }

    public function criteria()
    {
        return view('admin.criteria');
    }

    // --- 5. AJAX API METHODS ---

    // SUBJECTS
    public function storeSubject(Request $request)
    {
        $validated = $request->validate([
            'subject_code' => 'required|unique:subjects,subject_code',
            'description' => 'required'
        ]);
        $subject = Subject::create($validated);
        return response()->json($subject);
    }

    public function destroySubject($id)
    {
        Subject::destroy($id);
        return response()->json(['status' => 'success']);
    }

    // SECTIONS
    public function storeSection(Request $request)
    {
        $validated = $request->validate(['name' => 'required|unique:class_sections,name']);
        $section = ClassSection::create($validated);
        return response()->json($section);
    }

    public function updateSectionSubjects(Request $request, $id)
    {
        $section = ClassSection::findOrFail($id);
        $subjects = Subject::whereIn('subject_code', $request->subjects)->pluck('id');
        $section->subjects()->sync($subjects);
        return response()->json(['status' => 'success']);
    }

    public function destroySection($id)
    {
        ClassSection::destroy($id);
        return response()->json(['status' => 'success']);
    }

    // FACULTY
    public function storeFaculty(Request $request)
    {
        $validated = $request->validate([
            'faculty_code' => 'required|unique:faculties,faculty_code',
            'name' => 'required',
            'email' => 'required|email|unique:faculties,email',
            'password' => 'required'
        ]);

        $nameParts = explode(' ', $validated['name'], 2);

        $faculty = Faculty::create([
            'faculty_code' => $validated['faculty_code'],
            'first_name' => $nameParts[0],
            'last_name' => $nameParts[1] ?? '',
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json($faculty);
    }

    public function updateFacultySections(Request $request, $id)
    {
        $faculty = Faculty::findOrFail($id);
        $sections = ClassSection::whereIn('name', $request->sections)->pluck('id');
        $faculty->sections()->sync($sections);
        return response()->json(['status' => 'success']);
    }

    public function destroyFaculty($id)
    {
        Faculty::destroy($id);
        return response()->json(['status' => 'success']);
    }

    // STUDENTS
    public function storeStudent(Request $request)
    {
        $validated = $request->validate([
            'student_number' => 'required|unique:students',
            'name' => 'required',
            'email' => 'required|email|unique:students',
            'section' => 'required',
            'password' => 'required'
        ]);

        $section = ClassSection::where('name', $validated['section'])->firstOrFail();
        $nameParts = explode(' ', $validated['name'], 2);

        $student = Student::create([
            'student_number' => $validated['student_number'],
            'first_name' => $nameParts[0],
            'last_name' => $nameParts[1] ?? '',
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'section_id' => $section->id
        ]);

        return response()->json($student);
    }

    public function destroyStudent($id)
    {
        Student::destroy($id);
        return response()->json(['status' => 'success']);
    }
}
