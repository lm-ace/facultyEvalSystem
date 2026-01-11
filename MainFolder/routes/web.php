<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/login', function () {
    return view('auth.role-selection');
})->name('login');

Route::get('/login/{role}', function ($role) {
    return view('auth.login-form', ['role' => $role]);
})->name('login.role');

Route::get('/faculty/dashboard', function () {
    return view('faculty.dashboard');
})->name('faculty.dashboard');


Route::post('/login/process/{role}', function (Request $request, $role) {
  

    if ($role === 'faculty') {
        return redirect()->route('faculty.dashboard');
    } elseif ($role === 'admin') {
        return redirect('/admin/dashboard'); 
    } else {
        return redirect('/'); 
    }
})->name('login.process');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () { return view('admin.dashboard'); })->name('dashboard');
    Route::get('/departments', function () { return view('admin.departments.index'); })->name('departments');
    Route::get('/criteria', function () { return view('admin.criteria'); })->name('criteria');
    Route::get('/reports', function () { return view('admin.reports'); })->name('reports');
});

Route::get('/admin/sections/{section_code}', function ($section_code) {
    return view('admin.section-detail', ['section' => $section_code]);
})->name('admin.section.detail');


//temporary route for student module, kindly make a logical route to connect with the 
Route::get('/student', function() {
    return view('student.index');
})->name('student'); 


Route::get('/faculty/dashboard', function () {
    return view('faculty.dashboard');
})->name('faculty.dashboard');