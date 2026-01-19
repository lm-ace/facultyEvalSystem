<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Course;
use App\Models\Subject;
use App\Models\ClassSection;
use App\Models\Faculty;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Exception;


class AdminDepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        return view('admin.departments', compact('departments'));
    }

    public function getDepartmentData($code)
    {
        $department = Department::where('code', $code)->firstOrFail();
        
        $subjects = Subject::whereHas('course', function($query) use ($department) {
            $query->where('department_id', $department->id);
        })->with('course')->get();

        $sections = ClassSection::whereHas('course', function($query) use ($department) {
            $query->where('department_id', $department->id);
        })->with('course')->get();

        $faculty = Faculty::where('department_id', $department->id)
            ->with('classOfferings.subject')
            ->get();

        $students = Student::whereHas('enrollments.classSection.course', function($query) use ($department) {
            $query->where('department_id', $department->id);
        })->with('enrollments.classSection')->get();

        return response()->json([
            'department' => $department,
            'subjects' => $subjects,
            'sections' => $sections,
            'faculty' => $faculty,
            'students' => $students
        ]);
    }

    // Add Subject
    public function addSubject(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'subject_code' => 'required|unique:subjects,subject_code',
            'name' => 'required|string|max:200',
            'year_level' => 'required|integer|min:1|max:5',
            'credits' => 'nullable|integer'
        ]);

        $subject = Subject::create($request->all());
        return response()->json(['success' => true, 'subject' => $subject]);
    }

    // Delete Subject
    public function deleteSubject($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();
        return response()->json(['success' => true]);
    }

    // Update Subject Professor Assignment
    public function assignProfessor(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'faculty_id' => 'required|exists:faculties,id',
            'section_id' => 'required|exists:class_sections,id',
            'semester_id' => 'required|exists:review_periods,id'
        ]);

        $existing = DB::table('class_offerings')
            ->where('class_section_id', $request->section_id)
            ->where('subject_id', $request->subject_id)
            ->where('semester_id', $request->semester_id)
            ->first();

        if ($existing) {
            DB::table('class_offerings')
                ->where('id', $existing->id)
                ->update(['faculty_id' => $request->faculty_id, 'updated_at' => now()]);
        } else {
            DB::table('class_offerings')->insert([
                'class_section_id' => $request->section_id,
                'subject_id' => $request->subject_id,
                'faculty_id' => $request->faculty_id,
                'semester_id' => $request->semester_id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return response()->json(['success' => true]);
    }

    // Add Section
    public function addSection(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'year_level' => 'required|integer',
            'block' => 'required|string|max:50'
        ]);

        $section = ClassSection::create($request->all());
        return response()->json(['success' => true, 'section' => $section->load('course')]);
    }

    // Delete Section
    public function deleteSection($id)
    {
        $section = ClassSection::findOrFail($id);
        $section->delete();
        return response()->json(['success' => true]);
    }

    // Assign Subjects to Section
    public function assignSubjectsToSection(Request $request)
    {
        $request->validate([
            'section_id' => 'required|exists:class_sections,id',
            'subject_ids' => 'required|array',
            'semester_id' => 'required|exists:review_periods,id'
        ]);

        DB::transaction(function () use ($request) {
            DB::table('class_offerings')
                ->where('class_section_id', $request->section_id)
                ->where('semester_id', $request->semester_id)
                ->delete();

            foreach ($request->subject_ids as $subjectId) {
                DB::table('class_offerings')->insert([
                    'class_section_id' => $request->section_id,
                    'subject_id' => $subjectId,
                    'faculty_id' => null,
                    'semester_id' => $request->semester_id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        });

        return response()->json(['success' => true]);
    }

    // Add Faculty
    public function addFaculty(Request $request)
    {
        $request->validate([
            'faculty_code' => 'required|unique:faculties,faculty_code',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'department_id' => 'required|exists:departments,id',
            'password' => 'required|min:8'
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'role' => 'faculty',
                'username' => $request->faculty_code,
                'email' => $request->email,
                'password' => Hash::make($request->password), // Changed column to 'password'
                'is_active' => true
            ]);

            $faculty = Faculty::create(array_merge($request->only([
                'faculty_code', 'first_name', 'middle_name', 'last_name', 'suffix', 'email', 'contact_no', 'department_id'
            ]), ['user_id' => $user->id, 'profile_picture' => 'default-avatar.png']));

            DB::commit();

            return response()->json([
                'success' => true,
                'faculty' => $faculty->load('department'),
                'message' => 'Faculty member added successfully'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Delete Faculty
    public function deleteFaculty($id)
    {
        DB::beginTransaction();
        try {
            $faculty = Faculty::findOrFail($id);
            $user = $faculty->user;
            
            $faculty->delete();
            if ($user) $user->delete();
            
            DB::commit();
            return response()->json(['success' => true]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Add Student
    public function addStudent(Request $request)
    {
        $request->validate([
            'student_number' => 'required|unique:students,student_number',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'section_id' => 'required|exists:class_sections,id',
            'password' => 'required|min:8'
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'role' => 'student',
                'username' => $request->student_number,
                'email' => $request->email,
                'password' => Hash::make($request->password), // Changed column to 'password'
                'is_active' => true
            ]);

            $student = Student::create(array_merge($request->only([
                'student_number', 'first_name', 'middle_name', 'last_name', 'suffix', 'year_level', 'block_section', 'contact_no'
            ]), ['user_id' => $user->id]));

            DB::table('enrollments')->insert([
                'student_id' => $student->id,
                'class_section_id' => $request->section_id,
                'enrolled_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'student' => $student->load('enrollments.classSection'),
                'message' => 'Student registered successfully'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Delete Student
    public function deleteStudent($id)
    {
        DB::beginTransaction();
        try {
            $student = Student::findOrFail($id);
            $user = $student->user;
            
            $student->delete();
            if ($user) $user->delete();
            
            DB::commit();
            return response()->json(['success' => true]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}