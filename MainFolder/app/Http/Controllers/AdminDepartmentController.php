<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;

class AdminDepartmentController extends Controller
{
    // =========================================================================
    // MAIN VIEW (The only thing this controller needs to load)
    // =========================================================================
    public function index()
    {
        // We load all the relationships here so the Drill-Down UI works instantly
        $departments = Department::with([
            'courses.subjects.faculties',
            'courses.classSections.students',
            'courses.classSections.classOfferings.faculty',
            'faculties.subjects'
        ])->get();

        return view('admin.departments', compact('departments'));
    }

    // =========================================================================
    // DEPARTMENT MANAGEMENT (Keep this here for now)
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

        // Safety Checks
        if ($department->faculties()->count() > 0) {
            return redirect()->route('admin.departments')->with('error', 'Cannot delete: Faculty members assigned.');
        }
        if ($department->courses()->count() > 0) {
            return redirect()->route('admin.departments')->with('error', 'Cannot delete: Courses assigned.');
        }

        $department->delete();

        return redirect()->route('admin.departments')->with('success', 'Department deleted!');
    }
}