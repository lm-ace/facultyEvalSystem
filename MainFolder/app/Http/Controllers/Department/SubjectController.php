<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 

class SubjectController extends Controller
{
    public function store(Request $request)
    {
        Log::info("Admin is adding a subject. Input: " . json_encode($request->only('subject_code', 'name')));

        $validated = $request->validate([
            'course_id'     => 'required|exists:courses,id',
            'department_id' => 'required|exists:departments,id',
            'subject_code'  => 'required|string|max:20|unique:subjects,subject_code',
            'name'          => 'required|string|max:200',
            'year_level'    => 'required|integer|min:1|max:6',
            'credits'       => 'required|integer|min:0|max:10',
        ]);

        $subject = Subject::create($validated);

        Log::notice("SUCCESS: Subject Created - {$subject->subject_code} ({$subject->name})");

        return redirect()->route('admin.departments')
            ->with('success', 'Subject added successfully')
            ->with('open_dept_id', $request->department_id)
            ->with('open_program_id', $request->course_id);
    }

    public function update(Request $request, $id)
    {
        Log::info("Admin is updating Subject ID: $id");

        $subject = Subject::findOrFail($id);

        $validated = $request->validate([
            'subject_code' => 'required|string|max:20|unique:subjects,subject_code,' . $id,
            'name'         => 'required|string|max:200',
            'year_level'   => 'required|integer|min:1|max:6',
            'credits'      => 'required|integer|min:0|max:10'
        ]);

        $subject->update($validated);

        Log::notice("SUCCESS: Subject Updated - Code: {$subject->subject_code}");

        return redirect()->route('admin.departments')
            ->with('success', 'Subject updated successfully')
            ->with('open_dept_id', $subject->department_id)
            ->with('open_program_id', $subject->course_id);
    }

    public function destroy($id)
    {
        Log::info("Admin is deleting Subject ID: $id");

        $subject = Subject::findOrFail($id);
        $deptId = $subject->department_id;
        $courseId = $subject->course_id;

        $code = $subject->subject_code;
        $name = $subject->name;

        $subject->delete();

        Log::notice("SUCCESS: Subject Deleted - {$code} ({$name})");

        return redirect()->route('admin.departments')
            ->with('success', 'Subject deleted successfully')
            ->with('open_dept_id', $deptId)
            ->with('open_program_id', $courseId);
    }
}