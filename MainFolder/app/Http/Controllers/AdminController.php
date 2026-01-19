<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Faculty;
use App\Models\Student;
use App\Models\Evaluation;
use App\Models\Department;
use App\Models\ReviewPeriod;
use App\Models\Subject;  // Added
use App\Models\ClassSection;  // Added (This represents your 'Classes')

class AdminController extends Controller
{
    // --- 1. EXISTING DASHBOARD LOGIC ---
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
            'totalFaculty', 'totalStudents', 'totalEvaluations', 
            'departmentCount', 'reviewPeriods', 'isEvalOpen', 'recentEvaluations'
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

    // --- 2. NEW DEPARTMENTS PAGE LOGIC ---

    public function departments()
    {
        // A. Fetch Subjects
        $subjects = Subject::all()->map(function($s) {
            return [
                'id' => $s->id,
                'code' => $s->subject_code,
                'name' => $s->description,
                'assignedProf' => '' // Logic for this can be added if needed
            ];
        });

        // B. Fetch Sections (Classes) with their Subjects
        $sections = ClassSection::with('subjects')->get()->map(function($s) {
            return [
                'id' => $s->id,
                'name' => $s->name,
                'subjects' => $s->subjects->pluck('subject_code')->toArray()
            ];
        });

        // C. Fetch Faculty with assigned Sections
        // Assuming Faculty belongsToMany Sections
        $faculty = Faculty::with('sections')->get()->map(function($f) {
            return [
                'id' => $f->id,
                'faculty_id' => $f->faculty_id_number ?? 'N/A',
                'name' => $f->first_name . ' ' . $f->last_name, // Adjust based on your columns
                'email' => $f->email,
                'assignedSections' => $f->sections->pluck('name')->toArray()
            ];
        });

        // D. Fetch Students
        $students = Student::with('section')->get()->map(function($std) {
            return [
                'id' => $std->id,
                'student_number' => $std->student_number,
                'name' => $std->first_name . ' ' . $std->last_name, // Adjust based on columns
                'section' => $std->section ? $std->section->name : 'N/A',
                'email' => $std->email
            ];
        });

        return view('admin.departments', compact('subjects', 'sections', 'faculty', 'students'));
    }

    // --- 3. AJAX API METHODS (For Saving/Deleting) ---

    // SUBJECTS
    public function storeSubject(Request $request) {
        $validated = $request->validate([
            'subject_code' => 'required|unique:subjects,subject_code',
            'description' => 'required'
        ]);
        $subject = Subject::create($validated);
        return response()->json($subject);
    }

    public function destroySubject($id) {
        Subject::destroy($id);
        return response()->json(['status' => 'success']);
    }

    // SECTIONS (Classes)
    public function storeSection(Request $request) {
        $validated = $request->validate(['name' => 'required|unique:sections,name']);
        $section = ClassSection::create($validated);
        return response()->json($section);
    }
    
    public function updateSectionSubjects(Request $request, $id) {
        // Syncs subjects to a section (Many-to-Many)
        $section = ClassSection::findOrFail($id);
        // Expects an array of subject IDs or Codes. 
        // If your inputs send Codes, you might need to find IDs first.
        $subjects = Subject::whereIn('subject_code', $request->subjects)->pluck('id');
        $section->subjects()->sync($subjects);
        return response()->json(['status' => 'success']);
    }

    public function destroySection($id) {
        ClassSection::destroy($id);
        return response()->json(['status' => 'success']);
    }

    // FACULTY
    public function storeFaculty(Request $request) {
        $validated = $request->validate([
            'faculty_id_number' => 'required|unique:faculties',
            'name' => 'required', // or split first_name/last_name
            'email' => 'required|email|unique:faculties',
            'password' => 'required'
        ]);
        
        // Handle name splitting if your DB uses first_name/last_name
        $nameParts = explode(' ', $validated['name'], 2);
        
        $faculty = Faculty::create([
            'faculty_id_number' => $validated['faculty_id_number'],
            'first_name' => $nameParts[0],
            'last_name' => $nameParts[1] ?? '',
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);
        
        // Simulate email sending here if needed
        
        return response()->json($faculty);
    }
    
    public function updateFacultySections(Request $request, $id) {
        $faculty = Faculty::findOrFail($id);
        $sections = ClassSection::whereIn('name', $request->sections)->pluck('id');
        $faculty->sections()->sync($sections); // Assumes belongsToMany relationship
        return response()->json(['status' => 'success']);
    }

    public function destroyFaculty($id) {
        Faculty::destroy($id);
        return response()->json(['status' => 'success']);
    }

    // STUDENTS
    public function storeStudent(Request $request) {
        $validated = $request->validate([
            'student_number' => 'required|unique:students',
            'name' => 'required',
            'email' => 'required|email|unique:students',
            'section' => 'required', // This comes in as section NAME
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

    public function destroyStudent($id) {
        Student::destroy($id);
        return response()->json(['status' => 'success']);
    }
}