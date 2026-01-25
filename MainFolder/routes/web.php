<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// --- Controllers ---
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminDepartmentController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CriteriaController; // <--- Import is here
use App\Http\Controllers\Department\FacultyController as DepartmentFacultyController;
use App\Http\Controllers\Department\ProgramController as DepartmentProgramController;
use App\Http\Controllers\Department\SectionController;
use App\Http\Controllers\Department\StudentController;
use App\Http\Controllers\Department\SubjectController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\ForgotPasswordController;


// ====================================================
// PUBLIC PAGES
// ====================================================

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

// ====================================================
// AUTHENTICATION (Login & Logout)
// ====================================================

Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::get('/login/{role}', [LoginController::class, 'showLoginForm'])->name('login.role');
Route::post('/login', [LoginController::class, 'login'])->name('login.process');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Forgot Password 
Route::post('/forgot/send-otp', [ForgotPasswordController::class, 'sendOtp'])->name('forgot.sendOtp');
Route::post('/forgot/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('forgot.verifyOtp');
Route::post('/forgot/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('forgot.resetPassword');

// ====================================================
// ADMIN ROUTES
// Prefix: /admin | Name: admin. | Middleware: auth
// ====================================================

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // --- Dashboard ---
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::post('/toggle-status', [AdminController::class, 'toggleStatus'])->name('toggle.status');

    // --- Departments Management ---
    Route::get('/departments', [AdminDepartmentController::class, 'index'])->name('departments');
    Route::post('/departments', [AdminDepartmentController::class, 'storeDepartment'])->name('departments.store');
    Route::delete('/departments/{department}', [AdminDepartmentController::class, 'departmentDestroy'])->name('departments.destroy');
    Route::put('/departments/{id}', [AdminDepartmentController::class, 'updateDepartment'])->name('departments.update');

    // --- Course (Program) Management ---
    Route::post('/programs', [DepartmentProgramController::class, 'store'])->name('programs.store');
    Route::put('/programs/{id}', [DepartmentProgramController::class, 'update'])->name('programs.update');
    Route::delete('/courses/{id}', [DepartmentProgramController::class, 'destroy'])->name('courses.destroy');

    // --- Subjects ---
    Route::post('/subjects', [SubjectController::class, 'store'])->name('subjects.store');
    Route::put('/subjects/{id}', [SubjectController::class, 'update'])->name('subjects.update');
    Route::delete('/subjects/{id}', [SubjectController::class, 'destroy'])->name('subjects.destroy');

    // --- Sections CRUD ---
    Route::post('/sections', [SectionController::class, 'store'])->name('sections.store');
    Route::put('/sections/{id}', [SectionController::class, 'update'])->name('sections.update');
    Route::delete('/sections/{id}', [SectionController::class, 'destroy'])->name('sections.destroy');

    // --- Faculty CRUD ---
    Route::post('/faculty', [DepartmentFacultyController::class, 'store'])->name('faculty.store');
    Route::put('/faculty/{id}', [DepartmentFacultyController::class, 'update'])->name('faculty.update');
    Route::delete('/faculty/{id}', [DepartmentFacultyController::class, 'destroy'])->name('faculty.destroy');

    // --- Students CRUD ---
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::put('/students/{id}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('students.destroy');

    // --- Criteria Management ---
    // 1. Main View
    Route::get('/criteria', [CriteriaController::class, 'index'])->name('criteria');

    // 2. Categories (Sections)
    Route::post('/criteria/section', [CriteriaController::class, 'storeSection'])->name('criteria.section.store');
    Route::put('/criteria/section/{id}', [CriteriaController::class, 'updateSection'])->name('criteria.section.update');
    Route::delete('/criteria/section/{id}', [CriteriaController::class, 'destroySection'])->name('criteria.section.destroy');

    // 3. Questions (Items)
    Route::post('/criteria/item', [CriteriaController::class, 'storeItem'])->name('criteria.item.store');
    Route::put('/criteria/item/{id}', [CriteriaController::class, 'updateItem'])->name('criteria.item.update');
    Route::delete('/criteria/item/{id}', [CriteriaController::class, 'destroyItem'])->name('criteria.item.destroy');

}); // <--- THIS WAS LIKELY MISSING OR DELETED

// ====================================================
// FACULTY ROUTES
// ====================================================

Route::middleware(['auth'])->prefix('faculty')->name('faculty.')->group(function () {
    Route::get('/dashboard', [FacultyController::class, 'show'])->name('dashboard');
    Route::post('/changePassword', [FacultyController::class, 'changePassword'])->name('changePassword');
});

// ====================================================
// STUDENT ROUTES
// ====================================================

Route::middleware(['auth'])->prefix('student')->name('student.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::post('/evaluate', [DashboardController::class, 'store'])->name('evaluate.store');
    Route::post('/changePassword', [DashboardController::class, 'changePassword'])->name('changePassword');
    Route::post('/logout',[DashboardController::class, 'logout'])->name('logout');
});