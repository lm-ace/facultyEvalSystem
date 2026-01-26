<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProgramController extends Controller
{
    public function store(Request $request)
    {
        Log::info("Admin is adding a program. Input: " . json_encode($request->only('code', 'name', 'department_id')));

        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'code' => 'required|string|max:20',
            'name' => 'required|string|max:255'
        ]);

        $program = Course::create($validated);

        Log::notice("SUCCESS: Program Created - {$program->code} ({$program->name})");

        return redirect()->route('admin.departments')
            ->with('success', 'Program added successfully!')
            ->with('open_dept_id', $request->department_id); 
    }

    public function update(Request $request, $id)
    {
        Log::info("Admin is updating Program ID: $id");

        $request->validate([
            'code' => 'required|string|max:20',
            'name' => 'required|string|max:255'
        ]);

        $course = Course::findOrFail($id);
        
        $course->update([
            'code' => $request->code,
            'name' => $request->name
        ]);

        Log::notice("SUCCESS: Program Updated - {$course->code}");

        return redirect()->route('admin.departments')
            ->with('success', 'Program updated successfully!')
            ->with('open_dept_id', $course->department_id);
    }

    public function destroy($id)
    {
        Log::info("Admin is deleting Program ID: $id");

        $course = Course::findOrFail($id);
        $deptId = $course->department_id; 

        if($course->subjects()->count() > 0) {
             Log::warning("BLOCKED: Admin tried to delete program {$course->code} but it has subjects.");
             return back()->with('error', 'Cannot delete: Program has subjects assigned.')
                          ->with('open_dept_id', $deptId);
        }

        $code = $course->code;
        $name = $course->name;

        $course->delete();

        Log::notice("SUCCESS: Program Deleted - {$code} ({$name})");

        return redirect()->route('admin.departments')
            ->with('success', 'Program deleted successfully')
            ->with('open_dept_id', $deptId);
    }
}