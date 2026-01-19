<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Controllers\Student\DashboardController;

//PUBLIC PAGES
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

//Login & Logout 
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::get('/login/{role}', [LoginController::class, 'showLoginForm'])->name('login.role');
Route::post('/login', [LoginController::class, 'login'])->name('login.process'); 
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

//Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function(){
    Route::get('/dashboard', function () {
        return view('admin.dashboard'); 
    })->name('dashboard'); 

Route::get('/', function () { 
    return redirect()->route('admin.dashboard'); 
});

Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->name('dashboard');

Route::get('/dashboard-stats', function () {
})->name('dashboard.stats');

Route::get('/departments', function () {
    return view('admin.departments');
})->name('departments');

Route::get('/department/{code}', function ($code) {
    return view('admin.department-detail', compact('code'));
})->name('department.detail');

Route::get('/sections/{section_code}', function ($section_code) {
    return view('admin.section-detail', ['section' => $section_code]);
})->name('section.detail');

Route::get('/criteria', [AdminController::class, 'criteria'])->name('criteria');

// For views lang wala since wala pa controller 
Route::post('/criteria', function () {})->name('criteria.store');
Route::put('/criteria/{id}', function () {})->name('criteria.update');
Route::delete('/criteria/{id}', function ($id) {})->name('criteria.destroy');
Route::post('/faculty', function () {})->name('faculty.add');
Route::post('/students', function () {})->name('students.add');
Route::post('/subjects', function () {})->name('subjects.add');
Route::post('/sections', function () {})->name('sections.add');
Route::post('/assign-subject', function () {})->name('assign.subject');
Route::get('/reports', function () {return view('admin.reports');})->name('reports');
Route::get('/reports/faculty/{id}', function () {})->name('reports.faculty');
Route::post('/generate-report', function () {})->name('generate.report');
Route::post('/evaluation-status', function () {})->name('evaluation.status');
Route::post('/system-settings', function () {})->name('system.settings');

//Faculty Routes 
Route::middleware(['auth'])->prefix('faculty')->name('faculty.')->group(function () {
    Route::get('/dashboard', [FacultyController::class, 'show'])->name('dashboard'); 
});

//Student Routes
Route::middleware(['auth'])->prefix('student')->name('student.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index'); 
    Route::post('/evaluate', [DashboardController::class, 'store'])->name('evaluate.store');
    Route::post('/changePassword', [DashboardController::class, 'changePassword'])->name('changePassword');
});

}

);