<?php

use App\Http\Controllers\StudentsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

// PUBLIC PAGES
Route::get('/', function () { return view('welcome'); })->name('home');
Route::get('/about', function () { return view('about'); })->name('about');
Route::get('/how-it-works', function () { return view('how-it-works'); })->name('how-it-works');
Route::get('/contact', function () { return view('contact'); })->name('contact');

// AUTHENTICATION ROUTES
Route::get('/login', function () {
    return view('auth.role-selection');
})->name('login');

// SPECIFIC ROLE LOGIN ROUTES
Route::get('/login/{role}', function ($role) {
    return view('auth.login-form', ['role' => $role]);
})->name('login.role');

// LOGIN PROCESSING ROUTE
Route::post('/login/process/{role}', function (Request $request, $role) {

    $request->validate([
        'login_id' => 'required',
        'password' => 'required',
    ]);

    $credentials = [
        'username' => $request->input('login_id'),
        'password' => $request->input('password')
    ];

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        if ($role === 'student') {
            return redirect()->route('student');
        } elseif ($role === 'faculty') {
            return redirect()->route('faculty.dashboard');
        } elseif ($role === 'admin') {
            return redirect('/admin/dashboard');
        }

        return redirect('/');
    }

    return back()->withErrors([
        'login_id' => 'The provided credentials do not match our records.',
    ])->onlyInput('login_id');

})->name('login.process');

// ADMIN ROUTES
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () { return view('admin.dashboard'); })->name('dashboard');
    Route::get('/departments', function () { return view('admin.departments.index'); })->name('departments');
    Route::get('/criteria', function () { return view('admin.criteria'); })->name('criteria');
    Route::get('/reports', function () { return view('admin.reports'); })->name('reports');
});

Route::get('/admin/sections/{section_code}', function ($section_code) {
    return view('admin.section-detail', ['section' => $section_code]);
})->name('admin.section.detail');

// STUDENT ROUTES
Route::middleware(['auth'])->group(function () {
    Route::get('/student', function () {
        return view('student.index');
    })->name('student');

    Route::post('/student/changePassword', [StudentsController::class, 'changePassword'])
        ->name('student.changePassword');

    Route::get('/logout', [StudentsController::class, 'logout'])
        ->name('student.logout');
});

// FACULTY ROUTES
Route::get('/faculty/dashboard', function () {
    return view('faculty.dashboard');
})->name('faculty.dashboard');

