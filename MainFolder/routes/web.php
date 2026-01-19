<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
// --- Controllers ---
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\StudentController;

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
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function(){

    // --- Dashboard & General ---
    Route::get('/', function () { 
        return redirect()->route('admin.dashboard'); 
    });

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/reports', function () {
        return view('admin.reports'); 
    })->name('reports');

    Route::post('/toggle-status', [AdminController::class, 'toggleStatus'])->name('toggle.status');

    // --- Departments Management ---
    Route::get('/departments', [AdminDepartmentController::class, 'index'])->name('departments');
    

    // Data route for AJAX/Fetch
    Route::get('/departments/data/{code}', [AdminDepartmentController::class, 'getDepartmentData'])->name('departments.data');

    // --- Subjects CRUD ---
    Route::post('/subjects', [AdminDepartmentController::class, 'addSubject'])->name('subjects.store');
    Route::delete('/subjects/{id}', [AdminDepartmentController::class, 'deleteSubject'])->name('subjects.destroy');

    // --- Sections CRUD ---
    Route::post('/sections', [AdminDepartmentController::class, 'addSection'])->name('sections.store');
    Route::delete('/sections/{id}', [AdminDepartmentController::class, 'deleteSection'])->name('sections.destroy');
    
    // --- Faculty CRUD ---
    Route::post('/faculty', [AdminDepartmentController::class, 'addFaculty'])->name('faculty.store');
    Route::delete('/faculty/{id}', [AdminDepartmentController::class, 'deleteFaculty'])->name('faculty.destroy');

    // --- Students CRUD ---
    Route::post('/students', [AdminDepartmentController::class, 'addStudent'])->name('students.store');
    Route::delete('/students/{id}', [AdminDepartmentController::class, 'deleteStudent'])->name('students.destroy');

    // --- Assignments ---
    Route::post('/assign-professor', [AdminDepartmentController::class, 'assignProfessor'])->name('assign.professor');
    Route::post('/section-assign-subjects', [AdminDepartmentController::class, 'assignSubjectsToSection'])->name('section.assign.subjects');

    // --- Section Detail View ---
    Route::get('/sections/{section_code}', function ($section_code) {
        return view('admin.section-detail', ['section' => $section_code]);
    })->name('section.detail');

    // --- Criteria Management ---
    Route::get('/criteria', function () {
        return view('admin.criteria');
    })->name('criteria');

    // (Placeholders)
    Route::post('/criteria', function () {})->name('criteria.store');
    Route::put('/criteria/{id}', function () {})->name('criteria.update');
    Route::delete('/criteria/{id}', function ($id) {})->name('criteria.destroy');

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
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});