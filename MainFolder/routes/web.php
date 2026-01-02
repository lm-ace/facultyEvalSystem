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