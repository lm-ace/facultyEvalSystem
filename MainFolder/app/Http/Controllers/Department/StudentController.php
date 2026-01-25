<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\ClassSection;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\StudentCredentialMail;

class StudentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_number' => 'required|unique:students,student_number',
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'email'          => 'required|email|unique:users,email',
            'section_id'     => 'required|exists:class_sections,id',
            'department_id'  => 'required', // Needed for redirection
            'course_id'      => 'required'  // Needed for redirection
        ]);

        DB::beginTransaction();
        try {
            // 1. Generate Credentials
            $generatedPassword = Str::random(12);

            $user = User::create([
                'role'          => 'student',
                'username'      => $validated['student_number'],
                'email'         => $validated['email'],
                'password_hash' => Hash::make($generatedPassword), // Naka-hash sa database
                'is_active'     => true
            ]);

            // 2. Fetch Section details for "block_section"
            $section = ClassSection::findOrFail($request->section_id);

            // 3. Create Student Profile
            $student = Student::create([
                'user_id'        => $user->id,
                'student_number' => $validated['student_number'],
                'first_name'     => $validated['first_name'],
                'last_name'      => $validated['last_name'],
                'middle_name'    => $request->middle_name,
                'suffix'         => $request->suffix,
                'email'          => $validated['email'],
                'contact_no'     => $request->contact_no,
                'year_level'     => $request->year_level ?? 1, 
                'block_section'  => $section->block, 
                'section_id'     => $validated['section_id']
            ]);

            // 4. Create Enrollment Record
            Enrollment::create([
                'student_id'       => $student->id,
                'class_section_id' => $validated['section_id'],
                'enrolled_at'      => now(),
            ]);

            // Email credentials
            Mail::to($validated['email'])->send(new StudentCredentialMail($student, $generatedPassword));

            DB::commit();

            return redirect()->route('admin.departments')
                ->with('success', "Student registered! Credentials sent to email: " . $validated['email'])
                ->with('open_dept_id', $request->department_id)
                ->with('open_program_id', $request->course_id)
                ->with('open_tab', 'students');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email|unique:users,email,' . $student->user_id,
            'section_id' => 'required|exists:class_sections,id',
        ]);

        DB::beginTransaction();
        try {
            $oldSectionId = $student->section_id;
            $newSectionId = $validated['section_id'];

            // 1. Update Profile
            $student->update([
                'first_name'  => $validated['first_name'],
                'last_name'   => $validated['last_name'],
                'middle_name' => $request->middle_name,
                'suffix'      => $request->suffix,
                'email'       => $validated['email'],
                'contact_no'  => $request->contact_no,
                'year_level'  => $request->year_level ?? $student->year_level,
                'section_id'  => $newSectionId
            ]);

            // 2. Update User Email (Login credentials)
            if ($student->user) {
                $student->user->update(['email' => $validated['email']]);
            }

            // 3. New Enrollment if section changed
            if ($oldSectionId != $newSectionId) {
                Enrollment::create([
                    'student_id'       => $student->id,
                    'class_section_id' => $newSectionId,
                    'enrolled_at'      => now(),
                ]);
            }

            DB::commit();

            return redirect()->route('admin.departments')
                ->with('success', 'Student details updated successfully')
                ->with('open_dept_id', $request->department_id)
                ->with('open_program_id', $request->course_id)
                ->with('open_tab', 'students');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        
        // Retrieve context for redirect before deleting
        $section = ClassSection::with('course')->find($student->section_id);
        $courseId = $section ? $section->course_id : null;
        $deptId = $section ? $section->course->department_id : null;
        
        $userId = $student->user_id;

        DB::beginTransaction();
        try {
            // Delete student
            $student->delete();
            
            // Delete associated User account
            if ($userId) {
                User::where('id', $userId)->delete();
            }

            DB::commit();

            return redirect()->route('admin.departments')
                ->with('success', 'Student account deleted.')
                ->with('open_dept_id', $deptId)
                ->with('open_program_id', $courseId)
                ->with('open_tab', 'students');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}