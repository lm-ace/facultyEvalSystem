@extends('layouts.app')
@section('title', 'About Us')
@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

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
            <a href="{{ route('about') }}" class="bg-white/20 px-4 py-1.5 rounded-lg text-sm font-semibold transition hover:bg-white/30">About</a>
            <a href="{{ route('how-it-works') }}" class="px-4 py-1.5 rounded-lg text-sm font-medium transition hover:bg-white/10">How It Works</a>
            <a href="{{ route('contact') }}" class="px-4 py-1.5 rounded-lg text-sm font-medium transition hover:bg-white/10">Contact</a>
            <a href="{{ route('login') }}" class="ml-4 border border-white/40 px-5 py-1.5 rounded-lg text-sm font-bold hover:bg-white hover:text-[#800000] transition">Login</a>
        </div>

        <div class="md:hidden flex items-center">
            <button id="mobile-menu-btn" class="text-white focus:outline-none">
                <i class="fa-solid fa-bars text-2xl"></i>
            </button>
        </div>
    </div>

    <div id="mobile-menu" class="hidden md:hidden absolute top-full left-0 w-full bg-[#660000] border-t border-white/10 shadow-xl flex flex-col p-4 space-y-3">
        <a href="{{ route('home') }}" class="block w-full px-4 py-2 rounded-lg hover:bg-white/10 transition">Home</a>
        <a href="{{ route('about') }}" class="block w-full px-4 py-2 rounded-lg bg-white/10 hover:bg-white/20 transition">About</a>
        <a href="{{ route('how-it-works') }}" class="block w-full px-4 py-2 rounded-lg hover:bg-white/10 transition">How It Works</a>
        <a href="{{ route('contact') }}" class="block w-full px-4 py-2 rounded-lg hover:bg-white/10 transition">Contact</a>
        <a href="{{ route('login') }}" class="block w-full text-center border border-white/40 px-4 py-2 rounded-lg font-bold hover:bg-white hover:text-[#800000] transition">
            Login
        </a>
    </div>
</nav>

<section class="relative h-[40vh] md:h-[50vh] bg-cover bg-center mt-12 md:mt-0" style="background-image: url('{{ asset('images/lagoon.jpg') }}');">
    <div class="absolute inset-0 bg-[#4D0000]/70 flex flex-col items-center justify-center text-center text-white px-4">
        <h2 class="text-3xl md:text-5xl font-bold mb-2">What is EduRate?</h2>
        <p class="text-base md:text-lg opacity-90 max-w-2xl text-yellow-400 font-medium tracking-wide">The official Faculty Evaluation System of PUP Main Campus</p>
    </div>
</section>

