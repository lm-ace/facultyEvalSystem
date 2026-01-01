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
        <a href="{{ route('how-it-works') }}" class="bg-white/20 px-4 py-1.5 rounded-lg text-sm font-semibold transition hover:bg-white/30">How It Works</a>
        
        <a href="{{ route('contact') }}" class="px-4 py-1.5 rounded-lg text-sm font-medium transition hover:bg-white/10">Contact</a>
        
        <a href="{{ route('login') }}" class="ml-4 border border-white/40 px-5 py-1.5 rounded-lg text-sm font-bold hover:bg-white hover:text-[#800000] transition">Login</a>
    </div>
</nav>

<section class="relative h-[45vh] bg-cover bg-center" style="background-image: url('{{ asset('images/lagoon.jpg') }}');">
    <div class="absolute inset-0 bg-[#4D0000]/70 flex flex-col items-center justify-center text-center text-white px-4">
        <h2 class="text-4xl md:text-5xl font-bold mb-2 text-white">How It Works</h2>
        <p class="text-lg opacity-90 max-w-2xl text-yellow-400 font-medium">A step-by-step guide to completing your faculty evaluation</p>
    </div>
</section>

<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-6 max-w-4xl">
        <div class="relative space-y-8">
            <div class="absolute left-8 top-0 bottom-0 w-0.5 bg-gray-200 hidden md:block"></div>

            <div class="relative flex flex-col md:flex-row items-center md:items-start space-y-4 md:space-y-0 md:space-x-8">
                <div class="z-10 bg-[#800000] w-16 h-16 rounded-full flex items-center justify-center shadow-lg shrink-0">
                    <i class="fa-solid fa-right-to-bracket text-white text-2xl"></i>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md flex-grow border border-gray-100 transition hover:shadow-xl">
                    <span class="text-[#800000] font-bold text-xs uppercase tracking-widest">Step 1</span>
                    <h3 class="text-xl font-bold text-gray-800 mt-1">Login to Your Account</h3>
                    <p class="text-gray-600 text-sm mt-2">Access the system using your student credentials. If you don't have an account, contact your administrator.</p>
                </div>
            </div>

            <div class="relative flex flex-col md:flex-row items-center md:items-start space-y-4 md:space-y-0 md:space-x-8">
                <div class="z-10 bg-[#800000] w-16 h-16 rounded-full flex items-center justify-center shadow-lg shrink-0">
                    <i class="fa-solid fa-clipboard-list text-white text-2xl"></i>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md flex-grow border border-gray-100 transition hover:shadow-xl">
                    <span class="text-[#800000] font-bold text-xs uppercase tracking-widest">Step 2</span>
                    <h3 class="text-xl font-bold text-gray-800 mt-1">Select Faculty to Evaluate</h3>
                    <p class="text-gray-600 text-sm mt-2">View the list of your enrolled subjects and the corresponding faculty members assigned to evaluate.</p>
                </div>
            </div>

            <div class="relative flex flex-col md:flex-row items-center md:items-start space-y-4 md:space-y-0 md:space-x-8">
                <div class="z-10 bg-[#800000] w-16 h-16 rounded-full flex items-center justify-center shadow-lg shrink-0">
                    <i class="fa-solid fa-star text-white text-2xl"></i>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md flex-grow border border-gray-100 transition hover:shadow-xl">
                    <span class="text-[#800000] font-bold text-xs uppercase tracking-widest">Step 3</span>
                    <h3 class="text-xl font-bold text-gray-800 mt-1">Complete the Evaluation Form</h3>
                    <p class="text-gray-600 text-sm mt-2">Answer the evaluation questions honestly. Rate each criterion and provide constructive feedback if desired.</p>
                </div>
            </div>

            <div class="relative flex flex-col md:flex-row items-center md:items-start space-y-4 md:space-y-0 md:space-x-8">
                <div class="z-10 bg-[#800000] w-16 h-16 rounded-full flex items-center justify-center shadow-lg shrink-0">
                    <i class="fa-solid fa-circle-check text-white text-2xl"></i>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md flex-grow border border-gray-100 transition hover:shadow-xl">
                    <span class="text-[#800000] font-bold text-xs uppercase tracking-widest">Step 4</span>
                    <h3 class="text-xl font-bold text-gray-800 mt-1">Submit Your Evaluation</h3>
                    <p class="text-gray-600 text-sm mt-2">Review your responses and submit the evaluation. Your feedback is anonymous and confidential.</p>
                </div>
            </div>
        </div>

        <div class="mt-12 bg-white p-10 rounded-xl shadow-lg border-l-8 border-yellow-500">
            <h3 class="text-2xl font-bold text-[#800000] mb-6">Important Reminders</h3>
            <ul class="space-y-3">
                <li class="flex items-center text-gray-700 text-sm">
                    <span class="text-yellow-600 mr-2">•</span> Evaluation period is announced by the administration
                </li>
                <li class="flex items-center text-gray-700 text-sm">
                    <span class="text-yellow-600 mr-2">•</span> You can only evaluate faculty members from your enrolled subjects
                </li>
                <li class="flex items-center text-gray-700 text-sm">
                    <span class="text-yellow-600 mr-2">•</span> All responses are anonymous and confidential
                </li>
                <li class="flex items-center text-gray-700 text-sm">
                    <span class="text-yellow-600 mr-2">•</span> Be honest and constructive with your feedback
                </li>
                <li class="flex items-center text-gray-700 text-sm">
                    <span class="text-yellow-600 mr-2">•</span> Once submitted, evaluations cannot be modified
                </li>
            </ul>
        </div>
    </div>
</section>

<footer class="bg-[#660000] text-white pt-16 pb-6">
    <div class="container mx-auto px-10 grid grid-cols-1 md:grid-cols-3 gap-12 text-left">
        <div>
            <div class="flex items-center space-x-3 mb-4">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10">
                <div><h4 class="font-bold leading-none">EduRate</h4><p class="text-xs">Faculty Evaluation System</p></div>
            </div>
            <p class="text-sm opacity-70">Polytechnic University of the Philippines - Main Campus</p>
        </div>
        <div>
            <h4 class="font-bold mb-4">Quick Links</h4>
            <ul class="text-sm space-y-2 opacity-70">
                <li><a href="{{ route('home') }}" class="hover:underline hover:text-yellow-400 transition">Home</a></li>
                <li><a href="{{ route('about') }}" class="hover:underline hover:text-yellow-400 transition">About</a></li>
                <li><a href="{{ route('how-it-works') }}" class="hover:underline hover:text-yellow-400 transition">How It Works</a></li>
                <li><a href="{{ route('contact') }}" class="hover:underline hover:text-yellow-400 transition">Contact</a></li>
            </ul>
        </div>
        <div>
            <h4 class="font-bold mb-4">Contact Us</h4>
            <ul class="text-sm space-y-2 opacity-70">
                <li>PUP Main Campus, Sta. Mesa Manila</li>
                <li>Email: <a href="mailto:edurate@pup.edu.ph" class="hover:underline">edurate@pup.edu.ph</a></li>
            </ul>
        </div>
    </div>
    <div class="border-t border-white/10 mt-16 pt-6 text-center text-xs opacity-50">
        Copyright © 2026 | EduRate, Polytechnic University of the Philippines
    </div>
</footer>
@endsection