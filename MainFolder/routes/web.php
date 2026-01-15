<?php

use App\Http\Controllers\FacultyController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Public routes
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

// Authentication routes
Route::get('/login', function () {
    return view('auth.role-selection');
})->name('login');

Route::get('/login/{role}', function ($role) {
    return view('auth.login-form', ['role' => $role]);
})->name('login.role');

Route::post('/login/process/{role}', function (Request $request, $role) {
    // TODO: Add actual authentication logic here
    $credentials = $request->only('username', 'password');

    if ($role === 'faculty') {
        // For testing, find any faculty user
        $user = \App\Models\User::where('role', 'faculty')
            ->where('username', $credentials['username'] ?? 'testfaculty')
            ->first();

        if ($user) {
            Auth::login($user);
            return redirect()->route('faculty.dashboard');
        }
    }

    return back()->withErrors(['login' => 'Invalid credentials']);
})->name('login.process');

// Temporary test login route (remove in production)
Route::get('/login-test/{id}', function ($id) {
    Auth::loginUsingId($id);
    return redirect()->route('faculty.dashboard');
})->name('login.test');

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/departments', function () {
        return view('admin.departments.index');
    })->name('departments');

    Route::get('/criteria', function () {
        return view('admin.criteria');
    })->name('criteria');

    Route::get('/reports', function () {
        return view('admin.reports');
    })->name('reports');

    Route::get('/sections/{section_code}', function ($section_code) {
        return view('admin.section-detail', ['section' => $section_code]);
    })->name('section.detail');
});

// Student routes
Route::get('/student', function () {
    return view('student.index');
})->name('student');

//FACULTY ROUTES
Route::get('/faculty/{id}/dashboard', [FacultyController::class, 'show'])
->name('faculty.dashboard'); 


    // Logout route (must be POST for security)
    Route::post('/logout', function () {
        Auth::logout();
        return redirect()->route('home');
    })->name('logout');

    // Also allow GET for testing (remove in production)
    Route::get('/logout', function () {
        Auth::logout();
        return redirect()->route('home');
    });
