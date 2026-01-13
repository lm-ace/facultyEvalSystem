<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use App\Models\Subject;
use App\Models\Section;
use App\Models\SubjectAssignment;
use App\Models\EvaluationCriterion;
use App\Models\Evaluation;
use App\Models\SystemSetting;
use App\Models\AuditLog;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Mail\CredentialsMail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'password' => 'required',
            'role' => 'required'
        ]);

        $credentials = [
            'user_id' => $request->user_id,
            'password' => $request->password,
            'role' => $request->role,
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('redirect.dashboard');
        }

        return back()->withErrors([
            'password' => 'The credentials provided do not match our records.',
        ])->onlyInput('user_id');
    }

    /**
     * Display admin dashboard
     */
    public function dashboard()
    {
        $totalFaculty = User::where('role', 'faculty')->count();
        $totalStudents = User::where('role', 'student')->count();
        $totalEvaluations = Evaluation::whereNotNull('completed_at')->count();

        $recentEvaluations = Evaluation::with(['student', 'faculty', 'subject'])
            ->whereNotNull('completed_at')
            ->orderBy('completed_at', 'desc')
            ->limit(10)
            ->get();

        $evaluationStatus = SystemSetting::where('key', 'evaluation_status')->first()->value ?? 'closed';
        $currentSemester = SystemSetting::where('key', 'current_semester')->first()->value ?? '1st';
        $currentYear = SystemSetting::where('key', 'current_academic_year')->first()->value ?? '2025-2026';

        $facultyByDept = User::select('department_code', DB::raw('COUNT(*) as count'))
            ->where('role', 'faculty')
            ->whereNotNull('department_code')
            ->groupBy('department_code')
            ->get();

        $topFaculty = Evaluation::select('faculty_id', DB::raw('AVG(average_rating) as avg_rating'))
            ->whereNotNull('completed_at')
            ->groupBy('faculty_id')
            ->orderBy('avg_rating', 'desc')
            ->limit(5)
            ->with('faculty')
            ->get();

        return view('admin.dashboard', compact(
            'totalFaculty',
            'totalStudents',
            'totalEvaluations',
            'recentEvaluations',
            'evaluationStatus',
            'currentSemester',
            'currentYear',
            'facultyByDept',
            'topFaculty'
        ));
    }

    /**
     * Display departments management page
     */
    public function departments()
    {
        $departments = Department::withCount([
            'users as faculty_count' => function ($query) {
                $query->where('role', 'faculty');
            },
            'users as student_count' => function ($query) {
                $query->where('role', 'student');
            },
            'subjects',
            'sections'
        ])->orderBy('name')->get();

        return view('admin.departments', compact('departments'));
    }

    /**
     * Display department detail page
     */
    public function departmentDetail($code)
    {
        $department = Department::where('code', $code)->firstOrFail();

        $subjects = Subject::where('department_code', $code)->get();

        $sections = Section::where('department_code', $code)->get();

        $faculty = User::where('department_code', $code)
            ->where('role', 'faculty')
            ->withCount(['assignedSubjects' => function($query) {
                $query->where('academic_year', SystemSetting::where('key', 'current_academic_year')->first()->value ?? '2025-2026')
                      ->where('semester', SystemSetting::where('key', 'current_semester')->first()->value ?? '1st');
            }])
            ->get();

        $students = User::where('department_code', $code)
            ->where('role', 'student')
            ->orderBy('section')
            ->get()
            ->groupBy('section');

        return view('department-detail', compact(
            'department',
            'subjects',
            'sections',
            'faculty',
            'students'
        ));
    }

    /**
     * Display criteria management page
     */
    public function criteria()
    {
        $criteria = EvaluationCriterion::orderBy('category')
            ->orderBy('order')
            ->get()
            ->groupBy('category');

        $categories = [
            'Instructional Competence',
            'Classroom Management',
            'Assessment and Feedback',
            'Professionalism'
        ];

        return view('admin.criteria', compact('criteria', 'categories'));
    }

    /**
     * Store new evaluation criterion
     */
    public function storeCriterion(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:100',
            'question' => 'required|string|max:1000',
        ]);

        $lastOrder = EvaluationCriterion::where('category', $request->category)
            ->max('order') ?? 0;

        $criterion = EvaluationCriterion::create([
            'question' => $request->question,
            'category' => $request->category,
            'order' => $lastOrder + 1,
            'is_active' => true
        ]);

        AuditLog::log(
            'CRITERIA_CREATE',
            'Created new evaluation criterion: ' . substr($request->question, 0, 50) . '...'
        );

        return redirect()->route('admin.criteria')
            ->with('success', 'Question added successfully!');
    }

    /**
     * Update evaluation criterion
     */
    public function updateCriterion(Request $request, $id)
    {
        $request->validate([
            'question' => 'required|string|max:1000',
        ]);

        $criterion = EvaluationCriterion::findOrFail($id);
        $criterion->update(['question' => $request->question]);

        AuditLog::log(
            'CRITERIA_UPDATE',
            'Updated evaluation criterion ID: ' . $id
        );

        return redirect()->route('admin.criteria')
            ->with('success', 'Question updated successfully!');
    }

    /**
     * Delete evaluation criterion
     */
    public function destroyCriterion($id)
    {
        $criterion = EvaluationCriterion::findOrFail($id);
        $criterion->delete();

        AuditLog::log(
            'CRITERIA_DELETE',
            'Deleted evaluation criterion ID: ' . $id
        );

        return redirect()->route('admin.criteria')
            ->with('success', 'Question deleted successfully!');
    }

    /**
     * Display reports page
     */
    public function reports(Request $request)
    {
        $departments = Department::where('is_active', true)->get();

        $selectedDept = $request->get('department', 'all');
        $selectedSemester = $request->get('semester', '1st Semester 2025-2026');

        $semesterData = $this->parseSemester($selectedSemester);
        $academicYear = $semesterData['year'] ?? '2025-2026';
        $semester = $semesterData['semester'] ?? '1st';

        $query = User::where('role', 'faculty')
            ->with(['department'])
            ->withCount([
                'evaluationsReceived as total_evaluations' => function ($query) use ($academicYear, $semester) {
                    $query->where('academic_year', $academicYear)
                          ->where('semester', $semester)
                          ->whereNotNull('completed_at');
                }
            ]);

        if ($selectedDept !== 'all') {
            $query->where('department_code', $selectedDept);
        }

        $faculty = $query->get()->map(function ($facultyMember) use ($academicYear, $semester) {
            $evaluations = Evaluation::where('faculty_id', $facultyMember->id)
                ->where('academic_year', $academicYear)
                ->where('semester', $semester)
                ->whereNotNull('completed_at')
                ->get();

            $averageRating = $evaluations->avg('average_rating') ?? 0;

            $status = $this->getRatingStatus($averageRating);

            $totalResponses = $evaluations->count();
            $expectedResponses = $this->getExpectedResponses($facultyMember->id, $academicYear, $semester);

            return [
                'id' => $facultyMember->id,
                'user_id' => $facultyMember->user_id,
                'name' => $facultyMember->name,
                'department' => $facultyMember->department->code ?? 'N/A',
                'department_name' => $facultyMember->department->name ?? 'N/A',
                'average_rating' => round($averageRating, 2),
                'total_responses' => $totalResponses,
                'status' => $status,
                'expected_responses' => $expectedResponses,
                'response_rate' => $expectedResponses > 0 ? round(($totalResponses / $expectedResponses) * 100, 1) : 0
            ];
        });

        $faculty = $faculty->sortByDesc('average_rating')->values();

        return view('admin.reports', compact(
            'departments',
            'faculty',
            'selectedDept',
            'selectedSemester'
        ));
    }

    /**
     * Display faculty performance report
     */
    public function facultyReport($id)
    {
        $faculty = User::with(['department'])
            ->where('role', 'faculty')
            ->findOrFail($id);

        $currentSemester = SystemSetting::where('key', 'current_semester')->first()->value ?? '1st';
        $currentYear = SystemSetting::where('key', 'current_academic_year')->first()->value ?? '2025-2026';

        $evaluations = Evaluation::where('faculty_id', $faculty->id)
            ->where('academic_year', $currentYear)
            ->where('semester', $currentSemester)
            ->whereNotNull('completed_at')
            ->with(['subject', 'student'])
            ->get();

        $categoryAverages = [];
        $allRatings = [];

        foreach ($evaluations as $evaluation) {
            if ($evaluation->ratings) {
                $ratings = is_string($evaluation->ratings)
                    ? json_decode($evaluation->ratings, true)
                    : $evaluation->ratings;

                if (is_array($ratings)) {
                    foreach ($ratings as $criterionId => $rating) {
                        $criterion = EvaluationCriterion::find($criterionId);
                        if ($criterion) {
                            $category = $criterion->category;

                            if (!isset($categoryAverages[$category])) {
                                $categoryAverages[$category] = [
                                    'total' => 0,
                                    'count' => 0
                                ];
                            }

                            $categoryAverages[$category]['total'] += floatval($rating);
                            $categoryAverages[$category]['count']++;

                            $allRatings[] = floatval($rating);
                        }
                    }
                }
            }
        }

        $categoryAveragesFinal = [];
        foreach ($categoryAverages as $category => $data) {
            $categoryAveragesFinal[$category] = [
                'average' => round($data['total'] / max($data['count'], 1), 2),
                'count' => $data['count']
            ];
        }

        $overallAverage = count($allRatings) > 0
            ? round(array_sum($allRatings) / count($allRatings), 2)
            : 0;

        $comments = $evaluations->pluck('comments')->filter()->values();

        $facultyRank = $this->calculateFacultyRank($faculty->id, $currentYear, $currentSemester);

        return view('admin.faculty-report', compact(
            'faculty',
            'evaluations',
            'categoryAveragesFinal',
            'overallAverage',
            'comments',
            'facultyRank',
            'currentSemester',
            'currentYear'
        ));
    }

    /**
     * Add new faculty member
     */
    public function addFaculty(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|unique:users,user_id|regex:/^[A-Za-z0-9\-_]+$/',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'department_code' => 'required|exists:departments,code',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'user_id.regex' => 'User ID can only contain letters, numbers, dashes and underscores.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Please fix the errors below.'
            ], 422);
        }

        DB::beginTransaction();

        try {
            $profileImagePath = null;
            if ($request->hasFile('profile_image')) {
                $profileImagePath = $request->file('profile_image')->store('profiles', 'public');
            }

            $faculty = User::create([
                'user_id' => $request->user_id,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'faculty',
                'department_code' => $request->department_code,
                'profile_image' => $profileImagePath,
                'email_verified_at' => now(),
                'is_active' => true,
            ]);

            try {
                Mail::to($faculty->email)->send(new CredentialsMail(
                    $faculty,
                    $request->password,
                    'faculty'
                ));
            } catch (\Exception $emailException) {
                Log::error('Failed to send credentials email: ' . $emailException->getMessage());
            }

            AuditLog::log(
                'USER_CREATE',
                'Created faculty account: ' . $faculty->name . ' (' . $faculty->user_id . ')'
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Faculty member added successfully!',
                'faculty' => [
                    'id' => $faculty->id,
                    'user_id' => $faculty->user_id,
                    'name' => $faculty->name,
                    'email' => $faculty->email,
                    'department_code' => $faculty->department_code,
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error adding faculty: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error adding faculty: ' . ($e->getMessage())
            ], 500);
        }
    }

