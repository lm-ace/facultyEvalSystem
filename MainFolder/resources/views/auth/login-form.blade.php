@extends('layouts.app')

@section('content')
@php
    // Define dynamic labels based on the role
    $identifierLabel = 'Username';
    $placeholder = 'Enter your username';

    if($role == 'student') {
        $identifierLabel = 'Student Number';
        $placeholder = 'e.g. 2021-00001-MN-0';
    } elseif($role == 'faculty') {
        $identifierLabel = 'Faculty ID';
        $placeholder = 'Enter Faculty ID';
    }
@endphp

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
        <div class="text-center mb-6">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-16 mx-auto mb-4">
            <h2 class="text-2xl font-bold text-gray-800">Hi, PUPian!</h2>
            <p class="text-gray-500 text-sm">Enter your credentials to continue</p>
        </div>

        <div class="flex items-center justify-center space-x-2 mb-8 text-[#800000]">
            <div class="bg-[#800000] p-2 rounded-full text-white">
                @if($role == 'student') <i class="fa-solid fa-graduation-cap"></i>
                @elseif($role == 'faculty') <i class="fa-solid fa-user-tie"></i>
                @else <i class="fa-solid fa-shield-halved"></i> @endif
            </div>
            <span class="font-bold uppercase tracking-wider">{{ ucfirst($role) }} Login</span>
        </div>

        <form action="{{ route('login.process', $role) }}" method="POST" class="space-y-5">
            @csrf 
            
            <div>
                <label class="text-xs font-bold text-gray-400 uppercase">{{ $identifierLabel }}</label>
                <input type="text" name="login_id" required 
                    class="w-full mt-1 px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-[#800000] outline-none" 
                    placeholder="{{ $placeholder }}"
                    value="{{ old('login_id') }}">
            </div>
            
            <div>
                <label class="text-xs font-bold text-gray-400 uppercase">Password</label>
                <div class="relative">
                    <input id="password" type="password" name="password" required 
                        class="w-full mt-1 px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-[#800000] outline-none pr-12" 
                        placeholder="Enter your password">
                    
                    <button type="button" onclick="togglePasswordVisibility()" 
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-[#800000] focus:outline-none">
                        <i id="eyeIcon" class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>
            
            <button type="submit" class="w-full py-3 bg-[#800000] text-white font-bold rounded-lg hover:bg-[#660000] transition shadow-lg mb-3">
                Login
            </button>
            
            <a href="{{ route('login') }}" 
               class="block w-full text-center py-3 border-2 border-[#800000]/20 bg-white text-[#800000] font-bold rounded-lg transition-all duration-300 hover:bg-[#FFB800] hover:text-[#800000] hover:border-[#FFB800] shadow-sm text-sm uppercase tracking-tight">
                Back to Role Selection
            </a>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('home') }}" class="text-gray-400 hover:text-[#800000] text-xs font-medium transition flex items-center justify-center">
                <i class="fa-solid fa-arrow-left mr-2"></i> Back to Homepage
            </a>
        </div>
    </div>

    <div class="absolute bottom-6 left-0 right-0 text-center text-white/50 text-xs z-10 px-4">
        Copyright © {{ date('Y') }} | EduRate, Polytechnic University of the Philippines - Main Campus
    </div>
</div>

<script>
    function togglePasswordVisibility() {
        const passwordField = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }
</script>
@endsection