@extends('layouts.app')

@section('content')
<nav class="fixed top-0 left-0 right-0 z-50 flex items-center justify-between px-10 py-2 text-white bg-[#660000]/85 backdrop-blur-md shadow-lg transition-all duration-300">
    <div class="flex items-center space-x-3">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8"> 
        <div>
            <h1 class="font-bold leading-none text-base">EduRate</h1>
            <p class="text-[9px] tracking-tighter uppercase opacity-80">Faculty Evaluation System</p>
        </div>
    </div>
    
    <div class="hidden space-x-1 md:flex items-center">
        <a href="{{ route('home') }}" class="px-4 py-1.5 rounded-lg text-sm font-medium transition hover:bg-white/10">Home</a>
        <a href="{{ route('about') }}" class="px-4 py-1.5 rounded-lg text-sm font-medium transition hover:bg-white/10">About</a>
        <a href="{{ route('how-it-works') }}" class="px-4 py-1.5 rounded-lg text-sm font-medium transition hover:bg-white/10">How It Works</a>
        <a href="{{ route('contact') }}" class="px-4 py-1.5 rounded-lg text-sm font-medium transition hover:bg-white/10">Contact</a>
    </div>
</nav>

<div class="relative min-h-screen flex items-center justify-center bg-cover bg-center" style="background-image: url('{{ asset('images/lagoon.jpg') }}');">
    <div class="absolute inset-0 bg-[#4D0000]/70"></div>

    <div class="relative z-10 w-full max-w-md bg-white rounded-xl shadow-2xl p-8 mx-4">
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-16 mx-auto mb-4">
            <h2 class="text-2xl font-bold text-gray-800">Hi, PUPian!</h2>
            <p class="text-gray-500 text-sm">Select your role to continue</p>
        </div>

        <div class="space-y-4">
            <a href="{{ route('login.role', 'student') }}" class="flex items-center p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition border border-transparent hover:border-[#800000]/20 group">
                <div class="bg-[#800000] w-12 h-12 rounded-full flex items-center justify-center text-white mr-4 shadow-md group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-graduation-cap text-xl"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800">Student</h4>
                    <p class="text-xs text-gray-500">Evaluate your faculty members</p>
                </div>
            </a>

            <a href="{{ route('login.role', 'faculty') }}" class="flex items-center p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition border border-transparent hover:border-[#800000]/20 group">
                <div class="bg-[#800000] w-12 h-12 rounded-full flex items-center justify-center text-white mr-4 shadow-md group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-user-tie text-xl"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800">Faculty</h4>
                    <p class="text-xs text-gray-500">View your evaluation results</p>
                </div>
            </a>

            <a href="{{ route('login.role', 'admin') }}" class="flex items-center p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition border border-transparent hover:border-[#800000]/20 group">
                <div class="bg-[#800000] w-12 h-12 rounded-full flex items-center justify-center text-white mr-4 shadow-md group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-shield-halved text-xl"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800">Administrator</h4>
                    <p class="text-xs text-gray-500">Manage the evaluation system</p>
                </div>
            </a>
        </div>

        <div class="mt-8 text-center">
            <a href="{{ route('home') }}" class="text-gray-400 hover:text-[#800000] text-sm font-medium transition flex items-center justify-center group">
                <i class="fa-solid fa-arrow-left mr-2 transition-transform group-hover:-translate-x-1"></i> Back to Homepage
            </a>
        </div>
    </div>

    <div class="absolute bottom-6 left-0 right-0 text-center text-white/50 text-xs z-10 px-4">
        Copyright © {{ date('Y') }} | EduRate, Polytechnic University of the Philippines - Main Campus
    </div>
</div>
@endsection