public function addStudent(Request $request)
{
    $validator = Validator::make($request->all(), [
        'user_id' => 'required|unique:students,student_number|regex:/^[A-Za-z0-9\-_]+$/',
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:students,email',
        'password' => 'required|min:8|confirmed',
        'section_id' => 'required|exists:sections,id', 
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        $student = null;

        DB::transaction(function () use ($request, &$student) {
            // Siguraduhin na 'Student' model ay naka-link sa 'students' table
            $student = Student::create([
                'student_number' => $request->user_id,
                'full_name'      => $request->name,
                'email'          => $request->email,
                'password'       => Hash::make($request->password),
                'section_id'     => $request->section_id,
                'created_at'     => now(),
            ]);

            AuditLog::log('STUDENT_CREATE', 'Created: ' . $student->full_name);
        });

        if ($student) {
            Mail::to($student->email)->send(new CredentialsMail($student, $request->password, 'student'));
            return response()->json(['success' => true, 'message' => 'Saved and Emailed!']);
        }

    } catch (\Exception $e) {
        Log::error('Error: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

    /**
     * Add new subject
     */
    public function addSubject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:subjects,code|regex:/^[A-Za-z0-9\-_\.]+$/',
            'name' => 'required|string|max:255',
            'department_code' => 'required|exists:departments,code',
            'units' => 'required|integer|min:1|max:5',
            'description' => 'nullable|string',
        ], [
            'code.regex' => 'Subject code can only contain letters, numbers, dashes, dots and underscores.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Please fix the errors below.'
            ], 422);
        }

        try {
            $subject = Subject::create([
                'code' => strtoupper($request->code),
                'name' => $request->name,
                'department_code' => $request->department_code,
                'units' => $request->units,
                'description' => $request->description,
                'is_active' => true,
            ]);

            AuditLog::log(
                'SUBJECT_CREATE',
                'Created subject: ' . $subject->code . ' - ' . $subject->name
            );

            return response()->json([
                'success' => true,
                'message' => 'Subject added successfully!',
                'subject' => $subject
            ]);

        } catch (\Exception $e) {
            Log::error('Error adding subject: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error adding subject: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add new section
     */
    public function addSection(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'department_code' => 'required|exists:departments,code',
            'year_level' => 'required|integer|min:1|max:5',
            'schedule_type' => 'required|in:day,night',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Please fix the errors below.'
            ], 422);
        }

        try {
            $existing = Section::where('name', $request->name)
                ->where('department_code', $request->department_code)
                ->exists();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Section already exists in this department!'
                ], 409);
            }

            $section = Section::create([
                'name' => strtoupper($request->name),
                'department_code' => $request->department_code,
                'year_level' => $request->year_level,
                'schedule_type' => $request->schedule_type,
                'is_active' => true,
            ]);

            AuditLog::log(
                'SECTION_CREATE',
                'Created section: ' . $section->name . ' in ' . $section->department_code
            );

            return response()->json([
                'success' => true,
                'message' => 'Section created successfully!',
                'section' => $section
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating section: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating section: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Assign subject to faculty and section
     */
    public function assignSubject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject_id' => 'required|exists:subjects,id',
            'faculty_id' => 'required|exists:users,id',
            'section_id' => 'required|exists:sections,id',
            'academic_year' => 'required|string|regex:/^\d{4}-\d{4}$/',
            'semester' => 'required|in:1st,2nd,summer',
            'room' => 'nullable|string|max:50',
            'schedule' => 'nullable|string|max:100',
        ], [
            'academic_year.regex' => 'Academic year must be in format: YYYY-YYYY',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Please fix the errors below.'
            ], 422);
        }

        try {
            $faculty = User::where('id', $request->faculty_id)
                ->where('role', 'faculty')
                ->first();

            if (!$faculty) {
                return response()->json([
                    'success' => false,
                    'message' => 'Selected user is not a faculty member!'
                ], 400);
            }

            $existing = SubjectAssignment::where('subject_id', $request->subject_id)
                ->where('faculty_id', $request->faculty_id)
                ->where('section_id', $request->section_id)
                ->where('academic_year', $request->academic_year)
                ->where('semester', $request->semester)
                ->exists();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'This assignment already exists!'
                ], 409);
            }

            $assignment = SubjectAssignment::create([
                'subject_id' => $request->subject_id,
                'faculty_id' => $request->faculty_id,
                'section_id' => $request->section_id,
                'academic_year' => $request->academic_year,
                'semester' => $request->semester,
                'room' => $request->room,
                'schedule' => $request->schedule,
            ]);

            AuditLog::log(
                'SUBJECT_ASSIGN',
                'Assigned subject: Subject ID ' . $request->subject_id .
                ' to Faculty ID ' . $request->faculty_id .
                ' for Section ID ' . $request->section_id
            );

            return response()->json([
                'success' => true,
                'message' => 'Subject assigned successfully!',
                'assignment' => $assignment
            ]);

        } catch (\Exception $e) {
            Log::error('Error assigning subject: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error assigning subject: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle evaluation system status
     */
    public function toggleEvaluationStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:open,closed',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Please fix the errors below.'
            ], 422);
        }

        if (!Hash::check($request->password, Auth::user()->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid admin password!'
            ], 401);
        }

        try {
            $setting = SystemSetting::where('key', 'evaluation_status')->first();

            if (!$setting) {
                $setting = SystemSetting::create([
                    'key' => 'evaluation_status',
                    'value' => $request->status,
                    'type' => 'string',
                    'description' => 'Evaluation system status'
                ]);
            } else {
                $setting->update(['value' => $request->status]);
            }

            AuditLog::log(
                'SYSTEM_STATUS_CHANGE',
                'Changed evaluation status to: ' . $request->status
            );

            return response()->json([
                'success' => true,
                'message' => 'Evaluation system is now ' . $request->status . '!',
                'status' => $request->status
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating system status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating system status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update system settings
     */
    public function updateSystemSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_academic_year' => 'required|regex:/^\d{4}-\d{4}$/',
            'current_semester' => 'required|in:1st,2nd,summer',
            'evaluation_start_date' => 'required|date',
            'evaluation_end_date' => 'required|date|after:evaluation_start_date',
            'minimum_responses' => 'required|integer|min:1',
        ], [
            'current_academic_year.regex' => 'Academic year must be in format: YYYY-YYYY',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Please fix the errors below.'
            ], 422);
        }

        DB::beginTransaction();

        try {
            $settings = [
                ['key' => 'current_academic_year', 'value' => $request->current_academic_year, 'type' => 'string'],
                ['key' => 'current_semester', 'value' => $request->current_semester, 'type' => 'string'],
                ['key' => 'evaluation_start_date', 'value' => $request->evaluation_start_date, 'type' => 'date'],
                ['key' => 'evaluation_end_date', 'value' => $request->evaluation_end_date, 'type' => 'date'],
                ['key' => 'minimum_responses', 'value' => $request->minimum_responses, 'type' => 'integer'],
            ];

            foreach ($settings as $setting) {
                SystemSetting::updateOrCreate(
                    ['key' => $setting['key']],
                    [
                        'value' => $setting['value'],
                        'type' => $setting['type'],
                        'description' => $this->getSettingDescription($setting['key'])
                    ]
                );
            }

            AuditLog::log(
                'SYSTEM_SETTINGS_UPDATE',
                'Updated system settings'
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'System settings updated successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating settings: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error updating settings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate PDF report
     */
    public function generateReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'report_type' => 'required|in:faculty,department,system',
            'faculty_id' => 'required_if:report_type,faculty|exists:users,id',
            'department_code' => 'required_if:report_type,department|exists:departments,code',
            'academic_year' => 'required|regex:/^\d{4}-\d{4}$/',
            'semester' => 'required|in:1st,2nd,summer',
        ], [
            'academic_year.regex' => 'Academic year must be in format: YYYY-YYYY',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Please fix the errors below.'
            ], 422);
        }

        AuditLog::log(
            'REPORT_GENERATE',
            'Generated ' . $request->report_type . ' report for ' .
            $request->academic_year . ' ' . $request->semester . ' semester'
        );

        return response()->json([
            'success' => true,
            'message' => 'Report generated successfully!',
            'data' => [
                'report_type' => $request->report_type,
                'generated_at' => now()->toDateTimeString(),
                'download_url' => '#',
            ]
        ]);
    }

    /**
     * Get dashboard statistics (AJAX)
     */
    public function getDashboardStats()
    {
        try {
            $totalFaculty = User::where('role', 'faculty')->count();
            $totalStudents = User::where('role', 'student')->count();
            $totalEvaluations = Evaluation::whereNotNull('completed_at')->count();
            $evaluationStatus = SystemSetting::where('key', 'evaluation_status')->first()->value ?? 'closed';

            $recentEvaluations = Evaluation::whereNotNull('completed_at')
                ->where('completed_at', '>=', Carbon::now()->subDays(7))
                ->count();

            $activeEvaluations = Evaluation::whereNull('completed_at')
                ->where('created_at', '>=', Carbon::now()->subHours(24))
                ->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_faculty' => $totalFaculty,
                    'total_students' => $totalStudents,
                    'total_evaluations' => $totalEvaluations,
                    'recent_evaluations' => $recentEvaluations,
                    'active_evaluations' => $activeEvaluations,
                    'evaluation_status' => $evaluationStatus,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching dashboard stats: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching dashboard statistics'
            ], 500);
        }
    }

    /**
     * Get faculty list for dropdown (AJAX)
     */
    public function getFacultyList(Request $request, $departmentCode = null)
    {
        try {
            $query = User::where('role', 'faculty')
                ->where('is_active', true)
                ->with('department')
                ->orderBy('name');

            $departmentCode = $departmentCode ?? $request->get('department_code');
            if ($departmentCode && $departmentCode !== 'all') {
                $query->where('department_code', $departmentCode);
            }

            $faculty = $query->get(['id', 'user_id', 'name', 'department_code']);

            return response()->json([
                'success' => true,
                'faculty' => $faculty
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching faculty list: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching faculty list'
            ], 500);
        }
    }

    /**
     * Get subject list for dropdown (AJAX)
     */
    public function getSubjectList(Request $request, $departmentCode = null)
    {
        try {
            $query = Subject::where('is_active', true)
                ->orderBy('code');

            $departmentCode = $departmentCode ?? $request->get('department_code');
            if ($departmentCode && $departmentCode !== 'all') {
                $query->where('department_code', $departmentCode);
            }

            $subjects = $query->get(['id', 'code', 'name', 'department_code']);

            return response()->json([
                'success' => true,
                'subjects' => $subjects
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching subject list: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching subject list'
            ], 500);
        }
    }

    /**
     * Get section list for dropdown (AJAX)
     */
    public function getSectionList(Request $request, $departmentCode = null)
    {
        try {
            $query = Section::where('is_active', true)
                ->orderBy('year_level')
                ->orderBy('name');

            $departmentCode = $departmentCode ?? $request->get('department_code');
            if ($departmentCode && $departmentCode !== 'all') {
                $query->where('department_code', $departmentCode);
            }

            $sections = $query->get(['id', 'name', 'department_code', 'year_level', 'schedule_type']);

            return response()->json([
                'success' => true,
                'sections' => $sections
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching section list: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching section list'
            ], 500);
        }
    }

    /**
     * Helper: Parse semester string
     */
    private function parseSemester(string $semesterString): array
    {
        preg_match('/(1st|2nd|summer)\s+Semester\s+(\d{4}-\d{4})/i', $semesterString, $matches);

        return [
            'semester' => $matches[1] ?? '1st',
            'year' => $matches[2] ?? date('Y') . '-' . (date('Y') + 1)
        ];
    }

    /**
     * Helper: Get rating status
     */
    private function getRatingStatus(float $rating): string
    {
        if ($rating >= 4.50) return 'EXCELLENT';
        if ($rating >= 3.50) return 'VERY GOOD';
        if ($rating >= 2.50) return 'GOOD';
        if ($rating >= 1.50) return 'NEEDS IMPROVEMENT';
        return 'UNSATISFACTORY';
    }

    /**
     * Helper: Get expected responses for faculty
     */
    private function getExpectedResponses(int $facultyId, string $academicYear, string $semester): int
    {
        $assignments = SubjectAssignment::where('faculty_id', $facultyId)
            ->where('academic_year', $academicYear)
            ->where('semester', $semester)
            ->with('section')
            ->get();

        $totalStudents = 0;
        foreach ($assignments as $assignment) {
            $studentCount = User::where('role', 'student')
                ->where('department_code', $assignment->section->department_code ?? null)
                ->where('section', $assignment->section->name ?? null)
                ->count();

            $totalStudents += $studentCount;
        }

        return $totalStudents;
    }

    /**
     * Helper: Calculate faculty rank
     */
    private function calculateFacultyRank(int $facultyId, string $academicYear, string $semester): int
    {
        try {
            $rank = DB::select("
                SELECT rank
                FROM (
                    SELECT faculty_id,
                           AVG(average_rating) as avg_rating,
                           RANK() OVER (ORDER BY AVG(average_rating) DESC) as rank
                    FROM evaluations
                    WHERE academic_year = ?
                    AND semester = ?
                    AND completed_at IS NOT NULL
                    AND faculty_id IS NOT NULL
                    GROUP BY faculty_id
                ) as ranks
                WHERE faculty_id = ?
            ", [$academicYear, $semester, $facultyId]);

            return $rank[0]->rank ?? 0;
        } catch (\Exception $e) {
            Log::error('Error calculating faculty rank: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Helper: Get setting description
     */
    private function getSettingDescription(string $key): string
    {
        $descriptions = [
            'current_academic_year' => 'Current Academic Year',
            'current_semester' => 'Current Semester',
            'evaluation_start_date' => 'Evaluation Start Date',
            'evaluation_end_date' => 'Evaluation End Date',
            'minimum_responses' => 'Minimum Responses Required',
            'evaluation_status' => 'Evaluation System Status',
        ];

        return $descriptions[$key] ?? ucfirst(str_replace('_', ' ', $key));
    }
}