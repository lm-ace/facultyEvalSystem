<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// --- Controllers ---
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
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
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function(){

    // --- Dashboard ---
    Route::get('/', function () { 
        return redirect()->route('admin.dashboard'); 
    });

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::post('/toggle-status', [AdminController::class, 'toggleStatus'])->name('toggle.status');

    // --- Departments Management ---
    Route::get('/departments', [AdminController::class, 'departments'])->name('departments');

    // --- Subjects CRUD ---
    Route::post('/subjects', [AdminController::class, 'storeSubject'])->name('subjects.store');
    Route::delete('/subjects/{id}', [AdminController::class, 'destroySubject'])->name('subjects.destroy');

    // --- Sections CRUD ---
    Route::post('/sections', [AdminController::class, 'storeSection'])->name('sections.store');
    Route::put('/sections/{id}/subjects', [AdminController::class, 'updateSectionSubjects'])->name('sections.update.subjects');
    Route::delete('/sections/{id}', [AdminController::class, 'destroySection'])->name('sections.destroy');
    
    // --- Faculty CRUD ---
    Route::post('/faculty', [AdminController::class, 'storeFaculty'])->name('faculty.store');
    Route::put('/faculty/{id}/sections', [AdminController::class, 'updateFacultySections'])->name('faculty.update.sections');
    Route::delete('/faculty/{id}', [AdminController::class, 'destroyFaculty'])->name('faculty.destroy');

    // --- Students CRUD ---
    Route::post('/students', [AdminController::class, 'storeStudent'])->name('students.store');
    Route::delete('/students/{id}', [AdminController::class, 'destroyStudent'])->name('students.destroy');

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
});