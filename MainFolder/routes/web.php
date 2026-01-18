<?php

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

    Route::get('/departments', function () {
        return view('admin.departments.index'); 
    })->name('departments'); 

    Route::get('/criteria', function() {
        return view('admin.criteria');
    })->name('criteria'); 

    Route::get('/reports', function() {
        return view('admin.reports'); 
    })->name('reports');

    Route::get('/sections/{section_code}', function($section_code){
        return view('admin.section-detail', ['section' => $section_code]);  
    })->name('section.detail'); 
});

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


