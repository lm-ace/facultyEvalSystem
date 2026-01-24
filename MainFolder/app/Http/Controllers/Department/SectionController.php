<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\ClassOffering;
use App\Models\ClassSection;
use App\Models\ReviewPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SectionController extends Controller
{
   public function store(Request $request)
    {
        $request->validate([
            'course_id'   => 'required',
            'department_id' => 'required', // Needed for redirection
            'year_level'  => 'required|integer',
            'block'       => 'required|string',
            'subject_ids' => 'required|array'
        ]);

        // 1. Get Semester Context
        $activePeriod = ReviewPeriod::latest('id')->first();

        if (!$activePeriod) {
            return back()->with('error', 'Please create an Academic Year / Review Period first.')
                         ->with('open_dept_id', $request->department_id)
                         ->with('open_program_id', $request->course_id);
        }

        DB::beginTransaction();
        try {
            // 2. Create Section
            $section = ClassSection::create([
                'course_id'  => $request->course_id,
                'year_level' => $request->year_level,
                'block'      => $request->block
            ]);

            // 3. Create Offerings
            foreach ($request->subject_ids as $subjectId) {
                
                $facultyId = $request->input("faculty_for.$subjectId");
                if ($facultyId === 'TBA') $facultyId = null;

                ClassOffering::create([
                    'class_section_id' => $section->id,
                    'subject_id'       => $subjectId,
                    'faculty_id'       => $facultyId,
                    'semester_id'      => $activePeriod->id,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.departments')
                ->with('success', 'Class section created successfully!')
                ->with('open_dept_id', $request->department_id)
                ->with('open_program_id', $request->course_id)
                ->with('open_tab', 'classes'); // Optional: Tell JS to switch to 'Classes' tab

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $section = ClassSection::findOrFail($id);
        $activePeriod = ReviewPeriod::latest('id')->first();
        
        if (!$activePeriod) {
             return back()->with('error', 'No Academic Year found.');
        }

        $validated = $request->validate([
            'year_level'  => 'required|integer',
            'block'       => 'required|string',
            'subject_ids' => 'required|array'
        ]);

        DB::beginTransaction();
        try {
            $section->update([
                'year_level' => $validated['year_level'],
                'block'      => $validated['block']
            ]);

            $processedSubjectIds = [];

            foreach ($request->subject_ids as $subjectId) {
                $processedSubjectIds[] = $subjectId;

                $facultyId = $request->input("faculty_for.$subjectId");
                if ($facultyId === 'TBA') $facultyId = null;

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

            // Remove Unchecked
            ClassOffering::where('class_section_id', $section->id)
                ->whereNotIn('subject_id', $processedSubjectIds)
                ->delete();

            DB::commit();

            return redirect()->route('admin.departments')
                ->with('success', 'Class section updated successfully!')
                ->with('open_dept_id', $request->department_id)
                ->with('open_program_id', $request->course_id)
                ->with('open_tab', 'classes');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        // Load the section along with its course to get navigation IDs
        $section = ClassSection::with('course')->findOrFail($id);
        
        $courseId = $section->course_id;
        $deptId = $section->course->department_id;

        // --- 1. CONSTRAINT CHECK: Prevent delete if students exist ---
        $studentCount = $section->students()->count();

        if ($studentCount > 0) {
             return redirect()->route('admin.departments')
                ->with('error', "Cannot delete: This section still has $studentCount student(s) enrolled.")
                ->with('open_dept_id', $deptId)
                ->with('open_program_id', $courseId)
                ->with('open_tab', 'classes');
        }

        // --- 2. Proceed with Delete if empty ---
        // Optional: Also delete related class offerings (subjects) to keep DB clean
        $section->classOfferings()->delete(); 
        
        $section->delete();

        return redirect()->route('admin.departments')
            ->with('success', 'Class section deleted successfully')
            ->with('open_dept_id', $deptId)
            ->with('open_program_id', $courseId)
            ->with('open_tab', 'classes');
    }
}
