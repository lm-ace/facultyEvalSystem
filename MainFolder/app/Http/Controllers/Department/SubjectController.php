<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'department_id' => 'required|exists:departments,id',
            'subject_code' => 'required|string|max:20|unique:subjects,subject_code',
            'name' => 'required|string|max:200',
            'year_level' => 'required|integer|min:1|max:6',
            'credits' => 'required|integer|min:0|max:10',
        ]);

        Subject::create($validated);

        // Redirect back, asking the view to re-open the specific Dept AND Program
        return redirect()->route('admin.departments')
            ->with('success', 'Subject added successfully')
            ->with('open_dept_id', $request->department_id)
            ->with('open_program_id', $request->course_id);
    }

    public function update(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);

        $validated = $request->validate([
            'subject_code' => 'required|string|max:20|unique:subjects,subject_code,' . $id,
            'name' => 'required|string|max:200',
            'year_level' => 'required|integer|min:1|max:6',
            'credits' => 'required|integer|min:0|max:10'
        ]);

        $subject->update($validated);

        return redirect()->route('admin.departments')
            ->with('success', 'Subject updated successfully')
            ->with('open_dept_id', $subject->department_id)
            ->with('open_program_id', $subject->course_id);
    }

    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);
        $deptId = $subject->department_id;
        $courseId = $subject->course_id;

        $subject->delete();

        return redirect()->route('admin.departments')
            ->with('success', 'Subject deleted successfully')
            ->with('open_dept_id', $deptId)
            ->with('open_program_id', $courseId);
    }
}
