<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash; 
use App\Models\User;                 
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Admin\CriteriaController;

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

//AUTH ROUTES
Route::get('/login', function () {
    return view('auth.role-selection');
})->name('login');

Route::get('/login/{role}', function ($role) {
    return view('auth.login-form', ['role' => $role]);
})->name('login.role');

//LOGGING IN 
Route::post('/login/process/{role}', function (Request $request, $role) {
    
    // 1. Validate form data
    $request->validate([
        'username' => 'required',
        'password' => 'required'
    ]);

    // 2. Find user in the database
    $user = User::where('username', $request->username)
                ->where('role', $role) 
                ->first();

    // 3. Check if user exists AND password is correct
    if ($user && Hash::check($request->password, $user->password_hash)) {
        
        // SUCCESS
        Auth::login($user);
        $request->session()->regenerate();

        // Redirect to correct dashboard based on role
        if ($role === 'student') {
            return redirect()->route('student.index');
        } elseif ($role === 'faculty') {
            return redirect()->route('faculty.dashboard');
        } elseif ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
    }

    // 4. If login failed, go back with error
    return back()->withErrors([
        'username' => 'Invalid username or password.',
    ]);

})->name('login.process');


//FACULTY ROUTES
Route::get('/faculty/dashboard', function () {
    return view('faculty.dashboard');
})->name('faculty.dashboard');


//ADMIN ROUTES
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () { return view('admin.dashboard'); })->name('dashboard');
    Route::get('/departments', function () { return view('admin.departments.index'); })->name('departments');
    Route::get('/criteria', function () { return view('admin.criteria'); })->name('criteria');
    Route::get('/reports', function () { return view('admin.reports'); })->name('reports');
    Route::get('/criteria', [CriteriaController::class, 'index'])->name('criteria');
    Route::post('/criteria/category', [CriteriaController::class, 'storeSection'])->name('criteria.storeSection');
    Route::post('/criteria/question', [CriteriaController::class, 'storeItem'])->name('criteria.storeItem');
    Route::delete('/criteria/question/{id}', [CriteriaController::class, 'destroyItem'])->name('criteria.destroyItem');
    Route::delete('/criteria/category/{id}', [CriteriaController::class, 'destroySection'])->name('criteria.destroySection');
});

Route::get('/admin/sections/{section_code}', function ($section_code) {
    return view('admin.section-detail', ['section' => $section_code]);
})->name('admin.section.detail');


//STUDENT ROUTES
Route::middleware(['auth'])->prefix('student')->name('student.')->group(function () {
    
    // Dashboard / Index
    Route::get('/', [DashboardController::class, 'index'])->name('index'); 
    
    // Submission Logic
    Route::post('/evaluate', [DashboardController::class, 'store'])->name('evaluate.store');

});