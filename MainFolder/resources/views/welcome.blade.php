@extends('layouts.app')

@section('content')
<nav id="navbar" class="fixed top-0 left-0 right-0 z-50 px-4 md:px-10 py-2 text-white bg-[#660000]/90 backdrop-blur-md shadow-lg transition-all duration-300">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8"> 
            <div>
                <h1 class="font-bold leading-none text-base">EduRate</h1>
                <p class="text-[9px] tracking-tighter uppercase opacity-80">Faculty Evaluation System</p>
            </div>
        </div>
        
        <div class="hidden md:flex items-center space-x-1">
            <a href="{{ route('home') }}" class="bg-white/20 px-4 py-1.5 rounded-lg text-sm font-semibold transition duration-300 hover:bg-white/30">Home</a>
            <a href="{{ route('about') }}" class="px-4 py-1.5 rounded-lg text-sm font-semibold transition duration-300 hover:bg-white/10">About</a>
            <a href="{{ route('how-it-works') }}" class="px-4 py-1.5 rounded-lg text-sm font-medium transition duration-300 hover:bg-white/10">How It Works</a>
            <a href="{{ route('contact') }}" class="px-4 py-1.5 rounded-lg text-sm font-medium transition duration-300 hover:bg-white/10">Contact</a>
            <a href="{{ route('login') }}" class="ml-4 border border-white/40 px-5 py-1.5 rounded-lg text-sm font-bold hover:bg-white hover:text-[#800000] transition duration-300">
                Login
            </a>
        </div>

        <div class="md:hidden flex items-center">
            <button id="mobile-menu-btn" class="text-white focus:outline-none">
                <i class="fa-solid fa-bars text-2xl"></i>
            </button>
        </div>
    </div>

    <div id="mobile-menu" class="hidden md:hidden absolute top-full left-0 w-full bg-[#660000] border-t border-white/10 shadow-xl flex flex-col p-4 space-y-3">
        <a href="{{ route('home') }}" class="block w-full px-4 py-2 rounded-lg bg-white/10 hover:bg-white/20 transition">Home</a>
        <a href="{{ route('about') }}" class="block w-full px-4 py-2 rounded-lg hover:bg-white/10 transition">About</a>
        <a href="{{ route('how-it-works') }}" class="block w-full px-4 py-2 rounded-lg hover:bg-white/10 transition">How It Works</a>
        <a href="{{ route('contact') }}" class="block w-full px-4 py-2 rounded-lg hover:bg-white/10 transition">Contact</a>
        <a href="{{ route('login') }}" class="block w-full text-center border border-white/40 px-4 py-2 rounded-lg font-bold hover:bg-white hover:text-[#800000] transition">
            Login
        </a>
    </div>
</nav>

<section class="relative h-screen bg-cover bg-center" style="background-image: url('{{ asset('images/lagoon.jpg') }}');">
    <div class="absolute inset-0 bg-[#4D0000]/70 flex flex-col items-center justify-center text-center text-white px-4">
        <h2 class="text-4xl md:text-7xl font-bold mb-2">Sukatin ang Galing,</h2>
        <h2 class="text-4xl md:text-7xl font-bold text-yellow-400 italic mb-6">Palakasin ang Edukasyon</h2>
        
        <p class="max-w-2xl text-base md:text-lg mb-8 opacity-90 leading-relaxed">
            Welcome to the Faculty Evaluation System of Polytechnic University of the Philippines - Main Campus. 
            Your feedback matters in shaping the future of education.
        </p>
        <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4 w-full md:w-auto px-6 md:px-0">
            <a href="{{ route('about') }}" class="border-2 border-white py-3 px-8 rounded font-bold hover:bg-white hover:text-[#800000] transition w-full md:w-auto">Learn More</a>
        </div>
    </div>
</section>

