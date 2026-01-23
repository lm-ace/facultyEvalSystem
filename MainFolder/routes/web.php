<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// --- Controllers ---
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminDepartmentController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\Student\DashboardController;

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
    Route::post('/courses', [AdminDepartmentController::class, 'storeCourse'])->name('courses.store');
    Route::put('/courses/{id}', [AdminDepartmentController::class, 'updateCourse'])->name('courses.update');
    Route::delete('/courses/{id}', [AdminDepartmentController::class, 'deleteCourse'])->name('courses.destroy');

    // --- Subjects ---
    Route::post('/subjects', [AdminDepartmentController::class, 'storeSubject'])->name('subjects.store');
    Route::put('/subjects/{id}', [AdminDepartmentController::class, 'updateSubject'])->name('subjects.update');
    Route::delete('/subjects/{id}', [AdminDepartmentController::class, 'deleteSubject'])->name('subjects.destroy');

    // --- Sections CRUD ---
    Route::post('/sections', [AdminDepartmentController::class, 'addSection'])->name('sections.store');
    Route::put('/sections/{id}', [AdminDepartmentController::class, 'updateSection'])->name('sections.update');
    Route::delete('/sections/{id}', [AdminDepartmentController::class, 'deleteSection'])->name('sections.destroy');

    // --- Faculty CRUD ---
    Route::post('/faculty', [AdminDepartmentController::class, 'storeFaculty'])->name('faculty.store');
    Route::put('/faculty/{id}', [AdminDepartmentController::class, 'updateFaculty'])->name('faculty.update');
    Route::delete('/faculty/{id}', [AdminDepartmentController::class, 'deleteFaculty'])->name('faculty.destroy');

    // --- Students CRUD ---
    Route::post('/students', [AdminDepartmentController::class, 'storeStudent'])->name('students.store');
    Route::put('/students/{id}', [AdminDepartmentController::class, 'updateStudent'])->name('students.update');
    Route::delete('/students/{id}', [AdminDepartmentController::class, 'destroyStudent'])->name('students.destroy');

    // --- Criteria Management ---
    Route::get('/criteria', [AdminController::class, 'criteria'])->name('criteria');
    Route::post('/criteria', [AdminController::class, 'storeCriteria'])->name('criteria.store');
    Route::put('/criteria/{id}', [AdminController::class, 'updateCriteria'])->name('criteria.update');
    Route::delete('/criteria/{id}', [AdminController::class, 'destroyCriteria'])->name('criteria.destroy');
});

// ====================================================
// FACULTY ROUTES
// ====================================================

Route::middleware(['auth'])->prefix('faculty')->name('faculty.')->group(function () {
    Route::get('/dashboard', [FacultyController::class, 'show'])->name('dashboard');
});

// ====================================================
// STUDENT ROUTES
// ====================================================

Route::middleware(['auth'])->prefix('student')->name('student.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::post('/evaluate', [DashboardController::class, 'store'])->name('evaluate.store');
    Route::post('/changePassword', [DashboardController::class, 'changePassword'])->name('changePassword');
    Route::post('/logout', [DashboardController::class, 'logout'])->name('logout');
});
