<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\StudentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- PUBLIC ROUTES ---
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/how-it-works', function () {
    return view('how-it-works');
})->name('how-it-works');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

/** * LOGIN & ROLE SELECTION (PUBLICLY ACCESSIBLE)
 * Inilabas natin ito sa 'guest' middleware para hindi mag-redirect pabalik sa home 
 * kapag gusto mong lumipat ng account o mag-login ulit.
 */
Route::get('/login', function () {
    return view('auth.role-selection'); 
})->name('login');

Route::get('/login/{role}', function ($role) {
    if (!in_array($role, ['admin', 'faculty', 'student'])) {
        abort(404);
    }
    return view('auth.login-form', ['role' => $role]);
})->name('login.role');

// In routes/web.php
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Login page route
Route::get('/login-page/{role?}', function ($role = null) {
    return view('auth.login', ['role' => $role]);
})->name('login.page');

    
    // Login process (Ang POST request lang ang block sa naka-login na)
    Route::post('/login/process/{role}', [AuthController::class, 'login'])
        ->name('login.process');

    // Registration routes
    Route::get('/register', function () {
        return view('auth.register-selection');
    })->name('register');

    // In routes/web.php
    Route::get('/register/{role}', function ($role) {
        if (!in_array($role, ['admin', 'faculty', 'student'])) {
            abort(404);
        }
        return view('auth.register-form', ['role' => $role]);
    })->name('register.role');

    Route::post('/register/process/{role}', [AuthController::class, 'register'])
        ->name('register.process');

    // Password Reset Routes
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');

    Route::post('/forgot-password/send', [ResetPasswordController::class, 'sendCode'])
        ->name('password.send-code');

    Route::post('/verify-code', [ResetPasswordController::class, 'verifyCode'])
        ->name('password.verify-code');

    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
        ->name('password.reset');

    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
        ->name('password.update');


// --- PROTECTED ROUTES (AUTH REQUIRED) ---
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // GET version of logout (Optional: para sa mabilis na testing sa browser)
    Route::get('/logout-manual', [AuthController::class, 'logout']);

    // Redirection after login based on role
    Route::get('/redirect-to-dashboard', [AuthController::class, 'redirectToDashboard'])
        ->name('redirect.dashboard');

  // --- ADMIN ROUTES ---
Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
    
    // Dashboard
    Route::get('/', function () { return redirect()->route('admin.dashboard'); });
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard-stats', [AdminController::class, 'getDashboardStats'])->name('dashboard.stats');
    
    // Departments & Management
    Route::get('/departments', [AdminController::class, 'departments'])->name('departments');
    Route::get('/department/{code}', [AdminController::class, 'departmentDetail'])->name('department.detail');
    Route::get('/sections/{section_code}', function ($section_code) {
        return view('admin.section-detail', ['section' => $section_code]);
    })->name('section.detail');
    
    // Evaluation Criteria
    Route::get('/criteria', [AdminController::class, 'criteria'])->name('criteria');
    Route::post('/criteria', [AdminController::class, 'storeCriterion'])->name('criteria.store');
    Route::put('/criteria/{id}', [AdminController::class, 'updateCriterion'])->name('criteria.update');
    Route::delete('/criteria/{id}', [AdminController::class, 'destroyCriterion'])->name('criteria.destroy');
    
    // Actions (Forms/POST)
    Route::post('/faculty', [AdminController::class, 'addFaculty'])->name('faculty.add');
    Route::post('/students', [AdminController::class, 'addStudent'])->name('students.add');
    Route::post('/subjects', [AdminController::class, 'addSubject'])->name('subjects.add');
    Route::post('/sections', [AdminController::class, 'addSection'])->name('sections.add');
    Route::post('/assign-subject', [AdminController::class, 'assignSubject'])->name('assign.subject');
    
    // Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::get('/reports/faculty/{id}', [AdminController::class, 'facultyReport'])->name('reports.faculty');
    Route::post('/generate-report', [AdminController::class, 'generateReport'])->name('generate.report');
    
    // Settings
    Route::post('/evaluation-status', [AdminController::class, 'toggleEvaluationStatus'])->name('evaluation.status');
    Route::post('/system-settings', [AdminController::class, 'updateSystemSettings'])->name('system.settings');
});

    // --- FACULTY ROUTES ---
    Route::prefix('faculty')->name('faculty.')->middleware('role:faculty')->group(function () {
        Route::get('/', function () { return redirect()->route('faculty.dashboard'); });
        Route::get('/dashboard', [FacultyController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [FacultyController::class, 'profile'])->name('profile');
        Route::get('/evaluations', [FacultyController::class, 'evaluations'])->name('evaluations');
        Route::get('/subjects', [FacultyController::class, 'subjects'])->name('subjects');
        Route::get('/reports', [FacultyController::class, 'reports'])->name('reports');
    });

    // --- STUDENT ROUTES ---
    Route::prefix('student')->name('student.')->middleware('role:student')->group(function () {
        Route::get('/', function () { return redirect()->route('student.dashboard'); });
        Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
        Route::get('/evaluate', [StudentController::class, 'evaluate'])->name('evaluate');
        Route::get('/evaluation/history', [StudentController::class, 'history'])->name('evaluation.history');
        Route::post('/evaluation/submit', [StudentController::class, 'submitEvaluation'])->name('evaluation.submit');
        Route::get('/profile', [StudentController::class, 'profile'])->name('profile');
    });
});

use App\Models\User;
use App\Mail\CredentialsMail;
use Illuminate\Support\Facades\Mail;

Route::get('/test-email-send', function () {
    try {
        // Create a dummy user for testing
        $testUser = new User();
        $testUser->name = 'Juan Dela Cruz';
        $testUser->email = 'juan@example.com'; // Palitan ng iyong personal email para ma-test
        $testUser->user_id = '2026-00123-MN-0';
        
        $password = 'TestPassword123';
        
        // Send email
        Mail::send(new CredentialsMail($testUser, $password, 'student'));
        
        return response()->json([
            'success' => true,
            'message' => 'Test email sent successfully!',
            'to' => $testUser->email
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});