<div class="bg-gray-50 py-12 md:py-16">
    <div class="container mx-auto px-4 md:px-6 max-w-5xl">
        
        <div class="bg-white p-6 md:p-10 rounded-xl shadow-lg mb-8 md:mb-10 border-t-4 border-[#800000]">
            <h3 class="text-xl md:text-2xl font-bold text-[#800000] mb-4 text-center md:text-left">About the System</h3>
            <p class="text-gray-700 leading-relaxed mb-4 text-sm md:text-base text-justify md:text-left">
                EduRate is the Faculty Evaluation System designed specifically for the Polytechnic University of the Philippines - Main Campus. This system enables students to provide valuable feedback about their professors and instructors in a secure and anonymous manner.
            </p>
            <p class="text-gray-700 leading-relaxed text-sm md:text-base text-justify md:text-left">
                The evaluation results help the administration identify areas for improvement and recognize outstanding teaching performance. By participating in the evaluation process, students contribute directly to the enhancement of educational quality at PUP Main Campus.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 mb-8 md:mb-10">
            <div class="bg-white p-6 md:p-8 rounded-xl shadow-lg border-l-8 border-[#800000] hover:scale-[1.02] transition-transform duration-300">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="text-[#800000] text-2xl md:text-3xl">
                        <i class="fa-solid fa-bullseye"></i>
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-gray-800">Mission</h3>
                </div>
                <p class="text-gray-600 text-sm leading-relaxed text-justify md:text-left">
                    To provide a reliable, transparent, and efficient platform for evaluating faculty performance that promotes academic excellence and continuous improvement in teaching methodologies.
                </p>
            </div>

            <div class="bg-white p-6 md:p-8 rounded-xl shadow-lg border-l-8 border-yellow-500 hover:scale-[1.02] transition-transform duration-300">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="text-yellow-500 text-2xl md:text-3xl">
                        <i class="fa-solid fa-eye"></i>
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-gray-800">Vision</h3>
                </div>
                <p class="text-gray-600 text-sm leading-relaxed text-justify md:text-left">
                    To be the leading faculty evaluation system that fosters a culture of excellence, accountability, and continuous learning within the PUP system.
                </p>
            </div>
        </div>

        <div class="bg-white p-6 md:p-10 rounded-xl shadow-lg mb-16">
            <h3 class="text-xl md:text-2xl font-bold text-[#800000] mb-6">Objectives</h3>
            <ul class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <li class="flex items-start space-x-3">
                    <i class="fa-solid fa-circle-check text-[#800000] mt-1 shrink-0"></i>
                    <span class="text-gray-700 text-sm md:text-base">Provide a structured and fair evaluation process for all faculty members</span>
                </li>
                <li class="flex items-start space-x-3">
                    <i class="fa-solid fa-circle-check text-[#800000] mt-1 shrink-0"></i>
                    <span class="text-gray-700 text-sm md:text-base">Collect constructive feedback from students to improve teaching quality</span>
                </li>
                <li class="flex items-start space-x-3">
                    <i class="fa-solid fa-circle-check text-[#800000] mt-1 shrink-0"></i>
                    <span class="text-gray-700 text-sm md:text-base">Generate comprehensive reports for academic administrators</span>
                </li>
                <li class="flex items-start space-x-3">
                    <i class="fa-solid fa-circle-check text-[#800000] mt-1 shrink-0"></i>
                    <span class="text-gray-700 text-sm md:text-base">Ensure anonymity and confidentiality of student responses</span>
                </li>
                <li class="flex items-start space-x-3">
                    <i class="fa-solid fa-circle-check text-[#800000] mt-1 shrink-0"></i>
                    <span class="text-gray-700 text-sm md:text-base">Support continuous professional development of faculty members</span>
                </li>
            </ul>
        </div>

        <div class="text-center">
            <div class="inline-block border-b-4 border-yellow-500 pb-2 mb-10">
                <h3 class="text-2xl md:text-3xl font-bold text-[#800000]">System Developers</h3>
            </div>
            
            <div class="flex flex-wrap justify-center gap-8">
                
                <div class="dev-card w-full md:w-[30%] bg-white rounded-xl shadow-lg border-t-4 border-[#800000] hover:-translate-y-2 transition-all duration-300 flex flex-col items-center group overflow-hidden">
                    <div class="p-6 pb-2 flex flex-col items-center w-full z-10 bg-white">
                        <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-gray-200 group-hover:border-yellow-500 transition-colors duration-300 mb-4 shadow-md">
                            <img src="{{ asset('images/lebron.jpg') }}" alt="Leo Miguel B. Aceitunas" class="w-full h-full object-cover">
                        </div>
                        <h4 class="font-bold text-gray-800 text-lg">Leo Miguel B. Aceitunas</h4>
                        <p class="text-[#800000] font-medium text-sm mt-1">System Project Manager</p>
                    </div>

                    <div class="w-full bg-gray-50 border-t border-gray-100 py-4">
                        <div class="flex items-center justify-center space-x-5">
                            <a href="https://facebook.com" target="_blank" class="text-[#800000] hover:text-yellow-500 transform hover:scale-125 transition-all" title="Facebook">
                                <i class="fa-brands fa-facebook text-xl"></i>
                            </a>
                            <a href="#" target="_blank" class="text-[#800000] hover:text-yellow-500 transform hover:scale-125 transition-all" title="LinkedIn">
                                <i class="fa-brands fa-linkedin text-xl"></i>
                            </a>
                            <a href="#" target="_blank" class="text-[#800000] hover:text-yellow-500 transform hover:scale-125 transition-all" title="GitHub">
                                <i class="fa-brands fa-github text-xl"></i>
                            </a>
                            <a href="mailto:email@pup.edu.ph" class="text-[#800000] hover:text-yellow-500 transform hover:scale-125 transition-all" title="Email">
                                <i class="fa-solid fa-envelope text-xl"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="dev-card w-full md:w-[30%] bg-white rounded-xl shadow-lg border-t-4 border-[#800000] hover:-translate-y-2 transition-all duration-300 flex flex-col items-center group overflow-hidden">
                    <div class="p-6 pb-2 flex flex-col items-center w-full z-10 bg-white">
                        <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-gray-200 group-hover:border-yellow-500 transition-colors duration-300 mb-4 shadow-md">
                            <img src="{{ asset('images/mhaine.jpg') }}" alt="Jermaine M. Sebido" class="w-full h-full object-cover">
                        </div>
                        <h4 class="font-bold text-gray-800 text-lg">Jermaine M. Sebido</h4>
                        <p class="text-[#800000] font-medium text-sm mt-1">Full-stack Developer</p>
                    </div>

                    <div class="w-full bg-gray-50 border-t border-gray-100 py-4">
                        <div class="flex items-center justify-center space-x-5">
                            <a href="https://facebook.com" target="_blank" class="text-[#800000] hover:text-yellow-500 transform hover:scale-125 transition-all" title="Facebook">
                                <i class="fa-brands fa-facebook text-xl"></i>
                            </a>
                            <a href="#" target="_blank" class="text-[#800000] hover:text-yellow-500 transform hover:scale-125 transition-all" title="LinkedIn">
                                <i class="fa-brands fa-linkedin text-xl"></i>
                            </a>
                            <a href="#" target="_blank" class="text-[#800000] hover:text-yellow-500 transform hover:scale-125 transition-all" title="GitHub">
                                <i class="fa-brands fa-github text-xl"></i>
                            </a>
                            <a href="mailto:email@pup.edu.ph" class="text-[#800000] hover:text-yellow-500 transform hover:scale-125 transition-all" title="Email">
                                <i class="fa-solid fa-envelope text-xl"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="dev-card w-full md:w-[30%] bg-white rounded-xl shadow-lg border-t-4 border-[#800000] hover:-translate-y-2 transition-all duration-300 flex flex-col items-center group overflow-hidden">
                    <div class="p-6 pb-2 flex flex-col items-center w-full z-10 bg-white">
                        <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-gray-200 group-hover:border-yellow-500 transition-colors duration-300 mb-4 shadow-md">
                            <img src="{{ asset('images/razi.JPG') }}" alt="Raziela T. Calapatia" class="w-full h-full object-cover">
                        </div>
                        <h4 class="font-bold text-gray-800 text-lg">Raziela T. Calapatia</h4>
                        <p class="text-[#800000] font-medium text-sm mt-1">Front-end Developer</p>
                    </div>

                    <div class="w-full bg-gray-50 border-t border-gray-100 py-4">
                        <div class="flex items-center justify-center space-x-5">
                            <a href="https://facebook.com" target="_blank" class="text-[#800000] hover:text-yellow-500 transform hover:scale-125 transition-all" title="Facebook">
                                <i class="fa-brands fa-facebook text-xl"></i>
                            </a>
                            <a href="#" target="_blank" class="text-[#800000] hover:text-yellow-500 transform hover:scale-125 transition-all" title="LinkedIn">
                                <i class="fa-brands fa-linkedin text-xl"></i>
                            </a>
                            <a href="#" target="_blank" class="text-[#800000] hover:text-yellow-500 transform hover:scale-125 transition-all" title="GitHub">
                                <i class="fa-brands fa-github text-xl"></i>
                            </a>
                            <a href="mailto:email@pup.edu.ph" class="text-[#800000] hover:text-yellow-500 transform hover:scale-125 transition-all" title="Email">
                                <i class="fa-solid fa-envelope text-xl"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="dev-card w-full md:w-[30%] bg-white rounded-xl shadow-lg border-t-4 border-[#800000] hover:-translate-y-2 transition-all duration-300 flex flex-col items-center group overflow-hidden">
                    <div class="p-6 pb-2 flex flex-col items-center w-full z-10 bg-white">
                        <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-gray-200 group-hover:border-yellow-500 transition-colors duration-300 mb-4 shadow-md">
                            <img src="{{ asset('images/zell.jpg') }}" alt="Zellphie M. Dela" class="w-full h-full object-cover">
                        </div>
                        <h4 class="font-bold text-gray-800 text-lg">Zellphie M. Dela</h4>
                        <p class="text-[#800000] font-medium text-sm mt-1">Back-end Developer</p>
                    </div>

                    <div class="w-full bg-gray-50 border-t border-gray-100 py-4">
                        <div class="flex items-center justify-center space-x-5">
                            <a href="https://facebook.com" target="_blank" class="text-[#800000] hover:text-yellow-500 transform hover:scale-125 transition-all" title="Facebook">
                                <i class="fa-brands fa-facebook text-xl"></i>
                            </a>
                            <a href="#" target="_blank" class="text-[#800000] hover:text-yellow-500 transform hover:scale-125 transition-all" title="LinkedIn">
                                <i class="fa-brands fa-linkedin text-xl"></i>
                            </a>
                            <a href="#" target="_blank" class="text-[#800000] hover:text-yellow-500 transform hover:scale-125 transition-all" title="GitHub">
                                <i class="fa-brands fa-github text-xl"></i>
                            </a>
                            <a href="mailto:email@pup.edu.ph" class="text-[#800000] hover:text-yellow-500 transform hover:scale-125 transition-all" title="Email">
                                <i class="fa-solid fa-envelope text-xl"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="dev-card w-full md:w-[30%] bg-white rounded-xl shadow-lg border-t-4 border-[#800000] hover:-translate-y-2 transition-all duration-300 flex flex-col items-center group overflow-hidden">
                    <div class="p-6 pb-2 flex flex-col items-center w-full z-10 bg-white">
                        <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-gray-200 group-hover:border-yellow-500 transition-colors duration-300 mb-4 shadow-md bg-gray-100 flex items-center justify-center">
                            <img src="{{ asset('images/Taroma.jpg') }}" alt="Yra Louisse A. Taroma" class="w-full h-full object-cover">
                        </div>
                        <h4 class="font-bold text-gray-800 text-lg">Yra Louisse A. Taroma</h4>
                        <p class="text-[#800000] font-medium text-sm mt-1">Back-end Developer</p>
                    </div>

                    <div class="w-full bg-gray-50 border-t border-gray-100 py-4">
                        <div class="flex items-center justify-center space-x-5">
                            <a href="https://facebook.com" target="_blank" class="text-[#800000] hover:text-yellow-500 transform hover:scale-125 transition-all" title="Facebook">
                                <i class="fa-brands fa-facebook text-xl"></i>
                            </a>
                            <a href="#" target="_blank" class="text-[#800000] hover:text-yellow-500 transform hover:scale-125 transition-all" title="LinkedIn">
                                <i class="fa-brands fa-linkedin text-xl"></i>
                            </a>
                            <a href="#" target="_blank" class="text-[#800000] hover:text-yellow-500 transform hover:scale-125 transition-all" title="GitHub">
                                <i class="fa-brands fa-github text-xl"></i>
                            </a>
                            <a href="mailto:email@pup.edu.ph" class="text-[#800000] hover:text-yellow-500 transform hover:scale-125 transition-all" title="Email">
                                <i class="fa-solid fa-envelope text-xl"></i>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<footer class="bg-[#660000] text-white pt-12 md:pt-16 pb-6 relative z-20">
    <div class="container mx-auto px-6 md:px-10 grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12 text-center md:text-left">
        <div>
            <div class="flex items-center justify-center md:justify-start space-x-3 mb-4">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10">
                <div class="text-left"><h4 class="font-bold leading-none">EduRate</h4><p class="text-xs">Faculty Evaluation System</p></div>
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
    <div class="border-t border-white/10 mt-12 md:mt-16 pt-6 text-center text-xs opacity-50 px-4">
        Copyright © 2026 | EduRate, Polytechnic University of the Philippines - Main Campus
    </div>
</footer>

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
@endsection