<section class="py-16 md:py-20 bg-gray-50">
    <div class="container mx-auto px-6 text-center">
        <h2 class="text-2xl md:text-3xl font-bold text-[#800000] mb-8 md:mb-12">Why Use EduRate?</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 md:gap-8">
            <div class="bg-white p-6 md:p-8 rounded-xl shadow-md transition-all duration-300 hover:-translate-y-3 hover:shadow-2xl border border-transparent hover:border-maroon/10">
                <div class="bg-[#800000] w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-clipboard-check text-white text-2xl"></i>
                </div>
                <h3 class="font-bold text-lg md:text-xl mb-2 text-gray-800">Easy Evaluation</h3>
                <p class="text-gray-600 text-sm">Simple and intuitive evaluation forms for students to rate their faculty members.</p>
            </div>
            <div class="bg-white p-6 md:p-8 rounded-xl shadow-md transition-all duration-300 hover:-translate-y-3 hover:shadow-2xl border border-transparent hover:border-maroon/10">
                <div class="bg-[#800000] w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-users text-white text-2xl"></i>
                </div>
                <h3 class="font-bold text-lg md:text-xl mb-2 text-gray-800">Faculty Development</h3>
                <p class="text-gray-600 text-sm">Help faculty members grow through constructive feedback and performance insights.</p>
            </div>
            <div class="bg-white p-6 md:p-8 rounded-xl shadow-md transition-all duration-300 hover:-translate-y-3 hover:shadow-2xl border border-transparent hover:border-maroon/10">
                <div class="bg-[#800000] w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-chart-bar text-white text-2xl"></i> 
                </div>
                <h3 class="font-bold text-lg md:text-xl mb-2 text-gray-800">Detailed Reports</h3>
                <p class="text-gray-600 text-sm">Comprehensive analytics and reports for administrators to track performance.</p>
            </div>
            <div class="bg-white p-6 md:p-8 rounded-xl shadow-md transition-all duration-300 hover:-translate-y-3 hover:shadow-2xl border border-transparent hover:border-maroon/10">
                <div class="bg-[#800000] w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-user-secret text-white text-2xl"></i>
                </div>
                <h3 class="font-bold text-lg md:text-xl mb-2 text-gray-800">Anonymous & Secure</h3>
                <p class="text-gray-600 text-sm">Student responses are kept anonymous to ensure honest and unbiased feedback.</p>
            </div>
        </div>
    </div>
</section>

<section class="bg-[#800000] py-12 md:py-16 text-center text-white">
    <div class="container mx-auto px-4">
        <h2 class="text-2xl md:text-3xl font-bold mb-4">Access the EduRate Portal</h2>
        <p class="mb-8 opacity-80 max-w-2xl mx-auto leading-relaxed text-sm md:text-base">
            Whether you are a <strong>Student</strong> submitting an evaluation or a <strong>Faculty Member</strong> reviewing performance analytics, log in to access your dashboard.
        </p>
        <a href="{{ route('login') }}" class="inline-block bg-yellow-500 text-[#800000] font-bold py-3 px-10 rounded-lg shadow-md hover:bg-yellow-400 transition duration-300">
            Login Account
        </a>
    </div>
</section>

<footer class="bg-[#660000] text-white pt-12 md:pt-16 pb-6">
    <div class="container mx-auto px-6 md:px-10 grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12">
        <div class="text-center md:text-left">
            <div class="flex items-center justify-center md:justify-start space-x-3 mb-4">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10">
                <div class="text-left">
                    <h4 class="font-bold leading-none">EduRate</h4>
                    <p class="text-xs">Faculty Evaluation System</p>
                </div>
            </div>
            <p class="text-sm opacity-70 leading-relaxed">Polytechnic University of the Philippines - Main Campus </p>
        </div>
        <div class="text-center md:text-left">
            <h4 class="font-bold mb-4">Quick Links</h4>
            <ul class="text-sm space-y-2 opacity-70">
                <li><a href="{{ route('home') }}" class="hover:underline">Home</a></li>
                <li><a href="{{ route('about') }}" class="hover:underline">About</a></li>
                <li><a href="{{ route('how-it-works') }}" class="hover:underline">How It Works</a></li>
                <li><a href="{{ route('contact') }}" class="hover:underline">Contact</a></li>
            </ul>
        </div>
        <div class="text-center md:text-left">
            <h4 class="font-bold mb-4">Contact Us</h4>
            <ul class="text-sm space-y-2 opacity-70 text-xs">
                <li>PUP Main Campus, Sta. Mesa Manila, Philippines</li>
                <li class="pt-2">Email: edurate@pup.edu.ph</li>
            </ul>
        </div>
    </div>
    <div class="border-t border-white/10 mt-12 md:mt-16 pt-6 text-center text-xs opacity-50 px-4">
        Copyright © 2026 | EduRate, Polytechnic University of the Philippines
    </div>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');

        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    });
</script>
@endsection