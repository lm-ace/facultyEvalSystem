<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'code' => 'required|string|max:20',
            'name' => 'required|string|max:255'
        ]);

        Course::create($validated);

        return redirect()->route('admin.departments')
            ->with('success', 'Program added successfully!')
            ->with('open_dept_id', $request->department_id); 
    }

    public function update(Request $request, $id)
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

        return redirect()->route('admin.departments')
            ->with('success', 'Program updated successfully!')
            ->with('open_dept_id', $course->department_id);
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $deptId = $course->department_id; 

        // Safety check
        if($course->subjects()->count() > 0) {
             return back()->with('error', 'Cannot delete: Program has subjects assigned.')
                          ->with('open_dept_id', $deptId);
        }

        $course->delete();

        return redirect()->route('admin.departments')
            ->with('success', 'Program deleted successfully')
            ->with('open_dept_id', $deptId);
    }
}
