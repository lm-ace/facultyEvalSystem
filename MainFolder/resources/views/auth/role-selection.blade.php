@extends('layouts.app')

@section('content')
<nav class="fixed top-0 left-0 right-0 z-50 px-4 md:px-10 py-2 text-white bg-[#660000]/90 backdrop-blur-md shadow-lg transition-all duration-300">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8"> 
            <div>
                <h1 class="font-bold leading-none text-base">EduRate</h1>
                <p class="text-[9px] tracking-tighter uppercase opacity-80">Faculty Evaluation System</p>
            </div>
        </div>
        
        <div class="hidden md:flex items-center space-x-1">
            <a href="{{ route('home') }}" class="px-4 py-1.5 rounded-lg text-sm font-medium transition hover:bg-white/10">Home</a>
            <a href="{{ route('about') }}" class="px-4 py-1.5 rounded-lg text-sm font-medium transition hover:bg-white/10">About</a>
            <a href="{{ route('how-it-works') }}" class="px-4 py-1.5 rounded-lg text-sm font-medium transition hover:bg-white/10">How It Works</a>
            <a href="{{ route('contact') }}" class="px-4 py-1.5 rounded-lg text-sm font-medium transition hover:bg-white/10">Contact</a>
        </div>

        <div class="md:hidden flex items-center">
            <button id="mobile-menu-btn" class="text-white focus:outline-none">
                <i class="fa-solid fa-bars text-2xl"></i>
            </button>
        </div>
    </div>

    <div id="mobile-menu" class="hidden md:hidden absolute top-full left-0 w-full bg-[#660000] border-t border-white/10 shadow-xl flex flex-col p-4 space-y-3">
        <a href="{{ route('home') }}" class="block w-full px-4 py-2 rounded-lg hover:bg-white/10 transition">Home</a>
        <a href="{{ route('about') }}" class="block w-full px-4 py-2 rounded-lg hover:bg-white/10 transition">About</a>
        <a href="{{ route('how-it-works') }}" class="block w-full px-4 py-2 rounded-lg hover:bg-white/10 transition">How It Works</a>
        <a href="{{ route('contact') }}" class="block w-full px-4 py-2 rounded-lg hover:bg-white/10 transition">Contact</a>
    </div>
</nav>

<div class="relative min-h-screen flex items-center justify-center bg-cover bg-center pt-16 md:pt-0" style="background-image: url('{{ asset('images/lagoon.jpg') }}');">
    <div class="absolute inset-0 bg-[#4D0000]/70"></div>

    <div class="relative z-10 w-full max-w-md bg-white rounded-xl shadow-2xl p-6 md:p-8 mx-4 my-8 md:my-0 animate-fade-in-up">
        
        <div class="text-center mb-6 md:mb-8">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-14 md:h-16 mx-auto mb-4">
            <h2 class="text-xl md:text-2xl font-bold text-gray-800">Hi, PUPian!</h2>
            <p class="text-gray-500 text-xs md:text-sm">Select your role to continue</p>
        </div>

        @if (session('status'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 flex items-start space-x-3 shadow-sm">
            <div class="shrink-0">
                <i class="fa-solid fa-circle-check text-green-600 mt-0.5 text-lg"></i>
            </div>
            <div>
                <h4 class="text-sm font-bold text-green-800">Success!</h4>
                <p class="text-xs text-green-700 mt-1">{{ session('status') }}</p>
            </div>
        </div>
        @endif

        @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 flex items-start space-x-3 shadow-sm">
            <div class="shrink-0">
                <i class="fa-solid fa-circle-exclamation text-red-600 mt-0.5 text-lg"></i>
            </div>
            <div>
                <h4 class="text-sm font-bold text-red-800">Notice</h4>
                <ul class="list-disc list-inside text-xs text-red-700 mt-1">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <div class="space-y-3 md:space-y-4">
            <a href="{{ route('login.role', 'student') }}" class="flex items-center p-3 md:p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition border border-transparent hover:border-[#800000]/20 group active:scale-[0.98]">
                <div class="bg-[#800000] w-10 h-10 md:w-12 md:h-12 rounded-full flex items-center justify-center text-white mr-4 shadow-md group-hover:scale-110 transition-transform shrink-0">
                    <i class="fa-solid fa-graduation-cap text-lg md:text-xl"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800 text-sm md:text-base">Student</h4>
                    <p class="text-[10px] md:text-xs text-gray-500">Evaluate your faculty members</p>
                </div>
                <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity text-gray-400">
                    <i class="fa-solid fa-chevron-right text-sm"></i>
                </div>
            </a>

            <a href="{{ route('login.role', 'faculty') }}" class="flex items-center p-3 md:p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition border border-transparent hover:border-[#800000]/20 group active:scale-[0.98]">
                <div class="bg-[#800000] w-10 h-10 md:w-12 md:h-12 rounded-full flex items-center justify-center text-white mr-4 shadow-md group-hover:scale-110 transition-transform shrink-0">
                    <i class="fa-solid fa-user-tie text-lg md:text-xl"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800 text-sm md:text-base">Faculty</h4>
                    <p class="text-[10px] md:text-xs text-gray-500">View your evaluation results</p>
                </div>
                <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity text-gray-400">
                    <i class="fa-solid fa-chevron-right text-sm"></i>
                </div>
            </a>

            <a href="{{ route('login.role', 'admin') }}" class="flex items-center p-3 md:p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition border border-transparent hover:border-[#800000]/20 group active:scale-[0.98]">
                <div class="bg-[#800000] w-10 h-10 md:w-12 md:h-12 rounded-full flex items-center justify-center text-white mr-4 shadow-md group-hover:scale-110 transition-transform shrink-0">
                    <i class="fa-solid fa-shield-halved text-lg md:text-xl"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800 text-sm md:text-base">Administrator</h4>
                    <p class="text-[10px] md:text-xs text-gray-500">Manage the evaluation system</p>
                </div>
                <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity text-gray-400">
                    <i class="fa-solid fa-chevron-right text-sm"></i>
                </div>
            </a>
        </div>

        <div class="mt-6 md:mt-8 text-center">
            <a href="{{ route('home') }}" class="text-gray-400 hover:text-[#800000] text-xs md:text-sm font-medium transition flex items-center justify-center group">
                <i class="fa-solid fa-arrow-left mr-2 transition-transform group-hover:-translate-x-1"></i> Back to Homepage
            </a>
        </div>
    </div>

    <div class="absolute bottom-4 md:bottom-6 left-0 right-0 text-center text-white/50 text-[10px] md:text-xs z-10 px-4">
        Copyright © {{ date('Y') }} | EduRate, Polytechnic University of the Philippines - Main Campus
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');

        if(btn && menu){
            btn.addEventListener('click', () => {
                menu.classList.toggle('hidden');
            });
        }
    });
</script>

<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translate3d(0, 20px, 0);
        }
        to {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.5s ease-out;
    }
</style>
@endsection