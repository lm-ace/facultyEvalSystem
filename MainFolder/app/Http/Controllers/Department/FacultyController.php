<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\FacultyCredentialMail;

class FacultyController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'faculty_code' => 'required|unique:faculties,faculty_code|unique:users,username',
            'first_name'   => 'required|string|max:100',
            'last_name'    => 'required|string|max:100',
            'email'        => 'required|email|unique:users,email',
            'department_id' => 'required|exists:departments,id',
            'contact_no'   => 'nullable|string|max:20',
            'profile_picture' => 'nullable|image|max:2048',
            'subject_ids'  => 'nullable|array'
        ]);

        DB::beginTransaction();
        try {
            // 1. Handle File Upload
            $avatarPath = 'default-avatar.png';
            if ($request->hasFile('profile_picture')) {
                $avatarPath = $request->file('profile_picture')->store('faculties', 'public');
            }

            // 2. Create User Credentials
            $generatedPassword = Str::random(12);
            $user = User::create([
                'role'          => 'faculty',
                'username'      => $validated['faculty_code'],
                'email'         => $validated['email'],
                'password_hash' => Hash::make($generatedPassword),
                'is_active'     => true
            ]);

            // 3. Create Faculty Profile
            $faculty = Faculty::create([
                'user_id'         => $user->id,
                'faculty_code'    => $validated['faculty_code'],
                'first_name'      => $validated['first_name'],
                'last_name'       => $validated['last_name'],
                'email'           => $validated['email'],
                'contact_no'      => $request->contact_no,
                'department_id'   => $validated['department_id'],
                'profile_picture' => $avatarPath
            ]);

            // 4. Sync Subjects
            $faculty->subjects()->sync($request->input('subject_ids', []));

            // Email credentials
            Mail::to($validated['email'])->send(new FacultyCredentialMail($faculty, $generatedPassword));

            DB::commit();

            return redirect()->route('admin.departments')
                ->with('success', "Faculty added! Credentials sent to email: " . $validated['email'])
                ->with('open_dept_id', $request->department_id)
                ->with('open_program_id', $request->course_id)
                ->with('open_tab', 'faculty');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $faculty = Faculty::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email|unique:users,email,' . $faculty->user_id,
            'contact_no' => 'nullable|string|max:20',
            'profile_picture' => 'nullable|image|max:2048',
            'subject_ids' => 'nullable|array'
        ]);

        DB::beginTransaction();
        try {
            // 1. Update Picture
            if ($request->hasFile('profile_picture')) {
                if ($faculty->profile_picture && $faculty->profile_picture !== 'default-avatar.png') {
                    Storage::disk('public')->delete($faculty->profile_picture);
                }
                $faculty->profile_picture = $request->file('profile_picture')->store('faculties', 'public');
            }

            // 2. Update Details
            $faculty->update([
                'first_name' => $validated['first_name'],
                'last_name'  => $validated['last_name'],
                'email'      => $validated['email'],
                'contact_no' => $request->contact_no
            ]);

            // 3. Update User Email
            if ($faculty->user) {
                $faculty->user->update(['email' => $validated['email']]);
            }

            // 4. Sync Subjects
            $faculty->subjects()->sync($request->input('subject_ids', []));

            DB::commit();

            return redirect()->route('admin.departments')
                ->with('success', 'Faculty updated successfully')
                ->with('open_dept_id', $request->department_id)
                ->with('open_program_id', $request->course_id)
                ->with('open_tab', 'faculty');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $faculty = Faculty::findOrFail($id);
        $deptId = $faculty->department_id;
        $userId = $faculty->user_id;

        DB::beginTransaction();
        try {
            DB::table('class_offerings')
                ->where('faculty_id', $id)
                ->update(['faculty_id' => null]);

            $faculty->subjects()->detach();

            if ($faculty->profile_picture && $faculty->profile_picture !== 'default-avatar.png') {
                Storage::disk('public')->delete($faculty->profile_picture);
            }

            $faculty->delete();
            if ($userId) {
                User::where('id', $userId)->delete();
            }

            DB::commit();

            return redirect()->route('admin.departments')
                ->with('success', 'Faculty deleted successfully')
                ->with('open_dept_id', $deptId)
                ->with('open_tab', 'faculty');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
