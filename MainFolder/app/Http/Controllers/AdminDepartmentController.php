<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Exception;

// --- Models ---
use App\Models\Department;
use App\Models\Course;
use App\Models\Subject;
use App\Models\ClassSection;
use App\Models\ClassOffering; // Added this import
use App\Models\Faculty;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Str; // Needed for random password generation
use App\Models\ReviewPeriod; // <--- Import your model
use App\Models\Enrollment;


class AdminDepartmentController extends Controller
{
    // =========================================================================
    // MAIN VIEW
    // =========================================================================

    public function index()
    {
        $departments = Department::with([
            // Update this line to include '.faculties'
            'courses.subjects.faculties',
            'courses.classSections.students',
            'courses.classSections.classOfferings.faculty', // Load assigned faculty for edit mode
            'faculties.subjects'
        ])->get();

        return view('admin.departments', compact('departments'));
    }

    // =========================================================================
    // 1. DEPARTMENT MANAGEMENT
    // =========================================================================

    public function storeDepartment(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:departments,code|max:10',
            'name' => 'required|max:255'
        ]);

        Department::create($validated);

        return redirect()->route('admin.departments')->with('success', 'Department added!');
    }

    public function updateDepartment(Request $request, $id)
    {
        $validated = $request->validate([
            'code' => 'required|max:10|unique:departments,code,' . $id,
            'name' => 'required|max:255'
        ]);

        $department = Department::findOrFail($id);
        $department->update($validated);

        return redirect()->route('admin.departments')->with('success', 'Department updated successfully!');
    }

    public function departmentDestroy($id)
    {
        $department = Department::findOrFail($id);

        // Check for dependencies before deleting
        if ($department->faculties()->count() > 0) {
            return redirect()->route('admin.departments')->with('error', 'Cannot delete: Faculty members assigned.');
        }
        if ($department->courses()->count() > 0) {
            return redirect()->route('admin.departments')->with('error', 'Cannot delete: Courses assigned.');
        }
        if ($department->subjects()->count() > 0) {
            return redirect()->route('admin.departments')->with('error', 'Cannot delete: Subjects assigned.');
        }

        $department->delete();

        return redirect()->route('admin.departments')->with('success', 'Department deleted!');
    }

    // =========================================================================
    // 2. COURSE (PROGRAM) MANAGEMENT
    // =========================================================================

    public function storeCourse(Request $request)
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'code' => 'required|string|max:20',
            'name' => 'required|string|max:255'
        ]);

        $course = Course::create($validated);

        return response()->json([
            'success' => true,
            'course' => $course,
            'message' => 'Course added successfully'
        ]);
    }

    public function updateCourse(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|string|max:20',
            'name' => 'required|string|max:255'
        ]);

        $course = Course::findOrFail($id);
        $course->update([
            'code' => $request->code,
            'name' => $request->name
        ]);

        return response()->json([
            'success' => true,
            'course' => $course,
            'message' => 'Course updated successfully'
        ]);
    }

    public function deleteCourse($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return response()->json(['success' => true, 'message' => 'Course deleted successfully']);
    }

    // =========================================================================
    // 3. SUBJECT MANAGEMENT
    // =========================================================================

    public function storeSubject(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'subject_code' => 'required|string|max:20|unique:subjects,subject_code',
            'name' => 'required|string|max:200',
            'year_level' => 'required|integer|min:1|max:6',
            'credits' => 'required|integer|min:0|max:10',
            'department_id' => 'required|exists:departments,id',
        ]);

        $subject = Subject::create($validated);

        return response()->json([
            'success' => true,
            'subject' => $subject,
            'message' => 'Subject added successfully'
        ]);
    }

    public function updateSubject(Request $request, $id)
    {
        $validated = $request->validate([
            'subject_code' => 'required|string|max:20|unique:subjects,subject_code,' . $id,
            'name' => 'required|string|max:200',
            'year_level' => 'required|integer|min:1|max:6',
            'credits' => 'required|integer|min:0|max:10'
        ]);

        $subject = Subject::findOrFail($id);
        $subject->update($validated);

        return response()->json([
            'success' => true,
            'subject' => $subject,
            'message' => 'Subject updated successfully'
        ]);
    }

    public function deleteSubject($id)
    {
        Subject::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Subject deleted']);
    }

    // =========================================================================
    // 4. CLASS SECTION MANAGEMENT
    // =========================================================================
    public function addSection(Request $request)
    {
        // 1. Validation
        $request->validate([
            'course_id'   => 'required',
            'year_level'  => 'required|integer',
            'block'       => 'required|string',
            'subject_ids' => 'required|array'
        ]);

        // 2. Get the Semester Context
        $activePeriod = ReviewPeriod::latest('id')->first();

        if (!$activePeriod) {
            return response()->json([
                'success' => false,
                'message' => 'Error: Please create at least one Academic Year / Review Period first.'
            ]);
        }

        DB::beginTransaction();
        try {
            // 3. Create the Basic Section
            $section = ClassSection::create([
                'course_id'  => $request->course_id,
                'year_level' => $request->year_level,
                'block'      => $request->block
            ]);

            // 4. Create Class Offerings (Using the Model)
            foreach ($request->subject_ids as $subjectId) {

                // Get faculty logic
                $facultyId = $request->input("faculty_for.$subjectId");
                if ($facultyId === 'TBA') $facultyId = null;

                // --- REFACTORED: USING ELOQUENT MODEL ---
                ClassOffering::create([
                    'class_section_id' => $section->id,
                    'subject_id'       => $subjectId,
                    'faculty_id'       => $facultyId,
                    'semester_id'      => $activePeriod->id,
                    // created_at/updated_at are handled automatically!
                ]);
            }

            DB::commit();

            $section->load('classOfferings.faculty');

            return response()->json([
                'success' => true,
                'section' => $section,
                'message' => 'Class section created successfully!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateSection(Request $request, $id)
    {
        $section = ClassSection::findOrFail($id);

        // 1. Get Semester Context
        $activePeriod = ReviewPeriod::latest('id')->first();

        if (!$activePeriod) {
            return response()->json(['success' => false, 'message' => 'No Academic Year found.']);
        }

        // 2. Validation
        $validated = $request->validate([
            'year_level'  => 'required|integer',
            'block'       => 'required|string',
            'subject_ids' => 'required|array'
        ]);

        DB::beginTransaction();
        try {
            // 3. Update Basic Section Info
            $section->update([
                'year_level' => $validated['year_level'],
                'block'      => $validated['block']
            ]);

            // 4. Sync Class Offerings
            $processedSubjectIds = [];

            foreach ($request->subject_ids as $subjectId) {
                $processedSubjectIds[] = $subjectId;

                $facultyId = $request->input("faculty_for.$subjectId");
                if ($facultyId === 'TBA') $facultyId = null;

                // --- REFACTORED: USING ELOQUENT MODEL ---
                // updateOrCreate checks the first array (match attributes).
                // If found, it updates with the second array. If not, it creates new.
                ClassOffering::updateOrCreate(
                    [
                        'class_section_id' => $section->id,
                        'subject_id'       => $subjectId
                    ],
                    [
                        'faculty_id'  => $facultyId,
                        'semester_id' => $activePeriod->id
                    ]
                );
            }

            // 5. Remove Unchecked Subjects
            // We can use the model here too for consistency
            ClassOffering::where('class_section_id', $section->id)
                ->whereNotIn('subject_id', $processedSubjectIds)
                ->delete();

            DB::commit();

            $section->load('classOfferings.faculty');

            return response()->json([
                'success' => true,
                'section' => $section,
                'message' => 'Class section updated successfully!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    public function deleteSection($id)
    {
        $section = ClassSection::findOrFail($id);
        $section->delete();
        return response()->json(['success' => true]);
    }

    // =========================================================================
    // 5. FACULTY MANAGEMENT
    // =========================================================================

    public function storeFaculty(Request $request)
    {
        $validated = $request->validate([
            'faculty_code' => 'required|unique:faculties,faculty_code|unique:users,username',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'contact_no' => 'nullable|string|max:20',
            'department_id' => 'required|exists:departments,id',
            'profile_picture' => 'nullable|image|max:2048',
            'subject_ids' => 'nullable|array'
        ]);

        DB::beginTransaction();
        try {
            // 1. Handle File Upload
            $avatarPath = 'default-avatar.png';
            if ($request->hasFile('profile_picture')) {
                $path = $request->file('profile_picture')->store('faculties', 'public');
                $avatarPath = $path;
            }

            // 2. Generate Credentials
            $generatedPassword = Str::random(12);

            // 3. Create User
            $user = User::create([
                'role' => 'faculty',
                'username' => $validated['faculty_code'],
                'email' => $validated['email'],
                'password_hash' => Hash::make($generatedPassword),
                'is_active' => true
            ]);

            // 4. Create Profile
            $faculty = Faculty::create([
                'user_id' => $user->id,
                'faculty_code' => $validated['faculty_code'],
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'contact_no' => $request->contact_no,
                'department_id' => $validated['department_id'],
                'profile_picture' => $avatarPath
            ]);

            // 5. Sync Subjects
            // We use 'input' to safely get the array, or empty array if null
            $subjects = $request->input('subject_ids', []);
            $faculty->subjects()->sync($subjects);

            DB::commit();

            // --- CRITICAL FIX: REFRESH DATA ---
            // Force reload from DB to ensure 'subjects' relationship is populated
            $faculty->refresh();

            return response()->json([
                'success' => true,
                // Load the relationships so JS can count them
                'faculty' => $faculty->load(['department', 'subjects']),
                'generated_password' => $generatedPassword,
                'message' => 'Faculty member added successfully'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateFaculty(Request $request, $id)
    {
        $faculty = Faculty::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $faculty->user_id,
            'contact_no' => 'nullable|string|max:20',
            'profile_picture' => 'nullable|image|max:2048',
            'subject_ids' => 'nullable|array'
        ]);

        DB::beginTransaction();
        try {
            // 1. Picture Update
            if ($request->hasFile('profile_picture')) {
                // (Optional: Delete old file code here)
                $path = $request->file('profile_picture')->store('faculties', 'public');
                $faculty->profile_picture = $path;
            }

            // 2. Info Update
            $faculty->first_name = $validated['first_name'];
            $faculty->last_name = $validated['last_name'];
            $faculty->email = $validated['email'];
            $faculty->contact_no = $request->contact_no;
            $faculty->save();

            // 3. User Email Update
            if ($faculty->user) {
                $faculty->user->update(['email' => $validated['email']]);
            }

            // 4. Sync Subjects
            // Use input() to get the array. If nothing checked, default to [] to clear subjects.
            $subjects = $request->input('subject_ids', []);
            $faculty->subjects()->sync($subjects);

            DB::commit();

            // --- CRITICAL FIX: REFRESH DATA ---
            $faculty->refresh();

            return response()->json([
                'success' => true,
                // Load fresh data
                'faculty' => $faculty->load(['department', 'subjects']),
                'message' => 'Faculty updated successfully'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    public function deleteFaculty($id)
    {
        DB::beginTransaction();
        try {
            $faculty = Faculty::findOrFail($id);
            $userId = $faculty->user_id; // Save ID to delete User account later

            // 1. Unassign from active Classes (Set to TBA)
            // We update class_offerings to set faculty_id to NULL instead of deleting the class
            DB::table('class_offerings')
                ->where('faculty_id', $id)
                ->update(['faculty_id' => null]);

            // 2. Remove Subject Qualifications (Pivot Table)
            $faculty->subjects()->detach();

            // 3. Delete Profile Picture (Clean up storage)
            if ($faculty->profile_picture && $faculty->profile_picture !== 'default-avatar.png') {
                // Make sure to import: use Illuminate\Support\Facades\Storage;
                \Illuminate\Support\Facades\Storage::disk('public')->delete($faculty->profile_picture);
            }

            // 4. Delete the Faculty Profile
            $faculty->delete();

            // 5. Delete the User Login Account
            if ($userId) {
                User::where('id', $userId)->delete();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Faculty deleted and classes set to TBA.'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    // =========================================================================
    // 6. STUDENT MANAGEMENT
    // =========================================================================

    public function storeStudent(Request $request)
    {
        $validated = $request->validate([
            'student_number' => 'required|unique:students,student_number',
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'email'          => 'required|email|unique:users,email',
            'section_id'     => 'required|exists:class_sections,id', // Required to know where to enroll
            // Add other nullable fields as needed
        ]);

        DB::beginTransaction();
        try {
            // 1. Generate Credentials
            $generatedPassword = Str::random(12);

            // 2. Create User Account
            $user = User::create([
                'role'          => 'student',
                'username'      => $validated['student_number'],
                'email'         => $validated['email'],
                'password_hash' => Hash::make($generatedPassword),
                'is_active'     => true
            ]);

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
                'year_level'     => $request->year_level ?? 1, // Default to 1 if missing
                'block_section' => $section->block,
                'section_id'     => $validated['section_id']   // Current Section
            ]);

            // 4. CREATE ENROLLMENT RECORD (The New Part)
            Enrollment::create([
                'student_id'       => $student->id,
                'class_section_id' => $validated['section_id'],
                'enrolled_at'      => now(),
            ]);

            DB::commit();

            // Load section details for frontend
            $student->section_name = 'Year ' . $student->section->year_level . ' - ' . $student->section->block;

            return response()->json([
                'success'            => true,
                'student'            => $student,
                'generated_password' => $generatedPassword,
                'message'            => 'Student added and enrolled successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateStudent(Request $request, $id)
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
            // Check if section is changing
            $oldSectionId = $student->section_id;
            $newSectionId = $validated['section_id'];

            // 1. Update Student Profile
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

            // 2. Update User Email
            if ($student->user) {
                $student->user->update(['email' => $validated['email']]);
            }

            // 3. CREATE NEW ENROLLMENT IF SECTION CHANGED (The New Part)
            if ($oldSectionId != $newSectionId) {
                Enrollment::create([
                    'student_id'       => $student->id,
                    'class_section_id' => $newSectionId,
                    'enrolled_at'      => now(),
                ]);
            }

            DB::commit();

            // Refresh data for frontend
            $student->refresh();
            $student->section_name = 'Year ' . $student->section->year_level . ' - ' . $student->section->block;

            return response()->json([
                'success' => true,
                'student' => $student,
                'message' => 'Student details updated successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    // Delete Student
    public function destroyStudent($id)
    {
        DB::beginTransaction();
        try {
            $student = Student::findOrFail($id);
            $userId = $student->user_id;

            // 1. Delete Student Record
            $student->delete();

            // 2. Delete Linked User Account
            if ($userId) {
                User::where('id', $userId)->delete();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Student and user account deleted.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
