<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use Illuminate\Support\Facades\Log; 

class AdminDepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with([
            'courses.subjects.faculties',
            'courses.classSections.students',
            'courses.classSections.classOfferings.faculty',
            'faculties.subjects'
        ])->get();

        return view('admin.departments', compact('departments'));
    }

    public function storeDepartment(Request $request)
    {
        Log::info('=================>>> STARTING DEPARTMENT CREATION');
        Log::info('Admin Input: ', $request->all());

        $validated = $request->validate([
            'code' => 'required|unique:departments,code|max:10',
            'name' => 'required|max:255'
        ]);

        try {
            $department = Department::create($validated);

            Log::notice("SUCCESS: Created Department - {$department->code} ({$department->name})");

            return redirect()->route('admin.departments')->with('success', 'Department added!');

        } catch (\Exception $e) {
            Log::error("ERROR: Failed to create department. Reason: " . $e->getMessage());
            return back()->with('error', 'Error creating department.');
        }
    }

    public function updateDepartment(Request $request, $id)
    {
        Log::info("=================>>> STARTING DEPARTMENT UPDATE (ID: $id)");

        $validated = $request->validate([
            'code' => 'required|max:10|unique:departments,code,' . $id,
            'name' => 'required|max:255'
        ]);

        try {
            $department = Department::findOrFail($id);
            $oldCode = $department->code; 
            
            $department->update($validated);

            Log::notice("SUCCESS: Updated Department. Old Code: $oldCode -> New Code: {$department->code}");

            return redirect()->route('admin.departments')->with('success', 'Department updated successfully!');

        } catch (\Exception $e) {
            Log::error("ERROR: Failed to update department ID $id. Reason: " . $e->getMessage());
            return back()->with('error', 'Error updating department.');
        }
    }

    public function departmentDestroy($id)
    {
        Log::info("=================>>> STARTING DEPARTMENT DELETION (ID: $id)");

        try {
            $department = Department::findOrFail($id);

            if ($department->faculties()->count() > 0) {
                Log::warning("BLOCKED: Admin tried to delete department {$department->code} but it has faculties.");
                return redirect()->route('admin.departments')->with('error', 'Cannot delete: Faculty members assigned.');
            }
            if ($department->courses()->count() > 0) {
                Log::warning("BLOCKED: Admin tried to delete department {$department->code} but it has courses.");
                return redirect()->route('admin.departments')->with('error', 'Cannot delete: Courses assigned.');
            }

            $code = $department->code;
            $department->delete();

            Log::notice("SUCCESS: Deleted Department - $code");

            return redirect()->route('admin.departments')->with('success', 'Department deleted!');

        } catch (\Exception $e) {
            Log::error("ERROR: Failed to delete department ID $id. Reason: " . $e->getMessage());
            return back()->with('error', 'Error deleting department.');
        }
    }
}